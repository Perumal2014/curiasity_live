<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use App\Models\UserLearningPlanSchedule;
use App\Models\LearningPlanReminder;

use Modules\CourseSetting\Entities\Course;
use Modules\CourseSetting\Entities\CourseEnrolled;
use Modules\CourseSetting\Entities\Lesson;

class SendLearningPlanReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:learning-plan-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send learning plan reminders';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $today = Carbon::today()->format('Y-m-d');

        /*
        |--------------------------------------------------------------------------
        | Get Pending Schedules
        |--------------------------------------------------------------------------
        */

        $schedules = UserLearningPlanSchedule::where(
            'status',
            'pending'
        )->get();

        if ($schedules->isEmpty()) {

            $this->info('No schedules found.');

            return;
        }

        foreach ($schedules as $schedule) {

            /*
            |--------------------------------------------------------------------------
            | Get User
            |--------------------------------------------------------------------------
            */

            $user = $schedule->user;

            if (!$user) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Get Course
            |--------------------------------------------------------------------------
            */

            $course = Course::find($schedule->course_id);

            if (!$course) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Get Enrollment
            |--------------------------------------------------------------------------
            */

            $enrollment = CourseEnrolled::where(
                'user_id',
                $schedule->user_id
            )
            ->where(
                'course_id',
                $schedule->course_id
            )
            ->first();

            if (!$enrollment) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Check Lesson Completion
            |--------------------------------------------------------------------------
            */

            /*
            |--------------------------------------------------------------------------
            | Total Lessons Count
            |--------------------------------------------------------------------------
            */

            $totalLessons = Lesson::where(
                'course_id',
                $schedule->course_id
            )->count();

            /*
            |--------------------------------------------------------------------------
            | Completed Lessons Count
            |--------------------------------------------------------------------------
            */

            $completedLessons = DB::table('lesson_completes')
                ->where(
                    'course_id',
                    $schedule->course_id
                )
                ->where(
                    'user_id',
                    $schedule->user_id
                )
                ->where(
                    'status',
                    1
                )
                ->count();

            /*
            |--------------------------------------------------------------------------
            | All Lessons Completed
            |--------------------------------------------------------------------------
            */

            if (
                $totalLessons > 0 &&
                $totalLessons == $completedLessons
            ) {

                /*
                |--------------------------------------------------------------------------
                | Update Enrollment Status
                |--------------------------------------------------------------------------
                */

                $enrollment->status = 1;

                $enrollment->save();

                /*
                |--------------------------------------------------------------------------
                | Update Schedule Status
                |--------------------------------------------------------------------------
                */

                $schedule->status = 'completed';

                $schedule->save();

                $this->info(
                    "Course completed for User {$user->id}"
                );

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Reminder Types
            |--------------------------------------------------------------------------
            */

            $reminderTypes = LearningPlanReminder::where(
                'learning_plan_id',
                $schedule->learning_plan_id
            )
            ->pluck('reminder_type')
            ->toArray();

            /*
            |--------------------------------------------------------------------------
            | BEFORE DEADLINE
            |--------------------------------------------------------------------------
            */

            if (
                in_array(2, $reminderTypes) &&
                !$schedule->reminder_before_sent &&
                $schedule->before_reminder_date == $today
            ) {

                course_allocation_notification(
                    $user,
                    'course_reminder_notification',
                    [

                        'email' => $user->email,

                        'name' => $user->name,

                        'user_name' => $user->name,

                        'course_title' => $course->title,

                        'time' => now(),

                        'meeting_link' => '',

                        'admin_name' => '-',

                        'mobile' => '-',

                    ]
                );

                /*
                |--------------------------------------------------------------------------
                | Update Reminder Status
                |--------------------------------------------------------------------------
                */

                $schedule->reminder_before_sent = 1;

                $schedule->save();

                $this->info(
                    "Before reminder sent to User {$user->id}"
                );
            }

            /*
            |--------------------------------------------------------------------------
            | ON DEADLINE
            |--------------------------------------------------------------------------
            */

            if (
                in_array(1, $reminderTypes) &&
                $schedule->due_date == $today
            ) {

                course_allocation_notification(
                    $user,
                    'course_deadline_notification',
                    [

                        'email' => $user->email,

                        'name' => $user->name,

                        'user_name' => $user->name,

                        'course_title' => $course->title,

                        'time' => now(),

                        'meeting_link' => '',

                        'admin_name' => '-',

                        'mobile' => '-',

                    ]
                );

                $this->info(
                    "Deadline reminder sent to User {$user->id}"
                );
            }

            /*
            |--------------------------------------------------------------------------
            | AFTER DEADLINE
            |--------------------------------------------------------------------------
            */

            if (
                in_array(3, $reminderTypes) &&
                !$schedule->reminder_after_sent &&
                $schedule->after_reminder_date == $today
            ) {

                course_allocation_notification(
                    $user,
                    'course_overdue_notification',
                    [

                        'email' => $user->email,

                        'name' => $user->name,

                        'user_name' => $user->name,

                        'course_title' => $course->title,

                        'time' => now(),

                        'meeting_link' => '',

                        'admin_name' => '-',

                        'mobile' => '-',

                    ]
                );

                /*
                |--------------------------------------------------------------------------
                | Update Reminder Status
                |--------------------------------------------------------------------------
                */

                $schedule->reminder_after_sent = 1;

                $schedule->save();

                $this->info(
                    "After reminder sent to User {$user->id}"
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Mark Overdue
            |--------------------------------------------------------------------------
            */

            if (
                $schedule->due_date < $today &&
                $schedule->status == 'pending'
            ) {

                $schedule->status = 'overdue';

                $schedule->save();

                $this->info(
                    "Schedule overdue for User {$user->id}"
                );
            }
        }

        $this->info(
            'Learning plan reminders processed successfully.'
        );
    }
}