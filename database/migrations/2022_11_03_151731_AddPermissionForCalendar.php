<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\RolePermission\Entities\Permission;

return new class extends Migration {

    public function up()
    {


        $routes = [
            ['name' => 'Calendar', 'route' => 'calendar', 'type' => 1, 'parent_route' => null, 'module' => 'Calendar'],
            ['name' => 'View', 'route' => 'calendar_show', 'type' => 2, 'parent_route' => 'calendar', 'module' => 'Calendar'],
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
