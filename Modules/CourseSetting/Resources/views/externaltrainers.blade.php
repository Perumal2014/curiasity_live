@extends('setting::layouts.master')

@section('mainContent')

    {!! generateBreadcrumb() !!}

    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid p-0">
            <div class="white-box">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <div class="box_header">
                            <div class="main-title">
                                <h3 class="mb-0">{{ __('courses.External Trainers') }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        @include('coursesetting::components._external_trainers')
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@include('setting::page_components.script')
