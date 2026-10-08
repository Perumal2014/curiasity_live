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
                        <h3 class="mb-0 mr-30">Course Enrollment Report</h3>

                        <ul class="d-flex">
                            <li>
                                <a href="{{ route('course.enrollment.export', request()->all()) }}"
                                   class="primary-btn radius_30px fix-gr-bg"
                                   title="Export Excel">
                                    <i class="fas fa-file-excel"></i>
                                    Export
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- TABLE -->
                <div class="col-lg-12">
                    <div class="QA_section QA_section_heading_custom check_box_table">
                        <div class="QA_table">

                            <div class="table-responsive">
                                <table class="table Crm_table_active3">
                                    <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Course</th>
                                            <th>Enrollment ID</th>
                                            <th>Selection Criteria</th>
                                            <th>Module</th>
                                            <th>Company</th>
                                            <th>College</th>
                                            <th>Version</th>
                                            <th>Language</th>
                                            <th>Enrolled (UTC)</th>
                                            <th>Started (UTC)</th>
                                            <th>Completed (UTC)</th>
                                            <th>Status</th>
                                            <th>Progress</th>
                                            <th>Time Spent (min)</th>
                                            <th>Grade</th>
                                            <th>Score</th>
                                            <th>Total Mark</th>
                                            <th>Attempts</th>
                                            <th>Location</th>
                                            <th>Profile</th>
                                            <th>Duration (min)</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @forelse($reportData as $index => $row)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $row['name'] }}</td>
                                                <td>{{ $row['email'] }}</td>
                                                <td>{{ $row['course_title'] }}</td>
                                                <td>{{ $row['unique_id'] }}</td>
                                                <td>{{ $row['selection_criteria'] }}</td>
                                                <td>{{ $row['module'] }}</td>
                                                <td>{{ $row['company_name'] }}</td>
                                                <td>{{ $row['college_name'] }}</td>
                                                <td>{{ $row['version'] }}</td>
                                                <td>{{ $row['language'] }}</td>
                                                <td>{{ $row['enrollment_date'] }}</td>
                                                <td>{{ $row['started_date'] }}</td>
                                                <td>{{ $row['completion_date'] }}</td>
                                                <td>{{ $row['status'] }}</td>

                                                <td>
                                                    <div class="progress-bar-wrapper">
                                                        <div class="progress">
                                                            <div class="progress-bar" role="progressbar"
                                                                style="width: {{ $row['progress_percent'] }}%;">
                                                            </div>
                                                        </div>
                                                        <small>{{ $row['progress_percent'] }}%</small>
                                                    </div>
                                                </td>

                                                <td>{{ $row['time_spent_minutes'] }}</td>
                                                <td>{{ $row['grade'] }}</td>
                                                <td>{{ $row['assessment_score'] }}</td>
                                                <td>{{ $row['assessment_total_mark'] }}</td>
                                                <td>{{ $row['attempts_taken'] }}</td>
                                                <td>{{ $row['location'] }}</td>
                                                <td>{{ $row['profile'] }}</td>
                                                <td>{{ $row['training_duration_minutes'] }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="24" class="text-center">
                                                    No enrollments found.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>

                                </table>
                            </div>

                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</section>

@endsection
