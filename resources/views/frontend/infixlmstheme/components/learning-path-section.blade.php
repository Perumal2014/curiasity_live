<meta name="csrf-token" content="{{ csrf_token() }}">
<div>
    <style>
        .class_tags {
            position: absolute;
            z-index: 1;
            top: 0;
            right: 0;
        }

        @media (max-width: 991px){
            .input-group.theme_search_field {
                width: 80%!important;
            }
        }

        @media (max-width: 767px){
            .input-group.theme_search_field {
                width: 60%!important;
                float: initial!important;
                margin: 0 auto;
            }
        }

        @media (max-width: 576px){
            .input-group.theme_search_field {
                width: 90%!important;
                float: initial!important;
                margin: 0 auto;
            }
        }

    </style>

    <style>
    .step-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 14px 18px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }

    .step-card:hover {
        border-color: #4f46e5;
        box-shadow: 0 4px 14px rgba(79, 70, 229, 0.12);
    }

    .step-toggle-icon {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: 0.3s;
    }

    .step-card.active .step-toggle-icon i {
        transform: rotate(180deg);
    }

    .step-toggle-icon i {
        transition: 0.3s ease;
    }

    .mini-course-card {
        background: #fff;
        border: 1px solid #ececec;
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s ease;
        height: 100%;
        box-shadow: 0 2px 10px rgba(0,0,0,0.04);
    }

    .mini-course-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    }

    .mini-course-image img {
        width: 100%;
        height: 140px;
        object-fit: cover;
    }

    .mini-course-body {
        padding: 12px;
    }

    .mini-course-title {
        display: block;
        font-size: 15px;
        font-weight: 600;
        color: #111827;
        margin-bottom: 8px;
        text-decoration: none;
        line-height: 1.4;
    }

    .mini-course-title:hover {
        color: #4f46e5;
    }

    .mini-course-desc {
        font-size: 13px;
        color: #6b7280;
        min-height: 38px;
        margin-bottom: 10px;
    }

    .mini-progress {
        height: 6px;
        border-radius: 20px;
        background: #e5e7eb;
    }

    .mini-progress .progress-bar {
        border-radius: 20px;
    }
