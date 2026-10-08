@extends('backend.master')
@push('styles')
    <link rel="stylesheet"
          href="{{asset('Modules/Calendar/Resources/assets/js')}}/calender_js/core/main.css{{assetVersion()}}">
    <link rel="stylesheet"
          href="{{asset('Modules/Calendar/Resources/assets/js')}}/calender_js/daygrid/main.css{{assetVersion()}}">
    <link rel="stylesheet"
          href="{{asset('Modules/Calendar/Resources/assets/js')}}/calender_js/timegrid/main.css{{assetVersion()}}">
    <link rel="stylesheet"
          href="{{asset('Modules/Calendar/Resources/assets/js')}}/calender_js/list/main.css{{assetVersion()}}">
    <link rel="stylesheet" href="{{asset('public/backend/css/class.css')}}{{assetVersion()}}"/>
    <style>
        .fc-event-container .fc-content .fc-title {
            padding-left: 15px;
        }
    </style>
@endpush
@php
    $table_name='lms_notice_boards';
@endphp
@section('table')
    {{$table_name}}
@endsection
@section('mainContent')

    {{generateBreadcrumb()}}

    <section class="admin-visitor-area up_st_admin_visitor">
        <input type="hidden" id="today_date" value="{{date('Y-m-d')}}">
        <div class="container-fluid p-0">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="white-box">
                        <div class="row">
                            <div class="col-12">
                                <div class="box_header common_table_header">
                                    <div class="main-title d-flex">
                                        <h3 class="mb-0 mr-30">{{__('calender.Calender')}}</h3>
                                    </div>
                                    <div class="table_btn_wrap">

                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div>
                                    <div id='calendar'></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <input type="hidden" id="url" value="{{url('/')}}">
                <!-- View Modal -->
                <div class="modal fade admin-query" id="mile_stone_view_modal">
                    <div class="modal-dialog modal_1000px modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="viewEventDate"></h4>
                                <span class="edit_pop_text">
                                    <a id="edit_button" class="text-white" data-calendar_info="" onclick="editModal()"
                                       data-bs-dismiss="modal" href="#">
                                    <button class="primary-btn small  mr-10">{{__('common.Edit')}}</button>
                                    </a>
                                    <a id="delete_button" class="text-white" data-calendar_info=""
                                       onclick="deleteCalendar()" data-bs-dismiss="modal" href="#">
                                        <button class="primary-btn small mr-10">{{__('common.Delete')}}</button>
                                    </a>
                                </span>
                            </div>

                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-xl-12 mb-20">
                                        <div class="main-title2 d-flex mb-1">
                                            <h2 class="mb-0 mr-30 text-center" id="calendar_title"></h2>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="col-lg-6">
                                            <h5 id="calendar_for_title"></h5>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="col-lg-6">
                                            <strong>{{__('calender.Date')}}: </strong>
                                            <span id="start_date"></span>
                                            <strong>{{__('calender.TO')}}</strong>
                                            <span id="end_date"></span>
                                        </div>
                                        <div class="col-lg-6">
                                            <strong>{{__('calender.Time')}}: </strong>
                                            <span id="start_time"></span>
                                            <strong>{{__('calender.TO')}}</strong>
                                            <span id="end_time"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mt-5">
                                        <img src="" width="100%" height="auto" id="calendar_banner" alt="">
                                    </div>
                                    <div class="col-lg-12">
                                        <p class="default_text14" id="calendar_description">

                                        </p>
                                    </div>
                                </div>
                                <div class="row mt-40">
                                    <div class="col-lg-12 text-center">
                                        <button type="button" class="primary-btn tr-bg float-end"
                                                data-bs-dismiss="modal"> {{__('common.Cancel')}}
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <!--/ View Model -->
                <!-- Add Modal -->
                <div class="modal fade admin-query" id="add_event_model">
                    <div class="modal-dialog modal_1000px modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="selectedDateShow"></h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"><i class="ti-close "></i></button>
                                {{-- <span class="edit_pop_text">Edit</span> --}}
                            </div>

                            <div class="modal-body">
                                <form action="{{route('calendar_store')}}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="row pl-20">
                                                <div class="col-md-12">
                                                    <label class="primary_input_label"
                                                           for="">{{__('calender.Calender')}} {{__('common.For')}}
                                                        <strong
                                                            class="text-danger">*</strong></label>
                                                </div>

                                                <div class="col-md-6">
                                                    <input onclick="calendarFor(1)" type="radio"
                                                           class="common-checkbox name"
                                                           {{isset($editData)? $editData->calendar_for==1? 'checked':'':'checked'}} name="calendar_for"
                                                           id="Course" value="1">
                                                    <label for="Course">{{__('courses.Courses')}}</label>
                                                </div>
                                                <div class="col-md-6">
                                                    <input onclick="calendarFor(2)" type="radio"
                                                           class="common-checkbox name"
                                                           {{isset($editData)? $editData->calendar_for==2? 'checked':'':''}} name="calendar_for"
                                                           id="Live_Class" value="2">
                                                    <label
                                                        for="Live_Class">{{__('virtual-class.Live Classes')}}</label>
                                                </div>


                                            </div>
                                        </div>

                                        <div class="col-lg-6" id="course_list">
                                            <label class="primary_input_label mt-1">{{__('courses.Course')}} <span class="text-danger">*</span> </label>
                                            <select
                                                class="primary_select {{ $errors->has('course') ? ' is-invalid' : '' }}"
                                                id="course" name="course">
                                                <option data-display="{{__('common.Select')}} {{__('courses.Course')}}*"
                                                        value="">{{__('common.Select')}} {{__('courses.Course')}} *
                                                </option>

                                                @foreach ($courses as $course)
                                                    <option
                                                        value="{{$course->id}}" {{isset($editData)? ($editData->course_id == $course->id? 'selected' : ''):"" }}>{{$course->title}}</option>
                                                @endforeach


                                            </select>
                                            @if ($errors->has('course'))
                                                <span class="invalid-feedback invalid-select" role="alert">
                                                        <strong>{{ $errors->first('course') }}</strong>
                                                    </span>
                                            @endif

                                        </div>
                                        <div class="col-lg-6" id="class_list" style="display: none">
                                            <label class="primary_input_label mt-1">{{__('virtual-class.Class')}} <span class="text-danger">*</span>
                                            </label>
                                            <select
                                                class="primary_select {{ $errors->has('class') ? ' is-invalid' : '' }}"
                                                id="class" name="class">
                                                <option
                                                    data-display="{{__('common.Select')}} {{__('virtual-class.Class')}}*"
                                                    value="">{{__('common.Select')}} {{__('virtual-class.Class')}} *
                                                </option>

                                                @foreach ($classes as $course)
                                                    <option
                                                        value="{{$course->id}}" {{isset($editData)? ($editData->course_id == $course->id? 'selected' : ''):"" }}>{{$course->title}}</option>
                                                @endforeach


                                            </select>
                                            @if ($errors->has('class'))
                                                <span class="invalid-feedback invalid-select" role="alert">
                                                        <strong>{{ $errors->first('class') }}</strong>
                                                    </span>
                                            @endif

                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <div class="primary_input mb-25">
                                                <label class="primary_input_label" for="">{{__('common.Title')}} <strong
                                                        class="text-danger">*</strong></label>
                                                <input class="primary_input_field" name="title" placeholder="-"
                                                       type="text"
                                                       value="{{ old('title') }}" {{$errors->first('title') ? 'autofocus' : ''}}>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row course_section">

                                        <div class="col-lg-6 input-right-icon mb-30">
                                            <div class="primary_input">
                                                <label class="primary_input_label"
                                                       for="from_date">{{__('communicate.From Date')}} <strong
                                                        class="text-danger">*</strong> </label>
                                                <div class="primary_datepicker_input">
                                                    <div class="g-0 input-right-icon">
                                                        <div class="col">
                                                            <div class="">
                                                                <input placeholder="{{__('communicate.From Date')}} "
                                                                       class="primary_input_field primary-input date form-control  {{ @$errors->has('from_date') ? ' is-invalid' : '' }}"
                                                                       id="from_date" type="text"
                                                                       name="from_date"
                                                                       value="{{isset($editData)? date('m/d/Y', strtotime($editData->from_date)): ''}}"
                                                                       autocomplete="off">
                                                            </div>
                                                        </div>
                                                        <button class="" type="button">
                                                            <i class="ti-calendar"></i>
                                                        </button>
                                                    </div>
                                                    @if ($errors->has('from_date'))
                                                        <span class="invalid-feedback d-block mb-10"
                                                              role="alert">
                                                            <strong>{{ @$errors->first('from_date') }}</strong>
                                                            </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 input-right-icon mb-30">
                                            <div class="primary_input">
                                                <label class="primary_input_label"
                                                       for="to_date">{{__('communicate.To Date')}} <strong
                                                        class="text-danger">*</strong>

                                                </label>
                                                <div class="primary_datepicker_input">
                                                    <div class="g-0 input-right-icon">
                                                        <div class="col">
                                                            <div class="">
                                                                <input placeholder="{{__('communicate.To Date')}} "
                                                                       class="primary_input_field primary-input date form-control  {{ @$errors->has('to_date') ? ' is-invalid' : '' }}"
                                                                       id="to_date" type="text"
                                                                       name="to_date"
                                                                       value="{{isset($editData)? date('m/d/Y', strtotime($editData->to_date)): ''}}"
                                                                       autocomplete="off">
                                                            </div>
                                                        </div>
                                                        <button class="" type="button">
                                                            <i class="ti-calendar"></i>
                                                        </button>
                                                    </div>
                                                    @if ($errors->has('to_date'))
                                                        <span class="invalid-feedback d-block mb-10"
                                                              role="alert">
                                                            <strong>{{ @$errors->first('to_date') }}</strong>
                                                            </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-20 course_section">
                                        <div class="col-lg-6">
                                            <label class="primary_input_label mt-1">{{__('communicate.Start Time')}} <span
                                                    class="text-danger">*</span></label>
                                            <div class="primary_input">
                                                <input
                                                    class="primary-input primary_input_field  time form-control{{ @$errors->has('start_time') ? ' is-invalid' : '' }}"
                                                    type="text" name="start_time"
                                                    value="{{ isset($editData) ? old('time',$editData->start_time): old('start_time')}}">

                                            </div>

                                            @if ($errors->has('start_time'))
                                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ @$errors->first('start_time') }}</strong>
                                    </span>
                                            @endif
                                        </div>
                                        <div class="col-lg-6">
                                            <label class="primary_input_label mt-1">{{__('communicate.End Time')}} <span
                                                    class="text-danger">*</span></label>
                                            <div class="primary_input">
                                                <input
                                                    class="primary-input primary_input_field  time form-control{{ @$errors->has('end_time') ? ' is-invalid' : '' }}"
                                                    type="text" name="end_time"
                                                    value="{{ isset($editData) ? old('end_time',$editData->end_time): old('end_time')}}">

                                            </div>

                                            @if ($errors->has('end_time'))
                                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ @$errors->first('end_time') }}</strong>
                                    </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row  mb-20">
                                        <div class="col-lg-6">
                                            <div class="input-effect">
                                                <label class="primary_input_label mt-1">{{__('common.URL')}} <span
                                                        class="text-danger">*</span> </label>
                                                <input
                                                    class="primary_input_field{{ $errors->has('url') ? ' is-invalid' : '' }}"
                                                    type="text" name="url" autocomplete="off"
                                                    placeholder="{{__('common.URL')}}"
                                                    value="{{isset($editData)? $editData->url : '' }}">
                                                <span class="focus-border"></span>
                                                @if ($errors->has('url'))
                                                    <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('url') }}</strong>
                                            </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="primary_input">
                                                <label class="primary_input_label"
                                                       for="">{{__('frontendmanage.Banner')}} <span class="text-danger">*</span></label>
                                                <div class="primary_file_uploader">
                                                    <input class="primary-input imgName" type="text"
                                                           id="placeholderFileOneName"
                                                           placeholder="{{isset($editData->banner) && $editData->banner != ""? @showPicName($editData->banner): trans('student.Browse Image file').'*'}}"
                                                           readonly="">
                                                    <button class="" type="button">
                                                        <label class="primary-btn small fix-gr-bg"
                                                               for="document_file_1">{{__('common.Browse')}}</label>
                                                        <input type="file" class="d-none imgBrowse" name="banner"
                                                               id="document_file_1">
                                                    </button>
                                                </div>
                                            </div>
                                            @if ($errors->has('banner'))
                                                <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $errors->first('banner') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-25">
                                            <div class="primary_input mb-15">
                                                <label class="primary_input_label"
                                                       for=" ">{{__('common.Color')}}</label>
                                                <input type="color" name="color"
                                                       class="primary_input_field color_field" value="#F13D80"
                                                       required>
                                                <span class="text-danger">{{$errors->first('color')}}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-20">
                                        <div class="col-lg-12">
                                            <div class="input-effect">
                                                <label class="primary_input_label mt-1">{{__('common.Description')}} <span class="text-danger">*</span>
                                                </label>
                                                <textarea
                                                    class="lms_summernote {{ $errors->has('description') ? ' is-invalid' : '' }}"
                                                    name="description" style="height: 150px" rows="3">
                                           {{isset($editData)? $editData->description: ''}}
                                           </textarea>
                                                @if ($errors->has('description'))
                                                    <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('description') }}</strong>
                                            </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-40">
                                        <div class="col-lg-12 text-center">
                                            <button class="primary-btn fix-gr-bg submit" type="submit"
                                                    data-bs-toggle="tooltip" title="">
                                                <i class="ti-check"></i>
                                                {{__('common.Save')}}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
                <!--/ Add Model -->
                <!-- Edit Modal -->
                <div class="modal fade admin-query" id="edit_event_model">
                    <div class="modal-dialog modal_1000px modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="">{{__('common.Update')}}</h4>
                                {{-- <span class="edit_pop_text">Edit</span> --}}
                            </div>

                            <div class="modal-body">
                                <form action="{{route('calendar_update')}}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" id="edit_calendar_id" name="calendar_id" value="">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="row pl-20">
                                                <div class="col-md-12">
                                                    <label class="primary_input_label" for="">
                                                        {{__('calender.Calender')}} {{__('common.For')}}
                                                        <strong
                                                            class="text-danger">*</strong></label>
                                                </div>

                                                <div class="col-md-6">
                                                    <input onclick="calendarForEdit(1)" type="radio"
                                                           class="common-checkbox name"
                                                           {{isset($editData)? $editData->calendar_for==1? 'checked':'':'checked'}} name="calendar_for"
                                                           id="Course_edit" value="1">
                                                    <label for="Course_edit">{{__('courses.Courses')}}</label>
                                                </div>
                                                <div class="col-md-6">
                                                    <input onclick="calendarForEdit(2)" type="radio"
                                                           class="common-checkbox name"
                                                           {{isset($editData)? $editData->calendar_for==2? 'checked':'':''}} name="calendar_for"
                                                           id="Live_Class_edit" value="2">
                                                    <label
                                                        for="Live_Class_edit">{{__('virtual-class.Live Classes')}}</label>
                                                </div>


                                            </div>
                                        </div>

                                        <div class="col-lg-6" id="course_list_edit">
                                            <label class="primary_input_label mt-1">{{__('courses.Course')}} <span class="text-danger">*</span> </label>
                                            <select
                                                class="primary_select {{ $errors->has('course') ? ' is-invalid' : '' }}"
                                                id="course_select" name="course">
                                                <option data-display="{{__('common.Select')}} {{__('courses.Course')}}*"
                                                        value="">{{__('common.Select')}} {{__('courses.Course')}} *
                                                </option>

                                                @foreach ($courses as $course)
                                                    <option
                                                        value="{{$course->id}}" {{isset($editData)? ($editData->course_id == $course->id? 'selected' : ''):"" }}>{{$course->title}}</option>
                                                @endforeach


                                            </select>
                                            @if ($errors->has('course'))
                                                <span class="invalid-feedback invalid-select" role="alert">
                                                        <strong>{{ $errors->first('course') }}</strong>
                                                    </span>
                                            @endif

                                        </div>
                                        <div class="col-lg-6" id="class_list_edit" style="display: none">
                                            <label class="primary_input_label mt-1">{{__('virtual-class.Class')}} <span class="text-danger">*</span>
                                            </label>
                                            <select
                                                class="primary_select {{ $errors->has('class') ? ' is-invalid' : '' }}"
                                                id="class_select" name="class">
                                                <option
                                                    data-display="{{__('common.Select')}} {{__('virtual-class.Class')}}*"
                                                    value="">{{__('common.Select')}} {{__('virtual-class.Class')}} *
                                                </option>

                                                @foreach ($classes as $course)
                                                    <option
                                                        value="{{$course->id}}" {{isset($editData)? ($editData->course_id == $course->id? 'selected' : ''):"" }}>{{$course->title}}</option>
                                                @endforeach


                                            </select>
                                            @if ($errors->has('class'))
                                                <span class="invalid-feedback invalid-select" role="alert">
                                                        <strong>{{ $errors->first('class') }}</strong>
                                                    </span>
                                            @endif

                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <div class="primary_input mb-25">
                                                <label class="primary_input_label" for="">{{__('common.Title')}} <strong
                                                        class="text-danger">*</strong></label>
                                                <input class="primary_input_field" id="edit_calendar_title" name="title"
                                                       placeholder="-"
                                                       type="text"
                                                       value="{{ old('title') }}" {{$errors->first('title') ? 'autofocus' : ''}}>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row course_section_edit">

                                        <div class="col-lg-6 g-0 input-right-icon mb-30">
                                            <div class="primary_input">
                                                <label class="primary_input_label"
                                                       for="from_date">{{__('communicate.From Date')}} <strong
                                                        class="text-danger">*</strong> </label>
                                                <div class="primary_datepicker_input">
                                                    <div class="g-0 input-right-icon">
                                                        <div class="col">
                                                            <div class="">
                                                                <input placeholder="{{__('communicate.From Date')}} "
                                                                       class="primary_input_field primary-input from_date date form-control  {{ @$errors->has('from_date') ? ' is-invalid' : '' }}"
                                                                       id="from_date" type="text"
                                                                       name="from_date"
                                                                       value="{{isset($editData)? date('m/d/Y', strtotime($editData->from_date)): ''}}"
                                                                       autocomplete="off">
                                                            </div>
                                                        </div>
                                                        <button class="" type="button">
                                                            <i class="ti-calendar"></i>
                                                        </button>
                                                    </div>
                                                    @if ($errors->has('from_date'))
                                                        <span class="invalid-feedback d-block mb-10"
                                                              role="alert">
                                                            <strong>{{ @$errors->first('from_date') }}</strong>
                                                            </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 g-0 input-right-icon mb-30">
                                            <div class="primary_input">
                                                <label class="primary_input_label"
                                                       for="to_date">{{__('communicate.To Date')}} <strong
                                                        class="text-danger">*</strong> </label>
                                                <div class="primary_datepicker_input">
                                                    <div class="g-0 input-right-icon">
                                                        <div class="col">
                                                            <div class="">
                                                                <input placeholder="{{__('communicate.To Date')}} "
                                                                       class="primary_input_field primary-input date to_date form-control  {{ @$errors->has('to_date') ? ' is-invalid' : '' }}"
                                                                       id="to_date" type="text"
                                                                       name="to_date"
                                                                       value="{{isset($editData)? date('m/d/Y', strtotime($editData->to_date)): ''}}"
                                                                       autocomplete="off">
                                                            </div>
                                                        </div>
                                                        <button class="" type="button">
                                                            <i class="ti-calendar"></i>
                                                        </button>
                                                    </div>
                                                    @if ($errors->has('to_date'))
                                                        <span class="invalid-feedback d-block mb-10"
                                                              role="alert">
                                                            <strong>{{ @$errors->first('to_date') }}</strong>
                                                            </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-20 course_section_edit">
                                        <div class="col-lg-6">
                                            <label class="primary_input_label mt-1">{{__('communicate.Start Time')}} <span
                                                    class="text-danger">*</span></label>
                                            <div class="primary_input">
                                                <input
                                                    class="primary-input primary_input_field start_time time form-control{{ @$errors->has('start_time') ? ' is-invalid' : '' }}"
                                                    type="text" id="start_time" name="start_time"
                                                    value="{{ isset($editData) ? old('time',$editData->start_time): old('start_time')}}">

                                            </div>

                                            @if ($errors->has('start_time'))
                                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ @$errors->first('start_time') }}</strong>
                                    </span>
                                            @endif
                                        </div>
                                        <div class="col-lg-6">
                                            <label class="primary_input_label mt-1">{{__('communicate.End Time')}} <span
                                                    class="text-danger">*</span></label>
                                            <div class="primary_input">
                                                <input
                                                    class="primary-input primary_input_field end_time time form-control{{ @$errors->has('end_time') ? ' is-invalid' : '' }}"
                                                    type="text" name="end_time" id="end_time"
                                                    value="{{ isset($editData) ? old('end_time',$editData->end_time): old('end_time')}}">

                                            </div>

                                            @if ($errors->has('end_time'))
                                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ @$errors->first('end_time') }}</strong>
                                    </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row  mb-20">
                                        <div class="col-lg-6">
                                            <div class="input-effect">
                                                <label class="primary_input_label mt-1">{{__('common.URL')}} </label>
                                                <input
                                                    class="primary_input_field{{ $errors->has('url') ? ' is-invalid' : '' }}"
                                                    type="text" id="edit_url" name="url" autocomplete="off"
                                                    placeholder="{{__('common.URL')}}"
                                                    value="{{isset($editData)? $editData->url : '' }}">
                                                <span class="focus-border"></span>
                                                @if ($errors->has('url'))
                                                    <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('url') }}</strong>
                                            </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="primary_input">
                                                <label class="primary_input_label"
                                                       for="">{{__('frontendmanage.Banner')}} <span class="text-danger">*</span></label>
                                                <div class="primary_file_uploader">
                                                    <input class="primary-input imgName edit_banner_image" type="text"
                                                           id="placeholderFileThreeName3"
                                                           placeholder="{{isset($editData->banner) && $editData->banner != ""? @showPicName($editData->banner): trans('student.Browse Image file').'*'}}"
                                                           readonly="">
                                                    <button class="" type="button">
                                                        <label class="primary-btn small fix-gr-bg"
                                                               for="document_file_3">{{__('common.Browse')}}</label>
                                                        <input type="file" class="d-none imgBrowse" name="banner"
                                                               id="document_file_3">
                                                    </button>
                                                </div>
                                            </div>
                                            @if ($errors->has('banner'))
                                                <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $errors->first('banner') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-25">
                                            <div class="primary_input mb-15">
                                                <label class="primary_input_label"
                                                       for=" ">{{__('common.Color')}}</label>
                                                <input type="color" name="color"
                                                       class="primary_input_field color_field" id="edit_color"
                                                       value="#F13D80"
                                                       required>
                                                <span class="text-danger">{{$errors->first('color')}}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-20">
                                        <div class="col-lg-12">
                                            <div class="input-effect">
                                                <label class="primary_input_label mt-1">{{__('common.Description')}} <span class="text-danger">*</span>
                                                </label>
                                                <textarea id="edit_description"
                                                          class=" {{ $errors->has('description') ? ' is-invalid' : '' }}"
                                                          name="description" style="height: 150px" rows="3">
                                           {{isset($editData)? $editData->description: ''}}
                                           </textarea>
                                                @if ($errors->has('description'))
                                                    <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('description') }}</strong>
                                            </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-40">
                                        <div class="col-lg-12 text-center">
                                            <button class="primary-btn fix-gr-bg submit" type="submit"
                                                    data-bs-toggle="tooltip" title="">
                                                <i class="ti-check"></i>
                                                {{__('common.Update')}}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
                <!--/ Edit Model -->

                {{-- delete model --}}
                <div class="modal fade admin-query" id="calendar-delete-modal">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">{{ __('common.Delete Confirmation') }}</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal">
                                    <i class="ti-close "></i>
                                </button>
                            </div>
                            <div class="modal-body">
                                <h3 class="text-center">{{__('common.Are you sure to delete ?')}}</h3>

                                <div class="col-lg-12 text-center">
                                    <div class="mt-40 d-flex justify-content-between">
                                        <button type="button" class="primary-btn tr-bg"
                                                data-bs-dismiss="modal">{{__('common.Cancel')}}</button>
                                        <a id="calendar_delete_url"
                                           class="primary-btn semi_large2 fix-gr-bg">{{__('common.Delete')}}</a>


                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
