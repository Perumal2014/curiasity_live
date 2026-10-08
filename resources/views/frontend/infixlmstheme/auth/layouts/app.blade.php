<!doctype html>
<html dir="{{isRtl()?'rtl':''}}" class="{{isRtl()?'rtl':''}}" lang="en" itemscope
      itemtype="{{url('/')}}">
      @php
        $tenant = null;

        if (app()->bound('tenant')) {
            $tenant = app('tenant');
        }
        $title = $tenant?->tenant_name ?? Settings('site_title');
        $image = $tenant?->tenant_logo ?? Settings('logo');
        @endphp
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{ $title }}</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" type="image/x-icon" href="{{ getCourseImage($image) }}{{assetVersion()}}">

    <x-frontend-dynamic-style-color/>
    <x-backend-dynamic-color/>


    @if(isRtl())
        <link rel="stylesheet"
              href="{{ asset('public/frontend/infixlmstheme') }}/css/bootstrap.rtl.min.css{{assetVersion()}} ">
    @else
        <link rel="stylesheet"
              href="{{ asset('public/frontend/infixlmstheme') }}/css/bootstrap.min.css{{assetVersion()}} ">
    @endif

    <link rel="stylesheet" href="{{ asset('public/frontend/infixlmstheme') }}/css/app.css{{assetVersion()}}">
    @if(isRtl())
        <link rel="stylesheet"
              href="{{ asset('public/frontend/infixlmstheme') }}/css/frontend_style_rtl.css{{assetVersion()}}">
    @else
        <link rel="stylesheet"
              href="{{ asset('public/frontend/infixlmstheme') }}/css/frontend_style.css{{assetVersion()}}">
    @endif

    <script src="{{asset('public/js/common.js')}}{{assetVersion()}}"></script>
    <script src="{{asset('public/frontend/infixlmstheme/js/app.js')}}{{assetVersion()}}"></script>

    <link rel="stylesheet" href="{{ asset('public/frontend/infixlmstheme') }}/css/gijgo.min.css{{assetVersion()}}">
    <script src="{{ asset('public/frontend/infixlmstheme') }}/js/gijgo.min.js{{assetVersion()}}"></script>
    <link rel="stylesheet" href="{{asset('public/backend/css/themify-icons.css')}}{{assetVersion()}}"/>
    <link rel="stylesheet" href="{{asset('public/css/preloader.css')}}{{assetVersion()}}"/>

    <script>
        window._locale = '{{ app()->getLocale() }}';
        window._translations = {!! $jsonLang??''!!};
    </script>
    <x-analytics-tool/>

</head>

<body>
@include('secret_login')
@include('preloader')

@yield('content')


{!! \Brian2694\Toastr\Facades\Toastr::message() !!}
{!! NoCaptcha::renderJs() !!}

<script>
    if ($('.small_select').length > 0) {
        $('.small_select').niceSelect();
    }

    if ($('.datepicker').length > 0) {
        $('.datepicker').datepicker();
    }
    setTimeout(function () {
        $('.preloader').fadeOut('hide', function () {
            // $(this).remove();

        });
    }, 0);
</script>

</body>


</html>
