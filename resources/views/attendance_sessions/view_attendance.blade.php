@extends('backend.master')

@section('mainContent')

@if(session('success'))
    <script>
        toastr.success(@json(session('success')), 'Success', {
            closeButton: true,
            progressBar: true
        });
    </script>
@endif

{!! generateBreadcrumb() !!}

<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        <div class="white-box">

            <!-- Header -->
            <div class="row justify-content-center mb-20">
                <div class="col-12">
                    <div class="box_header common_table_header d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">
                            {{ __('View Attendance') }} -
                            {{ $session->lesson->name ?? '' }}
                        </h3>

                        <a href="{{ route('attendance.sessions', ['tenant_slug' => request()->route('tenant_slug'), 'course' => $session->course_id ]) }}"
                           class="primary-btn small fix-gr-bg">
                            {{ __('Back') }}
                        </a>
                    </div>
                </div>
            </div>

            <!-- Session Info -->
            <div class="row mb-30">
                <div class="col-lg-12">
                    <div class="primary_input mb-3">
                        <strong>{{ __('Date') }}:</strong>
                        {{ $session->lesson->start_date }}
                        &nbsp;&nbsp;&nbsp;
                        <strong>{{ __('Time') }}:</strong>
                        {{ $session->lesson->start_time }} - {{ $session->lesson->end_time }}
                        &nbsp;&nbsp;&nbsp;
                        <strong>{{ __('Type') }}:</strong>
                        {{ ucfirst(str_replace('_', ' ', $session->type)) }}
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="QA_section QA_section_heading_custom check_box_table">
                <div class="QA_table">
                    <table class="table Crm_table_active3">
                        <thead>
                            <tr>
                                <th>{{ __('SL') }}</th>
                                <th>{{ __('Student Name') }}</th>
                                <th>{{ __('Email') }}</th>
                                <th>{{ __('Status') }}</th>
                            </tr>
                        </thead>
                        <tbody>

                            @forelse($students as $key => $student)

                                @php
                                    $status = $attendanceRecords[$student->id]->status ?? 'Not Attended';
                                @endphp

                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $student->name }}</td>
                                    <td>{{ $student->email }}</td>
                                    <td>
                                        @if($status == 'Attended')
                                            <span >
                                                {{ __('Attended') }}
                                            </span>
                                        @else
                                            <span>
                                                {{ __('Not Attended') }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>

                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">
                                        {{ __('No Students Found') }}
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</section>

@endsection