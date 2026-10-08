@php
    use Illuminate\Support\Facades\Auth;

    $table_name = 'courses';

    $category = request()->get('category');
    $type = request()->get('type');
    $instructor = request()->get('instructor');
    $status = request()->get('search_status');
    $search_required_type = request()->get('search_required_type');
    $search_delivery_mode = request()->get('search_delivery_mode');

    $url = route('getAllCourseData')
        . '?search_status=' . $status
        . '&category=' . $category
        . '&type=' . $type
        . '&instructor=' . $instructor
        . '&required_type=' . $search_required_type
        . '&mode_of_delivery=' . $search_delivery_mode;

    $text = trans('common.All');
@endphp

@extends('backend.master')

@section('table')
    {{ $table_name }}
@endsection


<style>

@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');



:root{
    --primary:#556EE6;
    --primary-hover:#4458d6;
    --heading:#3F4254;
    --text:#7E8299;
    --border:#E4E6EF;
    --bg:#F5F5FA;
    --white:#ffffff;
}

body{
    background:var(--bg);
    font-family:'Poppins',sans-serif;
    font-size:14px;
    color:var(--text);
}

/* ==========================
   MAIN WRAPPER
========================== */

.course-list-wrapper{
    background:var(--white);
    border-radius:12px;
    padding:25px;
    padding-bottom:80px;
    border:1px solid var(--border);
    box-shadow:none;
}



footer,
.footer-area{
    position: relative !important;
    clear: both;
    /* z-index: 1; */
}

.main_content_iner{
    padding-bottom:100px;
}

/* ==========================
   FILTER SECTION
========================== */

.advanced-filter-card{
    background:#fff;
    border:1px solid #E4E6EF;
    border-radius:10px;
    padding:25px 30px;
    margin-bottom:25px;
}

.advanced-filter-card form{
    margin-bottom:0;
}


.filter-title{
    font-family:'Poppins',sans-serif;
    font-size:16px;
    font-weight:600;
    color:#3F4254;
    line-height:1.4;
    margin-bottom:24px;
}

.filter-label{
    display:block;
    margin-bottom:10px;
    font-family:'Poppins',sans-serif;
    font-size:12px;
    font-weight:500;
    letter-spacing:0.5px;
    text-transform:uppercase;
    color:#6C7293;
}


.custom-filter{
    height:58px !important;

    font-family:'Poppins',sans-serif;
    font-size:15px !important;
    font-weight:400;

    color:#555 !important;

    border:1px solid #E4E6EF !important;
    border-radius:6px !important;
    background:#fff !important;

    padding-left:16px;
}

.filter-btn{
    min-width:120px;
    height:48px;
    padding:0 24px;

    background:#556EE6 !important;
    color:#fff !important;

    border:none !important;
    border-radius:6px;

    font-size:15px;
    font-weight:500;

    margin-top:0;
}

.filter-btn:hover{
    background:#4458d6 !important;
    color:#fff !important;
}

.filter-btn i{
    margin-right:8px;
}
/* ==========================
   TOOLBAR
========================== */

/* ==========================
   TOOLBAR
========================== */

.toolbar{
    display:grid;
    grid-template-columns:220px 1fr 260px;
    align-items:center;
    gap:25px;
    margin-bottom:35px;
    padding-bottom:20px;
    border-bottom:1px solid var(--border);
}

.entries-info{
    white-space:nowrap;
}

.quick-search{
    position:relative;
    width:100%;
    max-width:450px;
    margin:0 auto;
}

.quick-search i{
    position:absolute;
    left:8px;
    top:50%;
    transform:translateY(-50%);
    color:#6c7293;
    font-size:18px;
    z-index:2;
}

.quick-search input{
    width:70%;
    height:38px;
    border:none;
    border-bottom:1px solid #D9DCEA;
    padding-left:35px;
    background:transparent;
    outline:none;
    color:#556EE6;
    font-weight:500;
    text-transform:uppercase;
    text-align:left;
}

