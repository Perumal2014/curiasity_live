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
                            {{ __('Mark Attendance') }}
                        </h3>

                        <a href="{{ route('attendance.sessions', ['tenant_slug' => request()->route('tenant_slug'), 'course' => $session->course_id ]) }}"

                           class="primary-btn small fix-gr-bg">
                            {{ __('Back to Sessions') }}
                        </a>
                    </div>
                </div>
            </div>

            <!-- Session Info -->
            <div class="row mb-30">
                <div class="col-lg-12">
                    <div class="primary_input mb-3">
                        <strong>{{ __('Session') }}:</strong>
                        {{ $session->lesson->name ?? '' }}
                        &nbsp;&nbsp;&nbsp;
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

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Attendance Table -->
            <form method="POST"
                  action="{{ route('attendance.sessions.save', [
                        'tenant_slug' => request()->route('tenant_slug'),
                        'id' => $session->id
                  ]) }}">
                @csrf

                <div class="QA_section QA_section_heading_custom check_box_table">
                    <div class="QA_table">
                        <div class="mb-3 d-flex gap-2">
                            <button type="button" id="markPresentBtn"  class="primary-btn small fix-gr-bg d-none">
                                {{ __('Mark Selected Attended') }}
                            </button>
                            
                            <button type="button"  id="markAbsentBtn"  class="primary-btn small tr-bg d-none">
                                {{ __('Mark Selected Not Attended') }}
                            </button>
                        </div>

                        <table class="table Crm_table_active3">
                            <thead>
                                <tr>
                                    <th>
                                        <label class="primary_checkbox d-flex mr-12">
                                            <input id="selectAll" type="checkbox">
                                            <span class="checkmark"></span>
                                        </label>
                                    </th>
                                    <th>{{ __('SL') }}</th>
                                    <th>{{ __('Student Name') }}</th>
                                    <th>{{ __('Status') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $key => $user)

                                    @php
                                        $status = $existingAttendance[$user->id]->status ?? null;
                                    @endphp

                                    <tr>
                                        <td>
                                            <label class="primary_checkbox d-flex mr-12">
                                                <input type="checkbox" class="attendanceCheckbox" value="{{ $user->id }}">
                                                <span class="checkmark"></span>
                                            </label>
                                        </td>

                                        <td>{{ $key + 1 }}</td>

                                        <td>{{ $user->name }}</td>

                                        <td>
                                            <select name="attendance[{{ $user->id }}]"
                                                    class="primary_select attendance-status">
                                                <option value="Attended"
                                                    {{ $status == 'Attended' ? 'selected' : '' }}>
                                                    {{ __('Attended') }}
                                                </option>

                                                <option value="Not Attended"
                                                    {{ $status == 'Not Attended' ? 'selected' : '' }}>
                                                    {{ __('Not Attended') }}
                                                </option>

                                              
                                            </select>
                                        </td>
                                    </tr>

                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="row mt-30">
                    <div class="col-lg-12 text-end">
                        <button type="submit"
                                class="primary-btn fix-gr-bg">
                            {{ __('Save Attendance') }}
                        </button>
                    </div>
                </div>

            </form>

        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
$(document).ready(function () {

    // Select All
    $(document).on('click', '#selectAll', function () {

        $('.attendanceCheckbox').prop('checked', this.checked);

        toggleBulkButtons();
    });

    // Individual checkbox
    $(document).on('click', '.attendanceCheckbox', function () {

        $('#selectAll').prop(
            'checked',
            $('.attendanceCheckbox').length === $('.attendanceCheckbox:checked').length
        );

        toggleBulkButtons();
    });

    function toggleBulkButtons() {

        if ($('.attendanceCheckbox:checked').length > 0) {

            $('#markPresentBtn').removeClass('d-none');
            $('#markAbsentBtn').removeClass('d-none');

        } else {

            $('#markPresentBtn').addClass('d-none');
            $('#markAbsentBtn').addClass('d-none');

        }

    }



});

$('#markPresentBtn').click(function () {

    $('.attendanceCheckbox:checked').each(function () {

        let select = $(this).closest('tr').find('select.attendance-status');

        select.val('Attended').change();

        // Update Nice Select UI
        select.next('.nice-select').find('.current').text('Attended');

        // Update selected option
        select.next('.nice-select')
              .find('li')
              .removeClass('selected');

        select.next('.nice-select')
              .find('li[data-value="Attended"]')
              .addClass('selected');

    });

});

$('#markAbsentBtn').click(function () {

    $('.attendanceCheckbox:checked').each(function () {

        let select = $(this).closest('tr').find('select.attendance-status');

        select.val('Not Attended').change();

        select.next('.nice-select').find('.current').text('Not Attended');

        select.next('.nice-select')
              .find('li')
              .removeClass('selected');

        select.next('.nice-select')
              .find('li[data-value="Not Attended"]')
              .addClass('selected');

    });

});

</script>
@endpush
