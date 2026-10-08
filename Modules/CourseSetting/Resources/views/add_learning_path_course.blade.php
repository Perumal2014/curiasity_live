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
                <h4>{{__('common.Add New')}} {{__('courses.Learning Path')}}</h4>
            </div>
            <div class="col-lg-12">


                <input type="hidden" id="url" value="{{url('/')}}">

                    <form action="{{ route('learningPathstore', ['id' => $id]) }}"  id="learningPlanForm" method="POST" enctype="multipart/form-data">
                            @csrf
                            <!-- Title -->
                           
                            

                            <!-- Steps Container -->
                            <div id="step_container"></div>

                            <!-- Add Step Button -->
                            <button type="button" class="primary-btn small fix-gr-bg" id="add_step">
                                + ADD STEP
                            </button>

                            <div class="col-lg-12 text-center pt_15">
                                <div class="d-flex justify-content-center">
                                    <button class="primary-btn semi_large2  fix-gr-bg" id="save_button_parent"
                                            type="submit"><i
                                            class="ti-check"></i> {{__('common.Publish') }}
                                    </button>
                                </div>
                            </div>

                            <input type="hidden" name="grp_type" id="grp_type">

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
       $(document).ready(function () {
        $('#add_step').trigger('click');
       });
            
    let count = 0;

    // Add Row
  
  

    // Remove Row
    function updateCourseOptions() {

        let selectedCourses = [];

        $('.course_select').each(function () {
            let val = $(this).val();
            if (val) {
                selectedCourses = selectedCourses.concat(val);
            }
        });

        

       

        $('.course_select').each(function () {

            let current = $(this);
            let currentVals = current.val() || [];

            current.find('option').each(function () {

                let value = $(this).val();

                if (value == "0") return;

                // ✅ Only disable if selected in OTHER dropdowns
                if (selectedCourses.includes(value) && !currentVals.includes(value)) {
                    $(this).prop('disabled', true);
                } else {
                    $(this).prop('disabled', false);
                }

            });

            current.trigger('change.select2'); // refresh
        });
    }

    $(document).on('change', '.course_select', function () {
        updateCourseOptions();
    });

    function reorderSteps() {
        let index = 1;

        $('#step_container .card').each(function () {

            // Update step title
            $(this).find('h3').text('Group ' + index);

            // Update ID
            $(this).attr('id', 'step_' + index);

            // Update remove button
            $(this).find('.remove_step').attr('data-id', index);

            // ✅ Update input names properly
            $(this).find('select, input').each(function () {

                let name = $(this).attr('name');

                if (name) {
                    name = name.replace(/steps\[\d+\]/, 'steps[' + index + ']');
                    $(this).attr('name', name);
                }

            });

            index++;
        });

        stepCount = index - 1;
    }

    
        $('#recgrpList_id').select2({
            placeholder: "Select Plan Group",
            width: '100%',
        });
    
    function initSelect2(element) {
        element.select2({
            placeholder: "Select courses",
            width: '100%',
            
        });
    }

    let stepCount = 0;

    $('#add_step').click(function () {

    stepCount++;

    let html = `
    <div class="card p-3 mb-3" id="step_${stepCount}" style="border-radius:10px;">
            
            <div class="d-flex justify-content-between align-items-center">
                <input type="text" 
                    name="steps[${stepCount}][group_name]" 
                    class="form-control w-50 fw-bold" 
                    value="Group ${stepCount}" 
                    placeholder="Enter Group Name">

                <button type="button" class="btn btn-danger remove_step" data-id="${stepCount}">
                    ✕
                </button>
            </div>

            <div class="row mt-3">

                <!-- Courses -->
                <div class="col-xl-8">
                    <label>Select Courses</label>
                    <select name="steps[${stepCount}][courses][]" 
                            class="multi_select course_select" 
                            data-step="${stepCount}"
                            multiple>
                        
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}">
                                {{ $course->title }}
                            </option>
                        @endforeach

                    </select>
                </div>

                <!-- Completion Rule -->
                <div class="col-xl-4">
                    <label>Completion Rule</label>
                    <select name="steps[${stepCount}][completion_type]" 
                            class="primary_select completion_rule"
                            id="completion_rule_${stepCount}">
                        <option value="">Select Rule</option>
                    </select>
                </div>

            </div>

        </div>
    `;

    $('#step_container').append(html);

    let newSelect = $('#step_' + stepCount).find('.multi_select');
    initSelect2(newSelect);

    updateCourseOptions();
});

// course select change event
$(document).on('change', '.course_select', function () {

    let selectedCourses = $(this).val() || [];
    let count = selectedCourses.length;
    let stepId = $(this).data('step');

    let completionDropdown = $('#completion_rule_' + stepId);

    completionDropdown.empty();

    completionDropdown.append(`<option value="">Select Rule</option>`);

    for (let i = 0; i <= count; i++) {
        completionDropdown.append(
            `<option value="${i}">${i}</option>`
        );
    }
});

// remove step
$(document).on('click', '.remove_step', function () {
    let id = $(this).data('id');
    $('#step_' + id).remove();

    reorderSteps(); // optional
});

    
    </script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>

@endpush
