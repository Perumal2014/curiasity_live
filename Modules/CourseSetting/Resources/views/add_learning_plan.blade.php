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

         .select2-container--default .select2-selection--multiple {
            border-radius: 6px;
            padding: 4px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: var(--dynamic-text-color);
            line-height: 40px;
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

        .before_deadline_col {
            transition: all 0.3s ease;
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
            <div class="white_box_tittle list_header">
                <h4>{{__('common.Add New')}} {{__('courses.Learning Plan')}}</h4>
            </div>
            <div class="col-lg-12">


                <input type="hidden" id="url" value="{{url('/')}}">

                <form action="{{route('savePlans')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-xl-6">
                            <div class="primary_input mb-25">
                                <label class="primary_input_label d-flex">
                                    {{__('courses.Plan Name')}} <span class="required_mark">*</span>
                                    @includeIf('aicontent::inc.button' , ['selected_template' => 1,'slug'=>'course-title'])
                                </label>

                                <input class="primary_input_field" name="title"
                                    placeholder="-"
                                    id="addTitle"
                                    type="text"
                                    {{$errors->has('title') ? 'autofocus' : ''}}
                                    value="{{old('title')}}">
                            </div>
                        </div>

                        <div class="col-xl-6">
                            <div class="primary_input mb-25">
                                <label class="primary_input_label d-flex">
                                    {{__('courses.Plan Group')}} <span class="required_mark">*</span>
                                    @includeIf('aicontent::inc.button' , ['selected_template' => 1,'slug'=>'course-title'])
                                </label>

                                <select class="learning_grp" multiple name="grp_select_val[]" id="recgrpList_id">
                                    @if ($grpLists)
                                        @foreach($grpLists as $item)
                                            <option value="{{ $item->id }}" data-type="{{ $item->grp_type }}">
                                                {{ $item->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6">
                            <div class="primary_input mb-25">
                                <label class="primary_input_label d-flex">
                                    {{__('courses.Plan Occur When?')}} <span class="required_mark">*</span>
                                    @includeIf('aicontent::inc.button' , ['selected_template' => 1,'slug'=>'course-title'])
                                </label>

                                <select name="occurs_when" class="primary_select">
                                    <option value="1">On Joining</option>
                                    <option value="2">Completion of milestone</option>
                                    <option value="3">Every one year</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div>&nbsp;</div>
                        <div class="col-xl-6">
                             <label for="add learning path"><h5>Add MileStones</h5></label>
                            <button type="button"
                                class="primary-btn icon-only fix-gr-bg p-0"
                                id="add_learning_box">
                                <i class="ti-plus m-0"></i>
                            </button>
                           
                        </div>
                    </div>
                    <div>&nbsp;</div>
                    <div id="learning_plan_container"></div>
                    <input type="hidden" name="grp_type" id="grp_type">
                    <div class="col-lg-12 text-center pt_15">
                        <div class="d-flex justify-content-center">
                            <button class="primary-btn semi_large2  fix-gr-bg" id="save_button_parent"
                                    type="submit"><i
                                    class="ti-check"></i> {{__('common.Add') }} {{__('courses.Learning Plan') }}
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
        $('#recgrpList_id').on('change', function () {

            let grpTypes = [];

            $('#recgrpList_id option:selected').each(function () {
                grpTypes.push($(this).data('type'));
            });

            $('#grp_type').val(grpTypes.join(','));
        });

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

        });

        $('.primary_select1').select2({
        placeholder: "Select Profile",
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
        
    let count = 0;

    // Add Row
    $('#add_learning_box').click(function () {

        count++;

        let row = `
            <div class="row align-items-end mb-3" id="row_${count}">
                
                <div class="col-xl-2">
                    <div class="primary_input">
                        <label class="primary_input_label">No.of Days</label>
                        <input type="number" name="days[${count}]" class="primary_input_field" placeholder="Days">
                    </div>
                </div>

                <div class="col-xl-4">
                    <div class="primary_input">
                        <label class="primary_input_label">Add Course</label>
                        <select name="add_courses[${count}][]" class="multi_select course_select" multiple>
                            @if ($courses) 
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}">
                                        {{ $course->title }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <div class="col-xl-3">
                    <div class="primary_input">
                        <label class="primary_input_label">Set Deadline</label>
                        <select name="deadline[${count}][]" class="deadline_select" multiple>
                            <option value="1">On Deadline</option>
                            <option value="2">Before Deadline</option>
                            <option value="3">After Deadline</option>
                        </select>
                    </div>
                </div>
                    <!-- ✅ Hidden textbox -->
                
                <div class="col-xl-2 before_deadline_col" style="display:none;">
                    <div class="primary_input mt-2">
                        <input type="number" name="before_days[${count}]" 
                            class="primary_input_field" 
                            placeholder="Days before deadline">
                    </div>
                </div>
                <div class="col-xl-2 after_deadline_col" style="display:none;">
                    <div class="primary_input mt-2">
                        <input type="number" name="after_days[${count}]" 
                            class="primary_input_field" 
                            placeholder="Days after deadline">
                    </div>
                </div>

                <div class="col-xl-1 text-center">
                    <button type="button" class="btn btn-danger remove_row" data-id="${count}">
                        ✕
                    </button>
                </div>

            </div>
            `;

        $('#learning_plan_container').append(row);

        // Initialize Select2 for new row
        let newSelect = $('#row_' + count).find('.course_select');
        let newDeadlineselect = $('#row_' + count).find('.deadline_select');
        

        newSelect.select2({
            placeholder: "Select course",
            allowClear: true,
            width: '100%'
        });

        newDeadlineselect.select2({
            placeholder: "Select Deadline",
            allowClear: true,
            width: '100%'
        });

        // Update options across all dropdowns
        updateCourseOptions();
    });

    $(document).on('change', '.deadline_select', function () {

        let selectedValues = $(this).val() || [];
        let row = $(this).closest('.row');

        if (selectedValues.includes("2")) {
            row.find('.before_deadline_col').show();
        } else {
            row.find('.before_deadline_col').hide();
            row.find('.before_deadline_col input').val('');
        }
    });

    $(document).on('change', '.deadline_select', function () {

        let selectedValues = $(this).val() || [];
        let row = $(this).closest('.row');

        if (selectedValues.includes("3")) {
            row.find('.after_deadline_col').show();
        } else {
            row.find('.after_deadline_col').hide();
            row.find('.after_deadline_col input').val('');
        }
    });
    // Function to disable already selected courses
    function updateCourseOptions() {

        let selectedCourses = [];

        // Collect all selected values
        $('.course_select').each(function () {
            let val = $(this).val();
            if (val) {
                selectedCourses = selectedCourses.concat(val);
            }
        });

        // Loop each select
        $('.course_select').each(function () {

            let currentSelect = $(this);
            let currentValues = currentSelect.val() || [];

            currentSelect.find('option').each(function () {

                let optionValue = $(this).val();

                // Skip "All"
                if (optionValue === "0") return;

                if (
                    selectedCourses.includes(optionValue) &&
                    !currentValues.includes(optionValue)
                ) {
                    $(this).prop('disabled', true);
                } else {
                    $(this).prop('disabled', false);
                }
            });

            // Refresh Select2 UI
            currentSelect.trigger('change.select2');
        });
    }


    // Trigger on selection change
    $(document).on('change', '.course_select', function () {
        updateCourseOptions();
    });


    // Remove Row
    $(document).on('click', '.remove_row', function () {
        let id = $(this).data('id');
        $('#row_' + id).remove();

        // Refresh options after removal
        updateCourseOptions();
    });    

    
    $('.learning_grp').select2({
    placeholder: "Select Department",
    allowClear: true
    });

    
    </script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>

@endpush
