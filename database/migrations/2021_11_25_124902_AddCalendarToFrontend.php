<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;
use Modules\FrontendManage\Entities\FrontPage;
use Modules\FrontendManage\Entities\HeaderMenu;

return new class extends Migration {

    public function up()
    {
        $check_calendar =FrontPage::where('slug', '/calendar-view')->first();
            if (!$check_calendar) {
                DB::table('front_pages')->insert([
                    'name' => 'Calendar',
                    'title' => 'Calendar',
                    'sub_title' => 'Calendar',
                    'details' => 'Calendar Page',
                    'slug' => '/calendar-view',
                    'status' => 1,
                    'is_static' => 1,
                ]);
            }

        $calendar = FrontPage::where('slug','/calendar-view')->first();
        $header_menu = HeaderMenu::where('link','/calendar-view')->first();
        if ($calendar && $header_menu==null) {
            $menu = new HeaderMenu();
            $menu->type = "Static Page";
            $menu->element_id = $calendar->id;
            $menu->title = $calendar->title;
            $menu->link = $calendar->slug;
            $menu->position = 9;
            $menu->save();

        }
    }


    public function down()
    {
        //
    }
};
