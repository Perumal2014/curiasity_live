@extends('backend.master')
@push('styles')
    <link rel="stylesheet" href="{{asset('public/backend/css/student_list.css')}}"/>
    <style>
    /* Progress */
    .progress-bar {
        background-color: #9734f2;
    }

    /* Select2 base */
    .select2-container {
        width: 100% !important;
    }

    .select2-dropdown {
        z-index: 9999 !important;
    }

    /* Chips spacing */
    .select2-selection__choice {
        margin: 3px;
    }

    /* Input width */
    .select2-search__field {
        width: 100% !important;
    }

    /* Modal UI */
    .modal-content {
        border-radius: 12px;
    }

    .modal-footer {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    /* ===================================== */
    /* 🔥 MAIN FIX (PREVENT OVERLAPPING) */
    /* ===================================== */

    .select2-results__options,
    .select2-results__option,
    .select2-results__group {
        display: block !important;
        position: relative !important;
        height: auto !important;
        line-height: 1.5 !important;
        float: none !important;
        white-space: normal !important;
    }

    /* Group headers */
    .select2-results__group {
        padding: 10px 12px !important;
        font-weight: 600;
        font-size: 13px;
        color: #333;
        background: #f5f5f5;
        border-top: 1px solid #eee;
    }

    /* Options */
    .select2-results__option {
        padding: 6px 12px !important;
        width: 100% !important;
    }

    /* Scroll */
    .select2-results__options {
        max-height: 300px;
        overflow-y: auto;
    }

    /* Highlight */
    .select2-results__option--highlighted {
        background-color: #e9ecef !important;
        color: #000 !important;
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
                <!-- <div class="col-12">
                    <div class="box_header common_table_header">
                        <div class="main-title d-md-flex">
                            <h3 class="mb-0 mr-30 mb_xs_15px mb_sm_20px" id="page_title">{{__('student.Announcements')}}</h3>
                        </div>
                    </div>
                </div> -->
                <div class="col-12">
                    <div class="box_header common_table_header">
                        
                        <div class="d-flex justify-content-between align-items-center flex-wrap">

                            <!-- LEFT SIDE -->
                            <!-- <div>
                                <h3 class="mb-0" id="page_title">
                                    Announcement List
                                </h3>
                            </div> -->

                            <!-- RIGHT SIDE -->
                            <div class="d-flex align-items-center gap-2 mt-2 mt-md-0">

                                    <button class="can_delete primary-btn small fix-gr-bg text-nowrap d-none "
                                                type="button" id="bulkEnrollBtn">
                                            {{__('common.Bulk')}}     {{__('common.Enroll')}}
                                    </button>


                                @if (permissionCheck('learningpath.store'))
                                   <button class="primary-btn small fix-gr-bg" id="openAnnouncementModal">
                                        <i class="ti-plus"></i> {{ __('common.New Announcement') }}
                                    </button>
                                @endif

                            </div>

                        </div>

                    </div>
                </div>
                <div class="col-lg-12 mt-40">
                    <div class="QA_section QA_section_heading_custom check_box_table">
                        <div class="QA_table ">
                            <!-- table-responsive -->
                            <div class="">
                                <table id="lms_table" class="table Crm_table_active3">
                                    <thead>
                                    <tr>
                                        <th class="can_delete" scope="col">

                                            <label class="primary_checkbox d-flex mr-12 ">
                                                <input name="" id="selectAll"
                                                        type="checkbox">
                                                <span class="checkmark"></span>
                                            </label>

                                        </th>
                                        <th scope="col">{{__('common.SL')}}</th>
                                        <th scope="col">{{__('common.Title')}}</th>
                                        <th scope="col">{{__('common.Description')}}</th>
                                        <th scope="col">{{__('common.Link')}}</th>
                                        <th scope="col">{{__('common.Start Date')}}</th>
                                        <th scope="col">{{__('common.End Date')}}</th>
                                        <th scope="col">{{__('common.Created By')}}</th>
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

                <div class="modal fade" id="announcementModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">

                            <!-- Header -->
                            <div class="modal-header">
                                <h5 class="modal-title">New Announcement</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <!-- Body -->
                            <div class="modal-body">

                                <div class="mb-3">
                                    <label>Title</label>
                                    <input type="text" id="ann_title" class="form-control">
                                </div>

                                <div class="mb-3">
                                    <label>Description</label>
                                    <textarea id="ann_description" class="form-control"></textarea>
                                </div>

                                <div class="mb-3">
                                    <label>Link</label>
                                    <input type="text" id="ann_link" class="form-control">
                                </div>

                                <div class="mb-3">
                                    <label>Start Date</label>
                                    <input type="date" id="ann_start_date" class="form-control">
                                </div>

                                <div class="mb-3">
                                    <label>End Date</label>
                                    <input type="date" id="ann_end_date" class="form-control">
                                </div>

                            </div>

                            <!-- Footer -->
                            <div class="modal-footer">
                                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button class="btn btn-primary" id="saveAnnouncementBtn">Save</button>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="modal fade" id="confirm_cancel_delete_bulk" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">

                            <!-- HEADER -->
                            <div class="modal-header border-0">
                                <h5 class="modal-title">Enroll Learners</h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>

                            <!-- BODY -->
                            <div class="modal-body">

                                <form id="bulkEnrollForm" method="POST">
                                    @csrf
                                     <input type="hidden" id="bulk_cancle_ids" name="ids" value="">
                                    <div class="section">
                                        <h6>Select Group</h6>
                                        <p>Multiple user groups in a set will only add users common in those groups.</p>

                                        <select id="enroll_type" multiple style="width:100%"></select>
                                    </div>

                                </form>

                            </div>
                            <input type="hidden" id="searchUsers" value="{{ route('searchUsers') }}">
                            <!-- ✅ FOOTER MUST BE INSIDE modal-content -->
                            <div class="modal-footer border-0">
                                <button class="btn cancel" data-dismiss="modal">Cancel</button>
                                <button class="btn proceed-btn">Proceed</button>
                                 <span class="btn-loader d-none">
                                    <i class="fa fa-spinner fa-spin"></i> Saving...
                                </span>
                            </div>

                        </div>
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
        $url = route('getAnnouncementData');
    @endphp
   
    <script>

        dataTableOptions.serverSide = true
        dataTableOptions.processing = true
        dataTableOptions.ajax = '{!! $url !!}';
        dataTableOptions.columns = [
            {data: 'checkbox', name: 'checkbox', orderable: false, searchable: false},
            {data: 'DT_RowIndex', name: 'id', orderable: true},
            {data: 'title', name: 'title', orderable: false},
            {data: 'description', name: 'description'},
            {data: 'announce_link', name: 'announce_link'},
            {data: 'start_date', name: 'start_date', orderable: false},
            {data: 'end_date', name: 'end_date', orderable: false},
            {data: 'created_by', name: 'created_by', orderable: false},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ];
        dataTableOptions = updateColumnExportOption(dataTableOptions, [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]);

        let table = $('#lms_table').DataTable(dataTableOptions);


        $(document).on('click', '#openAnnouncementModal', function () {
            $('#announcementModal').modal('show');
            
        });

        $('#saveAnnouncementBtn').click(function () {

            let title = $('#ann_title').val();
            let description = $('#ann_description').val();
            let link = $('#ann_link').val();
            let start_date = $('#ann_start_date').val();
            let end_date = $('#ann_end_date').val();

            if (title == '') {
                alert('Title is required');
                return;
            }

            $.ajax({
                url: "{{ route('storeAnnouncement') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    title,
                    description,
                    link,
                    start_date,
                    end_date
                },
                success: function () {

                    $('#announcementModal').modal('hide');

                    // optional: reload page or table
                    location.reload();

                }
            });

        });

        $(document).ready(function() {
              let today = new Date().toISOString().split('T')[0];

                $('#ann_start_date').attr('min', today);
                $('#ann_end_date').attr('min', today);
        });

        $(document).on('click', '.edit-announcement', function () {

            $('#ann_id').val($(this).data('id'));
            $('#ann_title').val($(this).data('title'));
            $('#ann_description').val($(this).data('description'));
            $('#ann_link').val($(this).data('link'));
            $('#ann_start_date').val($(this).data('start'));
            $('#ann_end_date').val($(this).data('end'));

            $('#announcementModal').modal('show');
        });

         $(document).ready(function () {
            $(document).on('click', '#selectAll', function () {
                $(".deleteCheckbox").prop("checked", this.checked);

                if ($('.deleteCheckbox:checked').length > 0) {
                    $('#bulkEnrollBtn').removeClass('d-none');
                } else {
                    $('#bulkEnrollBtn').addClass('d-none');
                }
            });

            $(document).on('click', '.paginate_button', function () {
                $('#bulkEnrollBtn').addClass('d-none');
                $("#selectAll").prop("checked", false);

            });
            $(document).on('click', '.deleteCheckbox', function () {
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
            $(document).on('click', '#bulkEnrollBtn', function () {
                
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

       

        $(document).on('click', '#cancelEnroll', function(){

            toastr.clear();
            toastr.error("Enrollment cancelled");
        });

       

        // $('#confirm_cancel_delete_bulk').on('shown.bs.modal', function () {

        //     let select = $('#enroll_type');
            
        //     if (select.hasClass("select2-hidden-accessible")) {
        //         select.select2('destroy');
        //     }

            
        //     select.select2({
        //         width: '100%',
        //         dropdownParent: $('#confirm_cancel_delete_bulk'),
        //         placeholder: "Search users",
        //         minimumInputLength: 1,

        //         sorter: function(data) {
        //             return data; // keep backend order
        //         },

        //         ajax: {
        //             url: $('#searchUsers').val(),
        //             dataType: 'json',
        //             delay: 250,
        //             cache: true,

        //             data: function (params) {
        //                 return { q: params.term };
        //             },
        //             processResults: function (data) {
        //                 return { results: data.results };
        //             }
        //         }
        //     });

           
        // });

        $('#confirm_cancel_delete_bulk').on('shown.bs.modal', function () {

            let select = $('#enroll_type');

            // Destroy previous instance
            if ($.fn.select2 && select.data('select2')) {
                select.select2('destroy');
            }

            select.select2({
                width: '100%',
                dropdownParent: $('#confirm_cancel_delete_bulk'),
                placeholder: "Search users",
                minimumInputLength: 1,

                sorter: function (data) {
                    return data;
                },

                // ✅ FIX GROUP RENDERING
                templateResult: function (data) {

                    if (data.loading) return data.text;

                    // Group Header
                    if (data.children) {
                        return $('<div style="font-weight:600; padding:8px 12px; background:#f5f5f5;">' + data.text + '</div>');
                    }

                    // Normal item
                    return $('<div style="padding:6px 12px;">' + data.text + '</div>');
                },

                templateSelection: function (data) {
                    return data.text || data.id;
                },

                escapeMarkup: function (markup) {
                    return markup;
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
                        return { results: data.results };
                    }
                }
            });

        });


       
        $(document).on('click', '.cancel', function () {

            // ✅ Clear Select2 (if using select)
            $('#enroll_type').val(null).trigger('change');

            // ✅ Clear normal inputs (if any)
            $('#bulkEnrollForm')[0].reset();

            // ✅ Hide modal
            $('#confirm_cancel_delete_bulk').modal('hide');

        });

        $('.proceed-btn').on('click', function () {

            let selectedGroups = $('#enroll_type').val(); // ['bu_2','dept_3']
            let announcementId = $('#bulk_cancle_ids').val(); // or pass manually

            if (!selectedGroups || selectedGroups.length === 0) {
                alert('Please select at least one group');
                return;
            }

            // disable + show loader
            let btn = $(this);

            btn.prop('disabled', true);
            btn.find('.btn-text').addClass('d-none');
            btn.find('.btn-loader').removeClass('d-none');

            $.ajax({
                    url: "{{ route('assignAnnouncementUsers') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        announcement_id: announcementId,
                        groups: selectedGroups
                    },
                    success: function (res) {
                        alert('Announcement sent successfully');
                        $('#confirm_cancel_delete_bulk').modal('hide');
                    }
                });
        });

    </script>

    <script src="{{asset('public/backend/js/student_list.js')}}"></script>

@endpush
