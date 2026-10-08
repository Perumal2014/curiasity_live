@php
    $route =Route::currentRouteName();

    if($route=="register"){
        $title =$page->reg_title;
        $banner =$page->reg_banner;
        $slogans1 =$page->reg_slogans1;
        $slogans2 =$page->reg_slogans2;
        $slogans3 =$page->reg_slogans3;
    }elseif($route=="login"){
        $title =$page->title;
        $banner =$page->banner;
        $slogans1 =$page->slogans1;
        $slogans2 =$page->slogans2;
        $slogans3 =$page->slogans3;

    }else{
        $title =$page->forget_title;
                $banner =$page->forget_banner;
        $slogans1 =$page->forget_slogans1;
        $slogans2 =$page->forget_slogans2;
        $slogans3 =$page->forget_slogans3;

    }

    
    $tenant = null;

    if (app()->bound('tenant')) {
        $tenant = app('tenant');
    }
    $tenantName = $tenant->tenant_name ?? 'Curiasity';
    $tenantBanner = $tenant->tenant_banner ?? null;
@endphp

<div class="login_wrapper_right" style="background: var(--system_primary_color)">
    <div class="login_main_info">
        <h4 style="color:#fff">

            {{ 'Welcome To ' . ($tenantName . ' Learning Platform' ?? 'Learning Management System') }}
        </h4>
        <div class="thumb">
             <img src="{{ asset($tenantBanner ?? $banner ?? 'public/frontend/infixlmstheme/img/banner/global.png')}}" alt="">
        </div>
        <div class="other_links">
            <span style="color:#fff">{{$slogans1?? 'Learn.'}} </span>
            <span style="color:#fff">{{$slogans2?? 'Grow.'}} </span>
            <span style="color:#fff">{{$slogans3?? 'Achieve.'}} </span>
        </div>
    </div>
</div>