.quick-search input:focus{
    border-bottom:2px solid var(--primary);
}

.export-group{
    display:flex;
    justify-content:flex-end;
}

.quick-search input::placeholder{
    color:#556EE6;
    font-weight:600;
    letter-spacing:.5px;
    text-align:left;
}
/* ==========================
   EXPORT BUTTONS
========================== */

.export-group{
    display:flex;
    flex-wrap:nowrap;
    flex-shrink:0;
}

.export-group button{
    width:42px;
    height:42px;
    border:1px solid var(--primary);
    background:#fff;
    color:var(--primary);
    transition:.3s;
}

.export-group button:hover{
    background:var(--primary);
    color:#fff;
}

.export-group button:first-child{
    border-radius:25px 0 0 25px;
}

.export-group button:last-child{
    border-radius:0 25px 25px 0;
}

/* ==========================
   COURSE CARD
========================== */

.course-card{
    height:100%;
    display:flex;
    flex-direction:column;
    background:#fff;
    border:1px solid var(--border);
    border-radius:12px;
    overflow:hidden;
    transition:.3s;
}

.course-card:hover{
    transform:translateY(-2px);
    box-shadow:0 10px 30px rgba(82,63,105,.08);
}

.course-image{
    height:170px;
    position:relative;
    overflow:hidden;
}

.course-image img{
    width:100%;
    height:100%;
    object-fit:cover;
}

.course-badge{
    position:absolute;
    top:10px;
    right:10px;
    background:var(--primary);
    color:#fff;
    font-size:12px;
    font-weight:500;
    padding:6px 14px;
    border-radius:20px;
}

/* ==========================
   CARD BODY
========================== */

.course-body{
    padding:12px 15px;
}

.dropdown{
    margin-top:auto;
}

.course-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:10px;
}

.course-title{
    font-size:14px;
    font-weight:600;
    color:#3F4254;
    line-height:1.4;
    margin-bottom:4px;
}

.course-category{
    font-size:13px;
    color:#6C7293;
    margin-bottom:0;
}

.course-meta{
    display:flex;
    justify-content:space-between;
    margin-top:15px;
    font-size:13px;
    color:#6C7293;
}

.course-meta i{
    color:var(--text);
    margin-right:4px;
}

.course-stats{
    display:flex;
    justify-content:center;
    gap:50px;
    margin-top:10px;
    padding-top:10px;
    border-top:1px solid #E4E6EF;
}


.course-stats div{
    text-align:center;
    flex:1;
}

.course-stats strong{
    display:block;
     color:#3F4254;
    font-size:18px;
    font-weight:600;
    line-height:1;
    margin-bottom:2px;

}


.course-stats span{
    color:var(--heading);
    font-size:13px;

}

/* ACTION BUTTON */

.action-btn{
    width:100%;
    height:46px;
    margin-top:8px;

    background:#fff !important;
    border:1px solid #556EE6 !important;
    border-radius:6px;

    color:#556EE6 !important;
    font-size:14px;
    font-weight:600;
    text-transform:uppercase;

    display:flex;
    align-items:center;
    justify-content:center;
    gap:8px;

    transition:.3s;
}

.action-btn:hover,
.action-btn:focus{
    background:#556EE6 !important;
    color:#fff !important;
    border-color:#556EE6 !important;
    box-shadow:none !important;
}

/* keep arrow on same line */
.action-btn.dropdown-toggle::after{
    margin-left:6px;
    vertical-align:middle;
}

/* DROPDOWN MENU */

.dropdown-menu{
    min-width:200px;
    border:none;
    border-radius:12px;
    padding:12px 0;
    box-shadow:0 8px 25px rgba(0,0,0,.12);
}

.dropdown-item{
    font-size:14px;
    font-weight:400;
    color:#6C7293;
    padding:10px 20px;
    text-align:center;
    text-transform:uppercase;
}

.dropdown-item:hover{
    background:#F5F7FF;
    color:#556EE6;
}

