@extends('backend.master')

@push('styles')
<style>

/*----------------------------------
        COMMON CARD
-----------------------------------*/

.chart-card,
.table-card{

    background:#fff;
    border:1px solid var(--border);
    border-radius:18px;
    box-shadow:0 3px 12px rgba(15,23,42,.05);
    padding:22px;

}

.section-space{
    margin-top:22px;
}

.row{
    --bs-gutter-x:22px;
    --bs-gutter-y:22px;
}

/* ==========================================
   KPI CARD
========================================== */

.dashboard-card{
    background:#fff;
    border:1px solid #edf2f7;
    border-radius:12px;
    padding:22px;
    min-height:120px;
    box-shadow:0 2px 8px rgba(0,0,0,.04);
    transition:.3s;
}

.dashboard-card:hover{
    box-shadow:0 8px 20px rgba(0,0,0,.08);
}

.card-title{
    font-size:16px;
    font-weight:600;
    color:#374151;
}

.card-value{
    font-size:18px;
    font-weight:700;
    color:#1f2937;
}

.card-subtitle{
    margin-top:24px;
    font-size:14px;
    color:#6b7280;
}

/*----------------------------------
        SECTION TITLE
-----------------------------------*/

.section-title{

    font-size:18px;
    font-weight:700;
    margin-bottom:2px;

}

.section-subtitle{

    color:var(--muted);
    font-size:13px;

}

.chart-header{

    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:20px;

}

.switch-btn{

    display:flex;
    background:#f1f5f9;
    padding:4px;
    border-radius:10px;

}

.switch-btn button{

    border:none;
    background:none;
    padding:8px 18px;
    border-radius:8px;
    font-weight:600;
    color:#64748b;

}

.switch-btn .active{

    background:#fff;
    color:var(--primary);
    box-shadow:0 2px 6px rgba(0,0,0,.08);

}

/*----------------------------------
        CHART
-----------------------------------*/

.chart-wrapper{

    position:relative;
    /* height:300px; */

}

.chart-wrapper-sm{

    position:relative;
    height:240px;

}

.chart-card .border-start{
    border-color:#e8edf4 !important;
    padding-left:2rem !important;
}

.chart-wrapper canvas,
.chart-wrapper-sm canvas{

    width:100%!important;
    height:100%!important;

}

/*----------------------------------
        COURSE TABLE
-----------------------------------*/

.table-card{

    overflow:hidden;

}

.table{

    margin-bottom:0;

}

.table thead th{

    background:#f8fafc;
    border:none;
    padding:16px;
    font-size:12px;
    text-transform:uppercase;
    color:#64748b;
    letter-spacing:.05em;
    font-weight:600;

}

.table tbody td{

    padding:18px 16px;
    border-top:1px solid #eef2f7;
    vertical-align:middle;
    font-size:14px;

}

.table tbody tr:hover{

    background:#fafcff;

}

.course-rank{

    display:flex;
    align-items:center;
    gap:14px;

}

.course-icon{

    width:46px;
    height:46px;
    border-radius:12px;
    display:flex;
    justify-content:center;
    align-items:center;
    color:#fff;
    font-size:18px;

}

