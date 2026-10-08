@extends('backend.master')

@section('mainContent')

{!! generateBreadcrumb() !!}

<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        <div class="white-box">

            <div class="row">
                <div class="col-lg-6">
                    <div class="main-title">
                        <h3>{{ __('Bulk Upload Attendance') }}</h3>
                    </div>
                </div>

                <!-- <div class="col-lg-6 text-end mb-20 d-flex gap-10 flex-wrap justify-content-end">
                    <a href="#">
                        <button class="primary-btn tr-bg text-uppercase bord-rad">
                            {{ __('Sample Download') }}
                            <span class="pl ti-download"></span>
                        </button>
                    </a>
                </div> -->
            </div>

            
            <div class="row mt-20">
                <div class="col-lg-12">
                    <div class="alert alert-info">
                        <strong>{{ __('Session') }}:</strong>
                        {{ $session->lesson->name ?? '' }}
                        |
                        {{ $session->lesson->start_date }}
                        |
                        {{ $session->lesson->start_time }} - {{ $session->lesson->end_time }}
                    </div>
                </div>
            </div>

            <form method="POST"
                  action="{{ route('attendance.sessions.import', [
                        'tenant_slug' => request()->route('tenant_slug'),
                        'id' => $session->id
                  ]) }}"
                  enctype="multipart/form-data">
                @csrf

                <div class="row mt-30">

                    <!-- File Upload -->
                    <div class="col-lg-12 ">
                        <div class="primary_input mb-35">
                            <label class="primary_input_label">
                                {{ __('Browse CSV/Excel') }}
                                <strong class="text-danger">*</strong>
                            </label>

                            <div class="primary_file_uploader">
                                <input class="primary-input"
                                       type="text"
                                       id="filePlaceholder"
                                       placeholder="{{ __('Browse file') }}"
                                       readonly>

                                <button type="button">
                                    <label class="primary-btn small fix-gr-bg"
                                           for="attendance_file">
                                        {{ __('Browse') }}
                                    </label>

                                    <input type="file"
                                           class="d-none"
                                           name="file"
                                           id="attendance_file"
                                           required
                                           onchange="document.getElementById('filePlaceholder').value = this.files[0].name">
                                </button>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Submit -->
                <div class="row">
                    <div class="col-lg-12 d-flex justify-content-center">
                        <button type="submit" class="primary-btn fix-gr-bg">
                            <i class="ti-check"></i>
                            {{ __('Import Attendance') }}
                        </button>
                    </div>
                </div>

            </form>

        </div>
    </div>
</section>

@endsection