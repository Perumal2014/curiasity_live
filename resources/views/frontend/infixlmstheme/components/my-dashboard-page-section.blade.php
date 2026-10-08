@php
$totalHours = Auth::user()->total_hours;

if (!$totalHours || $totalHours == 0) {
    $totalHours = 0;
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
:root{
    --lms-primary:#315fbe;
    --lms-primary-dark:#254c9b;
    --lms-blue-soft:#eef4ff;
    --lms-purple:#7c67d9;
    --lms-orange:#f59a45;
    --lms-green:#49b96b;
    --lms-red:#e96a6a;
    --lms-text:#263754;
    --lms-muted:#71809a;
    --lms-border:#e5eaf3;
    --lms-bg:#f5f7fc;
    --lms-card:#fff;
    --lms-shadow:0 8px 24px rgba(39,60,100,.07);
}

/* =========================================================
   DASHBOARD
   ========================================================= */


.lms_dashboard{
    position:relative;
    min-height:100%;

    background:
        linear-gradient(
            180deg,
            #e1e6f5 0%,
            #e7ebf7 22%,
            #eef1f9 48%,
            #f2f4fa 72%,
            #e9edf7 100%
        );

    overflow:hidden;
    isolation:isolate;
}


/* =========================================================
   LEFT LARGE DIAGONAL SHAPE
   ========================================================= */

.lms_dashboard::before{
    content:"";
    position:absolute;
    z-index:-1;

    width:760px;
    height:760px;

    left:-480px;
    top:130px;

    background:
        linear-gradient(
            135deg,
            rgba(91,119,193,.13),
            rgba(91,119,193,.025)
        );

    clip-path:polygon(
        62% 0,
        100% 25%,
        82% 48%,
        100% 72%,
        58% 100%,
        0 70%,
        18% 32%
    );

    pointer-events:none;
}


/* =========================================================
   RIGHT / BOTTOM LARGE DIAGONAL SHAPE
   ========================================================= */

.lms_dashboard::after{
    content:"";
    position:absolute;
    z-index:-1;

    width:900px;
    height:900px;

    right:-600px;
    bottom:-430px;

    background:
        linear-gradient(
            135deg,
            rgba(82,108,185,.14),
            rgba(82,108,185,.025)
        );

    clip-path:polygon(
        28% 0,
        72% 18%,
        100% 45%,
        76% 68%,
        100% 100%,
        25% 82%,
        0 45%
    );

    pointer-events:none;
}


/* =========================================================
   VERY SOFT INNER GEOMETRY
   ========================================================= */

.lms_dashboard .container-fluid{
    position:relative;
    z-index:1;
}


/* LEFT / LOWER FAINT SHAPE */
.lms_dashboard .container-fluid::before{
    content:"";
    position:absolute;
    z-index:-1;

    width:620px;
    height:620px;

    left:-390px;
    bottom:-160px;

    background:
        linear-gradient(
            145deg,
            rgba(102,127,194,.075),
            rgba(102,127,194,0)
        );

    clip-path:polygon(
        0 30%,
        48% 0,
        100% 34%,
        78% 70%,
        100% 100%,
        28% 82%
    );

    pointer-events:none;
}


/* RIGHT / TOP FAINT SHAPE */
.lms_dashboard .container-fluid::after{
    content:"";
    position:absolute;
    z-index:-1;

    width:650px;
    height:650px;

    right:-410px;
    top:60px;

    background:
        linear-gradient(
            150deg,
            rgba(93,116,184,.065),
            rgba(93,116,184,0)
        );

    clip-path:polygon(
        38% 0,
        100% 22%,
        82% 55%,
        100% 100%,
        35% 78%,
        0 35%
    );

    pointer-events:none;
}



/* =========================================================
   WELCOME
   ========================================================= */

.lms_welcome{
    display:grid;
    grid-template-columns:minmax(260px,1fr) minmax(260px,360px);
    gap:18px;
    align-items:center;
    margin-bottom:18px;
}

.lms_welcome h3{
    margin:0 0 4px;
    color:var(--lms-text);
    font-size:24px;
    line-height:1.2;
    font-weight:700;
}

.lms_welcome p{
    margin:0;
    color:var(--lms-muted);
    font-size:14px;
}

.learning_goal{
    background:#fff;
    border:1px solid var(--lms-border);
    border-radius:24px;
    min-height:64px;
    padding:11px 18px;
    display:flex;
    align-items:center;
    gap:14px;
    box-shadow:var(--lms-shadow);
}

.goal_icon{
    width:48px;
    height:48px;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    background:var(--lms-blue-soft);
    color:var(--lms-primary);
    font-size:22px;
    flex:0 0 48px;
}

.goal_copy{
    min-width:0;
    flex:1;
}

.goal_copy small{
    display:block;
    color:var(--lms-muted);
    font-size:12px;
    margin-bottom:2px;
}

.goal_copy strong{
    display:block;
    color:var(--lms-text);
    font-size:18px;
    line-height:1.1;
}

.goal_bar{
    height:5px;
    background:#e8edf6;
    border-radius:10px;
    overflow:hidden;
    margin-top:6px;
}

.goal_bar span{
    display:block;
    height:100%;
    width:60%;
    background:linear-gradient(90deg,#4d7bd7,#78a0e8);
    border-radius:10px;
}


/* =========================================================
   MAIN GRID
   ========================================================= */

.lms_main_grid{
    display:grid;
    grid-template-columns:255px minmax(0,1fr) 285px;
    gap:18px;
    align-items:stretch;
}

.lms_col{
    display:flex;
    flex-direction:column;
    gap:14px;
    min-width:0;
}

.lms_col .lms_card{
    margin-top:0!important;
    flex-shrink:0;
}


/* =========================================================
   CARDS
   ========================================================= */

.lms_card{
    background:var(--lms-card);
    border:1px solid var(--lms-border);
    border-radius:18px;
    box-shadow:0 5px 20px rgba(35,58,98,.055);
    overflow:hidden;
}

.lms_card_pad{
    padding:16px;
}

.lms_card_header{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:10px;
    margin-bottom:13px;
}

.lms_card_header h4{
    margin:0;
    color:var(--lms-text);
    font-size:16px;
    font-weight:700;
    letter-spacing:-.15px;
}

.lms_card_header p{
    margin:2px 0 0;
    color:var(--lms-muted);
    font-size:11px;
}

.lms_link{
    color:var(--lms-primary);
    font-size:11px;
    font-weight:600;
    text-decoration:none;
    white-space:nowrap;
}


/* =========================================================
   LEFT - SKILL GAP
   ========================================================= */

.skill_gap_card{
    background:linear-gradient(145deg,#244b91,#315fbe);
    color:#fff;
    border:none;
    box-shadow:0 10px 26px rgba(37,75,145,.18);
}

.skill_gap_card .lms_card_header h4,
.skill_gap_card .lms_card_header p{
    color:#fff;
}

.skill_gap_card .lms_card_header{
    border-bottom:1px solid rgba(255,255,255,.18);
    padding-bottom:11px;
}

.skill_context{
    font-size:11px;
    line-height:1.6;
    color:rgba(255,255,255,.82);
    margin-bottom:13px;
}

.skill_row_ref{
    margin:13px 0;
}

.skill_row_ref:last-child{
    margin-bottom:0;
}

.skill_row_ref_top{
    display:flex;
    justify-content:space-between;
    gap:8px;
    font-size:12px;
    margin-bottom:6px;
}

.skill_row_ref_top strong{
    font-weight:600;
    color:#fff;
}

.skill_status{
    padding:3px 7px;
    border-radius:5px;
    font-size:9px;
    line-height:1;
    background:rgba(255,255,255,.16);
    color:#fff;
}

.skill_status.good{
    background:#53b96b;
}

.skill_status.mid{
    background:#f4a047;
}

.skill_status.low{
    background:#e96a6a;
}

.skill_bar_ref{
    height:5px;
    border-radius:8px;
    background:rgba(255,255,255,.16);
    overflow:hidden;
}

.skill_bar_ref span{
    display:block;
    height:100%;
    border-radius:8px;
    background:#73b5ff;
}

.skill_bar_ref.green span{
    background:#5fca77;
}

.skill_bar_ref.orange span{
    background:#f5a24e;
}

.skill_bar_ref.red span{
    background:#ef7373;
}


/* =========================================================
   LEFT - RECOMMENDED COURSES
   ========================================================= */

.recommend_list{
    display:flex;
    flex-direction:column;
}

.recommend_item{
    display:flex;
    align-items:center;
    gap:10px;
    padding:10px 0;
    border-bottom:1px solid #edf0f5;
    text-decoration:none;
    transition:
        background .18s ease,
        padding-left .18s ease;
    border-radius:8px;
}

.recommend_item:last-child{
    border-bottom:0;
    padding-bottom:0;
}

.recommend_item:hover{
    background:#f7f9fd;
    padding-left:6px;
}

.recommend_thumb{
    width:40px;
    height:40px;
    border-radius:9px;
    object-fit:cover;
    flex:0 0 40px;
}

.recommend_info{
    min-width:0;
    flex:1;
}

.recommend_info strong{
    display:block;
    color:var(--lms-text);
    font-size:11px;
    font-weight:600;
    white-space:nowrap;
    overflow:hidden;
    text-overflow:ellipsis;
}

.recommend_info small{
    display:block;
    color:var(--lms-muted);
    font-size:10px;
    margin-top:3px;
}

.recommend_arrow{
    color:#a6b0c2;
    font-size:17px;
}


/* =========================================================
   CENTER - LEARNING PROGRESS
   ========================================================= */

.lms_col_center .lms_card:first-child{
    min-height:590px;
}

.progress_top{
    display:grid;
    grid-template-columns:180px minmax(0,1fr);
    gap:30px;
    align-items:center;
}

.progress_ring{
    --size:150px;
    --thickness:13px;
    --percent:0%;

    width:var(--size);
    height:var(--size);
    border-radius:50%;

    background:
        conic-gradient(
            #3f73c9 var(--percent),
            #dfe8f7 0
        );

    position:relative;
    margin:auto;
}

.progress_ring:before{
    content:"";
    position:absolute;
    inset:12px;
    background:#fff;
    border-radius:50%;
}

.progress_ring_content{
    position:absolute;
    inset:0;

    display:flex;
    align-items:center;
    justify-content:center;
    flex-direction:column;

    z-index:1;
}

.progress_ring_content strong{
    font-size:34px;
    color:var(--lms-text);
    line-height:1;
}

.progress_ring_content small{
    font-size:13px;
    color:var(--lms-muted);
    margin-top:4px;
}

.progress_track{
    height:10px;
    background:#e9edf5;
    border-radius:10px;
    overflow:hidden;
    margin:14px 0 20px;
}

.progress_track span{
    display:block;
    height:100%;
    border-radius:10px;
    background:linear-gradient(90deg,#4776c9,#315fbe);
}

.progress_metrics{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    border-top:1px solid #edf0f5;
    padding-top:13px;
}

.progress_metric{
    text-align:center;
    border-right:1px solid #e7ebf2;
    padding:0 8px;
}

.progress_metric:last-child{
    border-right:0;
}

.progress_metric strong{
    display:block;
    font-size:22px;
    color:var(--lms-text);
}

.progress_metric span{
    display:block;
    font-size:12px;
    color:var(--lms-muted);
    margin-top:3px;
}

.progress_metric span a{
    color:inherit;
    text-decoration:none;
}


/* =========================================================
   HOURS / LEARNING CHART
   ========================================================= */

.hours_block{
    margin-top:24px;
    border-top:1px solid #edf0f5;
    padding-top:20px;
}

.hours_head{
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    gap:10px;
}

.hours_value strong{
    font-size:30px;
    color:var(--lms-text);
}

.hours_value small{
    font-size:13px;
    color:var(--lms-muted);
}

.hours_tabs{
    display:flex;
    gap:3px;
    background:#f2f5fa;
    padding:3px;
    border-radius:9px;
}

.hours_tabs span{
    padding:7px 11px;
    border-radius:7px;
    font-size:12px;
    color:var(--lms-muted);
    cursor:pointer;
}

.hours_tabs span.active{
    background:#fff;
    color:var(--lms-primary);
    box-shadow:0 2px 6px rgba(0,0,0,.07);
    font-weight:600;
}

.hours_chart{
    height:320px;
    margin-top:16px;
    padding:4px 2px 0;
    position:relative;
}

.hours_chart canvas{
    width:100%!important;
    height:100%!important;
    display:block!important;
}

.hours_chart.chart_no_data:after{
    content:'No learning activity yet';
    position:absolute;
    left:50%;
    top:50%;
    transform:translate(-50%,-50%);
    font-size:12px;
    color:#9aa7bb;
    pointer-events:none;
    background:rgba(255,255,255,.88);
    padding:7px 12px;
    border-radius:6px;
}

.chart_empty{
    display:flex;
    align-items:center;
    justify-content:center;
    border:1px dashed #e0e6f0;
    border-radius:12px;
    background:#fbfcfe;
}

.chart_empty_message{
    display:flex;
    flex-direction:column;
    align-items:center;
    gap:5px;
    color:#9aa7bb;
    font-size:10px;
}

.chart_empty_message i{
    font-size:20px;
    color:#b1bfd3;
}


/* =========================================================
   CENTER - OLD COURSE STRIP
   ========================================================= */

.course_strip{
    display:grid;
    grid-template-columns:repeat(3,minmax(0,1fr));
    gap:12px;
}

.course_ref_card{
    background:#fff;
    border:1px solid var(--lms-border);
    border-radius:11px;
    overflow:hidden;
    min-width:0;
    transition:
        transform .18s ease,
        box-shadow .18s ease,
        border-color .18s ease;
}

.course_ref_card:hover{
    transform:translateY(-2px);
    box-shadow:0 8px 20px rgba(35,58,98,.09);
    border-color:#d4deef;
}

.course_ref_thumb{
    height:100px;
    background-size:cover;
    background-position:center;
}

.course_ref_body{
    padding:9px;
}

.course_ref_body h6{
    margin:0 0 7px;
    color:var(--lms-text);
    font-size:11px;
    line-height:1.35;
    min-height:29px;
}

.course_ref_progress{
    height:5px;
    background:#e7ebf2;
    border-radius:8px;
    overflow:hidden;
    margin-bottom:8px;
}

.course_ref_progress span{
    display:block;
    height:100%;
    background:#4776c9;
    border-radius:8px;
}

.course_ref_meta{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:5px;
}

.course_badge{
    font-size:8px;
    padding:3px 6px;
    border-radius:5px;
    background:#e9f5eb;
    color:#34864b;
}

.course_percent{
    font-size:9px;
    color:var(--lms-muted);
}

.course_ref_btn{
    display:block;
    text-align:center;
    background:#edf4ff;
    color:var(--lms-primary);
    border-radius:7px;
    padding:5px;
    font-size:10px;
    font-weight:600;
    text-decoration:none;
    margin-top:8px;
}


/* =========================================================
   MY COURSES / OWL CAROUSEL
   ========================================================= */

.cw-carousel-wrap{
    min-height:330px!important;
}

.cw-header{
    display:flex;
    align-items:center;
    justify-content:space-between;
    margin-bottom:12px;
}

.cw-header h4{
    font-size:20px!important;
    color:var(--lms-text);
    margin:0;
}

.cw-header p{
    font-size:13px!important;
    color:var(--lms-muted);
    margin:3px 0 0;
}


.cw-header-arrows{
    display:flex;
    align-items:center;
    gap:8px;
}

.cw-header-arrows .header-arrow{
    width:36px;
    height:36px;
    min-width:36px;
    min-height:36px;

    display:inline-flex;
    align-items:center;
    justify-content:center;

    border-radius:50%;
    background:#edf3ff;
    color:var(--lms-primary);

    font-size:24px;
    line-height:1;
    cursor:pointer;

    box-sizing:border-box;
}

.cw-header-arrows .header-arrow:hover{
    background:#dce9ff;
    color:var(--lms-primary-dark);
}


/* =========================================================
   OWL RESET
   ========================================================= */

.owl-nav,
.owl-nav button,
.owl-prev,
.owl-next{
    display:none!important;
    opacity:0!important;
    pointer-events:none!important;
}

.cw-carousel-wrap .owl-stage{
    display:flex;
}

.cw-carousel .owl-item{
    height:auto;
    padding:2px;
}


/* =========================================================
   COURSE CARD
   ========================================================= */

.cw-carousel .continue_card{
    height:100%;
    background:#fff;
    border:1px solid var(--lms-border);
    border-radius:14px;
    box-shadow:none;
    overflow:hidden;

    display:flex;
    flex-direction:column;

    box-sizing:border-box;
}


/* ================= COURSE IMAGE ================= */

.cw-carousel .thumb{
    width:100%;
    aspect-ratio:16 / 9;

    height:auto;
    min-height:0;

    background:#f4f7fb;

    overflow:hidden;

    display:flex;
    align-items:center;
    justify-content:center;

    flex:0 0 auto;
}

.cw-carousel .thumb img{
    display:block;

    width:100%;
    height:100%;

    max-width:100%;
    max-height:100%;

    object-fit:contain;
    object-position:center center;

    background:#f4f7fb;
}


/* =========================================================
   COURSE CARD BODY
   ========================================================= */

.cw-carousel .card_body{
    padding:14px;
    display:flex;
    flex-direction:column;
    flex:1;
    box-sizing:border-box;
}

.cw-carousel h6{
    font-size:14px;
    line-height:1.45;
    min-height:42px;
    margin:0 0 10px;
    color:var(--lms-text);
}


/* =========================================================
   COURSE PROGRESS
   ========================================================= */

.cw-carousel .progress{
    position:relative;
    height:7px;
    background:#e5e9f1;
    border-radius:8px;
    margin:10px 0 16px;
    overflow:visible;
}

.cw-carousel .progress-bar{
    height:100%;
    background:#4776c9;
    border-radius:8px;
}

.cw-carousel .progress-badge{
    position:absolute;
    right:0;
    top:-21px;
    font-size:11px;
    color:var(--lms-muted);
}


/* =========================================================
   CONTINUE BUTTON
   ========================================================= */

.cw-carousel .theme_btn{
    font-size:13px;
    padding:9px 12px;
    border-radius:9px;
    margin-top:auto;
}


/* =========================================================
   RIGHT - MENTOR
   ========================================================= */

.mentor_card{
    background:linear-gradient(150deg,#234a91,#315fbe);
    color:#fff;
    border:none;
    height:160px;
    box-shadow:0 10px 26px rgba(37,75,145,.16);
}

.mentor_card h4{
    color:#fff;
}

.mentor_message{
    background:#fff;
    color:#4e5c73;
    border-radius:16px 16px 16px 5px;
    padding:9px 11px;
    font-size:10px;
    margin-bottom:9px;
    box-shadow:0 3px 10px rgba(0,0,0,.06);
}

.mentor_actions{
    display:flex;
    flex-direction:column;
    gap:7px;
}

.mentor_action{
    display:block;
    text-align:left;
    border:1px solid rgba(255,255,255,.18);
    background:rgba(255,255,255,.12);
    color:#fff;
    border-radius:10px;
    padding:8px 10px;
    font-size:10px;
    text-decoration:none;
}

.mentor_action:hover{
    background:rgba(255,255,255,.2);
    color:#fff;
}


/* =========================================================
   PROFILE
   ========================================================= */

.profile_compact{
    display:flex;
    align-items:center;
    gap:10px;
}

.profile_compact img{
    width:46px;
    height:46px;
    border-radius:50%;
    object-fit:cover;
    border:2px solid #e7edf7;
}

.profile_compact strong{
    display:block;
    font-size:15px;
    color:var(--lms-text);
}

.profile_compact small{
    display:block;
    font-size:11px;
    color:var(--lms-muted);
    margin-top:2px;
}

.profile_stats_ref{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:8px;
    margin-top:12px;
}

.profile_stat_ref{
    background:#f8faff;
    border:1px solid #edf1f7;
    border-radius:9px;
    padding:9px;
    text-align:center;
}

.profile_stat_ref strong{
    display:block;
    font-size:17px;
    color:var(--lms-text);
}

.profile_stat_ref span{
    font-size:10px;
    color:var(--lms-muted);
}


/* =============================
   CALENDAR - 
   ============================= */

.calendar_card{
    width:100%;
    box-sizing:border-box;
    padding-bottom:18px!important;
}

.calendar_card .calendar_header{
    width:100%;
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:10px;
    margin-bottom:14px;
}

.calendar_card .calendar_header h5{
    margin:0;
    font-size:16px;
    line-height:1.3;
    font-weight:700;
    color:var(--lms-text);
    white-space:nowrap;
}

.calendar_nav{
    display:flex;
    align-items:center;
    gap:4px;
    flex-shrink:0;
}

.calendar_nav span{
    width:24px;
    height:24px;

    display:inline-flex;
    align-items:center;
    justify-content:center;

    margin:0!important;

    border-radius:50%;
    cursor:pointer;

    color:#8390a5;
    background:transparent;

    font-size:18px;
    line-height:1;

    transition:
        background .15s ease,
        color .15s ease;
}

.calendar_nav span:hover{
    background:#edf3ff;
    color:var(--lms-primary);
}


/* =========================================================
   CALENDAR GRID
   ========================================================= */

.calendar_grid{
    width:100%;
    display:grid;

    grid-template-columns:
        repeat(7,minmax(0,1fr));

    gap:4px 2px;

    text-align:center;

    font-size:11px;
    color:var(--lms-text);

    box-sizing:border-box;
}


/* Weekday headings */

.calendar_grid .day{
    width:100%;
    height:24px;

    display:flex;
    align-items:center;
    justify-content:center;

    padding:0!important;
    margin:0!important;

    font-size:10px;
    font-weight:600;

    color:#97a2b5;
}


/* Calendar day cells */

.calendar_grid span{
    width:100%;
    max-width:32px;
    height:32px;

    margin:0 auto!important;
    padding:0!important;

    display:flex;
    align-items:center;
    justify-content:center;

    border-radius:50%;

    cursor:pointer;

    font-size:11px;
    font-weight:500;

    position:relative;

    box-sizing:border-box;

    transition:
        background .15s ease,
        color .15s ease;
}


/* Hover */

.calendar_grid span:hover{
    background:#edf3ff;
    color:var(--lms-primary);
}


/* Today */

.calendar_grid .today{
    background:#edf3ff;
    color:var(--lms-primary);
    font-weight:700;
}


/* Selected date */

.calendar_grid .selected{
    background:var(--lms-primary);
    color:#fff;
    font-weight:700;
}


/* Event indicator */

.calendar_grid span.has-event:after{
    content:'';

    width:4px;
    height:4px;

    position:absolute;
    left:50%;
    bottom:3px;

    transform:translateX(-50%);

    background:#f59a45;
    border-radius:50%;
}


/* Empty cells */

.calendar_grid span:empty{
    cursor:default;
    background:transparent!important;
}


/* =========================================================
   RIGHT COLUMN - SMALLER SCREENS
   ========================================================= */

@media(max-width:1200px){

    .calendar_grid{
        gap:3px 1px;
    }

    .calendar_grid span{
        max-width:30px;
        height:30px;
    }

}


@media(max-width:1050px){

    .calendar_grid span{
        max-width:34px;
        height:34px;
    }

}


@media(max-width:768px){

    .calendar_card .calendar_header h5{
        font-size:15px;
    }

    .calendar_grid{
        gap:4px 2px;
    }

    .calendar_grid span{
        max-width:34px;
        height:34px;
    }

}

/* =========================================================
   EVENTS
   ========================================================= */

.events_card{
    max-height:245px;
    display:flex;
    flex-direction:column;
    height:200px;
}

#eventsList{
    overflow-y:auto;
    padding-right:3px;
}

.event_item{
    display:flex;
    align-items:flex-start;
    gap:8px;
    padding:9px;
    border-radius:9px;
    background:#f7f9fc;
    margin-bottom:8px;
}

.event_date{
    min-width:34px;
    height:36px;
    background:#edf4ff;
    color:var(--lms-primary);
    border-radius:8px;
    text-align:center;
    font-weight:700;
    line-height:1.05;
    padding-top:5px;
    font-size:11px;
}

.event_date small{
    display:block;
    font-size:8px;
    font-weight:500;
}

.event_info{
    flex:1;
    min-width:0;
}

.event_info strong{
    display:block;
    font-size:11px;
    color:var(--lms-text);
}

.event_info small{
    display:block;
    font-size:10px;
    color:var(--lms-muted);
    margin-top:3px;
}

.event_arrow{
    font-size:14px;
    color:#94a3b8;
    margin-top:4px;
}

.event_arrow a{
    color:inherit;
    text-decoration:none;
}


/* =========================================================
   BADGES
   ========================================================= */

.badge-header{
    display:flex;
    align-items:center;
    justify-content:space-between;
    margin-bottom:12px;
}

.badge-header-arrows{
    display:flex;
    gap:5px;
}

.badge_item_ref{
    padding:5px;
    text-align:center;
}

.badge_item_ref img{
    width:62px;
    height:92px;
    object-fit:contain;
}

.badge_item_ref p{
    font-size:11px;
    color:var(--lms-text);
    margin:4px 0 0;
}


/* =========================================================
   SKILLS
   ========================================================= */

.skills_inline{
    display:flex;
    flex-direction:column;
    gap:11px;
}

.skills_header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:8px;
    margin-bottom:12px;
}

.skills_header h4{
    font-size:17px;
    font-weight:700;
    margin:0;
    color:var(--lms-text);
}

.add-skill-btn{
    border:0;
    background:#edf3ff;
    color:var(--lms-primary);
    padding:7px 10px;
    border-radius:7px;
    font-size:11px;
    font-weight:600;
}

.skill_row{
    margin-bottom:10px;
}

.skill_top{
    display:flex;
    justify-content:space-between;
    font-size:12px;
    margin-bottom:5px;
}

.skill_name{
    font-weight:600;
    color:var(--lms-text);
}

.skill_percent{
    color:var(--lms-muted);
}

.skill_progress{
    height:5px;
    background:#e8ecf3;
    border-radius:8px;
    overflow:hidden;
}

.skill_progress span{
    display:block;
    height:100%;
    background:linear-gradient(90deg,#637fca,#315fbe);
    border-radius:8px;
}

.empty_skill{
    padding:10px 0;
    text-align:center;
}


/* =========================================================
   MODAL
   ========================================================= */

.skill_modal{
    position:fixed;
    inset:0;
    background:rgba(15,23,42,.45);
    display:none;
    align-items:center;
    justify-content:center;
    z-index:9999;
}

.skill_modal_content{
    background:#fff;
    padding:22px;
    border-radius:15px;
    width:320px;
    box-shadow:0 20px 60px rgba(0,0,0,.18);
}

.skill_modal_content input,
.skill_modal_content select{
    width:100%;
    margin-top:10px;
    padding:9px;
    border-radius:9px;
    border:1px solid #e3e8f0;
}

.modal_actions{
    display:flex;
    justify-content:flex-end;
    gap:8px;
    margin-top:16px;
}

.btn_cancel{
    background:#e8edf5;
    border:0;
    padding:7px 12px;
    border-radius:7px;
}

.btn_save{
    background:#315fbe;
    color:#fff;
    border:0;
    padding:7px 14px;
    border-radius:7px;
}


/* =========================================================
   EMPTY STATES
   ========================================================= */

.empty_panel{
    min-height:86px;
    display:flex;
    flex-direction:column;
    align-items:center;
    justify-content:center;
    gap:4px;
    text-align:center;
    border:1px dashed #dbe3ef;
    border-radius:12px;
    background:#fafcff;
}

.empty_panel i{
    font-size:20px;
    color:#8ba1c4;
}

.empty_panel span{
    font-size:10px;
    color:var(--lms-muted);
}

.empty_panel a{
    font-size:10px;
    font-weight:700;
    color:var(--lms-primary);
    text-decoration:none;
}

.course_empty_card{
    display:flex;
    align-items:center;
    gap:12px;
    padding:12px;
    border:1px dashed #d7e1f0;
    border-radius:12px;
    background:#fafcff;
}

.course_empty_card .empty_course_icon{
    width:42px;
    height:42px;
    border-radius:10px;
    background:#edf3ff;
    color:var(--lms-primary);
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:20px;
    flex:0 0 42px;
}

.course_empty_card strong{
    display:block;
    color:var(--lms-text);
    font-size:11px;
}

.course_empty_card span{
    display:block;
    color:var(--lms-muted);
    font-size:9px;
    margin-top:3px;
    line-height:1.4;
}

.course_empty_card a{
    display:inline-block;
    margin-top:5px;
    color:var(--lms-primary);
    font-size:9px;
    font-weight:700;
    text-decoration:none;
}


/* =========================================================
   ONBOARDING
   ========================================================= */

.onboarding_card{
    background:linear-gradient(145deg,#f8fbff,#eef4ff);
    border:1px solid #dce7f8;
}

.onboarding_card .onboarding_icon{
    width:38px;
    height:38px;
    border-radius:11px;
    background:#dfeaff;
    color:var(--lms-primary);

    display:flex;
    align-items:center;
    justify-content:center;

    font-size:18px;
    margin-bottom:9px;
}

.onboarding_card h5{
    margin:0 0 5px;
    font-size:15px;
    color:var(--lms-text);
}

.onboarding_card p{
    margin:0 0 10px;
    font-size:12px;
    line-height:1.5;
    color:var(--lms-muted);
}

.onboarding_steps{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:6px;
    margin-top:10px;
}

.onboarding_step{
    background:#fff;
    border:1px solid #e1e9f5;
    border-radius:8px;
    padding:7px;
    text-align:center;
}

.onboarding_step strong{
    display:block;
    font-size:9px;
    color:var(--lms-text);
}

.onboarding_step span{
    display:block;
    font-size:8px;
    color:var(--lms-muted);
    margin-top:2px;
}


/* =========================================================
   QUICK ACTIONS
   ========================================================= */

.quick_actions{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:7px;
}

.quick_action{
    border:1px solid #e2e8f2;
    background:#f8faff;
    border-radius:9px;
    padding:10px;
    color:var(--lms-text);
    text-decoration:none;
    font-size:11px;
    font-weight:600;
}

.quick_action i{
    color:var(--lms-primary);
    margin-right:4px;
}


/* =========================================================
   DASHBOARD SECTION TITLES
   ========================================================= */

.dashboard_section_title{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:10px;
}

.dashboard_section_title h4{
    margin:0;
    color:var(--lms-text);
    font-size:15px;
    font-weight:700;
}

.dashboard_section_title p{
    margin:3px 0 0;
    color:var(--lms-muted);
    font-size:10px;
}


/* =========================================================
   FOOTER ROW
   ========================================================= */

.dashboard_footer_row{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:18px;
    margin-top:18px;
}

.dashboard_footer_row .lms_card{
    margin-top:0!important;
}


/* =========================================================
   COLUMN BALANCE
   ========================================================= */

.lms_col_center .cw-carousel-wrap{
    margin-top:0;
}

@media(min-width:1051px){

    .lms_col_center .lms_card:first-child{
        min-height:590px;
    }

    .lms_col_center .cw-carousel-wrap{
        min-height:330px;
    }

}


/* =========================================================
   LARGE TYPOGRAPHY
   ========================================================= */

.lms_dashboard{
    font-size:14px;
}

.lms_dashboard .lms_card_pad{
    padding:20px;
}

.lms_card_header h4{
    font-size:19px;
}

.lms_card_header p{
    font-size:13px;
}

.lms_link{
    font-size:13px;
}

.lms_welcome h3{
    font-size:29px;
}

.lms_welcome p{
    font-size:16px;
}

.goal_copy small{
    font-size:13px;
}

.goal_copy strong{
    font-size:20px;
}

.skill_gap_card .lms_card_header h4{
    font-size:18px;
}

.skill_context{
    font-size:13px;
    line-height:1.65;
}

.skill_row_ref_top{
    font-size:14px;
}

.skill_status{
    font-size:11px;
    padding:4px 8px;
}

.recommend_info strong{
    font-size:13px;
}

.recommend_info small{
    font-size:11px;
}

.recommend_arrow{
    font-size:19px;
}
      
/* =========================================================
   LEADERBOARD
   ========================================================= */

.leaderboard_card{
    background:#fff;
    height: 310px;
}

.leaderboard_header_icon{
    width:38px;
    height:38px;

    display:flex;
    align-items:center;
    justify-content:center;

    border-radius:10px;

    background:#fff4df;
    color:#e99a32;

    font-size:18px;
}

.leaderboard_list{
    display:flex;
    flex-direction:column;
    gap:7px;
}

.leaderboard_item{
    display:flex;
    align-items:center;

    gap:9px;

    padding:9px 8px;

    border-radius:10px;

    transition:
        background .18s ease,
        transform .18s ease;
}

.leaderboard_item:hover{
    background:#f7f9fd;
    transform:translateX(2px);
}

.leaderboard_first{
    background:linear-gradient(
        90deg,
        #fff8e9,
        #fffdf8
    );

    border:1px solid #f8e8c3;
}

.leaderboard_rank{
    width:24px;
    height:24px;

    flex:0 0 24px;

    display:flex;
    align-items:center;
    justify-content:center;

    font-size:11px;
    font-weight:700;

    color:#8793a7;
}

.leaderboard_first .leaderboard_rank{
    color:#e59a2e;
    font-size:15px;
}

.leaderboard_avatar{
    width:38px;
    height:38px;

    flex:0 0 38px;

    border-radius:50%;

    object-fit:cover;

    border:2px solid #edf1f7;
}

.leaderboard_info{
    min-width:0;
    flex:1;
}

.leaderboard_info strong{
    display:block;

    color:var(--lms-text);

    font-size:12px;
    font-weight:700;

    white-space:nowrap;
    overflow:hidden;
    text-overflow:ellipsis;
}

.leaderboard_info small{
    display:block;

    color:var(--lms-muted);

    font-size:10px;

    margin-top:3px;
}

.leaderboard_points{
    text-align:right;
    flex:0 0 auto;
}

.leaderboard_points strong{
    display:block;

    color:var(--lms-primary);

    font-size:13px;
    font-weight:700;
}

.leaderboard_points small{
    display:block;

    color:var(--lms-muted);

    font-size:9px;

    margin-top:1px;
}

.leaderboard_empty{
    min-height:130px;

    display:flex;
    flex-direction:column;

    align-items:center;
    justify-content:center;

    text-align:center;

    gap:5px;

    color:var(--lms-muted);
}

.leaderboard_empty i{
    font-size:28px;
    color:#c4cedf;
}

.leaderboard_empty strong{
    color:var(--lms-text);
    font-size:12px;
}

.leaderboard_empty span{
    font-size:10px;
}

/* =========================================================
   FULL WIDTH - RECOMMENDED COURSES
   ========================================================= */

.recommended_full_section{
    width:100%;

    margin-top:20px;

    padding:20px;

    box-sizing:border-box;

    background:#fff;

    border:1px solid var(--lms-border);

    border-radius:18px;

    box-shadow:
        0 5px 20px rgba(35,58,98,.055);
}


/* =========================================================
   HEADER
   ========================================================= */

.recommended_full_header{

    display:flex;

    align-items:center;

    justify-content:space-between;

    gap:20px;

    margin-bottom:18px;

}


.recommended_title_area{

    min-width:0;

    flex:1;

}


.recommended_title_area h4{

    margin:0;

    color:var(--lms-text);

    font-size:19px;

    line-height:1.3;

    font-weight:700;

    letter-spacing:-.2px;

}


.recommended_title_area p{

    margin:5px 0 0;

    color:var(--lms-muted);

    font-size:12px;

    line-height:1.4;

}


/* =========================================================
   HEADER CONTROLS
   ========================================================= */

.recommended_courses_controls{

    display:flex;

    align-items:center;

    gap:7px;

    flex-shrink:0;

}


/* =========================================================
   PREVIOUS / NEXT BUTTON
   ========================================================= */

.recommended_arrow{

    width:36px;

    height:36px;

    min-width:36px;

    min-height:36px;

    padding:0;

    border:0;

    border-radius:50%;

    background:#edf3ff;

    color:var(--lms-primary);

    display:inline-flex;

    align-items:center;

    justify-content:center;

    font-size:14px;

    line-height:1;

    cursor:pointer;

    transition:
        background .18s ease,
        color .18s ease,
        transform .18s ease,
        box-shadow .18s ease;

}


.recommended_arrow:hover{

    background:#dce9ff;

    color:var(--lms-primary-dark);

    transform:translateY(-1px);

    box-shadow:
        0 4px 10px rgba(49,95,190,.12);

}


.recommended_arrow:active{

    transform:translateY(0);

    box-shadow:none;

}


.recommended_arrow:focus{

    outline:none;

}


/* =========================================================
   DISABLED ARROW
   ========================================================= */

.recommended_arrow.disabled,
.recommended_arrow:disabled{

    opacity:.4;

    cursor:not-allowed;

    transform:none;

    box-shadow:none;

}


/* =========================================================
   VIEW ALL
   ========================================================= */

.recommended_view_all{

    margin-left:7px;

    font-size:13px;

    white-space:nowrap;

}


/* =========================================================
   CAROUSEL
   ========================================================= */

.recommended_courses_carousel{

    width:100%;

    position:relative;

}


/* =========================================================
   OWL STAGE
   ========================================================= */

.recommended_courses_carousel .owl-stage{

    display:flex;

}


/* =========================================================
   OWL ITEM
   ========================================================= */

.recommended_courses_carousel .owl-item{

    height:auto;

    padding:3px;

    box-sizing:border-box;

}


/* =========================================================
   COURSE CARD
   ========================================================= */

.recommended_course_card{

    width:100%;

    height:100%;

    min-width:0;

    display:flex;

    flex-direction:column;

    box-sizing:border-box;

    overflow:hidden;

    background:#fff;

    border:1px solid #e6ebf3;

    border-radius:14px;

    text-decoration:none;

    color:inherit;

    transition:
        transform .18s ease,
        box-shadow .18s ease,
        border-color .18s ease;

}


.recommended_course_card:hover{

    transform:translateY(-3px);

    border-color:#d4dfef;

    box-shadow:
        0 10px 25px rgba(35,58,98,.09);

}


/* =========================================================
   COURSE IMAGE
   ========================================================= */

.recommended_course_thumb{

    width:100%;

    aspect-ratio:16 / 9;

    height:auto;

    min-height:0;

    background:#f4f7fb;

    overflow:hidden;

    display:flex;

    align-items:center;

    justify-content:center;

    flex:0 0 auto;

}


.recommended_course_thumb img{

    display:block;

    width:100%;

    height:100%;

    max-width:100%;

    max-height:100%;

    object-fit:contain;

    object-position:center center;

    background:#f4f7fb;

    transition:
        transform .25s ease;

}


.recommended_course_card:hover
.recommended_course_thumb img{

    transform:scale(1.025);

}


/* =========================================================
   COURSE BODY
   ========================================================= */

.recommended_course_body{

    padding:14px;

    display:flex;

    flex-direction:column;

    flex:1;

    box-sizing:border-box;

}


/* =========================================================
   COURSE TITLE
   ========================================================= */

.recommended_course_body h5{

    margin:0 0 7px;

    color:var(--lms-text);

    font-size:14px;

    line-height:1.45;

    font-weight:700;

    display:-webkit-box;

    -webkit-line-clamp:2;

    -webkit-box-orient:vertical;

    overflow:hidden;

    min-height:41px;

}


/* =========================================================
   CATEGORY
   ========================================================= */

.recommended_course_category{

    display:block;

    color:var(--lms-muted);

    font-size:11px;

    line-height:1.4;

    white-space:nowrap;

    overflow:hidden;

    text-overflow:ellipsis;

    margin-bottom:12px;

}


/* =========================================================
   FOOTER
   ========================================================= */

.recommended_course_footer{

    display:flex;

    align-items:center;

    justify-content:space-between;

    margin-top:auto;

    padding-top:10px;

    border-top:1px solid #edf0f5;

    color:var(--lms-muted);

    font-size:10px;

}


.recommended_course_type{

    display:flex;

    align-items:center;

    gap:4px;

}


.recommended_course_type i{

    color:var(--lms-primary);

    font-size:11px;

}


/* =========================================================
   ARROW INSIDE COURSE CARD
   ========================================================= */

.recommended_course_arrow{

    width:26px;

    height:26px;

    min-width:26px;

    display:flex;

    align-items:center;

    justify-content:center;

    border-radius:50%;

    background:#edf4ff;

    color:var(--lms-primary);

    font-size:18px;

    line-height:1;

    transition:
        background .18s ease,
        transform .18s ease;

}


.recommended_course_card:hover
.recommended_course_arrow{

    background:#dce9ff;

    transform:translateX(2px);

}


/* =========================================================
   EMPTY STATE
   ========================================================= */

.recommended_empty{

    width:100%;

    min-height:150px;

    display:flex;

    align-items:center;

    justify-content:center;

    gap:14px;

    padding:20px;

    box-sizing:border-box;

    border:1px dashed #dbe3ef;

    border-radius:13px;

    background:#fafcff;

}


.recommended_empty_icon{

    width:48px;

    height:48px;

    min-width:48px;

    border-radius:12px;

    display:flex;

    align-items:center;

    justify-content:center;

    background:#edf3ff;

    color:var(--lms-primary);

    font-size:20px;

}


.recommended_empty_content{

    display:flex;

    flex-direction:column;

    gap:4px;

}


.recommended_empty_content strong{

    color:var(--lms-text);

    font-size:13px;

    font-weight:700;

}


.recommended_empty_content span{

    color:var(--lms-muted);

    font-size:11px;

    line-height:1.5;

}


.recommended_empty_content a{

    color:var(--lms-primary);

    font-size:11px;

    font-weight:700;

    text-decoration:none;

    margin-top:3px;

}


.recommended_empty_content a:hover{

    text-decoration:underline;

}


/* =========================================================
   REMOVE DEFAULT OWL NAV
   We use our own buttons above.
   ========================================================= */

.recommended_courses_carousel .owl-nav{

    display:none !important;

}


/* =========================================================
   RESPONSIVE - TABLET
   ========================================================= */

@media(max-width:1200px){

    .recommended_full_section{

        padding:18px;

    }

}


/* =========================================================
   RESPONSIVE - 900
   ========================================================= */

@media(max-width:900px){

    .recommended_full_header{

        align-items:flex-start;

    }

    .recommended_title_area h4{

        font-size:18px;

    }

}


/* =========================================================
   RESPONSIVE - MOBILE
   ========================================================= */

@media(max-width:600px){

    .recommended_full_section{

        margin-top:16px;

        padding:15px;

        border-radius:14px;

    }


    .recommended_full_header{

        flex-direction:column;

        gap:12px;

        margin-bottom:14px;

    }


    .recommended_courses_controls{

        width:100%;

        justify-content:flex-end;

    }


    .recommended_title_area{

        width:100%;

    }


    .recommended_title_area h4{

        font-size:17px;

    }


    .recommended_title_area p{

        font-size:11px;

    }


    .recommended_arrow{

        width:34px;

        height:34px;

        min-width:34px;

        min-height:34px;

    }


    .recommended_view_all{

        font-size:12px;

    }


    .recommended_course_body{

        padding:12px;

    }


    .recommended_course_body h5{

        font-size:13px;

    }

}


/* =========================================================
   VERY SMALL MOBILE
   ========================================================= */

@media(max-width:380px){

    .recommended_full_section{

        padding:12px;

    }


    .recommended_title_area h4{

        font-size:16px;

    }


    .recommended_course_body h5{

        font-size:12px;

    }

}      

/* =========================================================
   RESPONSIVE - 1400+
   ========================================================= */

@media(min-width:1400px){

    .lms_main_grid{
        grid-template-columns:255px minmax(0,1fr) 285px;
        gap:16px;
    }

    .lms_welcome{
        grid-template-columns:minmax(320px,1fr) minmax(300px,360px);
        gap:16px;
    }

}


/* =========================================================
   RESPONSIVE - 1200
   ========================================================= */

@media(max-width:1200px){

    .lms_welcome{
        grid-template-columns:1fr minmax(260px,360px);
    }

    .lms_main_grid{
        grid-template-columns:240px minmax(0,1fr) 250px;
    }

}


/* =========================================================
   RESPONSIVE - 1050
   ========================================================= */

@media(max-width:1050px){

    .lms_welcome{
        grid-template-columns:1fr 1fr;
    }

    .lms_welcome .mentor_top{
        grid-column:1/-1;
    }

    .lms_main_grid{
        grid-template-columns:1fr 1fr;
    }

    .lms_col_center{
        grid-column:1/-1;
        grid-row:1;
    }

    .lms_col_left{
        grid-column:1;
        grid-row:2;
    }

    .lms_col_right{
        grid-column:2;
        grid-row:2;
    }

    .lms_col_center .lms_card:first-child{
        min-height:560px;
    }

}


/* =========================================================
   RESPONSIVE - 768
   ========================================================= */

@media(max-width:768px){

    .lms_welcome,
    .lms_main_grid{
        grid-template-columns:1fr;
    }

    .lms_welcome .mentor_top,
    .lms_col_center,
    .lms_col_left,
    .lms_col_right{
        grid-column:auto;
        grid-row:auto;
    }

    .lms_welcome h3{
        font-size:24px;
    }

    .lms_welcome p{
        font-size:14px;
    }

    .progress_top{
        grid-template-columns:1fr;
        gap:20px;
    }

    .progress_metrics{
        margin-top:8px;
    }

    .hours_chart{
        height:220px;
    }

    .lms_col_center .lms_card:first-child{
        min-height:auto;
    }

    .course_strip{
        grid-template-columns:1fr;
    }

    .lms_dashboard{
        padding-top:0;
    }

    /*
     * Keep full image behavior on mobile too.
     * Do NOT set a fixed height here.
     */
    .cw-carousel .thumb{
        aspect-ratio:16 / 9;
        height:auto;
    }

    .cw-carousel .thumb img{
        width:100%;
        height:100%;
        object-fit:contain;
    }

}
</style>


@php
    $total = Auth::user()->totalStudentCourses();
    $isGamification =
        Settings('gamification_status') &&
        Settings('gamification_leaderboard_show_badges_status');

    $completedCourses = (int) ($total['complete'] ?? 0);
    $inProgressCourses = (int) ($total['process'] ?? 0);
    // Keep dashboard totals consistent when the LMS course relation is delayed/filtered.
    $enrolledCourses = max((int) Auth::user()->courses->count(), $completedCourses + $inProgressCourses);
    $progressPercent = $enrolledCourses > 0
        ? min(100, round(($completedCourses / $enrolledCourses) * 100))
        : 0;
@endphp

<div class="main_content_iner main_content_padding lms_dashboard">
    <div class="container-fluid g-0">

        @if ($unreadCount > 0)
        <div class="dashboard_title mb-3">
            <h3>
                You have {{ $unreadCount }} new announcements,
                <a href="{{ route('announcements.list', ['id' => Auth::user()->id]) }}">click here</a>
            </h3>
        </div>
        @endif

        <!-- ================= WELCOME ================= -->
        <div class="lms_welcome">

            <div>
                <h3>{{ @$wish_string }}, {{ Auth::user()->name }}!</h3>
                <p>Your personalized learning dashboard is ready.</p>
            </div>

            <div class="learning_goal">
                <div class="goal_icon"><i class="ti-time"></i></div>
                <div class="goal_copy">
                    <small>Learning time today</small>
                    <strong><span id="goalHours">{{ $day['h'] }}</span>h {{ $day['m'] }}m</strong>
                    <div class="goal_bar"><span></span></div>
                </div>
            </div>


        </div>

        <!-- ================= MAIN 3-COLUMN DASHBOARD ================= -->
        <div class="lms_main_grid">

            <!-- ================= LEFT COLUMN ================= -->
            <div class="lms_col lms_col_left">

                <!-- SKILL GAP -->
                <div class="lms_card lms_card_pad skill_gap_card">
                    <div class="lms_card_header">
                        <div>
                            <h4>Skill Gap Analysis</h4>
                            <p>Your current learning skills</p>
                        </div>
                        <i class="ti-bar-chart" style="opacity:.75;"></i>
                    </div>

                    <div class="skill_context">
                        Add skills from your learning profile to track your progress and identify areas to improve.
                    </div>

                    <div id="skillGapPreview">
                        <div class="skill_row_ref">
                            <div class="skill_row_ref_top">
                                <strong>Learning Progress</strong>
                                <span class="skill_status good">{{ $progressPercent }}%</span>
                            </div>
                            <div class="skill_bar_ref green">
                                <span style="width:{{ $progressPercent }}%"></span>
                            </div>
                        </div>

                        <div class="skill_row_ref">
                            <div class="skill_row_ref_top">
                                <strong>Courses In Progress</strong>
                                <span class="skill_status mid">{{ $inProgressCourses }}</span>
                            </div>
                            <div class="skill_bar_ref orange">
                                <span style="width:{{ $enrolledCourses ? min(100, round(($inProgressCourses / $enrolledCourses) * 100)) : 0 }}%"></span>
                            </div>
                        </div>

                        <div class="skill_row_ref">
                            <div class="skill_row_ref_top">
                                <strong>Courses Completed</strong>
                                <span class="skill_status good">{{ $completedCourses }}</span>
                            </div>
                            <div class="skill_bar_ref green">
                                <span style="width:{{ $progressPercent }}%"></span>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- YOUR SKILLS -->
                <div class="lms_card lms_card_pad">
                    <div class="skills_header">
                        <h4>Your Skills</h4>
                        <button class="add-skill-btn" id="openSkillModal">+ Add Skill</button>
                    </div>

                    <div id="skillsContainer" class="skills_inline">
                        <div class="empty_skill" id="emptySkill">
                            <div style="font-size:20px;color:#9bb0cf;margin-bottom:5px;"><i class="ti-target"></i></div>
                            <p class="text-muted mb-1" style="font-size:10px;">Build your skill profile</p>
                            <p class="text-muted mb-0" style="font-size:9px;line-height:1.4;">Add skills to unlock better course and learning-path recommendations.</p>
                        </div>
                    </div>
                </div>
              
              <!-- ================= LEADERBOARD ================= -->
<div class="lms_card lms_card_pad leaderboard_card">

    <div class="lms_card_header">
        <div>
            <h4>Leaderboard</h4>
            <p>Top learners this month</p>
        </div>

        <div class="leaderboard_header_icon">
            <i class="ti-cup"></i>
        </div>
    </div>

    <div class="leaderboard_list">

        @forelse(collect($leaderboard ?? [])->take(5) as $index => $learner)

            <div class="leaderboard_item {{ $index == 0 ? 'leaderboard_first' : '' }}">

                <div class="leaderboard_rank">
                    @if($index == 0)
                        <i class="ti-cup"></i>
                    @else
                        {{ $index + 1 }}
                    @endif
                </div>

                <img
                    class="leaderboard_avatar"
                    src="{{ getProfileImage($learner->image ?? null, $learner->name ?? 'Learner') }}"
                    alt="{{ $learner->name ?? 'Learner' }}"
                >

                <div class="leaderboard_info">
                    <strong>
                        {{ $learner->name ?? 'Learner' }}
                    </strong>

                    <small>
                        {{ $learner->courses_completed ?? 0 }} courses completed
                    </small>
                </div>

                <div class="leaderboard_points">
                    <strong>
                        {{ $learner->points ?? 0 }}
                    </strong>
                    <small>pts</small>
                </div>

            </div>

        @empty

            <div class="leaderboard_empty">
                <i class="ti-cup"></i>

                <strong>No leaderboard data yet</strong>

                <span>
                    Complete courses and earn points to appear here.
                </span>
            </div>

        @endforelse

    </div>

</div>


                <!-- QUICK ACTIONS: USEFUL FOR NEW LEARNERS -->
                <div class="lms_card lms_card_pad onboarding_card">
                    <div class="onboarding_icon"><i class="ti-rocket"></i></div>
                    <h5>Start your learning journey</h5>
                    <p>Choose a course, add your skills and begin building your personalized learning path.</p>
                    <div class="quick_actions">
                        <a class="quick_action" href="{{tenantRoute('catalog')}}"><i class="ti-book"></i> Browse courses</a>
                        <a class="quick_action" href="#" id="quickAddSkill"><i class="ti-plus"></i> Add skills</a>
                    </div>
                </div>

            </div>

            <!-- ================= CENTER COLUMN ================= -->
            <div class="lms_col lms_col_center">

                <!-- LEARNING PROGRESS -->
                <div class="lms_card lms_card_pad">
                    <div class="lms_card_header">
                        <div>
                            <h4>Learning Progress</h4>
                            <p>Track your overall course completion</p>
                        </div>
                        <span style="color:#9aa8bd;font-size:15px;">••••</span>
                    </div>

                    <div class="progress_top">
                        <div class="progress_ring"
                             style="--percent:{{ $progressPercent }}%;">
                            <div class="progress_ring_content">
                                <strong>{{ $progressPercent }}%</strong>
                                <small>Complete</small>
                            </div>
                        </div>

                        <div>
                            <!-- <div class="progress_track">
                                <span style="width:{{ $progressPercent }}%;"></span>
                            </div> -->

                            <div class="progress_metrics">

                               <div class="progress_metric">
                                    <strong>{{ Auth::user()->totalCertificate() ?? 0 }}</strong>
                                    <span><a href="{{ route('tenant.myCertificate') }}">Certificates</a></span>
                                </div>
                                <div class="progress_metric">
                                    <strong>{{ $completedCourses }}</strong>
                                    <span><a href="{{ route('tenant.myCourses') }}">Courses Completed</a></span>
                                </div>
                                
                                <div class="progress_metric">
                                    <strong>{{ $inProgressCourses }}</strong>
                                    <span><a href="{{ route('tenant.myCourses') }}">In Progress</a></span>
                                </div>
                                <div class="progress_metric">
                                    <strong>0</strong>
                                    <span><a href="{{ route('tenant.myCourses') }}">Overdue</a></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- HOURS CHART -->
                    <div class="hours_block">
                        <div class="hours_head">
                            <div class="hours_value">
                                <strong><span id="hoursValue">0h</span> <span id="minutesValue">0m</span></strong>
                                <small>Total learning time</small>
                            </div>

                            <div class="hours_tabs">
                                <span class="active" data-type="day" data-h="{{ $day['h'] }}" data-m="{{ $day['m'] }}">Day</span>
                                <span data-type="week" data-h="{{ $week['h'] }}" data-m="{{ $week['m'] }}">Weekly</span>
                                <span data-type="month" data-h="{{ $month['h'] }}" data-m="{{ $month['m'] }}">Monthly</span>
                            </div>
                        </div>

                        <div class="hours_chart">
                            <canvas id="hoursSpentChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- CONTINUE WATCHING / MY COURSES -->
                <div class="lms_card lms_card_pad cw-carousel-wrap">
                    <div class="cw-header">
                        <div>
                            <h4 class="mb-0" style="font-size:16px;color:var(--lms-text);">My Courses</h4>
                            <p style="font-size:11px;color:var(--lms-muted);margin:3px 0 0;">Continue where you left off</p>
                        </div>
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

                            @if($percentage < 100)
                            <div class="continue_card">
                                <div class="thumb">
                                    <img src="{{ getCourseImage($course->image) }}" alt="{{ $course->title }}" loading="lazy" >
                                </div>

                                <div class="card_body">
                                    <h6>{{ $course->title }}</h6>

                                    <div class="progress mb-2" style="--percent:{{ $percentage }}">
                                        <div class="progress-bar" style="width:{{ $percentage }}%"></div>
                                        <span class="progress-badge">{{ $percentage }}%</span>
                                    </div>

                                    <a href="{{ route('tenant.continueCourse',[$course->slug]) }}"
                                       class="theme_btn w-100">Continue</a>
                                </div>
                            </div>
                            @endif
                        @endforeach
                    </div>

                    @if(($total['process'] ?? 0) === 0)
                        <div class="course_empty_card" style="margin-top:10px;">
                            <div class="empty_course_icon"><i class="ti-book"></i></div>
                            <div>
                                <strong>No courses in progress</strong>
                                <span>Start a course and your active learning will appear here.</span>
                                <a href="{{tenantRoute('catalog')}}">Explore courses →</a>
                            </div>
                        </div>
                    @endif
                </div>

            </div>

            <!-- ================= RIGHT COLUMN ================= -->
            <div class="lms_col lms_col_right">


                <!-- PROFILE / LEARNING SNAPSHOT -->
                <div class="lms_card lms_card_pad">
                    <div class="profile_compact">
                        <img src="{{ getProfileImage(Auth::user()->image,Auth::user()->name) }}"
                             alt="{{ Auth::user()->name }}">
                        <div>
                            <strong>{{ Auth::user()->name }}</strong>
                            <small>{{ (Auth::user()->tenant_id == CORPORATE) ? 'Learner' : 'Student' }}</small>
                        </div>
                    </div>

                    <div class="profile_stats_ref">
                        <div class="profile_stat_ref">
                            <strong>{{ $enrolledCourses }}</strong>
                            <span>Total Courses</span>
                        </div>
                        <div class="profile_stat_ref">
                            <strong><span id="studyHours">0h</span></strong>
                            <span>Study Hours</span>
                        </div>
                    </div>
                </div>

               

                

                <!-- CALENDAR -->
                <div class="lms_card lms_card_pad calendar_card">
                    <div class="calendar_header">
                        <h5 id="calendarTitle"></h5>
                        <div class="calendar_nav">
                            <span id="prevMonth">&lsaquo;</span>
                            <span id="nextMonth">&rsaquo;</span>
                        </div>
                    </div>

                    <div class="calendar_grid" id="calendarGrid"></div>
                </div>

                 <!-- LEARNING ASSISTANT -->
                <div class="lms_card lms_card_pad mentor_card">
                    <div class="lms_card_header">
                        <h4><i class="ti-comments"></i> Mentor</h4>
                        <i class="ti-angle-down" style="font-size:10px;opacity:.8;"></i>
                    </div>
                    <!-- <div class="mentor_message">How can I assist you today?</div> -->
                    <div class="mentor_actions">
                        <a class="mentor_action" href="{{ route('tenant.myCourses') }}">
                            <i class="ti-help-alt"></i> About my courses
                        </a>
                        <a class="mentor_action" href="{{tenantRoute('catalog')}}">
                            <i class="ti-book"></i> Explore courses
                        </a>
                    </div>
                </div>

                <!-- SCHEDULE -->
                <div class="lms_card lms_card_pad events_card">
                    <h4 style="font-size:14px;color:var(--lms-text);margin:0 0 10px;">Upcoming Sessions</h4>
                    <div id="eventsList">
                        <p class="text-muted mb-0" style="font-size:11px;">Select a date to view events</p>
                    </div>
                </div>

                <!-- BADGES -->
                @if($isGamification)
                <div class="lms_card lms_card_pad badge-carousel-wrap">
                    <div class="badge-header">
                        <h4 style="font-size:14px;color:var(--lms-text);margin:0;">Upcoming Badges</h4>
                        <div class="badge-header-arrows">
                            <span class="header-arrow prev">&lsaquo;</span>
                            <span class="header-arrow next">&rsaquo;</span>
                        </div>
                    </div>

                    <div id="badgeCarousel" class="owl-carousel badge-carousel">
                        @foreach($badges ?? [] as $type)
                            @foreach($type->take(1) as $badge)
                                <div class="badge_item_ref">
                                    <img src="{{ asset($badge->image) }}" alt="{{ $badge->title }}">
                                    <p>{{ $badge->title }}</p>
                                </div>
                            @endforeach
                        @endforeach
                    </div>
                </div>
                @endif

            </div>
        </div>
        <!-- =========================================================
     FULL WIDTH - RECOMMENDED COURSES
     ========================================================= -->

<div class="recommended_full_section">

    <!-- ================= HEADER ================= -->

    <div class="recommended_full_header">

        <div class="recommended_title_area">

            <h4>
                Recommended Courses
            </h4>

            <p>
                Courses selected based on your learning interests and skills
            </p>

        </div>


        <!-- ================= CONTROLS ================= -->

        <div class="recommended_courses_controls">

            <button
                type="button"
                class="recommended_arrow recommended_prev"
                aria-label="Previous recommended courses"
            >
                <i class="ti-angle-left"></i>
            </button>


            <button
                type="button"
                class="recommended_arrow recommended_next"
                aria-label="Next recommended courses"
            >
                <i class="ti-angle-right"></i>
            </button>


            <a
                href="{{ tenantRoute('catalog') }}"
                class="lms_link recommended_view_all"
            >
                View all
            </a>

        </div>

    </div>


    <!-- =====================================================
         COURSE CAROUSEL
         ===================================================== -->

  @if(collect($recommended_courses ?? [])->count())

    <div id="recommendedCoursesCarousel"
         class="owl-carousel recommended_courses_carousel">

        @foreach(collect($recommended_courses)->take(10) as $course)

            <a href="{{ route('courseDetailsView', [$course->slug]) }}"
               class="recommended_course_card">

                <div class="recommended_course_thumb">
                    <img src="{{ getCourseImage($course->image) }}"
                         alt="{{ $course->title }}"
                         loading="lazy">
                </div>

                <div class="recommended_course_body">

                    <h5>{{ $course->title }}</h5>

                    <span class="recommended_course_category">
                        {{ $course->category->name ?? 'Course' }}
                    </span>

                    <div class="recommended_course_footer">
                        <span class="recommended_course_type">
                            <i class="ti-book"></i>
                            Course
                        </span>

                        <span class="recommended_course_arrow">
                            &rsaquo;
                        </span>
                    </div>

                </div>

            </a>

        @endforeach

    </div>

@else

    <div class="recommended_empty">

        <div class="recommended_empty_icon">
            <i class="ti-book"></i>
        </div>

        <div class="recommended_empty_content">

            <strong>No recommended courses yet</strong>

            <span>
                Complete a course or add skills to get
                personalized recommendations.
            </span>

            <a href="{{ tenantRoute('catalog') }}">
                Browse courses →
            </a>

        </div>

    </div>

@endif

    </div>
</div>

<!-- ADD SKILL MODAL -->
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
        labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
        hours: [0, 0, 0, 0, 0, 0, 0]
    },
    week: {
        labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
        hours: [0, 0, 0, 0]
    },
    month: {
        labels: [
            'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
            'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
        ],
        hours: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
    }
};

let hoursChart = null;


/* =========================================================
   NORMALIZE CHART DATA
   ========================================================= */

function normalizeHoursChartData(chart) {

    const fallback = {
        day: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            hours: [0, 0, 0, 0, 0, 0, 0]
        },

        week: {
            labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
            hours: [0, 0, 0, 0]
        },

        month: {
            labels: [
                'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
            ],
            hours: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
        }
    };

    if (!chart || typeof chart !== 'object') {
        return fallback;
    }

    ['day', 'week', 'month'].forEach(type => {

        const source = chart[type];

        if (!source || typeof source !== 'object') {
            return;
        }

        const labels = Array.isArray(source.labels)
            ? source.labels
            : fallback[type].labels;

        const values = Array.isArray(source.hours)
            ? source.hours.map(value => Number(value) || 0)
            : fallback[type].hours;

        hoursData[type] = {
            labels: labels.length
                ? labels
                : fallback[type].labels,

            hours: values.length
                ? values
                : fallback[type].hours
        };
    });

    return hoursData;
}


/* =========================================================
   UPDATE TOTAL LEARNING TIME
   ========================================================= */

function updateLearningTime(hours, minutes) {

    const h = Number(hours) || 0;
    const m = Number(minutes) || 0;

    const hoursValue =
        document.getElementById('hoursValue');

    const minutesValue =
        document.getElementById('minutesValue');

    const studyHours =
        document.getElementById('studyHours');

    const progressStudyHours =
        document.getElementById('progressStudyHours');

    const studyMinutes =
        document.getElementById('studyMinutes');

    if (hoursValue) {
        hoursValue.innerText = h + 'h';
    }

    if (minutesValue) {
        minutesValue.innerText = m + 'm';
    }

    if (studyHours) {
        studyHours.innerText = h + 'h';
    }

    if (progressStudyHours) {
        progressStudyHours.innerText = h + 'h';
    }

    if (studyMinutes) {
        studyMinutes.innerText = m + 'm';
    }
}


/* =========================================================
   FORMAT HOURS FOR TOOLTIP / Y AXIS
   ========================================================= */

function formatLearningTime(value) {

    const totalHours = Number(value) || 0;

    if (totalHours === 0) {
        return '0h';
    }

    const wholeHours = Math.floor(totalHours);

    const minutes = Math.round(
        (totalHours - wholeHours) * 60
    );

    if (wholeHours === 0) {
        return minutes + 'm';
    }

    if (minutes === 0) {
        return wholeHours + 'h';
    }

    return wholeHours + 'h ' + minutes + 'm';
}


/* =========================================================
   DYNAMIC Y AXIS
   ---------------------------------------------------------
   The Y axis is calculated from the learner's actual
   learning-time values returned in data.chart.

   No WebsiteController change is required.
   ========================================================= */

function getDynamicYAxis(values) {

    const cleanValues = values
        .map(value => Number(value) || 0)
        .filter(value => value >= 0);

    const maxValue = cleanValues.length
        ? Math.max(...cleanValues)
        : 0;


    /*
     * New learner / no learning activity.
     *
     * Keep a small readable graph instead of showing
     * a huge fixed 10h/20h scale.
     */
    if (maxValue <= 0) {

        return {
            max: 1,
            step: 0.2
        };
    }


    /*
     * Very small learning times.
     *
     * Example:
     * 0.1h = 6 minutes
     * 0.5h = 30 minutes
     */
    if (maxValue <= 0.5) {

        return {
            max: 0.5,
            step: 0.1
        };
    }


    if (maxValue <= 1) {

        return {
            max: 1,
            step: 0.2
        };
    }


    if (maxValue <= 2) {

        return {
            max: 2,
            step: 0.5
        };
    }


    if (maxValue <= 5) {

        return {
            max: 5,
            step: 1
        };
    }


    if (maxValue <= 10) {

        return {
            max: 10,
            step: 2
        };
    }


    if (maxValue <= 20) {

        return {
            max: 20,
            step: 5
        };
    }


    if (maxValue <= 50) {

        return {
            max: 50,
            step: 10
        };
    }


    if (maxValue <= 100) {

        return {
            max: 100,
            step: 20
        };
    }


    /*
     * Larger learners.
     *
     * Add 20% headroom and round to a clean
     * 50-hour boundary.
     */
    const paddedMax = maxValue * 1.2;

    const dynamicMax =
        Math.ceil(paddedMax / 50) * 50;

    return {
        max: dynamicMax,
        step: dynamicMax <= 250 ? 50 : 100
    };
}


/* =========================================================
   LOAD LEARNER LEARNING TIME
   ========================================================= */

function loadUserSpentHours() {

    const loginHoursUrl =
        "{{ route('tenant.getLoginHours', session('tenant_slug')) }}";


    fetch(loginHoursUrl, {
        method: 'GET',

        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },

        credentials: 'same-origin'
    })

    .then(response => {

        if (!response.ok) {

            throw new Error(
                'Learning hours request failed: HTTP ' +
                response.status
            );
        }

        return response.json();
    })

    .then(data => {

        console.log(
            'Learning hours response:',
            data
        );


        /*
         * Update the total learning-time display.
         */
        updateLearningTime(
            data.hours,
            data.minutes
        );


        /*
         * Only replace chart data when the backend
         * actually sends data.chart.
         *
         * This prevents:
         *
         * Cannot read properties of undefined
         * (reading 'day')
         */
        if (
            data &&
            data.chart &&
            typeof data.chart === 'object'
        ) {

            normalizeHoursChartData(
                data.chart
            );
        }


        /*
         * Render Day graph initially.
         */
        renderChart('day');
    })

    .catch(error => {

        console.error(
            'Learning hours:',
            error
        );


        /*
         * Still display an empty graph if the
         * learning-time request fails.
         */
        renderChart('day');
    });
}


/* =========================================================
   RENDER LEARNING GRAPH
   ========================================================= */

function renderChart(type = 'day') {

    const canvas =
        document.getElementById(
            'hoursSpentChart'
        );


    if (
        !canvas ||
        typeof Chart === 'undefined'
    ) {

        console.error(
            'Hours chart could not initialize: ' +
            'canvas or Chart.js is missing.'
        );

        return;
    }


    /*
     * Destroy old chart before creating
     * the selected Day / Weekly / Monthly chart.
     */
    if (hoursChart) {

        hoursChart.destroy();

        hoursChart = null;
    }


    const source =
        hoursData[type] ||
        hoursData.day;


    const labels =
        Array.isArray(source.labels) &&
        source.labels.length
            ? source.labels
            : hoursData.day.labels;


    const values =
        Array.isArray(source.hours)
            ? source.hours.map(
                value => Number(value) || 0
            )
            : labels.map(() => 0);


    /*
     * Detect whether this learner has
     * any learning activity.
     */
    const hasActivity =
        values.some(value => value > 0);


    /*
     * Calculate Y axis specifically from
     * THIS learner's graph values.
     */
    const yAxis =
        getDynamicYAxis(values);


    const chartWrap =
        canvas.parentElement;


    if (chartWrap) {

        chartWrap.classList.toggle(
            'chart_no_data',
            !hasActivity
        );
    }


    canvas.style.display = 'block';


    /* =====================================================
       CHART.JS 2.x
       ===================================================== */

    hoursChart = new Chart(
        canvas.getContext('2d'),
        {

            type: 'line',

            data: {

                labels: labels,

                datasets: [{

                    label: 'Learning Hours',

                    data: values,

                    borderColor: '#4776c9',

                    backgroundColor:
                        'rgba(71,118,201,0.10)',

                    pointBackgroundColor:
                        '#315fbe',

                    pointBorderColor:
                        '#ffffff',

                    pointBorderWidth: 2,

                    pointRadius:
                        hasActivity ? 4 : 3,

                    pointHoverRadius: 6,

                    borderWidth: 3,

                    lineTension: 0.35,

                    fill: true
                }]
            },


            options: {

                responsive: true,

                maintainAspectRatio: false,

                animation: {
                    duration: 450
                },


                legend: {
                    display: false
                },


                tooltips: {

                    enabled: hasActivity,

                    callbacks: {

                        label: function(
                            tooltipItem
                        ) {

                            const value =
                                Number(
                                    tooltipItem.yLabel
                                ) || 0;

                            return ' Learning time: ' +
                                formatLearningTime(
                                    value
                                );
                        }
                    }
                },


                scales: {

                    /* ================= X AXIS ================= */

                    xAxes: [{

                        gridLines: {
                            display: false,
                            drawBorder: false
                        },

                        ticks: {

                            fontSize: 13,

                            fontColor:
                                '#71809a',

                            padding: 6
                        }
                    }],


                    /* ================= Y AXIS ================= */

                    yAxes: [{

                        ticks: {

                            beginAtZero: true,

                            min: 0,

                            /*
                             * THIS IS THE IMPORTANT PART:
                             * The maximum changes according
                             * to the learner's actual time.
                             */
                            max: yAxis.max,

                            stepSize: yAxis.step,

                            fontSize: 13,

                            fontColor:
                                '#71809a',

                            padding: 8,

                            callback: function(
                                value
                            ) {

                                return formatLearningTime(
                                    value
                                );
                            }
                        },


                        gridLines: {

                            color:
                                '#edf1f6',

                            drawBorder:
                                false,

                            zeroLineColor:
                                '#dfe5ef'
                        }
                    }]
                }
            }
        }
    );
}


/* =========================================================
   INITIALIZE GRAPH
   ========================================================= */

document.addEventListener(
    'DOMContentLoaded',
    function() {

        /*
         * Render immediately so the graph area
         * exists even before AJAX completes.
         */
        renderChart('day');


        /*
         * Load actual learner learning data.
         */
        loadUserSpentHours();


        /*
         * Day / Weekly / Monthly tabs.
         */
        document
            .querySelectorAll(
                '.hours_tabs span'
            )
            .forEach(tab => {

                tab.addEventListener(
                    'click',
                    function() {

                        document
                            .querySelectorAll(
                                '.hours_tabs span'
                            )
                            .forEach(
                                item =>
                                    item.classList.remove(
                                        'active'
                                    )
                            );


                        this.classList.add(
                            'active'
                        );


                        const type =
                            this.getAttribute(
                                'data-type'
                            ) || 'day';


                        renderChart(type);
                    }
                );
            });
    }
);
</script>
<script>
$(document).ready(function() {

    /* ========== INIT OWL ========== */
    const cw = $('#cwCarousel').length ? $('#cwCarousel').owlCarousel({
        loop: false,
        margin: 7,
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
    }) : null;

    const badge = $('#badgeCarousel').length ? $('#badgeCarousel').owlCarousel({
        loop: false,
        margin: 5,
        nav: false,
        dots: false,
        // responsive: {
        //     0:    { items: 1 },
        //     768:  { items: 2 },
        //     1200: { items: 3 }
        // }
    }) : null;

    /* ========== HEADER ARROWS CONTROL ========== */

    // Continue Watching
    $('.cw-header-arrows .next').on('click', function() {
        if (cw) cw.trigger('next.owl.carousel');
    });

    $('.cw-header-arrows .prev').on('click', function() {
        if (cw) cw.trigger('prev.owl.carousel');
    });

    // Upcoming Badges
    $('.badge-header-arrows .next').on('click', function() {
        if (badge) badge.trigger('next.owl.carousel');
    });

    $('.badge-header-arrows .prev').on('click', function() {
        if (badge) badge.trigger('prev.owl.carousel');
    });

});
</script>

<script>
(function ($) {

    'use strict';


    /* =====================================================
       RECOMMENDED COURSES CAROUSEL
       ===================================================== */

    function initRecommendedCoursesCarousel() {

        var $carousel = $('#recommendedCoursesCarousel');


        /* ---------------------------------------------
           Carousel does not exist
           --------------------------------------------- */

        if (!$carousel.length) {

            return;

        }


        /* ---------------------------------------------
           Owl Carousel not loaded
           --------------------------------------------- */

        if (typeof $.fn.owlCarousel !== 'function') {

            console.error(
                'Recommended Courses: Owl Carousel is not loaded.'
            );

            return;

        }


        /* ---------------------------------------------
           Destroy previous instance if initialized
           --------------------------------------------- */

        if ($carousel.hasClass('owl-loaded')) {

            $carousel.trigger('destroy.owl.carousel');

            $carousel.removeClass(
                'owl-loaded owl-hidden'
            );

            $carousel.find('.owl-stage-outer')
                .children()
                .unwrap();

            $carousel
                .find('.owl-stage')
                .children()
                .unwrap();

        }


        /* ---------------------------------------------
           Count actual courses
           --------------------------------------------- */

        var courseCount =
            $carousel.find('.recommended_course_card').length;


        /* ---------------------------------------------
           Empty state
           --------------------------------------------- */

        if (!courseCount) {

            $('.recommended_prev')
                .prop('disabled', true)
                .addClass('disabled');

            $('.recommended_next')
                .prop('disabled', true)
                .addClass('disabled');

            return;

        }


        /* ---------------------------------------------
           Initialize Owl Carousel
           --------------------------------------------- */

        $carousel.owlCarousel({

            loop: courseCount > 6,

            rewind: courseCount <= 6,

            margin: 14,

            nav: false,

            dots: false,

            autoplay: false,

            smartSpeed: 450,

            mouseDrag: true,

            touchDrag: true,

            pullDrag: true,

            responsive: {

                /* Desktop */

                0: {

                    items: 1

                },

                /* Small mobile */

                480: {

                    items: 1

                },

                /* Tablet */

                600: {

                    items: 2

                },

                /* Small desktop */

                900: {

                    items: 3

                },

                /* Desktop */

                1200: {

                    items: 4

                },

                /* Large desktop */

                1500: {

                    items: 5

                },

                /* Very large screens */

                1700: {

                    items: 6

                }

            },

            onInitialized: function () {

                updateRecommendedArrows();

            },

            onChanged: function () {

                updateRecommendedArrows();

            }

        });


        /* ---------------------------------------------
           PREVIOUS
           --------------------------------------------- */

        $('.recommended_prev')
            .off('click.recommendedCourses')
            .on(
                'click.recommendedCourses',
                function () {

                    $carousel.trigger(
                        'prev.owl.carousel'
                    );

                }
            );


        /* ---------------------------------------------
           NEXT
           --------------------------------------------- */

        $('.recommended_next')
            .off('click.recommendedCourses')
            .on(
                'click.recommendedCourses',
                function () {

                    $carousel.trigger(
                        'next.owl.carousel'
                    );

                }
            );


        /* ---------------------------------------------
           Update arrow state
           --------------------------------------------- */

        function updateRecommendedArrows() {

            var carouselData =
                $carousel.data('owl.carousel');


            if (!carouselData) {

                return;

            }


            var current =
                carouselData.relative(
                    carouselData.current()
                );


            var items =
                carouselData.items().length;


            var total =
                carouselData.items().length;


            /*
             * If looping is enabled, keep both arrows active.
             */

            if (carouselData.settings.loop) {

                $('.recommended_prev')
                    .prop('disabled', false)
                    .removeClass('disabled');

                $('.recommended_next')
                    .prop('disabled', false)
                    .removeClass('disabled');

                return;

            }


            /*
             * Non-looping carousel.
             */

            var currentPosition =
                carouselData.current();


            var maximumPosition =
                carouselData.maximum();


            $('.recommended_prev')
                .prop(
                    'disabled',
                    currentPosition <= 0
                )
                .toggleClass(
                    'disabled',
                    currentPosition <= 0
                );


            $('.recommended_next')
                .prop(
                    'disabled',
                    currentPosition >= maximumPosition
                )
                .toggleClass(
                    'disabled',
                    currentPosition >= maximumPosition
                );

        }

    }


    /* =====================================================
       DOCUMENT READY
       ===================================================== */

    $(document).ready(function () {

        initRecommendedCoursesCarousel();

    });


    /* =====================================================
       HANDLE WINDOW RESIZE
       ===================================================== */

    var recommendedResizeTimer;


    $(window).on(
        'resize.recommendedCourses',
        function () {

            clearTimeout(
                recommendedResizeTimer
            );


            recommendedResizeTimer =
                setTimeout(
                    function () {

                        var $carousel =
                            $('#recommendedCoursesCarousel');


                        if (
                            $carousel.length &&
                            $carousel.hasClass('owl-loaded')
                        ) {

                            $carousel.trigger(
                                'refresh.owl.carousel'
                            );

                        }

                    },
                    200
                );

        }
    );


})(jQuery);
</script>

<script>
const openModal = document.getElementById('openSkillModal');
const closeModal = document.getElementById('closeSkillModal');
const modal = document.getElementById('skillModal');
const saveBtn = document.getElementById('saveSkill');
const container = document.getElementById('skillsContainer');
const emptySkill = document.getElementById('emptySkill');

const quickAddSkill = document.getElementById('quickAddSkill');
openModal.onclick = () => modal.style.display = 'flex';
if (quickAddSkill) quickAddSkill.onclick = (e) => { e.preventDefault(); modal.style.display = 'flex'; };
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