<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\RolePermission\Entities\Permission;

class AddPermissionInScorm extends Migration
{
    public function up()
    {
        $routes = [
            ['name' => 'SCORM', 'route' => 'scorm', 'type' => 1, 'parent_route' => null, 'module' => 'SCORM'],
            ['name' => 'Report', 'route' => 'scorm.report.index', 'type' => 2, 'parent_route' => 'scorm', 'module' => 'SCORM'],
            ['name' => 'Lesson List', 'route' => 'scorm.report.details', 'type' => 3, 'parent_route' => 'scorm.report.index', 'module' => 'SCORM'],
        ];
        if (function_exists('permissionUpdateOrCreate')) {
            permissionUpdateOrCreate($routes);
        }

    }

    public function down()
    {
        //
    }
}
