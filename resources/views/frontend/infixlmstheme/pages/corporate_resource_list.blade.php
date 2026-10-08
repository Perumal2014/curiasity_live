@extends(theme('layouts.dashboard_master'))

@section('title')
    {{ Settings('site_title') ? Settings('site_title') : 'Infix LMS' }} |
    {{ __('Resource List') }}
@endsection

@section('css')

<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet"/>
<link rel="stylesheet"
      href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">

<style>

.resources-page{
    padding:30px;
    background:#f5f7fb;
    min-height:100vh;
}

/* =====================================================
PAGE HEADER
===================================================== */

.page-header{
    margin-bottom:30px;
}

.page-title{
    font-size:32px;
    font-weight:800;
    color:#111827;
    margin-bottom:10px;
}

.page-subtitle{
    font-size:16px;
    color:#6b7280;
}

/* =====================================================
TOP ACTION BAR
===================================================== */

.top-action-bar{
    background:#fff;

    border:1px solid #e5e7eb;

    border-radius:28px;

    padding:24px 28px;

    display:flex;
    align-items:center;
    justify-content:space-between;

    gap:20px;

    flex-wrap:wrap;

    margin-bottom:28px;
}

.left-actions{
    display:flex;
    align-items:center;
    gap:18px;
    flex-wrap:wrap;
}

.custom-checkbox{
    display:flex;
    align-items:center;
    gap:12px;

    font-size:15px;
    font-weight:700;

    color:#111827;
}

.custom-checkbox input{
    width:20px;
    height:20px;
    cursor:pointer;
}

.selected-count{
    background:#eef2ff;
    color:#4f46e5;

    padding:12px 22px;

    border-radius:40px;

    font-size:14px;
    font-weight:700;
}

/* =====================================================
SEND REQUEST BUTTON
===================================================== */

.send-request-btn{
    height:44px;

    border:none;
    outline:none;

    padding:0 34px;

    border-radius:20px;

    background:linear-gradient(
        135deg,
        #2563eb,
        #4f46e5
    );

    color:#fff;

    font-size:16px;
    font-weight:700;

    display:inline-flex;
    align-items:center;
    justify-content:center;
    gap:14px;

    transition:.25s ease;
}

.send-request-btn i{
    font-size:22px;
}

.send-request-btn:hover{
    transform:translateY(-2px);

    box-shadow:0 12px 24px rgba(37,99,235,.18);
}

/* =====================================================
TABLE
===================================================== */

.resource-table-wrapper{
    background:#fff;

    border-radius:30px;

    overflow:hidden;

    border:1px solid #e5e7eb;
}

.resource-table{
    width:100%;
    border-collapse:collapse;
}

.resource-table thead{
    background:#f8fafc;
}

.resource-table th{
    padding:26px 22px;

    font-size:15px;
    font-weight:800;

    color:#111827;

    border-bottom:1px solid #e5e7eb;
}

.resource-table td{
    padding:26px 22px;

    font-size:15px;

    color:#4b5563;

    border-bottom:1px solid #f1f5f9;

    vertical-align:middle;
}

.resource-table tbody tr{
    transition:.2s ease;
}

.resource-table tbody tr:hover{
    background:#fafbff;
}

/* =====================================================
RESOURCE USER
===================================================== */

.resource-user{
    display:flex;
    align-items:center;
    gap:16px;
}

.resource-avatar{
    width:64px;
    height:64px;

    border-radius:18px;

    background:#eef2ff;

    display:flex;
    align-items:center;
    justify-content:center;

    flex-shrink:0;
}

.resource-avatar i{
    font-size:30px;
    color:#4f46e5;
}

.resource-name{
    font-size:16px;
    font-weight:600;
    color:#111827;
    margin-bottom:6px;
}

.resource-email{
    font-size:14px;
    color:#6b7280;
}

/* =====================================================
SKILL BADGE
===================================================== */

.skill-badge{
    display:inline-flex;
    align-items:center;
    justify-content:center;

    padding:10px 18px;

    border-radius:40px;

    background:#eef2ff;
    color:#4f46e5;

    font-size:13px;
    font-weight:700;
}

/* =====================================================
ACTION BUTTON
===================================================== */

.action-btn{
    height:54px;

    padding:0 22px;

    border:none;

    border-radius:18px;

    background:transparent;

    color:#4f46e5;

    display:inline-flex;
    align-items:center;
    justify-content:center;
    gap:10px;

    font-size:14px;
    font-weight:700;

    transition:.25s ease;
}

.action-btn:hover{
    background:#eef2ff;
    color:#4f46e5;
}

/* =====================================================
ACTION DROPDOWN
===================================================== */

.action-dropdown{
    position:relative;
    display:inline-block;
}