/* ==========================
   TOGGLE SWITCH
========================== */

.course-switch{
    position:relative;
    display:inline-block;
    width:46px;
    height:20px;
}

.course-switch input{
    display:none;
}

.slider{
     display:block;
    position:absolute;
    inset:0;
    cursor:pointer;
    background:#cfd3e4;
    border-radius:50px;
    transition:.3s;
}

.slider:before{
    content:"";
    position:absolute;
    width:14px;
    height:14px;
    left:3px;
    bottom:3px;
    background:white;
    border-radius:50%;
    transition:.3s;
}

.course-switch input:checked + .slider{
    background:var(--primary);
}

.course-switch input:checked + .slider:before{
    transform:translateX(26px);
}

/* ==========================
   PAGINATION
========================== */

.pagination-wrapper{
    margin-top:30px;
}

.pagination .page-link{
    color:var(--primary);
    border:1px solid var(--border);
    width:42px;
    height:42px;
    display:flex;
    align-items:center;
    justify-content:center;
    margin:0 4px;
    border-radius:8px !important;
    font-weight:500;
}

.pagination .page-item.active .page-link{
    background:var(--primary);
    border-color:var(--primary);
    color:#fff;
}

.pagination .page-link:hover{
    background:var(--primary);
    color:#fff;
    border-color:var(--primary);
}

.course-thumbnail {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: #4f46e5;
    color: #fff;
    font-size: 28px;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 15px auto;
    text-transform: uppercase;
}
.entries-info {
    display: flex;
    align-items: center;
    gap: 10px;
}

.entries-info select {
    width: 80px;
    height: 38px;
    padding: 5px 10px;
}
</style>


@section('mainContent')

{!! generateBreadcrumb() !!}

<div class="advanced-filter-card">

    <h6 class="filter-title">
        Advanced Filter
    </h6>

    <form action="{{ route('getAllCourse') }}" method="GET">

        <div class="row">

            <div class="col-lg-3 col-md-6 mb-4">

                <label class="filter-label">
                    CATEGORY
                </label>

                <select class="form-control custom-filter">
                    <option>Select Category</option>
                    <option>HR</option>
                    <option>IT</option>
                    <option>Finance</option>
                </select>

            </div>

            <div class="col-lg-3 col-md-6 mb-4">

                <label class="filter-label">
                    COURSE TYPE
                </label>

                <select class="form-control custom-filter">
                    <option>Select Course Type</option>
                    <option>Course</option>
                    <option>Quiz</option>
                </select>

            </div>

            <div class="col-lg-3 col-md-6 mb-4">

                <label class="filter-label">
                    INSTRUCTOR
                </label>

                <select class="form-control custom-filter">
                    <option>Select Instructor</option>
                    <option>Admin User</option>
                    <option>John Smith</option>
                </select>

            </div>

            <div class="col-lg-3 col-md-6 mb-4">

                <label class="filter-label">
                    STATUS
                </label>

                <select class="form-control custom-filter">
                    <option>Select Status</option>
                    <option>Active</option>
                    <option>Inactive</option>
                </select>

            </div>

        </div>

        <button type="submit" class="btn filter-btn">
            <i class="fa fa-search"></i>
            Filter
        </button>

    </form>

</div>

<div class="course-list-wrapper">

    <!-- TOOLBAR -->

    
<div class="toolbar">
        <div class="entries-info">
            <label>Show</label>

            <select id="perPage" onchange="changePerPage(this.value)">
                <option value="12" selected>12</option>
                <option value="24">24</option>
                <option value="48">48</option>
                <option value="100">100</option>
            </select>

            <label>Entries</label>
        </div>
        
        <div class="quick-search">

            <i class="fas fa-search"></i>

            <input
                type="text"
                id="quickSearch"
                placeholder="QUICK SEARCH">

        </div>

        <div class="export-group">

            <button type="button">
                <i class="fas fa-copy"></i>
            </button>

            <button type="button">
                <i class="fas fa-file-excel"></i>
            </button>

            <button type="button">
                <i class="fas fa-file-alt"></i>
            </button>

            <button type="button">
                <i class="fas fa-file-pdf"></i>
            </button>

            <button type="button">
                <i class="fas fa-print"></i>
            </button>

            <button type="button">
                <i class="fas fa-th-large"></i>
            </button>

        </div>

    </div>

