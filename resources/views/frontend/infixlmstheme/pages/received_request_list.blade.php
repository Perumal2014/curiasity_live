@extends(theme('layouts.dashboard_master'))

@section('title')
    {{ Settings('site_title') ? Settings('site_title') : 'Infix LMS' }} |
    {{ __('Sent Requests') }}
@endsection

@section('css')

<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet"/>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>

<style>

.request-page{
    padding:30px;
    background:#f8fafc;
    min-height:100vh;
}

.page-header{
    margin-bottom:22px;
}

.page-title{
    font-size:30px;
    font-weight:800;
    color:#111827;
}

.page-subtitle{
    color:#6b7280;
    font-size:14px;
}

.action-bar{
    display:flex;
    align-items:center;
    justify-content:space-between;
    flex-wrap:wrap;
    gap:20px;
    margin-bottom:24px;
}

.filter-toggle-btn{
    height:52px;
    padding:0 24px;
    border:none;
    border-radius:16px;
    background:#111433;
    color:#fff;
    font-size:16px;
    font-weight:700;
    display:flex;
    align-items:center;
    gap:10px;
    cursor:pointer;
}

/* =========================================
   STATUS TOGGLE
========================================= */

.status-toggle{

    display:flex;

    align-items:center;

    justify-content:flex-end;

    gap:12px;

    flex-wrap:wrap;
}

.status-btn{

    min-width:130px;

    height:52px;

    padding:0 24px;

    border-radius:14px;

    text-decoration:none;

    background:#eef2ff;

    color:#4f46e5;

    font-size:15px;

    font-weight:700;

    display:flex;

    align-items:center;

    justify-content:center;

    transition:0.3s;
}

.status-btn.active{

    background:#7c3aed;

    color:#fff;

    box-shadow:0 8px 18px rgba(124,58,237,0.22);
}

.status-btn:hover{

    background:#4f46e5;

    color:#fff;
}

.filter-card{
    background:#fff;
    border:1px solid #e7ecf3;
    border-radius:28px;
    padding:32px;
    margin-bottom:24px;
}

.filter-label{
    font-size:16px;
    font-weight:700;
    color:#0f172a;
    margin-bottom:12px;
    display:block;
}

.keyword-wrap{
    position:relative;
}

.keyword-wrap i{
    position:absolute;
    top:50%;
    left:18px;
    transform:translateY(-50%);
    color:#94a3b8;
}

.form-control{
    width:100%;
    height:60px;
    border:1px solid #e2e8f0;
    border-radius:18px;
    background:#fff;
    padding:0 18px 0 50px;
}

.select2-container{
    width:100% !important;
}

.select2-container--default .select2-selection--multiple{
    min-height:60px;
    border:1px solid #e2e8f0 !important;
    border-radius:18px !important;
    padding:8px 12px !important;
}

.experience-range-card{
    border:1px solid #e2e8f0;
    border-radius:22px;
    padding:20px;
}

.experience-top{
    display:flex;
    justify-content:space-between;
    margin-bottom:15px;
}

.experience-badge{
    background:#eef2ff;
    color:#3563ff;
    padding:10px 18px;
    border-radius:999px;
    font-size:14px;
    font-weight:700;
}

.experience-range-slider{
    width:100%;
}

.filter-btn-wrap{
    display:flex;
    gap:16px;
    margin-top:34px;
}

.apply-filter-btn{
    height:58px;
    padding:0 34px;
    border:none;
    border-radius:18px;
    background:#020826;
    color:#fff;
    font-size:18px;
    font-weight:700;
}

.cancel-filter-btn{
    height:58px;
    padding:0 34px;
    border:1px solid #dbe2ea;
    border-radius:18px;
    background:#fff;
}

.table-card{
    background:#fff;
    border-radius:24px;
    overflow:hidden;
    border:1px solid #e9edf5;
}

.custom-table{
    width:100%;
}

.custom-table thead{
    background:#f9fafb;
}

.custom-table thead th{
    padding:18px 16px;
    font-size:13px;
    font-weight:800;
}

.custom-table tbody td{
    padding:18px 16px;
    border-bottom:1px solid #f1f5f9;
}

.custom-checkbox{
    width:15px;
    height:15px;
}

#pageLoader{
    position:fixed;
    inset:0;
    background:rgba(255,255,255,0.8);
    display:none;
    align-items:center;
    justify-content:center;
    z-index:9999;
}

.loader-content{
    text-align:center;
}

.loader-content i{
    font-size:50px;
}

/* =========================================
   DATATABLE CUSTOM
========================================= */

.dataTables_wrapper{
    padding:0;
}

.dataTables_length,
.dataTables_filter{
    padding:20px;
}

.dataTables_filter input{
    height:42px !important;
    border-radius:12px !important;
    border:1px solid #dbe2ea !important;
    padding:0 14px !important;
    outline:none !important;
}

