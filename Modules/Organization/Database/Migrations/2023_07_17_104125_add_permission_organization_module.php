<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\RolePermission\Entities\Permission;

class AddPermissionOrganizationModule extends Migration
{
    public function up()
    {


        $routes = [
            ['name' => 'Financial', 'route' => 'organization.financial.index', 'type' => 1, 'parent_route' => null, 'module' => 'Organization'],
            ['name' => 'Sales Report', 'route' => 'organization.sales_report.index', 'type' => 2, 'parent_route' => 'organization.financial.index', 'module' => 'Organization'],
            ['name' => 'Financial Report', 'route' => 'organization.financial_report.index', 'type' => 2, 'parent_route' => 'organization.financial.index', 'module' => 'Organization'],
            ['name' => 'Payout', 'route' => 'organization.payout.index', 'type' => 2, 'parent_route' => 'organization.financial.index', 'module' => 'Organization'],
            ['name' => 'Request Payout', 'route' => 'organization.payout.store', 'type' => 3, 'parent_route' => 'organization.payout.index', 'module' => 'Organization'],
            ['name' => 'Completed', 'route' => 'organization.payout.completed', 'type' => 3, 'parent_route' => 'organization.payout.index', 'module' => 'Organization'],


        ];
        if (function_exists('permissionUpdateOrCreate')) {
            permissionUpdateOrCreate($routes);
        }

    }

    public function down()
    {

    }
}
