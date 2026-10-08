@extends(theme('layouts.master'))

@push('styles')
<link rel="stylesheet" href="{{ asset('public/frontend/infixlmstheme/css/custom-home.css') }}">
@endpush
@section('mainContent')

@include(theme('home.sections.hero'))
@include(theme('home.sections.features'))
@include(theme('home.sections.categories'))
@include(theme('home.sections.courses'))
@include(theme('home.sections.cta'))
@include(theme('home.sections.testimonials'))
<!-- @include(theme('home.sections.partners')) -->

@endsection