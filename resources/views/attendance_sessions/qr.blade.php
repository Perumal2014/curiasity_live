@extends('backend.master')

@section('mainContent')
<div class="container mt-4">
    <div class="white-box text-center">

        <h3>Attendance QR</h3>

        <p><strong>Course:</strong> {{ $session->course->title ?? '' }}</p>
        <p><strong>Expires At:</strong> {{ $session->qr_expires_at }}</p>

        <div class="mt-4">
            {!! QrCode::format('svg')
                ->size(300)
                ->errorCorrection('H')
                ->generate($qrUrl) !!}
        </div>

        <div class="mt-3">
            <small>This QR will expire automatically.</small>
        </div>

    </div>
</div>
@endsection