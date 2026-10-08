<section style="padding:80px 0; background:#fff;">

    <div class="container">

        <!-- HEADER -->
        <div style="
            display:flex;
            justify-content:space-between;
            align-items:center;
            margin-bottom:40px;
        ">

            <!-- TITLE -->
            <div>
                <h2 style="font-size:32px;font-weight:700;margin:0;">
                    Top Courses
                </h2>

                <span style="
                    display:block;
                    width:60px;
                    height:4px;
                    background:#f59e0b;
                    margin-top:8px;
                    border-radius:2px;
                "></span>
            </div>

            <!-- BUTTON -->
            <a href="{{ route('courses') }}" style="
                padding:10px 20px;
                border-radius:8px;
                border:1px solid #e5e7eb;
                text-decoration:none;
                color:#111;
                font-weight:500;
                transition:0.3s;
            "
            onmouseover="this.style.background='#2563eb'; this.style.color='#fff';"
            onmouseout="this.style.background='transparent'; this.style.color='#111';"
            >
                All Courses →
            </a>

        </div>


        <!-- COURSES GRID -->
        <div class="row" style="row-gap:30px;">

            @foreach($courses as $course)
                <div class="course-col">

                    <!-- CARD -->
                        <a href="{{ (Auth::check() && session()->has('tenant_slug'))
                                ? url(session('tenant_slug') . '/courses-details/' . $course->slug) . '?category_id[]=' . $course->id
                                : url('courses-details/' . $course->slug) . '?category_id[]=' . $course->id }}"
                            style="text-decoration:none;">

                        <div style="
                            background:#fff;
                            border-radius:14px;
                            overflow:hidden;
                            transition:0.3s;
                            border:1px solid #eee;
                        "
                        onmouseover="this.style.transform='translateY(-6px)'; this.style.boxShadow='0 15px 40px rgba(0,0,0,0.1)';"
                        onmouseout="this.style.transform='none'; this.style.boxShadow='none';"
                        >

                            <!-- IMAGE -->
                            <div class="course-image">
                                <img src="{{ getCourseImage(str_replace('/public/', '', $course->image)) }}" alt="" >

                                <!-- FREE BADGE -->
                                <!-- @if($course->price == 0)
                                    <span style="
                                        position:absolute;
                                        top:10px;
                                        left:10px;
                                        background:#22c55e;
                                        color:#fff;
                                        padding:5px 10px;
                                        font-size:12px;
                                        border-radius:6px;
                                        font-weight:600;
                                    ">
                                        FREE
                                    </span>
                                @endif -->
                            </div>

                            <!-- CONTENT -->
                            <div style="padding:15px;">

                                <!-- LEVEL -->
                                <!-- <span style="
                                    font-size:12px;
                                    background:#e0f2fe;
                                    color:#0284c7;
                                    padding:4px 10px;
                                    border-radius:6px;
                                ">
                                    {{ $course->level ?? 'All Levels' }}
                                </span> -->

                                <!-- TITLE -->
                                <h6 style="
                                    margin:10px 0;
                                    font-size:16px;
                                    font-weight:600;
                                    color:#111;
                                ">
                                    {{ \Illuminate\Support\Str::limit($course->title, 50) }}
                                </h6>

                                <!-- INSTRUCTOR -->
                                <p style="margin:0; font-size:14px; color:#2563eb;">
                                    {{ $course->user->name ?? '' }}
                                </p>

                                <!-- PRICE -->
                                <!-- <div style="margin-top:10px; font-weight:600;">
                                    {{ $course->price == 0 ? 'Free' : '$'.$course->price }}
                                </div> -->

                                <!-- RATING -->
                                <!-- <div style="margin-top:5px; color:#f59e0b;">
                                    ★★★★★ <span style="color:#6b7280;">(2)</span>
                                </div> -->

                            </div>

                        </div>

                    </a>

                </div>
            @endforeach

        </div>

    </div>

</section>

<style>

    .course-image {
    width: 100%;
    height: 180px;
    background: #f3f4f6;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    border-bottom: 1px solid #eee;
}

.course-image img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}
    .course-col {
    width: 20%;
    padding: 0 10px;
    margin-bottom: 30px;
}

/* RESPONSIVE */
@media (max-width: 1200px) {
    .course-col { width: 25%; } /* 4 per row */
}

@media (max-width: 992px) {
    .course-col { width: 33.33%; } /* 3 per row */
}

@media (max-width: 768px) {
    .course-col { width: 50%; } /* 2 per row */
}

@media (max-width: 576px) {
    .course-col { width: 100%; } /* 1 per row */
}

</style>