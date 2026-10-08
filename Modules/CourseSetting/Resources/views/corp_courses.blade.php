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
$url =
route('getAllCourseData').'?search_status='.$status.'&category='.$category.'&type='.$type.'&instructor='.$instructor.'&required_type='.$search_required_type.'&mode_of_delivery='.$search_delivery_mode;
$text =trans('common.All');

@endphp

@section('table')
{{$table_name}}
@stop
@section('mainContent')
{!! generateBreadcrumb() !!}

<style>
/* .dataTables_wrapper > .dataTables_paginate {
    display: none;
} */
#cardViewContainer .white_box {
    border-radius: 12px;
    overflow: hidden;
    transition: 0.3s;
}

#cardViewContainer .white_box:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
}


#cardViewContainer .dataTables_paginate {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 10px;
    margin-top: 25px;
}


#cardViewContainer .paginate_button {
    border: none !important;
    background: #f1f3f5 !important;
    color: #555 !important;
    padding: 8px 12px !important;
    border-radius: 50% !important;
    min-width: 38px;
    height: 38px;
    display: flex !important;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    transition: 0.2s;
}


#cardViewContainer .paginate_button:hover {
    background: #556ee6 !important;
}


#cardViewContainer .paginate_button.current {
    background: #556ee6 !important;
    color: #fff !important;
    box-shadow: 0 4px 10px rgba(85, 110, 230, 0.3);
}


#cardViewContainer .paginate_button.previous,
#cardViewContainer .paginate_button.next {
    font-size: 16px;
    font-weight: bold;
}


#cardViewContainer .paginate_button.disabled {
    /* opacity: 0.4 !important; */
    cursor: not-allowed !important;
}


.dataTables_wrapper .dataTables_paginate {
    background: transparent !important;
    border: none !important;
}


.card-pagination-wrapper{
    display:flex;
    justify-content:space-between;
    align-items:center;
    width:100%;
    margin-top:30px;
    padding-top:20px;
    border-top:1px solid #E5E7EB;
}

.card-pagination-wrapper .dataTables_info{
    color:#6B7280;
    font-size:14px;
    margin:0;
}

.card-pagination-wrapper .dataTables_paginate{
    margin-left:auto !important;
    float:none !important;
}

.card-pagination-wrapper .paginate_button{
    display:inline-flex !important;
    align-items:center;
    justify-content:center;

    min-width:38px;
    height:38px;
    margin:0 4px;

    border-radius:50% !important;

    color:#556EE6 !important;
    font-size:15px;
    font-weight:700 !important;

    transition:all .3s ease;
}

.card-pagination-wrapper .paginate_button:hover{
    background:#EEF2FF !important;
    color:#556EE6 !important;
}

/* Active Page */
.card-pagination-wrapper .paginate_button.current,
.card-pagination-wrapper .paginate_button.current:hover{

    min-width:52px !important;
    height:52px !important;

    background:#556EE6 !important;
    color:#fff !important;

    border-radius:50% !important;

    font-size:16px !important;
    font-weight:700 !important;

    box-shadow:0 8px 20px rgba(85,110,230,.35);

    transform:scale(1.05);
}

/* Previous / Next */
.card-pagination-wrapper .paginate_button.previous,
.card-pagination-wrapper .paginate_button.next{
    font-size:18px;
    font-weight:700;
}

/* Disabled */
.card-pagination-wrapper .paginate_button.disabled{
    opacity:.4;
    cursor:not-allowed;
}

@media(max-width:768px){

    .card-pagination-wrapper{
        flex-direction:column;
        gap:15px;
    }

    .card-pagination-wrapper .dataTables_paginate{
        margin-left:0 !important;
    }
}
</style>
<style>
.progress-bar {
    background-color: #9734f2;
}

.select2-container {
    width: 100% !important;
}

.select2-dropdown {
    z-index: 9999;
}

/* Dropdown scroll area */
.select2-results__options {
    max-height: auto !important;
    overflow-y: auto;
}

/* Group header */
.select2-results__group {
    font-weight: 600;
    color: #444;
    padding: 8px 10px;
    background: #c2bfbf;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: .4px;
    display: block;
}

/* Nested list */
.select2-results__options--nested {
    padding-left: 0 !important;
    margin-bottom: 6px;
}

.select2-search__field {
    color: #000 !important;
    background-color: #fff !important;
    caret-color: #000 !important;
}

/* Child options */
.select2-results__options--nested .select2-results__option {
    padding: 6px 10px 6px 25px !important;
}

.select2-container--default .select2-selection--multiple {
    border-radius: 6px;
    padding: 4px;
}

/* BACKGROUND */
body {
    background: #1e1e1e;
    font-family: Arial, sans-serif;
}

/* FIX SCROLL + CENTER */
body.modal-open {
    overflow: hidden !important;
    padding-right: 0 !important;
}

/* MODAL POSITION */
.modal-dialog {
    max-width: 650px;
    width: 100%;
}

/* MODAL BOX */
.modal-content {
    background: #2b2b2b;
    color: #ddd;
    border-radius: 12px;
    max-height: 90vh;
    overflow-y: auto;
    padding: 20px;
}

/* HEADER */
.modal-title {
    font-size: 18px;
    font-weight: 600;
}

/* RADIO */
.radio-group label {
    display: block;
    margin-bottom: 8px;
    font-size: 13px;
    color: #ccc;
}

/* TABS */
.tabs {
    display: flex;
    gap: 25px;
    border-bottom: 1px solid #444;
    margin: 20px 0 10px;
}

.tab-item {
    padding: 8px 0;
    cursor: pointer;
    color: #aaa;
    position: relative;
}

.tab-item.active {
    color: #4b2323;
    font-weight: 600;
}

.tab-item.active::after {
    content: "";
    position: absolute;
    bottom: -1px;
    left: 0;
    width: 100%;
    height: 2px;
    background: orange;

}

/* TAB CONTENT */
.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}

/* SECTION */
.section {
    margin-top: 20px;
}

.section h6 {
    font-size: 14px;
    color: #0f0d0d;
}

.section p {
    font-size: 12px;
    color: #aaa;
}

