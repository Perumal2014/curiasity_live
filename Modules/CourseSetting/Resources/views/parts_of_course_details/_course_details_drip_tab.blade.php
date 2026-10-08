<style>
    .reminder-container {
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        background: #fff;
    }

    .left-panel {
        border-right: 1px solid #e5e7eb;
        min-height: 350px;
    }

    .reminder-item {
        padding: 10px 12px;
        cursor: pointer;
        border-radius: 4px;
    }

    .reminder-item:hover {
        background: #f3f4f6;
    }

    .reminder-item.active {
        background: #e6f0ff;
        font-weight: 500;
    }

    .add-reminder {
        color: #2563eb;
        cursor: pointer;
        font-size: 14px;
    }

    .right-header {
        background: #3b82f6;
        color: #fff;
        padding: 8px 12px;
        font-size: 14px;
        border-radius: 4px 4px 0 0;
    }

    .form-section {
        padding: 15px;
    }

    .action-icons {
        text-align: right;
    }

    .action-icons i {
        cursor: pointer;
        margin-left: 10px;
    }
</style>

<div class="QA_section QA_section_heading_custom check_box_table  pt-20">
    <div class="QA_table ">
        <div class="reminder-container p-3">
            <div class="row">

                <!-- LEFT PANEL -->
                <div class="col-md-4 left-panel">
                    <h6 class="mb-3">Existing Reminders</h6>

                    <div id="reminderList">
                        @foreach($feedbacks as $i => $item)
                            <div class="reminder-item d-flex align-items-center" data-index="{{$i}}">

                                <input type="radio"
                                    name="active_feedback"
                                    value="{{$item->id}}" class="feedback-radio"
                                    {{ $item->status == 1 || $loop->first ? 'checked' : '' }}>

                                <span>{{$item->feedback_name}}</span>

                            </div>
                        @endforeach
                    </div>

                    <div class="mt-3">
                        <span class="add-reminder">+ Add New Reminder</span>
                    </div>
                </div>

                <!-- RIGHT PANEL -->
                <div class="col-md-8">
                    <div class="right-header">
                        Reminder Setting
                    </div>

                    @foreach($feedbacks as $i => $feedback)

                    @php
                        $reminder = $feedback->reminders;
                    @endphp
                    <div class="reminder-form d-none" id="form_{{$i}}">
                        <div class="form-section">

                            <form method="POST">

                                <input type="hidden" name="feedback_id" value="{{$feedback->id}}">
                                <input type="hidden" name="course_id" value="{{ $course->id }}">

                                <!-- When -->
                                <div class="mb-3">
                                    <label class="form-label">When to send</label>
                                    <select class="form-control" name="when">
                                        <option value="after_completion"
                                            {{ ($reminder->when_to_send ?? '') == 'after_completion' ? 'selected' : '' }}>
                                            After Course completion
                                        </option>
                                    </select>
                                </div>

                                <!-- Days -->
                                <div class="mb-3">
                                    <label class="form-label">Days after</label>
                                    <input type="number" class="form-control"
                                        name="days_after"
                                        value="{{ $reminder->days_after_completion ?? '' }}">
                                </div>

                                <!-- Recurrence -->
                                <div class="mb-3">
                                    <label class="form-label">Recurrence</label>
                                    <select class="form-control" name="recurrence">
                                        <option value="daily"
                                            {{ ($reminder->recurrence ?? '') == 'daily' ? 'selected' : '' }}>
                                            Every day
                                        </option>
                                    </select>
                                </div>

                                <!-- For -->
                                <div class="mb-3">
                                    <label class="form-label">For</label>
                                    <div class="d-flex">
                                        <input type="number" class="form-control me-2"
                                            name="for_days"
                                            value="{{ $reminder->for_days ?? '' }}">
                                        <span class="mt-2">days</span>
                                    </div>
                                </div>

                                <!-- Icons -->
                                <div class="action-icons">
                                    <i class="ti-trash text-danger delete-reminder"></i>
                                    <i class="ti-check text-success save-reminder" data-index="{{$i}}"></i>
                                </div>

                            </form>

                        </div>
                    </div>

                    @endforeach
                </div>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addReminderModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Add Feedback</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3">
                    <label>Feedback Name</label>
                    <input type="text" id="feedback_name" class="form-control" name="feedback_name">
                    <input type="hidden" id="course_id" class="form-control" name="course_id" value="{{ $course->id ?? 0}}">
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-primary" id="saveReminderBtn">Save</button>
            </div>

        </div>
    </div>
</div>

<div class="position-fixed top-0 end-0 p-3" style="z-index: 9999">
    <div id="liveToast" class="toast align-items-center text-white bg-success border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body">
                Saved successfully ✅
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

<div id="errorToast" class="toast align-items-center text-white bg-danger border-0">
    <div class="d-flex">
        <div class="toast-body">
            Something went wrong ❌
        </div>
    </div>
</div>

