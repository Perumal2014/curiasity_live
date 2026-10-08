@extends('backend.master')
@push('styles')
    <style>
        .skill-status-toggle {
            cursor: pointer;
        }
    </style>
@endpush

@section('mainContent')
    {!! generateBreadcrumb() !!}

    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid p-0">
            <div class="white-box">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <div class="box_header common_table_header d-flex justify-content-between align-items-center">
                            <h3 class="mb-0 mr-30">{{ __('Skills Library') }}</h3>
                            <ul class="d-flex">
                                <li>
                                    <button class="primary-btn radius_30px fix-gr-bg" data-bs-toggle="modal"
                                        data-bs-target="#addSkillModal">
                                        <i class="ti-plus"></i> {{ __('Add Skill') }}
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <div class="QA_section QA_section_heading_custom check_box_table">
                            <div class="QA_table">
                                <div class="">
                                    <table id="lms_table" class="table Crm_table_active3">
                                        <thead>
                                            <tr>
                                                <th scope="col">{{ __('SL') }}</th>
                                                <th scope="col">{{ __('Skill Name') }}</th>
                                                <!-- <th scope="col">{{ __('Status') }}</th> -->
                                                <th scope="col">{{ __('Created On') }}</th>
                                                <th scope="col">{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($skills as $key => $skill)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $skill->name }}</td>
                                                    
                                                    <!-- <td>
                                                        <label class="switch_toggle" for="active_checkbox{{ $skill->id }}">
                                                            <input type="checkbox"
                                                                class="skill-status-toggle"
                                                                id="active_checkbox{{ $skill->id }}"
                                                                {{ $skill->status == 'Active' ? 'checked' : '' }}
                                                                data-id="{{ $skill->id }}">
                                                            <i class="slider round"></i>
                                                        </label>
                                                    </td> -->
                                                    <td>{{ $skill->created_at->format('d M Y') }}</td>
                                                    <td>
                                                        <div class="dropdown CRM_dropdown">
                                                            <button class="btn btn-secondary dropdown-toggle" type="button"
                                                                id="dropdownMenuButton{{ $skill->id }}" data-bs-toggle="dropdown"
                                                                aria-expanded="false">
                                                                {{ __('Action') }}
                                                            </button>
                                                            <ul class="dropdown-menu dropdown-menu-right"
                                                                aria-labelledby="dropdownMenuButton{{ $skill->id }}">
                                                                <li>
                                                                    <a class="dropdown-item editSkillBtn" 
                                                                        data-id="{{ $skill->id }}"
                                                                        data-name="{{ $skill->name }}"
                                                                        data-category="{{ $skill->category }}"
                                                                        data-status="{{ $skill->status }}">
                                                                        {{ __('Edit') }}
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a class="dropdown-item"
                                                                        onclick="confirm_modal('{{ route('skills.delete', $skill->id) }}');">
                                                                        {{ __('Delete') }}
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Add Skill Modal -->
                    <div class="modal fade admin-query" id="addSkillModal">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <form id="skillForm" method="POST">
                                    @csrf
                                    <div class="modal-header">
                                        <h4 class="modal-title">{{ __('Add New Skill') }}</h4>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                                            <i class="ti-close"></i>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="primary_input_label">{{ __('Skill Name') }}</label>
                                            <input type="text" name="name" class="primary_input_field" required>
                                             <span class="text-danger error-name"></span>
                                        </div>
                                       
                                        <div class="mb-3">
                                            <label class="primary_input_label">{{ __('Status') }}</label>
                                            <select name="status" class="primary_select">
                                                <option value="1">{{ __('Active') }}</option>
                                                <option value="0">{{ __('Inactive') }}</option>
                                            </select>
                                        </div>
                                        <div class="alert alert-danger d-none" id="skillError"></div>
                                        <div class="alert alert-success d-none" id="skillSuccess"></div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="primary-btn tr-bg" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                                        <button class="primary-btn fix-gr-bg" type="submit" id="saveSkillBtn">{{ __('Save Skill') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade admin-query" id="editSkillModal" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <form id="editSkillForm">
                                    @csrf

                                    <input type="hidden" name="id" id="edit_skill_id">

                                    <div class="modal-header">
                                        <h4 class="modal-title">Edit Skill</h4>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                                            <i class="ti-close"></i>
                                        </button>
                                    </div>

                                    <div class="modal-body">

                                        <div class="mb-3">
                                            <label class="primary_input_label">Skill Name</label>
                                            <input type="text"
                                                name="name"
                                                id="edit_skill_name"
                                                class="primary_input_field"
                                                required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="primary_input_label">Status</label>
                                            <select name="status"
                                                    id="edit_skill_status"
                                                    class="primary_select">
                                                <option value="1">Active</option>
                                                <option value="0">Inactive</option>
                                            </select>
                                        </div>

                                    </div>
                                    <div class="alert alert-danger d-none" id="updateSkillError"></div>
                                    <div class="alert alert-success d-none" id="updateSkillSuccess"></div>
                                    <div class="modal-footer">
                                        <button type="button"
                                                class="primary-btn tr-bg"
                                                data-bs-dismiss="modal">
                                            Cancel
                                        </button>

                                        <button type="submit"
                                                class="primary-btn fix-gr-bg" id="updateSkillBtn">
                                            Update Skill
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
$(document).on('submit', '#skillForm', function(e) {
    e.preventDefault();
    $('.text-danger').html('');
    $('#skillError').addClass('d-none');
    $('#skillSuccess').addClass('d-none');
    $('#updateSkillError').addClass('d-none');
    $('#updateSkillSuccess').addClass('d-none');
    $.ajax({
        url: "{{ route('skills.store', ['tenant_slug' => request()->tenant_slug]) }}",
        type: "POST",
        data: $(this).serialize(),

        success: function(response) {

            if (response.status) {

                $('#skillSuccess')
                    .removeClass('d-none')
                    .html(response.message);

                $('#skillForm')[0].reset();

                // Optional: reload DataTable
                // $('.data-table').DataTable().ajax.reload();

                setTimeout(function() {
                    $('#addSkillModal').modal('hide');
                }, 1000);

                $('#lms_table').load(location.href + ' #lms_table');

                $('#saveSkillBtn').prop('disabled', false);

            } else {

                $('#skillError')
                    .removeClass('d-none')
                    .html(response.message);
                $('#saveSkillBtn').prop('disabled', false);
            }
        },

        error: function(xhr) {

            if (xhr.status === 422) {

                let errors = xhr.responseJSON.errors;

                $.each(errors, function(key, value) {
                    $('.error-' + key).html(value[0]);
                });
            }

            $('#saveSkillBtn').prop('disabled', false);
        }
    });
});

 $(document).on('click', '.editSkillBtn', function(e) {
    
        e.preventDefault();

        $('#edit_skill_id').val($(this).data('id'));
        $('#edit_skill_name').val($(this).data('name'));
        $('#edit_skill_status').val($(this).data('status'));

       $('#editSkillModal').modal('show');

        // editModal.show();
    });

    $(document).on('submit', '#editSkillForm', function(e) {

        e.preventDefault();

        $('#updateSkillBtn').prop('disabled', true);
        
        let id = $('#skill_id').val();

        $.ajax({
            url: "{{ route('skills.update', ['id' => ':id']) }}".replace(':id', id),
            type: "POST",
            data: $(this).serialize(),

            success: function(response) {

            if (response.status) {

                $('#updateSkillSuccess')
                    .removeClass('d-none')
                    .html(response.message);

                $('#editSkillForm')[0].reset();

                // Optional: reload DataTable
                // $('.data-table').DataTable().ajax.reload();

                setTimeout(function() {
                    $('#editSkillModal').modal('hide');
                }, 1000);

                $('#lms_table').load(location.href + ' #lms_table');

                $('#updateSkillBtn').prop('disabled', false);

            } else {

                $('#updateSkillError')
                    .removeClass('d-none')
                    .html(response.message);
                $('#updateSkillBtn').prop('disabled', false);
            }
        },

        error: function(xhr) {

            if (xhr.status === 422) {

                let errors = xhr.responseJSON.errors;

                $.each(errors, function(key, value) {
                    $('.error-' + key).html(value[0]);
                });
            }

            $('#updateSkillBtn').prop('disabled', false);
        }
        });

    });
</script>