.dataTables_length select{
    height:42px !important;
    border-radius:12px !important;
    border:1px solid #dbe2ea !important;
    padding:0 10px !important;
}

.dataTables_info{
    padding:20px !important;
    color:#64748b !important;
}

.dataTables_paginate{
    padding:20px !important;
}

.dataTables_paginate .paginate_button{
    min-width:42px !important;
    height:42px !important;
    border-radius:12px !important;
    border:none !important;
    background:#eef2ff !important;
    color:#4f46e5 !important;
    font-weight:700 !important;
    margin:0 4px !important;
}

.dataTables_paginate .paginate_button.current{
    background:#7c3aed !important;
    color:#fff !important;
}

.dataTables_paginate .paginate_button:hover{
    background:#4f46e5 !important;
    color:#fff !important;
}

table.dataTable{
    border-collapse:separate !important;
    border-spacing:0 !important;
}

table.dataTable thead th{
    border-bottom:none !important;
}

table.dataTable.no-footer{
    border-bottom:none !important;
}

.dataTables_scrollBody{
    border-bottom:none !important;
}
/* =========================================
   DATATABLE TOP SECTION
========================================= */

.dataTables_wrapper .dataTables_filter{
    float:right !important;
    text-align:right !important;
}

.dataTables_wrapper .dataTables_length{
    float:left !important;
}

.dataTables_wrapper .dataTables_filter label{
    display:flex;
    align-items:center;
    gap:10px;
}

.dataTables_wrapper .dataTables_filter input{
    margin-left:0 !important;
}

.dataTables_paginate .paginate_button{

    width:40px !important;
    height:40px !important;

    display:inline-flex !important;
    align-items:center;
    justify-content:center;

    border-radius:12px !important;

    background:#eef2ff !important;

    color:#4f46e5 !important;

    font-size:18px !important;

    border:none !important;
}

.dataTables_paginate .paginate_button.current{

    background:#7c3aed !important;

    color:#fff !important;
}

.dataTables_paginate .paginate_button:hover{

    background:#4f46e5 !important;

    color:#fff !important;
}

/* =========================================
   COMMON TABLE FONT STYLE
========================================= */

.custom-table{

    font-family: 'Inter', sans-serif;
}

.custom-table thead th{

    font-size:13px;

    font-weight:700;

    color:#475569;

    letter-spacing:0.4px;
}

.custom-table tbody td{

    font-size:14px;

    font-weight:500;

    color:#0f172a;

    line-height:22px;
}

</style>

@endsection

@section('mainContent')

@php

$statusMap = [
    'pending'  => 2,
    'approved' => 1,
    'rejected' => 3,
];

$activeStatus = request('status', 'pending');

@endphp

<div class="request-page">

    <!-- LOADER -->

    <div id="pageLoader">

        <div class="loader-content">

            <i class="ri-loader-4-line ri-spin"></i>

            <p>Loading...</p>

        </div>

    </div>

    <!-- HEADER -->

    <div class="page-header">

        <h2 class="page-title">
            Received Requests
        </h2>

        <div class="page-subtitle">
            Manage all your received requests
        </div>

    </div>

    <!-- ACTION BAR -->

    <div class="action-bar">

        <div class="d-flex align-items-center gap-3 flex-wrap">

            <button
                class="filter-toggle-btn"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#requestFilter">

                <i class="ri-filter-3-fill"></i>

                Filter

            </button>

            <button
                type="button"
                class="filter-toggle-btn"
                id="bulkDeleteBtn"
                style="display:none;background:#dc2626;">

                <i class="ri-delete-bin-6-line"></i>

                Delete Selected

            </button>

        </div>

        <!-- STATUS -->

        <div class="status-toggle">

            <a href="?status=pending"
            class="status-btn {{ $activeStatus == 'pending' ? 'active' : '' }}">
                Pending
            </a>

            <a href="?status=approved"
            class="status-btn {{ $activeStatus == 'approved' ? 'active' : '' }}">
                Approved
            </a>

            <a href="?status=rejected"
            class="status-btn {{ $activeStatus == 'rejected' ? 'active' : '' }}">
                Rejected
            </a>

        </div>

    </div>

    <!-- FILTER -->

    <div class="collapse mb-4" id="requestFilter">

        <div class="filter-card">

            <div class="row g-4">

                <!-- KEYWORD -->

                <div class="col-lg-6">

                    <label class="filter-label">
                        Keywords
                    </label>

                    <div class="keyword-wrap">

                        <i class="ri-search-line"></i>

                        <input
                            type="text"
                            class="form-control"
                            id="keyword"
                            placeholder="Search by name">

                    </div>

                </div>

                <!-- PROFILE -->

                <div class="col-lg-6">

                    <label class="filter-label">
                        Profile
                    </label>

                    <select
                        class="modern-select2"
                        id="profile"
                        multiple>

                        @foreach($profiles as $profile)

                            <option value="{{ $profile->id }}">
                                {{ $profile->profile_name }}
                            </option>

                        @endforeach

                    </select>

                </div>

            </div>

            <div class="row g-4 mt-1">

                <!-- EXPERIENCE -->

                <div class="col-lg-6">

                    <label class="filter-label">
                        Experience
                    </label>

                    <div class="experience-range-card">

                        <div class="experience-top">

                            <h6>
                                Range
                            </h6>

                            <div class="experience-badge">

                                <span id="experienceValue">
                                    All Experience
                                </span>

                            </div>

                        </div>

                        <input
                            type="range"
                            min="0"
                            max="25"
                            value="0"
                            class="experience-range-slider"
                            id="experienceRange">

                    </div>

                </div>

                <!-- LOCATION -->

                

            </div>

            <div class="filter-btn-wrap">

                <button class="apply-filter-btn">
                    Apply Filters
                </button>

                <button
                    type="button"
                    class="cancel-filter-btn">

                    Cancel

                </button>

            </div>

        </div>

    </div>

    <!-- TABLE -->

    <div class="table-card">

        <div class="table-responsive">

            <table id="tenantTable" class="custom-table">

                <thead>

                    <tr>

                        <th width="50">

                            <input
                                type="checkbox"
                                id="selectAll"
                                class="custom-checkbox">

                        </th>

                        <th>SL No</th>

                        <th>Name</th>

                        <th>Company</th>

                        <th>Profile</th>

                        <th>Experience</th>

                        <th>Location</th>

                        <th>Status</th>

                        <th>Action</th>

                    </tr>

                </thead>

            </table>

        </div>

    </div>

