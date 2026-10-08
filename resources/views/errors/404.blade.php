@extends('errors.layout')
@section('title'){{Settings('site_title')  ? Settings('site_title')  : 'Infix LMS'}} | {{__('Not Found')}} @endsection
@section('css') @endsection
@section('js') @endsection

@section('mainContent')
@endsection

@section('message')
    {{'Opps! Page Not Found' }}
@endsection
@section('details')
    The requested page is unavailable.
@endsection

