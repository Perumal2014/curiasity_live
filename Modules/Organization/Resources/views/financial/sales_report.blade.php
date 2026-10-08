@extends('backend.master')
@push('styles')
    <link rel="stylesheet" href="{{ asset('public/backend/css/daterangepicker.css') }}">
@endpush
@section('mainContent')

    {!! generateBreadcrumb() !!}

    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid p-0">

            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="white_box mb_20">
                        <div class="white_box_tittle list_header main-title mb-0">
                            <h3 class="mb-20">{{__('courses.Advanced Filter')}} </h3>
                        </div>
                        <form action="#" method="POST">
                            @csrf
                            <div class="row">

                                @if($is_admin)
                                    <div class="col-lg-3 mb-3">
                                        <label class="primary_input_label"
                                               for="f_user">{{__('organization.organization')}}</label>
                                        <select class="primary_select" name="f_user" id="f_user">
                                            <option value="">{{__('common.Select One')}}</option>
                                            @foreach($users as $user)
                                                <option value="{{$user->id}}">{{$user->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif


                                <div class="col-lg-3 mb-3">
                                    <label class="primary_input_label" for="f_type">{{__('common.Type')}}</label>
                                    <select class="primary_select" name="f_type" id="f_type">
                                        <option value="">{{__('common.Select One')}}</option>
                                        <option value="1">{{ trans('courses.Course')}}</option>
                                        <option value="2">{{ trans('courses.Quiz')}}</option>
                                        <option value="3">{{ trans('courses.Virtual Class')}}</option>
                                    </select>
                                </div>


                                <div class="col-lg-3 mb-3">
                                    <label class="primary_input_label"
                                           for="f_topic">{{__('organization.topic')}}</label>
                                    <select class="primary_select" name="f_topic" id="f_topic">
                                        <option value="">{{__('common.Select One')}}</option>
                                        @foreach($topics as $topic)
                                            <option value="{{$topic->id}}">{{$topic->title}}</option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="col-lg-3">
                                    <div class="primary_input mb-15 date_range">
                                        <div class="primary_datepicker_input filter">
                                            <label class="primary_input_label" for="">{{__('common.Date')}}</label>
                                            <div class="g-0 input-right-icon">
                                                <input placeholder="{{__('common.Date')}}" readonly
                                                       class="primary_input_field date_range_input" type="text"
                                                       name="date_range_filter" value="">
                                                <button class="" type="button">
                                                    <i class="fa fa-refresh" id="reset-date-filter"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-lg-3  ">
                                    <div class="search_course_btn">
                                        <a class="primary-btn radius_30px fix-gr-bg reset_btn mt-20 w-fit">{{__('common.Reset')}} </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-12">
                    <div class="white-box">
                        <div class="row">
                            <div class="col-12">
                                <div class="box_header common_table_header">
                                    <div class="main-title d-md-flex">
                                        <h3 class="mb-0 mr-30 mb_xs_15px mb_sm_20px" id="page_title">{{ trans('organization.sales_report') }}</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="QA_section QA_section_heading_custom check_box_table">
                                    <div class="QA_table ">
                                        <!-- table-responsive -->
                                        <div class="">
                                            <table id="lms_table" class="table Crm_table_active3">
                                                <thead>
                                                <tr>
                                                    <th scope="col">{{__('common.SL')}}</th>
                                                    <th scope="col">{{__('common.Date')}}</th>
                                                    <th scope="col">{{__('common.Student')}}</th>
                                                    <th scope="col">{{__('organization.organization')}}</th>
                                                    <th scope="col">{{__('organization.topic')}}</th>
                                                    <th scope="col">{{__('common.Type')}}</th>
                                                    <th scope="col">{{__('common.Price')}}</th>
                                                    <th scope="col">{{__('common.Discount')}}</th>
                                                    <th scope="col">{{__('organization.income')}}</th>
                                                    <th scope="col">{{__('organization.admin_profit')}}</th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
            <input type="hidden" value="{{$is_admin}}" id="is_admin">
            <input type="hidden" value="{{route('organization.sales_report.datatable')}}"
                   id="organization_sales_report_route">
        </div>
    </section>
@endsection

@push('scripts')
    <script src="{{asset('public/backend/js/daterangepicker.min.js')}}"></script>
    <script src="{{asset('public/modules/common/date_range_init.js')}}"></script>
    <script src="{{asset('Modules/Organization/Resources/assets/js/sales_report.js')}}"></script>
@endpush