</div>

@endsection

@section('js')

<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>

$(document).ready(function () {

    // SELECT2

    $('.modern-select2').select2({

        placeholder: "Select options",

        allowClear: true,

        closeOnSelect: false,

        width: '100%'

    });

    // EXPERIENCE

    $('#experienceRange').on('input', function () {

        let value = $(this).val();

        if(value == 0){

            $('#experienceValue').text('All Experience');

        }else{

            $('#experienceValue').text(value + ' Years');

        }

    });

    // DATATABLE
    let table = $('#tenantTable').DataTable({

        processing: true,
        serverSide: true,
        responsive: true,
        autoWidth: false,
        searching: true,
        ordering: false,
        lengthChange: true,
        pageLength: 10,
        language: {

        paginate: {

                previous: '<i class="ri-arrow-left-s-line"></i>',

                next: '<i class="ri-arrow-right-s-line"></i>'
            }
        },

        ajax: {

            url: "{{ route('organization.received.requests.datatable') }}",

            type: "GET",

            data: function (d) {

                d.keyword = $('#keyword').val();

                d.experience = $('#experienceRange').val();

                d.status = "{{ $statusMap[$activeStatus] }}";
            },

            beforeSend: function () {

                $('#pageLoader').css('display', 'flex');
            },

            complete: function () {

                $('#pageLoader').hide();
            }
        },

        columns: [

            {
                data: 'checkbox',
                orderable: false,
                searchable: false
            },

            {
                data: 'DT_RowIndex',
                orderable: false,
                searchable: false
            },

            {
                data: 'name'
            },

            {
                data: 'tenant_name'
            },

            {
                data: 'profile_name'
            },

            {
                data: 'experience'
            },

            {
                data: 'location'
            },

            {
                data: 'status_badge',
                orderable: false,
                searchable: false
            },

            {
                data: 'action',
                orderable: false,
                searchable: false
            }

        ],

        drawCallback: function () {

            $('.row-checkbox').on('change', function () {

                toggleBulkDelete();
            });
        }
    });

    // APPLY FILTER

    $('.apply-filter-btn').click(function (e) {

        e.preventDefault();

        table.ajax.reload();

    });

    // SELECT ALL

    $('#selectAll').on('change', function () {

        $('.row-checkbox').prop('checked', $(this).is(':checked'));

        toggleBulkDelete();

    });

    // SINGLE CHECKBOX

    $(document).on('change', '.row-checkbox', function () {

        let total = $('.row-checkbox').length;

        let checked = $('.row-checkbox:checked').length;

        $('#selectAll').prop('checked', total === checked);

        toggleBulkDelete();

    });

    // TOGGLE DELETE BUTTON

    function toggleBulkDelete() {

        let checked = $('.row-checkbox:checked').length;

        if(checked > 0){

            $('#bulkDeleteBtn').show();

        }else{

            $('#bulkDeleteBtn').hide();

        }

    }

});

</script>

@endsection