@php
$totalHours = Auth::user()->total_hours;

if (!$totalHours || $totalHours == 0) {
$totalHours = 76; // temporary fallback
}

$weekHours = round($totalHours * 0.6, 1);
$dayHours = round($weekHours / 7, 1);
$monthHours = round($totalHours, 1);

if (!function_exists('formatHM')) {
    function formatHM($hours)
    {
        $h = floor($hours);
        $m = round(($hours - $h) * 60);

        if ($m == 60) {
            $h++;
            $m = 0;
        }

        return [
            'h' => $h,
            'm' => $m,
        ];
    }
}

$day = formatHM($dayHours);
$week = formatHM($weekHours);
$month = formatHM($monthHours);

@endphp


<div>
    <style>
    /* ================= LAYOUT ================= */
    .dashboard_layout {
        display: flex;
        gap: 24px;
        align-items: flex-start;
    }

    .dashboard_left {
        flex: 0 0 70%;
        max-width: 70%;
    }

    .dashboard_right {
        flex: 0 0 25%;
        max-width: 25%;
        position: sticky;
        top: 20px;
    }

    @media (max-width: 1200px) {
        .dashboard_layout {
            flex-direction: column;
        }

        .dashboard_left,
        .dashboard_right {
            max-width: 100%;
            flex: 0 0 100%;
        }
    }

    /* ================= GLOBAL ================= */
    .dashboard_card {
        background: #ffffff;
        border-radius: 18px;
        padding: 22px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, .04);
    }

    .dashboard_card h4 {
        font-size: 22px;
        font-weight: 700;
        color: #334155;
        margin-bottom: 6px;
    }

    /* ================= TITLE ================= */
    .main_content_iner h3 {
        font-size: 28px;
        font-weight: 700;
        color: #0f172a;
    }

    .main_content_iner p {
        color: #64748b;
        font-size: 14px;
    }

    /* ================= STAT CARDS ================= */
    .stat_card {
        border-radius: 16px;
        padding: 18px;
        gap: 14px;
        transition: .2s;
    }

    .stat_card:hover {
        transform: translateY(-2px);
    }

    /* ICON */
    .stat_icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        color: #fff;
    }

    /* TEXT */
    .stat_card h5 {
        margin: 0;
        font-size: 22px;
        font-weight: 700;
    }

    .stat_card p {
        margin: 0;
        font-size: 13px;
        color: #64748b;
    }

    /* COLORS */
    .stat_blue {
        background: #e7f0ff;
    }

    .stat_blue .stat_icon {
        background: #3b82f6;
    }

    .stat_orange {
        background: #fff4e6;
    }

    .stat_orange .stat_icon {
        background: #f97316;
    }

    .stat_green {
        background: #eaf9f0;
    }

    .stat_green .stat_icon {
        background: #22c55e;
    }

    .stat_red {
        background: #ffecec;
    }

    .stat_red .stat_icon {
        background: #ef4444;
    }

    /* ================= PROFILE CARD ================= */
    .profile_card {
        background: #ffffff;
        border-radius: 18px;
        padding: 24px;
        text-align: center;
        box-shadow: 0 8px 24px rgba(0, 0, 0, .05);
    }

    .profile_card img {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        margin-bottom: 10px;
    }

    .profile_card h5 {
        font-weight: 600;
        margin-bottom: 4px;
    }

    /* INNER STATS */
    .profile_stats {
        background: #f1f5f9;
        border-radius: 14px;
        padding: 14px;
        display: flex;
        justify-content: space-around;
        margin-top: 15px;
    }

    .profile_stats h4 {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
    }

    .profile_stats small {
        color: #64748b;
    }



    /* ================= LAYOUT ================= */
    .dashboard_layout {
        display: flex;
        gap: 20px;
    }

    .dashboard_left {
        flex: 0 0 72%;
    }

    .dashboard_right {
        flex: 0 0 28%;
    }

    /* ================= RESPONSIVE ================= */
    @media (max-width: 1200px) {
        .dashboard_layout {
            flex-direction: column;
        }
    }

    /* ================= PROFILE ================= */
    .profile_card {
        /* background: linear-gradient(180deg,#1e3a8a,#1e40af); */
        color: #fff;
        border-radius: 18px;
        padding: 28px;
        text-align: center;
        margin-bottom: 24px;
    }

    .profile_card img {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        border: 3px solid rgba(255, 255, 255, .4);
        margin-bottom: 10px;
    }

    .profile_stats {
        margin-top: 18px;
        background: rgba(255, 255, 255, .15);
        border-radius: 14px;
        padding: 14px;
        display: flex;
        justify-content: space-around;
    }


    /* ================= SKILL CIRCLE ================= */
    .skill_circle_wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 25px;
    }

    /* ================= SKILL CIRCLE ================= */
    .skill_circle {
        --size: 180px;
        --thickness: 14px;
        --color: #6366f1;
        --bg: #e5e7eb;

        width: var(--size);
        height: var(--size);
        border-radius: 50%;
        position: relative;

        background: conic-gradient(var(--color) 0%,
                var(--color) calc(var(--percent) * 1%),
                var(--bg) calc(var(--percent) * 1%));
    }

    .skill_circle::before {
        content: '';
        position: absolute;
        inset: 14px;
        /* match thickness */
        background: #fff;
        border-radius: 50%;
    }

    .skill_value {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        font-size: 26px;
        font-weight: 700;
    }

    .skill_value small {
        font-size: 14px;
        color: #64748b;
    }

    /* ================= HOURS ================= */
    #hoursValue {
        font-size: 34px;
        font-weight: 700;
        display: block;
    }

    #minutesValue {
        font-size: 14px;
        color: #64748b;
    }

    .hours_tabs {
        background: #f1f5f9;
        padding: 4px;
        border-radius: 10px;
    }

    .hours_tabs span {
        padding: 6px 12px;
        border-radius: 8px;
        cursor: pointer;
    }

    .hours_tabs .active {
        background: #fff;
        box-shadow: 0 2px 6px rgba(0, 0, 0, .08);
    }

    .equal_height {
        height: 85%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    canvas {
        max-width: 100% !important;
    }

    /* ================= CAROUSEL WRAPPERS ================= */
    .cw-carousel-wrap,
    .badge-carousel-wrap {
        position: relative;
        overflow: hidden;
    }

    /* ================= HEADERS ================= */
    .cw-header,
    .badge-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 16px;
    }

    .cw-header h4,
    .badge-header h4 {
        margin: 0;
        line-height: 1.2;
    }

    /* ================= HEADER NAV ================= */
    .cw-nav,
    .badge-nav {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* ================= OWL NAV RESET ================= */
    .cw-carousel-wrap .owl-nav,
    .badge-carousel-wrap .owl-nav {
        position: static !important;
        margin: 0 !important;
        display: flex !important;
        gap: 8px;
    }

    /* ================= ARROW BUTTONS ================= */
    .cw-carousel-wrap .owl-nav button,
    .badge-carousel-wrap .owl-nav button {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #6366f1;
        color: #ffffff;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all .2s ease;
        box-shadow: 0 4px 12px rgba(0, 0, 0, .15);
    }

    .cw-carousel-wrap .owl-nav button:hover,
    .badge-carousel-wrap .owl-nav button:hover {
        background: #4f46e5;
    }

    .owl-nav button.disabled {
        opacity: .35;
        pointer-events: none;
        box-shadow: none;
    }

    /* ================= ARROW ICON ================= */
    .cw-arrow {
        font-size: 14px;
        line-height: 1;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* ================= CONTINUE WATCHING CARDS ================= */
    .cw-carousel .continue_card {
        max-width: none;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, .06);
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }

    .cw-carousel .thumb {
        height: 150px;
        background-size: cover;
        background-position: center;
    }

    .cw-carousel .card_body {
        padding: 14px;
        display: flex;
        flex-direction: column;
        flex: 1;
    }

    .cw-carousel h6 {
        font-size: 14px;
        margin-bottom: 8px;
    }

    .cw-carousel .progress {
        position: relative;
        height: 6px;
        background: #e5e7eb;
        border-radius: 6px;
        margin-top: 18px;
        overflow: visible;
    }

    .cw-carousel .progress-bar {
        height: 100%;
        background: #7c3aed;
        border-radius: 6px;
    }

    .cw-carousel .progress-badge {
        position: absolute;
        top: -14px;
        left: calc(var(--percent) * 1%);
        transform: translateX(-50%);
        width: 32px;
        height: 32px;
        background: #ffffff;
        border-radius: 50%;
        font-size: 11px;
        font-weight: 600;
        color: #374151;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 10px rgba(0, 0, 0, .12);
        z-index: 2;
    }



    /* ================= RECOMMENDED COURSES ================= */


    /* ================= GRID ================= */
    .recommended_grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
    }

    /* CARD */
    .course_card {
        display: block;
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
        text-decoration: none;
        color: #0f172a;
        box-shadow: 0 8px 24px rgba(0, 0, 0, .05);
        transition: all .25s ease;
    }

    .course_card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, .08);
    }

    /* IMAGE */
    .course_thumb img {
        width: 100%;
        height: 160px;
        object-fit: cover;
    }

    /* BODY */
    .course_body {
        padding: 16px;
    }

    /* TAGS */
    .course_tags {
        display: flex;
        gap: 8px;
        margin-bottom: 8px;

    }

    .tag {
        font-size: 11px;
        padding: 4px 8px;
        border-radius: 6px;
        font-weight: 600;
    }

    .tag.category {
        background: rgba(99, 102, 241, 0.1);
        color: #4338ca;
        backdrop-filter: blur(4px);
    }

    .tag.level {
        background: #f1f5f9;
        color: #64748b;
    }

    /* TITLE */
    .course_body h5 {
        font-size: 15px;
        font-weight: 600;
        margin: 6px 0;
    }

    /* DESCRIPTION */
    .course_body p {
        font-size: 13px;
        color: #64748b;
        margin: 0;
    }

    .rw-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        /* IMPORTANT */
        gap: 8px;
        margin-bottom: 20px;
    }

    /* Prevent link breaking */
    .see_all {
        white-space: nowrap;
        font-size: 13px;
        font-weight: 600;
        color: #6366f1;
    }

    /* RESPONSIVE */
    @media (max-width: 992px) {
        .recommended_grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 576px) {
        .recommended_grid {
            grid-template-columns: 1fr;
        }
    }

    /* .recommended_courses_card{
    background:#ffffff;
}

.rw-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:16px;
}

.rw-header h4{
    font-size:16px;
    font-weight:600;
}

.see_all{
    font-size:12px;
    font-weight:600;
    color:#6366f1;
    text-decoration:none;
}

.recommended_list{
    display:flex;
    flex-direction:column;
    gap:12px;
}

.recommended_item{
    display:flex;
    align-items:center;
    gap:16px;
    background:#e5e7eb; 
    border-radius:14px;
    padding:16px 18px;
    text-decoration:none;
    color:#0f172a;
    transition:all .25s ease;
}


.recommended_item:hover{
    background:#c7d2fe; 
}

.recommended_thumb img{
    width:48px;
    height:48px;
    border-radius:8px;
    object-fit:cover;
}

.recommended_content{
    flex:1;
}

.recommended_category{
    font-size:10px;
    text-transform:uppercase;
    letter-spacing:.08em;
    color:#94a3b8;
}

.recommended_content h6{
    margin:3px 0 0;
    font-size:14px;
    font-weight:600;
}

.recommended_meta{
    font-size:12px;
    color:#64748b;
    margin-right:10px;
}

.recommended_arrow{
    font-size:18px;
    color:#94a3b8;
} */

    /* .recommended_item:first-child{
    background:#2563eb;
    color:#fff;
}

.recommended_item:first-child .recommended_category,
.recommended_item:first-child .recommended_meta,
.recommended_item:first-child .recommended_arrow{
    color:#e5e7eb;
} */
    /* ================= BADGES ================= */
    .badge-carousel .dashboard_badge_item img {
        width: 90px;
        height: 90px;
    }

    /* ================= HEADER TEXT ARROWS ================= */
    .cw-header-arrows,
    .badge-header-arrows {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .header-arrow {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #6366f1;
        color: #ffffff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        font-weight: 600;
        cursor: pointer;
        user-select: none;
        box-shadow: 0 4px 12px rgba(0, 0, 0, .15);
        transition: all .2s ease;
    }

    .header-arrow:hover {
        background: #4f46e5;
    }

    .owl-nav,
    .owl-nav button,
    .owl-prev,
    .owl-next {
        display: none !important;
        opacity: 0 !important;
        pointer-events: none !important;
    }

    /* Skill legend */
    .skill-legend {
        display: flex;
        gap: 16px;
        font-size: 12px;
        color: #64748b;
        justify-content: space-between;
        /* margin-top: 6px; */
    }

    .skill-legend .legend-item {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .skill-legend .legend-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
    }

    /* Match circle colors */

    .skill-legend .legend-dot.done {
        background: #22c55e;
        /* green */
    }

    .skill-legend .legend-dot.remaining {
        background: #e5e7eb;
        /* grey */
    }

    .calendar_grid .today {
        background: #fff;
        color: #2563eb;
        font-weight: 700;
    }

    .calendar_grid span:hover {
        background: rgba(255, 255, 255, .25);
    }

    .calendar_card {
        background: #ffffff;
    }

    .calendar_header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 14px;
    }

    .calendar_nav span {
        cursor: pointer;
        font-size: 18px;
        margin-left: 6px;
        color: #64748b;
    }


    .calendar_grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 8px;
        text-align: center;
        font-size: 13px;
        color: #0f172a;
    }

    .calendar_grid .day {
        font-weight: 600;
        color: #64748b;
    }

    .calendar_grid span {
        padding: 6px 0;
        border-radius: 8px;
        cursor: pointer;
    }

    .calendar_grid span {
        width: 34px;
        height: 34px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        cursor: pointer;
        font-weight: 500;
        transition: all .2s ease;
    }

    .calendar_grid span:hover {
        background: #e0e7ff;
        color: #2563eb;
    }

    /* Today */
    .calendar_grid .today {
        background: #2563eb;
        color: #fff;
        font-weight: 700;
    }

    /* Selected date */
    .calendar_grid .selected {
        background: #f97316;
        color: #fff;
        font-weight: 700;
    }

    .calendar_grid span.has-event::after {
        content: '';
        width: 6px;
        height: 6px;
        background: #f97316;
        border-radius: 50%;
        position: absolute;
        bottom: 4px;
    }

    .calendar_grid span {
        position: relative;
    }

    /* ================= EVENTS CARD ================= */

    .events_card {
        background: #ffffff;
        max-height: 340px;
        /* FIXED HEIGHT */
        display: flex;
        flex-direction: column;
    }

    .events_card h5 {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 12px;
    }

    /* Scroll container */
    #eventsList {
        overflow-y: auto;
        padding-right: 6px;
    }

    /* Custom scrollbar */
    #eventsList::-webkit-scrollbar {
        width: 6px;
    }

    #eventsList::-webkit-scrollbar-thumb {
        background: #c7d2fe;
        border-radius: 10px;
    }

    #eventsList::-webkit-scrollbar-track {
        background: transparent;
    }

    /* Individual event */
    .event_item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 12px;
        border-radius: 12px;
        background: #f8fafc;
        margin-bottom: 10px;
        transition: all .2s ease;
    }

    .event_item:hover {
        background: #eef2ff;
    }

    /* Date badge */
    .event_date {
        min-width: 44px;
        height: 44px;
        background: #2563eb;
        color: #fff;
        border-radius: 10px;
        text-align: center;
        font-weight: 700;
        line-height: 1.1;
        padding-top: 6px;
        font-size: 14px;
    }

    .event_date small {
        display: block;
        font-size: 10px;
        font-weight: 500;
        opacity: .85;
    }

    /* Info */
    .event_info {
        flex: 1;
    }

    .event_info strong {
        display: block;
        font-size: 14px;
        color: #0f172a;
    }

    .event_info small {
        font-size: 12px;
        color: #64748b;
    }

    /* Arrow */
    .event_arrow {
        font-size: 18px;
        color: #94a3b8;
        margin-top: 4px;
    }

    /* ================= SKILL SET ================= */
    .skillset_card {
        background: #ffffff;
    }

    .add-skill-btn {
        background: #7c3aed;
        color: #fff;
        border: none;
        padding: 6px 14px;
        border-radius: 8px;
        font-size: 13px;
        cursor: pointer;
    }

    .add-skill-btn:hover {
        background: #6d28d9;
    }

    .skill_list {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
    }

    .skill_item {
        background: #f8fafc;
        border-radius: 14px;
        padding: 14px;
        min-width: 160px;
        box-shadow: 0 6px 16px rgba(0, 0, 0, .05);
    }

    .skill_item h6 {
        margin: 0;
        font-size: 14px;
        font-weight: 600;
    }

    .skill_level {
        font-size: 12px;
        color: #64748b;
        margin-top: 4px;
    }

    .skill_bar {
        height: 6px;
        background: #e5e7eb;
        border-radius: 6px;
        overflow: hidden;
        margin-top: 8px;
    }

    .skill_bar span {
        display: block;
        height: 100%;
        background: #22c55e;
    }

    .empty_skill {
        width: 100%;
        text-align: center;
        padding: 20px 0;
    }

    /* ================= MODAL ================= */
    .skill_modal {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, .45);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 9999;
    }

    .skill_modal_content {
        background: #fff;
        padding: 24px;
        border-radius: 16px;
        width: 320px;
    }

    .skill_modal_content input,
    .skill_modal_content select {
        width: 100%;
        margin-top: 12px;
        padding: 10px;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
    }

    .modal_actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 18px;
    }

    .btn_cancel {
        background: #e5e7eb;
        border: none;
        padding: 8px 14px;
        border-radius: 8px;
    }

    .btn_save {
        background: #7c3aed;
        color: #fff;
        border: none;
        padding: 8px 16px;
        border-radius: 8px;
    }

    /* ================= YOUR SKILLS BLOCK ================= */

    .skills_block {
        background: #f8fafc;
        border-radius: 20px;
    }

    /* HEADER */
    .skills_header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .skills_header h4 {
        font-size: 20px;
        font-weight: 700;
    }

    /* SKILL ITEM */
    .skill_row {
        margin-bottom: 18px;
    }

    /* TOP LINE */
    .skill_top {
        display: flex;
        justify-content: space-between;
        font-size: 14px;
        margin-bottom: 6px;
    }

    .skill_name {
        font-weight: 600;
        color: #0f172a;
    }

    .skill_percent {
        color: #64748b;
    }

    /* BAR */
    .skill_progress {
        width: 100%;
        height: 8px;
        background: #e5e7eb;
        border-radius: 10px;
        overflow: hidden;
    }

    /* FILLED BAR */
    .skill_progress span {
        display: block;
        height: 100%;
        border-radius: 10px;
        background: linear-gradient(90deg, #6366f1, #4f46e5);
        /* match skill circle */
        transition: width .4s ease;
    }

    :root {
        --system_primery_color: linear-gradient(77.16deg,
                #0D56A5 13.44%,
                #0D56A5 50%,
                #0D56A5 87.24%);
    }

    .sidebar .logo {
        background: var(--system_primery_color);
        padding: 35px 30px;
        background-size: 200% auto;
    }
    </style>

    @php
    $total = Auth::user()->totalStudentCourses();
    $isGamification =
    Settings('gamification_status') &&
    Settings('gamification_leaderboard_show_badges_status');
    @endphp

    <div class="main_content_iner main_content_padding">
        <div class="container-fluid g-0">
            @if ($unreadCount > 0)
            <div class="row">
                <div class="col-12">
                    <div class="dashboard_title">
                        <h3>
                            You have {{ $unreadCount }} new announcements,
                            <a href="{{ route('announcements.list', ['id' => Auth::user()->id]) }}">click here</a>
                        </h3>
                    </div>
                </div>
            </div>
            @endif
            <!-- TITLE -->
            <div class="mb-4">
                <h3>{{ @$wish_string }}, <span>{{ Auth::user()->name }}</span></h3>
                <p>{{ @$date }}</p>
            </div>

            <div class="dashboard_layout">

                <!-- ================= LEFT ================= -->
                <div class="dashboard_left">

                    <!-- STATS -->
                    <div class="row mb-4 g-3">

                        <div class="col-md-3">
                            <div class="stat_card stat_blue d-flex align-items-center">
                                <div class="stat_icon"><i class="ti-medall"></i></div>
                                <div>
                                    <h5>{{ Auth::user()->totalCertificate() ?? 0 }}</h5>
                                    <p><a href="{{ route('tenant.myCertificate') }}">Certificates</a></p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="stat_card stat_orange d-flex align-items-center">
                                <div class="stat_icon"><i class="ti-book"></i></div>
                                <div>
                                    <h5>{{ $total['process'] ?? 0 }}</h5>
                                    <p><a href="{{ route('tenant.myCourses') }}">In Progress</a></p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="stat_card stat_green d-flex align-items-center">
                                <div class="stat_icon"><i class="ti-check"></i></div>
                                <div>
                                    <h5>{{ $total['complete'] ?? 0 }}</h5>
                                    <p><a href="{{ route('tenant.myCourses') }}">Completed</a></p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="stat_card stat_red d-flex align-items-center">
                                <div class="stat_icon"><i class="ti-alert"></i></div>
                                <div>
                                    <h5>0</h5>
                                    <p><a href="{{ route('tenant.myCourses') }}">Overdue</a></p>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- SKILL + HOURS -->
                    <div class="row mb-4">

                        <!-- ================= SKILL ================= -->
                        <div class="col-md-4">
                            <div class="dashboard_card equal_height p-4">
                                <h4>Skill Level</h4>

                                <div class="skill_circle_wrapper">
                                    <div class="skill_circle" id="skillCircle" style="--percent: 5">
                                        <span class="skill_value">
                                            <span id="skillText">0%</span>
                                            <small>Basic</small>
                                        </span>
                                    </div>
                                </div>

                                <div class="skill-legend">
                                    <span class="legend-item">
                                        <i class="legend-dot done"></i> Mastered
                                    </span>
                                    <span class="legend-item">
                                        <i class="legend-dot remaining"></i> Remaining
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- ================= HOURS ================= -->
                        <div class="col-md-8">
                            <div class="dashboard_card equal_height p-4">

                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <h4>Hours Spent</h4>

                                        <span id="hoursValue">15h</span>
                                        <span id="minutesValue">5m</span>

                                        <div class="hours_subtitle">Total learning time</div>
                                    </div>

                                    <div class="hours_tabs">
                                        <span class="active" data-type="day" data-h="{{ $day['h'] }}"
                                            data-m="{{ $day['m'] }}">
                                            Day
                                        </span>

                                        <span data-type="week" data-h="{{ $week['h'] }}" data-m="{{ $week['m'] }}">
                                            Weekly
                                        </span>

                                        <span data-type="month" data-h="{{ $month['h'] }}" data-m="{{ $month['m'] }}">
                                            Monthly
                                        </span>
                                    </div>
                                </div>

                                <div style="height:160px;">
                                    <canvas id="hoursSpentChart"></canvas>
                                </div>

                            </div>
                        </div>

                    </div>

                    @if($mycourse->isNotEmpty())
                    <div class="dashboard_card p-4 cw-carousel-wrap">
                        <div class="cw-header">
                            <h4 class="mb-0">Continue Watching</h4>
                            <div class="cw-header-arrows">
                                <span class="header-arrow prev">&lsaquo;</span>
                                <span class="header-arrow next">&rsaquo;</span>
                            </div>
                        </div>

                        <div id="cwCarousel" class="owl-carousel cw-carousel">
                            @foreach($mycourse ?? [] as $single_course)
                            @php
                            $course = $single_course->course;
                            $percentage = round($course->loginUserTotalPercentage ?? 0);
                            @endphp

                            @if($percentage < 100) <div class="continue_card">
                                <div class="thumb" style="background-image:url('{{ getCourseImage($course->image) }}')">
                                </div>

                                <div class="card_body">
                                    <h6>{{ $course->title }}</h6>

                                    <div class="progress mb-3" style="--percent: {{ $percentage }}">
                                        <span class="progress-badge">{{ $percentage }}%</span>
                                        <div class="progress-bar" style="width:{{ $percentage }}%"></div>
                                    </div>

                                    <a href="{{ route('tenant.continueCourse',[$course->slug]) }}"
                                        class="theme_btn w-100">Continue
                                    </a>
                                </div>
                        </div>
                        @endif
                        @endforeach

                        @if(($total['process'] ?? 0) === 0)
                        <div class="text-center py-5">
                            <!-- <h5> All courses completed!</h5> -->
                            <p class="text-muted mb-0">Explore new courses to keep learning.</p>
                        </div>
                        @endif

                    </div>
                </div>
                @endif

                <div class="dashboard_card p-4 recommended_courses_card">

                    <div class="rw-header">
                        <h4 class="mb-0">Recommended Courses</h4>
                        <a href="{{ (Auth::check() && session()->has('tenant_slug'))
                                ? route('tenant.courses', ['tenant_slug' => session('tenant_slug')])
                                : route('courses') }}" class="see_all">Browse All</a>
                    </div>

                    <div class="recommended_grid">
                        @forelse($recommended_courses ?? [] as $course)

                        <a href="{{ route('courseDetailsView',[$course->slug]) }}" class="course_card">

                            <!-- IMAGE -->
                            <div class="course_thumb">
                                <img src="{{ getCourseImage($course->image) }}" alt="{{ $course->title }}">
                            </div>

                            <!-- CONTENT -->
                            <div class="course_body">

                                <!-- TAGS -->
                                <div class="course_tags">
                                    <span class="tag category">
                                        {{ $course->category->name ?? 'Course' }}
                                    </span>

                                    <!-- <span class="tag level">
                        {{ $course->level ?? 'Beginner' }}
                        </span> -->
                                </div>

                                <!-- TITLE -->
                                <h5>{{ $course->title }}</h5>

                                <!-- DESCRIPTION -->
                                <p>
                                    {{ \Illuminate\Support\Str::limit($course->description, 90) }}
                                </p>

                            </div>
                        </a>

                        @empty
                        <div class="text-center py-4">
                            <p class="text-muted mb-0">No recommended courses available</p>
                        </div>
                        @endforelse
                    </div>

                </div>

                <div class="dashboard_card p-4 mt-4 skills_block">

                    <!-- HEADER -->
                    <div class="skills_header">
                        <h4>Your Skills</h4>
                        <button class="add-skill-btn" id="openSkillModal">+ Add Skill</button>
                    </div>

                    <!-- SKILLS LIST -->
                    <div id="skillsContainer">

                        <!-- Empty -->
                        <div class="empty_skill" id="emptySkill">
                            <p class="text-muted mb-0">No skills added yet</p>
                        </div>

                    </div>

                </div>

                <div class="skill_modal" id="skillModal">
                    <div class="skill_modal_content">
                        <h5>Add Skill</h5>

                        <input type="text" id="skillName" placeholder="Skill name">
                        <select id="skillLevel">
                            <option value="25">Beginner</option>
                            <option value="50">Intermediate</option>
                            <option value="75">Advanced</option>
                            <option value="100">Expert</option>
                        </select>

                        <div class="modal_actions">
                            <button class="btn_cancel" id="closeSkillModal">Cancel</button>
                            <button class="btn_save" id="saveSkill">Save</button>
                        </div>
                    </div>
                </div>


            </div>

            <!-- ================= RIGHT ================= -->
            <div class="dashboard_right">

                <div class="profile_card">
                    <img src="{{ getProfileImage(Auth::user()->image,Auth::user()->name) }}">
                    <h5>{{ Auth::user()->name }}</h5>
                    <small class="text-muted">{{ (Auth::user()->tenant_id == CORPORATE) ? 'Learner' : 'Student' }}</small>

                    <div class="profile_stats mt-3">
                        <div>
                            <h4>{{ Auth::user()->courses->count() ?? 0 }}</h4>
                            <small>Total Courses</small>
                        </div>
                        <div>
                            <h4> <span id="studyHours">0h</span>
                                        <span id="studyMinutes">0m</span></h4>
                            <small>Study Hours</small>
                        </div>
                    </div>
                </div>

                <div class="dashboard_card calendar_card p-4 mb-4">
                    <div class="calendar_header">
                        <h5 id="calendarTitle"></h5>
                        <div class="calendar_nav">
                            <span id="prevMonth">&lsaquo;</span>
                            <span id="nextMonth">&rsaquo;</span>
                        </div>
                    </div>

                    <div class="calendar_grid" id="calendarGrid">
                        <!-- JS renders days -->
                    </div>
                </div>

                <div class="dashboard_card events_card p-4 mb-4">
                    <h5 class="mb-3">Schedule</h5>
                    <div id="eventsList">
                        <p class="text-muted mb-0">Select a date to view events</p>
                    </div>
                </div>



                @if($isGamification)
                <div class="dashboard_card p-4 badge-carousel-wrap">
                    <div class="badge-header">
                        <h4 class="mb-0">Upcoming Badges</h4>
                        <div class="badge-header-arrows">
                            <span class="header-arrow prev">&lsaquo;</span>
                            <span class="header-arrow next">&rsaquo;</span>
                        </div>
                    </div>

                    <div id="badgeCarousel" class="owl-carousel badge-carousel">
                        @foreach($badges ?? [] as $type)
                        @foreach($type->take(1) as $badge)
                        <div class="dashboard_badge_item text-center">
                            <img src="{{ asset($badge->image) }}">
                            <p class="mt-2">{{ $badge->title }}</p>
                        </div>
                        @endforeach
                        @endforeach
                    </div>
                </div>

                @endif

            </div>
        </div>
    </div>
