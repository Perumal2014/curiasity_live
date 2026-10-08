@extends(theme('layouts.dashboard_master'))
@php
    $tenant = app()->bound('tenant') ? app('tenant') : null;

    $title = $tenant?->tenant_name ?? Settings('site_title');
@endphp
@section('title')
    {{ $title }} | {{ __('common.Dashboard') }}
@endsection
@section('css')
    <link href="{{asset('public/frontend/infixlmstheme/css/class_details.css')}}{{assetVersion()}}" rel="stylesheet"/>

@endsection

@section('mainContent')
    
    @if(auth()->user()->student_type == 'membership')
        <x-my-membership-dashboard-page-section/>
    @else
        <x-my-dashboard-page-section/>
    @endif
@endsection
@section('js')
    <script src="{{asset('public/frontend/infixlmstheme/js/class_details.js')}}"></script>
@endsection