/* INPUT */
input,
textarea {
    width: 100%;
    background: #1e1e1e;
    border: 1px solid #444;
    color: #fff;
    padding: 8px 10px;
    border-radius: 6px;
}

/* LINK */
.link {
    display: inline-block;
    margin-top: 8px;
    color: #4da3ff;
    font-size: 12px;
}

/* FOOTER */
.help {
    font-size: 12px;
    color: #aaa;
}

/* BUTTONS */
.btn {
    padding: 8px 16px;
    border-radius: 6px;
    font-size: 13px;
}

.cancel {
    background: #444;
    color: #ddd;
    border: none;
}

.primary {
    background: #4da3ff;
    color: #fff;
    border: none;
}

/* BACKDROP FIX */
.modal-backdrop {
    z-index: 1040 !important;
}

.modal {
    z-index: 1050 !important;
}
</style>
<style>
.progress-bar {
    background-color: #9734f2;
}

.select2-container {
    width: 100% !important;
}

.select2-dropdown {
    z-index: 9999;
}

/* Dropdown scroll area */
.select2-results__options {
    max-height: auto !important;
    overflow-y: auto;
}

/* Group header */
.select2-results__group {
    font-weight: 600;
    color: #444;
    padding: 8px 10px;
    background: #c2bfbf;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: .4px;
    display: block;
}

/* Nested list */
.select2-results__options--nested {
    padding-left: 0 !important;
    margin-bottom: 6px;
}

.select2-search__field {
    color: #000 !important;
    background-color: #fff !important;
    caret-color: #000 !important;
}

/* Child options */
.select2-results__options--nested .select2-results__option {
    padding: 6px 10px 6px 25px !important;
}

.select2-container--default .select2-selection--multiple {
    border-radius: 6px;
    padding: 4px;
}

/* BACKGROUND */
body {
    background: #1e1e1e;
    font-family: Arial, sans-serif;
}

/* FIX SCROLL + CENTER */
body.modal-open {
    overflow: hidden !important;
    padding-right: 0 !important;
}

/* MODAL POSITION */
.modal-dialog {
    max-width: 650px;
    width: 100%;
}

/* MODAL BOX */
.modal-content {
    background: #2b2b2b;
    color: #ddd;
    border-radius: 12px;
    max-height: 90vh;
    overflow-y: auto;
    padding: 20px;
}

/* HEADER */
.modal-title {
    font-size: 18px;
    font-weight: 600;
}

/* RADIO */
.radio-group label {
    display: block;
    margin-bottom: 8px;
    font-size: 13px;
    color: #ccc;
}

/* TABS */
.tabs {
    display: flex;
    gap: 25px;
    border-bottom: 1px solid #444;
    margin: 20px 0 10px;
}

.tab-item {
    padding: 8px 0;
    cursor: pointer;
    color: #aaa;
    position: relative;
}

.tab-item.active {
    color: #4b2323;
    font-weight: 600;
}

.tab-item.active::after {
    content: "";
    position: absolute;
    bottom: -1px;
    left: 0;
    width: 100%;
    height: 2px;
    background: orange;

}

/* TAB CONTENT */
.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}

/* SECTION */
.section {
    margin-top: 20px;
}

.section h6 {
    font-size: 14px;
    color: #0f0d0d;
}

.section p {
    font-size: 12px;
    color: #aaa;
}

/* INPUT */
input,
textarea {
    width: 100%;
    background: #1e1e1e;
    border: 1px solid #444;
    color: #fff;
    padding: 8px 10px;
    border-radius: 6px;
}

/* LINK */
.link {
    display: inline-block;
    margin-top: 8px;
    color: #4da3ff;
    font-size: 12px;
}

/* FOOTER */
.help {
    font-size: 12px;
    color: #aaa;
}

/* BUTTONS */
.btn {
    padding: 8px 16px;
    border-radius: 6px;
    font-size: 13px;
}

.cancel {
    background: #444;
    color: #ddd;
    border: none;
}

.primary {
    background: #4da3ff;
    color: #fff;
    border: none;
}

/* BACKDROP FIX */
.modal-backdrop {
    z-index: 1040 !important;
}

