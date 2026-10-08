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
     $url = route('getAllLearningPlanData').'?search_status='.$status.'&category='.$category.'&type='.$type.'&instructor='.$instructor.'&required_type='.$search_required_type.'&mode_of_delivery='.$search_delivery_mode;
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
                                            id="page_title">{{__('courses.Learning Plan')}}</h3>

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
                                        @if (permissionCheck('learningplan.store'))
                                            <a class="primary-btn radius_30px  float-end  fix-gr-bg"
                                               href="{{route('learningplan.store')}}">
                                                <i class="ti-plus"></i>{{__('common.Add')}} {{__('courses.Learning Plan')}}</a>
                                        @endif
                                    </div>

                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="QA_section QA_section_heading_custom check_box_table">
                                    <div class="QA_table">
                                        <!-- table-responsive -->
                                        <div class="">
                                            <table id="lms_table" class="table classList">
                                                
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
            { data: 'occurs_when', name: 'occurs_when' },
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

            drawCallback: function () {

                let data = this.api().rows({ page: 'current' }).data();

                $('#cardViewContainer').html('');

                data.each(function (row) {

                    let occursWhenText = '';

                    if (row.occurs_when == 1) {
                        occursWhenText = 'Daily';
                    } else if (row.occurs_when == 2) {
                        occursWhenText = 'Weekly';
                    } else if (row.occurs_when == 3) {
                        occursWhenText = 'Monthly';
                    } else {
                        occursWhenText = row.occurs_when;
                    }

                    let card = `
                    <div class="col-lg-4 col-md-4 col-sm-6 mb-30">

                        <div class="white_box h-100">

                            <div class="p-3">
                                <img class="w-100"
                                    src="${row.thumbnail && row.thumbnail !== '' ? row.thumbnail : '{{ asset('public/assets/course/no_image.png') }}'}"
                                    onerror="this.onerror=null;this.src='{{ asset('public/assets/course/no_image.png') }}';"
                                    style="height:180px;object-fit:cover;border-radius:8px;"
                                    alt="">


                                <h5 class="mb-3">${row.title}</h5>

                                <div class="mb-2">
                                    <strong>Occurs When:</strong> ${occursWhenText}
                                </div>

                                <div class="mb-2">
                                    <strong>Created By:</strong> ${row.created_by}
                                </div>

                                <div class="mb-3">
                                    <strong>Created At:</strong> ${row.created_at}
                                </div>

                                <div>
                                    ${row.action}
                                </div>

                            </div>

                        </div>

                    </div>
                    `;

                    $('#cardViewContainer').append(card);
                });
            }
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

$('.viewToggle').click(function(){

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
