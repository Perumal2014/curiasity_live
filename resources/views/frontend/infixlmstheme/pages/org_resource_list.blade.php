@extends(theme('layouts.dashboard_master'))

@section('title')
{{ Settings('site_title') ? Settings('site_title') : 'Infix LMS' }}
| {{ __('ticket.available_resources') }}
@endsection


{{-- =========================================
CSS
========================================= --}}
@section('css')

<link rel="stylesheet" href="{{ asset('public/frontend/infixlmstheme/css/support.css') }}{{ assetVersion() }}">

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
    background: linear-gradient(90deg, #7c32ff, #c738ff) !important;
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

.dataTables_wrapper .dataTables_paginate {
    display: flex !important;
    justify-content: flex-start !important;
    align-items: center;
}

#userDetailsModal .modal-content {
    border-radius: 20px;
}

#userDetailsModal .card {
    min-height: 100%;
}

#userDetailsModal label {
    font-size: 13px;
}

#pageLoader {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.8);
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


        {{-- PAGE TITLE --}}
        <div class="support_main_card_title">

            <h4>

                {{ $tenant->tenant_name }} Users

            </h4>

        </div>

        <button class="theme_btn" id="assignUsersBtn" style="display:none;">

            Selected Users

        </button>

        {{-- CUSTOM LOADER --}}




        {{-- TABLE --}}
        <div class="support_main_card p-3">

            <div class="table-responsive">
                <table id="usersTable" class="table table-bordered" width="100%">

                    <thead>

                        <tr>
                            <th>

                                <input type="checkbox" class="form-check-input userCheckbox" id="selectAllUsers">

                            </th>
                            <th>SL</th>

                            <th>Name</th>

                            <th>Email</th>

                            <th>Phone</th>

                            <th>Status</th>

                            <th>Action</th>

                        </tr>

                    </thead>

                </table>
            </div>

        </div>

    </div>

    <!-- USER DETAILS MODAL -->

    <!-- USER DETAILS MODAL -->

    <div class="modal fade" id="userDetailsModal" tabindex="-1" aria-hidden="true">

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

                            <span id="userStatusBadge" class="badge bg-success">

                                Active

                            </span>

                        </div>

                    </div>

                    <button type="button" class="btn-close" data-bs-dismiss="modal">

                    </button>

                </div>



                <!-- BODY -->
                <div class="modal-body pt-2">

                    <div class="row g-4">


                        <!-- LEFT -->
                        <div class="col-md-8">

                            <div class="card border-0 bg-light rounded-4 p-4">

                                <div class="d-flex align-items-center mb-4">

                                    <img id="userImage" src="https://ui-avatars.com/api/?name=User" width="60"
                                        height="60" class="rounded-circle me-3">

                                    <div>

                                        <h5 class="mb-0" id="userFullName">

                                            User Name

                                        </h5>

                                        <small class="text-muted" id="userEmail">

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

                                        <div id="userPhone" class="fw-semibold">

                                            -

                                        </div>

                                    </div>



                                    <div class="col-md-6 mb-3">

                                        <label class="text-muted small">

                                            Department

                                        </label>

                                        <div id="userDepartment" class="fw-semibold">

                                            -

                                        </div>

                                    </div>



                                    <div class="col-md-6 mb-3">

                                        <label class="text-muted small">

                                            Primary Skill

                                        </label>

                                        <div id="userPrimarySkill" class="fw-semibold">

                                            -

                                        </div>

                                    </div>



                                    <div class="col-md-6 mb-3">

                                        <label class="text-muted small">

                                            Secondary Skill

                                        </label>

                                        <div id="userSecondarySkill" class="fw-semibold">

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

                                    <div id="joinedDate" class="fw-semibold">

                                        -

                                    </div>

                                </div>

                                <div class="mb-3">

                                    <label class="text-muted small">

                                        Resource Status

                                    </label>

                                    <div id="resourceStatus" class="fw-semibold">

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

    <!-- ASSIGN USERS MODAL -->

    <div class="modal fade" id="assignUsersModal" tabindex="-1" aria-hidden="true">

        <div class="modal-dialog modal-lg modal-dialog-centered">

            <div class="modal-content rounded-4 border-0">

                <div class="modal-header">

                    <h5 class="modal-title">

                        Assign Selected Users

                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal">

                    </button>

                </div>

                <div class="modal-body">

                    <!-- USERS LIST -->
                    <div class="mb-4">

                        <label class="fw-bold mb-2">

                            Selected Users

                        </label>

                        <div id="selectedUsersList">

                        </div>

                    </div>



                    <!-- DESCRIPTION -->
                    <div class="mb-3">

                        <label class="fw-bold mb-2">

                            Description

                        </label>

                        <textarea class="form-control" id="assignDescription" rows="5"
                            placeholder="Enter description..."></textarea>

                    </div>

                    <input type="hidden" id="organizationId" value="{{ request()->route('id') }}">

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">

                        Close

                    </button>

                    <button type="button" class="theme_btn" id="submitAssignBtn">

                        Submit

                    </button>

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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>


<script>

