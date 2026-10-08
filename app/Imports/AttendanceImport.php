<?php

namespace App\Imports;

use App\Models\Attendance;
use App\Models\User;
use Modules\CourseSetting\Entities\CourseEnrolled;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class AttendanceImport implements ToCollection
{
    protected $session;
    protected $processedUsers = [];

    public $total = 0;
    public $imported = 0;
    public $notFound = 0;
    public $notEnrolled = 0;
    public $invalidStatus = 0;
    public $duplicateInFile = 0;
    public $alreadyExists = 0;

    public function __construct($session)
    {
        $this->session = $session;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {

            if ($index == 0) continue; // Skip header

            $this->total++;

            $email  = trim($row[0] ?? null);
            $status = strtolower(trim($row[1] ?? ''));

            if (!in_array($status, ['Attended', 'Not Attended'])) {
                $this->invalidStatus++;
                continue;
            }

            $user = User::where('email', $email)
                ->where('organization_id', $this->session->organization_id)
                ->first();

            if (!$user) {
                $this->notFound++;
                continue;
            }

            // Prevent duplicate inside same Excel file
            if (in_array($user->id, $this->processedUsers)) {
                $this->duplicateInFile++;
                continue;
            }

            $this->processedUsers[] = $user->id;

            $isEnrolled = CourseEnrolled::where('course_id', $this->session->course_id)
                ->where('user_id', $user->id)
                ->exists();

            if (!$isEnrolled) {
                $this->notEnrolled++;
                continue;
            }

            // Check if attendance already exists in DB
            $exists = Attendance::where('attendance_session_id', $this->session->id)
                ->where('user_id', $user->id)
                ->exists();

            if ($exists) {
                $this->alreadyExists++;
                continue; // Skip instead of updating
            }

            Attendance::create([
                'attendance_session_id' => $this->session->id,
                'user_id' => $user->id,
                'status' => $status,
            ]);

            $this->imported++;
        }
    }

    public function getSummary()
    {
        return [
            'total' => $this->total,
            'imported' => $this->imported,
            'not_found' => $this->notFound,
            'not_enrolled' => $this->notEnrolled,
            'invalid_status' => $this->invalidStatus,
            'duplicate_in_file' => $this->duplicateInFile,
            'already_exists' => $this->alreadyExists,
        ];
    }
}