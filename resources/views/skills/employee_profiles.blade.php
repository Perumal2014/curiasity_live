@extends('backend.master')

@section('mainContent')

{!! generateBreadcrumb() !!}

<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        <div class="white-box">
            <div class="row justify-content-center">

                <!-- PAGE HEADER -->
                <div class="col-12">
                    <div class="box_header common_table_header d-flex justify-content-between align-items-center">
                        <h3 class="mb-0 mr-30">Employee Skill Profiles</h3>
                        <ul class="d-flex">
                            <li>
                                <button class="primary-btn radius_30px fix-gr-bg"
                                        data-bs-toggle="modal"
                                        data-bs-target="#addEmployeeSkillModal">
                                    <i class="ti-plus"></i> Add Skill Mapping
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- MAIN TABLE -->
                <div class="col-lg-12">
                    <div class="QA_section QA_section_heading_custom check_box_table">
                        <div class="QA_table">
                            <table class="table Crm_table_active3">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Employee</th>
                                        <th>Skill</th>
                                        <th>Level</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @php $sl = 1; @endphp
                                    @foreach($employees as $emp)
                                        @foreach($emp->corporateSkills as $skill)
                                            <tr>
                                                <td>{{ $sl++ }}</td>
                                                <td>{{ $emp->name }}</td>
                                                <td>{{ $skill->name }}</td>
                                                <td>{{ $skill->pivot->level }}</td>

                                                <td>
                                                    <div class="dropdown CRM_dropdown">
                                                        <button class="btn btn-secondary dropdown-toggle"
                                                                type="button"
                                                                id="actionMenu{{ $emp->id }}_{{ $skill->id }}"
                                                                data-bs-toggle="dropdown">
                                                            Action
                                                        </button>

                                                        <ul class="dropdown-menu dropdown-menu-right"
                                                            aria-labelledby="actionMenu{{ $emp->id }}_{{ $skill->id }}">
                                                            <li>
                                                                <a class="dropdown-item"
                                                                   onclick="confirm_modal('{{ route('skills.employees.remove', [$emp->id, $skill->id]) }}');">
                                                                    Remove
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>

                <!-- ADD MAPPING MODAL -->
                <div class="modal fade admin-query" id="addEmployeeSkillModal">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <form id="addEmployeeSkillForm" method="POST">
                                @csrf

                                <div class="modal-header">
                                    <h4 class="modal-title">Assign Skill to Employee</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                                        <i class="ti-close"></i>
                                    </button>
                                </div>

                                <div class="modal-body">

                                    <div class="mb-3">
                                        <label class="primary_input_label">Employee</label>
                                        <select name="user_id" class="primary_select" required>
                                            <option value="">Select Employee</option>
                                            @foreach($employees as $emp)
                                                <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="primary_input_label">Skill</label>
                                        <select name="skill_id" class="primary_select" required>
                                            <option value="">Select Skill</option>
                                            @foreach($skills as $skill)
                                                <option value="{{ $skill->id }}">{{ $skill->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="primary_input_label">Skill Level</label>
                                        <select name="level" class="primary_select" required>
                                            <option value="">Select Level</option>
                                            <option value="1">Beginner</option>
                                            <option value="2">Intermediate</option>
                                            <option value="3">Expert</option>
                                        </select>
                                    </div>

                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="primary-btn tr-bg" data-bs-dismiss="modal">Cancel</button>
                                    <button class="primary-btn fix-gr-bg" type="submit">Save</button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
                <!-- END MODAL -->

            </div>
        </div>
    </div>
</section>

@endsection
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    $(document).ready(function() {
        $('#addEmployeeSkillForm').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: '{{ route('skills.employees.store') }}',
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#addEmployeeSkillModal').modal('hide');
                        location.reload(); // Reload the page to reflect changes
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr) {
                    alert('An error occurred while processing your request.');
                }
            });
        });
    });
</script>
