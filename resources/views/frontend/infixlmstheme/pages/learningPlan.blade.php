@extends(theme('layouts.dashboard_master'))

@section('title')
    {{ __('Learning Plan') }}
@endsection

@section('mainContent')

<x-learning-plan-section :request="$request"/>

@endsection