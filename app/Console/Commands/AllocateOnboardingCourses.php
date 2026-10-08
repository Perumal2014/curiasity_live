<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;

use App\User;

use App\Models\LearningPlan;
use App\Models\LearningPlanCourse;
use App\Models\LearningPlanGroup;
use App\Models\UserLearningPlanSchedule;
use App\Models\LearningPlanMilestone;
use App\Models\LearningPlanReminder;

use Modules\CourseSetting\Entities\Course;
use Modules\CourseSetting\Entities\CourseEnrolled;

class AllocateOnboardingCourses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'allocate:onboarding-courses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Allocate onboarding courses based on user groups';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        /*
        |--------------------------------------------------------------------------
        | Get Users
        |--------------------------------------------------------------------------
        */

        $users = User::where('plan_assigned', 0)
            ->whereNotNull('organization_id')
            ->get();

        if ($users->isEmpty()) {

            $this->info('No users found for onboarding allocation.');
            return;
        }

        foreach ($users as $user) {

            /*
            |--------------------------------------------------------------------------
            | User Group Mapping
            |--------------------------------------------------------------------------
            */

            $userGroups = [

                1 => $user->profile_id,
                2 => $user->dept_id,
                3 => $user->bu,
                4 => $user->country,
                5 => $user->state,
                6 => $user->city,
                7 => $user->location_code,
                8 => $user->mgt_id,

            ];

            /*
            |--------------------------------------------------------------------------
            | Get Matching Learning Plans
            |--------------------------------------------------------------------------
            */

            $learningPlanIds = [];

            $matchedGroups = [];

            foreach ($userGroups as $groupType => $groupId) {

                if (empty($groupId)) {
                    continue;
                }

                $planIds = LearningPlanGroup::where('group_type', $groupType)
                    ->where('group_id', $groupId)
                    ->pluck('learning_plan_id')
                    ->toArray();

                foreach ($planIds as $planId) {

                    $matchedGroups[$planId] = [

                        'group_id' => $groupId,
                        'group_type' => $groupType,

                    ];
                }

                $learningPlanIds = array_merge($learningPlanIds, $planIds);
            }

            /*
            |--------------------------------------------------------------------------
            | Remove Duplicate Plans
            |--------------------------------------------------------------------------
            */

            $learningPlanIds = array_unique($learningPlanIds);

            if (empty($learningPlanIds)) {

                $this->info(
                    "No matching learning plans for User ID: {$user->id}"
                );

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Get Onboarding Learning Plans
            |--------------------------------------------------------------------------
            */

            $learningPlans = LearningPlan::whereIn('id', $learningPlanIds)
                ->where('occurs_when', 1)
                ->get();

            if ($learningPlans->isEmpty()) {

                $this->info(
                    "No onboarding learning plans found for User ID: {$user->id}"
                );

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Process Learning Plans
            |--------------------------------------------------------------------------
            */

            foreach ($learningPlans as $plan) {

                /*
                |--------------------------------------------------------------------------
                | Group Information
                |--------------------------------------------------------------------------
                */

                $groupId = $matchedGroups[$plan->id]['group_id'] ?? null;

                $groupType = $matchedGroups[$plan->id]['group_type'] ?? null;

                /*
                |--------------------------------------------------------------------------
                | Get Milestone
                |--------------------------------------------------------------------------
                */

                $milestone = LearningPlanMilestone::where(
                    'learning_plan_id',
                    $plan->id
                )->first();

                /*
                |--------------------------------------------------------------------------
                | Default Due Days
                |--------------------------------------------------------------------------
                */

                $dueDays = 7;

                $beforeDays = 0;

                $afterDays = 0;

                if ($milestone) {

                    $dueDays = $milestone->no_of_days ?? 7;

                    $beforeDays = $milestone->before_days ?? 0;

                    $afterDays = $milestone->after_days ?? 0;
                }

                /*
                |--------------------------------------------------------------------------
                | Reminder Types
                |--------------------------------------------------------------------------
                */

                $reminderTypes = LearningPlanReminder::where(
                    'learning_plan_id',
                    $plan->id
                )
                ->pluck('reminder_type')
                ->toArray();

                /*
                |--------------------------------------------------------------------------
                | Get Courses
                |--------------------------------------------------------------------------
                */

                $courseIds = LearningPlanCourse::where(
                    'learning_plan_id',
                    $plan->id
                )->pluck('course_id');

                if ($courseIds->isEmpty()) {
                    continue;
                }

                foreach ($courseIds as $courseId) {

                    /*
                    |--------------------------------------------------------------------------
                    | Prevent Duplicate Enrollment
                    |--------------------------------------------------------------------------
                    */

                    $alreadyEnrolled = CourseEnrolled::where(
                        'user_id',
                        $user->id
                    )
                    ->where('course_id', $courseId)
                    ->exists();

                    if (!$alreadyEnrolled) {

                        CourseEnrolled::create([

                            'tracking' => uniqid(),

                            'user_id' => $user->id,

                            'course_id' => $courseId,

                            'purchase_price' => 0,

                            'status' => 1,

                            'coupon' => 0,

                        ]);

                        $this->info(
                            "Course {$courseId} assigned to User {$user->id}"
                        );
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Prevent Duplicate Schedule
                    |--------------------------------------------------------------------------
                    |
                    | Important:
                    | same user + same course
                    | should only have one schedule
                    |
                    */

                    $scheduleExists = UserLearningPlanSchedule::where(
                        'user_id',
                        $user->id
                    )
                    ->where('course_id', $courseId)
                    ->exists();

                    if ($scheduleExists) {

                        $this->info(
                            "Schedule already exists for User {$user->id} Course {$courseId}"
                        );

                        continue;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Dates
                    |--------------------------------------------------------------------------
                    */

                    $startDate = Carbon::now();

                    $dueDate = Carbon::now()
                        ->addDays($dueDays);

                    $beforeReminderDate = null;

                    $afterReminderDate = null;

                    /*
                    |--------------------------------------------------------------------------
                    | Before Reminder
                    |--------------------------------------------------------------------------
                    */

                    if (in_array(2, $reminderTypes) && $beforeDays > 0) {

                        $beforeReminderDate = $dueDate
                            ->copy()
                            ->subDays($beforeDays)
                            ->format('Y-m-d');
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | After Reminder
                    |--------------------------------------------------------------------------
                    */

                    if (in_array(3, $reminderTypes) && $afterDays > 0) {

                        $afterReminderDate = $dueDate
                            ->copy()
                            ->addDays($afterDays)
                            ->format('Y-m-d');
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Create Schedule
                    |--------------------------------------------------------------------------
                    */

                    UserLearningPlanSchedule::create([

                        'user_id' => $user->id,

                        'group_id' => $groupId,

                        'group_type' => $groupType,

                        'learning_plan_id' => $plan->id,

                        'course_id' => $courseId,

                        'start_date' => $startDate
                            ->format('Y-m-d'),

                        'due_date' => $dueDate
                            ->format('Y-m-d'),

                        'before_reminder_date' => $beforeReminderDate,

                        'after_reminder_date' => $afterReminderDate,

                        'status' => 'pending',

                        'reminder_before_sent' => in_array(2, $reminderTypes)
                            ? 0
                            : 1,

                        'reminder_after_sent' => in_array(3, $reminderTypes)
                            ? 0
                            : 1,

                    ]);

                    $this->info(
                        "Schedule created for User {$user->id} Course {$courseId}"
                    );

                    /*
                    |--------------------------------------------------------------------------
                    | Send Allocation Mail
                    |--------------------------------------------------------------------------
                    */

                    $course = Course::find($courseId);

                    if ($course) {

                        course_allocation_notification(
                            $user,
                            'course_allocation_notification',
                            [

                                'email' => $user->email,

                                'name' => $user->name,

                                'user_name' => $user->name,

                                'course_title' => $course->title,

                                'time' => now(),

                                'meeting_link' => 'https://meet.google.com/spg-kxiw-jrt',

                                'admin_name' => '-',

                                'mobile' => '-',

                            ]
                        );
                    }
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Update User Status
            |--------------------------------------------------------------------------
            */

            $user->plan_assigned = 1;

            $user->save();

            $this->info(
                "Onboarding completed for User ID: {$user->id}"
            );
        }

        $this->info(
            'All onboarding course allocations completed successfully.'
        );
    }
}