.dropdown-toggle-btn{
    min-width:130px;
}

.action-dropdown-menu{
    position:absolute;

    top:110%;
    right:0;

    width:230px;

    background:#fff;

    border:1px solid #e5e7eb;

    border-radius:20px;

    padding:10px;

    box-shadow:0 15px 40px rgba(0,0,0,.08);

    opacity:0;
    visibility:hidden;

    transform:translateY(10px);

    transition:.25s ease;

    z-index:999;
}

.action-dropdown.active .action-dropdown-menu{
    opacity:1;
    visibility:visible;
    transform:translateY(0);
}

.dropdown-item{
    width:100%;

    display:flex;
    align-items:center;
    gap:12px;

    padding:14px 16px;

    border-radius:14px;

    text-decoration:none;

    color:#111827;

    font-size:14px;
    font-weight:600;

    transition:.2s ease;
}

.dropdown-item:hover{
    background:#eef2ff;
    color:#4f46e5;
}

/* =====================================================
PAGINATION
===================================================== */

.custom-pagination{
    margin-top:34px;

    display:flex;
    justify-content:center;
    gap:10px;
}

.page-btn{
    width:48px;
    height:48px;

    border:none;

    border-radius:14px;

    background:#fff;

    border:1px solid #e5e7eb;

    font-size:14px;
    font-weight:700;

    transition:.2s ease;
}

.page-btn.active{
    background:#4f46e5;
    color:#fff;
    border-color:#4f46e5;
}

.page-btn:hover{
    transform:translateY(-2px);
    border-color:#4f46e5;
    color:#4f46e5;
}

/* =====================================================
RESPONSIVE
===================================================== */

@media(max-width:991px){

    .resources-page{
        padding:20px;
    }

    .resource-table-wrapper{
        overflow-x:auto;
    }

    .resource-table{
        min-width:1200px;
    }

}

@media(max-width:767px){

    .page-title{
        font-size:32px;
    }

}

/* =====================================================
DATATABLE CUSTOM
===================================================== */

.dataTables_length,
.dataTables_filter{
    display:none;
}

.dataTables_info{
    padding:20px !important;
    font-size:14px;
    color:#6b7280 !important;
}

.dataTables_paginate{
    padding:20px !important;
}

.dataTables_paginate .paginate_button{
    min-width:42px !important;
    height:42px;

    border-radius:12px !important;
    border:none !important;

    background:#f3f4f6 !important;

    color:#111827 !important;

    margin:0 4px;

    font-weight:700;
}

.dataTables_paginate .paginate_button.current{
    background:#4f46e5 !important;
    color:#fff !important;
}

.dataTables_paginate .paginate_button:hover{
    background:#6366f1 !important;
    color:#fff !important;
}

/* =====================================================
TOP BAR
===================================================== */

.table-top-bar{
    padding:24px;
    border-bottom:1px solid #eef2f7;

    display:flex;
    justify-content:space-between;
    align-items:center;
}

.table-search{
    width:320px;
    position:relative;
}

.table-search i{
    position:absolute;
    top:50%;
    left:16px;
    transform:translateY(-50%);
    color:#9ca3af;
    font-size:18px;
}

.table-search input{
    width:100%;
    height:48px;

    border:1px solid #e5e7eb;
    border-radius:14px;

    padding:0 18px 0 46px;

    outline:none;

    font-size:14px;

    transition:.2s ease;
}

.table-search input:focus{
    border-color:#4f46e5;
    box-shadow:0 0 0 4px rgba(79,70,229,.08);
}

/* MODAL BODY */

.request-modal-body{
    padding: 24px;
}

/* DESCRIPTION TEXTAREA */

#requestDescription{
    width: 100%;

    min-height: 140px;

    resize: vertical;

    border: 1px solid #d1d5db;

    border-radius: 14px;

    padding: 16px;

    font-size: 15px;

    color: #111827;

    outline: none;

    box-sizing: border-box;

    transition: .2s ease;

    background: #fff;
}

#requestDescription:focus{
    border-color: #4f46e5;

    box-shadow: 0 0 0 4px rgba(79,70,229,.08);
}

/* REMOVE HORIZONTAL SCROLL */

.request-modal-content{
    width: 100%;

    max-width: 620px;

    overflow: hidden;

    border-radius: 24px;

    background: #fff;
}

</style>

@endsection

@section('mainContent')