</style>
    <div class="main_content_iner main_content_padding">
        <div class="dashboard_lg_card">
            <div class="container-fluid g-0">
                <div class="my_courses_wrapper">
                    <div class="row">
                        <div class="col-12">
                            <div class="section__title3">
                                <h3>
                                    @if (routeIs('myClasses'))
                                        {{ __('courses.Live Class') }}
                                    @elseif(routeIs('myQuizzes'))
                                        {{ __('courses.My Quizzes') }}
                                    @elseif(routeIs('myCompletedCourses'))
                                        {{ __('Completed Courses') }}  
                                    @elseif(routeIs('PrivateCourses'))
                                        {{ __('Private Courses') }}
                                    @elseif(routeIs('learningPath'))
                                        {{ __('Learning Paths') }}
                                    @else
                                        {{ __('courses.My Courses') }}
                                    @endif
                                </h3>
                            </div>
                        </div>

                        @php
                            if (routeIs('learningPath')) {
                                $search_text = trans('frontend.Search Learning Paths');
                                $search_route = '';
                            } 
                        @endphp
                    </div>
                    <div class="row d-flex align-items-center mb-4 mb-lg-5">
                        <div class="col-xl-6 col-md-6 col-sm-12 mt-3">
                            <div class="short_select d-flex align-items-center pt-0 pb-3">
                                <h5 class="mr_10 font_16 f_w_500 mb-0">{{ __('frontend.Filter By') }}:</h5>
                                <input type="hidden" id="siteUrl" value="{{ route(\Request::route()->getName()) }}">
                                <select class="theme_select my-course-select w-50" id="categoryFilter">
                                    <option value="" data-display="{{ __('frontend.All Categories') }}">
                                        {{ __('frontend.All Categories') }}</option>
                                    @foreach ($categories->where('parent_id',0) as $category)
                                        @include('backend.categories._single_select_option',['category'=>$category,'level'=>1])

                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class=" col-xl-6 col-md-6 pb-3 col-sm-12  mt-3">
                            <form action="{{ route(\Request::route()->getName()) }}">
                                <div class="input-group theme_search_field pt-0 pb-3 float-end w-50">
                                    <div class="input-group-prepend">
                                        <button class="btn" type="button" id="button-addon1"><i class="ti-search"></i>
                                        </button>
                                    </div>

                                    <input type="text" id="courseSearch" class="form-control course_search_option" name="search"
                                           placeholder="{{ $search_text }}" value="{{ $search }}"
                                           onfocus="this.placeholder = ''"
                                           onblur="this.placeholder = '{{ $search_text }}'">

                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="row row-gap-24" id="courseList">
                        @if(isset($courses) && $courses->count())
                            @foreach($courses as $learningPath)
                                {{-- Loop over steps safely --}}
                                @foreach($learningPath->steps ?? [] as $step)
                                    <div class="col-12 mb-3">
                                        <div class="group-header" style="cursor:pointer; padding:10px; background:#f5f5f5; border-radius:6px;" data-step-id="{{ $step->id }}">
                                            <strong>{{ $step->step_title }}</strong>
                                            <span class="toggle-icon" style="float:right;">+</span>
                                        </div>

                                        <div class="group-courses mt-2" id="group-{{ $step->id }}" style="display:none;">
                                            <div class="row row-gap-24">
                                                @foreach($step->plancourses ?? [] as $planCourse)
                                                    @if($planCourse->course)
                                                        @php $course = $planCourse->course; @endphp
                                                        <div class="col-xl-4 col-md-6">
                                                            <div class="course-item">
                                                                <div class="course-item-img">
                                                                    <img src="{{ getCourseImage($course->thumbnail) }}" alt="thumb image">
                                                                </div>
                                                                <div class="course-item-info">
                                                                    <a href="{{ route('continueCourse', [$course->slug]) }}" class="title">
                                                                        {{ $course->title }}
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endforeach
                        @else
                            <div class="col-12 text-center">
                                <p>{{ __('student.No Learning Paths Found!') }}</p>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@if(isModuleActive('CPD'))
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('cpd.CPD') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="ti-close"></i></span>
                    </button>
                </div>

                <form method="POST" action="{{ route('cpd.course_to_cpd') }}">
                    @csrf
                    <input type="hidden" name="course_id" id="cpd_course_id" value="">

                <div class="modal-body">
                    <div class="input-control">
                        <label for="#">{{ __('cpd.CPD') }}</label>
                        <select name="" id="" class="theme_select">
                            <option value="">{{ __('cpd.Select CPD') }}</option>
                            @foreach ($cpds as $cpd)
                                <option value="{{ $cpd->id }}">{{ $cpd->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer mntop">
                    <button type="button" class="theme_btn small_btn bg-transparent"
                            data-bs-dismiss="modal">{{ __('common.Cancel') }}</button>
                    <button type="button" class="theme_btn small_btn ">{{ __('common.Submit') }}</button>
                </div>
                </form>
            </div>
        </div>
    </div>
@endif


<script>
    let typingTimer;
    let delay = 400; // milliseconds

    $('#courseSearch1').on('keyup', function () {
        clearTimeout(typingTimer);

        let search = $(this).val();
        let url = "{{ route(\Request::route()->getName()) }}";

        typingTimer = setTimeout(function () {
            $.ajax({
                url: url,
                type: "GET",
                data: {
                    search: search
                },
                success: function (response) {
                    $('#courseList').html(response);
                }
            });
        }, delay);
    });
</script>

{{-- JS for toggle and AJAX --}}
<script>

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.group-header').forEach(header => {
        header.addEventListener('click', function () {
            const stepId = this.dataset.stepId;
            const group = document.getElementById('group-' + stepId);
            const icon = this.querySelector('.toggle-icon');

            if(group.style.display === 'none' || group.style.display === '') {
                group.style.display = 'block';
                icon.innerText = '-';
            } else {
                group.style.display = 'none';
                icon.innerText = '+';
            }
        });
    });
});


// AJAX Enroll
document.querySelectorAll('.enroll-group-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const user_id = this.dataset.user;
        const learning_path_id = this.dataset.learningpath;
        const step_id = this.dataset.step;
        const course_ids = this.dataset.courseids;

        fetch("{{ route('group.course.enroll') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                user_id, learning_path_id, step_id, course_ids
            })
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                alert('Courses enrolled successfully!');
            } else {
                alert(data.message || 'Something went wrong! Check console for details.');
                console.log(data);
            }
        })
        .catch(err => {
            console.error(err);
            alert('Something went wrong! Check console for details.');
        });
    });
});
</script>

