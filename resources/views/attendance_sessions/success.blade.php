@extends(theme('layouts.dashboard_master'))

@section('title')
    {{ __('Attendance Success') }}
@endsection

@section('mainContent')
<div class="main_content_iner main_content_padding">
    <div class="dashboard_lg_card">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-6">

                    <div class="text-center py-5">

                        <div class="mb-4">
                            <i class="fas fa-check-circle text-success" style="font-size: 60px;"></i>
                        </div>

                        <h3 class="mb-3">
                            {{ __('Attendance Marked Successfully!') }}
                        </h3>

                        <p class="mb-4">
                            {{ __('Your attendance has been recorded as Present.') }}
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