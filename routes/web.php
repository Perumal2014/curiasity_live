<?php

use Carbon\Carbon;
use App\Subscription;
use Illuminate\Support\Facades\Route;
use Modules\Membership\Entities\MembershipPlanCheckout;
use App\Http\Controllers\CustomHomeController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Http\Request;

// dd(request()->segment(1));

if (isModuleActive('LmsSaas') || isModuleActive('LmsSaasMD')) {
    Route::group(['middleware' => ['subdomain']], function ($routes) {
        require('tenant.php');
    });
} 

else if (request()->segment(1) != '') {  
    //   print_r('tenant.php'); exit;  
    require('tenant.php');
        
} 