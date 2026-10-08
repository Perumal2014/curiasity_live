@extends('backend.master')
@push('styles')
    <link rel="stylesheet" href="{{asset('public/backend/css/student_list.css')}}"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
        input, textarea {
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
                <div class="col-lg-12">
                    <div class="white-box">
                        <div class="row">
                            <div class="col-12">
                                <div class="box_header common_table_header">
                                    <div class="main-title d-flex justify-content-between">
                                        <h3 class="mb-15 mr-30 mb_xs_15px mb_sm_20px"
                                            id="page_title">Topic - {{$course->title}}</h3>
                                        <button class="can_delete primary-btn small fix-gr-bg text-nowrap d-none "
                                                type="button" id="bulkEnrollBtn">
                                            {{__('common.Bulk')}}     {{__('common.Enroll')}}
                                        </button>

                                    </div>

                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="QA_section QA_section_heading_custom check_box_table">
                                    <div class="QA_table ">
                                        
                                            @csrf

                                            <div class="">
                                                <table id="lms_table_enroll" class="table Crm_table_active3">
                                                    <thead>
                                                    <tr>
                                                        <th class="can_delete" scope="col">

                                                            <label class="primary_checkbox d-flex mr-12 ">
                                                                <input name="" id="selectAll"
                                                                       type="checkbox">
                                                                <span class="checkmark"></span>
                                                            </label>

                                                        </th>
                                                        <th scope="col">{{__('common.SL')}} </th>
                                                        <th scope="col">{{__('common.Image')}} </th>
                                                        <th scope="col">{{__('common.Name')}} </th>
                                                        <th scope="col">{{__('common.Email Address')}} </th>
                                                        <th scope="col">{{__('courses.Courses')}} {{__('courses.Manager Email')}}</th>
                                                       
                                                        <th scope="col">{{__('common.Action')}}</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                </table>

                                            </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- <div class="modal fade admin-query" id="confirm_cancel_delete_bulk">
            <div class="modal-dialog modal-dialog-centered modal_650px">
                <div class="modal-content">
                    <h2>Enroll Learners</h2>

                     
                        <div class="form-group row">
                            <label>Select Instance:</label>
                            <select>
                                <option>Default Instance</option>
                            </select>
                        </div>

                        
                        <div class="radio-group">
                            <label><input type="radio" name="type" checked> Add Users/Email ID</label>
                            <label><input type="radio" name="type"> Upload a CSV</label>
                        </div>

                       
                        <div class="tabs">
                            <span class="active">Users</span>
                            <span>Email IDs</span>
                        </div>

                        
                        <div class="section">
                            <h4>Include Learners</h4>
                            <p>Multiple user groups in a set will only add users common in those groups.</p>

                            <input type="text" placeholder="Search">

                            <a href="#" class="link">+ New inclusion set</a>
                        </div>

                        
                        <div class="section">
                            <h4>Exclude Learners</h4>
                            <p>Select specific users or user groups below, to be excluded from the above sets.</p>

                            <input type="text" placeholder="Search">

                            <a href="#" class="link">+ New exclusion set</a>
                        </div>

                        
                        <div class="footer">
                            <span class="help">Need help importing CSV? <a href="#">Visit help</a></span>

                            <div>
                                <button class="btn cancel">Cancel</button>
                                <button class="btn primary">Proceed</button>
                            </div>
                        </div>   

                </div>
            </div>
        </div> -->


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
                        <form id="bulkEnrollForm" action="{{ route('bulkEnroll') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-12 ">
                                    <label class="primary_input_label"
                                            for="">Enroll Type<strong class="text-danger">*</strong>
                                    </label>
                                </div>
                                <input type="hidden" name="ids" id="ids">
                                <div class="col-md-4 col-sm-4 mb-25">
                                    <label class="primary_checkbox d-flex mr-12">
                                        <input type="radio" id="type1"
                                                name="type"
                                                value="1"
                                                checked>
                                        <span class="checkmark me-2"></span>{{__('courses.Add Users/Email Ids')}}
                                    </label>
                                </div>
                                <div class="col-md-4 col-sm-4 mb-25">
                                    <label class="primary_checkbox d-flex mr-12">
                                        <input type="radio" id="type2"
                                                name="type"
                                            value="2" >
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
                                    <input type="hidden" id="searchUsers" placeholder="Search Users" value="{{ route('searchUsers') }}">
                                    <!-- <a href="#" class="link">+ New inclusion set</a> -->
                                </div>

                                <div class="section">
                                    <h6>Specific Learners</h6>
                                    <p>Select specific users or user groups below.</p>
                                    <select name="search_specific_user[]" id="search_specific_user" multiple style="width:100%">
                                    </select>
                                    <input type="hidden" id="searchSingleUsers" placeholder="Search Users" value="{{ route('searchSingleUsers') }}">
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
                        <input type="hidden" name="course_id" value="{{ $course->id }}">
                        </form>
                </div>
            </div>
        </div>
    </section>

@endsection
@push('scripts')
    @if ($errors->any())
        <script>
           
            @if(Session::has('type'))
            @if(Session::get('type')=="store")
            $('#add_student').modal('show');
            @else
            $('#editStudent').modal('show');
            @endif
            @endif
        </script>
    @endif


    @php
        $url = route('course.getAllData', $course->id);
    @endphp

    <script>
        
        dataTableOptions.serverSide = true
        dataTableOptions.processing = true
        dataTableOptions.ajax = '{!! $url !!}';
        // console.log(dataTableOptions.ajax);
        dataTableOptions.columns = [
            {data: 'checkbox', name: 'checkbox', orderable: false, searchable: false},
            {data: 'DT_RowIndex', name: 'id', orderable: true},
            {data: 'image', name: 'image', orderable: false},
            {data: 'name', name: 'name'},
            {data: 'email', name: 'email'},
            {data: 'manager_email', name: 'manager_email'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
           
        ];
        dataTableOptions = updateColumnExportOption(dataTableOptions, [0, 1,2, 3, 4, 5, 6]);

        let table = $('#lms_table_enroll').DataTable(dataTableOptions);


    </script>

    <!-- <script src="{{asset('public/backend/js/student_list.js')}}"></script> -->
    
@endpush
