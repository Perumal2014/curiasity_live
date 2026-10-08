@extends(theme('layouts.dashboard_master'))
@section('title')
    {{Settings('site_title')  ? Settings('site_title')  : 'Infix LMS'}} | {{__('frontend.Feedbacks')}}
@endsection
@section('css')
    <style>
        @media only screen and (min-width: 1440px) and (max-width: 1580px) {
            .course_badge_section .dashboard_badge_carousel {
                min-height: initial;
            }
        }

        .course_badge_section .dashboard_badge_carousel {
            min-height: initial;
        }

        .my_courses_wrapper .img-thumbnail{
            border: none;
        }

        .my_courses_wrapper .btn.theme_btn.small {
            font-size: 12px;
            padding: 8px;
            height: 32px;
            width: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .my_courses_wrapper .title h5 {
            font-size: 18px;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .my_courses_wrapper .course_badge_item {
            border: 1px solid #eff2f7;
            padding: 16px;
            border-radius: 10px;
        }
    </style>

@endsection
@section('js')
    <script>
        "use strict";

        function copyCurrentUrl(text, url) {
            var copyText = document.createElement("textarea");
            copyText.value = text + "\n" + url;
            document.body.appendChild(copyText);
            copyText.select();
            document.execCommand("copy");
            document.body.removeChild(copyText);
            toastr.success("{{__('frontend.Link Copied')}}");
        }

    </script>
@endsection

@section('mainContent')
    <div>
        <div class="main_content_iner main_content_padding">
            <div class="dashboard_lg_card">
                <div class="container-fluid g-0">
                    <div class="my_courses_wrapper">
                        <div class="row">
                            <div class="col-12">
                                <div
                                    class="section__title3 gap-3 d-flex flex-wrap align-items-center justify-content-between">
                                    <h3 class="mb-0">
                                        {{ __("frontend.Feedbacks") }}
                                    </h3>
                                    @php
                                        $user = auth()->user();
                                    @endphp
                                    <button class="theme_btn height_50"
                                            onclick="copyCurrentUrl('{{$user->username?route('profileUniqueUrl',$user->username):''}}?tab=course_badges')"
                                    >
                                        {{__('communication.Copy Link')}}
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="row d-flex align-items-center mt-3 row-gap-24">
                            @foreach($feedbackallocations as $feedbackallocation)

                                    <div class="col-sm-6 col-xl-4 col-xxl-3">
                                        <div class="course_badge_item text-center">

                                            <!-- Image (optional) -->
                                            

                                            <div class="title">

                                                <!-- Title -->
                                                <h5 class="mt-2">
                                                    {{ $feedbackallocation->feedback->feedback_name }}
                                                </h5>

                                                <!-- Description -->
                                                <!-- <p class="text-muted">
                                                    {{ \Illuminate\Support\Str::limit($feedbackallocation->feedback->feedback_description, 80) }}
                                                </p> -->

                                                <!-- View Button -->
                                                <a href="{{ route('userfeedback.form', ['id' => $feedbackallocation->id]) }}"
                                                class="btn theme_btn small">
                                                    View
                                                </a>

                                                <!-- Optional Share -->
                                               

                                            </div>
                                        </div>
                                    </div>
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
