@extends('backend.master')

@php
    $table_name = 'tenant_galleries';
@endphp

@section('table')
    {{ $table_name }}
@endsection

@section('mainContent')

@include("backend.partials.alertMessage")

@php
    $LanguageList = getLanguageList();
@endphp

{!! generateBreadcrumb() !!}

<section class="admin-visitor-area up_st_admin_visitor">

    <div class="container-fluid p-0">

        <div class="row">

            {{-- =========================
                LEFT : ADD GROUP
            ========================== --}}
            <div class="col-xxl-3">

                <div class="white-box mb_30 student-details header-menu">

                    <div class="box_header common_table_header">

                        <div class="main-title d-flex mb-0">

                            <h3 class="mb-0">

                                @if(!isset($question))

                                    {{ __('courses.Add New Group') }}

                                @else

                                    {{ __('courses.Edit New Group') }}

                                @endif

                            </h3>

                        </div>

                    </div>


                    <div class="row pt-0">

                        @if(isModuleActive('FrontendMultiLang'))

                            <ul class="nav nav-tabs no-bottom-border mt-sm-md-20 mb-10 ml-3"
                                role="tablist">

                                @foreach ($LanguageList as $key => $language)

                                    <li class="nav-item">

                                        <a class="nav-link
                                            @if (auth()->user()->language_code == $language->code)
                                                active
                                            @endif"
                                           href="#element{{ $language->code }}"
                                           role="tab"
                                           data-bs-toggle="tab">

                                            {{ $language->native }}

                                        </a>

                                    </li>

                                @endforeach

                            </ul>

                        @endif

                    </div>


                    @if (isset($feedbackform))

                        <form action="{{ route('feedbackforms.update') }}"
                              method="POST"
                              id="gallery-form"
                              name="gallery-form"
                              enctype="multipart/form-data">

                            <input type="hidden"
                                   name="id"
                                   value="{{ $feedbackform->id }}">

                    @else

                        <form action="{{ route('learning_groups') }}"
                              method="POST"
                              id="gallery-form"
                              name="gallery-form"
                              enctype="multipart/form-data">

                    @endif

                        @csrf

                        <div class="row">

                            <div class="col-lg-12 mt-10">

                                <div class="mb-15">

                                    <label class="primary_input_label"
                                           for="question">

                                        {{ __('courses.Group Name') }}

                                        <span class="text-danger">*</span>

                                    </label>

                                    <input class="primary_input_field"
                                           type="text"
                                           name="group_name"
                                           id="group"
                                           value="{{ isset($feedbackform) ? $feedbackform->form_name : '' }}"
                                           placeholder="{{ __('courses.Group Name') }}">

                                    @error('group_name')

                                        <span class="text-danger">
                                            {{ $message }}
                                        </span>

                                    @enderror

                                </div>

                            </div>


                            <div class="col-lg-12 text-center">

                                <div class="d-flex justify-content-center pt_20">

                                    <button type="submit"
                                            class="primary-btn semi_large fix-gr-bg"
                                            data-bs-toggle="tooltip"
                                            title="Save Form"
                                            id="save_button_parent">

                                        <i class="fa fa-check"></i>

                                        @if(!isset($question))

                                            {{ __('common.Save') }}

                                        @else

                                            {{ __('common.Update') }}

                                        @endif

                                    </button>

                                </div>

                            </div>

                        </div>

                    </form>

                </div>

            </div>


            {{-- =========================
                RIGHT : GROUP LIST
            ========================== --}}
            <div class="col-xxl-9">

                <div class="white-box">

                    <div class="box_header common_table_header">

                        <div class="main-title d-flex flex-wrap mb-0">

                            <h3 class="mb-0" id="page_title">

                                {{ __('courses.Group Lists') }}

                            </h3>

                        </div>

                    </div>


                    <div class="QA_section QA_section_heading_custom check_box_table">

                        <div class="QA_table">

                            <div>

                                <table id="lms_table"
                                       class="table table-data">

                                    <thead>

                                        <tr>

                                            <th scope="col">
                                                {{ __('common.SL') }}
                                            </th>

                                            <th scope="col">
                                                {{ __('courses.Group Name') }}
                                            </th>

                                            <th scope="col">
                                                {{ __('common.Status') }}
                                            </th>

                                            <th scope="col">
                                                {{ __('common.Action') }}
                                            </th>

                                        </tr>

                                    </thead>


                                    <tbody>

                                        @if($groups->isEmpty())

                                            <tr>

                                                <td colspan="4"
                                                    class="text-center">

                                                    {{ __('courses.No Data Available') }}

                                                </td>

                                            </tr>

                                        @else

                                            @foreach($groups as $key => $form)

                                                <tr>

                                                    <td>
                                                        {{ ++$key }}
                                                    </td>

                                                    <td>
                                                        {{ $form->group_name }}
                                                    </td>

                                                    <td>

                                                        <span class="badge bg-{{
                                                            $form->group_status == 1
                                                                ? 'success'
                                                                : 'danger'
                                                        }}">

                                                            {{
                                                                $form->group_status == 1
                                                                    ? __('common.Active')
                                                                    : __('common.Inactive')
                                                            }}

                                                        </span>

                                                    </td>


                                                    <td>

                                                        <div class="dropdown">

                                                            <button class="btn btn-sm btn-outline-primary dropdown-toggle"
                                                                    type="button"
                                                                    data-bs-toggle="dropdown"
                                                                    aria-haspopup="true"
                                                                    aria-expanded="false">

                                                                {{ __('common.Action') }}

                                                            </button>


                                                            <div class="dropdown-menu">

                                                                <a class="dropdown-item answered-users"
                                                                   href="#"
                                                                   data-id="{{ $form->id }}"
                                                                   data-name="{{ $form->form_name }}">

                                                                    {{ __('common.Add Users') }}

                                                                </a>


                                                                <a class="dropdown-item add-courses"
                                                                   href="#"
                                                                   data-id="{{ $form->id }}"
                                                                   data-name="{{ $form->form_name }}">

                                                                    {{ __('common.Enroll Courses') }}

                                                                </a>


                                                                <a class="dropdown-item copy-link"
                                                                   href="javascript:void(0)"
                                                                   data-id="{{ $form->id }}"
                                                                   data-name="{{ $form->form_name }}"
                                                                   data-url="{{ route('feedback.show', $form->id) }}">

                                                                    {{ __('common.Copy Link') }}

                                                                </a>


                                                                <a class="dropdown-item download-excel"
                                                                   href="javascript:void(0)"
                                                                   data-id="{{ $form->id }}"
                                                                   data-name="{{ $form->form_name }}"
                                                                   data-url="{{ route('feedback.show', $form->id) }}">

                                                                    {{ __('common.Download Excel') }}

                                                                </a>

                                                            </div>

                                                        </div>

                                                    </td>

                                                </tr>

                                            @endforeach

                                        @endif

                                    </tbody>

                                </table>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
        ADD USERS MODAL
    ====================================================== --}}

    <div class="modal fade"
         id="addUsersModal"
         tabindex="-1"
         aria-labelledby="addUsersModalLabel"
         aria-hidden="true">

        <div class="modal-dialog modal-lg">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title"
                        id="addUsersModalLabel">

                        Add Users

                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">
                    </button>

                </div>


                <div class="modal-body">

                    <input type="hidden"
                           id="feedback_form_id">


                    <div class="mb-3">

                        <label class="form-label">
                            Selected Users
                        </label>

                        <div id="selectedUsersBox"
                             class="form-control d-flex flex-wrap gap-2"
                             style="min-height:45px;height:auto;">

                        </div>

                    </div>


                    <div class="mb-3">

                        <input type="text"
                               id="userSearch"
                               class="form-control"
                               placeholder="Search users...">

                    </div>


                    <div class="border-bottom pb-2 mb-2">

                        <label class="d-flex align-items-center gap-2">

                            <input type="checkbox"
                                   id="selectAllUsers">

                            <strong>
                                Select All
                            </strong>

                        </label>

                    </div>


                    <div id="usersList"
                         style="max-height:350px;overflow-y:auto;">

                    </div>

                </div>


                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">

                        Close

                    </button>


                    <button type="button"
                            class="btn btn-primary"
                            id="saveSelectedUsers">

                        Save Users

                    </button>

                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
        ENROLL COURSES MODAL
    ====================================================== --}}

    <div class="modal fade"
         id="enrollCoursesModal"
         tabindex="-1">

        <div class="modal-dialog modal-xl">

            <div class="modal-content">


                <div class="modal-header">

                    <div>

                        <h5 class="modal-title mb-1">
                            Enroll Courses
                        </h5>

                        <small class="text-muted"
                               id="courseFormName">
                        </small>

                    </div>


                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">
                    </button>

                </div>


                <div class="modal-body">

                    <input type="hidden"
                           id="course_feedback_form_id">


                    <div class="row">

                        {{-- ===============================
                            AVAILABLE COURSES
                        ================================ --}}

                        <div class="col-md-7">

                            <div class="card border">

                                <div class="card-header">

                                    <div class="row align-items-center">

                                        <div class="col-md-7">

                                            <h6 class="mb-0">
                                                Available Courses
                                            </h6>

                                        </div>


                                        <div class="col-md-5">

                                            <input type="text"
                                                   id="courseSearch"
                                                   class="form-control form-control-sm"
                                                   placeholder="Search course...">

                                        </div>

                                    </div>

                                </div>


                                <div class="card-body p-0">

                                    <div id="coursesList">

                                        <div class="text-center py-5">

                                            <i class="fa fa-spinner fa-spin"></i>

                                            Loading courses...

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- ===============================
                            SELECTED COURSES
                        ================================ --}}

                        <div class="col-md-5">

                            <div class="card border">

                                <div class="card-header d-flex justify-content-between">

                                    <h6 class="mb-0">
                                        Selected Courses
                                    </h6>


                                    <span class="badge bg-primary"
                                          id="selectedCourseCount">

                                        0

                                    </span>

                                </div>


                                <div class="card-body"
                                     id="selectedCourses"
                                     style="height:400px;overflow-y:auto;">

                                    <div class="text-center text-muted py-5">

                                        <i class="fa fa-book fa-2x mb-2"></i>

                                        <div>
                                            No courses selected
                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">

                        Cancel

                    </button>


                    <button type="button"
                            class="btn btn-primary"
                            id="saveEnrolledCourses">

                        <i class="fa fa-check"></i>

                        Enroll Courses

                    </button>

                </div>

            </div>

        </div>

    </div>

