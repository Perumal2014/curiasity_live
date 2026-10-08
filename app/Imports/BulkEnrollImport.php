<?php 

namespace App\Imports;
use App\User;
use Modules\CourseSetting\Entities\CourseEnrolled;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Course;
use App\Models\CourseWaitingList;
use Modules\CourseSetting\Entities\CourseApproval;
use Illuminate\Http\Request;
use Modules\Payment\Entities\Cart;
use Illuminate\Support\Facades\DB;

class BulkEnrollImport implements ToCollection, WithStartRow, WithHeadingRow
{
    protected $course_id;   // ← property must be here

    public function __construct($course_id)
    {
        $this->course_id = $course_id;
    }

    public function collection(Collection $items)
    {
        $courseId = $this->course_id;
        $course = Course::find($courseId);

        DB::transaction(function () use ($items, $courseId, $course) {

            foreach ($items as $row) {

                if (empty($row['employee_id'])) {
                    continue;
                }

                $user = User::where('employee_id', $row['employee_id'])->first();
                if (!$user) {
                    continue;
                }

                $enrolledCount = CourseEnrolled::where('course_id', $courseId)
                    ->where('status', 1)
                    ->count();

                if ($course->access_limit > 0 && $enrolledCount >= $course->access_limit) {

                    $position = CourseWaitingList::where('course_id', $courseId)->max('position');
                    $position = $position ? $position + 1 : 1;

                    CourseWaitingList::create([
                        'course_id' => $courseId,
                        'user_id' => $user->id,
                        'organization_id' => auth()->user()->organization_id,
                        'tenant_id' => auth()->user()->tenant_id,
                        'position' => $position,
                        'status' => 1
                    ]);

                } else {

                    CourseApproval::updateOrCreate(
                        [
                            'user_id' => $user->id,
                            'course_id' => $courseId,
                        ],
                        [
                            'status' => 1,
                            'updated_at' => now(),
                        ]
                    );

                    $cart = Cart::create([
                        'user_id' => $user->id,
                        'instructor_id' => $user->id,
                        'course_id' => $courseId,
                        'tracking' => getTrx(),
                        'price' => 0
                    ]);

                    CourseEnrolled::create([
                        'user_id' => $user->id,
                        'tracking' => $cart->tracking,
                        'course_id' => $courseId,
                        'purchase_price' => 0,
                        'status' => 1,
                        'lms_id' => 1
                    ]);
                }
            }

        });
    }

    public function startRow(): int
    {
        return 2;
    }

    public function headingRow(): int
    {
        return 1;
    }
}