</div>
</div>

<script src="{{ asset('public/backend/vendors/chartlist/Chart.min.js') }}"></script>
<script>
const baseUrl = "{{ url('/') }}";
const tenantSlug = "{{ request()->route('tenant_slug') }}";
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let monthEvents = {};
    const calendarTitle = document.getElementById('calendarTitle');
    const calendarGrid = document.getElementById('calendarGrid');
    const prevBtn = document.getElementById('prevMonth');
    const nextBtn = document.getElementById('nextMonth');
    const eventsList = document.getElementById('eventsList');

    let currentDate = new Date();
    let selectedDate = new Date(); // ✅ PRE-SELECT TODAY

    /* ================= DATE KEY (LOCAL TIME SAFE) ================= */
    function formatDateKey(date) {
        const y = date.getFullYear();
        const m = String(date.getMonth() + 1).padStart(2, '0');
        const d = String(date.getDate()).padStart(2, '0');
        return `${y}-${m}-${d}`;
    }

    /* ================= MOCK EVENTS ================= */
    const eventsData = {
        '2026-02-06': [{
                title: 'Basic HTML & CSS',
                info: '2 / 3 Lessons'
            },
            {
                title: 'UI Design Basics',
                info: '1 / 5 Lessons'
            }
        ],
        '2026-02-10': [{
            title: 'JavaScript Essentials',
            info: '3 / 6 Lessons'
        }]
    };

    /* ================= RENDER CALENDAR ================= */
    function renderCalendar(date) {
        calendarGrid.innerHTML = '';

        const year = date.getFullYear();
        const month = date.getMonth();
        const today = new Date();

        calendarTitle.innerText =
            date.toLocaleString('default', {
                month: 'long'
            }) + ' ' + year;

        /* ===== Week headers (Sunday start) ===== */
        ['S', 'M', 'T', 'W', 'T', 'F', 'S'].forEach(day => {
            const el = document.createElement('div');
            el.className = 'day';
            el.innerText = day;
            calendarGrid.appendChild(el);
        });

        /* ===== First day offset ===== */
        const firstDay = new Date(year, month, 1).getDay();
        for (let i = 0; i < firstDay; i++) {
            calendarGrid.appendChild(document.createElement('span'));
        }

        /* ===== Days ===== */
        const daysInMonth = new Date(year, month + 1, 0).getDate();

        for (let d = 1; d <= daysInMonth; d++) {
            const dayEl = document.createElement('span');
            dayEl.innerText = d;

            const thisDate = new Date(year, month, d);
            const key = formatDateKey(thisDate);

            /* Today */
            if (thisDate.toDateString() === today.toDateString()) {
                dayEl.classList.add('today');
            }

            /* Selected */
            if (
                selectedDate &&
                thisDate.toDateString() === selectedDate.toDateString()
            ) {
                dayEl.classList.add('selected');
            }

            /* Event dot */
            // if (eventsData[key]) {
            //     dayEl.classList.add('has-event');
            // }
            if (monthEvents[key]) {
                dayEl.classList.add('has-event');
            }

            /* Click */
            dayEl.addEventListener('click', () => {
                selectedDate = thisDate;
                renderCalendar(currentDate);
                loadEventsForDate(thisDate);
            });

            calendarGrid.appendChild(dayEl);
        }
    }

    /* ================= LOAD EVENTS ================= */

    function loadMonthEvents(date) {
        const year = date.getFullYear();
        const month = date.getMonth() + 1;

        $.get(
            baseUrl + '/' + tenantSlug + '/calendar-events/' + year + '/' + month,
            function(res) {

                monthEvents = {};

                // Ensure array
                const events = Array.isArray(res) ? res : [res];

                events.forEach(ev => {
                    if (!ev.start || !ev.end) return;

                    let start = new Date(ev.start);
                    let end = new Date(ev.end);

                    // Loop through date range
                    while (start <= end) {
                        const key = formatDateKey(start);
                        monthEvents[key] = true;
                        start.setDate(start.getDate() + 1);
                    }
                });

                console.log('Month events mapped:', monthEvents);
                renderCalendar(currentDate);
            }
        );
    }

    function loadEventsForDate(date) {
        const key = formatDateKey(date);
        eventsList.innerHTML = '';
        $.ajax({
            url: baseUrl + '/' + tenantSlug + '/get-calendar/' + key,
            method: 'GET',
            success: function(data) {
                console.log(data);
                if (!data.success) {
                    eventsList.innerHTML = `
                <p class="text-muted mb-0">
                    No events scheduled
                </p>
            `;
                    return;
                }

                eventsList.innerHTML = '';

                const item = document.createElement('div');
                item.className = 'event_item';

                item.innerHTML = `
            <div class="event_date">
                ${date.getDate()}
                <small>${date.toLocaleString('default',{month:'short'})}</small>
            </div>
            <div class="event_info">
                <strong>${data.title}</strong>
                <small>${data.course_start_time} - ${data.course_end_time}</small>
            </div>
            <span class="event_arrow"><a href="${data.meeting_url}" target="_blank">→</a></span>
        `;

                eventsList.appendChild(item);
            }
        });
        const readableDate = date.toLocaleDateString('en-GB', {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        });

        if (!eventsData[key] || eventsData[key].length === 0) {
            eventsList.innerHTML = `
            <p class="text-muted mb-0">
                No events scheduled on <strong>${readableDate}</strong>
            </p>
        `;
            return;
        }

        eventsData[key].forEach(ev => {
            const item = document.createElement('div');
            item.className = 'event_item';

            item.innerHTML = `
            <div class="event_date">
                ${date.getDate()}
                <small>${date.toLocaleString('default',{month:'short'})}</small>
            </div>
            <div class="event_info">
                <strong>${ev.title}</strong>
                <small>${ev.info}</small>
            </div>
            <span class="event_arrow">→</span>
        `;

            eventsList.appendChild(item);
        });
    }


    /* ================= MONTH NAV ================= */
    prevBtn.onclick = () => {
        // currentDate.setMonth(currentDate.getMonth() - 1);
        // renderCalendar(currentDate);
        currentDate.setMonth(currentDate.getMonth() - 1);
        loadMonthEvents(currentDate);


    };

    nextBtn.onclick = () => {
        // currentDate.setMonth(currentDate.getMonth() + 1);
        // renderCalendar(currentDate);
        currentDate.setMonth(currentDate.getMonth() + 1);
        loadMonthEvents(currentDate);

    };

    /* ================= INIT ================= */
    renderCalendar(currentDate);
    loadEventsForDate(selectedDate); // ✅ LOAD TODAY EVENTS
    loadMonthEvents(currentDate); // ✅ LOAD CURRENT MONTH EVENTS

});
</script>
<script>
/* ================= HOURS DATA ================= */
let hoursData = {
    day: {
        labels: [],
        hours: []
    },
    week: {
        labels: [],
        hours: []
    },
    month: {
        labels: [],
        hours: []
    }
};

