<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\RolePermission\Entities\Permission;

return new class extends Migration {

    public function up()
    {
        $routes = [
            ['name' => 'Event List', 'route' => 'event', 'type' => 2, 'parent_route' => 'calendar', 'module' => 'Calendar'],
            ['name' => 'Event Add', 'route' => 'event_create', 'type' => 3, 'parent_route' => 'event', 'module' => 'Calendar'],
            ['name' => 'Event Edit', 'route' => 'event-edit', 'type' => 3, 'parent_route' => 'event', 'module' => 'Calendar'],
            ['name' => 'Event Delete', 'route' => 'delete-event', 'type' => 3, 'parent_route' => 'event', 'module' => 'Calendar'],
        ];
        if (function_exists('permissionUpdateOrCreate')) {
            permissionUpdateOrCreate($routes);
        }
    }

    public function down()
    {
        //
    }
};
