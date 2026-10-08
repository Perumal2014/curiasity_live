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

    /* =====================================================
        HEADER
    ===================================================== */

    .page-header{
        margin-bottom:22px;
    }

    .page-title{
        font-size:30px;
        font-weight:800;
        color:#111827;
        margin-bottom:4px;
    }

    .page-subtitle{
        color:#6b7280;
        font-size:14px;
    }

    /* =====================================================
        ACTION BAR
    ===================================================== */

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
        transition:0.3s;
    }

    .filter-toggle-btn:hover{
        transform:translateY(-2px);
    }

    .filter-toggle-btn i{
        font-size:18px;
    }

    /* =====================================================
        STATUS TOGGLE
    ===================================================== */

    .status-toggle{
        display:flex;
        align-items:center;
        gap:16px;
        flex-wrap:wrap;
    }

    .status-btn{
        min-width:150px;
        height:56px;
        border-radius:18px;
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
        background:#863CF0;
        color:#fff;
        box-shadow:0 10px 24px rgba(134,60,240,0.25);
    }

    .status-btn:hover{
        background:#4f46e5;
        color:#fff;
    }

    /* =====================================================
        FILTER PANEL
    ===================================================== */

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

    /* =====================================================
        SEARCH INPUT
    ===================================================== */

    .keyword-wrap{
        position:relative;
        width:100%;
    }

    .keyword-wrap i{
        position:absolute;
        top:50%;
        left:18px;
        transform:translateY(-50%);
        color:#94a3b8;
        font-size:18px;
    }

    .form-control{
        width:100%;
        height:60px;
        border:1px solid #e2e8f0;
        border-radius:18px;
        background:#fff;
        padding:0 18px 0 50px;
        font-size:15px;
        font-weight:400;
        color:#0f172a;
        outline:none;
        box-shadow:none !important;
        transition:0.3s;
    }

    .form-control:focus{
        border-color:#020826;
        box-shadow:0 0 0 3px rgba(2,8,38,0.05) !important;
    }

    .form-control::placeholder{
        color:#94a3b8;
    }

    /* =====================================================
        SELECT2
    ===================================================== */

    .select2-container{
        width:100% !important;
    }

    .select2-container--default .select2-selection--multiple{
        min-height:60px;
        border:1px solid #e2e8f0 !important;
        border-radius:18px !important;
        padding:8px 12px !important;
        display:flex;
        align-items:center;
        flex-wrap:wrap;
        gap:6px;
        background:#fff;
    }

    .select2-container--default.select2-container--focus
    .select2-selection--multiple{
        border-color:#020826 !important;
        box-shadow:0 0 0 3px rgba(2,8,38,0.05);
    }

    .select2-container--default
    .select2-selection--multiple
    .select2-selection__choice{
        background:#eef2ff !important;
        border:none !important;
        color:#3563ff !important;
        border-radius:999px !important;
        padding:6px 12px !important;
        font-size:13px;
        font-weight:600;
        margin-top:0 !important;
    }

    .select2-container--default
    .select2-selection--multiple
    .select2-selection__choice__remove{
        color:#3563ff !important;
        margin-right:6px;
        border:none !important;
    }

    .select2-container--default
    .select2-search--inline
    .select2-search__field{
        margin-top:0 !important;
        margin-left:0 !important;
        height:24px;
        font-size:14px !important;
    }

    .select2-container--default
    .select2-selection--multiple
    .select2-selection__rendered{
        padding-left:0 !important;
        margin-left:0 !important;
    }

    .select2-dropdown{
        border:1px solid #e2e8f0 !important;
        border-radius:14px !important;
        overflow:hidden;
    }

    .select2-results__option{
        padding:10px 14px !important;
        font-size:14px;
    }

    .select2-container--default
    .select2-results__option--highlighted.select2-results__option--selectable{
        background:#7c3aed !important;
        color:#fff !important;
    }

    /* =====================================================
        EXPERIENCE BOX
    ===================================================== */

    .experience-range-card{
        border:1px solid #e2e8f0;
        border-radius:22px;
        padding:20px;
        background:#fff;
        min-height:100px;
    }

    .experience-top{
        display:flex;
        align-items:center;
        justify-content:space-between;
        margin-bottom:15px;
        gap:16px;
    }

    .experience-title{
        font-size:16px;
        font-weight:700;
        color:#0f172a;
        margin:0;
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
        appearance:none;
        height:8px;
        border-radius:999px;
        background:#dbe4f0;
        outline:none;
    }

    .experience-range-slider::-webkit-slider-thumb{
        appearance:none;
        width:22px;
        height:22px;
        border-radius:50%;
        background:#3563ff;
        border:4px solid #fff;
        box-shadow:0 4px 12px rgba(53,99,255,0.35);
        cursor:pointer;
    }

    .experience-range-slider::-moz-range-thumb{
        width:22px;
        height:22px;
        border:none;
        border-radius:50%;
        background:#3563ff;
        cursor:pointer;
    }

    /* =====================================================
        BUTTONS
    ===================================================== */

    .filter-btn-wrap{
        display:flex;
        align-items:center;
        gap:16px;
        margin-top:34px;
        flex-wrap:wrap;
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
        cursor:pointer;
        transition:0.3s;
    }

    .cancel-filter-btn{
        height:58px;
        padding:0 34px;
        border:1px solid #dbe2ea;
        border-radius:18px;
        background:#fff;
        color:#0f172a;
        font-size:18px;
        font-weight:600;
        cursor:pointer;
        transition:0.3s;
    }

    .apply-filter-btn:hover,
    .cancel-filter-btn:hover{
        transform:translateY(-2px);
    }

    /* =====================================================
        TABLE
    ===================================================== */

    .table-checkbox{
        display:flex;
        align-items:center;
        justify-content:center;
    }
    
    .custom-checkbox{
        width:15px;
        height:15px;
        cursor:pointer;
        accent-color:#7c3aed;
    }

    .table-card{
        background:#fff;
        border-radius:24px;
        overflow:hidden;
        border:1px solid #e9edf5;
    }

    .table-responsive{
        overflow-x:auto;
    }

    .custom-table{
        width:100%;
        margin:0;
        border-collapse:separate;
        border-spacing:0;
    }

    .custom-table thead{
        background:#f9fafb;
    }

    .custom-table thead th{
        padding:18px 16px;
        font-size:13px;
        font-weight:800;
        color:#374151;
        text-transform:uppercase;
        border-bottom:1px solid #eef2f7;
        white-space:nowrap;
    }

    .custom-table tbody td{
        padding:18px 16px;
        border-bottom:1px solid #f1f5f9;
        font-size:14px;
        color:#111827;
        vertical-align:middle;
    }

    .custom-table tbody tr:last-child td{
        border-bottom:none;
    }

    .custom-table tbody tr:hover{
        background:#fafbff;
    }

    /* =====================================================
        USER
    ===================================================== */

    .user-name{
        font-size:15px;
        font-weight:700;
        color:#111827;
    }



    /* =====================================================
        ACTION
    ===================================================== */

    .action-wrap{
        display:flex;
        align-items:center;
        gap:10px;
    }

    .action-btn{
        width:40px;
        height:40px;
        border:none;
        border-radius:12px;
        display:flex;
        align-items:center;
        justify-content:center;
        font-size:18px;
        transition:0.3s;
        text-decoration:none;
    }

    .view-btn{
        background:#eef2ff;
        color:#4f46e5;
    }

    .view-btn:hover{
        background:#4f46e5;
        color:#fff;
    }

    .delete-btn{
        background:#fef2f2;
        color:#dc2626;
    }

    .delete-btn:hover{
        background:#dc2626;
        color:#fff;
    }

    /* =====================================================
        EMPTY STATE
    ===================================================== */

    .empty-state{
        padding:80px 20px;
        text-align:center;
    }

    .empty-state i{
        font-size:70px;
        color:#cbd5e1;
        margin-bottom:16px;
    }

    .empty-title{
        font-size:24px;
        font-weight:800;
        color:#111827;
        margin-bottom:6px;
    }

    .empty-text{
        color:#6b7280;
        font-size:14px;
    }

    /* =====================================================
        MOBILE
    ===================================================== */

    @media(max-width:768px){

        .request-page{
            padding:18px;
        }

        .filter-card{
            padding:22px;
            border-radius:22px;
        }

        .page-title{
            font-size:24px;
        }

        .action-bar{
            flex-direction:column;
            align-items:stretch;
        }

        .status-toggle{
            width:100%;
            gap:10px;
        }

        .status-btn{
            flex:1;
            min-width:auto;
            height:52px;
            font-size:14px;
        }

        .filter-toggle-btn{
            width:100%;
            justify-content:center;
        }

        .apply-filter-btn,
        .cancel-filter-btn{
            width:100%;
        }

    }

    #pageLoader {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255,255,255,0.8);
    z-index: 999999;
    display: none;

    justify-content: center;
    align-items: center;
    }

    #pageLoader .loader-content {
        text-align: center;
    }

    #pageLoader i {
        font-size: 45px;
        color: #7c32ff;
    }

    #pageLoader p {
        margin-top: 10px;
        font-weight: 600;
        color: #333;
    }

