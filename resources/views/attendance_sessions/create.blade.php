@extends('backend.master')

@section('mainContent')

{!! generateBreadcrumb() !!}

<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        <div class="white-box">

            <!-- Header -->
            <div class="row justify-content-center mb-20">
                <div class="col-12">
                    <div class="box_header common_table_header d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">
                            {{ __('Create Attendance Session') }}
                        </h3>

                        <a href="{{ route('attendance.sessions.index', request()->route('tenant_slug')) }}"
                           class="primary-btn small tr-bg">
                            {{ __('Back to Sessions') }}
                        </a>
                    </div>
                </div>
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Form -->
            <form method="POST"
                  action="{{ route('attendance.sessions.store', [
                        'tenant_slug' => request()->route('tenant_slug')
                  ]) }}">
                @csrf

                <div class="row">

                    <!-- Course -->
                    <div class="col-lg-6 mb-3">
                        <label class="primary_input_label">
                            {{ __('Select Course') }} <span class="text-danger">*</span>
                        </label>

                        <select name="course_id"
                                class="primary_select @error('course_id') is-invalid @enderror"
                                required>
                            <option value="">
                                {{ __('-- Select Course --') }}
                            </option>

                            @foreach($courses as $course)
                                <option value="{{ $course->id }}"
                                    {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                    {{ $course->title ?? $course->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('course_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Session Date -->
                    <div class="col-lg-6 mb-3">
                        <label class="primary_input_label">
                            {{ __('Session Date') }} <span class="text-danger">*</span>
                        </label>

                        <input type="date"
                               name="session_date"
                               value="{{ old('session_date') }}"
                               class="primary_input_field @error('session_date') is-invalid @enderror"
                               required>

                        @error('session_date')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Start Time -->
                    <div class="col-lg-6 mb-3">
                        <label class="primary_input_label">
                            {{ __('Start Time') }} <span class="text-danger">*</span>
                        </label>

                        <input type="time"
                               name="start_time"
                               value="{{ old('start_time') }}"
                               class="primary_input_field @error('start_time') is-invalid @enderror"
                               required>

                        @error('start_time')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- End Time -->
                    <div class="col-lg-6 mb-3">
                        <label class="primary_input_label">
                            {{ __('End Time') }} <span class="text-danger">*</span>
                        </label>

                        <input type="time"
                               name="end_time"
                               value="{{ old('end_time') }}"
                               class="primary_input_field @error('end_time') is-invalid @enderror"
                               required>

                        @error('end_time')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Type -->
                    <div class="col-lg-6 mb-3">
                        <label class="primary_input_label">
                            {{ __('Session Type') }}
                        </label>

                        <select name="type" class="primary_select">
                            <option value="face_to_face"
                                {{ old('type') == 'face_to_face' ? 'selected' : '' }}>
                                {{ __('Face to Face') }}
                            </option>

                            <option value="virtual"
                                {{ old('type') == 'virtual' ? 'selected' : '' }}>
                                {{ __('Virtual') }}
                            </option>
                        </select>
                    </div>

                </div>

                <!-- Submit Button -->
                <div class="row mt-30">
                    <div class="col-lg-12 text-end">
                        <button type="submit"
                                class="primary-btn fix-gr-bg">
                            {{ __('Create Session') }}
                        </button>
                    </div>
                </div>

            </form>

        </div>
    </div>
</section>

@endsection
