
@extends('backend.master')
@push('styles')
    <style>
        .select2-container--default .select2-selection--single {
            background-color: #fff;
            width: 100%;
            height: 46px;
            line-height: 46px;
            font-size: 13px;
            padding: 3px 20px;
            padding-left: 20px;
            font-weight: 300;
            border-radius: 30px;
            color: var(--base_color);
            border: 1px solid #ECEEF4
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 46px;
            position: absolute;
            top: 1px;
            right: 20px;
            width: 20px;
            color: var(--text-color);
        }

        .select2-dropdown {
            background-color: white;
            border: 1px solid var(--backend-border-color);
            border-radius: 4px;
            box-sizing: border-box;
            display: block;
            position: absolute;
            left: -100000px;
            width: 100%;
            width: 100%;
            background: var(--bg_white);
            overflow: auto !important;
            border-radius: 0px 0px 10px 10px;
            margin-top: 1px;
            z-index: 9999 !important;
            border: 0;
            box-shadow: 0px 10px 20px rgb(108 39 255 / 30%);
            z-index: 1051;
            min-width: 200px;
        }

        .select2-search--dropdown .select2-search__field {
            padding: 4px;
            width: 100%;
            box-sizing: border-box;
            box-sizing: border-box;
            background-color: #fff;
            border: 1px solid rgba(130, 139, 178, 0.3) !important;
            border-radius: 3px;
            box-shadow: none;
            color: #333;
            display: inline-block;
            vertical-align: middle;
            padding: 0px 8px;
            width: 100% !important;
            height: 46px;
            line-height: 46px;
            outline: 0 !important;
        }

        .select2-container {
            width: 100% !important;
            min-width: 90px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: var(--dynamic-text-color);
            line-height: 40px;
        }


        .makeResize.responsiveResize:last-child.col-xl-6 {
            margin-top: 30px;
        }

        #durationBox {
            /*margin-top: 30px;*/
        }

        @media (max-width: 1199px) {
            .responsiveResize2 {
                margin-top: 30px;
            }
        }
        .filepond {
            border: 1px solid var(--backend-border-color);
        }

        .filepond--item{
            margin-left: 10px;
            top: 8px;
        }

    </style>
@endpush
@section('mainContent')
    @php
        if (@$course->discount_price != null) {
             $course_price = $course->discount_price;
         } else {
             $course_price = $course->price;
         }
    @endphp
    @php
        $LanguageList = getLanguageList();
    @endphp

    {!! generateBreadcrumb() !!}
    <section class="admin-visitor-area student-details">
        <div class="container-fluid p-0">
            <div class="white_box">

                <div class="row">

                    <div class="col-md-12 ">
                        <div class="main-title">
                            <h3 class="">
                                <a>User Course List</a>
                            </h3>
                        </div>
                        <div class="row pt-0">
                            <ul class="nav nav-tabs no-bottom-border  mt-sm-md-20 mb-10 ms-3" role="tablist">
                                
                                    <!-- <li class="nav-item" id="ongoingCoursesTab">
                                        <a class="nav-link {{ $tab=='ongoingCoursesTab' ? 'active' : '' }}">
                                            {{ __('courses.On-going Courses') }}
                                        </a>
                                    </li>

                                    <li class="nav-item" id="upcomingCoursesTab">
                                        <a class="nav-link {{ $tab=='upcomingCoursesTab' ? 'active' : '' }}">
                                            {{ __('courses.Upcoming Courses') }}
                                        </a>
                                    </li>

                                    <li class="nav-item" id="pastCoursesTab">
                                        <a class="nav-link {{ $tab=='pastCoursesTab' ? 'active' : '' }}">
                                            {{ __('courses.Past Courses') }}
                                        </a>
                                    </li> -->

                                    <li class="nav-item" id="ongoingCoursesTab">
                                        <a class="nav-link active">On-going Courses</a>
                                    </li>

                                    <li class="nav-item" id="upcomingCoursesTab">
                                        <a class="nav-link">Upcoming Courses</a>
                                    </li>

                                    <li class="nav-item" id="pastCoursesTab">
                                        <a class="nav-link">Past Courses</a>
                                    </li>
                                    
                            </ul>
                        </div>
                        

                        <div class="row tab-content-box" id="ongoingCoursesContent">
                            @include('coursesetting::_user_ongoing_courses')
                        </div>

                        <div class="row tab-content-box" id="upcomingCoursesContent" style="display:none">
                            @include('coursesetting::_user_upcoming_courses')
                        </div>

                        <div class="row tab-content-box" id="pastCoursesContent" style="display:none">
                            @include('coursesetting::_user_past_courses')
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

@endsection



@push('scripts')
    <script src="{{ asset('/') }}/Modules/CourseSetting/Resources/assets/js/course.js"></script>
    <script src="{{ asset('/') }}/Modules/CourseSetting/Resources/assets/js/advance_search.js"></script>
@endpush