<div class="container-fluid resources-page">

    <!-- PAGE HEADER -->

    <div class="page-header">

        <h2 class="page-title">
            Resource List
        </h2>

        <p class="page-subtitle">
            Select multiple resources and send request
        </p>

    </div>

    <!-- TOP ACTION BAR -->

    <div class="top-action-bar">

        <div class="left-actions">

            <label class="custom-checkbox">

                <input type="checkbox"
                       id="selectAllResources">

                <span>Select All</span>

            </label>

            <span class="selected-count">
                0 Selected
            </span>

        </div>

        <button class="send-request-btn">

            <i class="ri-send-plane-line"></i>

            Send Request

        </button>

    </div>

    <!-- RESOURCE TABLE -->

    <div class="resource-table-wrapper">

    <!-- TOP FILTER BAR -->

    <div class="table-top-bar">

        <div class="table-search">

            <i class="ri-search-line"></i>

            <input type="text"
                   id="customSearch"
                   placeholder="Search resources...">

        </div>

    </div>

    <table class="resource-table display" id="resourceTable">

        <thead>

            <tr>

                <th width="50"></th>

                <th>Name</th>

                <th>Company</th>

                <th>Role</th>

                <th>Skills</th>

                <th>Experience</th>

                <th class="text-center">Action</th>

            </tr>

        </thead>

    </table>

</div>

    <!-- PAGINATION -->

    <div class="custom-pagination">

        <button class="page-btn">
            <i class="ri-arrow-left-s-line"></i>
        </button>

        <button class="page-btn active">
            1
        </button>

        <button class="page-btn">
            <i class="ri-arrow-right-s-line"></i>
        </button>

    </div>

</div>

@endsection

@section('js')
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

