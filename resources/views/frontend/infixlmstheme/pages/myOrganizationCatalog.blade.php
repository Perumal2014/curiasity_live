@extends(theme('layouts.dashboard_master'))

@section('title')
    {{ __('My Organization Catalog') }}
@endsection

@section('mainContent')

<x-my-organization-catalog-section :request="$request"/>

@endsection