.modal {
    z-index: 1050 !important;
}
#lms_table_wrapper > .dataTables_paginate:first-of-type {
    display: none !important;
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
                                    <option data-display="{{__('common.Select')}} {{__('courses.Category')}}" value="">
                                        {{__('common.Select')}} {{__('courses.Category')}}</option>
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
                                    <option data-display="{{__('common.Select')}} {{__('courses.Type')}}" value="">
                                        {{__('common.Select')}} {{__('courses.Type')}}</option>
                                    <option value="1" {{isset($category_type)?$category_type==1?'selected':'':''}}>
                                        {{__('courses.Course')}}</option>
                                    <option value="2" {{isset($category_type)?$category_type==2?'selected':'':''}}>
                                        {{__('quiz.Quiz')}}</option>
                                </select>

                            </div>
                            <div class="col-lg-3 mt-20">

                                <label class="primary_input_label" for="instructor">{{__('courses.Instructor')}}</label>
                                <select class="primary_select" name="instructor" id="instructor">
                                    <option data-display="{{__('common.Select')}} {{__('courses.Instructor')}}"
                                        value="">{{__('common.Select')}} {{__('courses.Instructor')}}</option>
                                    @foreach($instructors as $instructor)
                                    <option value="{{$instructor->id}}"
                                        {{isset($category_instructor)?$category_instructor==$instructor->id?'selected':'':''}}>
                                        {{@$instructor->name}} </option>
                                    @endforeach
                                </select>

                            </div>

                            <div class="col-lg-3 mt-20">

                                <label class="primary_input_label" for="status">{{__('common.Status')}}</label>
                                <select class="primary_select" name="search_status" id="status">
                                    <option data-display="{{__('common.Select')}} {{__('common.Status')}}" value="">
                                        {{__('common.Select')}} {{__('common.Status')}}</option>
                                    <option value="Active"
                                        {{isset($category_status)?$category_status=="Active"?'selected':'':'selected'}}>
                                        {{__('courses.Active')}} </option>
                                    <option value="Inactive"
                                        {{isset($category_status)?$category_status=="Inactive"?'selected':'':''}}>
                                        {{__('common.Inactive')}} </option>
                                </select>

                            </div>
                            @if(isModuleActive('Org'))
                            <div class="col-lg-3 mt-20">
                                <label class="primary_input_label"
                                    for="search_required_type">{{__('courses.Required Type')}}</label>
                                <select class="primary_select" name="search_required_type" id="search_required_type">
                                    <option data-display="{{__('common.Select')}} {{__('courses.Required Type')}}"
                                        value="">{{__('common.Select')}} {{__('courses.Required Type')}}</option>
                                    <option value="Compulsory"
                                        {{isset($search_required_type)?$search_required_type=="Compulsory"?'selected':'':''}}>
                                        {{__('courses.Compulsory')}} </option>
                                    <option value="Open"
                                        {{isset($search_required_type)?$search_required_type=="Open"?'selected':'':''}}>
                                        {{__('courses.Open')}}</option>
                                </select>

                            </div>

                            <div class="col-lg-3 mt-20">

                                <label class="primary_input_label" for="status">{{__('courses.Delivery Mode')}}</label>
                                <select class="primary_select" name="search_delivery_mode" id="status">
                                    <option data-display="{{__('common.Select')}} {{__('courses.Delivery Mode')}}"
                                        value="">{{__('common.Select')}} {{__('courses.Delivery Mode')}}</option>
                                    <option value="1"
                                        {{isset($search_delivery_mode)?$search_delivery_mode=="1"?'selected':'':''}}>
                                        {{__('courses.Online')}} </option>
                                    <option value="3"
                                        {{isset($search_delivery_mode)?$search_delivery_mode=="3"?'selected':'':''}}>
                                        {{__('courses.Offline')}}</option>
                                </select>

                            </div>
                            @endif
                            <div class="col-12 mt-20">
                                <div class="search_course_btn text-end">
                                    <button type="submit" class="primary-btn radius_30px   fix-gr-bg">
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

                                    <h3 class="mb-0 mr-30 mb_xs_15px mb_sm_20px" id="page_title">
                                        {{__('courses.Catalogs').'/'.__('quiz.Quiz')}} {{__('courses.List')}}</h3>

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
                                    @if (permissionCheck('course.store'))
                                    <a class="primary-btn radius_30px  float-end  fix-gr-bg"
                                        href="{{route('course.store')}}">
                                        <i class="ti-plus"></i>{{__('common.Add')}} {{__('courses.Course')}}
                                        /{{__('quiz.Quiz')}}</a>
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
                                        <div id="cardPaginationContainer" class="mt-4 d-flex justify-content-between align-items-center"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade admin-query" id="editCourse">
                <div class="modal-dialog modal_1000px modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">{{__('common.Edit')}} {{__('quiz.Topic')}} </h4>
                            <button type="button" class="close " data-bs-dismiss="modal">
                                <i class="ti-close "></i>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="{{route('AdminUpdateCourse')}}" method="POST" enctype="multipart/form-data"
                                id="courseEditForm">
                                @csrf
                                <div class="row">
                                    <div class="col-xl-6 ">
                                        <div class="primary_input mb-25">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label class="primary_input_label" for="    ">
                                                        {{__('courses.Type')}}</label>
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="radio" class="common-radio type1" id="type_edit_1"
                                                        name="type" value="1">
                                                    <label for="type_edit_1">{{__('courses.Course')}}</label>
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="radio" class="common-radio type2" id="type_edit_2"
                                                        name="type" value="2">
                                                    <label for="type_edit_2">{{__('quiz.Quiz')}}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-xl-6 dripCheck">
                                        <div class="primary_input mb-25">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label class="primary_input_label" for="    ">
                                                        {{__('common.Drip Content')}}</label>
                                                </div>

                                                <div class="col-md-6">

                                                    <input type="radio" class="common-radio drip0" id="drip_edit_0"
                                                        name="drip" value="0" {{@$course->drip==0?"checked":""}}>
                                                    <label for="drip_edit_0">{{__('common.No')}}</label>
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="radio" class="common-radio drip1" id="drip_edit_1"
                                                        name="drip" value="1" {{@$course->drip==1?"checked":""}}>
                                                    <label for="drip_edit_1">{{__('common.Yes')}}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-12">
                                        <div class="primary_input mb-25">
                                            <label class="primary_input_label" for="title">{{__('quiz.Topic')}}
                                                {{__('common.Title')}}
                                                *</label>
                                            <input class="primary_input_field" name="title" id="title" placeholder="-"
                                                type="text" {{$errors->has('title') ? 'autofocus' : ''}}>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="id" class="course_id" id="editCourseId" value="">

                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="primary_input mb-35">
                                            <label class="primary_input_label" for="about">{{__('courses.Course')}}
                                                {{__('courses.Requirements')}} </label>
                                            <textarea class="lms_summernote" name="requirements" id="requirementsEdit"
                                                cols="30" rows="10"> </textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="primary_input mb-35">
                                            <label class="primary_input_label" for="about">{{__('courses.Course')}}
                                                {{__('courses.Description')}}</label>
                                            <textarea class="lms_summernote" name="about" id="aboutEdit" cols="30"
                                                rows="10"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="primary_input mb-35">
                                            <label class="primary_input_label" for="about">{{__('courses.Course')}}
                                                {{__('courses.Outcomes')}} </label>
                                            <textarea class="lms_summernote" name="outcomes" id="outcomesEdit" cols="30"
                                                rows="10"> </textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">

                                    <div class="col-xl-6 courseBox">
                                        <select class="primary_select edit_category_id" name="category"
                                            {{$errors->has('category') ? 'autofocus' : ''}}>
                                            <option data-display="{{__('common.Select')}} {{__('quiz.Category')}}"
                                                value="">{{__('common.Select')}} {{__('quiz.Category')}}
                                                *
                                            </option>
                                            @foreach($categories as $category)
                                            <option value="{{$category->id}}">{{@$category->name}} </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-xl-6 courseBox" id="edit_subCategoryDiv{{@$course->id}}">
                                        <select class="primary_select " name="sub_category" id="edit_subcategory_id"
                                            {{$errors->has('sub_category') ? 'autofocus' : ''}}>
                                            <option
                                                data-display="{{__('common.Select')}} {{__('courses.Sub Category')}}"
                                                value="">{{__('common.Select')}} {{__('courses.Sub Category')}}

                                            </option>


                                        </select>
                                    </div>
                                    <div class="col-xl-6 mt-30 quizBox" style="display: none">
                                        <select class="primary_select" name="quiz" id="quiz_edit_id"
                                            {{$errors->has('quiz') ? 'autofocus' : ''}}>
                                            <option data-display="{{__('common.Select')}} {{__('quiz.Quiz')}}" value="">
                                                {{__('common.Select')}} {{__('quiz.Quiz')}}
                                                *
                                            </option>
                                            @foreach($quizzes as $quiz)
                                            <option value="{{$quiz->id}}">{{@$quiz->title}} </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-xl-4 mt-30 makeResize">
                                        <select class="primary_select" id="levelEdit" name="level"
                                            {{$errors->has('level') ? 'autofocus' : ''}}>
                                            <option data-display="{{__('common.Select')}} {{__('courses.Level')}}"
                                                value="">{{__('common.Select')}} {{__('courses.Level')}}
                                                *
                                            </option>
                                            @foreach($levels as $level)
                                            <option value="{{$level->id}}">
                                                {{$level->title}}
                                            </option>
                                            @endforeach

                                        </select>
                                    </div>
                                    <div class="col-xl-4 mt-30 makeResize" id="">
                                        <select class="primary_select mb_30" name="language" id="languageEdit"
                                            {{$errors->has('language') ? 'autofocus' : ''}}>
                                            <option data-display="{{__('common.Select')}} {{__('courses.Language')}}"
                                                value="">{{__('common.Select')}} {{__('courses.Language')}}
                                                *
                                            </option>

                                            @foreach ($languages as $language)
                                            <option value="{{$language->id}}">{{$language->native}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-xl-4 makeResize">
                                        <div class="primary_input mb-25">
                                            <label class="primary_input_label" for="">{{__('common.Duration')}}
                                                ({{__('common.In Minute')}})
                                                *</label>
                                            <input class="primary_input_field" id="durationEdit" name="duration"
                                                placeholder="-" min="0" step="any" type="number"
                                                {{$errors->has('duration') ? 'autofocus' : ''}}>
                                        </div>
                                    </div>
                                </div>


                                <div class="row d-none">
                                    <div class="col-lg-6">
                                        <div class="checkbox_wrap d-flex align-items-center">
                                            <label for="course_1" class="switch_toggle">
                                                <input type="checkbox" id="edit_course_1">
                                                <i class="slider round"></i>
                                            </label>
                                            <label
                                                class="primary_input_label mt-1">{{__('courses.This course is a top course')}}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-20">
                                    <div class="col-lg-6">
                                        <div class="checkbox_wrap d-flex align-items-center mt-40">
                                            <label for="editCourseFree" class="switch_toggle">
                                                <input type="checkbox" class="edit_course_2" name="is_free" value="1"
                                                    id="editCourseFree">
                                                <i class="slider round"></i>
                                            </label>
                                            <label
                                                class="primary_input_label mt-1">{{__('courses.This course is a free course')}}</label>
                                        </div>
                                    </div>
                                    <div class="col-xl-4" id="edit_price_div">
                                        <div class="primary_input mb-25">
                                            <label class="primary_input_label" for="">{{__('courses.Price')}}</label>
                                            <input class="primary_input_field" name="price" id="priceEdit"
                                                placeholder="-" value="" type="text">
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-20 editDiscountDiv">
                                    <div class="col-lg-6">
                                        <div class="checkbox_wrap d-flex align-items-center mt-40">
                                            <label for="editCourseDiscount" class="switch_toggle">
                                                <input type="checkbox" class="edit_course_3" name="is_discount"
                                                    value="1" id="editCourseDiscount">
                                                <i class="slider round"></i>
                                            </label>
                                            <label
                                                class="primary_input_label mt-1">{{__('courses.This course has discounted price')}}</label>
                                        </div>
                                    </div>

                                    <div class="col-xl-4" id="edit_discount_price_div">
                                        <div class="primary_input mb-25">
                                            <label class="primary_input_label" for="">{{__('courses.Discount')}}
                                                {{__('courses.Price')}}</label>
                                            <input class="primary_input_field editDiscount" name="discount_price"
                                                id="editDiscountPrice" placeholder="-" type="text">
                                        </div>
                                    </div>
                                </div>


                                <div class="row mt-20 videoOption">
                                    <div class="col-xl-4 mt-25">
                                        <select class="primary_select category_id " name="host" id="editCourseHost">
                                            <option data-display="{{__('courses.Course overview host')}} *" value="">
                                                {{__('courses.Course overview host')}}
                                            </option>

                                            <option value="Youtube">
                                                {{__('courses.Youtube')}}
                                            </option>
                                            <option value="Vimeo">
                                                {{__('courses.Vimeo')}}
                                            </option>
                                            @if(isModuleActive("AmazonS3"))
                                            <option value="AmazonS3">
                                                {{__('courses.Amazon S3')}}
                                            </option>
                                            @endif

                                            <option value="Self">
                                                {{__('courses.Self Host')}}
                                            </option>


                                        </select>
                                    </div>
                                    <div class="col-xl-8 ">
                                        <div class="input-effect videoUrl"
                                            style="display:@if((isset($course) && (@$course->host!=" Youtube")) ||
                                            !isset($course)) none @endif">
                                            <label class="primary_input_label mt-1">{{__('courses.Video URL')}}
                                                <span class="required_mark">*</span></label>
                                            <input id="couseEditViewUrl"
                                                class="primary_input_field youtubeVideo name{{ $errors->has('trailer_link') ? ' is-invalid' : '' }}"
                                                type="text" name="trailer_link"
                                                placeholder="{{__('courses.Video URL')}}" autocomplete="off" value=" "
                                                {{$errors->has('trailer_link') ? 'autofocus' : ''}}>
                                            <span class="focus-border"></span>
                                            @if ($errors->has('trailer_link'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('trailer_link') }}</strong>
                                            </span>
                                            @endif
                                        </div>

                                        <div class="row  vimeoUrl" id=""
                                            style="display: @if((isset($course) && (@$course->host!=" Vimeo")) ||
                                            !isset($course)) none @endif">
                                            <div class="col-lg-12" id="">
                                                <label class="primary_input_label"
                                                    for="">{{__('courses.Vimeo Video')}}</label>
                                                <select class="primary_select vimeoVideo" name="vimeo"
                                                    id="viemoEditCourse">
                                                    <option
                                                        data-display="{{__('common.Select')}} {{__('courses.Video')}}"
                                                        value="">{{__('common.Select')}} {{__('courses.Video')}}
                                                    </option>
                                                    @if(isset($video_list))
                                                    @foreach ($video_list as $video)
                                                    <option value="{{@$video['uri']}}">{{@$video['name']}}</option>

                                                    @endforeach
                                                    @endif
                                                </select>
                                                @if ($errors->has('vimeo'))
                                                <span class="invalid-feedback invalid-select" role="alert">
                                                    <strong>{{ $errors->first('vimeo') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="row  videofileupload" id=""
                                            style="display: @if((isset($course) && ((@$course->host==" Vimeo") ||
                                            (@$course->host=="Youtube")) ) || !isset($course)) none @endif">

                                            <div class="col-xl-12">
                                                <div class="primary_input">
                                                    <label class="primary_input_label"
                                                        for="">{{__('courses.Video File')}}</label>
                                                    <div class="primary_file_uploader">
                                                        {{-- <input
                                                                 class="primary-input filePlaceholder"
                                                                 type="text"

                                                                 placeholder="{{__('courses.Browse Video file')}}"
                                                        readonly="">
                                                        <button class="" type="button">
                                                            <label class="primary-btn small fix-gr-bg"
                                                                for="document_file_edit">{{__('common.Browse') }}</label>
                                                            <input type="file" class="d-none fileUpload" name="file"
                                                                id="document_file_edit">
                                                        </button>

                                                        @if ($errors->has('file'))
                                                        <span class="invalid-feedback invalid-select" role="alert">
                                                            <strong>{{ $errors->first('file') }}</strong>
                                                        </span>
                                                        @endif--}}
                                                        <input type="file" class="filepond" name="file">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-20">


                                    <div class="col-xl-6">
                                        <div class="primary_input mb-35">
                                            <label class="primary_input_label" for="">{{__('courses.Course Thumbnail')}}
                                                ({{__('common.Max Image Size 1MB')}})
                                                *</label>
                                            <div class="primary_file_uploader">
                                                <input class="primary-input filePlaceholder" type="text" id=""
                                                    placeholder="{{__('courses.Browse Image file')}}" readonly=""
                                                    {{$errors->has('image') ? 'autofocus' : ''}}>
                                                <button class="" type="button">
                                                    <label class="primary-btn small fix-gr-bg"
                                                        for="document_file_1_edit_">{{__('common.Browse')}}</label>
                                                    <input type="file" class="d-none fileUpload" name="image"
                                                        id="document_file_1_edit_">
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    @if(Auth::user()->subscription_api_status==1)
                                    <div class="col-xl-6">
                                        <label class="primary_input_label" for="">{{__('newsletter.Subscription List')}}
                                        </label>
                                        <select class="primary_select" id="subscriptionEdit" name="subscription_list"
                                            {{$errors->has('subscription_list') ? 'autofocus' : ''}}>
                                            <option
                                                data-display="{{__('common.Select')}} {{__('newsletter.Subscription List')}}"
                                                value="">{{__('common.Select')}} {{__('newsletter.Subscription List')}}

                                            </option>
                                            @foreach($sub_lists as $list)
                                            <option value="{{$list['id']}}">
                                                {{$list['name']}}
                                            </option>
                                            @endforeach

                                        </select>
                                    </div>
                                    @endif
                                </div>
                                <div class="row">


                                    <div class="col-xl-12">
                                        <div class="primary_input mb-25">
                                            <label class="primary_input_label"
                                                for="">{{__('courses.Meta keywords')}}</label>
                                            <input class="primary_input_field" name="meta_keywords" id="editMetaKey"
                                                placeholder="-" type="text">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="primary_input mb-25">
                                            <label class="primary_input_label"
                                                for="">{{__('courses.Meta description')}}</label>
                                            <textarea id="editMetaDetails" class="primary_input_field"
                                                name="meta_description" style="height: 200px" rows="3"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 text-center pt_15">
                                    <div class="d-flex justify-content-center">
                                        <button class="primary-btn semi_large2  fix-gr-bg" id="save_button_parent"
                                            type="submit"><i class="ti-check"></i> {{__('common.Update')}}
                                            {{__('courses.Course')}}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>


        </div>

        <div class="modal fade admin-query" id="confirm_cancel_delete_bulk1">
            <div class="modal-dialog modal-dialog-centered modal_650px">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ __('common.Assign Roles') }} </h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"><i
                                class="ti-close "></i></button>
                    </div>

                    <div class="modal-body">

                        <form action="{{ route('staffs.assign_roles') }}" method="POST">
                            @csrf
                            <input type="hidden" id="bulk_cancle_ids" name="ids" value="">
                            <input type="hidden" id="approve" name="approve" value="1">
                            <?php 
                                $roles = \Modules\RolePermission\Entities\Role::whereNotIn('id', [1, 3])->where('tenant_id', session('tenant_type'))->get();
                                ?>
                            <select name="role_id" id="role_id" class="primary_input_field primary-input form-control">
                                <option value="">Select Role</option>
                                @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>

                            <div class="mt-40 d-flex justify-content-between">
                                <button type="button" class="primary-btn tr-bg"
                                    data-bs-dismiss="modal">{{__('common.Cancel')}}</button>

                                <button type="submit" class="primary-btn fix-gr-bg">
                                    <i class="ti-check"></i>
                                    {{__('common.Assign Roles')}}
                                </button>

                            </div>
                        </form>

                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirm_cancel_delete_bulk" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content custom-modal">

                <!-- HEADER -->
                <div class="modal-header border-0">
                    <h5 class="modal-title">Enroll Learners</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>

                <!-- BODY -->
                <div class="modal-body">

                    <!-- RADIO -->
                    <form id="bulkEnrollForm" action="{{ route('bulkEnroll') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 ">
                                <label class="primary_input_label" for="">Enroll Type<strong
                                        class="text-danger">*</strong>
                                </label>
                            </div>
                            <input type="hidden" name="ids" id="ids">
                            <input type="hidden" name="course_id" id="popup_course_id">
                            <div class="col-md-4 col-sm-4 mb-25">
                                <label class="primary_checkbox d-flex mr-12">
                                    <input type="radio" id="type1" name="type" value="1" checked>
                                    <span class="checkmark me-2"></span>{{__('courses.Add Users/Email Ids')}}
                                </label>
                            </div>
                            <div class="col-md-4 col-sm-4 mb-25">
                                <label class="primary_checkbox d-flex mr-12">
                                    <input type="radio" id="type2" name="type" value="2">
                                    <span class="checkmark me-2"></span> {{__('courses.Upload a CSV')}}</label>
                            </div>

                        </div>


                        <!-- TABS -->
                        <div class="tabs">
                            <div class="tab-item active" data-tab="users">Users</div>
                            <div class="tab-item" data-tab="emails">Email IDs</div>
                        </div>

                        <!-- USERS TAB -->
                        <div class="tab-content active" id="users">

                            <div class="section">
                                <h6>Group Learners</h6>
                                <p>Multiple user groups in a set will only add users common in those groups.</p>
                                <select name="enroll_type[]" id="enroll_type" multiple style="width:100%">
                                </select>
                                <input type="hidden" id="searchUsers" placeholder="Search Users"
                                    value="{{ route('searchUsers') }}">
                                <!-- <a href="#" class="link">+ New inclusion set</a> -->
                            </div>

                            <div class="section">
                                <h6>Specific Learners</h6>
                                <p>Select specific users or user groups below.</p>
                                <select name="search_specific_user[]" id="search_specific_user" multiple
                                    style="width:100%">
                                </select>
                                <input type="hidden" id="searchSingleUsers" placeholder="Search Users"
                                    value="{{ route('searchSingleUsers') }}">
                                <a href="#" class="link">+ New exclusion set</a>
                            </div>

                        </div>

                        <!-- EMAIL TAB -->
                        <div class="tab-content" id="emails">
                            <div class="section">
                                <h6>Email IDs</h6>
                                <p>Enter email IDs separated by comma.</p>
                                <select id="email_users" name="email_ids[]" multiple style="width:100%"></select>
                                <input type="hidden" id="searchEmailUsersUrl" value="{{ route('searchEmailUsers') }}">
                            </div>
                        </div>

                        <div class="csv-section" style="display:none;">
                            <div class="section">
                                <h6>Upload CSV</h6>
                                <input type="file" class="form-control" name="csv_file">
                                <p class="text-muted">Upload users using CSV file</p>
                            </div>
                        </div>



                </div>

                <!-- FOOTER -->
                <div class="modal-footer border-0 d-flex justify-content-between">
                    <span class="help">
                        Need help importing CSV? <a href="#">Visit help</a>
                    </span>

                    <div>
                        <button class="btn cancel" data-dismiss="modal">Cancel</button>
                        <button class="btn primary">Proceed</button>
                    </div>
                </div>

                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirm_cancel_delete_bulk2" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content custom-modal">

                <!-- HEADER -->
                <div class="modal-header border-0">
                    <h5 class="modal-title">Quiz Enrolls</h5>
                    <button type="button" class="close text-white" id="close_btn" data-dismiss="modal">&times;</button>
                </div>

                <!-- BODY -->
                <div class="modal-body">

                    <!-- RADIO -->
                    <form id="bulkQuizEnrollForm" action="{{ route('bulkEnroll') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <input type="hidden" name="ids" id="ids">
                            <input type="hidden" name="quiz_course_id" id="quiz_course_id">
                        </div>

                        <!-- EMAIL TAB -->
                        
                        <div class="section">
                            <h6>Select Users</h6>
                            <p>Enter email IDs separated by comma.</p>
                            <select name="search_specific_user[]" id="search_specific_user1" multiple
                                    style="width:100%">
                                </select>
                            <input type="hidden" name="quiz_chk" value="1">
                            <input type="hidden" id="searchSingleUsers" placeholder="Search Users"
                                    value="{{ route('searchSingleUsers') }}">
                        </div>
                        
                </div>

                <!-- FOOTER -->
                <div class="modal-footer border-0 d-flex justify-content-between">
                    <div>
                        <button class="btn cancel" id="quiz_cancel" data-dismiss="modal">Cancel</button>
                        <button class="btn primary">Proceed</button>
                    </div>
                </div>

                </form>
            </div>
        </div>
    </div>
