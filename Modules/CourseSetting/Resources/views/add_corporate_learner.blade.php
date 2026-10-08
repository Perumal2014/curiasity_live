@extends('backend.master')
@push('styles')
    <style>
        .select2-container--default .select2-selection--single {
            background-color: #fff;
            width: 100%;
            height: 46px;
            line-height: 46px;
            font-size: 13px;
            padding: 3px 20px;
            padding-left: 20px;
            font-weight: 300;
            border-radius: 30px;
            color: var(--base_color);
            border: 1px solid #ECEEF4
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 46px;
            position: absolute;
            top: 1px;
            right: 20px;
            width: 20px;
            color: var(--text-color);
        }

        .select2-dropdown {
            background-color: white;
            border: 1px solid var(--backend-border-color);
            border-radius: 4px;
            box-sizing: border-box;
            display: block;
            position: absolute;
            left: -100000px;
            width: 100%;
            width: 100%;
            background: var(--bg_white);
            overflow: auto !important;
            border-radius: 0px 0px 10px 10px;
            margin-top: 1px;
            z-index: 9999 !important;
            border: 0;
            box-shadow: 0px 10px 20px rgb(108 39 255 / 30%);
            z-index: 1051;
            min-width: 200px;
        }

        .select2-search--dropdown .select2-search__field {
            padding: 4px;
            width: 100%;
            box-sizing: border-box;
            box-sizing: border-box;
            background-color: #fff;
            border: 1px solid rgba(130, 139, 178, 0.3) !important;
            border-radius: 3px;
            box-shadow: none;
            color: #333;
            display: inline-block;
            vertical-align: middle;
            padding: 0px 8px;
            width: 100% !important;
            height: 46px;
            line-height: 46px;
            outline: 0 !important;
        }

        .select2-container {
            width: 100% !important;
            min-width: 90px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: var(--dynamic-text-color);
            line-height: 40px;
        }
        .select2-container--default .select2-selection--multiple {
                    border-radius: 6px;
                    padding: 4px;
        }

        .makeResize.responsiveResize.col-xl-6 {
            /*margin-top: 30px;*/
        }

        #durationBox {
            /*margin-top: 30px;*/
        }

        @media (max-width: 1199px) {
            .responsiveResize2 {
                margin-top: 30px;
            }
        }

        .is-invalid{
            border:1px solid red !important;
        }

        select.error + .select2 .select2-selection {
    border: 1px solid red !important;
}
    </style>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet"/>
@endpush
@php
    $table_name='courses';
@endphp
@section('table')
    {{$table_name}}
