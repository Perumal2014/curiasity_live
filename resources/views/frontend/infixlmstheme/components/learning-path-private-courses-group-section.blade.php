<meta name="csrf-token" content="{{ csrf_token() }}">
<div>
    <style>
    .class_tags {
        position: absolute;
        z-index: 1;
        top: 0;
        right: 0;
    }

    @media (max-width: 991px) {
        .input-group.theme_search_field {
            width: 80% !important;
        }
    }

    @media (max-width: 767px) {
        .input-group.theme_search_field {
            width: 60% !important;
            float: initial !important;
            margin: 0 auto;
        }
    }

    @media (max-width: 576px) {
        .input-group.theme_search_field {
            width: 90% !important;
            float: initial !important;
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
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
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
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
    }

    .mini-course-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
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
                                <h4 class="f_w_600 mb_20">Course Groups
                                </h4>
                            </div>
                        </div>

                        @php
                        if (routeIs('LearningPathPrivateCourses')) {
                        $search_text = trans('frontend.Search Learning Paths');
                        $search_route = '';
                        }
                        @endphp
                    </div>

                    <div class="row row-gap-24" id="courseList">

    <div class="col-12 mb-4">
        {{ $courses->links() }}
    </div>

    @forelse ($courses as $learningPath)

        @php
            // ✅ Get all course IDs in this learning path
            $allCourseIds = $learningPath->steps
                ->flatMap(fn($step) => $step->plancourses->pluck('course_id'))
                ->toArray();

            // ✅ Check enrollment
            $isEnrolled = count(array_intersect($allCourseIds, $enrolledCourseIds)) > 0;

            // ✅ Steps reset index
            $steps = $learningPath->steps->values();
        @endphp

        <div class="col-12 mb-4">
            <div class="card">

                {{-- HEADER --}}
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        {{ is_array($learningPath->title) ? ($learningPath->title['en'] ?? '-') : $learningPath->title }}
                    </h4>

                    {{-- ✅ Hide button after enroll --}}
                    @if(!$isEnrolled)
                        <a href="{{ route('privatePathCourseEnrolled', $learningPath->id) }}"
                           class="btn btn-sm btn-primary">
                            Enroll in All Courses
                        </a>
                    @endif
                </div>

                <div class="card-body">

                    <div class="accordion" id="learningPathAccordion{{ $learningPath->id }}">

                        {{-- ✅ LOOP STEPS --}}
                        @foreach ($steps as $key => $step)

                            @php
                                $totalCourses = $step->plancourses->count();

                                // ✅ Completed count
                                $completedCoursesCount = $step->plancourses->filter(function ($pc) use ($completedCourseIds) {
                                    return in_array($pc->course_id, $completedCourseIds);
                                })->count();

                                // ✅ Percentage
                                $percentage = $totalCourses > 0 
                                    ? round(($completedCoursesCount / $totalCourses) * 100)
                                    : 0;

                                // ✅ Status
                                if ($completedCoursesCount == $totalCourses && $totalCourses > 0) {
                                    $groupStatus = 'Completed';
                                    $badge = 'success';
                                } elseif ($completedCoursesCount > 0) {
                                    $groupStatus = 'In Progress';
                                    $badge = 'warning';
                                } else {
                                    $groupStatus = 'Not Started';
                                    $badge = 'secondary';
                                }

                                // ✅ LOCK LOGIC
                                $isLocked = false;

                                if ($learningPath->course_sequence == 1 && $key > 0) {

                                    $previousStep = $steps[$key - 1];

                                    $prevTotal = $previousStep->plancourses->count();

                                    $prevCompleted = $previousStep->plancourses->filter(function ($pc) use ($completedCourseIds) {
                                        return in_array($pc->course_id, $completedCourseIds);
                                    })->count();

                                    $isLocked = $prevCompleted < $prevTotal;
                                }
                            @endphp

                            {{-- ✅ ACCORDION ITEM --}}
                            <div class="accordion-item mb-3">

                                {{-- HEADER --}}
                                <h2 class="accordion-header">
                                    <button
                                        class="accordion-button {{ $isLocked ? 'disabled bg-light' : 'collapsed' }}"
                                        type="button"
                                        data-bs-toggle="{{ $isLocked ? '' : 'collapse' }}"
                                        data-bs-target="#collapse{{ $learningPath->id }}{{ $key }}">

                                        <div class="w-100 d-flex justify-content-between">

                                            <span>
                                                {{ $step->step_title ?? 'No Title' }}
                                                @if($isLocked) 🔒 @endif
                                            </span>

                                            <span>
                                                {{ $completedCoursesCount }} / {{ $totalCourses }}
                                                ({{ $percentage }}%)

                                                <span class="badge bg-{{ $badge }}">
                                                    {{ $groupStatus }}
                                                </span>
                                            </span>

                                        </div>
                                    </button>
                                </h2>

                                {{-- BODY --}}
                                @if(!$isLocked)
                                <div id="collapse{{ $learningPath->id }}{{ $key }}"
                                     class="accordion-collapse collapse"
                                     data-bs-parent="#learningPathAccordion{{ $learningPath->id }}">

                                    <div class="accordion-body">
                                        <div class="row">

                                            @forelse ($step->plancourses as $planCourse)

                                                @php
                                                    $course = $planCourse->course;
                                                    $isCompleted = in_array($planCourse->course_id, $completedCourseIds);
                                                    $courseLink = route('continueCourse', [$course->slug]);
                                                @endphp

                                                @if($course)
                                                    <div class="col-xl-4 col-md-6 mb-4">

                                                        @if(!$isCompleted)
                                                            <a href="{{ $courseLink }}" class="text-decoration-none text-dark">
                                                        @endif

                                                        <div class="course-item h-100 {{ $isCompleted ? 'opacity-75' : '' }}">

                                                            <div class="course-item-img">
                                                                <img src="{{ getCourseImage($course->thumbnail) }}">
                                                            </div>

                                                            <div class="course-item-info">

                                                                <h6 class="title">
                                                                    {{ $course->title }}
                                                                </h6>

                                                                <div class="course-item-info-description mb-2">
                                                                    {{ getLimitedText($course->about, 120) }}
                                                                </div>

                                                                {{-- STATUS --}}
                                                                @if($isCompleted)
                                                                    <span class="badge bg-success">Completed</span>
                                                                @else
                                                                    <span class="badge bg-secondary">Not Started</span>
                                                                @endif

                                                            </div>

                                                        </div>

                                                        @if(!$isCompleted)
                                                            </a>
                                                        @endif

                                                    </div>
                                                @endif

                                            @empty
                                                <div class="col-12">
                                                    <p>No courses in this step</p>
                                                </div>
                                            @endforelse

                                        </div>
                                    </div>
                                </div>
                                @endif

                            </div>

                        @endforeach

                    </div>

                </div>
            </div>
        </div>

    @empty
        <div class="col-12 text-center">
            <p>No Learning Paths Found</p>
        </div>
    @endforelse

</div>

                </div>
            </div>
        </div>
    </div>



            <script>
            let typingTimer;
            let delay = 400; // milliseconds
            </script>

            {{-- JS for toggle and AJAX --}}
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.group-header').forEach(header => {
                    header.addEventListener('click', function() {
                        const stepId = this.dataset.stepId;
                        const group = document.getElementById('group-' + stepId);
                        const icon = this.querySelector('.toggle-icon');

                        if (group.style.display === 'none' || group.style.display === '') {
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
                                user_id,
                                learning_path_id,
                                step_id,
                                course_ids
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                alert('Courses enrolled successfully!');
                            } else {
                                alert(data.message ||
                                    'Something went wrong! Check console for details.');
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