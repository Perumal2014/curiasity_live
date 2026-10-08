<div class="main_content_iner main_content_padding">

    <div class="dashboard_lg_card">

        <div class="container-fluid g-0">

            <div class="my_courses_wrapper">

            

                <div class="row">
                    <div class="col-12">
                        <div class="section__title3 mb-4">
                            <h3>{{ __('Organization Catalog') }}</h3>
                        </div>
                    </div>
                </div>

                <div class="row d-flex align-items-center mb-4 mb-lg-5">

    <!-- CATEGORY FILTER -->
    <div class="col-xl-6 col-md-6 col-sm-12 mt-3">
        <div class="short_select d-flex align-items-center pt-0 pb-3">

            <h5 class="mr_10 font_16 f_w_500 mb-0">
                {{ __('frontend.Filter By') }}:
            </h5>

            <input type="hidden" id="siteUrl"
                   value="{{ route('myOrganizationCatalog', request()->route('tenant_slug')) }}">

            <select class="theme_select my-course-select w-50" id="categoryFilter">

                <option value="">
                    {{ __('frontend.All Categories') }}
                </option>

                @foreach ($categories->where('parent_id',0) as $category)
                    @include('backend.categories._single_select_option',[
                        'category'=>$category,
                        'level'=>1
                    ])
                @endforeach

            </select>

        </div>
    </div>

    <!-- SEARCH -->
    <div class="col-xl-6 col-md-6 pb-3 col-sm-12 mt-3">

        <form method="GET"
              action="{{ route('myOrganizationCatalog', request()->route('tenant_slug')) }}">

            <div class="input-group theme_search_field pt-0 pb-3 float-end w-50">

                <div class="input-group-prepend">
                    <button class="btn" type="submit">
                        <i class="ti-search"></i>
                    </button>
                </div>

                <input type="text"
                       id="courseSearch"
                       class="form-control course_search_option"
                       name="search"
                       placeholder="Search Organization Courses"
                       value="{{ request('search') }}">

            </div>

        </form>

    </div>

</div>

                <div class="row row-gap-24">

                    @foreach($courses as $course)

                    <div class="col-xl-4 col-md-6">

                        <div class="course-item">

                            <div class="course-item-img">
                                <img src="{{ getCourseImage($course->thumbnail) }}" alt="course image">

                                @if($course->level)
                                <span class="course-tag">
                                    <span>{{ $course->courseLevel->title ?? '' }}</span>
                                </span>
                                @endif
                            </div>

                            <div class="course-item-info">

                                <a href="{{ courseDetailsUrl(null,$course->id,$course->type,$course->slug) }}" class="title">
                                    {{ $course->title }}
                                </a>

                                <div class="course-item-info-description">
                                    {{ getLimitedText($course->about,120) }}
                                </div>

                            </div>

                        </div>

                    </div>

                    @endforeach

                </div>

            </div>

        </div>

    </div>

</div>

<script>
    $('#categoryFilter').on('change', function () {

        let category = $(this).val();
        let baseUrl = $('#siteUrl').val();
        let search = $('#courseSearch').val();

        let url = baseUrl + '?';

        if (category) url += 'category=' + category + '&';
        if (search) url += 'search=' + search;

        window.location.href = url;
    });
</script>