</section>


<input type="hidden"
       name="status_route"
       class="status_route"
       value="{{ route('gallery.update') }}">

@include('backend.partials.delete_modal')

@endsection


@push('scripts')

<script>

$(document).ready(function () {

    /* =====================================================
       DATATABLE
    ====================================================== */

    if ($.fn.DataTable) {

        dataTableOptions = updateColumnExportOption(
            dataTableOptions,
            [0, 1, 2, 3, 4]
        );

        $('#lms_table').DataTable(dataTableOptions);

    }


    /* =====================================================
       COPY LINK
    ====================================================== */

    $(document).on('click', '.copy-link', function () {

        let url = $(this).data('url');

        navigator.clipboard.writeText(url)
            .then(function () {

                Swal.fire({
                    icon: 'success',
                    title: 'Copied',
                    text: 'Link copied successfully!',
                    timer: 1500,
                    showConfirmButton: false
                });

            })
            .catch(function () {

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Unable to copy link.'
                });

            });

    });


    /* =====================================================
       DOWNLOAD EXCEL
    ====================================================== */

    $(document).on('click', '.download-excel', function (e) {

        e.preventDefault();

        let id = $(this).data('id');

        let url = "{{ route('feedback.download.excel', ':id') }}"
            .replace(':id', id);

        window.location.href = url;

    });

});


