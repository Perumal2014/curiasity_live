@extends(theme('layouts.dashboard_master'))

@section('mainContent')
<div class="main_content_iner main_content_padding">
    <div class="dashboard_lg_card">
        <div class="container-fluid">
            <div class="text-center py-5">

                <h3 class="mb-4">Confirm Attendance</h3>

                <form method="POST"
                    action="{{ route('attendance.sessions.qrSubmit', [
                        'tenant_slug' => request()->route('tenant_slug'),
                        'id' => $session->id
                    ]) }}">
                    @csrf

                    <input type="hidden" name="status" value="present">

                    <button type="submit" class="theme_btn">
                        Mark Present
                    </button>

                </form>

            </div>
        </div>
    </div>
</div>
@endsection