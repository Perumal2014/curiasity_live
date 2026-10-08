@extends(theme('layouts.dashboard_master'))

@section('title')
    {{ Settings('site_title') ? Settings('site_title') : 'Infix LMS' }}
    | {{ __('ticket.available_resources') }}
@endsection


{{-- =========================================
CSS
========================================= --}}
@section('css')

<link rel="stylesheet"
      href="{{ asset('public/frontend/infixlmstheme/css/support.css') }}{{ assetVersion() }}">

<!-- <link rel="stylesheet"
href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css"> -->

<style>

.btn-loader {
    font-size: 12px;
}

#skillLoader {
    margin-top: 10px;
}

table.dataTable tbody tr td {
    vertical-align: middle;
}

/* Fix DataTable Layout */
.dataTables_wrapper {
    width: 100%;
}

.dataTables_filter {
    margin-bottom: 15px;
    text-align: right;
}

.dataTables_filter input {
    border: 1px solid #ddd !important;
    border-radius: 6px !important;
    height: 38px;
    padding: 5px 10px;
}

.dataTables_length {
    margin-bottom: 15px;
}

.dataTables_paginate {
    margin-top: 15px !important;
    text-align: right;
}

.dataTables_paginate .paginate_button {
    padding: 6px 14px !important;
    margin: 0 3px !important;
    border-radius: 6px !important;
    border: none !important;
}

.dataTables_paginate .paginate_button.current {
    background: linear-gradient(90deg,#7c32ff,#c738ff) !important;
    color: #fff !important;
}

.table-responsive {
    overflow-x: auto;
}

#tenantTable {
    width: 100% !important;
}

