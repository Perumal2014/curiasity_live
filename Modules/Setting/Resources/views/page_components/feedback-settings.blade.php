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
            <div class="row" id="reminderWrapper">

                <!-- LEFT PANEL -->
                <div class="col-md-4 left-panel">
                    <h6 class="mb-3">Existing Reminders</h6>

                    <div id="reminderList">
                        @foreach($feedbacks as $i => $item)
                            <div class="reminder-item d-flex align-items-center gap-2" data-index="{{$i}}">

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
                    <div class="col-md-8" id="reminderSetting">
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
                                    <input type="hidden" name="course_id" value="">

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
                                        <!-- <i class="ti-trash text-danger delete-reminder" data-index="{{$reminder->id}}"></i> -->
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
                    <input type="hidden" id="course_id" class="form-control" name="course_id" value="">
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-primary" id="saveReminderBtn">Save</button>
                <span id="loader" class="ms-2 d-none">
                    <i class="fa fa-spinner fa-spin"></i> Saving...
                </span>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
@push('scripts')
    <script>
    // ✅ GLOBAL function (VERY IMPORTANT)
        function initReminderUI() {

            $('.reminder-form').addClass('d-none');
            $('.reminder-item').removeClass('active');

            let checkedRadio = $('.feedback-radio:checked');

            if (checkedRadio.length) {
                let item = checkedRadio.closest('.reminder-item');
                let index = item.data('index');

                item.addClass('active');
                $('#form_' + index).removeClass('d-none');
            }
        }

        $(document).ready(function () {

            // ✅ Initial load
            initReminderUI();

            // ✅ Open modal
            $(document).on('click', '.add-reminder', function () {
                $('#addReminderModal').modal('show');
            });

            // ✅ Save new reminder
            $('#saveReminderBtn').click(function () {

                let name = $('#feedback_name').val();
                let course_id = $('#course_id').val();

                if (name == '') {
                    alert('Please enter feedback name');
                    return;
                }

                $('#loader').removeClass('d-none');
                $('#saveReminderBtn').prop('disabled', true);

                $.ajax({
                    url: "{{ route('storeFeedback') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        feedback_name: name,
                        course_id: course_id
                    },
                    success: function () {

                        // ✅ Reload FULL UI instead of manual append
                        $('#reminderWrapper').load(location.href + ' #reminderWrapper > *', function () {
                            initReminderUI();
                        });

                        $('#addReminderModal').modal('hide');
                        $('#feedback_name').val('');
                    },
                    complete: function () {
                        $('#loader').addClass('d-none');
                        $('#saveReminderBtn').prop('disabled', false);
                    }
                });
            });

            // ✅ Click left item
            $(document).on('click', '.reminder-item', function () {

                let index = $(this).data('index');

                $('.reminder-item').removeClass('active');
                $(this).addClass('active');

                $('.reminder-form').addClass('d-none');
                $('#form_' + index).removeClass('d-none');

                // also check radio
                $(this).find('.feedback-radio').prop('checked', true).trigger('change');
            });

            // ✅ Radio change → update DB + reload UI
            $(document).on('change', '.feedback-radio', function () {

                let feedback_id = $(this).val();

                $.ajax({
                    url: "{{ route('setActiveFeedback') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        feedback_id: feedback_id
                    },
                    success: function () {

                        $('#reminderWrapper').load(location.href + ' #reminderWrapper > *', function () {
                            initReminderUI();
                        });

                    }
                });
            });

            // ✅ Save reminder (right side)
            $(document).on('click', '.save-reminder', function () {

                let icon = $(this);
                let form = icon.closest('.reminder-form');

                icon.removeClass('ti-check text-success')
                    .addClass('fa fa-spinner fa-spin text-primary');

                let data = {
                    _token: "{{ csrf_token() }}",
                    feedback_id: form.find('[name="feedback_id"]').val(),
                    course_id: form.find('[name="course_id"]').val(),
                    when: form.find('[name="when"]').val(),
                    days_after: form.find('[name="days_after"]').val(),
                    recurrence: form.find('[name="recurrence"]').val(),
                    for_days: form.find('[name="for_days"]').val()
                };

                $.ajax({
                    url: "{{ route('ajaxSaveReminder') }}",
                    type: "POST",
                    data: data,
                    success: function () {
                         icon.removeClass('fa fa-spinner fa-spin text-primary')
                            .addClass('ti-check text-success');

                        setTimeout(() => {
                            icon.removeClass('ti-check text-success')
                                .addClass('ti-check text-success'); // reset (optional)
                        }, 1500);

                        $('#liveToast .toast-body').text('Reminder updated successfully ✅');

                        new bootstrap.Toast(document.getElementById('liveToast')).show();
                    },
                    error: function () {

                        icon.removeClass('fa fa-spinner fa-spin text-primary')
                            .addClass('ti-close text-danger');

                        new bootstrap.Toast(document.getElementById('errorToast')).show();
                    }
                });
            });

        });

        $(document).on('click', '.delete-reminder', function () {

            let icon = $(this);
            let feedback_id = icon.data('id');
            alert(feedback_id);
            if (!confirm('Are you sure you want to delete this reminder?')) {
                return;
            }

            // 🔄 Show loader on icon
            icon.removeClass('ti-trash text-danger')
                .addClass('fa fa-spinner fa-spin text-primary');

            $.ajax({
                url: "{{ route('deleteFeedback') }}", // create this route
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    feedback_id: feedback_id
                },
                success: function (res) {

                    // ✅ Reload full UI
                    $('#reminderWrapper').load(location.href + ' #reminderWrapper > *', function () {
                        initReminderUI();
                    });

                    // ✅ Success toast
                    $('#liveToast .toast-body').text('Reminder deleted successfully 🗑️');
                    new bootstrap.Toast(document.getElementById('liveToast')).show();
                },
                error: function () {

                    // ❌ Restore icon if failed
                    icon.removeClass('fa fa-spinner fa-spin text-primary')
                        .addClass('ti-trash text-danger');

                    new bootstrap.Toast(document.getElementById('errorToast')).show();
                }
            });

        });
    </script>
<div class="position-fixed top-0 end-0 p-3" style="z-index: 9999">

    <div id="liveToast"
         class="toast align-items-center text-white bg-success border-0"
         role="alert"
         data-bs-delay="2000">

        <div class="d-flex">
            <div class="toast-body">
                Saved successfully ✅
            </div>

            <button type="button"
                class="btn-close btn-close-white me-2 m-auto"
                data-bs-dismiss="toast">
            </button>
        </div>

    </div>

</div>
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