$(document).ready(function () {

    let resourceTable = $('#resourceTable').DataTable({

        processing: true,

        serverSide: true,

        paging: true,

        searching: true,

        info: true,

        destroy: true,

        ajax: {

            url: "{{ route('corporate.resource.view') }}",

            type: "GET",

            data: function (d) {

                d.tenant_id = "{{ request()->route('id') }}";

                d.keyword = @json(request('keyword'));

                d.business_unit = @json(request('business_unit', []));

                d.primary_skills = @json(request('primary_skills', []));

                d.secondary_skills = @json(request('secondary_skills', []));

                d.experience = @json(request('experience'));

                d.location = @json(request('location', []));

                d.grade = @json(request('grade', []));

                d.designation = @json(request('designation', []));

                d.department = @json(request('department', []));

                d.role = @json(request('role', []));

                d.country = @json(request('country', []));

                d.state = @json(request('state', []));

                d.city = @json(request('city', []));

                console.log(d.tenant_id);

                console.log(d.primary_skills);

                console.log(d.location);

            }

        },

        columns: [

            {
                data: 'checkbox',
                orderable: false,
                searchable: false
            },

            {
                data: 'name',
                name: 'name'
            },

            {
                data: 'company',
                name: 'company'
            },

            {
                data: 'role',
                name: 'role'
            },

            {
                data: 'skills',
                name: 'skills',
                orderable: false,
                searchable: false
            },

            {
                data: 'experience',
                name: 'experience'
            },

            {
                data: 'action',
                orderable: false,
                searchable: false,
                className: 'text-center'
            }
        ]
    });


    // SELECT ALL


    // UPDATE COUNT


    function updateSelectedCount() {

        let count =
            $('.resource-checkbox:checked').length;

        // $('.selected-count').text(
        //     count + ' Selected'
        // );

    }



    // SEND REQUEST

    
    // ACTION DROPDOWN

     /*
|--------------------------------------------------------------------------
| SELECT ALL
|--------------------------------------------------------------------------
*/

$('#selectAllResources').change(function () {

    $('.resource-checkbox').prop(
        'checked',
        $(this).is(':checked')
    );

    updateSelectedCount();

});

/*
|--------------------------------------------------------------------------
| SINGLE CHECKBOX
|--------------------------------------------------------------------------
*/

$(document).on(
    'change',
    '.resource-checkbox',
    function ()
    {
        updateSelectedCount();

        /*
        |--------------------------------------------------------------------------
        | AUTO CHECK SELECT ALL
        |--------------------------------------------------------------------------
        */

        let totalCheckbox =
            $('.resource-checkbox').length;

        let checkedCheckbox =
            $('.resource-checkbox:checked').length;

        $('#selectAllResources').prop(
            'checked',
            totalCheckbox === checkedCheckbox
        );
    }
);

/*
|--------------------------------------------------------------------------
| FILTER FORM
|--------------------------------------------------------------------------
*/

$('#filterForm').on('submit', function (e) {

    e.preventDefault();

    resourceTable.ajax.reload();

});

/*
|--------------------------------------------------------------------------
| UPDATE SELECTED COUNT
|--------------------------------------------------------------------------
*/

function updateSelectedCount()
{
    let count =
        $('.resource-checkbox:checked').length;

    $('.selected-count').text(
        count + ' Selected'
    );
}

/*
|--------------------------------------------------------------------------
| SEND REQUEST
|--------------------------------------------------------------------------
*/

$('.send-request-btn').click(function () {

    let selectedResources = [];

    let selectedResourceNames = [];

    /*
    |--------------------------------------------------------------------------
    | GET SELECTED USERS
    |--------------------------------------------------------------------------
    */
    $('.resource-checkbox:checked').each(function ()
    {
        selectedResources.push(
            $(this).val()
        );

        selectedResourceNames.push(
            $(this).data('name')
        );
    });

    /*
    |--------------------------------------------------------------------------
    | NO RESOURCE SELECTED
    |--------------------------------------------------------------------------
    */

    if (selectedResources.length === 0)
    {
        Swal.fire({

            icon: 'warning',

            title: 'Warning',

            text: 'At least one resource needs to be selected'

        });

        return;
    }

    /*
    |--------------------------------------------------------------------------
    | OPEN POPUP
    |--------------------------------------------------------------------------
    */

    Swal.fire({

        title: 'Send Request',

        html: `

            <div class="text-start">

                <div class="mb-3">

                    <label
                        class="mb-2"
                        style="
                            font-weight:600;
                            display:block;
                        ">
                        Selected Resources
                    </label>

                    <div style="
                        padding:12px;
                        background:#f5f7fb;
                        border-radius:10px;
                        font-size:14px;
                    ">
                        <div style="
                            display:flex;
                            flex-wrap:wrap;
                            gap:8px;
                        ">
                            ${selectedResourceNames.map(name => `
                                <span style="
                                    background:#eef2ff;
                                    color:#4f46e5;
                                    padding:8px 14px;
                                    border-radius:30px;
                                    font-size:13px;
                                    font-weight:600;
                                ">
                                    ${name}
                                </span>
                            `).join('')}
                        </div>
                    </div>

                </div>

                <div class="form-group mt-4">

                    <label class="mb-2 fw-bold">
                        Description
                    </label>

                    <textarea
                        id="requestDescription"
                        placeholder="Enter description..."
                    ></textarea>

                </div>

            </div>
        `,

        showCancelButton: true,

        confirmButtonText: 'Submit Request',

        cancelButtonText: 'Cancel',

        confirmButtonColor: '#4f46e5',

        preConfirm: () =>
        {
            let description =
                $('#requestDescription').val();

            if (!description)
            {
                Swal.showValidationMessage(
                    'Description is required'
                );

                return false;
            }

            return {
                description: description
            };
        }

    }).then((result) =>
    {
        /*
        |--------------------------------------------------------------------------
        | CONFIRMED
        |--------------------------------------------------------------------------
        */

        if (result.isConfirmed)
        {
            $.ajax({

                url: "{{ route('assign.users') }}",

                type: "POST",

                data: {

                    _token: "{{ csrf_token() }}",

                    org_id: "{{ request()->route('id') }}",

                    users: selectedResources,

                    description:
                        result.value.description
                },

                /*
                |--------------------------------------------------------------------------
                | LOADER
                |--------------------------------------------------------------------------
                */

                beforeSend: function ()
                {
                    Swal.fire({

                        title: 'Please wait...',

                        text: 'Submitting request',

                        allowOutsideClick: false,

                        didOpen: () =>
                        {
                            Swal.showLoading();
                        }

                    });
                },

                /*
                |--------------------------------------------------------------------------
                | SUCCESS
                |--------------------------------------------------------------------------
                */

                success: function (response)
                {
                    Swal.fire({

                        icon: 'success',

                        title: 'Success',

                        text: response.message

                    });

                    /*
                    |--------------------------------------------------------------------------
                    | RESET
                    |--------------------------------------------------------------------------
                    */

                    $('.resource-checkbox').prop(
                        'checked',
                        false
                    );

                    $('#selectAllResources').prop(
                        'checked',
                        false
                    );

                    updateSelectedCount();

                    /*
                    |--------------------------------------------------------------------------
                    | RELOAD TABLE
                    |--------------------------------------------------------------------------
                    */

                    resourceTable.ajax.reload();
                },

                /*
                |--------------------------------------------------------------------------
                | ERROR
                |--------------------------------------------------------------------------
                */

                error: function (xhr)
                {
                    let message =
                        'Something went wrong';

                    if (
                        xhr.responseJSON &&
                        xhr.responseJSON.message
                    )
                    {
                        message =
                            xhr.responseJSON.message;
                    }

                    Swal.fire({

                        icon: 'error',

                        title: 'Error',

                        text: message

                    });
                }

            });
        }
    });

});

    // CLOSE DROPDOWN

    $(document).click(function () {

        $('.action-dropdown').removeClass('active');

    });

});

</script>

@endsection