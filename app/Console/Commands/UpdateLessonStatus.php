<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\CourseSetting\Entities\Lesson;
use Carbon\Carbon;

class UpdateLessonStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lessons:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update lesson status when end date has passed';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $updated = Lesson::where('status', '!=', 1)
            ->whereNotNull('end_date')
            ->whereDate('end_date', '<', Carbon::today())
            ->update([
                'status' => 1,
            ]);

        $this->info("Updated {$updated} lessons.");

        return Command::SUCCESS;
    }
}