@stop
@section('mainContent')
    @php
        $required_type =false;
        if(isModuleActive('Org')){
            $required_type =true;
        }
    @endphp
    {!! generateBreadcrumb() !!}
    <section class="admin-visitor-area up_st_admin_visitor">


        <div class="white_box mb_30  student-details header-menu">
            <!-- <div class="white_box_tittle list_header">
                <h4>{{__('common.Add New')}} {{__('quiz.Topic')}}</h4>
            </div> -->
            <div class="col-lg-12">


                <input type="hidden" id="url" value="{{url('/')}}">

                <form id="learnerForm" action="{{route('AdminSaveLearner')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    @php
                        $LanguageList = getLanguageList();
                    @endphp
                    <div class="row pt-0">
                        @if(isModuleActive('FrontendMultiLang'))
                            <ul class="nav nav-tabs no-bottom-border  mt-sm-md-20 mb-10 ms-3"
                                role="tablist">
                                @foreach ($LanguageList as $key => $language)
                                    <li class="nav-item">
                                        <a class="nav-link  @if (auth()->user()->language_code == $language->code) active @endif"
                                           href="#element{{$language->code}}"
                                           role="tab"
                                           data-bs-toggle="tab">{{ $language->native }}  </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                    <div class="tab-content">
                        @foreach ($LanguageList as $key => $language)
                            <div role="tabpanel"
                                 class="tab-pane fade @if (auth()->user()->language_code == $language->code) show active @endif  "
                                 id="element{{$language->code}}">
                                <div class="row">

                                    <div class="col-xl-12">
                                        <div class="row">
                                            <div class="col-xl-4 courseBox mb_30">

                                                <label class="primary_input_label d-flex">
                                                    {{ __('common.Name') }}
                                                    <span class="required_mark">*</span>
                                                </label>

                                                <input type="text"
                                                    name="name"
                                                    id="name"
                                                    class="primary_input_field name"
                                                    placeholder="{{ __('common.Name') }}"
                                                    value="{{ old('name', $editUser->name ?? '') }}"
                                                    {{ $errors->has('name') ? 'autofocus' : '' }}>

                                                @if ($errors->has('name'))
                                                    <span class="text-danger">
                                                        <strong>{{ $errors->first('name') }}</strong>
                                                    </span>
                                                @endif

                                            </div>
                                            <div class="col-xl-4 courseBox mb_30">
                                                <label class="primary_input_label d-flex"
                                                       for="">{{__('common.Email')}} <span
                                                        class="required_mark">*</span>
                                                </label>
                                                <input name="email" id="email"
                                                       class="primary_input_field email" placeholder="{{__('common.Email')}}"
                                                       value="{{ old('email', $editUser->email ?? '') }}">
                                                @if ($errors->has('email'))
                                                    <span class="text-danger"><strong>{{ $errors->first('email') }}</strong></span>
                                                @endif    

                                            </div>
                                            <div class="col-xl-4 courseBox">
                                                <div class="primary_input mb-25">

                                                    <div class="row pt-2" id="complete_course">
                                                        <div class="col-md-4 mb-25">
                                                            <label class="primary_input_label mt-1"
                                                                for=""> {{__('common.Gender')}}</label>
                                                        </div>
                                                        <div class="col-md-2 mb-25" id="gender">
                                                            <label class="primary_checkbox d-flex mr-12">
                                                                <input type="radio" class="gender0"
                                                                    id="gender0" name="gender"
                                                                    value="male" @if (old('gender', $editUser->gender ?? '') == 'male') checked @endif>
                                                                <span class="checkmark me-2"></span> {{__('common.Male')}}</label>
                                                        </div>
                                                        <div class="col-md-2 mb-25" id="comp_cour_yes">
                                                            <label class="primary_checkbox d-flex mr-12">
                                                                <input type="radio" class="gender1"
                                                                    id="gender1" name="gender"
                                                                    value="female" @if (old('gender', $editUser->gender ?? '') == 'female') checked @endif>
                                                                <span class="checkmark me-2"></span>{{__('common.Female')}}</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            

                                            <div class="col-xl-4 courseBox mb_30">
                                                <label class="primary_input_label d-flex"
                                                    for="">{{__('common.Profile')}} <span
                                                        class="required_mark">*</span>
                                                </label>
                                                <select class="primary_select profile_id" name="profile"
                                                        id="profile_id" {{$errors->has('profile') ? 'autofocus' : ''}}>
                                                    <option data-display="{{__('common.Select')}}"
                                                            value="">{{__('common.Select')}} {{__('common.Profile')}} <span
                                                            class="required_mark">*</span></option>
                                                            @foreach($profiles as $profile)
                                                                <option value="{{$profile->id}}" {{old('profile', $editUser->profile_id ?? '') == $profile->id ? 'selected' : ''}}>
                                                                    {{$profile->profile_name}}
                                                                </option>
                                                            @endforeach
                                                </select>
                                            </div>

                                            <div class="col-xl-4 courseBox mb_30">
                                                <label class="primary_input_label d-flex"
                                                    for="">{{__('common.Business Unit')}} <span
                                                        class="required_mark">*</span>
                                                </label>
                                                <select class="primary_select category_id" name="business_unit"
                                                        id="bu_id" {{$errors->has('business_unit') ? 'autofocus' : ''}}>
                                                    <option data-display="{{__('common.Select')}}"
                                                            value="">{{__('common.Select')}} {{__('common.Business Unit')}} <span
                                                            class="required_mark">*</span></option>
                                                            @foreach($businessUnits as $bu)
                                                                <option value="{{$bu->id}}" {{old('business_unit', $editUser->bu ?? '') == $bu->id ? 'selected' : ''}}>
                                                                    {{$bu->bu_name}}
                                                                </option>
                                                            @endforeach
                                                </select>
                                            </div>

                                            <div class="col-xl-4 courseBox mb_30">
                                                <label class="primary_input_label d-flex"
                                                    for="">{{__('common.Department')}} <span
                                                        class="required_mark">*</span>
                                                </label>
                                                <select class="primary_select category_id" name="department"
                                                        id="dept_id" {{$errors->has('department') ? 'autofocus' : ''}}>
                                                    <option data-display="{{__('common.Select')}}"
                                                            value="">{{__('common.Select')}} {{__('common.Department')}} <span
                                                            class="required_mark">*</span></option>
                                                            @foreach($departments as $dept)
                                                                <option value="{{$dept->id}}" {{old('department', $editUser->dept_id ?? '') == $dept->id ? 'selected' : ''}}>
                                                                    {{$dept->dept_name}}
                                                                </option>
                                                            @endforeach
                                                </select>
                                            </div>

                                            <div class="col-xl-4 courseBox mb_30">
                                                <label class="primary_input_label d-flex"
                                                    for="">{{__('common.Country')}} <span
                                                        class="required_mark">*</span>
                                                </label>
                                                <select class="primary_select category_id" name="country"
                                                        id="country_id" {{$errors->has('country') ? 'autofocus' : ''}}>
                                                    <option data-display="{{__('common.Select')}}"
                                                            value="">{{__('common.Select')}} {{__('common.Country')}} <span
                                                            class="required_mark">*</span></option>
                                                            @foreach($countries as $country)
                                                                <option value="{{$country->id}}" {{old('country', $editUser->country ?? '') == $country->id ? 'selected' : ''}}>
                                                                    {{$country->country_name}}
                                                                </option>
                                                            @endforeach
                                                </select>
                                            </div>

                                            <div class="col-xl-4 courseBox mb_30">
                                                <label class="primary_input_label d-flex"
                                                    for="">{{__('common.State')}} <span
                                                        class="required_mark">*</span>
                                                </label>
                                                <select class="primary_select category_id" name="state"
                                                        id="state_id" {{$errors->has('state') ? 'autofocus' : ''}}>
                                                    <option data-display="{{__('common.Select')}}"
                                                            value="">{{__('common.Select')}} {{__('common.State')}} <span
                                                            class="required_mark">*</span></option>
                                                            @foreach($states as $state)
                                                                <option value="{{$state->id}}" {{old('state', $editUser->state ?? '') == $state->id ? 'selected' : ''}}>
                                                                    {{$state->state_name}}
                                                                </option>
                                                            @endforeach
                                                </select>
                                            </div>

                                            <div class="col-xl-4 courseBox mb_30">
                                                <label class="primary_input_label d-flex"
                                                    for="">{{__('common.City')}} <span
                                                        class="required_mark">*</span>
                                                </label>
                                                <select class="primary_select category_id" name="city"
                                                        id="city_id" {{$errors->has('city') ? 'autofocus' : ''}}>
                                                    <option data-display="{{__('common.Select')}}"
                                                            value="">{{__('common.Select')}} {{__('common.City')}} <span
                                                            class="required_mark">*</span></option>
                                                            @foreach($cities as $city)
                                                                <option value="{{$city->id}}" {{old('city', $editUser->city ?? '') == $city->id ? 'selected' : ''}}>
                                                                    {{$city->city_name}}
                                                                </option>
                                                            @endforeach
                                                </select>
                                            </div>

                                            <div class="col-xl-4 courseBox">
                                                <div class="primary_input mb-25">

                                                    <div class="row pt-2" id="complete_course">
                                                        <div class="col-md-4 mb-25">
                                                            <label class="primary_input_label mt-1"
                                                                for=""> {{__('common.is HR?')}}</label>
                                                        </div>
                                                        <div class="col-md-3 mb-25" id="comp_cour_no">
                                                            <label class="primary_checkbox d-flex mr-12">
                                                                <input type="radio" class="is_hr0"
                                                                    id="is_hr0" name="is_hr"
                                                                    value="0" checked @if (old('is_hr', $editUser->is_hr ?? '') == '0') checked @endif>
                                                                <span class="checkmark me-2"></span> {{__('common.No')}}</label>
                                                        </div>
                                                        <div class="col-md-3 mb-25" id="comp_cour_yes">
                                                            <label class="primary_checkbox d-flex mr-12">
                                                                <input type="radio" class="is_hr1"
                                                                    id="is_hr1" name="is_hr"
                                                                    value="1" @if (old('is_hr', $editUser->is_hr ?? '') == '1') checked @endif>
                                                                <span class="checkmark me-2"></span>{{__('common.Yes')}}</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xl-4 courseBox mb_30">
                                                <label class="primary_input_label d-flex"
                                                    for="">{{__('common.Locations')}} <span
                                                        class="required_mark">*</span>
                                                </label>
                                                <select class="primary_select category_id" name="location"
                                                        id="locations_id" {{$errors->has('location_code') ? 'autofocus' : ''}}>
                                                    <option data-display="{{__('common.Select')}}"
                                                            value="">{{__('common.Select')}} {{__('common.Locations')}} <span
                                                            class="required_mark">*</span></option>
                                                            @foreach($locations as $location)
                                                                <option value="{{$location->id}}" {{old('location_code', $editUser->location_code ?? '') == $location->id ? 'selected' : ''}}>
                                                                    {{$location->location_code}}
                                                                </option>
                                                            @endforeach
                                                </select>
                                            </div>

                                            <div class="col-xl-4 courseBox mb_30">
                                                <label class="primary_input_label d-flex"
                                                    for="">{{__('common.Management')}} <span
                                                        class="required_mark">*</span>
                                                </label>
                                                <select class="primary_select category_id" name="management"
                                                        id="management_id" {{$errors->has('management') ? 'autofocus' : ''}}>
                                                    <option data-display="{{__('common.Select')}}"
                                                            value="">{{__('common.Select')}} {{__('common.Management')}} <span
                                                            class="required_mark">*</span></option>
                                                            @foreach($managements as $mgt)
                                                                <option value="{{$mgt->id}}" {{old('mgt_id', $editUser->mgt_id ?? '') == $mgt->id ? 'selected' : ''}}>
                                                                    {{$mgt->management_name}}
                                                                </option>
                                                            @endforeach
                                                </select>
                                            </div>
                                            <div class="col-xl-4 courseBox mb_30">
                                                <label class="primary_input_label d-flex"
                                                       for="">{{__('common.Experience')}} <span
                                                        class="required_mark">*</span>
                                                </label>
                                                <input name="experience" id="experience"  maxlength="2"
                                                       class="primary_input_field experience only-number" placeholder="{{__('common.Experience')}}"
                                                       value="{{old('experience', $editUser->experience ?? '')}}" {{$errors->has('experience') ? 'autofocus' : ''}}>
                                                @if ($errors->has('experience'))
                                                    <span class="text-danger"><strong>{{ $errors->first('experience') }}</strong></span>
                                                @endif    

                                            </div>
                                            <div class="col-xl-4 courseBox mb_30">
                                                <label class="primary_input_label d-flex"
                                                    for="">{{__('common.Reporting Manager')}} <span
                                                        class="required_mark">*</span>
                                                </label>
                                                <select class="primary_select reporting_manager_id"
                                                        name="reporting_manager"
                                                        id="reporting_manager_id">

                                                    <option value="">
                                                        {{__('common.Select')}} {{__('common.Reporting Manager')}}
                                                    </option>

                                                    @foreach($reportingManagers as $manager)

                                                        <option value="{{$manager->id}}"
                                                                data-email="{{$manager->email}}"
                                                                data-employee="{{$manager->employee_id}}" {{old('reporting_manager', $editUser->reporting_manager_id ?? '') == $manager->employee_id ? 'selected' : ''}}>

                                                            {{$manager->name}} - {{$manager->employee_id}}

                                                        </option>

                                                    @endforeach

                                                </select>
                                                 @if ($errors->has('reporting_manager'))
                                                    <span class="text-danger"><strong>{{ $errors->first('reporting_manager') }}</strong></span>
                                                @endif  
                                            </div>

                                            <div class="col-xl-4 courseBox mb_30">
                                                <label class="primary_input_label d-flex"
                                                    for="">{{__('common.Skills')}} <span
                                                        class="required_mark">*</span>
                                                </label>
                                                <select class="primary_select skill_id"
                                                        name="skill_id[]"
                                                        id="skill_id" multiple>

                                                    <option value="">
                                                        {{__('common.Select')}} {{__('common.Skills')}}
                                                    </option>
                                                   @foreach($skills as $skill)
                                                        <option value="{{$skill->id}}"
                                                            {{ in_array($skill->id, old('skill_id', $editUser?->skills?->pluck('id')->toArray() ?? [])) ? 'selected' : '' }}>
                                                            {{$skill->name}}
                                                        </option>
                                                    @endforeach

                                                </select>
                                                 @if ($errors->has('skill'))
                                                    <span class="text-danger"><strong>{{ $errors->first('skill') }}</strong></span>
                                                @endif  
                                            </div>
                                            
                                            <input type="hidden" name="manager_email" id="manager_email">

                                            <input type="hidden" name="reporting_manager_id" id="employee_id">

                                            <input type="hidden" name="learner_id" value="{{ $editUser->id ?? ''}}">
                                            
                       
                                        </div>

                                    </div>
                                </div>
                                
                            </div>
                        @endforeach
                    </div>

                   
                    
                    <input type="hidden" id="ajaxSubCategoryUrl" value="{{ route('ajaxGetSubCategoryList') }}">
                    <input type="hidden" id="ajaxGetSecondarySkillList" value="{{ route('ajaxGetSecondarySkillList') }}">
                    <input type="hidden" id="ajaxGetDepartmentList" value="{{ route('ajaxGetDepartmentList') }}">
                    <input type="hidden" id="ajaxGetDepartmentWithBUList" value="{{ route('ajaxGetDepartmentWithBUList') }}">
                    <input type="hidden" id="ajaxGetBuList" value="{{ route('ajaxGetBuList') }}">
                    <input type="hidden" id="ajaxGetOverAllList" value="{{ route('ajaxGetOverAllList') }}">
                    
                    
                    

                    <div class="col-lg-12 text-center pt_15">
                        <div class="d-flex justify-content-center">
                            <button class="primary-btn semi_large2  fix-gr-bg" id="save_button_parent"
                                    type="submit"><i
                                    class="ti-check"></i> {{__('common.Add Learner') }}
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>

    </section>
    @include('backend.partials.delete_modal')