</style>

@endsection

@section('mainContent')

@php

    $activeStatus = request('status','pending');

    $statuses = ['pending','accepted','rejected'];

    $companies = [
        'Google',
        'Microsoft',
        'Infosys',
        'Amazon',
        'TCS',
        'Wipro',
        'Meta'
    ];

    $profiles = [
        'Frontend Developer',
        'Backend Developer',
        'UI Designer',
        'Full Stack Developer',
        'DevOps Engineer'
    ];

    $locations = [
        'Bangalore',
        'Chennai',
        'Hyderabad',
        'Mumbai',
        'Delhi',
        'Pune'
    ];


    $requests = [];

    for($i = 1; $i <= 25; $i++){

        $requests[] = [

            'name' => 'Employee '.$i,

            'company' => $companies[array_rand($companies)],

            'profile' => $profiles[array_rand($profiles)],

            'location' => $locations[array_rand($locations)],

            'experience' => rand(0,25).' Years',

            'status' => $statuses[array_rand($statuses)]

        ];

    }

    $filteredRequests = collect($requests)->where('status',$activeStatus);

@endphp

<div class="request-page">

    <!-- HEADER -->

    <div class="page-header">

        <h2 class="page-title">
            Sent Requests
        </h2>

        <div class="page-subtitle">
            Manage all your sent requests
        </div>

    </div>

    <!-- ACTION BAR -->

    <div class="action-bar">

        <div class="d-flex align-items-center gap-3 flex-wrap">
            
            <button
                class="filter-toggle-btn" 
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#requestFilter"
            >
            
                <i class="ri-filter-3-fill"></i>
                Filter
            </button>
            
            <!-- BULK DELETE -->
             
            <button
                type="button"
                class="filter-toggle-btn bulk-delete-btn"
                id="bulkDeleteBtn"
                style="display:none;background:#dc2626;"
            >
                <i class="ri-delete-bin-6-line"></i>
                
                Delete Selected
            
            </button>
        
        </div>

        <div class="status-toggle">

            <a
                href="?status=pending"
                class="status-btn {{ $activeStatus == 'pending' ? 'active' : '' }}"
            >
                Pending
            </a>

            <a
                href="?status=accepted"
                class="status-btn {{ $activeStatus == 'accepted' ? 'active' : '' }}"
            >
                Accepted
            </a>

            <a
                href="?status=rejected"
                class="status-btn {{ $activeStatus == 'rejected' ? 'active' : '' }}"
            >
                Rejected
            </a>

        </div>

    </div>

    <!-- FILTER -->

    <div class="collapse mb-4" id="requestFilter">

        <div class="filter-card">

            <div class="row g-4">

                <!-- KEYWORDS -->

                <div class="col-lg-6">

                    <label class="filter-label">
                        Keywords
                    </label>

                    <div class="keyword-wrap">

                        <i class="ri-search-line"></i>

                        <input
                            type="text"
                            class="form-control"
                            placeholder="Search by name or profile..."
                        >

                    </div>

                </div>

                <!-- PROFILE -->

                <div class="col-lg-6">

                    <label class="filter-label">
                        Profile
                    </label>

                    <select
                        class="modern-select2"
                        multiple
                    >

                        @foreach($profiles as $profile)

                            <option>
                                {{ $profile }}
                            </option>

                        @endforeach

                    </select>

                </div>

            </div>

            <!-- SECOND ROW -->

            <div class="row g-4 mt-1">

                <!-- EXPERIENCE -->

                <div class="col-lg-6">

                    <label class="filter-label">
                        Experience
                    </label>

                    <div class="experience-range-card">

                        <div class="experience-top">

                            <h6 class="experience-title">
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
                            id="experienceRange"
                        >

                    </div>

                </div>

                <!-- LOCATION -->

                <div class="col-lg-6">

                    <label class="filter-label">
                        Location
                    </label>

                    <select
                        class="modern-select2"
                        multiple
                    >

                        @foreach($locations as $location)

                            <option>
                                {{ $location }}
                            </option>

                        @endforeach

                    </select>

                </div>

            </div>

            <!-- BUTTONS -->

            <div class="filter-btn-wrap">

                <button class="apply-filter-btn">
                    Apply Filters
                </button>

                <button
                    type="button"
                    class="cancel-filter-btn"
                >
                    Cancel
                </button>

            </div>

        </div>

    </div>

    <!-- TABLE -->

    <div class="table-card">

        <div class="table-responsive">

            <table class="custom-table" id="tenantTable">

                <thead>

                    <tr>

                        <th width="60">
                            <div class="table-checkbox">
                                <input
                                    type="checkbox"
                                    id="selectAll"
                                    class="custom-checkbox"
                                >
                            </div>
                        </th>
                        <th width="80">SL No</th>
                        <th>Name</th>
                        <th>Company</th>
                        <th>Profile</th>
                        <th>Experience</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th width="120">Action</th>

                    </tr>

                </thead>

                <tbody>

                </tbody>

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

    function initModernSelect2() {

        $('.modern-select2').select2({

            placeholder: "Select options",

            allowClear: true,

            closeOnSelect: false,

            width: '100%'

        });

    }

    initModernSelect2();

    

    $('#requestFilter').on('shown.bs.collapse', function () {

        $('.modern-select2').select2('destroy');

        initModernSelect2();

    });

    // EXPERIENCE RANGE

    const experienceRange =
        document.getElementById('experienceRange');

    const experienceValue =
        document.getElementById('experienceValue');

    experienceRange.addEventListener('input', function(){

        if(this.value == 0){

            experienceValue.innerText = 'All Experience';

        }else{

            experienceValue.innerText =
                this.value + ' Years';

        }

    });

    /* =====================================================
        SELECT ALL CHECKBOX
    ===================================================== */

    const selectAll = document.getElementById('selectAll');
    
    const rowCheckboxes = document.querySelectorAll('.row-checkbox');
    
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
    
    selectAll.addEventListener('change', function(){
        rowCheckboxes.forEach((checkbox) => {       
            checkbox.checked = this.checked;
        });
          
        toggleBulkDelete();
    
    });
    
    rowCheckboxes.forEach((checkbox) => {    
        checkbox.addEventListener('change', function(){
            const checkedCount = document.querySelectorAll('.row-checkbox:checked').length;
                
            selectAll.checked = checkedCount === rowCheckboxes.length;
                
            toggleBulkDelete();
        });
        
    });
        
        
    function toggleBulkDelete(){
        const checked = document.querySelectorAll('.row-checkbox:checked').length;
        
        if(checked > 0){

            bulkDeleteBtn.style.display = 'flex';

        }else{
            
            bulkDeleteBtn.style.display = 'none';
        }
    }

