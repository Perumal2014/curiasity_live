<?php

namespace Modules\Calendar\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Calendar\Entities\LmsEvent;

class CalendarDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        LmsEvent::create([
            'event_title' => 'Test Event',
            'for_whom' => 'All',
            'event_location' => 'Dhaka, Bangladesh',
            'event_des' => 'This is a test event description',
            'from_date' => now(),
            'to_date' => now()->addMonths(1),
            'start_time' => '01:00 PM',
            'end_time' => '03:00 PM',
            'url' => 'https://google.com',
            'host_type' => 1,
            'host' => 'Teacher',
            'uplad_image_file' => '',
        ]);
    }
}