.bg-blue{background:#2563eb;}
.bg-green{background:#16a34a;}
.bg-purple{background:#7c3aed;}
.bg-orange{background:#ea580c;}
.bg-cyan{background:#0891b2;}

.progress{

    height:8px;
    border-radius:20px;
    background:#edf2f7;

}

.progress-bar{

    border-radius:20px;

}

.status-live{

    background:#ecfdf3;
    color:#16a34a;
    padding:6px 12px;
    border-radius:20px;
    font-size:12px;
    font-weight:600;

}

.status-draft{

    background:#fff7e8;
    color:#d97706;
    padding:6px 12px;
    border-radius:20px;
    font-size:12px;
    font-weight:600;

}

/*----------------------------------
        PENDING APPROVALS
-----------------------------------*/

.approval-item{

    border:1px solid #edf2f7;
    border-radius:14px;
    padding:15px;
    margin-top:14px;
    transition:.25s;

}

.approval-item:hover{

    background:#fafcff;

}

.user-avatar{

    width:42px;
    height:42px;
    border-radius:12px;
    display:flex;
    align-items:center;
    justify-content:center;
    color:#fff;
    font-size:14px;
    font-weight:700;

}

.avatar-blue{background:#2563eb;}
.avatar-red{background:#dc2626;}
.avatar-green{background:#16a34a;}
.avatar-cyan{background:#0891b2;}

.badge-soft{

    background:#eff6ff;
    color:#2563eb;
    border:1px solid #bfdbfe;
    border-radius:20px;
    padding:4px 10px;
    font-size:11px;
    font-weight:600;

}

.badge-count{

    width:28px;
    height:28px;
    border-radius:50%;
    background:#2563eb;
    color:#fff;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:12px;
    font-weight:700;

}

.btn-approve{

    background:#2563eb;
    color:#fff;
    border:none;
    border-radius:8px;
    padding:8px 14px;
    font-size:13px;
    font-weight:600;

}

.btn-review{

    background:#f1f5f9;
    border:none;
    border-radius:8px;
    padding:8px 14px;
    font-size:13px;
    font-weight:600;

}

/*----------------------------------
        RECENT ACTIVITY
-----------------------------------*/

.timeline{
    position:relative;
    padding-left:22px;
}

.timeline::before{
    content:"";
    position:absolute;
    left:5px;
    top:5px;
    bottom:0;
    width:2px;
    background:#e5e7eb;
}

.timeline-item{
    position:relative;
    padding-bottom:22px;
}

.timeline-item:last-child{
    padding-bottom:0;
}

.timeline-item::before{
    content:"";
    position:absolute;
    left:-22px;
    top:6px;
    width:12px;
    height:12px;
    border-radius:50%;
    background:var(--primary);
    border:3px solid #fff;
    box-shadow:0 0 0 2px var(--primary);
}

.timeline-title{
    font-size:15px;
    font-weight:600;
    color:var(--text);
}

.timeline-desc{
    font-size:13px;
    color:var(--muted);
    margin-top:4px;
}

.timeline-time{
    font-size:12px;
    color:#94a3b8;
    margin-top:6px;
}

/*----------------------------------
        LEGEND
-----------------------------------*/

.legend-dot{
    width:10px;
    height:10px;
    border-radius:50%;
    display:inline-block;
    margin-right:8px;
}

.legend-blue{background:#2563eb;}
.legend-green{background:#16a34a;}
.legend-purple{background:#7c3aed;}
.legend-red{background:#ef4444;}
.legend-gray{background:#94a3b8;}

.list-item{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:10px 0;
    border-bottom:1px solid #eef2f7;
}

.list-item:last-child{
    border-bottom:none;
}

.view-all{
    color:var(--primary);
    font-size:14px;
    font-weight:600;
}

.activity-item{
    display:flex;
    align-items:flex-start;
    margin-bottom:22px;
}

.activity-item:last-child{
    margin-bottom:0;
}

.view-all{
    font-size:14px;
    font-weight:600;
    color:#2563eb;
}

.view-all:hover{
    text-decoration:none;
}

.user-avatar{
    flex-shrink:0;
}

/*----------------------------------
        RESPONSIVE
-----------------------------------*/

@media(max-width:991px){

    .dashboard{
        padding:15px;
    }

    .chart-header{
        flex-direction:column;
        align-items:flex-start;
        gap:15px;
    }

    .chart-wrapper{
        height:250px;
    }

    .chart-wrapper-sm{
        height:220px;
    }

    .table-responsive{
        overflow-x:auto;
    }

    .border-start{
        border-left:none!important;
        border-top:1px solid #edf2f7!important;
        padding-top:24px;
        margin-top:24px;
    }

}

@media(max-width:768px){

    .card-value{
        font-size:20px;
    }

    .chart-wrapper{
        height:220px;
    }

    .chart-wrapper-sm{
        height:190px;
    }

    .table{
        min-width:720px;
    }

}

</style>
@endpush

@section('mainContent')

{!! generateBreadcrumb() !!}


<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid">

        <div class="dashboard">

           

            <div class="row">
                
                <div class="col-xl-3 col-md-6">
                    <div class="dashboard-card">
                        
                        <div class="d-flex justify-content-between align-items-start">
                        
                            <div class="card-title">
                                Students
                            </div>
                            
                            <div class="card-value" id="students_count">
                                0
                            </div>
                        </div>
                        
                        <div class="card-subtitle">
                            Number of Students
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="dashboard-card">
                        
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="card-title">
                                Enrolled
                            </div>
                            
                            <div class="card-value" id="enrolled_count">
                                0
                            </div>
                        
                        </div>
                        
                        <div class="card-subtitle">
                            Total Enrolled
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-3 col-md-6">
                    
                    <div class="dashboard-card">
                        
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="card-title">
                                Courses
                            </div>
                            
                            <div class="card-value" id="courses_count">
                                0
                            </div>
                        </div>
                        
                        <div class="card-subtitle" >
                            Number of Courses
                        </div>
                    </div>
                
                </div>
                
                <div class="col-xl-3 col-md-6">
                    
                    <div class="dashboard-card">
                        <div class="d-flex justify-content-between align-items-start">
                            
                            <div class="card-title">
                                Instructors
                            </div>
                            
                            <div class="card-value" id="instructors_count">
                                0
                            </div>
                        </div>
                        
                        <div class="card-subtitle">
                            Number of Instructors
                        </div>
                    
                    </div>
                </div>
            
            </div>

            <!-- ========================= -->
            <!-- Enrollment Trend  -->
            <!-- ========================= -->

            <div class="row section-space">

                <!-- Left Card -->
                <div class="col-lg-8">

                    <div class="chart-card h-100">

                        <div class="chart-header">

                            <div>

                                <h5 class="section-title">
                                    Enrollment Trend
                                </h5>

                                <div class="section-subtitle">
                                    Monthly enrollments across the institute
                                </div>

                            </div>

                            <div class="switch-btn">

                                <button class="active">
                                    Monthly
                                </button>

                                <button>
                                    Weekly
                                </button>

                            </div>

                        </div>

                        <div class="chart-wrapper">

                            <canvas id="enrollmentChart"></canvas>

                        </div>

                    </div>

                </div>

                <!-- ===================================== -->
                <!-- Pending Approvals -->   
                <!-- ===================================== -->
                     
                <div class="col-lg-4 ">
                    
                    <div class="table-card h-100">
                        
                        <div class="d-flex justify-content-between align-items-center">
                            
                            <div>
                                
                                <h5 class="section-title">Pending Approvals</h5>
                                
                                <div class="section-subtitle">
                                    Requires administrator action
                                </div>
                            </div>
                            
                            <span class="badge-count" id="pendingApprovalCount">0</span>

                            <div id="pendingApprovalBody"></div>
                        
                        </div>
                        
                        
                    </div>
                
                </div>               

            </div>

            <div class="row section-space">
            
                <!-- ===================================== -->
                <!-- Top Performing Courses -->
                <!-- =================================== -->
                 
                <div class="col-lg-8">
                    
                    <div class="table-card h-100">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h5 class="section-title">Top Performing Courses</h5>
                                <div class="section-subtitle">
                                    Highest enrollment courses this month
                                </div>
                            </div>
                            <button class="btn btn-outline-primary btn-sm">
                                View All
                            </button>
                        </div>
                        
                        <div class="table-responsive">

                            <table class="table align-middle">
                                
                                <thead>
                                    <tr>
                                        <th>Course</th>
                                        <th>Instructor</th>
                                        <th>Students</th>
                                        <th>Completion</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>

                                <tbody id="topCourseBody">
                                    
                                </tbody>
                            
                            </table>
                        </div>
                    
                    </div>
                </div>
                               
                <!-- Right Card -->
                <div class="col-lg-4">

                    <div class="chart-card h-100">

                        <h5 class="section-title">
                            Course Distribution
                        </h5>

                        <div class="section-subtitle mb-4">
                            Students by category
                        </div>

                        <div class="chart-wrapper-sm">

                            <canvas id="courseChart"></canvas>

                        </div>

                        <div class="mt-4" id="courseDistributionBody"></div>

                    </div>

                </div>
            </div>

            <!-- ===================================== -->
<!-- Recent Activity -->
<!-- ===================================== -->

            <div class="row section-space">

                <div class="col-12">

                    <div class="table-card">

                        <div class="d-flex justify-content-between align-items-center mb-4">

                            <h5 class="section-title mb-0">
                                Recent Activity
                            </h5>

                            <a href="#" class="view-all">
                                View log →
                            </a>

                        </div>

                        <div class="row" id="recentActivityBody">

                          
                        </div>

                    </div>

                </div>

            </div>

           
        </div>

    </div>

</section>

@endsection

@push('js')

<script src="{{ asset('public/backend/vendors/chartlist/Chart.min.js') }}"></script>

<script>

/* =====================================
   Enrollment Trend
===================================== */

const enrollmentChart = new Chart(document.getElementById("enrollmentChart"), {

    type: "line",

    data: {

        labels: [
            "Jan","Feb","Mar","Apr","May","Jun",
            "Jul","Aug","Sep","Oct","Nov","Dec"
        ],

        datasets: [{

            label: "Enrollments",

            data: [],

            borderColor: "#2563eb",

            backgroundColor: "rgba(37,99,235,.10)",

            fill: true,

            tension: .4,

            pointRadius: 4,

            pointBackgroundColor: "#2563eb",

            borderWidth: 3

        }]

    },

    options: {

        responsive: true,

        maintainAspectRatio: false,

        plugins: {
            legend: {
                display: false
            }
        },

        scales: {

            y: {
                beginAtZero: true,
                grid: {
                    color: "#edf2f7"
                }
            },

            x: {
                grid: {
                    display: false
                }
            }

        }

    }

});

/* =====================================
   Course Distribution
===================================== */

const courseChart = new Chart(document.getElementById("courseChart"), {
    type: "doughnut",
    data: {
        labels: [],
        datasets: [{
            data: [],
            backgroundColor: [
                "#7c3aed",
                "#16a34a",
                "#2563eb",
                "#ef4444",
                "#94a3b8"
            ],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: "72%",
        plugins: {
            legend: {
                display: false
            }
        }
    }
});



$('document').ready(function() {
    $.ajax({
        url: "{{ route('dashboard.data') }}",
        method: 'GET',
        success: function(response) {
            $('#students_count').text(response.students_count);
            $('#enrolled_count').text(response.enrolled_count);
            $('#courses_count').text(response.courses_count);
            $('#instructors_count').text(response.instructors_count);
            $('#pendingApprovalCount1').text(response.pending_approval_count);
            $('#pendingApprovalCount').html(response.approvals.length);

            // Update top courses table
            var topCourses = response.top_courses;
            
            var loadApprovals = response.approvals;
            var activities = response.activities;

            var categories = response.categories;

            var monthly = response.monthly;

            let monthlyData = new Array(12).fill(0);

            response.monthly.forEach(function(item){
                monthlyData[item.month - 1] = item.count;
            });

            enrollmentChart.data.datasets[0].data = monthlyData;
            enrollmentChart.update();

            let labels = [];
            let values = [];

            categories.forEach(function(item) {
                labels.push(item.category_name);
                values.push(item.count);
            });

            courseChart.data.labels = labels;
            courseChart.data.datasets[0].data = values;
            courseChart.update();

           
            let html = '';
             let total = 0;

            let colors = [
                'legend-blue',
                'legend-green',
                'legend-purple',
                'legend-red',
                'legend-gray'
            ];

            // Calculate total
           
            categories.forEach(function(item) {
                total += parseInt(item.count);
            });

            // Build legend and chart data
            categories.forEach(function(item, index) {

                labels.push(item.category_name);
                values.push(item.count);

                let percent = total > 0
                    ? Math.round((item.count / total) * 100)
                    : 0;

                html += `
                    <div class="list-item">
                        <div>
                            <span class="legend-dot ${colors[index % colors.length]}"></span>
                            ${item.category_name}
                        </div>
                        <strong>${percent}%</strong>
                    </div>
                `;
            });

            $('#courseDistributionBody').html(html);

            // Update Chart
            // courseChart.data.labels = labels;
            // courseChart.data.datasets[0].data = values;
            // courseChart.update();

            let activityBody = '';

            activities.forEach(function(activity) {

                let initials = activity.user_name
                    .split(' ')
                    .map(word => word.charAt(0))
                    .join('')
                    .substring(0,2)
                    .toUpperCase();

                let courseTitle = '';

                try {
                    courseTitle = JSON.parse(activity.course_title).en;
                } catch (e) {
                    courseTitle = activity.course_title;
                }

                activityBody += `
                    <div class="col-lg-6 mb-4">
                        <div class="d-flex align-items-start">

                            <div class="user-avatar avatar-blue">
                                ${initials}
                            </div>

                            <div class="ms-3">

                                <div>
                                    <strong>${activity.user_name}</strong>
                                    enrolled in
                                    <strong>${courseTitle}</strong>
                                </div>

                                <small class="text-muted">
                                    ${activity.enrolledDate}
                                </small>

                            </div>

                        </div>
                    </div>
                `;
            });

            $('#recentActivityBody').html(activityBody);
            
            topCourses.forEach(function(course) {
                topCourseBody += '<tr>' +
                    '<td>' +
                        '<div class="course-rank">' +
                            '<div class="course-icon bg-blue">' +
                                '<i class="bi bi-code-slash"></i>' +
                            '</div>' +
                            '<div>' +
                                '<strong>' + course.title + '</strong>' +
                                '<div class="text-muted small">' + course.category + '</div>' +
                            '</div>' +
                        '</div>' +
                    '</td>' +
                    '<td>' + course.instructor + '</td>' +
                    '<td><strong>' + course.students + '</strong></td>' +
                    '<td width="170">' +
                        '<div class="progress">' +
                            '<div class="progress-bar bg-success" style="width:' + course.completion + '%"></div>' +
                        '</div>' +
                        '<small>' + course.completion + '%</small>' +
                    '</td>' +
                    '<td><span class="status-live">Live</span></td>' +
                '</tr>';
            });
            $('#topCourseBody').html(topCourseBody);


           
               

                categories.forEach(function(item){
                    total += parseInt(item.count);
                });

               
               

                
        },
        error: function(xhr, status, error) {
            console.error('Error fetching dashboard data:', error);
        }
    });

    function loadApprovals(data) {

        $('#pendingApprovalCount').text(response.approvals.length);

        let html1 = '';

        $.each(data, function(index, row){

            html1 += `
            <div class="approval-item">

                <div class="d-flex align-items-center">

                    <div class="user-avatar avatar-blue">
                        ${row.initials}
                    </div>

                    <div class="ms-3 flex-grow-1">

                        <strong>${row.name}</strong>

                        <div class="text-muted small">
                            ${row.type}
                        </div>

                    </div>

                    <span class="badge-soft">
                        ${row.status}
                    </span>

                </div>

                <div class="d-flex gap-2 mt-3">

                    <button class="btn-approve flex-fill"
                        data-id="${row.id}">
                        Approve
                    </button>

                    <button class="btn-review flex-fill"
                        data-id="${row.id}">
                        Review
                    </button>

                </div>

            </div>`;
        });

    }

    $('#pendingApprovalBody').html(html1);

    function loadTopCourses(data)
    {
        let html='';

        $.each(data,function(i,row){

            html+=`
            <tr>

                <td>

                    <div class="course-rank">

                        <div class="course-icon bg-blue">
                            <i class="bi bi-book"></i>
                        </div>

                        <div>

                            <strong>${row.title}</strong>

                            <div class="text-muted small">
                                ${row.category}
                            </div>

                        </div>

                    </div>

                </td>

                <td>${row.instructor}</td>

                <td><strong>${row.students}</strong></td>

                <td>

                    <div class="progress">

                        <div class="progress-bar bg-success"
                            style="width:${row.completion}%">
                        </div>

                    </div>

                    <small>${row.completion}%</small>

                </td>

                <td>

                    <span class="${row.status=='Live' ? 'status-live' : 'status-draft'}">
                        ${row.status}
                    </span>

                </td>

            </tr>`;
        });

        $('#topCourseBody').html(html);

    }


    function loadCourseDistribution(data)
    {
        let html='';

        $.each(data,function(i,row){

            html+=`

            <div class="list-item">

                <div>

                    <span class="legend-dot"
                        style="background:${row.color}">
                    </span>

                    ${row.name}

                </div>

                <strong>${row.total}</strong>

            </div>`;

        });

        $('#courseDistributionBody').html(html);

    }



});

</script>

@endpush