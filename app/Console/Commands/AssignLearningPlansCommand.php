<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\Services\LearningPlanService;

class AssignLearningPlansCommand extends Command
{
    protected $signature = 'learning-plan:assign';
    protected $description = 'Assign learning plans to new users';

    public function handle()
    {
        User::select('id', 'profile_id', 'bu', 'dept_id', 'country', 'state', 'city', 'location_code', 'mgt_id')
            ->pendingPlans()
            ->chunk(100, function ($users) {

                foreach ($users as $user) {

                    try {
                        
                        app(LearningPlanService::class)
                            ->assignPlansToUser($user);

                        // dd('Assigning plans to user: ' . $user->id);
                        $user->update([
                            'plan_assigned' => 1
                        ]);

                    } catch (\Exception $e) {

                        \Log::error('Learning Plan Assignment Failed', [
                            'user_id' => $user->id,
                            'error' => $e->getMessage()
                        ]);
                    }
                }
            });

        $this->info('Learning plans assigned successfully.');
    }
}