</section>
@include('backend.partials.delete_modal')
@endsection
@push('scripts')

<script src="{{asset('/')}}/Modules/CourseSetting/Resources/assets/js/course.js"></script>

<script>
$(document).ready(function() {
    // $('.dataTables_paginate').eq(0).hide();
});
dataTableOptions.stateSave = false
dataTableOptions.serverSide = true
dataTableOptions.processing = true
dataTableOptions.ajax = '{!! $url !!}';
dataTableOptions.columns = [{
        data: 'DT_RowIndex',
        name: 'id'
    },
    {
        data: 'type',
        name: 'type'
    },
    @if(isModuleActive('Org')) {
        data: 'required_type',
        name: 'required_type'
    },
    @endif {
        data: 'title',
        name: 'title'
    },
    {
        data: 'mode_of_delivery',
        name: 'mode_of_delivery'
    },
    {
        data: 'category',
        name: 'category.name'
    },
    @if(!isModuleActive('Org')) {
        data: 'quiz',
        name: 'quiz.title'
    },
    @endif {
        data: 'user',
        name: 'user.name'
    },

    {
        data: 'lessons',
        name: 'lessons'
    },
    {
        data: 'enrolled_users',
        name: 'enrolled_users'
    },
    @if(showEcommerce()) {
        data: 'price',
        name: 'price'
    },
    @endif {
        data: 'scope',
        name: 'scope'
    },
    {
        data: 'status',
        name: 'search_status',
        orderable: false,
        searchable: false
    },
    {
        data: 'action',
        name: 'action',
        orderable: false
    },

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

        let api = this.api();

        $('#cardViewContainer').html('');

        let data = api.rows({
            page: 'current'
        }).data();

        data.each(function(row) {

            let baseUrl = "{{ url('/') }}";

            let card = `
                <div class="col-lg-4 col-md-6 col-sm-6 mb-4">

                    <div class="white_box h-100">

                        <div class="position-relative">

                            <img
                                class="w-100"
                                src="${row.thumbnail ? baseUrl + '/' + row.thumbnail : '{{ asset('uploads/default.png') }}'}"
                                style="height:180px;object-fit:cover;border-radius:8px;"
                                alt="">

                            <span class="badge bg-success position-absolute"
                                style="top:10px;right:10px;">
                                ${row.status}
                            </span>

                        </div>

                        <div class="p-3">

                            <h5 class="mb-2">
                                ${row.title}
                            </h5>

                            <p class="text-muted mb-3">
                                ${row.user}
                            </p>

                            <div class="d-flex justify-content-between">

                                <span>
                                    <i class="ti-book"></i>
                                    ${row.lessons}
                                </span>

                                <span>
                                    <i class="ti-user"></i>
                                    ${row.enrolled_users}
                                </span>

                            </div>

                            <div class="mt-3">
                                ${row.action}
                            </div>

                        </div>

                    </div>

                </div>
            `;

            $('#cardViewContainer').append(card);

        });

        // Remove old pagination
        $('#cardPaginationContainer').html('<div class="card-pagination-wrapper"></div>');

        // Clone datatable pagination
        let info = $(api.table().container()).find('.dataTables_info');
        let paginate = $(api.table().container()).find('.dataTables_paginate');

        $('#cardPaginationContainer .card-pagination-wrapper')
            .append(info)
            .append(paginate);
    }
});