@push('scripts')

    <!-- CALENDER JS  -->
    <script src="{{asset('Modules/Calendar/Resources/assets/js')}}/calender_js/core/main.js"></script>
    <script src="{{asset('Modules/Calendar/Resources/assets/js')}}/calender_js/daygrid/main.js"></script>
    <script src="{{asset('Modules/Calendar/Resources/assets/js')}}/calender_js/timegrid/main.js"></script>
    <script src="{{asset('Modules/Calendar/Resources/assets/js')}}/calender_js/interaction/main.js"></script>
    <script src="{{asset('Modules/Calendar/Resources/assets/js')}}/calender_js/list/main.js"></script>
    <script src="{{asset('Modules/Calendar/Resources/assets/js')}}/calender_js/activation.js"></script>
    <script>
        if ($('#calendar').length > 0) {


            document.addEventListener('DOMContentLoaded', function () {
                var calendarEl = document.getElementById('calendar');
                let todayDate = document.getElementById('today_date').value;
                let system_url = document.getElementById('url').value;
                let data =<?php echo $calendars; ?>;
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    plugins: ['dayGrid', 'timeGrid', 'list', 'interaction'],
                    header: {
                        left: 'title',
                        center: 'false',
                        right: 'prev, title , next',
                    },
                    height: 954,
                    defaultDate: todayDate,
                    navLinks: true, // can click day/week names to navigate views
                    editable: false,
                    eventLimit: true, // allow "more" link when too many events
                    locale: '{{config('app.locale')}}',
                    events: data,
                    eventClick: function (info, jsEvent, view) {

                        let selectedDateView = info.event.start;
                        var date = new Date(selectedDateView);
                        var date = date.toLocaleDateString();
                        var date = new Date(date);
                        let formatedDate = date.toDateString();

                        let selectedEndDateView = info.event.end;
                        var end_date = new Date(selectedEndDateView);
                        var end_date = end_date.toLocaleDateString();
                        var end_date = new Date(end_date);
                        let formatedend_date = end_date.toDateString();

                        //Assign Variable
                        $('#viewEventDate').html(formatedDate);
                        console.log(info.event);

                        let calendar_title = info.event.title;
                        let calendar_banner = info.event.extendedProps.banner;
                        let calendar_for = info.event.extendedProps.calendar_for;
                        let calendar_description = info.event.extendedProps.description;


                        $('#calendar_title').html(calendar_title);
                        $('#calendar_description').html(calendar_description);
                        $('#calendar_for_title').html(info.event.extendedProps.course.title);
                        $('#calendar_banner').attr("src", system_url + "/" + info.event.extendedProps.banner);
                        $('#start_date').html(formatedDate);
                        $('#end_date').html(formatedend_date);
                        $('#start_time').html(info.event.extendedProps.start_time);
                        $('#end_time').html(info.event.extendedProps.end_time);

                        let calendar_info = {
                            'id': info.event.id,
                            'title': info.event.title,
                            'banner': calendar_banner,
                            'calendar_for': info.event.extendedProps.calendar_for,
                            'course_id': info.event.extendedProps.course_id,
                            'description': info.event.extendedProps.description,
                            'start': info.event.start,
                            'end': info.event.end,
                            'start_time': info.event.extendedProps.start_time,
                            'end_time': info.event.extendedProps.end_time,
                            'url': info.event.extendedProps.calendar_url,
                            'color': info.event._def.ui.backgroundColor,
                        };
                        $('#edit_button').attr('data-calendar_info', JSON.stringify(calendar_info));
                        $('#delete_button').attr('data-calendar_info', JSON.stringify(calendar_info));

                        if (info.event.extendedProps.course_id == null) {
                            $('#edit_button').hide();
                            $('#delete_button').hide();
                        } else {
                            $('#edit_button').show();
                            $('#delete_button').show();
                        }

                        var modal = $("#mile_stone_view_modal");
                        modal.modal();
                    },
                    eventRender: function (event, element) {

                        // element.click(function(e){
                        //     e.preventDefault();
                        // });
                    },
                    dateClick: function (date, jsEvent, view) {
                        // Select Fields


                        let selectedDate = date.dateStr;
                        var date = new Date(selectedDate);
                        let formatedDate = date.toDateString();

                        //Assign Variable
                        let selectedDateShow = $('#selectedDateShow').html(formatedDate);

                        console.log(formatedDate);
                        $('#add_event_model').modal('show');
                    },
                });

                calendar.render();
            });

        }

        function deleteCalendar() {
            let system_url = document.getElementById('url').value;
            let delete_data = JSON.parse($('#delete_button').attr('data-calendar_info'));
            let calendar_delete = system_url + "/" + "calendar/delete/" + delete_data.id;
            $('#calendar_delete_url').attr('href', calendar_delete);
            // console.log(calendar_delete);
            $('#calendar-delete-modal').modal('show');
        }

        function editModal() {
            let edit_data = JSON.parse($('#edit_button').attr('data-calendar_info'));

            // console.log(edit_data);

            let start_date = new Date(edit_data.start)
            year = start_date.getFullYear();
            month = start_date.getMonth() + 1;
            dt = start_date.getDate();

            if (dt < 10) {
                dt = '0' + dt;
            }
            if (month < 10) {
                month = '0' + month;
            }

            start_date = month + '/' + dt + '/' + year;

            let end_date = new Date(edit_data.end)
            year = end_date.getFullYear();
            month = end_date.getMonth() + 1;
            dt = end_date.getDate();

            if (dt < 10) {
                dt = '0' + dt;
            }
            if (month < 10) {
                month = '0' + month;
            }

            end_date = month + '/' + dt + '/' + year;
            $('#edit_calendar_id').val(edit_data.id);
            $('#edit_calendar_title').val(edit_data.title);
            $('.from_date').val(start_date);
            $('.to_date').val(end_date);
            $('.start_time').val(edit_data.start_time);
            $('.end_time').val(edit_data.end_time);
            $('#edit_url').val(edit_data.url);
            $('#edit_color').val(edit_data.color);
            // $('#edit_description').html(edit_data.description);
            // $("#edit_description").summernote("code", edit_data.description);
            let element = $("#edit_description");
            element.summernote({
                tabsize: 2,
                code: edit_data.description,
                height: 180,
                tooltip: true,
                callbacks: {
                    onImageUpload: function (files) {
                        sendFile(files, "#edit_description", element.attr('name'))
                    }
                }
            });

            $('#course_select').val(edit_data.course_id);
            $('#class_select').val(edit_data.course_id);
            $('.edit_banner_image').attr('placeholder', edit_data.banner);

            $('#course_select').niceSelect('update');
            $('#class_select').niceSelect('update');
            let course_list_edit = document.getElementById('course_list_edit');
            let class_list_edit = document.getElementById('class_list_edit');
            let course_section_edit = $('.course_section_edit');
            if (edit_data.calendar_for == 1) {
                $('#Course_edit').attr('checked', 'checked');
                course_list_edit.style.display = 'block';
                class_list_edit.style.display = 'none';
                course_section_edit.show();
            } else {
                $('#Live_Class_edit').attr('checked', 'checked');
                course_list_edit.style.display = 'none';
                class_list_edit.style.display = 'block';
                course_section_edit.hide();
            }

            $('#edit_event_model').modal('show');
        }


    </script>
    <script>
        function calendarFor(val) {
            let course_list = document.getElementById('course_list');
            let class_list = document.getElementById('class_list');
            let course_section = $('.course_section');
            if (val == 1) {
                course_list.style.display = 'block';
                class_list.style.display = 'none';
                course_section.show();
            } else {
                course_list.style.display = 'none';
                class_list.style.display = 'block';
                course_section.hide();
            }
        }

        function calendarForEdit(val) {
            let course_list_edit = document.getElementById('course_list_edit');
            let class_list_edit = document.getElementById('class_list_edit');
            let course_section_edit = $('.course_section_edit');
            if (val == 1) {
                course_list_edit.style.display = 'block';
                class_list_edit.style.display = 'none';
                course_section_edit.show();
            } else {
                course_list_edit.style.display = 'none';
                class_list_edit.style.display = 'block';
                course_section_edit.hide();
            }
        }


    </script>
@endpush
