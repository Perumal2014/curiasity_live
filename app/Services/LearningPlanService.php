<?php

namespace App\Services;

use App\Models\LearningPlan;
use App\Models\LearningPlanCourse;
use App\Models\LearningPlanGroup;
use App\Models\UserLearningPlanSchedule;
use App\Models\LearningPlanMilestone;
use Modules\CourseSetting\Entities\CourseEnrolled;
use Illuminate\Support\Facades\DB;

class LearningPlanService
{
    /**
     * Assign learning plan courses to user
     */
    public function assignPlansToUser($userId)
    {
        $userGroups = $this->getUserGroups($userId);

        foreach ($userGroups as $group) {

            // dd($group);

            if (!$group['group_id']) continue;

            $plans = LearningPlanGroup::where('group_id', $group['group_id'])
                ->where('group_type', $group['group_type'])
                ->pluck('learning_plan_id');
            
            // dd($plans);

            foreach ($plans as $planId) {
                //  dd('Assigning plan: ' . $planId . ' to user: ' . $userId . ' based on group: ' . $group['group_id'] . ' of type: ' . $group['group_type']);
                $this->assignPlansByGroup($planId, $userId, $group['group_id'], $group['group_type']);
            }
        }
    }

    /**
     * ✅ MOVE FUNCTION HERE
     */
    public function getUserGroups($user)
    {
        return [
            ['group_id' => $user->profile_id, 'group_type' => 1],
            ['group_id' => $user->bu, 'group_type' => 2],
            ['group_id' => $user->dept_id, 'group_type' => 3],
            ['group_id' => $user->country, 'group_type' => 4],
            ['group_id' => $user->state, 'group_type' => 5],
            ['group_id' => $user->city, 'group_type' => 6],
            ['group_id' => $user->location_code, 'group_type' => 7],
            ['group_id' => $user->mgt_id, 'group_type' => 8],
        ];
    }


    /**
     * Assign plans based on group
     */
    public function assignPlansByGroup($planId, $userId, $groupId, $groupType)
    {
        // dd('Checking plan: ' . $planId . ' for user: ' . $userId . ' in group: ' . $groupId . ' of type: ' . $groupType);
        $plans = LearningPlan::where('id', $planId)->where('occurs_when', 1)->get();

        // dd('Checking plan: ' . $planId . ' for user: ' . $userId . ' in group: ' . $groupId . ' of type: ' . $groupType);

        foreach ($plans as $plan) {
            // dd('Checking plan: ' . $plan->id . ' for user: ' . $userId . ' in group: ' . $groupId . ' of type: ' . $groupType);
            $exists = LearningPlanGroup::where('learning_plan_id', $plan->id)
                ->where('group_id', $groupId)
                ->where('group_type', $groupType)
                ->exists();
            // dd('Plan: ' . $plan->id . ' exists for group: ' . $groupId . ' of type: ' . $groupType . ' => ' . ($exists ? 'Yes' : 'No'));
            if ($exists) {
                $this->assignToUser($plan->id, $userId, $groupId, $groupType);
            }
        }
    }

    public function assignToUser($planId, $userId, $groupId, $groupType)
    {
        // dd('user id: ' . $userId->id . ' plan id: ' . $planId);
        $courses = LearningPlanCourse::where('learning_plan_id', $planId)->get();

        if ($courses->isEmpty()) {
            // dd("No courses for plan: $planId");
            \Log::info("No courses for plan: $planId");
            return;
        }

        // ✅ Get ANY one milestone (or main milestone)
        $milestone = LearningPlanMilestone::where('learning_plan_id', $planId)->first();

        if (!$milestone) {
            // dd("No milestone for plan: $planId");
            \Log::info("No milestone for plan: $planId");
            return;
        }

        $startDate = now();
        $dueDate = now()->copy()->addDays($milestone->no_of_days);

        // dd('Assigning plan: ' . $planId . ' to user: ' . $userId . ' with start date: ' . $startDate . ' and due date: ' . $dueDate);

        DB::transaction(function () use ($courses, $planId, $userId, $groupId, $groupType, $startDate, $dueDate) {

            foreach ($courses as $course) {
                
                // dd('Processing course: ' . $course->course_id . ' for user: ' . $userId->id . ' with start date: ' . $startDate . ' and due date: ' . $dueDate);
                if (now()->gte($startDate)) {

                    $exists = CourseEnrolled::where('user_id', $userId->id)
                        ->where('course_id', $course->course_id)
                        ->exists();

                    if (!$exists) {
                        CourseEnrolled::create([
                            'user_id' => $userId->id ?? 0,
                            'tracking' => getTrx(),
                            'course_id' => $course->course_id,
                            'purchase_price' => 0,
                            'discount_amount' => 0,
                            'is_learning_plan_course' => 1,
                            'status' => 1,
                            'lms_id' => 1,
                        ]);

                        $schedule = UserLearningPlanSchedule::updateOrCreate(
                        [
                            'user_id' => $userId->id ?? 0,
                            'learning_plan_id' => $planId,
                            'course_id' => $course->course_id,
                        ],
                        [
                            'start_date' => $startDate,
                            'due_date' => $dueDate,
                            'status' => '0',
                            'group_id' => $groupId,
                            'group_type' => $groupType,
                        ]
                    );
                    }

                    $schedule->update(['status' => '1']);
                }
            }
        });
    }
}