// default grid view
$('#lms_table').hide();
$('.dataTables_scrollHead').hide();
$('#cardViewContainer').show();

$('.viewToggle').click(function() {

    $('.viewToggle').removeClass('active');
    $(this).addClass('active');

    let view = $(this).data('view');

    if (view === 'grid') {

        $('#lms_table').hide();
        $('.dataTables_scrollHead').hide();
        $('#cardViewContainer').show();

    } else {

        $('#cardViewContainer').hide();
        $('#lms_table').show();
        $('.dataTables_scrollHead').show();

    }

});
</script>

<script>
$(document).ready(function() {
    $(document).on('click', '#selectAll', function() {
        $(".deleteCheckbox").prop("checked", this.checked);

        if ($('.deleteCheckbox:checked').length > 0) {
            $('#bulkEnrollBtn').removeClass('d-none');
        } else {
            $('#bulkEnrollBtn').addClass('d-none');
        }
    });

    $(document).on('click', '.paginate_button', function() {
        $('#bulkEnrollBtn').addClass('d-none');
        $("#selectAll").prop("checked", false);

    });
    
    $(document).on('click', '.deleteCheckbox', function() {
        if ($('.deleteCheckbox:checked').length === $('.deleteCheckbox').length) {
            $('#selectAll').prop('checked', true);
        } else {
            $('#selectAll').prop('checked', false);
        }
        if ($('.deleteCheckbox:checked').length > 0) {
            $('#bulkEnrollBtn').removeClass('d-none');
        } else {
            $('#bulkEnrollBtn').addClass('d-none');
        }
    });
    $(document).on('click', '#bulkEnrollBtn', function() {

        let ids = $(".deleteCheckbox:checked").map(function() {
            return $(this).val();
        }).get();

        if (ids.length === 0) {
            toastr.error('{{__('ticket.please_at_least_one_item ')}}');
            return;
        }

        $('#bulk_cancle_ids').val(ids);
        $('#confirm_cancel_delete_bulk').modal('show', {
            // backdrop: 'static'
        });
        $('#confirm_cancel_delete_bulk2').modal('show', {
            // backdrop: 'static'
        });
    });
});