/* =========================================================
   USERS
========================================================= */

$(document).ready(function () {

    let selectedUsers = {};


    /* =====================================================
       OPEN USERS MODAL
    ====================================================== */

    $(document).on('click', '.answered-users', function (e) {

        e.preventDefault();

        let formId = $(this).data('id');
        let formName = $(this).data('name');

        $('#feedback_form_id').val(formId);

        $('#addUsersModalLabel').text(
            'Add Users - ' + formName
        );

        $('#usersList').html(`
            <div class="text-center py-3">
                Loading users...
            </div>
        `);

        $('#addUsersModal').modal('show');

        loadUsers();

    });


    /* =====================================================
       LOAD USERS
    ====================================================== */

    function loadUsers() {

        $.ajax({

            url: '{{ route("feedback.users", ":id") }}'
                .replace(
                    ':id',
                    $('#feedback_form_id').val()
                ),

            type: 'GET',

            success: function (response) {

                let html = '';

                if (!response.users ||
                    response.users.length === 0) {

                    html = `
                        <div class="text-center text-muted py-3">
                            No users found
                        </div>
                    `;

                } else {

                    $.each(response.users, function (index, user) {

                        let checked =
                            selectedUsers[user.id]
                                ? 'checked'
                                : '';

                        html += `

                            <div class="user-item py-2 border-bottom"
                                 data-name="${String(user.name ?? '').toLowerCase()}
                                 ${String(user.email ?? '').toLowerCase()}">

                                <label class="d-flex align-items-center gap-2 w-100"
                                       style="cursor:pointer;">

                                    <input type="checkbox"
                                           class="user-checkbox"
                                           value="${user.id}"
                                           data-name="${user.name ?? ''}"
                                           data-email="${user.email ?? ''}"
                                           ${checked}>

                                    <div>

                                        <div>
                                            <strong>
                                                ${user.name ?? ''}
                                            </strong>
                                        </div>

                                        <small class="text-muted">
                                            ${user.email ?? ''}
                                        </small>

                                    </div>

                                </label>

                            </div>

                        `;

                    });

                }

                $('#usersList').html(html);

                updateSelectAll();

            },

            error: function () {

                $('#usersList').html(`
                    <div class="alert alert-danger">
                        Failed to load users.
                    </div>
                `);

            }

        });

    }


    /* =====================================================
       USER CHECKBOX
    ====================================================== */

    $(document).on('change', '.user-checkbox', function () {

        let id = $(this).val();

        let name = $(this).data('name');

        let email = $(this).data('email');

        if ($(this).is(':checked')) {

            selectedUsers[id] = {
                id: id,
                name: name,
                email: email
            };

        } else {

            delete selectedUsers[id];

        }

        renderSelectedUsers();

        updateSelectAll();

    });


    /* =====================================================
       SELECT ALL USERS
    ====================================================== */

    $(document).on('change', '#selectAllUsers', function () {

        let checked = $(this).is(':checked');

        $('.user-checkbox:visible').each(function () {

            let id = $(this).val();

            let name = $(this).data('name');

            let email = $(this).data('email');

            $(this).prop('checked', checked);

            if (checked) {

                selectedUsers[id] = {
                    id: id,
                    name: name,
                    email: email
                };

            } else {

                delete selectedUsers[id];

            }

        });

        renderSelectedUsers();

    });


    /* =====================================================
       RENDER USERS
    ====================================================== */

    function renderSelectedUsers() {

        let html = '';

        $.each(selectedUsers, function (id, user) {

            html += `

                <span class="badge bg-primary d-flex align-items-center gap-2 p-2 selected-user"
                      data-id="${id}">

                    ${user.name}

                    <button type="button"
                            class="btn-close btn-close-white remove-user"
                            data-id="${id}"
                            style="font-size:8px;">
                    </button>

                </span>

            `;

        });


        if (html === '') {

            html = `
                <span class="text-muted">
                    No users selected
                </span>
            `;

        }

        $('#selectedUsersBox').html(html);

    }


    /* =====================================================
       REMOVE USER
    ====================================================== */

    $(document).on('click', '.remove-user', function (e) {

        e.preventDefault();

        let id = $(this).data('id');

        delete selectedUsers[id];

        $('.user-checkbox[value="' + id + '"]')
            .prop('checked', false);

        renderSelectedUsers();

        updateSelectAll();

    });


    /* =====================================================
       UPDATE SELECT ALL
    ====================================================== */

    function updateSelectAll() {

        let total =
            $('.user-checkbox:visible').length;

        let checked =
            $('.user-checkbox:visible:checked').length;

        $('#selectAllUsers').prop(
            'checked',
            total > 0 && total === checked
        );

    }


    /* =====================================================
       SEARCH USERS
    ====================================================== */

    $('#userSearch').on('keyup', function () {

        let search =
            String($(this).val() || '').toLowerCase();

        $('.user-item').each(function () {

            let text =
                String($(this).attr('data-name') || '')
                    .toLowerCase();

            if (text.includes(search)) {

                $(this).show();

            } else {

                $(this).hide();

            }

        });

        updateSelectAll();

    });


    /* =====================================================
       SAVE USERS
    ====================================================== */

    $('#saveSelectedUsers').on('click', function () {

        let formId =
            $('#feedback_form_id').val();

        let userIds =
            Object.keys(selectedUsers);


        if (userIds.length === 0) {

            Swal.fire({
                icon: 'warning',
                title: 'Select Users',
                text: 'Please select at least one user.'
            });

            return;

        }


        $.ajax({

            url: '{{ route("feedback.users.save") }}',

            type: 'POST',

            data: {

                _token: '{{ csrf_token() }}',

                feedback_form_id: formId,

                user_ids: userIds

            },

            success: function (response) {

                Swal.fire({

                    icon: 'success',

                    title: 'Success',

                    text: response.message ??
                        'Users added successfully.',

                    timer: 1500,

                    showConfirmButton: false

                });

                $('#addUsersModal').modal('hide');

            },

            error: function (xhr) {

                let message =
                    'Something went wrong.';

                if (xhr.responseJSON &&
                    xhr.responseJSON.message) {

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

    });

});


/* =========================================================
   COURSES
========================================================= */

$(document).ready(function () {

    /*
     * Selected courses are stored here.
     *
     * Example:
     *
     * selectedCourses = {
     *     687: {
     *         id: 687,
     *         title: "course upload tests"
     *     }
     * }
     */

    let selectedCourses = {};


    /* =====================================================
       OPEN COURSE MODAL
    ====================================================== */

    $(document).on('click', '.add-courses', function (e) {

        e.preventDefault();

        let formId =
            $(this).data('id');

        let formName =
            $(this).data('name');


        $('#course_feedback_form_id')
            .val(formId);

        $('#courseFormName')
            .text(formName);

        $('#courseSearch')
            .val('');


        /*
         * Reset selected courses when opening
         * a different group.
         *
         * If you want to keep previous selections
         * while modal is open, don't reset this.
         */

        selectedCourses = {};

        renderSelectedCourses();


        $('#enrollCoursesModal')
            .modal('show');


        loadCourses();

    });


    /* =====================================================
       GET COURSE TITLE
    ====================================================== */

    function getCourseTitle(course) {

        /*
         * Your API returns:
         *
         * title: {
         *     en: "course upload tests"
         * }
         *
         * So we must extract title.en.
         */

        if (
            course.title &&
            typeof course.title === 'object'
        ) {

            return (
                course.title.en ??
                course.title['en-US'] ??
                course.title['en_us'] ??
                Object.values(course.title)[0] ??
                ''
            );

        }


        return course.title ?? '';

    }


    /* =====================================================
       LOAD COURSES
    ====================================================== */

    function loadCourses() {

        $('#coursesList').html(`

            <div class="text-center py-5">

                <i class="fa fa-spinner fa-spin"></i>

                <div class="mt-2">
                    Loading courses...
                </div>

            </div>

        `);


        $.ajax({

            url: '{{ route("feedback.courses") }}',

            type: 'GET',

            dataType: 'json',

            success: function (response) {

                console.log(
                    'Course API Response:',
                    response
                );


                let html = '';


                if (
                    !response.courses ||
                    !Array.isArray(response.courses) ||
                    response.courses.length === 0
                ) {

                    html = `

                        <div class="text-center text-muted py-5">

                            <i class="fa fa-book fa-2x mb-2"></i>

                            <div>
                                No courses available
                            </div>

                        </div>

                    `;

                } else {


                    $.each(
                        response.courses,
                        function (index, course) {


                            console.log(
                                'Course:',
                                course
                            );


                            /*
                             * FIX:
                             * course.title is an object.
                             */

                            let courseTitle =
                                getCourseTitle(course);


                            let safeTitle =
                                $('<div>')
                                    .text(courseTitle)
                                    .html();


                            let isChecked =
                                selectedCourses[course.id]
                                    ? 'checked'
                                    : '';


                            html += `

                                <div class="course-item p-3 border-bottom"
                                     data-search="${String(courseTitle).toLowerCase()}">

                                    <div class="d-flex align-items-center">


                                        <div class="me-3">

                                            <input type="checkbox"

                                                   class="course-checkbox"

                                                   value="${course.id}"

                                                   data-title="${safeTitle}"

                                                   ${isChecked}>

                                        </div>


                                        <div class="flex-grow-1">

                                            <strong>
                                                ${safeTitle}
                                            </strong>


                                            <div class="mt-1">

                                                <span class="badge bg-light text-dark">

                                                    ${course.type ?? 'Course'}

                                                </span>


                                                ${
                                                    course.enrollCount !== undefined
                                                        ? `
                                                            <span class="badge bg-light text-dark ms-1">
                                                                ${course.enrollCount} Enrolled
                                                            </span>
                                                          `
                                                        : ''
                                                }

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            `;

                        }
                    );

                }


                console.log(
                    'Generated Course HTML:',
                    html
                );


                $('#coursesList')
                    .html(html);


                console.log(
                    'Courses displayed:',
                    $('#coursesList .course-item').length
                );


                /*
                 * Re-render selected courses
                 */

                renderSelectedCourses();

            },


            error: function (xhr) {

                console.error(
                    'Course AJAX Error:',
                    xhr
                );


                let message =
                    'Unable to load courses.';


                if (
                    xhr.responseJSON &&
                    xhr.responseJSON.message
                ) {

                    message =
                        xhr.responseJSON.message;

                }


                $('#coursesList').html(`

                    <div class="alert alert-danger m-3">

                        <strong>
                            Error:
                        </strong>

                        ${message}

                    </div>

                `);

            }

        });

    }


    /* =====================================================
       COURSE CHECKBOX
    ====================================================== */

    $(document).on(
        'change',
        '.course-checkbox',
        function () {

            let id =
                String($(this).val());

            let title =
                $(this).attr('data-title');


            if ($(this).is(':checked')) {

                selectedCourses[id] = {

                    id: id,

                    title: title

                };

            } else {

                delete selectedCourses[id];

            }


            renderSelectedCourses();

        }
    );


    /* =====================================================
       RENDER SELECTED COURSES
    ====================================================== */

    function renderSelectedCourses() {

        let html = '';

        let count =
            Object.keys(selectedCourses).length;


        $('#selectedCourseCount')
            .text(count);


        if (count === 0) {

            $('#selectedCourses').html(`

                <div class="text-center text-muted py-5">

                    <i class="fa fa-book fa-2x mb-2"></i>

                    <div>
                        No courses selected
                    </div>

                </div>

            `);

            return;

        }


        $.each(
            selectedCourses,
            function (id, course) {

                html += `

                    <div class="border rounded p-2 mb-2
                                d-flex justify-content-between
                                align-items-center">

                        <div class="me-2">

                            <div class="fw-bold">

                                ${course.title}

                            </div>

                        </div>


                        <button type="button"

                                class="btn btn-sm btn-outline-danger remove-course"

                                data-id="${id}"

                                title="Remove">

                            <i class="fa fa-times"></i>

                        </button>

                    </div>

                `;

            }
        );


        $('#selectedCourses')
            .html(html);

    }


    /* =====================================================
       REMOVE COURSE
    ====================================================== */

    $(document).on(
        'click',
        '.remove-course',
        function () {

            let id =
                String($(this).data('id'));


            delete selectedCourses[id];


            $('.course-checkbox[value="' + id + '"]')
                .prop('checked', false);


            renderSelectedCourses();

        }
    );


    /* =====================================================
       SEARCH COURSES
    ====================================================== */

    $('#courseSearch').on(
        'keyup',
        function () {

            let search =
                String($(this).val() || '')
                    .toLowerCase()
                    .trim();


            $('.course-item').each(
                function () {

                    let courseName =
                        String(
                            $(this).attr('data-search') || ''
                        ).toLowerCase();


                    if (
                        courseName.includes(search)
                    ) {

                        $(this).show();

                    } else {

                        $(this).hide();

                    }

                }
            );

        }
    );


    /* =====================================================
       SAVE / ENROLL COURSES
    ====================================================== */

    $('#saveEnrolledCourses').on(
        'click',
        function () {

            let formId =
                $('#course_feedback_form_id').val();


            let courseIds =
                Object.keys(selectedCourses);


            console.log(
                'Selected Course IDs:',
                courseIds
            );


            if (courseIds.length === 0) {

                Swal.fire({

                    icon: 'warning',

                    title: 'Select Courses',

                    text: 'Please select at least one course.'

                });

                return;

            }


            let button =
                $(this);


            button.prop(
                'disabled',
                true
            );


            button.html(`

                <i class="fa fa-spinner fa-spin"></i>

                Saving...

            `);


            $.ajax({

                url:
                    '{{ route("feedback.courses.save") }}',

                type:
                    'POST',

                data: {

                    _token:
                        '{{ csrf_token() }}',

                    feedback_form_id:
                        formId,

                    course_ids:
                        courseIds

                },


                success: function (response) {

                    Swal.fire({

                        icon: 'success',

                        title: 'Success',

                        text:
                            response.message ??
                            'Courses enrolled successfully.',

                        timer: 1500,

                        showConfirmButton: false

                    });


                    $('#enrollCoursesModal')
                        .modal('hide');


                    /*
                     * Clear after successful save
                     */

                    selectedCourses = {};

                    renderSelectedCourses();

                },


                error: function (xhr) {

                    console.error(
                        'Save Courses Error:',
                        xhr
                    );


                    let message =
                        'Something went wrong.';


                    if (
                        xhr.responseJSON &&
                        xhr.responseJSON.message
                    ) {

                        message =
                            xhr.responseJSON.message;

                    }


                    if (
                        xhr.status === 422 &&
                        xhr.responseJSON &&
                        xhr.responseJSON.errors
                    ) {

                        message =
                            Object.values(
                                xhr.responseJSON.errors
                            )
                            .flat()
                            .join('<br>');

                    }


                    Swal.fire({

                        icon: 'error',

                        title: 'Error',

                        html: message

                    });

                },


                complete: function () {

                    button.prop(
                        'disabled',
                        false
                    );


                    button.html(`

                        <i class="fa fa-check"></i>

                        Enroll Courses

                    `);

                }

            });

        }
    );

});

</script>

@endpush
