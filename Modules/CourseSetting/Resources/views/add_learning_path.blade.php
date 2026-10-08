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
           
            <div class="col-lg-12">


                <input type="hidden" id="url" value="{{url('/')}}">

                <form action="{{route('AdminLearningPathStore')}}" method="POST" enctype="multipart/form-data">
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

                                    <div
                                        class="col-xl-12">
                                        <div class="primary_input mb-25">
                                            <label class="primary_input_label d-flex"
                                                   for="">{{__('quiz.Topic')}} {{__('common.Title')}} <span
                                                    class="required_mark">*</span>
                                                @includeIf('aicontent::inc.button' , ['selected_template' => 1,'slug'=>'course-title'])
                                            </label>

                                            <input class="primary_input_field" name="title[{{$language->code}}]"
                                                   placeholder="-"
                                                   id="addTitle"
                                                   type="text" {{$errors->has('title') ? 'autofocus' : ''}}
                                                   value="{{old('title.'.$language->code)}}">
                                        </div>
                                    </div>
                                </div>
                                

                                <div class="row" id="course_description">
                                    <div class="col-xl-12">
                                        <div class="primary_input mb-35">
                                            <label class="primary_input_label d-flex"
                                                   for="">{{__('courses.Course')}} {{__('courses.Description')}}
                                                @includeIf('aicontent::inc.button' , ['selected_template' => 3,'slug'=>'course-long-description'])
                                            </label>
                                            <textarea class="lms_summernote"
                                                      name="about[{{$language->code}}]" id="addAbout"
                                                      cols="30"
                                                      rows="10">{!! old('about.'.$language->code) !!}</textarea>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        @endforeach
                    </div>

                    @php
                        if (courseSetting()->show_mode_of_delivery == 1 || isModuleActive('Org')) {
                            $col_size = 4;
                        } elseif (currentTheme()=='tvt'){
                            $col_size = 3;
                        }else {
                            $col_size = 6;
                        }
                    @endphp
                    <div class="row">

                        @if(currentTheme()=='tvt')
                            <div class="col-xl-{{$col_size}}  mb_30">
                                <label class="primary_input_label d-flex"
                                       for="">{{__('courses.School Subject')}}
                                </label>
                                <select class="primary_select school_subject_id" name="school_subject_id"
                                        id="school_subject_id" {{$errors->has('category') ? 'autofocus' : ''}}>
                                    <option data-display="{{__('common.Select')}} {{__('courses.School Subject')}} *"
                                            value="">{{__('common.Select')}} {{__('courses.School Subject')}} </option>
                                    @foreach($subjects as $subject)
                                        <option value="{{$subject->id}}">{{$subject->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        <div class="col-xl-{{$col_size}} courseBox mb_30">
                            <label class="primary_input_label d-flex"
                                   for="">{{__('quiz.Category')}} <span
                                    class="required_mark">*</span>
                            </label>
                            <select class="primary_select category_id" name="category"
                                    id="category_id" {{$errors->has('category') ? 'autofocus' : ''}}>
                                <option data-display="{{__('common.Select')}} {{__('quiz.Category')}}"
                                        value="">{{__('common.Select')}} {{__('quiz.Category')}} <span
                                        class="required_mark">*</span></option>
                                        @foreach($categories as $category)
                                            @if($category->parent_id==0)
                                                @include('backend.categories._single_select_option',['category'=>$category,'level'=>1])
                                            @endif
                                        @endforeach
                            </select>
                        </div>
                        <div class="col-xl-{{$col_size}} courseBox mb_30" id="subCategoryDiv">
                            <label class="primary_input_label d-flex"
                                   for=""> {{ __('courses.Sub Category') }}
                            </label>
                            <select class="primary_select" name="sub_category"
                                    id="subcategory_id" {{$errors->has('sub_category') ? 'autofocus' : ''}}>
                                <option
                                    data-display="{{ __('common.Select') }} {{ __('courses.Sub Category') }}  "
                                    value="">{{ __('common.Select') }} {{ __('courses.Sub Category') }}
                                </option>
                            </select>
                        </div>

                       
                        


                        <div class="col-xl-4 makeResize ">
                            <label class="primary_input_label d-flex"
                                   for="">{{ __('courses.Level') }}<span class="required_mark">*</span>
                            </label>
                            <select class="primary_select" name="level">
                                {{--                                <option--}}
                                {{--                                    data-display="{{ __('common.Select') }} {{ __('courses.Level') }}"--}}
                                {{--                                    value="">{{ __('common.Select') }} {{ __('courses.Level') }}--}}
                                {{--                                </option>--}}
                                @foreach($levels as $level)
                                    <option
                                        value="{{$level->id}}" {{old('level')==$level->id?"selected":""}} >{{$level->title}}</option>
                                @endforeach

                            </select>
                        </div>
                        <div class="col-xl-4 makeResize responsiveResize" id="">
                            <label class="primary_input_label d-flex"
                                   for="">{{ __('common.Language') }}<span class="required_mark">*</span>
                            </label>
                            <select class="primary_select mb-25" name="language"
                                    id="" {{$errors->has('language') ? 'autofocus' : ''}}>
                                <option
                                    data-display="{{ __('common.Select') }} {{ __('common.Language') }} *"
                                    value="">{{ __('common.Select') }} {{ __('common.Language') }}</option>
                                @foreach ($languages as $language)
                                    <option
                                        value="{{$language->id}}" {{old('language')==$language->id?"selected":""}} {{auth()->user()->language_id==$language->id?'selected':''}}>{{$language->native}}</option>

                                @endforeach
                            </select>
                        </div>
                        <div class="col-xl-4 makeResize responsiveResize" id="durationBox">
                            <label class="primary_input_label d-flex"
                                   for="">{{__('common.Duration')}} ({{__('common.In Minute')}})
                            </label>
                            <div class="primary_input mb-25">
                                <input class="primary_input_field" name="duration"
                                       placeholder="{{__('common.Duration')}} ({{__('common.In Minute')}})"
                                       id="addDuration"
                                       min="0" step="any" type="number"
                                       value="{{old('duration')}}" {{$errors->has('duration') ? 'autofocus' : ''}}>
                            </div>
                        </div>
                        <div class="col-xl-4 mb_30">
                            <label class="primary_input_label d-flex" for="">
                                {{ __('courses.Approve Type') }} 
                                <span class="required_mark">*</span>
                            </label>

                            <select class="primary_select approve_type" 
                                    name="approve_type" 
                                    id="approve_type">

                                <option value=""
                                    {{ old('approve_type') == '' ? 'selected' : '' }}>
                                    {{ __('common.Select') }} {{ __('courses.Approve Type') }}*
                                </option>

                                <option value="1"
                                    {{ old('approve_type') == '1' ? 'selected' : '' }}>
                                    {{ __('courses.Self Enroll') }}
                                </option>

                                <option value="2"
                                    {{ old('approve_type') == '2' ? 'selected' : '' }}>
                                    {{ __('courses.Manager Approval') }}
                                </option>

                            </select>
                        </div>
                        @if (courseSetting()->show_mode_of_delivery==1 || isModuleActive('Org'))
                            <div class="col-xl-{{$col_size}} mb_30 d-none">
                                <label class="primary_input_label d-flex" for="">
                                    {{ __('courses.Mode of Delivery') }} 
                                    <span class="required_mark">*</span>
                                </label>

                                <select class="primary_select mode_of_delivery" 
                                        name="mode_of_delivery" 
                                        id="mode_of_delivery">

                                    <option value=""
                                        {{ old('mode_of_delivery') == '' ? 'selected' : '' }}>
                                        {{ __('common.Select') }} {{ __('courses.Mode of Delivery') }}*
                                    </option>

                                    <option value="1"
                                        {{ old('mode_of_delivery') == '1' ? 'selected' : '' }}>
                                        {{ __('courses.Online') }}
                                    </option>

                                    @if(!isModuleActive('Org'))
                                        <option value="2"
                                            {{ old('mode_of_delivery') == '2' ? 'selected' : '' }}>
                                            {{ __('courses.Distance Learning') }}
                                        </option>

                                        <option value="3"
                                            {{ old('mode_of_delivery') == '3' ? 'selected' : '' }}>
                                            {{ __('courses.Face-to-Face') }}
                                        </option>
                                    @else
                                        <option value="3"
                                            {{ old('mode_of_delivery') == '3' ? 'selected' : '' }}>
                                            {{ __('courses.Offline') }}
                                        </option>
                                    @endif

                                </select>
                            </div>
                        @endif
                    </div>
                   
                    
                    <div class="col-xl-6 courseBox">
                        <div class="primary_input mb-25">

                            <div class="row pt-2" id="complete_course">
                                <div class="col-md-6 mb-25">
                                    <label class="primary_input_label mt-1"
                                           for=""> {{__('common.Complete course sequence')}}</label>
                                </div>
                                <div class="col-md-2 mb-25" id="comp_cour_no">
                                    <label class="primary_checkbox d-flex mr-12">
                                        <input type="radio" class="  complete_order0"
                                               id="complete_order0" name="complete_order"
                                               value="0" checked>
                                        <span class="checkmark me-2"></span> {{__('common.No')}}</label>
                                </div>
                                <div class="col-md-2 mb-25" id="comp_cour_yes">
                                    <label class="primary_checkbox d-flex mr-12">
                                        <input type="radio" class="complete_order1"
                                               id="complete_order1" name="complete_order"
                                               value="1">
                                        <span class="checkmark me-2"></span>{{__('common.Yes')}}</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <div class="row mt-20 mb-10 videoOption d-none" id="overview_video">
                        <div class="col-lg-6">
                            <div class="checkbox_wrap d-flex align-items-center mt-40">
                                <label for="show_overview_media" class="switch_toggle me-2">
                                    <input type="checkbox" id="show_overview_media" value="1"
                                           name="show_overview_media">
                                    <i class="slider round"></i>
                                </label>
                                <label
                                    class="mb-0">{{ __('courses.Show Overview Video') }}</label>
                            </div>
                        </div>
                    </div>

                    

                    <div class="row">
                        <div class="col-xl-4 mt-25">
                            <label class="primary_input_label mt-1">{{__('courses.Primary Skills')}} </label>
                            <select class="primary_select " name="primary_skill_id"
                                    id="primary_skill_id">
                                <option value="">{{__('courses.Select Primary Skill')}}</option>
                                @foreach($primarySkills as $primarySkill)
                                    <option value="{{ $primarySkill->id }}" {{ old('category') == $primarySkill->category ? 'selected' : '' }}>
                                        {{ $primarySkill->category }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                              
                       
                        <div class="col-xl-4 mt-25" id="secondarySkillsDiv">
                            <label class="primary_input_label mt-1">{{_trans('courses.Secondary Skills')}}
                                </label>
                            <select class="secondary_select1" name="secondary_skill_id[]" multiple
                                    id="secondary_skill_id" {{$errors->has('') ? 'autofocus' : ''}}>
                                
                                <option
                                    data-display="{{ __('common.Select') }} {{ __('courses.Secondary Skills') }}"
                                    value="">{{ __('common.Select') }} {{ __('courses.Secondary Skills') }}
                                </option>
                            </select>
                              <div id="secondarySkillLoader" style="display:none;">
                            Loading...
                        </div>

                        </div>
                       

                      
                        
                    </div>

                    
                    <div class="row mt-20">
                        
                        <div class="col-xl-6">
                            <div class=" mb-35">
                                <x-upload-file
                                    name="image"
                                    type="image"
                                    {{--                                    media_id="{{isset($edit)?$edit->image_media?->media_id:''}}"--}}
                                    label="{{ __('courses.Course Thumbnail') }}"
                                    note="{{__('student.Recommended size')}} (1170x600)"
                                />

                            </div>
                        </div>
                      
                    </div>

                    
                    
                    

                    <div class="row">
                        <div class="col-xl-4 mb-25">
                            <div class="checkbox_wrap d-flex align-items-center mt-20">
                                <label for="has_recommended" class="switch_toggle me-2">
                                    <input type="checkbox" id="has_recommended" value="1" name="has_recommended">
                                    <i class="slider round"></i>
                                </label>
                                <label
                                    class="mb-0">{{ __('common.Recommended For') }}</label>
                            </div>
                        </div>
                        <div class="col-xl-6" id="has_bu_div" style="display: none">
                            <label class="primary_input_label mt-1" for=""> {{__('courses.Recommended For')}} <span
                                class="required_mark">*</span></label>
                                    <select class="primary_select1" name="recommend_role_id[]" id="recommend_for" multiple>
                                        
                                        <option value="0" data-type="0">
                                                All
                                        </option>
                                        @foreach($grpLists as $item)
                                            <option value="{{ $item->grp_type }}-{{ $item->id }}" data-type="{{ $item->grp_type }}">
                                                {{ $item->name }}
                                            </option>
                                        @endforeach
                                        @if ($errors->has('recommend_role_id'))
                                            <span class="invalid-feedback invalid-select" role="alert">
                                            <strong>{{ $errors->first('recommend_role_id') }}</strong>
                                        </span>
                                        @endif
                                    </select>
                        </div>
                       
                    </div>
                    
                    

                    

                    <div class="row mt-10" >
                        <div class="col-lg-4 mb-25">
                            <div class="checkbox_wrap d-flex align-items-center mt-20">
                                <label for="has_certificate" class="switch_toggle me-2">
                                    <input type="checkbox" id="has_certificate" value="1" name="has_certificate">
                                    <i class="slider round"></i>
                                </label>
                                <label
                                    class="mb-0">{{ __('courses.Enable Certificate') }}</label>
                            </div>
                        </div>
                        <!-- <div class="col-xl-4  mb-25" id="has_certificate_div">
                            <x-upload-file
                                name="course_certificate"
                                type="image"
                                 label="{{ __('courses.Course Certificate') }}"
                            />
                        </div>
                        
                    </div> -->

                    
                    <input type="hidden" id="ajaxSubCategoryUrl" value="{{ route('ajaxGetSubCategoryList') }}">
                    <input type="hidden" id="ajaxGetSecondarySkillList" value="{{ route('ajaxGetSecondarySkillList') }}">
                    <input type="hidden" id="ajaxGetDepartmentList" value="{{ route('ajaxGetDepartmentList') }}">
                    <input type="hidden" id="ajaxGetDepartmentWithBUList" value="{{ route('ajaxGetDepartmentWithBUList') }}">
                    <input type="hidden" id="ajaxGetBuList" value="{{ route('ajaxGetBuList') }}">
                    <input type="hidden" id="ajaxGetOverAllList" value="{{ route('ajaxGetOverAllList') }}">
                    
                    
                    <div class="row">
                        <div class="col-xl-4 mt-25">
                            <!-- Course Type -->
                            <label class="primary_input_label mt-1">{{__('courses.Course Type')}} </label>
                                <select class="primary_select " name="course_type"
                                        id="course_type">
                                    <option
                                        value="1" {{@$course->course_type=="1"?'selected':''}}>{{__('courses.Self Paced')}}
                                    </option>
                                        <option
                                        value="2" {{@$course->course_type=="2"?'selected':''}}>{{__('courses.Face to Face')}}
                                    </option>
                                    <option {{@$course->course_type=="3"?'selected':''}} value="3">
                                        {{__('courses.Virtual Class')}}
                                    </option>
                                     <option {{@$course->course_type=="4"?'selected':''}} value="4">
                                        {{__('courses.Blended')}}
                                    </option>

                                </select>
                        </div>
                        <div class="col-xl-4 mt-25">
                            <!-- View Scope -->
                            <label class="primary_input_label mt-1">{{__('courses.View Scope')}} </label>
                                                <select class="primary_select " name="scope"
                                                        id="scope">
                                                    <option
                                                        value="1" {{@$course->scope=="1"?'selected':''}}>{{__('courses.Public')}}
                                                    </option>

                                                    <option {{@$course->scope=="0"?'selected':''}} value="0">
                                                        {{__('courses.Private')}}
                                                    </option>

                                                </select>
                        </div>

                        <div class="col-xl-4 mt-25 d-none group_div" id="group_div">
                            <!-- Group -->
                              <label class="primary_input_label d-flex" for="">
                                    {{ __('courses.Group') }} 
                                    <span class="required_mark">*</span>
                                </label>

                                <select class="bu_unit" name="grp_select_val[]" id="grp_select_val" multiple>
                                    <option value="0" data-type="0">
                                            All
                                    </option>
                                    @foreach($grpLists as $item)
                                        <option value="{{ $item->grp_type }}-{{ $item->id }}" data-type="{{ $item->grp_type }}">
                                            {{ $item->name }}
                                        </option>
                                    @endforeach
                                </select>
                        </div>

                        <!-- ✅ Business Unit -->
                        <div class="col-xl-4 mt-25 d-none" id="basedBuDivView">
                            <label class="primary_input_label d-flex">
                                {{ __('courses.Business Unit') }}
                            </label>

                            <select class="bu_unit" name="grp_business_unit[]" multiple id="bu_multiple1">
                            </select>

                            <div id="BuLoader" style="display:none;">Loading...</div>
                        </div> <!-- ✅ IMPORTANT: CLOSE HERE -->

                        <!-- ✅ Department -->
                        <div class="col-xl-6 mt-25 d-none" id="basedDepartmentDivView">
                            <label class="primary_input_label d-flex">
                                {{ __('courses.Department') }}
                            </label>

                            <select class="base_department" name="grp_department[]" multiple id="dept_multiple1">
                            </select>

                            <div id="DeptLoader" style="display:none;">Loading...</div>
                        </div>

                        <!-- ✅ State -->
                        <div class="col-xl-6 mt-25 d-none" id="basedStateDivView">
                            <label class="primary_input_label d-flex">
                                {{ __('courses.State') }}
                            </label>
                             <select class="state_multiple" name="grp_state[]" multiple id="state_multiple">
                            </select>

                            <div id="StateLoader" style="display:none;">Loading...</div>
                        </div>

                        <!-- ✅ City -->
                        <div class="col-xl-4 mt-25 d-none" id="basedCityDivView">
                            <label class="primary_input_label d-flex">
                                {{ __('courses.City') }}
                            </label>
                            <select class="city_multiple" name="grp_city[]" multiple id="city_multiple">
                            </select>

                            <div id="CityLoader" style="display:none;">Loading...</div>
                        </div>

                    </div>

                    <div class="col-lg-12 text-center pt_15">
                        <div class="d-flex justify-content-center">
                            <button class="primary-btn semi_large2  fix-gr-bg" id="save_button_parent"
                                    type="submit"><i
                                    class="ti-check"></i> {{__('common.Next') }}
                            </button>
                        </div>
                    </div>
                    <input type="hidden" name="recommend_grp_type" id="recommend_grp_type">

                    <input type="hidden" name="private_grp_type" id="private_grp_type">
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
       
    $('#recommend_for').on('change', function () {

        let RecgrpTypes = [];

        $('#recommend_for option:selected').each(function () {
            RecgrpTypes.push($(this).data('type'));
        });

        $('#recommend_grp_type').val(RecgrpTypes.join(','));
    });

    $('#grp_select_val').on('change', function () {

        let grpTypes = [];

        $('#grp_select_val option:selected').each(function () {
            grpTypes.push($(this).data('type'));
        });

        $('#private_grp_type').val(grpTypes.join(','));
    });
       
    </script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
@endpush