@endsection
@push('js')
    <script>
        let show_overview_media = $('#show_overview_media');
        let overview_host_section = $('#overview_host_section');
        show_overview_media.change(function () {
            if (show_overview_media.is(':checked')) {
                overview_host_section.show();
            } else {
                overview_host_section.hide();
            }
        });

        let mode_of_delivery = $('#mode_of_delivery')
        let overview_delivery_section = $('#overview_delivery_section');
        mode_of_delivery.change(function () {
            // alert('test');
            let mode_id = $(this).val();
            if (mode_id == 1) {
                overview_delivery_section.show();
            } else {
                overview_delivery_section.hide();
            }
        });
    </script>
    <script>
        let show_mode_of_delivery = $('#show_mode_of_delivery');
        let mode_of_delivery_options = $('#mode_of_delivery_options');
        show_mode_of_delivery.change(function () {
            if (show_mode_of_delivery.is(':checked')) {
                mode_of_delivery_options.show();
            } else {
                mode_of_delivery_options.hide();
            }
        });


        $('.mode_of_delivery').change(function () {
            let option = $(".mode_of_delivery option:selected").val();
            if (option == 3) {
                $('.quizBox').hide();
            } else {
                if ($('#type2').is(':checked')) {
                    $('.quizBox').show();
                }
            }
        });

        $('#iap').change(function () {
            if ($('#iap').is(':checked')) {
                $('#iap_div').removeClass('d-none');
            } else {
                $('#iap_div').addClass('d-none');
            }
        });

        // document on ready
        @if(old('type'))
        $(document).ready(function () {
            @if(old('type')==1)
            $('#type1').trigger('click');
            @elseif(old('type')==2)
            $('#type2').trigger('click');
            @elseif(old('type3')==3)
             $('#type3').trigger('click');
            @endif
        })
        @endif
    </script>