$(document).ready(function () {

    // =========================================
    // DATATABLE
    // =========================================

    let table = $('#usersTable').DataTable({

        serverSide: true,
        processing: false,
        responsive: true,
        autoWidth: false,

        language: {
            paginate: {
                previous: "Prev",
                next: "Next"
            }
        },

        ajax: {

            url: "{{ route('corporate.resource.users.datatable', [
                'tenant_slug' => request()->route('tenant_slug'),
                'id' => $tenant->id
            ]) }}",

            type: "GET",

            beforeSend: function () {

                $('#pageLoader').css('display', 'flex');
            },

            complete: function () {

                $('#pageLoader').css('display', 'none');
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
                data: 'email',
                name: 'email'
            },

            {
                data: 'phone',
                name: 'phone'
            },

            {
                data: 'status_badge',
                name: 'status',
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
    // VIEW USER DETAILS
    // =========================================

    $(document).on('click', '.viewUserBtn', function () {

        let userId = $(this).data('id');

        $('#userDetailsModal').modal('show');

        $.ajax({

            url: "{{ route(
                'corporate.user.details',
                [
                    'tenant_slug' => request()->route('tenant_slug'),
                    'id' => ':id'
                ]
            ) }}".replace(':id', userId),

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
                $('#resourceStatus').text(res.resource_status ?? '-');


                // STATUS BADGE
                if (res.status == 1) {

                    $('#userStatusBadge')
                        .removeClass()
                        .addClass('badge bg-success')
                        .text('Active');

                } else if (res.status == 2) {

                    $('#userStatusBadge')
                        .removeClass()
                        .addClass('badge bg-info')
                        .text('Available Resource');

                } else {

                    $('#userStatusBadge')
                        .removeClass()
                        .addClass('badge bg-secondary')
                        .text('Inactive');
                }


                // USER IMAGE
                $('#userImage').attr(
                    'src',
                    res.image ??
                    'https://ui-avatars.com/api/?name=' + res.name
                );
            },

            error: function () {

                alert('Something went wrong');
            }
        });
    });



    // =========================================
    // SELECT USERS
    // =========================================

    let selectedUsers = [];


    // =========================================
    // SELECT ALL
    // =========================================

    $(document).on('change', '#selectAllUsers', function () {

        let isChecked = $(this).prop('checked');

        $('.userCheckbox').prop('checked', isChecked);

        if (isChecked) {

            $('.userCheckbox').each(function () {

                let userId = $(this).val();

                if (!selectedUsers.includes(userId)) {

                    selectedUsers.push(userId);
                }
            });

        } else {

            selectedUsers = [];
        }

        toggleAssignButton();

        console.log(selectedUsers);
    });



    // =========================================
    // SINGLE CHECKBOX
    // =========================================

    $(document).on('change', '.userCheckbox', function () {

        let userId = $(this).val();

        if ($(this).is(':checked')) {

            if (!selectedUsers.includes(userId)) {

                selectedUsers.push(userId);
            }

        } else {

            selectedUsers = selectedUsers.filter(
                id => id != userId
            );

            $('#selectAllUsers').prop('checked', false);
        }


        // CHECK ALL SELECTED
        if (
            $('.userCheckbox:checked').length ===
            $('.userCheckbox').length
        ) {

            $('#selectAllUsers').prop('checked', true);
        }

        toggleAssignButton();

        console.log(selectedUsers);
    });



    // =========================================
    // TOGGLE ASSIGN BUTTON
    // =========================================

    function toggleAssignButton() {

        if ($('.userCheckbox:checked').length > 0) {

            $('#assignUsersBtn').show();

        } else {

            $('#assignUsersBtn').hide();
        }
    }



    // =========================================
    // OPEN ASSIGN MODAL
    // =========================================

    $('#assignUsersBtn').on('click', function () {

        let selectedUsersHtml = '';

        $('.userCheckbox:checked').each(function () {

            let row = $(this).closest('tr');

            let userName = row.find('td:eq(2)').text();

            selectedUsersHtml += `
                <span style="
                    display:inline-block;
                    background:#0d6efd;
                    color:#fff;
                    padding:6px 12px;
                    border-radius:20px;
                    margin-right:8px;
                    margin-bottom:8px;
                    font-size:14px;
                ">
                    ${userName}
                </span>
            `;
        });

        $('#selectedUsersList').html(selectedUsersHtml);

        $('#assignUsersModal').modal('show');
    });



    // =========================================
    // SUBMIT ASSIGN USERS
    // =========================================

    $('#submitAssignBtn').on('click', function () {

        let selectedUsers = [];

        $('.userCheckbox:checked').each(function () {

            selectedUsers.push($(this).val());
        });

        let description = $('#assignDescription').val();

        let organizationId = $('#organizationId').val();


        // VALIDATION
        if (selectedUsers.length == 0) {

            alert('Please select users');

            return;
        }

        if (description == '') {

            alert('Please enter description');

            return;
        }


        // BUTTON LOADER
        $('#submitAssignBtn').html(`
            <i class="fa fa-spinner fa-spin"></i>
            Saving...
        `);

        $('#submitAssignBtn').prop('disabled', true);


        $.ajax({

            url: "{{ route('assign.users',['tenant_slug' => request()->route('tenant_slug')]) }}",

            type: "POST",

            data: {

                _token: "{{ csrf_token() }}",

                users: selectedUsers,

                description: description,

                organization_id: organizationId
            },

            success: function (res) {

                alert(res.message);

                $('#assignUsersModal').modal('hide');

                $('#assignDescription').val('');

                $('.userCheckbox').prop('checked', false);

                $('#selectAllUsers').prop('checked', false);

                $('#assignUsersBtn').hide();

                table.ajax.reload();
            },

            error: function (xhr) {

                console.log(xhr.responseText);

                alert('Something went wrong');
            },

            complete: function () {

                $('#submitAssignBtn').html('Submit');

                $('#submitAssignBtn').prop('disabled', false);
            }
        });
    });

});

</script>
@endsection