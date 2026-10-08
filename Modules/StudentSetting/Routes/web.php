<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Super Admin Routes (NO SLUG)
|--------------------------------------------------------------------------
*/

require __DIR__.'/tenant.php';


/*
|--------------------------------------------------------------------------
| Tenant Routes (WITH SLUG)
|--------------------------------------------------------------------------
*/

Route::prefix('{tenant_slug}')
    ->where([
        'tenant_slug' => '(?!admin|login|dashboard|register|logout)[a-z0-9\-]+'
    ])
    ->middleware('tenant.slug')
    ->group(function () {
         Route::get('/fetch-states', 'StudentSettingController@fetchstates');  
        require __DIR__.'/tenant.php';

    });