let seatChecked = false;
// ✅ CSRF (VERY IMPORTANT)
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).on('submit', '#confirm_cancel_delete_bulk form', function(e) {

    if (seatChecked) {
        return true;
    }

    e.preventDefault();

    let form = $(this);
    let formData = new FormData(this);

    $.ajax({
        url: "{{ route('checkCourseSeats', request()->route('tenant_slug')) }}",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,

        success: function(response) {

            console.log(response);

            if (response && response.waitingUsers > 0) {

                toastr.warning(
                    "Only " + response.remainingSeats +
                    " seats available. " +
                    response.waitingUsers +
                    " users will be moved to waiting list.<br><br>" +
                    "<button id='continueEnroll' class='btn btn-sm btn-primary'>Continue</button> " +
                    "<button id='cancelEnroll' class='btn btn-sm btn-danger'>Cancel</button>",
                    "Seat Limit Warning", {
                        timeOut: 0,
                        extendedTimeOut: 0,
                        closeButton: true,
                        allowHtml: true
                    }
                );

            } else if (response && response.waitingUsers === 0) {
                seatChecked = true;
                form.off('submit');
                form.submit();
            } else {
                console.error("Invalid response", response);
            }
        }
    });
});



$(document).on('click', '#continueEnroll', function() {

    let form = $('#confirm_cancel_delete_bulk form');
    let courseId = "{{ $course->id ?? 26 }}"; // adjust if dynamic

    // ✅ Collect all selected users (same logic as backend)
    let users = [];

    // Specific users
    let specific = $('#search_specific_user').val() || [];
    users = users.concat(specific);
    

    // Group users (team_x)
    let groups = $('#enroll_type').val() || [];
    users = users.concat(groups);

    // ⚠️ NOTE: If groups contain "team_227", backend must still expand them
    // So here we only prepare format for direct users

    // ✅ Build ids format
    let ids = [];

    users.forEach(function(u) {
        if (!u.startsWith('team_')) {
            ids.push(u + '_' + courseId);
        }
    });

    $('#ids').val(ids.join(','));

    // ✅ Add waiting flag
    if ($('#waiting_confirmed').length === 0) {
        form.append('<input type="hidden" name="waiting_confirmed" value="1">');
    }

    seatChecked = true;
    toastr.clear();

    form.submit();
});

