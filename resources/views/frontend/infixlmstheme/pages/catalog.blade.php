@extends(theme('layouts.dashboard_master'))

@section('title')
    {{ Settings('site_title') }} | Catalog
@endsection

@section('mainContent')

<div class="main_content_iner">
    <div class="container-fluid">

        <!-- <div class="section__title3 mb-4">
            <h3>Course Catalog</h3>
        </div> -->

        <x-catalog-page-section :request="request()" />

    </div>
</div>

@endsection