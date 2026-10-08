@php use Illuminate\Support\Facades\Auth; @endphp
@extends('backend.master')


@php
    $table_name='courses';

     $category=request()->get('category');
     $type=request()->get('type');
     $instructor=request()->get('instructor');
     $status=request()->get('search_status');
     $search_required_type=request()->get('search_required_type');
     $search_delivery_mode=request()->get('search_delivery_mode');
     $url = route('getAllLearningPathData').'?search_status='.$status.'&category='.$category.'&type='.$type.'&instructor='.$instructor.'&required_type='.$search_required_type.'&mode_of_delivery='.$search_delivery_mode;
     $text =trans('common.All');

@endphp

@section('table')
    {{$table_name}}
@stop
@section('mainContent')
    {!! generateBreadcrumb() !!}

    <style>
        #cardViewContainer .white_box{
        border-radius:12px;
        overflow:hidden;
        transition:0.3s;
        }

        #confirm_cancel_delete_bulk .modal-body {
            max-height: 70vh;
            overflow-y: auto;
            overflow-x: hidden;
        }
        .select2-container {
            width: 100% !important;
        }

        .select2-dropdown {
            z-index: 99999 !important;
        }

        .select2-results__options {
            max-height: 200px !important;
            overflow-y: auto !important;
        }
        .select2-container--default .select2-selection--multiple {
                    border-radius: 6px;
                    padding: 4px;
        }

        .select2-container {
            z-index: 9999 !important;
        }

        .select2-dropdown {
            z-index: 9999 !important;
        }

        #cardViewContainer .white_box:hover{
            transform:translateY(-4px);
            box-shadow:0 8px 20px rgba(0,0,0,0.08);
        }
    </style>
    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid p-0">

            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="white_box mb_30">
                        <div class="white_box_tittle list_header main-title mb-0">
                            <h3 class="mb-0">{{__('courses.Advanced Filter')}} </h3>
                        </div>
                        <form action="{{route('getAllCatalogs')}}" method="GET">
                            <div class="row">

                                <div class="col-lg-3 mt-20">

                                    <label class="primary_input_label" for="category">{{__('courses.Category')}}</label>
                                    <select class="primary_select" name="category" id="category">
                                        <option data-display="{{__('common.Select')}} {{__('courses.Category')}}"
                                                value="">{{__('common.Select')}} {{__('courses.Category')}}</option>
                                        @foreach($categories as $category)
                                            @if($category->parent_id==0)
                                                @include('backend.categories._single_select_option',['category'=>$category,'level'=>1])
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3 mt-20">

                                    <label class="primary_input_label" for="type">{{__('courses.Type')}}</label>
                                    <select class="primary_select" name="type" id="type">
                                        <option data-display="{{__('common.Select')}} {{__('courses.Type')}}"
                                                value="">{{__('common.Select')}} {{__('courses.Type')}}</option>
                                        <option
                                            value="1" {{isset($category_type)?$category_type==1?'selected':'':''}}>{{__('courses.Course')}}</option>
                                        <option
                                            value="2" {{isset($category_type)?$category_type==2?'selected':'':''}}>{{__('quiz.Quiz')}}</option>
                                    </select>

                                </div>
                                <div class="col-lg-3 mt-20">

                                    <label class="primary_input_label"
                                           for="instructor">{{__('courses.Instructor')}}</label>
                                    <select class="primary_select" name="instructor" id="instructor">
                                        <option data-display="{{__('common.Select')}} {{__('courses.Instructor')}}"
                                                value="">{{__('common.Select')}} {{__('courses.Instructor')}}</option>
                                        @foreach($instructors as $instructor)
                                            <option
                                                value="{{$instructor->id}}" {{isset($category_instructor)?$category_instructor==$instructor->id?'selected':'':''}}>{{@$instructor->name}} </option>
                                        @endforeach
                                    </select>

                                </div>

                                <div class="col-lg-3 mt-20">

                                    <label class="primary_input_label" for="status">{{__('common.Status')}}</label>
                                    <select class="primary_select" name="search_status" id="status">
                                        <option data-display="{{__('common.Select')}} {{__('common.Status')}}"
                                                value="">{{__('common.Select')}} {{__('common.Status')}}</option>
                                        <option
                                            value="Active" {{isset($category_status)?$category_status=="Active"?'selected':'':'selected'}}>{{__('courses.Active')}} </option>
                                        <option
                                            value="Inactive" {{isset($category_status)?$category_status=="Inactive"?'selected':'':''}}>{{__('common.Inactive')}} </option>
                                    </select>

                                </div>
                                @if(isModuleActive('Org'))
                                    <div class="col-lg-3 mt-20">
                                        <label class="primary_input_label"
                                               for="search_required_type">{{__('courses.Required Type')}}</label>
                                        <select class="primary_select" name="search_required_type"
                                                id="search_required_type">
                                            <option
                                                data-display="{{__('common.Select')}} {{__('courses.Required Type')}}"
                                                value="">{{__('common.Select')}} {{__('courses.Required Type')}}</option>
                                            <option
                                                value="Compulsory" {{isset($search_required_type)?$search_required_type=="Compulsory"?'selected':'':''}}>{{__('courses.Compulsory')}} </option>
                                            <option
                                                value="Open" {{isset($search_required_type)?$search_required_type=="Open"?'selected':'':''}}> {{__('courses.Open')}}</option>
                                        </select>

                                    </div>

                                    <div class="col-lg-3 mt-20">

                                        <label class="primary_input_label"
                                               for="status">{{__('courses.Delivery Mode')}}</label>
                                        <select class="primary_select" name="search_delivery_mode" id="status">
                                            <option
                                                data-display="{{__('common.Select')}} {{__('courses.Delivery Mode')}}"
                                                value="">{{__('common.Select')}} {{__('courses.Delivery Mode')}}</option>
                                            <option
                                                value="1" {{isset($search_delivery_mode)?$search_delivery_mode=="1"?'selected':'':''}}>{{__('courses.Online')}} </option>
                                            <option
                                                value="3" {{isset($search_delivery_mode)?$search_delivery_mode=="3"?'selected':'':''}}>{{__('courses.Offline')}}</option>
                                        </select>

                                    </div>
                                @endif
                                <div class="col-12 mt-20">
                                    <div class="search_course_btn text-end">
                                        <button type="submit"
                                                class="primary-btn radius_30px   fix-gr-bg">
                                            <span class="ti-search pe-2"></span>

                                            {{__('courses.Filter')}} </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="white-box">
                        <div class="row">
                            <div class="col-12">
                                <div class="box_header common_table_header">
                                    <div class="main-title d-md-flex justify-content-between align-items-center">

                                        <h3 class="mb-0 mr-30 mb_xs_15px mb_sm_20px"
                                            id="page_title">{{__('courses.Learning Path')}}</h3>

                                             <!-- GRID VIEW BUTTON -->
                                                <!-- <button type="button"
                                                        class="primary-btn small fix-gr-bg viewToggle active mr-10"
                                                        data-view="grid">
                                                    <i class="ti-layout-grid2"></i>
                                                </button> -->

                                                <!-- LIST VIEW BUTTON -->
                                                <!-- <button type="button"
                                                        class="primary-btn small fix-gr-bg viewToggle mr-20"
                                                        data-view="list">
                                                    <i class="ti-view-list"></i>
                                                </button> -->
                                        
                                            

                                            <div class="box_header common_table_header">
                                                <div class="main-title d-md-flex">

                                                    <!-- <h3 class="mb-0 mr-30 mb_xs_15px mb_sm_20px"
                                                        id="page_title">{{ __('common.Add Learning Path')}}</h3> -->
                                                    @if (permissionCheck('learningpath.store'))
                                                        <a class="primary-btn radius_30px  float-end  fix-gr-bg"
                                                        href="{{route('learningpath.store')}}">
                                                            <i class="ti-plus"></i>{{ __('common.Add Learning Path')}}</a>
                                                    @endif
                                                </div>


                                            </div>
                                    </div>

                                </div>
                                
                            </div>

                            <div class="col-lg-12">
                                <div class="QA_section QA_section_heading_custom check_box_table">
                                    <div class="QA_table">
                                        <!-- table-responsive -->
                                        <div class="">
                                            <table id="lms_table" class="table classList">
                                                <thead>
                                                <tr>
                                                    <th scope="col"> {{__('common.SL')}}</th>
                                                    <th scope="col"> {{__('coupons.Type')}}</th>
                                                    @if(isModuleActive('Org'))
                                                        <th scope="col"> {{__('courses.Required Type')}}</th>
                                                    @endif
                                                    <th scope="col">{{__('courses.Course')}}
                                                        /{{__('quiz.Quiz')}} {{__('coupons.Title')}}</th>
                                                    <th scope="col">{{__('courses.Delivery')}}</th>
                                                    <th scope="col">{{__('quiz.Category')}}</th>
                                                    @if(!isModuleActive('Org'))
                                                        <th scope="col">{{__('quiz.Quiz')}}</th>
                                                    @endif
                                                    <th scope="col">{{__('courses.Instructor')}}</th>
                                                    <th scope="col">{{__('courses.Lesson')}}</th>
                                                    <th scope="col">{{__('courses.Enrolled')}}</th>
                                                    @if(showEcommerce())
                                                        <th scope="col">{{__('courses.Price')}}</th>
                                                    @endif
                                                    <th scope="col">{{__('courses.View Scope')}}</th>
                                                    <th scope="col">{{__('common.Status')}}</th>
                                                    <th scope="col">{{__('common.Action')}}</th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
                                            <div id="cardViewContainer" class="row mt-30" style="display:none;"></div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

               


            </div>


        </div>

        <div class="modal fade" id="confirm_cancel_delete_bulk" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content custom-modal">

                    <!-- HEADER -->
                    <div class="modal-header">
                        <h4 class="modal-title">{{__('common.New')}} {{__('courses.Learning Path')}}</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                            <i class="ti-close "></i>
                        </button>
                     </div>
                    <!-- BODY -->
                    

                </div>
            </div>
        </div>
        <div class="modal fade" id="learningPlanModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Learning Path Details</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <div class="mb-3">
                    <strong>Title:</strong>
                    <div id="modal_title"></div>
                </div>

                <div class="mb-3">
                    <strong>Created By:</strong>
                    <div id="modal_created_by"></div>
                </div>

                <div class="mb-3">
                    <strong>Date:</strong>
                    <div id="modal_created_at"></div>
                </div>

                <div class="mb-3">
                    <strong>Planning Days:</strong>
                    <div id="modal_occurs_when"></div>
                </div>

                <div class="mb-3">
                    <strong>Total Courses:</strong>
                    <div id="modal_total_courses"></div>
                </div>

                <div class="mb-3">
                    <strong>Courses:</strong>
                    <div id="modal_courses"></div>
                </div>

                <div class="mb-3">
                    <strong>Description:</strong>
                    <div id="modal_description"></div>
                </div>

            </div>

        </div>
    </div>