$(document).on('click', '#cancelEnroll', function() {

    toastr.clear();
    toastr.error("Enrollment cancelled");
});

$(document).on('click', '#quiz_cancel', function() {
    toastr.clear();
    toastr.error("Quiz Enrollment cancelled");
});

$(document).on('click', '#close_btn', function() {
    toastr.clear();
    toastr.error("Quiz Enrollment closed");
});




$('#confirm_cancel_delete_bulk').on('shown.bs.modal', function() {

    let select = $('#enroll_type');
    let singleuser = $('#search_specific_user');
    let course_id = $('#popup_course_id');

    if (select.hasClass("select2-hidden-accessible")) {
        select.select2('destroy');
    }

    if (singleuser.hasClass("select2-hidden-accessible")) {
        singleuser.select2('destroy');
    }

    select.select2({
        width: '100%',
        dropdownParent: $('#confirm_cancel_delete_bulk'),
        placeholder: "Search users",
        minimumInputLength: 1,

        sorter: function(data) {
            return data; // keep backend order
        },

        ajax: {
            url: $('#searchUsers').val(),
            dataType: 'json',
            delay: 250,
            cache: true,

            data: function(params) {
                return {
                    q: params.term
                };
            },

            processResults: function(data) {

                let results = [];

                data.results.forEach(function(group) {

                    group.children.forEach(function(item) {
                        results.push({
                            id: item.id,
                            text: item.text,
                            group: group.text
                        });
                    });

                });

                return {
                    results: results
                };
            }
            // processResults: function (data) {
            //     return { results: data.results };
            // }
        }
    });

    singleuser.select2({
        width: '100%',
        dropdownParent: $('#confirm_cancel_delete_bulk'),
        placeholder: "Search users",
        minimumInputLength: 1,

        sorter: function(data) {
            return data; // keep backend order
        },

        ajax: {
            url: $('#searchSingleUsers').val(),
            dataType: 'json',
            delay: 250,
            cache: true,

            data: function(params) {
                return {
                    q: params.term
                };
            },

            processResults: function(data) {
                return {
                    results: data.results.map(function(item) {
                        return {
                            id: item.id,
                            text: item.name + ' (' + item.employee_id + ')'
                        };
                    })
                };
            }

        }
    });

});


