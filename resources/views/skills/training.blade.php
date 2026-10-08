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
                        <h3 class="mb-0 mr-30">Recommended Trainings</h3>
                        <ul class="d-flex">
                            <li>
                                <!-- If no button needed, you can remove this -->
                                <!-- Example button: -->
                                <!-- <button class="primary-btn radius_30px fix-gr-bg"><i class="ti-plus"></i> Add Training</button> -->
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
                                        <th>Training Title</th>
                                        <th>Target Skill</th>
                                        <th>Level Focus</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @php $sl = 1; @endphp
                                    @foreach($trainings as $t)
                                        <tr>
                                            <td>{{ $sl++ }}</td>
                                            <td>{{ $t['title'] }}</td>
                                            <td>{{ $t['skill'] ?? '—' }}</td>
                                            <td>{{ $t['target'] }}</td>
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