</div>
    </section>
    @include('backend.partials.delete_modal')
@endsection
@push('scripts')

    <script src="{{asset('/')}}/Modules/CourseSetting/Resources/assets/js/course.js"></script>
    
    <script>
        dataTableOptions.stateSave = false
        dataTableOptions.serverSide = true
        dataTableOptions.processing = true
        dataTableOptions.ajax = '{!! $url !!}';
        dataTableOptions.columns = [
            { data: 'DT_RowIndex', name: 'id' },
            { data: 'title', name: 'title' },
            { data: 'description', name: 'description' },
            { data: 'created_by', name: 'created_by' },
            { data: 'created_at', name: 'created_at' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ];
        @if(isModuleActive('Org') && showEcommerce())
            dataTableOptions = updateColumnExportOption(dataTableOptions, [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);
        @elseif(showEcommerce())
            dataTableOptions = updateColumnExportOption(dataTableOptions, [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);
        @else
            dataTableOptions = updateColumnExportOption(dataTableOptions, [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]);
        @endif



        // let table = $('.classList').DataTable(dataTableOptions);


       let table = $('.classList').DataTable({

    ...dataTableOptions,

    drawCallback:function(){

        let data = this.api().rows({page:'current'}).data();

        $('#cardViewContainer').html('');

        data.each(function(row){

            let card = `
            <div class="col-lg-4 col-md-4 col-sm-6 mb-30">

                <div class="white_box h-100">

                    <div class="position-relative">

                        <img class="w-100"
                                src="${row.thumbnail ? row.thumbnail : '{{ asset('uploads/default.png') }}'}"
                                style="height:180px;object-fit:cover;border-radius:8px;"
                                alt="">

                        <span class="badge bg-success position-absolute"
                              style="top:10px;right:10px;">
                            ${row.status}
                        </span>

                    </div>

                    <div class="p-3">

                        <h5 class="mb-2">${row.title}</h5>

                        <p class="text-muted mb-1">
                            <strong>Created By:</strong> ${row.created_by}
                        </p>

                        <p class="text-muted mb-3">
                            <strong>Date:</strong> ${row.created_at}
                        </p>

                        <div class="d-flex gap-2">

                            <button class="btn btn-sm btn-primary viewPlanBtn"
                                data-title="${row.title}"
                                data-created_by="${row.created_by}"
                                data-created_at="${row.created_at}"
                                data-description="${row.description ?? ''}"
                                data-courses="${row.course_names ?? ''}"
                                data-total_courses="${row.total_courses ?? 0}"
                                data-occurs_when="${row.occurs_when ?? ''}">
                                View Details
                            </button>

                            <div>
                                ${row.action}
                            </div>

                        </div>

                    </div>

                </div>

            </div>
            `;

            $('#cardViewContainer').append(card);

        });

    }

});

$(document).on('click', '.viewPlanBtn', function () {

    $('#modal_title').text($(this).data('title'));
    $('#modal_created_by').text($(this).data('created_by'));
    $('#modal_created_at').text($(this).data('created_at'));
    $('#modal_description').text($(this).data('description'));
    $('#modal_courses').text($(this).data('courses'));
    $('#modal_total_courses').text($(this).data('total_courses'));

    let occursWhen = $(this).data('occurs_when');

    if (occursWhen == 1) {
        occursWhen = 'Daily';
    } else if (occursWhen == 2) {
        occursWhen = 'Weekly';
    } else if (occursWhen == 3) {
        occursWhen = 'Monthly';
    }

    $('#modal_occurs_when').text(occursWhen);

    $('#learningPlanModal').modal('show');
});

// default grid view
$('#lms_table_wrapper').hide();
$('#cardViewContainer').show();
 
$('.viewToggle').click(function(){

    $('.viewToggle').removeClass('active');
    $(this).addClass('active');

    let view = $(this).data('view');

    if(view === 'grid'){
        $('#lms_table_wrapper').hide();
        $('#cardViewContainer').show();
    }else{
        $('#cardViewContainer').hide();
        $('#lms_table_wrapper').show();
    }

});

$('.viewToggle').click(function() {

    $('.viewToggle').removeClass('active');
    $(this).addClass('active');

    let view = $(this).data('view');

    if(view === 'grid'){

        $('#lms_table_wrapper').hide();   // hide datatable
        $('#cardViewContainer').show();   // show cards

    }else{

        $('#cardViewContainer').hide();   // hide cards
        $('#lms_table_wrapper').show();   // show datatable

    }

});
   
   

   
  
    </script>

  
    @endpush
