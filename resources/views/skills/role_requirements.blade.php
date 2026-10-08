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
                        <h3 class="mb-0 mr-30">Role Skill Requirements</h3>
                        <ul class="d-flex">
                            <li>
                                <button class="primary-btn radius_30px fix-gr-bg"
                                        data-bs-toggle="modal"
                                        data-bs-target="#addRoleSkillModal">
                                    <i class="ti-plus"></i> Add Requirement
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
                                        <th>Role</th>
                                        <th>Required Skill</th>
                                        <th>Level</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @php $sl = 1; @endphp
                                    @foreach($roles as $role)
                                        @foreach($role->requiredSkills as $skill)
                                            <tr>
                                                <td>{{ $sl++ }}</td>
                                                <td>{{ $role->name }}</td>
                                                <td>{{ $skill->name }}</td>
                                                <td>{{ $skill->pivot->required_level }}</td>

                                                <td>
                                                    <div class="dropdown CRM_dropdown">
                                                        <button class="btn btn-secondary dropdown-toggle"
                                                                type="button"
                                                                id="dropdownMenuButton{{ $role->id }}_{{ $skill->id }}"
                                                                data-bs-toggle="dropdown">
                                                            Action
                                                        </button>

                                                        <ul class="dropdown-menu dropdown-menu-right"
                                                            aria-labelledby="dropdownMenuButton{{ $role->id }}_{{ $skill->id }}">
                                                            <li>
                                                                <a class="dropdown-item"
                                                                   onclick="confirm_modal('{{ route('skills.roles.remove', [$role->id, $skill->id]) }}');">
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

                <!-- ADD REQUIREMENT MODAL -->
                <div class="modal fade admin-query" id="addRoleSkillModal">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <form action="{{ route('skills.roles.assign') }}" method="POST">
                                @csrf

                                <div class="modal-header">
                                    <h4 class="modal-title">Add Role Skill Requirement</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                                        <i class="ti-close"></i>
                                    </button>
                                </div>

                                <div class="modal-body">

                                    <div class="mb-3">
                                        <label class="primary_input_label">Select Role</label>
                                        <select name="role_id" class="primary_select" required>
                                            <option value="">Choose Role</option>
                                            @foreach($roles as $role)
                                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="primary_input_label">Select Skill</label>
                                        <select name="skill_id" class="primary_select" required>
                                            <option value="">Choose Skill</option>
                                            @foreach($skills as $skill)
                                                <option value="{{ $skill->id }}">{{ $skill->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="primary_input_label">Required Level</label>
                                        <input type="text"
                                               class="primary_input_field"
                                               name="required_level"
                                               placeholder="Beginner / Intermediate / Expert"
                                               required>
                                    </div>

                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="primary-btn tr-bg" data-bs-dismiss="modal">Cancel</button>
                                    <button class="primary-btn fix-gr-bg" type="submit">Save Requirement</button>
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