.dataTables_processing {
    display: none !important;
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



{{-- =========================================
MAIN CONTENT
========================================= --}}
@section('mainContent')
<div id="pageLoader">

    <div class="loader-content">

        <i class="fa fa-spinner fa-spin"></i>

        <p>Loading data...</p>

    </div>

</div>
<div class="main_content_iner support_main main_content_padding">

    <div class="dashboard_lg_card">

        {{-- TITLE --}}
        <div class="support_main_card_title">

            <h5>{{ __('ticket.corporate_resources') }}</h5>

        </div>



        {{-- FILTER SECTION --}}
        <div class="support_main_card p-0">

            <form id="searchForm">

                <div class="support_main_card_content">

                    <div class="row">


                        {{-- PRIMARY SKILL --}}
                        <div class="col-md-4">

                            <div class="support_main_card_content_item">

                                <label class='primary_label2'>
                                    {{ __('ticket.Profile') }}
                                </label>

                                <select name="primary_skill"
                                        id="primarySkill"
                                        class="theme_select w-100">

                                    <option value="">
                                        {{ __('common.Select') }}
                                    </option>

                                    @foreach($profiles as $profile)

                                        <option value="{{ $profile->id }}">
                                            {{ $profile->profile_name }}
                                        </option>

                                    @endforeach

                                </select>

                            </div>

                        </div>



                        {{-- SECONDARY SKILL --}}
                        <!-- <div class="col-md-4">

                            <div class="support_main_card_content_item">

                                <label class='primary_label2'>
                                    {{ __('ticket.Secondary Skills') }}
                                </label>

                                <select name="secondary_skill"
                                        id="secondarySkill"
                                        class="theme_select w-100">

                                    <option value="">
                                        {{ __('common.Select') }}
                                    </option>

                                </select>

                            </div>


                            {{-- Loader --}}
                            <div id="skillLoader" style="display:none;">

                                <i class="fa fa-spinner fa-spin"></i>
                                Loading...

                            </div>

                        </div> -->



                        {{-- SEARCH BUTTON --}}
                        <div class="col-md-4">

                            <div class="support_main_card_content_item">

                                <label class='primary_label2'>
                                    &nbsp;
                                </label>

                                <button type="button"
                                        id="searchBtn"
                                        class="theme_btn radius_30px fix-gr-bg btn-loader">

                                    {{ __('common.Search') }}

                                </button>

                            </div>

                        </div>

                    </div>

                </div>

            </form>

        </div>



        {{-- TABLE TITLE --}}
        <div class="support_main_card_title mobile_wrap">

            <h5>Corporate Resources</h5>

        </div>



        {{-- TABLE SECTION --}}
        <div class="support_main_card p-0 m-0">

            <div class="support_main_ticket">

                <div class="tab-content" id="myTabContent">

                    <div class="tab-pane fade show active"
                         id="active"
                         role="tabpanel">
                        <div class="table-responsive">
                            
                            <table id="tenantTable" class="table table-bordered w-100">

                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Description</th>
                                        <th>Request Date</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                            </table>
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>

     <div class="modal fade"
        id="userDetailsModal"
        tabindex="-1"
        aria-hidden="true">

        <div class="modal-dialog modal-xl modal-dialog-centered">

            <div class="modal-content border-0 shadow-lg rounded-4">

                <!-- HEADER -->
                <div class="modal-header border-0 pb-0">

                    <div>

                        <h3 class="fw-bold mb-1" id="userName">

                            User Name

                        </h3>

                        <div class="d-flex align-items-center gap-2">

                            <span class="text-muted">
                                Status
                            </span>

                            <span id="userStatusBadge"
                                class="badge bg-success">

                                Active

                            </span>

                        </div>

                    </div>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">

                    </button>

                </div>



                <!-- BODY -->
                <div class="modal-body pt-2">

                    <div class="row g-4">


                        <!-- LEFT -->
                        <div class="col-md-8">

                            <div class="card border-0 bg-light rounded-4 p-4">

                                <div class="d-flex align-items-center mb-4">

                                    <img id="userImage"
                                        src="https://ui-avatars.com/api/?name=User"
                                        width="60"
                                        height="60"
                                        class="rounded-circle me-3">

                                    <div>

                                        <h5 class="mb-0"
                                            id="userFullName">

                                            User Name

                                        </h5>

                                        <small class="text-muted"
                                            id="userEmail">

                                            user@gmail.com

                                        </small>

                                    </div>

                                </div>



                                <!-- DETAILS -->
                                <div class="row">

                                    <div class="col-md-6 mb-3">

                                        <label class="text-muted small">

                                            Phone

                                        </label>

                                        <div id="userPhone"
                                            class="fw-semibold">

                                            -

                                        </div>

                                    </div>



                                    <div class="col-md-6 mb-3">

                                        <label class="text-muted small">

                                            Department

                                        </label>

                                        <div id="userDepartment"
                                            class="fw-semibold">

                                            -

                                        </div>

                                    </div>



                                    <div class="col-md-6 mb-3">

                                        <label class="text-muted small">

                                            Primary Skill

                                        </label>

                                        <div id="userPrimarySkill"
                                            class="fw-semibold">

                                            -

                                        </div>

                                    </div>



                                    <div class="col-md-6 mb-3">

                                        <label class="text-muted small">

                                            Secondary Skill

                                        </label>

                                        <div id="userSecondarySkill"
                                            class="fw-semibold">

                                            -

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>



                        <!-- RIGHT -->
                        <div class="col-md-4">

                            <div class="card border-0 bg-light rounded-4 p-4">

                                <h5 class="fw-bold mb-4">

                                    Additional Info

                                </h5>

                                <div class="mb-3">

                                    <label class="text-muted small">

                                        Joined Date

                                    </label>

                                    <div id="joinedDate"
                                        class="fw-semibold">

                                        -

                                    </div>

                                </div>

                                <div class="mb-3">

                                    <label class="text-muted small">

                                        Resource Status

                                    </label>

                                    <div id="resourceStatus"
                                        class="fw-semibold">

                                        -

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection



{{-- =========================================
JS
========================================= --}}
@section('js')

<script src="{{ asset('public/frontend/infixlmstheme/js/support.js') }}"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>



<script type="text/javascript">

$(document).ready(function () {
    // =========================================
    // DATATABLE
    // =========================================
    let table = $('#tenantTable').DataTable({

    serverSide: true,
    responsive: true,
    autoWidth: false,

    language: {
        paginate: {
            previous: "Prev",
            next: "Next"
        }
    },

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

            d.primary_skill = $('#primarySkill').val();

            d.secondary_skill = $('#secondarySkill').val();
        },

        error: function (xhr, error, thrown) {

            console.log(xhr.responseText);
        }
    },

    columns: [

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
            data: 'email',
            name: 'email'
        },

        {
            data: 'phone',
            name: 'phone'
        },

        {
            data: 'description',
            name: 'description'
        },

        {
            data: 'request_date',
            name: 'request_date'
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
    // SEARCH BUTTON
    // =========================================

    $('#searchBtn').on('click', function (e) {

        e.preventDefault();

        // Show custom loader
        $('#tableLoader').show();

        // Disable button
        $('#searchBtn').prop('disabled', true);

        // Button Loader
        $('#searchBtn').html(`
            <i class="fa fa-spinner fa-spin"></i> Loading...
        `);

        // Reload table
        table.ajax.reload(function () {

            // Hide loader
            $('#tableLoader').hide();

            // Enable button
            $('#searchBtn').prop('disabled', false);

            // Restore button text
            $('#searchBtn').html('Search');
        });

    });


    $(document).on('click', '.viewUserBtn', function () {

        let userId = $(this).data('id');

        $('#userDetailsModal').modal('show');

        $('#pageLoader').css('display', 'flex');

        $.ajax({

            url: "{{ route(
                    'corporate.user.details',
                    [
                        'tenant_slug' => request()->route('tenant_slug'),
                        'id' => ':id'
                    ]
                ) }}"
                .replace(':id', userId),

            type: "GET",

            success: function (res) {
                
                // USER INFO
                $('#userName').text(res.name);
                $('#userFullName').text(res.name);
                $('#userEmail').text(res.email);
                $('#userPhone').text(res.phone ?? '-');
                $('#userDepartment').text(res.department ?? '-');
                $('#userPrimarySkill').text(res.primary_skill ?? '-');
                $('#userSecondarySkill').text(res.secondary_skill ?? '-');
                $('#joinedDate').text(res.joined_date ?? '-');
                // $('#resourceStatus').text(res.status ?? '-');

                // alert(res.status);
                // // STATUS BADGE
                let statusText = '';
                let statusClass = '';

                if (res.status == 2) {

                    statusText = 'Available';
                    statusClass = 'badge bg-success';

                } else if (res.status == 3) {

                    statusText = 'Requested';
                    statusClass = 'badge bg-warning';

                } else {

                    statusText = 'Inactive';
                    statusClass = 'badge bg-secondary';
                }

                // =========================================
                // BADGE
                // =========================================

                $('#userStatusBadge')
                    .removeClass()
                    .addClass(statusClass)
                    .text(statusText);

                // =========================================
                // APPEND STATUS TEXT
                // =========================================

                $('#resourceStatus').text(statusText);

                // USER IMAGE
                $('#userImage').attr(
                    'src',
                    res.image ??
                    'https://ui-avatars.com/api/?name=' + res.name
                );

                // $('#pageLoader').css('display', 'flex');

                 $('#pageLoader').hide();
            },

            error: function () {

                alert('Something went wrong');
            }

        });

    });


});

</script>

@endsection