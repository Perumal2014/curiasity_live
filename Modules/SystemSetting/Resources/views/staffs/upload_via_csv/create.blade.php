@extends('backend.master')
@push('css')
    <style>
        .red {
            color: red;
            font-size: 13px;
        }
    </style>
@endpush
@section('mainContent')
    <div id="add_product">
        <section class="admin-visitor-area up_st_admin_visitor">
            <div class="container-fluid p-0">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <div class="white-box">

                            <div class="box_header d-block">
                                <div class="main-title d-flex flex-wrap">
                                    <h3 class="mb-0 mr-30 text-nowrap">{{ __('leave.Upload Staff Via CSV')}}</h3>
                                    <div class="d-flex justify-content-end align-items-center  flex-wrap flex-grow-1">
                                        <ul class="d-flex flex-wrap">
                                            <li><a download class="primary-btn radius_30px   fix-gr-bg"
                                                   href="{{ asset('public/staff_sample.xlsx') }}"><i
                                                        class="ti-import"></i>Sample File Download</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <form method="POST"
                                  enctype="multipart/form-data" class="csvForm">
                                @csrf
                                <div class="row form">
                                    <div class="col-xl-12 col-lg-12 col-md-12">
                                        <div class="primary_input mb-15">
                                            <div class="primary_file_uploader">
                                                <input class="primary-input" type="text" id="placeholderFileOneName"
                                                       placeholder="{{__('setting.Browse file')}}" readonly="">
                                                <button class="" type="button">
                                                    <label class="primary-btn small fix-gr-bg"
                                                           for="document_file_1">{{__("common.Browse")}} </label>
                                                    <input type="file" class="d-none" accept=".xlsx, .xls, .csv"
                                                           name="file" id="document_file_1">
                                                </button>
                                            </div>
                                            <span class="red">{{$errors->first('file')}}</span>
                                        </div>
                                    </div>
                                    <div class="col-xl-12 col-lg-12 col-md-12">
                                        <div class="primary_input mb-15">
                                            <label class="primary_input_label red" for="">Please download the sample
                                                file input your desire information then upload. Don't try to upload
                                                different file format and information</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="submit_btn text-center" id="submit_button_parent">
                                        <button class="primary-btn semi_large2 fix-gr-bg csvFormBtn" type="submit"><i
                                                class="ti-check"></i>{{__('leave.Upload CSV')}}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@push("scripts")
    <script type="text/javascript">
        $(document).ready(function () {
            $(".csvFormBtn").attr("disabled", false);
        });
        $(".csvFormBtn").on("click", function () {
            $(".csvForm").submit();
            $(".csvFormBtn").attr("disabled", true);
        });

        $(document).on('submit', '.csvForm', function (e) {

            e.preventDefault();

            let form = this;
            let formData = new FormData(form);
            let button = $('.csvFormBtn');

            button.prop('disabled', true);

            // STEP 1: Validate Excel
            $.ajax({
                url: "{{ route('staffs.csv_upload_staff_validate') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,

                success: function (response) {
                    // $(".csvFormBtn").attr("disabled", false);
                    $("#submit_button_parent").html(
                        '<button class="primary-btn semi_large2 fix-gr-bg csvFormBtn" type="submit"><i class="ti-check"></i>{{__("leave.Upload CSV")}}</button>'
                    );
                    // Duplicate found
                    if (response.status && response.has_duplicates) {

                        button.prop('disabled', false);

                        let message = '';

                        if (
                            response.duplicate_emails &&
                            response.duplicate_emails.length > 0
                        ) {
                            message += '<strong>Duplicate Emails:</strong><br>';

                            response.duplicate_emails.forEach(function (item) {
                                message +=
                                    'Row ' + item.row +
                                    ': <b>' + item.value + '</b>' +
                                    ' - ' + item.reason +
                                    '<br>';
                            });
                        }

                        if (
                            response.duplicate_phones &&
                            response.duplicate_phones.length > 0
                        ) {
                            message += '<br><strong>Duplicate Phones:</strong><br>';

                            response.duplicate_phones.forEach(function (item) {
                                message +=
                                    'Row ' + item.row +
                                    ': <b>' + item.value + '</b>' +
                                    ' - ' + item.reason +
                                    '<br>';
                            });
                        }

                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'error',
                            title: 'Duplicate Records Found',
                            html: message,
                            showConfirmButton: false,
                            timer: 7000,
                            timerProgressBar: true
                        });

                        return;
                    }

                    // =================================
                    // NO DUPLICATES
                    // NOW IMPORT USING AJAX
                    // =================================

                    $.ajax({
                        url: "{{ route('staffs.csv_upload_staff_store') }}",
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,

                        success: function (response) {

                            button.prop('disabled', false);

                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message ??
                                    'Staff imported successfully.'
                            });

                            // Clear file
                            form.reset();

                        },

                        error: function (xhr) {

                            button.prop('disabled', false);

                            let message =
                                'Staff import failed.';

                            if (
                                xhr.responseJSON &&
                                xhr.responseJSON.message
                            ) {
                                message = xhr.responseJSON.message;
                            }

                            Swal.fire({
                                icon: 'error',
                                title: 'Import Failed',
                                text: message
                            });
                        }
                    });
                },

                error: function (xhr) {

                    button.prop('disabled', false);

                    let message =
                        'Unable to validate the uploaded file.';

                    if (
                        xhr.responseJSON &&
                        xhr.responseJSON.message
                    ) {
                        message = xhr.responseJSON.message;
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Failed',
                        text: message
                    });
                }
            });
        });
    </script>
@endpush