@endpush
@push('scripts')

    <script src="{{asset('/')}}/Modules/CourseSetting/Resources/assets/js/course.js"></script>



    <script>
        let vdocipherList = $('.vdocipherList');

        vdocipherList.select2({
            ajax: {
                url: '{{route('getAllVdocipherData')}}',
                type: "GET",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        search: params.term,
                        page: params.page || 1,
                        // id: $('#country').find(':selected').val(),
                    }
                    return query;
                },
                cache: false
            }
        });

        $('.vimeoList').select2({
            ajax: {
                url: '{{route('getAllVimeoData')}}',
                type: "GET",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        search: params.term,
                        page: params.page || 1,
                    }
                },
                cache: false
            }
        });
    </script>
    @if(isModuleActive('UpcomingCourse'))
        <script>
            upcomingCourseDivToggle();

            $(document).on('change', '#is_upcoming_course', function (event) {
                upcomingCourseDivToggle();
            });

            $(document).on('change', '#is_allow_prebooking', function (event) {
                upcomingCourseDivToggle();
            });

            function upcomingCourseDivToggle() {
                if ($('#is_upcoming_course').is(':checked')) {
                    $('.upcoming_course_div').removeClass('d-none');
                } else {
                    $('.upcoming_course_div').addClass('d-none');
                }
                allowPreBooking();
            }

            function allowPreBooking() {
                if ($('#is_allow_prebooking').is(':checked')) {
                    $('.booking_amount_div').removeClass('d-none');
                } else {
                    $('.booking_amount_div').addClass('d-none');
                }
            }


        </script>
    @endif

    <script>
        $(document).ready(function () {
            $('#has_badge').change(function () {
                if (this.checked)
                    $('#has_badge_div').fadeIn('slow');
                else
                    $('#has_badge_div').fadeOut('slow');
            });

             $('#has_recommended').change(function () {
                if (this.checked)
                    $('#has_bu_div').fadeIn('slow');
                else
                    $('#has_bu_div').fadeOut('slow');
            });

            $('#has_external').change(function () {
                if (this.checked)
                    $('#has_external_div').fadeIn('slow');
                else
                    $('#has_external_div').fadeOut('slow');
            });

        });

        $('.primary_select1').select2({
        placeholder: "Select Recommended For",
        allowClear: true
        });

        $('.secondary_select1').select2({
        placeholder: "Select Secondary Select",
        allowClear: true
        });

        $('.bu_department').select2({
        placeholder: "Select Department",
        allowClear: true
        });

        $('.bu_unit').select2({
        placeholder: "Select Department",
        allowClear: true
        });

        $('#business_unit').select2({
            placeholder: "Select Business Unit",
            width: '100%'
        });

        $('.basedProfileBu').select2({
            placeholder: "Select Business Unit",
            width: '100%'
        });

        


        $('#bu_multiple').select2({
            placeholder: "Select Business Unit",
            width: '100%'
        });

        $('#state_multiple').select2({
            placeholder: "Select State",
            width: '100%'
        });

        $('#city_multiple').select2({
            placeholder: "Select City",
            width: '100%'
        });
        
        
        $('.base_department').select2({
            placeholder: "Select department",
            width: '100%'
        });

         $('.multiple_state').select2({
            placeholder: "Select department",
            width: '100%'
        });
        
        

    </script>

    <script>
