@extends('backend.master')

@section('mainContent')

{!! generateBreadcrumb() !!}

<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        <div class="white-box">

            <div class="row">
                <div class="col-lg-6">
                    <div class="main-title">
                        <h3>{{ __('Bulk Course Enrollment') }}</h3>
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

            <form id="bulkEnrollForm"
                    method="POST"
                    action="{{ route('bulk_enroll_upload', [
                            'tenant_slug' => request()->route('tenant_slug'),
                            'course_id' => $course->id
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
                                       name="file"
                                       id="filePlaceholder"
                                       placeholder="{{ __('Browse file') }}"
                                       readonly>
                                <input type="hidden" name="course_id" value="{{ $course->id}}">
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
                        <button type="submit" id="importEnrollBtn" class="primary-btn fix-gr-bg">
                            <i class="ti-check"></i>
                            {{ __('Import Enroll') }}
                        </button>
                    </div>
                </div>

            </form>

        </div>
    </div>
</section>

@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script type="text/javascript">
   
    let seatChecked = false;

    $(document).ready(function() {

        let seatChecked = false;

        $('#bulkEnrollForm').on('submit', function(e){

            if(seatChecked){
                return true;
            }

            e.preventDefault();

            let form = document.getElementById('bulkEnrollForm');
            let formData = new FormData(form);
            alert(formData.get('course_id')); // Debug: Check if course_id is correctly appended
            $.ajax({
                url: "{{ route('checkSeatsBasedOnCourse', request()->route('tenant_slug')) }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',

                success: function(response){
                    console.log(response);
                     alert(response.excelRows);
                    if(response.waitingUsers > 0){

                        if(confirm(
                            "Only "+response.remainingSeats+
                            " seats available. "+response.waitingUsers+
                            " users will move to waiting list. Continue?"
                        )){
                            seatChecked = true;
                            $('#bulkEnrollForm').submit();
                        }

                    }else{
                        seatChecked = true;
                        $('#bulkEnrollForm').submit();
                    }

                }
            });

        });

    });
</script>