</script>

<script>

    $(document).ready(function () {

        // =========================================
        // SELECT2
        // =========================================

        alert('test message');

        function initModernSelect2() {

            $('.modern-select2').select2({

                placeholder: "Select options",
                allowClear: true,
                closeOnSelect: false,
                width: '100%'

            });

        }

        initModernSelect2();

        $('#requestFilter').on('shown.bs.collapse', function () {

            $('.modern-select2').select2('destroy');

            initModernSelect2();

        });

        // =========================================
        // DATATABLE
        // =========================================

        let table = $('#tenantTable').DataTable({

            processing: true,
            serverSide: true,
            responsive: true,
            autoWidth: false,

            ajax: {

                url: "{{ route('organization.requests.datatable', ['tenant_slug' => request()->route('tenant_slug')]) }}",

                type: "GET",

                beforeSend: function () {

                    $('#pageLoader').css('display', 'flex');

                },

                complete: function () {

                    $('#pageLoader').hide();

                },

                data: function (d) {

                    d.keyword = $('#keyword').val();

                    d.profile = $('#profile').val();

                    d.location = $('#location').val();

                    d.experience = $('#experienceRange').val();

                },

                error: function (xhr) {

                    console.log(xhr.responseText);

                }

            },

            columns: [

                {
                    data: 'checkbox',
                    name: 'checkbox',
                    searchable: false,
                    orderable: false
                },

                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    searchable: false,
                    orderable: false
                },

                {
                    data: 'name',
                    name: 'name'
                },

                 {
                    data: 'tenant_name',
                    name: 'tenant_name'
                },

                 {
                    data: 'profile_name',
                    name: 'profile_name'
                },
               

                {
                    data: 'experience',
                    name: 'experience',
                    searchable: false,
                    orderable: false
                },

                {
                    data: 'location',
                    name: 'location'
                },

                {
                    data: 'status_badge',
                    name: 'status_badge',
                    searchable: false,
                    orderable: false
                },

                {
                    data: 'action',
                    name: 'action',
                    searchable: false,
                    orderable: false
                }

            ]

        });

        // =========================================
        // APPLY FILTER
        // =========================================

        $('.apply-filter-btn').on('click', function (e) {

            e.preventDefault();

            table.ajax.reload();

        });

        // =========================================
        // EXPERIENCE RANGE
        // =========================================

        const experienceRange =
            document.getElementById('experienceRange');

        const experienceValue =
            document.getElementById('experienceValue');

        experienceRange.addEventListener('input', function(){

            if(this.value == 0){

                experienceValue.innerText = 'All Experience';

            }else{

                experienceValue.innerText =
                    this.value + ' Years';

            }

        });

    });

</script>

@endsection
