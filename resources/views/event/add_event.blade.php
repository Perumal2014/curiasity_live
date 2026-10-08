@extends('backend.master')
@push('styles')
    <link rel="stylesheet" href="{{asset('public/backend/css/class.css')}}"/>
@endpush
@php
    $table_name='lms_notice_boards';
@endphp
@section('table')
    {{$table_name}}
@endsection
@section('mainContent')

    {{generateBreadcrumb()}}

    <section class="admin-visitor-area up_admin_visitor">
        <div class="container-fluid p-0">
            @if(isset($editData))

                <div class="row">
                    <div class="offset-lg-10 col-lg-2 text-end col-md-12 mb-20">
                        <a href="{{route('event')}}" class="primary-btn small fix-gr-bg">
                            <span class="ti-plus pe-2"></span>
                            @lang('common.Add')
                        </a>
                    </div>
                </div>

            @endif
            <div class="row">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="white-box">
                                <div class="main-title">
                                    <h3 class="mb-20">@if(isset($editData))
                                            {{__('common.Edit')}}
                                        @else
                                            {{__('common.Add')}}
                                        @endif
                                        {{__('communicate.Event')}}
                                    </h3>
                                </div>
                                @if(isset($editData))
                                    @if (permissionCheck('event-edit'))
                                        <form class="form-horizontal" method="POST" action="{{ route('event-update', $editData->id) }}" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            @endif
                                            @else
                                                @if (permissionCheck('event-store'))
                                                    <form class="form-horizontal" method="POST" action="{{ route('event') }}" enctype="multipart/form-data">
                                                        @csrf
                                                        @endif
                                                        @endif

                                                        <div class="add-visitor">
                                    <div class="row">
                                        <div class="col-lg-12 mb-20">
                                            <div class="input-effect">
                                                <label class="primary_input_label mt-1">{{__('communicate.Event')}} {{__('common.Title')}} <span
                                                        class="text-danger">*</span> </label>
                                                <input
                                                    class="primary_input_field {{ $errors->has('event_title') ? ' is-invalid' : '' }}"
                                                    type="text" name="event_title" autocomplete="off"
                                                    placeholder="{{__('common.Title')}}"
                                                    value="{{ old('event_title',isset($editData)? $editData->event_title : '')}}">
                                                <span class="focus-border"></span>

                                            </div>
                                        </div>

                                        <div class="col-lg-6 mb-20">
                                            <label class="primary_input_label mt-1">{{__('communicate.Event')}} {{__('communicate.For Whom')}} <span
                                                    class="text-danger">*</span> </label>
                                            <select
                                                class="primary_select mb-25 {{ $errors->has('for_whom') ? ' is-invalid' : '' }}"
                                                id="for_whom" name="for_whom">
                                                <option data-display="{{__('communicate.For Whom')}} *"
                                                        value="">{{__('communicate.For Whom')}} *
                                                </option>

                                                <option
                                                    value="All" {{isset($editData)? ($editData->for_whom == 'All'? 'selected' : ''):"" }}>
                                                    {{__('common.All')}}
                                                </option>
                                                @foreach ($roles as $role)
                                                    <option
                                                        value="{{$role->name}}" {{old('for_whom',isset($editData)? $editData->for_whom:'') == $role->name? 'selected' : ''}}>{{$role->name}}</option>
                                                @endforeach


                                            </select>


                                        </div>
                                        <div class="col-lg-6 mb-20">
                                            <div class="input-effect">
                                                <label class="primary_input_label mt-1">{{__('communicate.Event')}} {{__('communicate.Location')}} </label>
                                                <input
                                                    class="primary_input_field{{ $errors->has('event_location') ? ' is-invalid' : '' }}"
                                                    type="text" name="event_location" autocomplete="off"
                                                    placeholder="{{__('communicate.Location')}}"
                                                    value="{{ old('event_location',isset($editData)? $editData->event_location :'') }}">
                                                <span class="focus-border"></span>

                                            </div>

                                        </div>

                                        <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">

                                    </div>
                                    <div class="row">

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
                                                                       value="{{old('from_date',isset($editData)? date('m/d/Y', strtotime($editData->from_date)): '')}}"
                                                                       autocomplete="off" required>
                                                            </div>
                                                        </div>
                                                        <button class="" type="button">
                                                            <i class="ti-calendar"></i>
                                                        </button>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 input-right-icon mb-30">
                                            <div class="primary_input">
                                                <label class="primary_input_label"
                                                       for="to_date">{{__('communicate.To Date')}} <strong
                                                        class="text-danger">*</strong> </label>
                                                <div class="primary_datepicker_input">
                                                    <div class="g-0 input-right-icon">
                                                        <div class="col">
                                                            <div class="">
                                                                <input placeholder="{{__('communicate.To Date')}} "
                                                                       class="primary_input_field primary-input date form-control  {{ @$errors->has('to_date') ? ' is-invalid' : '' }}"
                                                                       id="to_date" type="text"
                                                                       name="to_date"
                                                                       value="{{old('to_date',isset($editData)? date('m/d/Y', strtotime($editData->to_date)): '')}}"
                                                                       autocomplete="off" required>
                                                            </div>
                                                        </div>
                                                        <button class="" type="button">
                                                            <i class="ti-calendar"></i>
                                                        </button>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-20">
                                        <div class="col-lg-6">
                                            <label class="primary_input_label mt-1">{{__('communicate.Start Time')}} <span
                                                    class="text-danger">*</span></label>
                                            <div class="primary_input">
                                                <input required
                                                       class="primary-input primary_input_field  time form-control{{ @$errors->has('start_time') ? ' is-invalid' : '' }}"
                                                       type="text" name="start_time"
                                                       value="{{ old('start_time',isset($editData) ? $editData->start_time:'')}}">

                                            </div>


                                        </div>
                                        <div class="col-lg-6">
                                            <label class="primary_input_label mt-1">{{__('communicate.End Time')}} <span
                                                    class="text-danger">*</span></label>
                                            <div class="primary_input">
                                                <input required
                                                       class="primary-input primary_input_field  time form-control{{ @$errors->has('end_time') ? ' is-invalid' : '' }}"
                                                       type="text" name="end_time"
                                                       value="{{  old('end_time',isset($editData) ? $editData->end_time :'')}}">

                                            </div>


                                        </div>
                                    </div>
                                    <div class="row  mb-20">
                                        <div class="col-lg-6">
                                            <div class="input-effect">
                                                <label class="primary_input_label mt-1">{{__('communicate.Event')}} {{__('common.URL')}}  </label>
                                                <input
                                                    class="primary_input_field{{ $errors->has('event_url') ? ' is-invalid' : '' }}"
                                                    type="text" name="event_url" autocomplete="off"
                                                    placeholder="{{__('common.URL')}}"
                                                    value="{{old('event_url',isset($editData)? $editData->url : '' )}}">
                                                <span class="focus-border"></span>

                                            </div>
                                        </div>
                                        <div class="col-lg-6">


                                            <x-upload-file
                                                name="image"
                                                required="true"
                                                type="image"
                                                media_id="{{isset($editData)?$editData->image_media?->media_id:''}}"
                                                label="{{ __('common.Image') }}"/>

                                        </div>
                                    </div>

                                    <div class="row mb-20">
                                        <div class="col-lg-6">
                                            <div class="row pl-20">
                                                <div class="col-md-12">
                                                    <label class="primary_input_label"
                                                           for="">{{__('setting.Host Type')}} <strong
                                                            class="text-danger">*</strong></label>
                                                </div>

                                                <div class="col-md-3">
                                                    <input onclick="hostType(1)" type="radio"
                                                           class="common-checkbox name"
                                                           {{old('host_type',isset($editData)? $editData->host_type:'')==1? 'checked':''}} name="host_type"
                                                           id="Internal" value="1">
                                                    <label for="Internal">{{__('setting.Internal')}}</label>
                                                </div>
                                                <div class="col-md-3">
                                                    <input onclick="hostType(2)" type="radio"
                                                           class="common-checkbox name"
                                                           {{old('host_type',isset($editData)? $editData->host_type:'')==2? 'checked':''}} name="host_type"
                                                           id="External" value="2">
                                                    <label for="External">{{__('setting.External')}}</label>
                                                </div>


                                            </div>
                                        </div>

                                        <div class="col-lg-6" id="internal_list">
                                            <label class="primary_input_label mt-1">{{__('communicate.Event')}} {{__('communicate.Host')}} <span
                                                    class="text-danger">*</span> </label>
                                            <select
                                                class="primary_select {{ $errors->has('instructor') ? ' is-invalid' : '' }}"
                                                id="instructor" name="instructor">
                                                <option
                                                    data-display="{{__('common.Select')}} {{__('communicate.Host')}}*"
                                                    value="">{{__('common.Select')}} {{__('communicate.Host')}} *
                                                </option>

                                                @foreach ($instructors as $instructor)
                                                    <option
                                                        value="{{$instructor->name}}" {{old('instructor',isset($editData)?$editData->host:'')  == $instructor->name? 'selected' : '' }}>{{$instructor->name}}</option>
                                                @endforeach


                                            </select>


                                        </div>
                                        <div class="col-lg-6" id="external_input" style="display: none">
                                            <div class="input-effect">
                                                <label class="primary_input_label mt-1">{{__('communicate.Event')}} {{__('communicate.Host')}} <span
                                                        class="text-danger">*</span> </label>
                                                <input
                                                    class="primary_input_field{{ $errors->has('host_name') ? ' is-invalid' : '' }}"
                                                    type="text" name="host_name" autocomplete="off"
                                                    placeholder="{{__('communicate.Host')}}"
                                                    value="{{old('host_name',isset($editData)? $editData->host_type:'')==2? $editData->host : ''}}">
                                                <span class="focus-border"></span>

                                            </div>

                                        </div>
                                    </div>
                                    <div class="row mb-20">
                                        <div class="col-lg-12">
                                            <div class="input-effect">
                                                <label class="primary_input_label mt-1">{{__('common.Description')}} <span class="text-danger">*</span>
                                                </label>
                                                <textarea
                                                    class="lms_summernote {{ $errors->has('event_des') ? ' is-invalid' : '' }}"
                                                    name="event_des" style="height: 150px" rows="3">
                                           {{old('event_des',isset($editData)? $editData->event_des: '')}}
                                           </textarea>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-12 text-center d-flex justify-content-center align-content-center">
                                            <button class="primary-btn fix-gr-bg submit" data-bs-toggle="tooltip"
                                                    title="">
                                                <i class="ti-check"></i>
                                                @if(isset($editData))
                                                    {{__('common.Update')}}
                                                @else
                                                    {{__('common.Save')}}
                                                @endif
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
@push('scripts')
    <script src="{{asset('public/backend/js/category.js')}}"></script>

    <script>
        function hostType(val) {
            let internal_list = document.getElementById('internal_list');
            let external_input = document.getElementById('external_input');
            if (val == 1) {
                internal_list.style.display = 'block';
                external_input.style.display = 'none';
            } else {
                internal_list.style.display = 'none';
                external_input.style.display = 'block';
            }
        }
    </script>
@endpush