<!-- COURSE GRID -->

<div class="row">

    @if ($courses->count() > 0)
        @foreach ($courses as $i => $course)
        <div class="col-xl-4 col-lg-6 col-md-6 mb-4">

        <div class="course-card">

            <div class="course-image">

                @if($course->thumbnail)
                    <img src="{{ asset($course->thumbnail) }}" alt="{{ $course->title }}">
                @else
                    @php
                        $words = explode(' ', trim($course->title));
                        $initials = strtoupper(
                            substr($words[0] ?? '', 0, 1) .
                            substr($words[1] ?? '', 0, 1)
                        );
                    @endphp

                    <div class="course-thumbnail">
                        {{ $initials }}
                    </div>
                @endif

                <!-- <span class="course-badge">
                    Course
                </span> -->

            </div>

            <div class="course-body">

                <div class="course-header">

                    <div>

                        <div class="course-title">
                            {{ $course->title }}
                        </div>

                        <div class="course-category">
                            {{ $course->category->name ?? 'N/A' }}
                        </div>

                    </div>

                    <label class="course-switch">
                        <input type="checkbox" {{ $course->status == 1 ? 'checked' : '' }} data-course-id="{{ $course->id }}">
                        <span class="slider"></span>
                    </label>

                </div>

                <div class="course-meta">

                    <span>
                        <i class="ri-user-line"></i>
                        {{ $course->user->name ?? 'N/A' }}
                    </span>

                    <span>
                        Online
                    </span>

                </div>

                <div class="course-stats">

                    <div>
                        <strong>{{ $course->lessons->count() }}</strong>
                        <span>Lessons</span>
                    </div>

                    <div>
                        <strong>{{ $course->enrolls->count() }}</strong>
                        <span>Enrolled</span>
                    </div>

                </div>

                <div class="dropdown mt-3">

                    <button
                        class="btn action-btn dropdown-toggle"
                        data-bs-toggle="dropdown"
                        type="button">
                            ACTION
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end">

                        <li>
                            <a class="dropdown-item" href="#">
                                View Course
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item" href="#">
                                Edit Course
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item" href="#">
                                Students
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item text-danger" href="#">
                                Delete
                            </a>
                        </li>

                    </ul>

                </div>

            </div>

        </div>

    </div>
    @endforeach
    @else 
    
        <div class="col-12">
            <div class="alert alert-warning text-center" role="alert">
                No courses found.
            </div>
        </div>
    @endif

</div>

    <!-- PAGINATION -->
    <div class="d-flex justify-content-between align-items-center mt-4">
       
        <div>
            Showing {{ $courses->firstItem() }} to {{ $courses->lastItem() }} of {{ $courses->total() }} entries
        </div>
    
      
        <div class="pagination-wrapper">
            {{ $courses->links() }}
        </div>
    </div>

    <!-- <ul class="pagination justify-content-center">

        <li class="page-item active">
            <a class="page-link" href="#">1</a>
        </li>

        <li class="page-item">
            <a class="page-link" href="#">2</a>
        </li>

        <li class="page-item">
            <a class="page-link" href="#">3</a>
        </li>

        <li class="page-item">
            <a class="page-link" href="#">
                <i class="ti-angle-right"></i>
            </a>
        </li>

    </ul> -->



</div>


@endsection


@section('scripts')

@endsection
<script>
    function changePerPage(perPage) {
        const url = new URL(window.location.href);
        url.searchParams.set('per_page', perPage);
        window.location.href = url.toString();
    }

   
    
</script>