$('#confirm_cancel_delete_bulk2').on('shown.bs.modal', function() {

    let select = $('#enroll_type');
    let singleuser = $('#search_specific_user1');
    let course_id = $('#popup_course_id');

    if (select.hasClass("select2-hidden-accessible")) {
        select.select2('destroy');
    }

    if (singleuser.hasClass("select2-hidden-accessible")) {
        singleuser.select2('destroy');
    }

    select.select2({
        width: '100%',
        dropdownParent: $('#confirm_cancel_delete_bulk2'),
        placeholder: "Search users",
        minimumInputLength: 1,

        sorter: function(data) {
            return data; // keep backend order
        },

        ajax: {
            url: $('#searchUsers').val(),
            dataType: 'json',
            delay: 250,
            cache: true,

            data: function(params) {
                return {
                    q: params.term
                };
            },

            processResults: function(data) {

                let results = [];

                data.results.forEach(function(group) {

                    group.children.forEach(function(item) {
                        results.push({
                            id: item.id,
                            text: item.text,
                            group: group.text
                        });
                    });

                });

                return {
                    results: results
                };
            }
            // processResults: function (data) {
            //     return { results: data.results };
            // }
        }
    });

    singleuser.select2({
        width: '100%',
        dropdownParent: $('#confirm_cancel_delete_bulk2'),
        placeholder: "Search users",
        minimumInputLength: 1,

        sorter: function(data) {
            return data; // keep backend order
        },

        ajax: {
            url: $('#searchSingleUsers').val(),
            dataType: 'json',
            delay: 250,
            cache: true,

            data: function(params) {
                return {
                    q: params.term
                };
            },

            processResults: function(data) {
                return {
                    results: data.results.map(function(item) {
                        return {
                            id: item.id,
                            text: item.name + ' (' + item.employee_id + ')'
                        };
                    })
                };
            }

        }
    });

});


$(document).ready(function() {

    // ✅ TAB CLICK
    $(document).on('click', '.tab-item', function() {

        let tab = $(this).data('tab');

        // switch tab header
        $('.tab-item').removeClass('active');
        $(this).addClass('active');

        // switch content
        $('.tab-content').removeClass('active');
        $('#' + tab).addClass('active');
    });

    // ✅ RADIO CHANGE
    $(document).on('change', 'input[name="type"]', function() {

        let type = $(this).val();

        if (type == 1) {
            // SHOW USERS/EMAIL
            $('.tabs').show();
            $('.tab-item').first().click(); // default to Users
            $('.csv-section').hide();

        } else if (type == 2) {
            // SHOW CSV
            $('.tabs').hide();
            $('.tab-content').removeClass('active');
            $('.csv-section').show();
        }

    });

    // ✅ INITIAL LOAD FIX
    $('input[name="type"]:checked').trigger('change');

    $('.close').click(function() {
        $('#confirm_cancel_delete_bulk2').hide();
    });

});

$('#email_users').select2({
    placeholder: 'Type or search email IDs',
    multiple: true,
    tags: true, // 🔥 allows manual typing
    tokenSeparators: [',', ' '], // press comma or space
    ajax: {
        url: $('#searchEmailUsersUrl').val(),
        dataType: 'json',
        delay: 250,
        data: function(params) {
            return {
                q: params.term
            };
        },
        processResults: function(data) {
            return {
                results: data.results
            };
        },
        cache: true
    }
});

$('#email_users1').select2({
    placeholder: 'Type or search email IDs',
    multiple: true,
    tags: true, // 🔥 allows manual typing
    tokenSeparators: [',', ' '], // press comma or space
    ajax: {
        url: $('#searchEmailUsersUrl').val(),
        dataType: 'json',
        delay: 250,
        data: function(params) {
            return {
                q: params.term
            };
        },
        processResults: function(data) {
            return {
                results: data.results
            };
        },
        cache: true
    }
});
</script>
@endpush