let hoursChart;

/* ================= UPDATE HOURS FROM BACKEND ================= */
// function updateHours(type) {


//     const el = document.querySelector(`.hours_tabs span[data-type="${type}"]`);

//     if (!el) return;

//     const h = parseInt(el.dataset.h) || 0;
//     const m = parseInt(el.dataset.m) || 0;

//     document.getElementById('hoursValue').innerText = h + 'h';
//     document.getElementById('minutesValue').innerText = m + 'm';
// }

function loadUserSpentHours() {

    const loginHoursUrl =
        "{{ route('tenant.getLoginHours', session('tenant_slug')) }}";

    fetch(loginHoursUrl)
        .then(response => response.json())
        .then(data => {

            document.getElementById('hoursValue').innerText =
                data.hours + 'h';

            document.getElementById('minutesValue').innerText =
                data.minutes + 'm';

            document.getElementById('studyHours').innerText =
                data.hours + 'h';

            document.getElementById('studyMinutes').innerText =
                data.minutes + 'm';

            hoursData = data.chart;

            renderChart('day');
        })
        .catch(error => console.error(error));
}



document.addEventListener('DOMContentLoaded', function() {
    loadUserSpentHours();
})

/* ================= RENDER CHART ================= */
function renderChart(type = 'day') {

    const ctx = document.getElementById('hoursSpentChart');

    if (!ctx) return;

    if (hoursChart) {
        hoursChart.destroy();
    }

    hoursChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: hoursData[type].labels,
            datasets: [{
                label: 'Hours Spent',
                data: hoursData[type].hours,
                backgroundColor: '#818cf8', // light purple
                borderRadius: 8
            }, ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,

            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        boxWidth: 10,
                        color: '#64748b'
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + context.raw + 'h';
                        }
                    }
                }
            },

            scales: {
                x: {
                    stacked: true,
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#64748b'
                    }
                },
                y: {
                    stacked: true,
                    beginAtZero: true,
                    grid: {
                        color: '#f1f5f9'
                    },
                    ticks: {
                        callback: function(value, index) {
                            return index % 2 === 0 ?
                                this.getLabelForValue(value) :
                                '';
                        }
                    }
                }
            }
        }
    });
}