$(document).ready(function () {

    /*
    |--------------------------------------------------------------------------
    | Initialize Select2
    |--------------------------------------------------------------------------
    */

    // $('.primary_select').select2({
    //     width: '100%'
    // });

    /*
    |--------------------------------------------------------------------------
    | Trigger Validation On Select2 Change
    |--------------------------------------------------------------------------
    */

    
    /*
    |--------------------------------------------------------------------------
    | Form Validation
    |--------------------------------------------------------------------------
    */

    $('#learnerForm').validate({

        

        rules: {

            'name[en]': {
                required: false
            },

            'email[en]': {
                required: false,
                email: false
            },

            reporting_manager_id: {
                required: false
            },

            profile_id: {
                required: false
            },

            bu: {
                required: false
            },

            dept: {
                required: false
            },

            country: {
                required: false
            },

            state: {
                required: false
            },

            city: {
                required: false
            },

            locations: {
                required: false
            },

            mgt_id: {
                required: false
            },

            category: {
                required: false
            }

        },

        messages: {

            'name[en]': {
                required: "Name is required"
            },

            'email[en]': {
                required: "Email is required",
                email: "Please enter valid email"
            },

            reporting_manager_id: {
                required: "Please select Reporting Manager"
            },

            profile_id: {
                required: "Please select Profile"
            },

            bu: {
                required: "Please select Business Unit"
            },

            dept: {
                required: "Please select Department"
            },

            country: {
                required: "Please select Country"
            },

            state: {
                required: "Please select State"
            },

            city: {
                required: "Please select City"
            },

            locations: {
                required: "Please select Location"
            },

            mgt_id: {
                required: "Please select Management"
            },

            category: {
                required: "Please select Category"
            }

        },

        errorElement: 'span',
        errorClass: 'text-danger',

        /*
        |--------------------------------------------------------------------------
        | Error Placement For Select2
        |--------------------------------------------------------------------------
        */

        errorPlacement: function (error, element) {

            if (element.hasClass('select2-hidden-accessible')) {

                error.insertAfter(element.next('.select2'));

            } else {

                error.insertAfter(element);
            }
        },

        /*
        |--------------------------------------------------------------------------
        | Highlight Invalid Fields
        |--------------------------------------------------------------------------
        */

        highlight: function (element) {

            if ($(element).hasClass('select2-hidden-accessible')) {

                $(element)
                    .next('.select2')
                    .find('.select2-selection')
                    .addClass('border-danger');

            } else {

                $(element).addClass('is-invalid');
            }
        },

        /*
        |--------------------------------------------------------------------------
        | Remove Error Styling
        |--------------------------------------------------------------------------
        */

        unhighlight: function (element) {

            if ($(element).hasClass('select2-hidden-accessible')) {

                $(element)
                    .next('.select2')
                    .find('.select2-selection')
                    .removeClass('border-danger');

            } else {

                $(element).removeClass('is-invalid');
            }
        },

        /*
        |--------------------------------------------------------------------------
        | Submit
        |--------------------------------------------------------------------------
        */

        submitHandler: function (form) {
            form.submit();
        }

    });

    $(document).ready(function () {

        function setManagerDetails() {

            let manager_email = $('#reporting_manager_id')
                .find(':selected')
                .data('email');

            let employee_id = $('#reporting_manager_id')
                .find(':selected')
                .data('employee');

            $('#manager_email').val(manager_email ?? '');

            $('#employee_id').val(employee_id ?? '');
        }

        // On dropdown change
        $('#reporting_manager_id').change(function () {

            setManagerDetails();

        });

        // Trigger once for edit page
        setManagerDetails();

    });

    $(document).on('input', '.only-number', function () {

        this.value = this.value.replace(/[^0-9]/g, '');

    });

    // $('#skill_id').select2({
    //         placeholder: "Select Skill",
    //         width: '100%'
    // });

});
</script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
@endpush