@push('scripts')
    <script>
        {{--        document on ready--}}
        $(document).ready(function () {
            $('.drip_type').on('change', function () {
                let type = $(this).val();
                let row = $(this).closest('tr');
                if (type == 1) {
                    row.find('.dripDate').removeClass('d-none');
                    row.find('.dripDays').addClass('d-none');
                } else {
                    row.find('.dripDate').addClass('d-none');
                    row.find('.dripDays').removeClass('d-none');
                }
            });
        })

        $(document).ready(function () {
            $('.reminder-item:first').addClass('active');
            $('.reminder-form:first').removeClass('d-none');

            $('.add-reminder').click(function () {
                $('#addReminderModal').modal('show');
            });

            $('#saveReminderBtn').click(function () {

                let name = $('#feedback_name').val();
                let course_id = $('#course_id').val();

                if (name == '') {
                    alert('Please enter feedback name');
                    return;
                }

                $.ajax({
                    url: "{{ route('storeFeedback') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        feedback_name: name,
                        course_id: course_id
                    },
                    success: function (res) {

                        // Append new item LEFT
                        let index = $('.reminder-item').length;

                        $('#reminderList').append(`
                            <div class="reminder-item" data-index="${index}">
                                ${res.feedback_name}
                            </div>
                        `);

                        // Append new FORM RIGHT
                        $('#feedbackForms').append(`
                            <div class="reminder-form d-none" id="form_${index}">
                                <div class="form-section">

                                    <input type="hidden" name="id[]" value="${res.id}">

                                    <div class="mb-3">
                                        <label>When to send</label>
                                        <select class="form-control" name="when[${index}]">
                                            <option value="after_completion">After Course completion</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label>Days after</label>
                                        <input type="number" class="form-control" name="days_after[${index}]">
                                    </div>

                                    <div class="mb-3">
                                        <label>Recurrence</label>
                                        <select class="form-control" name="recurrence[${index}]">
                                            <option value="daily">Every day</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label>For</label>
                                        <input type="number" class="form-control" name="for_days[${index}]">
                                    </div>

                                </div>
                            </div>
                        `);

                        // Close modal
                        $('#addReminderModal').modal('hide');
                        $('#feedback_name').val('');

                    }
                });

            });
        });

        $(document).ready(function () {

            // Default load first
            $('.reminder-item:first').addClass('active');
            $('.reminder-form:first').removeClass('d-none');

            $('.feedback-radio').on('change', function () {
                $('.reminder-item').removeClass('active');
                $(this).closest('.reminder-item').addClass('active');
            });

            // Click event
            $(document).on('click', '.reminder-item', function () {

                let index = $(this).data('index');

                console.log("Clicked index:", index); // debug

                // Highlight selected item
                $('.reminder-item').removeClass('active');
                $(this).addClass('active');

                // Hide all forms
                $('.reminder-form').addClass('d-none');

                // Show selected form
                $('#form_' + index).removeClass('d-none');

            });

        });

        $(document).on('click', '.save-reminder', function () {

            let icon = $(this);
            let form = icon.closest('.reminder-form');

            // 👉 Replace icon with loader
            icon.removeClass('ti-check text-success')
                .addClass('fa fa-spinner fa-spin text-primary');

            let feedback_id = form.find('[name="feedback_id"]').val();
            let course_id = form.find('[name="course_id"]').val();
            let when = form.find('[name="when"]').val();
            let days_after = form.find('[name="days_after"]').val();
            let recurrence = form.find('[name="recurrence"]').val();
            let for_days = form.find('[name="for_days"]').val();

            $.ajax({
                url: "{{ route('ajaxSaveReminder') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    feedback_id,
                    course_id,
                    when,
                    days_after,
                    recurrence,
                    for_days
                },
                success: function () {

                    // 👉 Show success tick again
                    icon.removeClass('fa fa-spinner fa-spin text-primary')
                        .addClass('ti-check text-success');
                    
                    let toast = new bootstrap.Toast(document.getElementById('liveToast'));
                    toast.show();

                },
                error: function () {

                    // 👉 Show error state
                    icon.removeClass('fa fa-spinner fa-spin text-primary')
                        .addClass('ti-close text-danger');

                     let toast = new bootstrap.Toast(document.getElementById('errorToast'));
                        toast.show();

                }
            });

        });

        $(document).on('change', '.feedback-radio', function () {

            let feedback_id = $(this).val();
            let course_id = "{{ $course->id }}";

            $.ajax({
                url: "{{ route('setActiveFeedback') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    feedback_id: feedback_id,
                    course_id: course_id
                },
                success: function () {
                    console.log('Updated successfully');
                }
            });

        });
    </script>
@endpush



 <!-- <div id="reminderList">
                        @if($feedbacks)
                            @foreach($feedbacks as $i => $item)
                                <div class="reminder-item"
                                    data-index="{{$i}}">
                                    {{$item->title}}
                                </div>
                            @endforeach
                        @endif
                    </div> -->