/* ================= INIT ================= */
document.addEventListener('DOMContentLoaded', function() {

    const tabs = document.querySelectorAll('.hours_tabs span');

    if (!tabs.length) return;

    const defaultType = 'day';

    // set active UI
    document.querySelectorAll('.hours_tabs span')
        .forEach(t => t.classList.remove('active'));

    document.querySelector(`.hours_tabs span[data-type="${defaultType}"]`)
        ?.classList.add('active');

    // 🔥 set value
    // updateHours(defaultType);

    // ✅ Load default chart
    // renderChart('day');
});

/* ================= TAB SWITCH ================= */
document.querySelectorAll('.hours_tabs span').forEach(tab => {

    tab.addEventListener('click', function() {

        document.querySelectorAll('.hours_tabs span')
            .forEach(t => t.classList.remove('active'));

        this.classList.add('active');

        const type = this.getAttribute('data-type');

        // updateHours(type);
        renderChart(type);
    });

});

/* ================= SKILL ================= */
function updateSkill(percent) {
    document.getElementById('skillCircle')
        .style.setProperty('--percent', percent);

    document.getElementById('skillText').innerText = percent + '%';
}

// ✅ Set skill %
updateSkill(5);
</script>
<script>
$(document).ready(function() {

    /* ========== INIT OWL ========== */
    const cw = $('#cwCarousel').owlCarousel({
        loop: false,
        margin: 16,
        nav: false,
        dots: false,
        responsive: {
            0: {
                items: 1
            },
            768: {
                items: 2
            },
            1200: {
                items: 3
            }
        }
    });

    const rw = $('#rwCarousel').owlCarousel({
        loop: false,
        margin: 16,
        nav: false,
        dots: false,
        responsive: {
            0: {
                items: 1
            },
            768: {
                items: 2
            },
            1200: {
                items: 3
            }
        }
    });

    const badge = $('#badgeCarousel').owlCarousel({
        loop: false,
        margin: 5,
        nav: false,
        dots: false,
        // responsive: {
        //     0:    { items: 1 },
        //     768:  { items: 2 },
        //     1200: { items: 3 }
        // }
    });

    /* ========== HEADER ARROWS CONTROL ========== */

    // Continue Watching
    $('.cw-header-arrows .next').on('click', function() {
        cw.trigger('next.owl.carousel');
    });

    $('.cw-header-arrows .prev').on('click', function() {
        cw.trigger('prev.owl.carousel');
    });

    // Upcoming Badges
    $('.badge-header-arrows .next').on('click', function() {
        badge.trigger('next.owl.carousel');
    });

    $('.badge-header-arrows .prev').on('click', function() {
        badge.trigger('prev.owl.carousel');
    });

});
</script>
<script>
const baseUrl = "{{ url('/') }}";
const tenantSlug = "{{ request()->route('tenant_slug') }}";
</script>


<script>
const openModal = document.getElementById('openSkillModal');
const closeModal = document.getElementById('closeSkillModal');
const modal = document.getElementById('skillModal');
const saveBtn = document.getElementById('saveSkill');
const container = document.getElementById('skillsContainer');
const emptySkill = document.getElementById('emptySkill');

openModal.onclick = () => modal.style.display = 'flex';
closeModal.onclick = () => modal.style.display = 'none';

/* ================= ADD SKILL ================= */
saveBtn.onclick = () => {

    const name = document.getElementById('skillName').value;
    const level = document.getElementById('skillLevel').value;

    if (!name) return;

    emptySkill.style.display = 'none';

    const row = document.createElement('div');
    row.className = 'skill_row';

    row.innerHTML = `
        <div class="skill_top">
            <span class="skill_name">${name}</span>
            <span class="skill_percent">${level}%</span>
        </div>
        <div class="skill_progress">
            <span style="width:${level}%"></span>
        </div>
    `;

    container.appendChild(row);

    modal.style.display = 'none';
    document.getElementById('skillName').value = '';
};
</script>