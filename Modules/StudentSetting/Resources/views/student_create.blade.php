@php use Illuminate\Support\Facades\Auth; @endphp
@extends('backend.master')
@push('styles')
    <link rel="stylesheet" href="{{asset('public/backend/css/student_list.css')}}"/>
    <style>
        select {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 14px;
    background: #fff;
    box-sizing: border-box;
}

select:focus {
    outline: none;
    border-color: #4a90e2;
}
    </style>
@endpush
@php
    $table_name='users';
@endphp
@section('table')
    {{$table_name}}
@endsection

@section('mainContent')

    {!! generateBreadcrumb() !!}

    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row justify-content-center">

                <div class="col-lg-12 ">
                    <div class="QA_section QA_section_heading_custom check_box_table">
                        <div class="QA_table ">
                            <div class="white-box">
                                <form action="{{isset($user)?route('student.update'):route('student.store')}}"
                                      method="POST"
                                      enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="id" value="{{isset($user)?$user->id:''}}">
                                    <div class="row">
                                        @if (Auth::user()->tenant_id == '4' || Auth::user()->tenant_id == '3')
                                        <div class="col-xl-4">
                                            <div class="primary_input mb-25">
                                                <label class="primary_input_label" for="">{{__('common.RollNo')}}
                                                    <strong
                                                        class="text-danger">*</strong></label>
                                                <input class="primary_input_field" name="student_roll_no" placeholder="-"
                                                       type="text" id="addRollNo"
                                                       value="{{ old('student_roll_no',isset($stud_detail)?$stud_detail->student_roll_no:'') }}" {{$errors->first('student_roll_no') ? 'autofocus' : ''}}>
                                            </div>
                                        </div>
                                        @endif
                                        <div class="col-xl-4">
                                            <div class="primary_input mb-25">
                                                <label class="primary_input_label" for="">{{__('common.Name')}}
                                                    <strong
                                                        class="text-danger">*</strong></label>
                                                <input class="primary_input_field" name="name" placeholder="-"
                                                       type="text" id="addName"
                                                       value="{{ old('name',isset($user)?$user->name:'') }}" {{$errors->first('name') ? 'autofocus' : ''}}>
                                            </div>
                                        </div>
                                        <div class="col-xl-4">
                                            <div class="primary_input mb-25">
                                                <label class="primary_input_label"
                                                    for="">{{__('common.Level')}}
                                                </label>
                                                <select class="primary_select mb-25" name="learning_level" id="learning_level">
                                                    <option value="">{{__('common.Select')}}</option>
                                                    @php
                                                    $course_levels = \Modules\CourseSetting\Entities\CourseLevel::get();
                                                    @endphp
                                                    @if (!empty($course_levels))
                                                    @foreach($course_levels as $course_level)
                                                        <option value="{{ $course_level->id }}"
                                                            @php
                                                                if (isset($stud_detail) && $stud_detail->learning_level == $course_level->id) echo 'selected';
                                                            @endphp>
                                                            {{ $course_level->title }}
                                                        </option>
                                                    @endforeach
                                                    @endif
                                                </select>

                                            </div>
                                        </div>

                                        <div class="col-xl-4">
                                            <div class="primary_input mb-25">
                                                <label class="primary_input_label"
                                                    for="">{{__('common.Department')}}
                                                </label>
                                                <select class="primary_select mb-25" name="department_id" id="department_id">
                                                    <option value="">{{__('common.Select')}}</option>
                                                    @php
                                                    $departments = \Modules\SystemSetting\Entities\Department::where('organization_id', Auth::user()->organization_id)->get();
                                                    @endphp
                                                    @if (!empty($departments))
                                                    @foreach($departments as $department)
                                                        <option value="{{ $department->id }}"
                                                            @php
                                                                if (isset($department->department_id) && ($department->id == $stud_detail->department_id)) echo 'selected';
                                                            @endphp>
                                                            {{ $department->name }}
                                                        </option>
                                                    @endforeach
                                                    @endif
                                                </select>

                                            </div>
                                        </div>
                                        
                                    </div>
                                    
                                    <div class="row">
                                        @if (Auth::user()->tenant_id == '4')
                                        <div class="col-xl-4">
                                            <div class="primary_input mb-25">
                                                <label class="primary_input_label"
                                                    for="">{{__('common.Year')}}
                                                </label>
                                                <select class="primary_select mb-25" name="student_year" id="student_year">

                                                    <option value="">{{__('common.Select')}}</option>
                                                    <option value="1" @if(isset($stud_detail) && $stud_detail->student_year == 1) selected @endif>1st Year</option>
                                                    <option value="2" @if(isset($stud_detail) && $stud_detail->student_year == 2) selected @endif>2nd Year</option>
                                                    <option value="3" @if(isset($stud_detail) && $stud_detail->student_year == 3) selected @endif>3rd Year</option>
                                                    <option value="4" @if(isset($stud_detail) && $stud_detail->student_year == 4) selected @endif>4th Year</option>
                                                </select>

                                            </div>
                                        </div>
                                        @endif
                                        <div class="col-xl-4">
                                            <div class="primary_input mb-15">
                                                <label class="primary_input_label"
                                                       for="">{{__('common.Date of Birth')}}
                                                </label>
                                                <div class="primary_datepicker_input">
                                                    <div class="g-0  input-right-icon">
                                                        <div class="col">
                                                            <div class="">
                                                                <input placeholder="{{__('common.Date')}}"
                                                                       class="primary_input_field primary-input date form-control"
                                                                       id="startDate" type="text" name="dob" data-prevent-future="1"
                                                                       data-prevent-future="1"
                                                                       value="{{ old('dob',isset($user)?$user->dob:'') }}"
                                                                       autocomplete="off" {{$errors->first('dob') ? 'autofocus' : ''}}>
                                                            </div>
                                                        </div>
                                                        <button class="" type="button">
                                                            <i class="ti-calendar" id="start-date-icon"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-4">
                                            <div class="primary_input mb-25">
                                                <label class="primary_input_label"
                                                       for="">{{__('common.Phone')}} </label>
                                                <input class="primary_input_field phoneNumberInput"
                                                       value="{{ old('phone',isset($user)?$user->phone:'') }}"
                                                       name="phone" id="addPhone"
                                                       placeholder="-"
                                                       type="number" {{$errors->first('phone') ? 'autofocus' : ''}}>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <div class="row">
                                        <div class="col-xl-4">
                                            <div class="primary_input mb-25">
                                                <label class="primary_input_label"
                                                       for="">{{__('common.Parent Phone')}} </label>
                                                <input class="primary_input_field phoneNumberInput"
                                                       value="{{ old('parent_no',isset($stud_detail)?$stud_detail->parent_no:'') }}"
                                                       name="parent_no" id="addPhone"
                                                       placeholder="-"
                                                       type="number" {{$errors->first('parent_no') ? 'autofocus' : ''}}>
                                            </div>
                                        </div>
                                        <div class="col-xl-4">
                                            <div class="primary_input mb-25">
                                                <label class="primary_input_label" for="">{{__('common.Email')}}
                                                    <strong
                                                        class="text-danger">*</strong></label>
                                                <input class="primary_input_field" name="email" placeholder="-"
                                                       value="{{ old('email',isset($user)?$user->email:'') }}"
                                                       id="addEmail"
                                                       {{$errors->first('email') ? 'autofocus' : ''}}
                                                       type="email">
                                            </div>
                                        </div>

                                        <div class="col-xl-4">
                                            <div class="primary_input mb-35">
                                                <label class="primary_input_label" for="">{{__('common.gender')}}
                                                </label>

                                                <select class="primary_select"
                                                        data-course_id="{{@$course->id}}" name="gender">
                                                    <option
                                                        data-display="{{__('common.Select')}} {{__('common.gender')}}"
                                                        value="">{{__('common.Select')}} {{__('common.gender')}} </option>

                                                    <option
                                                        value="male" {{(old('gender',isset($user)?$user->gender:'')=='male')?'checked':''}}>{{__('common.Male')}}</option>
                                                    <option
                                                        value="female" {{(old('gender',isset($user)?$user->gender:'')=='female')?'checked':''}}>{{__('common.Female')}}</option>
                                                    <option
                                                        value="other" {{(old('gender',isset($user)?$user->gender:'')=='other')?'checked':''}}>{{__('common.Other')}}</option>


                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-xl-12">
                                            <div class="primary_input mb-35">
                                                <label class="primary_input_label d-flex"
                                                        for="">Address </label>
                                                <textarea class="lms_summernote"
                                                            name="address" id=""
                                                            cols="30"

                                                            rows="10">{{old('address')}}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-xl-4">
                                            <div class=" mb-35">
                                                <x-upload-file
                                                    name="image"
                                                    type="image"
                                                    media_id="{{isset($user)?$user->image_media?->media_id:''}}"
                                                    label="{{__('common.Image')}}"
                                                    note=""/>

                                            </div>
                                        </div>
                                        <div class="col-xl-4" style="display: none">
                                            <div class="primary_input mb-35">
                                                <label class="primary_input_label" for="">Country
                                                </label>

                                                <select id="country" name="country_id" class="primary_select form-control">
                                                    <option value="">Select Country</option>
                                                    @foreach($countries as $country)
                                                        <option value="{{ $country['id'] }}" @if(isset($user) && $user->country == $country['id']) selected @endif>
                                                            {{ $country['name'] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                        
                                            </div>
                                        </div>

                                        <div class="col-xl-4" style="display: none">
                                            <div class="primary_input mb-35">
                                                <label class="primary_input_label d-flex"
                                                        for="">State</label>
                                                <select id="state" name="state_id" class="primary_input_field primary-input primary_select form-control">
                                                    <option value="">Select State</option>
                                                </select>
                                            </div>
                                        </div>
                                       
                                        <div class="col-xl-4">
                                            <div class="primary_input mb-25">
                                                <label class="primary_input_label"
                                                       for="">{{__('common.City')}}</label>
                                                <input class="primary_input_field" name="city" placeholder="-"
                                                       id="addCity"
                                                       type="text"
                                                       value="{{ old('city',isset($user)?$user->city:'') }}">
                                            </div>
                                        </div>
                                        @if (!isset($user))
                                        <div class="col-xl-4">
                                            <div class="primary_input mb-25">
                                                <label class="primary_input_label" for="">{{ __('common.Password') }}
                                                    ({{trans('Minimum 8 Letter')}}) <span class="required_mark">*</span></label>
                                                <input name="password" class="primary_input_field name"
                                                    autocomplete="new-password"
                                                    placeholder="{{ __('common.Password') }}" type="password" minlength="6">
                                                <span class="text-danger">{{$errors->first('password')}}</span>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="row">
                                        
                                        
                                        <div class="col-xl-4">
                                            <div class="primary_input mb-25">
                                                <label class="primary_input_label"
                                                       for="">{{__('common.LinkedIn URL')}}</label>
                                                <input class="primary_input_field" name="linkedin" placeholder="-"
                                                       id="addLinked"
                                                       type="text"
                                                       value="{{ old('linkedin',isset($user)?$user->linkedin:'') }}">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-12 text-center pt_15">
                                        <div class="d-flex justify-content-center">
                                            <button class="primary-btn semi_large2  fix-gr-bg"
                                                    id="save_button_parent"
                                                    type="submit"><i
                                                    class="ti-check"></i> {{isset($user)?__('common.Update'):__('common.Save')}} {{__('student.Student')}}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </section>

@endsection
@push('scripts')
    <script>
        const BASEURL = "{{ url('/') }}";
        const TENANT = "{{ request()->segment(1) }}";

    </script>
    <script>
        const EDIT_COUNTRY_ID = "{{ $countryId ?? '' }}";
        const EDIT_STATE_ID  = "{{ $stateId ?? '' }}";
    </script>

    <script src="{{asset('public/backend/js/student_list.js')}}"></script>

@endpush
