@extends('backend.master')

@section('mainContent')

<!-- {!! generateBreadcrumb() !!} -->

<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">

        <div class="white-box">

            <!-- Header -->
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="box_header common_table_header d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">
                            {{ __('Attendance Courses') }}
                        </h3>
                    </div>
                </div>

                <div class="col-lg-12">

                    <div class="QA_section QA_section_heading_custom check_box_table">

                        <div class="QA_table">

                            <table class="table Crm_table_active3">

                                <thead>
                                    <tr>
                                        <th>{{ __('SL') }}</th>

                                        <th>{{ __('Course') }}</th>

                                       
                                        <th>{{ __('Total Sessions') }}</th>

                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @forelse($courses as $key => $course)

                                        <tr>

                                            <td>
                                                {{ $key + 1 }}
                                            </td>

                                            <td>
                                                <strong>{{ $course->title }}</strong>
                                            </td>

                                           

                                            <td>
                                                
                                                    {{ $course->attendance_sessions_count }}
                                                
                                            </td>

                                           <td>
                                                <a href="{{ route('attendance.sessions', [
                                                    'tenant_slug' => request()->route('tenant_slug'),'course' => $course->id]) }}"
                                                    class="session-btn">
                                                    <i class="ti-eye"></i>
                                                    {{ __('View Sessions') }}
                                                </a>
                                            </td>

                                        </tr>

                                    @empty

                                        <tr>

                                            <td colspan="{{ Auth::user()->role_id == 11 ? 5 : 4 }}"
                                                class="text-center">

                                                {{ __('No Courses Found') }}

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
</section>

@endsection