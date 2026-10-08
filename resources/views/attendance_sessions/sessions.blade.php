@extends('backend.master')

@section('mainContent')

{!! generateBreadcrumb() !!}

<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">

        <div class="white-box">

            <!-- Header -->
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="box_header common_table_header d-flex justify-content-between align-items-center">

                        <div>
                            <h3 class="mb-0">
                                {{ __('Attendance Sessions') }}
                            </h3>
                        </div>

                        <a href="{{ route('attendance.sessions.index',[
                                'tenant_slug'=>request()->route('tenant_slug')
                            ]) }}"
                           class="primary-btn small fix-gr-bg">
                            <i class="ti-arrow-left"></i>
                            {{ __('Back to Courses') }}
                        </a>

                    </div>

                    <div class="row mb-30">
                <div class="col-lg-12">
                    <div class="primary_input mb-3">
                        {{ __('Course') }} :
                        <strong>{{ $course->title }}</strong>
                       
                    </div>
                </div>
            </div>
                </div>

                <div class="col-lg-12">

                    <div class="QA_section QA_section_heading_custom check_box_table">

                        <div class="QA_table">

                            <table class="table Crm_table_active3">

                                <thead>

                                <tr>

                                    <th>{{ __('SL') }}</th>

                                    <th>{{ __('Lesson') }}</th>

                                    @if(Auth::user()->role_id == 1 || Auth::user()->role_id == 8 || Auth::user()->role_id == 11)
                                        <th>Instructor</th>
                                    @endif

                                    <th>{{ __('Date') }}</th>

                                    <th>{{ __('Time') }}</th>

                                    <th>{{ __('Type') }}</th>

                                    <th>{{ __('Action') }}</th>

                                </tr>

                                </thead>

                                <tbody>

                                @forelse($sessions as $key => $session)

                                    <tr>

                                        <td>
                                            {{ $key + 1 }}
                                        </td>

                                        <td>
                                            {{ $session->lesson->name ?? 'N/A' }}
                                        </td>

                                        @if(Auth::user()->role_id == 1 || Auth::user()->role_id == 8 || Auth::user()->role_id == 11)
                                            <td>
                                                {{ $session->lesson?->instructor?->name ?? 'N/A' }}
                                            </td>
                                        @endif

                                        <td>
                                            {{ $session->lesson?->start_date
                                                ? \Carbon\Carbon::parse($session->lesson->start_date)->format('d M Y')
                                                : 'N/A'
                                            }}
                                        </td>

                                        <td>
                                            @if($session->lesson?->start_time && $session->lesson?->end_time)
                                                {{ $session->lesson->start_time }}
                                                -
                                                {{ $session->lesson->end_time }}
                                            @else
                                                N/A
                                            @endif
                                        </td>

                                        <td>
                                            {{ ucfirst(str_replace('_',' ',$session->type)) }}
                                        </td>

                                        <td>

                                            <div class="dropdown CRM_dropdown">

                                                <button class="btn btn-secondary dropdown-toggle"
                                                        type="button"
                                                        data-bs-toggle="dropdown">

                                                    {{ __('Action') }}

                                                </button>

                                                <ul class="dropdown-menu dropdown-menu-right">

                                                    <li>
                                                        <a class="dropdown-item"
                                                           href="{{ route('attendance.sessions.mark',[
                                                                'tenant_slug'=>request()->route('tenant_slug'),
                                                                'id'=>$session->id
                                                           ]) }}">
                                                            {{ __('Mark Attendance') }}
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a class="dropdown-item"
                                                           href="{{ route('attendance.sessions.generateQr',[
                                                                'tenant_slug'=>request()->route('tenant_slug'),
                                                                'id'=>$session->id
                                                           ]) }}">
                                                            <i class="ti-qrcode"></i>
                                                            {{ __('Generate QR') }}
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a class="dropdown-item"
                                                           href="{{ route('attendance.sessions.view',[
                                                                'tenant_slug'=>request()->route('tenant_slug'),
                                                                'id'=>$session->id
                                                           ]) }}">
                                                            {{ __('View Attendance') }}
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a class="dropdown-item"
                                                           href="{{ route('attendance.sessions.import.page',[
                                                                'tenant_slug'=>request()->route('tenant_slug'),
                                                                'id'=>$session->id
                                                           ]) }}">
                                                            {{ __('Upload Attendance') }}
                                                        </a>
                                                    </li>

                                                </ul>

                                            </div>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="{{ Auth::user()->role_id == 11 ? 7 : 6 }}"
                                            class="text-center">

                                            {{ __('No Sessions Found') }}

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