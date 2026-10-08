<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserLearningPlanSchedule;
use App\Models\LearningPlanCourse;
use App\Models\CourseEnrolled;

class ActivateLearningPlanCourses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:activate-learning-plan-courses';

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
        $items = UserLearningPlanSchedule::where('status', 'pending')
        ->whereDate('start_date', '<=', now())
        ->get();

        foreach ($items as $item) {

            CourseEnrolled::updateOrCreate([
                'user_id' => $item->user_id,
                'course_id' => $item->course_id,
            ], [
                'status' => 'active'
            ]);

            $item->update(['status' => 'active']);
        }
    }
}
