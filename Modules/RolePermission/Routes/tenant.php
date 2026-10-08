<?php

use Illuminate\Support\Facades\Route;

Route::prefix('{tenant_slug}')
    ->middleware('tenant.slug')
    ->group(function () {

        Route::group([
            'prefix'     => 'role-permission',
            'as'         => 'permission.',
            'middleware' => ['auth', 'admin'],
        ], function () {

            Route::resource('roles', 'RoleController')
                ->except('destroy')
                ->middleware('RoutePermissionCheck:permission.permissions.store');

            Route::get('roles-student', 'RoleController@studentIndex')
                ->name('student-roles')
                ->middleware('RoutePermissionCheck:permission.permissions.store');

            Route::get('roles-staff', 'RoleController@staffIndex')
                ->name('staff-roles')
                ->middleware('RoutePermissionCheck:permission.permissions.store');

            Route::resource('permissions', 'PermissionController')
                ->middleware('RoutePermissionCheck:permission.permissions.store');

            Route::delete('roles/{id}', 'RoleController@destroy')
                ->name('roles.destroy')
                ->middleware('RoutePermissionCheck:permission.permissions.store');
        });
    });






