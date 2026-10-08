<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserLearningPlanSchedule;
use App\Models\LearningPlanCourse;

class SendBeforeReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-before-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
         $items = UserLearningPlanSchedule::whereNotNull('due_date')
        ->where('reminder_before_sent', false)
        ->get();

        foreach ($items as $item) {

            $daysBefore = LearningPlanCourse::where('course_id', $item->course_id)
                ->where('learning_plan_id', $item->learning_plan_id)
                ->value('reminder_before_days');

            if ($daysBefore !== null &&
                now()->diffInDays($item->due_date, false) == $daysBefore) {

                // Send Notification
                \Log::info("Before Reminder sent to User {$item->user_id}");

                $item->update(['reminder_before_sent' => true]);
            }
        }
    }
}
