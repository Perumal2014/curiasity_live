<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Modules\CourseSetting\Entities\Lesson;
use Modules\Calendar\Entities\Calendar;

class ExpireLessonsCommand extends Command
{
    protected $signature = 'lessons:expire';

    protected $description = 'Expire lessons and calendar events whose end date has passed';

    public function handle()
    {
        $today = Carbon::today()->toDateString();

        // Update lessons
        Lesson::whereDate('end_date', '<', $today)
            ->where('status', 0)
            ->update([
                'status' => 1,
            ]);

        // Update calendars
        Calendar::whereDate('end', '<', $today)
            ->where('status', 0)
            ->update([
                'status' => 1,
            ]);

        $this->info('Expired lessons and calendars updated successfully.');

        return Command::SUCCESS;
    }
}