<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\UserLearningPlanSchedule;
use App\Models\LearningPlanCourse;


class SendAfterReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-after-reminders';

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
        ->where('reminder_after_sent', false)
        ->get();

        foreach ($items as $item) {

            if (now()->gt($item->due_date)) {

                $daysAfter = LearningPlanCourse::where('course_id', $item->course_id)
                    ->where('learning_plan_id', $item->learning_plan_id)
                    ->value('reminder_after_days');

                if ($daysAfter !== null &&
                    $item->due_date->diffInDays(now()) == $daysAfter) {

                    \Log::info("After Reminder sent to User {$item->user_id}");

                    $item->update([
                        'reminder_after_sent' => true,
                        'status' => 'overdue'
                    ]);
                }
            }
        }

    }
}
