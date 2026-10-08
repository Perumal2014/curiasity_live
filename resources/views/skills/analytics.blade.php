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
                        <h3 class="mb-0 mr-30">Skill Analytics Dashboard</h3>
                        <ul class="d-flex">
                            <li>
                                <!-- Optional button space -->
                                <!-- <button class="primary-btn radius_30px fix-gr-bg">Export</button> -->
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
                                        <th>Skill</th>
                                        <th>Employees with Skill</th>
                                        <th>Required (Roles)</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($skills as $key => $skill)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $skill->name }}</td>
                                            <td>{{ $skill->users_count }}</td>
                                            <td>{{ 0 }}</td>
                                            
                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

@endsection
