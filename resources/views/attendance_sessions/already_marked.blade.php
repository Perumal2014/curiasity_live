@extends(theme('layouts.dashboard_master'))

@section('title')
    {{ __('Attendance Already Marked') }}
@endsection

@section('mainContent')
<div class="main_content_iner main_content_padding">
    <div class="dashboard_lg_card">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-6">

                    <div class="text-center py-5">

                        <div class="mb-4 text-warning" style="font-size: 60px;">
                            ✔
                        </div>

                        <h3 class="mb-3">
                            {{ __('Attendance Already Marked') }}
                        </h3>

                        <p class="mb-4">
                            {{ __('You have already marked your attendance for this session.') }}
                        </p>

                        <a href="{{ route('tenant.dashboard', request()->route('tenant_slug')) }}"
                           class="theme_btn">
                            {{ __('Back to Dashboard') }}
                        </a>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection