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
            background: #f7f7f7;
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

        /* Child options */
        .select2-results__options--nested .select2-results__option {
            padding: 6px 10px 6px 25px !important;
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
                                                type="button" id="bulkDeleteBtn">
                                            {{__('common.Bulk')}}     {{__('common.Enroll')}}
                                        </button>

                                    </div>

                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="QA_section QA_section_heading_custom check_box_table">
                                    <div class="QA_table ">
                                        <form id="bulkDeleteForm">
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
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="modal fade admin-query" id="confirm_cancel_delete_bulk">
            <div class="modal-dialog modal-dialog-centered modal_650px">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{__('common.Bulk')}} {{ __('common.Approve Status') }} </h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"><i
                                class="ti-close "></i></button>
                    </div>

                    <div class="modal-body">
                        <p class="text-center">
                            {{__('common.Course Approve Confirmation')}}.
                        </p>
                        <form action="{{ route('bulkEnroll', request()->route('tenant_slug')) }}" method="POST">
                            @csrf
                            <input type="hidden" id="bulk_cancle_ids" name="ids" value="">
                            <input type="hidden" name="waiting_confirmed" id="waiting_confirmed" value="0">
                            <input type="hidden" id="approve" name="approve" value="1">
                            <input type="text" id="approve" name="approve" value="{{ $courseid ?? 0 }}">
                            <div class="mt-40 d-flex justify-content-between">
                                <select name="enroll_type" id="enroll_type" style="width:100%">
                                </select>
                                <input type="hidden" id="searchUsers" value="{{ route('searchUsers') }}">
                            </div>
                            <div class="mt-40 d-flex justify-content-between">
                                <button type="button" class="primary-btn tr-bg"
                                        data-bs-dismiss="modal">{{__('common.Cancel')}}</button>

                                <button type="submit" class="primary-btn fix-gr-bg">
                                    <i class="ti-check"></i>
                                    {{__('common.Course Enroll')}}
                                </button>

                            </div>
                        </form>

                    </div>

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
     <script>
        $(document).ready(function () {
            $(document).on('click', '#selectAll', function () {
                $(".deleteCheckbox").prop("checked", this.checked);

                if ($('.deleteCheckbox:checked').length > 0) {
                    $('#bulkDeleteBtn').removeClass('d-none');
                } else {
                    $('#bulkDeleteBtn').addClass('d-none');
                }
            });

            $(document).on('click', '.paginate_button', function () {
                $('#bulkDeleteBtn').addClass('d-none');
                $("#selectAll").prop("checked", false);

            });
            $(document).on('click', '.deleteCheckbox', function () {
                if ($('.deleteCheckbox:checked').length === $('.deleteCheckbox').length) {
                    $('#selectAll').prop('checked', true);
                } else {
                    $('#selectAll').prop('checked', false);
                }
                if ($('.deleteCheckbox:checked').length > 0) {
                    $('#bulkDeleteBtn').removeClass('d-none');
                } else {
                    $('#bulkDeleteBtn').addClass('d-none');
                }
            });
            $(document).on('click', '#bulkDeleteBtn', function () {
                let ids = $(".deleteCheckbox:checked").map(function () {
                    return $(this).val();
                }).get();

                if (ids.length === 0) {
                    toastr.error('{{__('ticket.please_at_least_one_item')}}');
                    return;
                }

                $('#bulk_cancle_ids').val(ids);
                $('#confirm_cancel_delete_bulk').modal('show', {backdrop: 'static'});
            });
        });
        
        let seatChecked = false;

        $(document).on('submit', '#confirm_cancel_delete_bulk form', function (e) {

            if(seatChecked){
                return true;
            }

            e.preventDefault();

            let form = $(this);

            $.ajax({
                url: "{{ route('checkCourseSeats', request()->route('tenant_slug')) }}",
                type: "POST",
                data: form.serialize(),

                success: function(response) {

                    if(response.waitingUsers > 0){

                        toastr.warning(
                            "Only " + response.remainingSeats +
                            " seats available. " +
                            response.waitingUsers +
                            " users will be moved to waiting list.<br><br>" +
                            "<button id='continueEnroll' class='btn btn-sm btn-primary'>Continue</button> " +
                            "<button id='cancelEnroll' class='btn btn-sm btn-danger'>Cancel</button>",
                            "Seat Limit Warning",
                            {
                                timeOut: 0,
                                extendedTimeOut: 0,
                                closeButton: true,
                                allowHtml: true
                            }
                        );

                    }else{
                        seatChecked = true;
                        form.submit();
                    }
                }
            });

        });


        $(document).on('click', '#continueEnroll', function(){

            seatChecked = true;
            toastr.clear();

            $('#confirm_cancel_delete_bulk form').submit();

        });

        $(document).on('click', '#cancelEnroll', function(){

            toastr.clear();
            toastr.error("Enrollment cancelled");

        });
       
        $('#confirm_cancel_delete_bulk').on('shown.bs.modal', function () {

            let select = $('#enroll_type');

            if (select.hasClass("select2-hidden-accessible")) {
                select.select2('destroy');
            }

            select.select2({
                width: '100%',
                dropdownParent: $('#confirm_cancel_delete_bulk'),
                placeholder: "Search user",
                minimumInputLength: 1,

                sorter: function(data) {
                    return data; // keep backend order
                },

                ajax: {
                    url: $('#searchUsers').val(),
                    dataType: 'json',
                    delay: 250,
                    cache: true,

                    data: function (params) {
                        return { q: params.term };
                    },

                    processResults: function (data) {

                        let results = [];

                        data.results.forEach(function(group){

                            group.children.forEach(function(item){
                                results.push({
                                    id: item.id,
                                    text: item.text,
                                    group: group.text
                                });
                            });

                        });

                        return { results: results };
                    }
                    // processResults: function (data) {
                    //     return { results: data.results };
                    // }
                }
            });

        });
       
    </script>
@endpush
