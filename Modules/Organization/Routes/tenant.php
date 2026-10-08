<?php

use Illuminate\Support\Facades\Route;
use Modules\Organization\Http\Controllers\OrganizationController;

Route::prefix('{tenant_slug}')
    ->middleware('tenant.slug')
    ->group(function () {

        Route::group([
            'prefix'     => 'organization',
            'as'         => 'organization.',
            'middleware' => ['auth'],
        ], function () {

        Route::get('tenant-data', 'OrganizationController@getAllTenants')
                ->name('alltenant.data')
                ->middleware('RoutePermissionCheck:organization.tenantindex');

        Route::get('organization-data', 'OrganizationController@getAllTenantData')
                ->name('tenant.data')
                ->middleware('RoutePermissionCheck:organization.index');

            Route::get('/organization', 'OrganizationController@index')
                ->name('index')
                ->middleware('RoutePermissionCheck:organization.index');

            Route::get('get-role-list/{organization_id}', 'OrganizationController@getRoleListByOrganization')
                ->name('get.role.list');

            Route::get('tenantIndex', 'OrganizationController@TenantIndex')
                ->name('tenantindex')
                ->middleware('RoutePermissionCheck:organization.tenantindex');

            

            

            Route::get('create', 'OrganizationController@create')
                ->name('store')
                ->middleware('RoutePermissionCheck:organization.store');

            Route::post('create', 'OrganizationController@store')
                ->middleware('RoutePermissionCheck:organization.store');

            Route::get('tenantCreate', 'OrganizationController@TenantCreate')
                ->name('tenantStore')
                ->middleware('RoutePermissionCheck:organization.tenantStore');

            Route::post('tenantCreate', 'OrganizationController@tenantStore')
                ->middleware('RoutePermissionCheck:organization.tenantStore');

            Route::get('edit/{id}', 'OrganizationController@edit')
                ->name('update')
                ->middleware('RoutePermissionCheck:organization.update');

            Route::post('edit/{id}', 'OrganizationController@update')
                ->middleware('RoutePermissionCheck:organization.update');

            Route::get('tenantEdit/{id}', 'OrganizationController@tenantedit')
                ->name('tenantupdate')
                ->middleware('RoutePermissionCheck:organization.tenantupdate');

            Route::post('tenantEdit/{id}', 'OrganizationController@tenantupdate')
                ->middleware('RoutePermissionCheck:organization.tenantupdate');

            Route::post('destroy', 'OrganizationController@destroy')
                ->name('destroy')
                ->middleware('RoutePermissionCheck:organization.destroy');

            /*
            |--------------------------------------------------------------------------
            | Financial Reports
            |--------------------------------------------------------------------------
            */

            Route::get('sales-report', 'FinancialReportController@salesReport')
                ->name('sales_report.index');

            Route::get('sales-report/datatable', 'FinancialReportController@salesReportDatatable')
                ->name('sales_report.datatable');

            Route::get('financial-report', 'FinancialReportController@financialReport')
                ->name('financial_report.index');

            Route::get('financial-report/datatable', 'FinancialReportController@financialReportDatatable')
                ->name('financial_report.datatable');

            Route::get('payout', 'FinancialReportController@payout')
                ->name('payout.index');

            Route::get('payout/datatable', 'FinancialReportController@payoutDatatable')
                ->name('payout.datatable');

            Route::post('payout-submit', 'FinancialReportController@payoutSubmit')
                ->name('payout.store');

            Route::get('payout-request-completed/{id}', 'FinancialReportController@payoutCompleted')
                ->name('payout.completed');

        });

    });
