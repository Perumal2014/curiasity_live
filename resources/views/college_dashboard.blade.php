@extends('backend.master')

@push('styles')
<style>



/*----------------------------------
        HEADER
-----------------------------------*/

.dashboard-header {
    margin-bottom: 30px;
}

.dashboard-header h2 {
    margin: 0 0 5px;
    font-size: 25px;
    font-weight: 600;
    color: var(--text-primary);
}

.dashboard-header p {
    margin: 0;
    font-size: 16px;
    color: var(--text-secondary);
}

/*----------------------------------
        COMMON CARD
-----------------------------------*/

.chart-card,
.table-card {

    background: #fff;
    border: 1px solid var(--border);
    border-radius: 18px;
    box-shadow: 0 3px 12px rgba(15,23,42,.05);
    padding: 22px;

}

.section-space {
    margin-top: 22px;
}

/*----------------------------------
        BOOTSTRAP ROW
-----------------------------------*/

.row {
    --bs-gutter-x: 22px;
    --bs-gutter-y: 22px;
}

/* ==========================================
   KPI CARDS - INSTITUTE DASHBOARD STYLE
========================================== */

.kpi-card {
    background: #fff;
    border: 1px solid #edf2f7;
    border-radius: 12px;
    padding: 22px;
    min-height: 120px;
    box-shadow: 0 2px 8px rgba(0,0,0,.04);
    transition: .3s;
}

.kpi-card:hover {
    box-shadow: 0 8px 20px rgba(0,0,0,.08);
}

.kpi-top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.kpi-label {
    font-size: 16px;
    font-weight: 600;
    color: #374151;
    letter-spacing: 0;
}

.kpi-icon {
    width: auto;
    height: auto;
    border-radius: 0;
    background: transparent !important;
    color: #1f2937;
    font-size: 18px;
    font-weight: 700;
}

.kpi-value {
    font-size: 18px;
    line-height: 1.2;
    font-weight: 700;
    color: #1f2937;
    margin: 0;
}

.kpi-description {
    margin-top: 24px;
    margin-bottom: 0;
    font-size: 14px;
    color: #6b7280;
}

.kpi-trend {
    display: none;
}

/*----------------------------------
        CHART AREA
-----------------------------------*/

.dashboard-chart-card {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 18px;
    box-shadow: 0 3px 12px rgba(15,23,42,.05);
    overflow: hidden;
    height: 430px;
    min-height: 430px;
}

.card-header-custom {
    padding: 25px 22px 10px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.card-title-custom {
    margin: 0;
    font-size: 17px;
    font-weight: 600;
    color: #153b63;
    letter-spacing: .8px;
}

.card-action {
    font-size: 14px;
    color: #0645ff;
    text-decoration: none;
    font-weight: 500;
}

.card-action:hover {
    color: #0034c7;
}

/*----------------------------------
        ENROLLMENT CHART
-----------------------------------*/

.enrollment-chart-wrapper {
    position: relative;
    height: 300px;
    padding: 5px 20px 10px;
}

#enrollmentTrendChart {
    width: 100% !important;
    height: 100% !important;
}

/*----------------------------------
        DEPARTMENT CHART
-----------------------------------*/

.department-chart-wrapper {
    height: 205px;
    padding: 8px 35px 0;
    display: flex;
    justify-content: center;
}

#departmentChart {
    max-width: 205px !important;
    max-height: 205px !important;
}

.department-list {
    padding: 5px 22px 22px;
}

.department-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    min-height: 25px;
    font-size: 14px;
    color: #48617d;
}

.department-name {
    display: flex;
    align-items: center;
    gap: 10px;
}

.department-dot {
    width: 9px;
    height: 9px;
    border-radius: 50%;
}

.department-value {
    color: #7890ad;
}

/*----------------------------------
    COURSE COMPLETION
-----------------------------------*/

.course-completion-card {
    height: 100%;
    min-height: 390px;
}

.card-section-title {
    font-size: 17px;
    font-weight: 600;
    color: #153b63;
    letter-spacing: .8px;
}

.course-chart-wrapper {
    height: 300px;
    margin-top: 18px;
}

#courseCompletionChart {
    width: 100% !important;
    height: 100% !important;
}


/*----------------------------------
    ACADEMIC PERFORMANCE
-----------------------------------*/

.academic-performance-card {
    padding: 0;
    overflow: hidden;
    height: 100%;
}

.performance-header {
    padding: 22px 24px 8px;
}

.performance-header h4 {
    margin: 0;
    font-size: 17px;
    font-weight: 600;
    color: #153b63;
    letter-spacing: .8px;
}

.performance-header span {
    display: block;
    margin-top: 5px;
    font-size: 14px;
    color: #8297b2;
}


/*----------------------------------
    FILTERS
-----------------------------------*/

.performance-filters {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 24px 14px;
    flex-wrap: wrap;
}

.performance-filter {
    height: 38px;
    min-width: 125px;
    padding: 0 12px;
    border: 1px solid #dce5ef;
    border-radius: 9px;
    background: #fff;
    color: #48617d;
    font-size: 13px;
    outline: none;
}

.performance-filter:focus {
    border-color: #3d7ff0;
}

.performance-reset {
    border: 0;
    background: transparent;
    color: #7890ad;
    font-size: 13px;
    cursor: pointer;
    padding: 8px 2px;
}


/*----------------------------------
    PERFORMANCE TABLE
-----------------------------------*/

.performance-table-wrapper {
    width: 100%;
    overflow-x: auto;
    max-height: 315px;
    overflow-y: auto;
}

.academic-performance-table {
    width: 100%;
    min-width: 980px;
    border-collapse: collapse;
}

.academic-performance-table thead th {
    position: sticky;
    top: 0;
    z-index: 2;
    background: #f7f9fc;
    padding: 13px 18px;
    color: #617995;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: .5px;
    text-align: left;
    white-space: nowrap;
    border-bottom: 1px solid #edf1f5;
}

.academic-performance-table tbody td {
    padding: 16px 18px;
    font-size: 13px;
    color: #48617d;
    white-space: nowrap;
    border-bottom: 1px solid #edf1f5;
}

.academic-performance-table tbody tr:hover {
    background: #fafcff;
}


/*----------------------------------
    ACADEMIC YEAR BADGE
-----------------------------------*/

.academic-badge {
    display: inline-flex;
    align-items: center;
    padding: 4px 8px;
    border-radius: 4px;
    background: #eef5ff;
    color: #1261e8;
    font-size: 11px;
    font-weight: 500;
}

.semester-text {
    color: #6f86a2;
}


/*----------------------------------
    PROGRESS
-----------------------------------*/

.progress-info {
    display: flex;
    align-items: center;
    gap: 8px;
}

.mini-progress {
    width: 62px;
    height: 5px;
    background: #e8edf4;
    border-radius: 10px;
    overflow: hidden;
}

.mini-progress span {
    display: block;
    height: 100%;
    background: #3d7ff0;
    border-radius: 10px;
}

.mini-progress.completion span {
    background: #10b981;
}

.progress-info small {
    color: #7187a1;
    font-size: 11px;
}


/*----------------------------------
    PERFORMANCE BADGES
-----------------------------------*/

.performance-badge {
    display: inline-block;
    padding: 3px 8px;
    border-radius: 10px;
    font-size: 10px;
    font-weight: 500;
}

.performance-badge.excellent {
    color: #059669;
    background: #ecfdf5;
    border: 1px solid #a7f3d0;
}

.performance-badge.outstanding {
    color: #7c3aed;
    background: #f5f3ff;
    border: 1px solid #ddd6fe;
}

.performance-badge.good {
    color: #2563eb;
    background: #eff6ff;
    border: 1px solid #bfdbfe;
}

.gpa-text {
    display: block;
    margin-top: 4px;
    color: #8297b2;
    font-size: 10px;
}

/*----------------------------------
    RECENT ENROLLMENTS
-----------------------------------*/

.recent-enrollments-card {
    padding: 0;
    overflow: hidden;
}

.recent-enrollments-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 22px 25px;
}

.recent-enrollments-header h4 {
    margin: 0;
    color: #153b63;
    font-size: 17px;
    font-weight: 600;
    letter-spacing: .8px;
}

.recent-enrollments-actions {
    display: flex;
    align-items: center;
    gap: 10px;
}

.enrollment-filter {
    height: 38px;
    min-width: 150px;
    padding: 0 12px;
    border: 1px solid #dce5ef;
    border-radius: 9px;
    background: #fff;
    color: #48617d;
    font-size: 13px;
    outline: none;
}

.enrollment-export {
    height: 38px;
    padding: 0 17px;
    border: 0;
    border-radius: 9px;
    background: #2563eb;
    color: #fff;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
}

.enrollment-export:hover {
    background: #1d4ed8;
}


/*----------------------------------
    TABLE
-----------------------------------*/

.recent-enrollments-table-wrapper {
    width: 100%;
    overflow-x: auto;
}

.recent-enrollments-table {
    width: 100%;
    min-width: 950px;
    border-collapse: collapse;
}

.recent-enrollments-table thead th {
    padding: 14px 25px;
    background: #f7f9fc;
    border-top: 1px solid #edf1f5;
    border-bottom: 1px solid #edf1f5;
    color: #617995;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: .6px;
    text-align: left;
    white-space: nowrap;
}

.recent-enrollments-table tbody td {
    padding: 17px 25px;
    border-bottom: 1px solid #edf1f5;
    color: #48617d;
    font-size: 13px;
    white-space: nowrap;
}

.recent-enrollments-table tbody tr:hover {
    background: #fafcff;
}


/*----------------------------------
    STUDENT ID
-----------------------------------*/

.student-id-badge {
    display: inline-block;
    padding: 4px 9px;
    border-radius: 4px;
    background: #eef5ff;
    color: #1261e8;
    font-size: 12px;
}


/*----------------------------------
    STUDENT
-----------------------------------*/

.enrollment-student {
    display: flex;
    align-items: center;
    gap: 10px;
    color: #102a43;
    font-size: 14px;
    font-weight: 500;
}

.enrollment-avatar {
    width: 36px;
    height: 36px;
    flex: 0 0 36px;
    border-radius: 50%;
    background: linear-gradient(
        135deg,
        #d7e2f0,
        #9eafc4
    );
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 11px;
    font-weight: 600;
}


/*----------------------------------
    STATUS
-----------------------------------*/

.enrollment-status {
    display: inline-flex;
    align-items: center;
    padding: 5px 10px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 500;
}

.enrollment-status.active {
    color: #059669;
    background: #ecfdf5;
    border: 1px solid #a7f3d0;
}

.enrollment-status.probation {
    color: #d97706;
    background: #fffbeb;
    border: 1px solid #fcd34d;
}

.enrollment-status.inactive {
    color: #64748b;
    background: #f1f5f9;
    border: 1px solid #dbe3ec;
}


/*----------------------------------
    FOOTER
-----------------------------------*/

.recent-enrollments-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 15px 25px;
    background: #fafbfd;
    color: #8297b2;
    font-size: 12px;
}

.enrollment-pagination {
    display: flex;
    align-items: center;
    gap: 5px;
}

.enrollment-pagination button {
    width: 30px;
    height: 30px;
    border: 0;
    border-radius: 6px;
    background: transparent;
    color: #48617d;
    cursor: pointer;
}

.enrollment-pagination button:hover {
    background: #eef5ff;
    color: #2563eb;
}

.enrollment-pagination button.active {
    background: #2563eb;
    color: #fff;
}

.enrollment-pagination span {
    padding: 0 5px;
    color: #8297b2;
}

/*----------------------------------
        RESPONSIVE
-----------------------------------*/

@media (max-width: 1200px) {

    .college-dashboard {
        padding: 25px;
    }

}

@media (max-width: 991px) {

    .kpi-card {
        margin-bottom: 0;
    }

}

@media (max-width: 767px) {

    .college-dashboard {
        padding: 20px 15px 30px;
    }

    .dashboard-header h2 {
        font-size: 22px;
    }

    .dashboard-header p {
        font-size: 14px;
    }

    .kpi-card {
        min-height: auto;
    }

    .card-header-custom {
        padding: 20px 18px 10px;
    }

    .enrollment-chart-wrapper {
        height: 270px;
        padding: 5px 10px 15px;
    }

    .department-chart-wrapper {
        height: 210px;
    }

}

@media (max-width: 767px) {

    .performance-header {
        padding: 20px 18px 8px;
    }

    .performance-filters {
        padding: 8px 18px 14px;
    }

    .performance-filter {
        width: 100%;
    }

    .performance-table-wrapper {
        max-height: 350px;
    }

}

@media (max-width: 767px) {

    .recent-enrollments-header {
        align-items: flex-start;
        flex-direction: column;
        gap: 15px;
    }

    .recent-enrollments-actions {
        width: 100%;
        flex-wrap: wrap;
    }

    .enrollment-filter {
        flex: 1;
    }

    .enrollment-export {
        flex: 1;
    }

    .recent-enrollments-footer {
        align-items: flex-start;
        flex-direction: column;
        gap: 12px;
    }

}


</style>
@endpush


@section('mainContent')

<div class="college-dashboard">

    {{-- HEADER --}}
    <div class="dashboard-header">

        <h2>
            Admin Overview
        </h2>

        <p>
            {{ \Carbon\Carbon::now()->format('l, F d, Y') }}
            ·
            Academic Year 2026–27
        </p>

    </div>


<div class="row">

    {{-- TOTAL STUDENTS --}}
    <div class="col-xl-3 col-md-6">
        <div class="kpi-card">

            <div class="kpi-top">
                <div class="kpi-label">
                    Total Students
                </div>

                <span id="totalStudents">
                    0
                </span>
            </div>

            <div class="kpi-description">
                Number of Students
            </div>

        </div>
    </div>


    {{-- ACTIVE COURSES --}}
    <div class="col-xl-3 col-md-6">
        <div class="kpi-card">

            <div class="kpi-top">
                <div class="kpi-label">
                    Active Courses
                </div>

                <div class="kpi-value" id="activeCourses">
                    0
                </div>
            </div>

            <div class="kpi-description">
                Number of Active Courses
            </div>

        </div>
    </div>


    {{-- FACULTY MEMBERS --}}
    <div class="col-xl-3 col-md-6">
        <div class="kpi-card">

            <div class="kpi-top">
                <div class="kpi-label">
                    Faculty Members
                </div>

                <div class="kpi-value" id="facultyMembers">
                    0
                </div>
            </div>

            <div class="kpi-description">
                Number of Faculty Members
            </div>

        </div>
    </div>


    {{-- Enrollment --}}
    <div class="col-xl-3 col-md-6">
        <div class="kpi-card">

            <div class="kpi-top">
                <div class="kpi-label">
                    Total Enrolled
                </div>

                <div class="kpi-value" id="totalEnrollments">
                    0
                </div>
            </div>

            <div class="kpi-description">
                Number of Enrollment
            </div>

        </div>
    </div>

</div>


    {{-- CHARTS --}}
    <div class="row section-space">

        {{-- ENROLLMENT --}}
        <div class="col-xl-8 col-lg-7">

            <div class="dashboard-chart-card">

                <div class="card-header-custom">

                    <h3 class="card-title-custom">
                       ENROLLMENT TREND
                    </h3>

                    <a href="#" class="card-action">
                       View report →
                    </a>

                </div>

                <div class="enrollment-chart-wrapper">

                    <canvas id="enrollmentTrendChart"></canvas>

                </div>

            </div>

        </div>


        {{-- DEPARTMENT --}}
        <div class="col-xl-4 col-lg-5">

            <div class="dashboard-chart-card">

                <div class="card-header-custom">

                    <h3 class="card-title-custom">
                       BY DEPARTMENT
                    </h3>

                </div>

                <div class="department-chart-wrapper">

                    <canvas id="departmentChart"></canvas>

                </div>

                <div class="department-list">

                    <div class="department-item">
                        <div class="department-name">
                            <span class="department-dot"
                                  style="background:#3d7ff0;"></span>
                            Engineering
                        </div>
                        <span class="department-value">1,420</span>
                    </div>

                    <div class="department-item">
                        <div class="department-name">
                            <span class="department-dot"
                                  style="background:#10b981;"></span>
                            Business
                        </div>
                        <span class="department-value">980</span>
                    </div>

                    <div class="department-item">
                        <div class="department-name">
                            <span class="department-dot"
                                  style="background:#8455e8;"></span>
                            Arts & Science
                        </div>
                        <span class="department-value">840</span>
                    </div>

                    <div class="department-item">
                        <div class="department-name">
                            <span class="department-dot"
                                  style="background:#f59e0b;"></span>
                            Medicine
                        </div>
                        <span class="department-value">620</span>
                    </div>

                    <div class="department-item">
                        <div class="department-name">
                            <span class="department-dot"
                                  style="background:#ef4444;"></span>
                            Law
                        </div>
                        <span class="department-value">310</span>
                    </div>

                    <div class="department-item">
                        <div class="department-name">
                            <span class="department-dot"
                                  style="background:#06b6d4;"></span>
                            Education
                        </div>
                        <span class="department-value">390</span>
                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- =========================================================
     COURSE COMPLETION + ACADEMIC YEAR PERFORMANCE
========================================================= --}}

<div class="row section-space">

    {{-- COURSE COMPLETION BY DEPARTMENT --}}
    <div class="col-xl-4 col-lg-5">

        <div class="chart-card course-completion-card">

            <div class="card-section-title">
                COURSE COMPLETION BY DEPT.
            </div>

            <div class="course-chart-wrapper">
                <canvas id="courseCompletionChart"></canvas>
            </div>

        </div>

    </div>


    {{-- ACADEMIC YEAR-WISE PERFORMANCE --}}
    <div class="col-xl-8 col-lg-7">

        <div class="table-card academic-performance-card">

            {{-- HEADER --}}
            <div class="performance-header">

                <div>
                    <h4>
                       ACADEMIC YEAR-WISE PERFORMANCE
                    </h4>

                    <span>
                        20 records · 4,793 students
                    </span>
                </div>

            </div>


            {{-- FILTERS --}}
            <div class="performance-filters">

                <select class="performance-filter">
                    <option>All Years</option>
                    <option>AY 2026–27</option>
                    <option>AY 2025–26</option>
                    <option>AY 2024–25</option>
                    <option>AY 2023–24</option>
                </select>


                <select class="performance-filter">
                    <option>All Depts</option>
                    <option>Engineering</option>
                    <option>Business</option>
                    <option>Arts & Science</option>
                    <option>Medicine</option>
                    <option>Law</option>
                    <option>Education</option>
                </select>


                <select class="performance-filter">
                    <option>All Batches</option>
                    <option>Year 1</option>
                    <option>Year 2</option>
                    <option>Year 3</option>
                    <option>Year 4</option>
                </select>


                <select class="performance-filter">
                    <option>All Semesters</option>
                    <option>Sem 1</option>
                    <option>Sem 2</option>
                    <option>Sem 3</option>
                    <option>Sem 4</option>
                    <option>Sem 5</option>
                    <option>Sem 6</option>
                    <option>Sem 7</option>
                    <option>Sem 8</option>
                </select>


                <button type="button"
                        class="performance-reset">
                    Reset
                </button>

            </div>


            {{-- TABLE --}}
            <div class="performance-table-wrapper">

                <table class="academic-performance-table">

                    <thead>

                    <tr>

                        <th>
                           ACADEMIC YEAR
                        </th>

                        <th>
                            YEAR / BATCH
                        </th>

                        <th>
                           SEMESTER
                        </th>

                        <th>
                           DEPARTMENT
                        </th>

                        <th>
                           STUDENTS
                        </th>

                        <th>
                           COURSES
                        </th>

                        <th>
                            ATTENDANCE
                        </th>

                        <th>
                           COMPLETION
                        </th>

                        <th>
                            PERFORMANCE
                        </th>

                    </tr>

                    </thead>


                    <tbody>

                    {{-- ROW 1 --}}
                    <tr>

                        <td>
                            <span class="academic-badge">
                                AY 2024–25
                            </span>
                        </td>

                        <td>
                            Year 4
                        </td>

                        <td>
                            <span class="semester-text">
                                Sem 2
                            </span>
                        </td>

                        <td>
                            Engineering
                        </td>

                        <td>
                            342
                        </td>

                        <td>
                            18
                        </td>

                        <td>

                            <div class="progress-info">

                                <div class="mini-progress">
                                    <span style="width:91.4%;"></span>
                                </div>

                                <small>
                                    91.4%
                                </small>

                            </div>

                        </td>

                        <td>

                            <div class="progress-info">

                                <div class="mini-progress completion">
                                    <span style="width:93.2%;"></span>
                                </div>

                                <small>
                                    93.2%
                                </small>

                            </div>

                        </td>

                        <td>

                            <span class="performance-badge excellent">
                                Excellent
                            </span>

                            <small class="gpa-text">
                                8.1 GPA
                            </small>

                        </td>

                    </tr>


                    {{-- ROW 2 --}}
                    <tr>

                        <td>
                            <span class="academic-badge">
                                AY 2024–25
                            </span>
                        </td>

                        <td>
                            Year 4
                        </td>

                        <td>
                            <span class="semester-text">
                                Sem 2
                            </span>
                        </td>

                        <td>
                            Business
                        </td>

                        <td>
                            218
                        </td>

                        <td>
                            16
                        </td>

                        <td>

                            <div class="progress-info">

                                <div class="mini-progress">
                                    <span style="width:89.7%;"></span>
                                </div>

                                <small>
                                    89.7%
                                </small>

                            </div>

                        </td>

                        <td>

                            <div class="progress-info">

                                <div class="mini-progress completion">
                                    <span style="width:91.8%;"></span>
                                </div>

                                <small>
                                    91.8%
                                </small>

                            </div>

                        </td>

                        <td>

                            <span class="performance-badge good">
                                Good
                            </span>

                            <small class="gpa-text">
                                7.9 GPA
                            </small>

                        </td>

                    </tr>


                    {{-- ROW 3 --}}
                    <tr>

                        <td>
                            <span class="academic-badge">
                                AY 2024–25
                            </span>
                        </td>

                        <td>
                            Year 4
                        </td>

                        <td>
                            <span class="semester-text">
                                Sem 2
                            </span>
                        </td>

                        <td>
                            Medicine
                        </td>

                        <td>
                            156
                        </td>

                        <td>
                            20
                        </td>

                        <td>

                            <div class="progress-info">

                                <div class="mini-progress">
                                    <span style="width:95.3%;"></span>
                                </div>

                                <small>
                                    95.3%
                                </small>

                            </div>

                        </td>

                        <td>

                            <div class="progress-info">

                                <div class="mini-progress completion">
                                    <span style="width:96.1%;"></span>
                                </div>

                                <small>
                                    96.1%
                                </small>

                            </div>

                        </td>

                        <td>

                            <span class="performance-badge outstanding">
                                Outstanding
                            </span>

                            <small class="gpa-text">
                                8.6 GPA
                            </small>

                        </td>

                    </tr>


                    {{-- ROW 4 --}}
                    <tr>

                        <td>
                            <span class="academic-badge">
                                AY 2024–25
                            </span>
                        </td>

                        <td>
                            Year 3
                        </td>

                        <td>
                            <span class="semester-text">
                                Sem 2
                            </span>
                        </td>

                        <td>
                            Engineering
                        </td>

                        <td>
                            368
                        </td>

                        <td>
                            22
                        </td>

                        <td>

                            <div class="progress-info">

                                <div class="mini-progress">
                                    <span style="width:87.1%;"></span>
                                </div>

                                <small>
                                    87.1%
                                </small>

                            </div>

                        </td>

                        <td>

                            <div class="progress-info">

                                <div class="mini-progress completion">
                                    <span style="width:88.4%;"></span>
                                </div>

                                <small>
                                    88.4%
                                </small>

                            </div>

                        </td>

                        <td>

                            <span class="performance-badge good">
                                Good
                            </span>

                            <small class="gpa-text">
                                7.8 GPA
                            </small>

                        </td>

                    </tr>


                    {{-- ROW 5 --}}
                    <tr>

                        <td>
                            <span class="academic-badge">
                                AY 2025–26
                            </span>
                        </td>

                        <td>
                            Year 2
                        </td>

                        <td>
                            <span class="semester-text">
                                Sem 2
                            </span>
                        </td>

                        <td>
                            Arts & Science
                        </td>

                        <td>
                            284
                        </td>

                        <td>
                            19
                        </td>

                        <td>

                            <div class="progress-info">

                                <div class="mini-progress">
                                    <span style="width:84.8%;"></span>
                                </div>

                                <small>
                                    84.8%
                                </small>

                            </div>

                        </td>

                        <td>

                            <div class="progress-info">

                                <div class="mini-progress completion">
                                    <span style="width:86.7%;"></span>
                                </div>

                                <small>
                                    86.7%
                                </small>

                            </div>

                        </td>

                        <td>

                            <span class="performance-badge good">
                                Good
                            </span>

                            <small class="gpa-text">
                                7.5 GPA
                            </small>

                        </td>

                    </tr>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

{{-- =========================================================
     RECENT ENROLLMENTS
========================================================= --}}

<div class="row section-space">

    <div class="col-12">

        <div class="table-card recent-enrollments-card">

            {{-- HEADER --}}
            <div class="recent-enrollments-header">

                <h4>
                    RECENT ENROLLMENTS
                </h4>

                <div class="recent-enrollments-actions">

                    <select class="enrollment-filter">
                        <option>All Departments</option>
                        <option>Engineering</option>
                        <option>Business</option>
                        <option>Arts & Science</option>
                        <option>Medicine</option>
                        <option>Law</option>
                        <option>Education</option>
                    </select>

                    <button type="button" class="enrollment-export">
                        Export CSV
                    </button>

                </div>

            </div>


            {{-- TABLE --}}
            <div class="recent-enrollments-table-wrapper">

                <table class="recent-enrollments-table">

                    <thead>
                        <tr>

                            <th>
                                STUDENT ID
                            </th>

                            <th>
                                NAME
                            </th>

                            <th>
                                DEPARTMENT
                            </th>

                            <th>
                                COURSE
                            </th>

                            <th>
                                STATUS
                            </th>

                            <th>
                                ENROLLED
                            </th>

                        </tr>
                    </thead>


                    <tbody>

                        {{-- ROW 1 --}}
                        <tr>

                            <td>
                                <span class="student-id-badge">
                                    STU-2847
                                </span>
                            </td>

                            <td>
                                <div class="enrollment-student">

                                    <span class="enrollment-avatar">
                                        PS
                                    </span>

                                    <span>
                                        Priya Sharma
                                    </span>

                                </div>
                            </td>

                            <td>
                                Engineering
                            </td>

                            <td>
                                Data Structures & Algorithms
                            </td>

                            <td>
                                <span class="enrollment-status active">
                                    Active
                                </span>
                            </td>

                            <td>
                                Jan 12, 2026
                            </td>

                        </tr>


                        {{-- ROW 2 --}}
                        <tr>

                            <td>
                                <span class="student-id-badge">
                                    STU-2848
                                </span>
                            </td>

                            <td>
                                <div class="enrollment-student">

                                    <span class="enrollment-avatar">
                                        MC
                                    </span>

                                    <span>
                                        Marcus Chen
                                    </span>

                                </div>
                            </td>

                            <td>
                                Business
                            </td>

                            <td>
                                Financial Management
                            </td>

                            <td>
                                <span class="enrollment-status active">
                                    Active
                                </span>
                            </td>

                            <td>
                                Jan 12, 2026
                            </td>

                        </tr>


                        {{-- ROW 3 --}}
                        <tr>

                            <td>
                                <span class="student-id-badge">
                                    STU-2849
                                </span>
                            </td>

                            <td>
                                <div class="enrollment-student">

                                    <span class="enrollment-avatar">
                                        AO
                                    </span>

                                    <span>
                                        Aisha Okonkwo
                                    </span>

                                </div>
                            </td>

                            <td>
                                Medicine
                            </td>

                            <td>
                                Anatomy & Physiology
                            </td>

                            <td>
                                <span class="enrollment-status active">
                                    Active
                                </span>
                            </td>

                            <td>
                                Jan 13, 2026
                            </td>

                        </tr>


                        {{-- ROW 4 --}}
                        <tr>

                            <td>
                                <span class="student-id-badge">
                                    STU-2850
                                </span>
                            </td>

                            <td>
                                <div class="enrollment-student">

                                    <span class="enrollment-avatar">
                                        LF
                                    </span>

                                    <span>
                                        Lucas Ferreira
                                    </span>

                                </div>
                            </td>

                            <td>
                                Law
                            </td>

                            <td>
                                Constitutional Law
                            </td>

                            <td>
                                <span class="enrollment-status probation">
                                    Probation
                                </span>
                            </td>

                            <td>
                                Jan 13, 2026
                            </td>

                        </tr>


                        {{-- ROW 5 --}}
                        <tr>

                            <td>
                                <span class="student-id-badge">
                                    STU-2851
                                </span>
                            </td>

                            <td>
                                <div class="enrollment-student">

                                    <span class="enrollment-avatar">
                                        EK
                                    </span>

                                    <span>
                                        Emma Kovács
                                    </span>

                                </div>
                            </td>

                            <td>
                                Arts & Science
                            </td>

                            <td>
                                Quantum Physics
                            </td>

                            <td>
                                <span class="enrollment-status active">
                                    Active
                                </span>
                            </td>

                            <td>
                                Jan 14, 2026
                            </td>

                        </tr>


                        {{-- ROW 6 --}}
                        <tr>

                            <td>
                                <span class="student-id-badge">
                                    STU-2852
                                </span>
                            </td>

                            <td>
                                <div class="enrollment-student">

                                    <span class="enrollment-avatar">
                                        RP
                                    </span>

                                    <span>
                                        Raj Patel
                                    </span>

                                </div>
                            </td>

                            <td>
                                Engineering
                            </td>

                            <td>
                                Machine Learning
                            </td>

                            <td>
                                <span class="enrollment-status active">
                                    Active
                                </span>
                            </td>

                            <td>
                                Jan 14, 2026
                            </td>

                        </tr>


                        {{-- ROW 7 --}}
                        <tr>

                            <td>
                                <span class="student-id-badge">
                                    STU-2853
                                </span>
                            </td>

                            <td>
                                <div class="enrollment-student">

                                    <span class="enrollment-avatar">
                                        SA
                                    </span>

                                    <span>
                                        Sofia Andersen
                                    </span>

                                </div>
                            </td>

                            <td>
                                Education
                            </td>

                            <td>
                                Curriculum Design
                            </td>

                            <td>
                                <span class="enrollment-status inactive">
                                    Inactive
                                </span>
                            </td>

                            <td>
                                Jan 15, 2026
                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>


            {{-- FOOTER --}}
            <div class="recent-enrollments-footer">

                <span>
                    Showing 7 of 4,720 students
                </span>

                <div class="enrollment-pagination">

                    <button>
                        ‹
                    </button>

                    <button class="active">
                        1
                    </button>

                    <button>
                        2
                    </button>

                    <button>
                        3
                    </button>

                    <span>...</span>

                    <button>
                        94
                    </button>

                    <button>
                        ›
                    </button>

                </div>

            </div>

        </div>

    </div>

</div>

</div>

@endsection


@push('js')

<script src="{{ asset('public/backend/vendors/chartlist/Chart.min.js') }}"></script>

<script>

/*----------------------------------
        ENROLLMENT TREND
-----------------------------------*/

var enrollmentCanvas =
    document.getElementById('enrollmentTrendChart');

if (enrollmentCanvas) {

    new Chart(enrollmentCanvas, {

        type: 'line',

        data: {

            labels: [
                'Aug',
                'Sep',
                'Oct',
                'Nov',
                'Dec',
                'Jan',
                'Feb',
                'Mar',
                'Apr',
                'May'
            ],

            datasets: [{

                data: [
                    3800,
                    4150,
                    4100,
                    4250,
                    3900,
                    4550,
                    4620,
                    4780,
                    4850,
                    4700
                ],

                borderColor: '#3d7ff0',

                backgroundColor:
                    'rgba(61,127,240,.08)',

                borderWidth: 2,

                pointRadius: 0,

                pointHoverRadius: 5,

                fill: true,

                lineTension: .4

            }]

        },

        options: {

            responsive: true,

            maintainAspectRatio: false,

            legend: {
                display: false
            },

            tooltips: {

                enabled: true,

                backgroundColor: '#102a43',

                titleFontSize: 13,

                bodyFontSize: 13,

                cornerRadius: 6,

                displayColors: false

            },

            scales: {

                yAxes: [{

                    ticks: {

                        beginAtZero: true,

                        max: 6000,

                        stepSize: 1500,

                        fontColor: '#8297b2',

                        fontSize: 12,

                        padding: 10

                    },

                    gridLines: {

                        color: '#e8eef5',

                        borderDash: [3,3],

                        zeroLineColor: '#e8eef5'

                    }

                }],

                xAxes: [{

                    ticks: {

                        fontColor: '#8297b2',

                        fontSize: 12

                    },

                    gridLines: {

                        color: '#e8eef5',

                        borderDash: [3,3]

                    }

                }]

            },

            animation: {

                duration: 900

            }

        }

    });

}


/*----------------------------------
        DEPARTMENT DONUT
-----------------------------------*/

var departmentCanvas =
    document.getElementById('departmentChart');

if (departmentCanvas) {

    new Chart(departmentCanvas, {

        type: 'doughnut',

        data: {

            labels: [
                'Engineering',
                'Business',
                'Arts & Science',
                'Medicine',
                'Law',
                'Education'
            ],

            datasets: [{

                data: [
                    1420,
                    980,
                    840,
                    620,
                    310,
                    390
                ],

                backgroundColor: [
                    '#3d7ff0',
                    '#10b981',
                    '#8455e8',
                    '#f59e0b',
                    '#ef4444',
                    '#06b6d4'
                ],

                borderWidth: 3,

                borderColor: '#ffffff'

            }]

        },

        options: {

            responsive: true,

            maintainAspectRatio: false,

            cutoutPercentage: 62,

            legend: {
                display: false
            },

            tooltips: {

                backgroundColor: '#102a43',

                titleFontSize: 13,

                bodyFontSize: 13,

                cornerRadius: 6

            },

            animation: {

                animateScale: true,

                animateRotate: true,

                duration: 900

            }

        }

    });

}

</script>

<script>

/*----------------------------------
    COURSE COMPLETION BY DEPARTMENT
-----------------------------------*/

var courseCompletionCanvas =
    document.getElementById('courseCompletionChart');

if (courseCompletionCanvas) {

    new Chart(courseCompletionCanvas, {

        type: 'bar',

        data: {

            labels: [
                'Engineering',
                'Business',
                'Arts',
                'Medicine',
                'Law',
                'Education'
            ],

            datasets: [{

                data: [
                    87,
                    91,
                    78,
                    94,
                    83,
                    89
                ],

                backgroundColor: '#3d7ff0',

                borderRadius: 5,

                borderWidth: 0

            }]

        },

        options: {

            responsive: true,

            maintainAspectRatio: false,

            legend: {
                display: false
            },

            tooltips: {

                enabled: true,

                backgroundColor: '#102a43',

                titleFontSize: 12,

                bodyFontSize: 12,

                cornerRadius: 6,

                displayColors: false,

                callbacks: {

                    label: function(tooltipItem) {

                        return tooltipItem.yLabel + '%';

                    }

                }

            },

            scales: {

                yAxes: [{

                    ticks: {

                        min: 60,

                        max: 100,

                        stepSize: 10,

                        fontColor: '#8297b2',

                        fontSize: 11,

                        padding: 8

                    },

                    gridLines: {

                        color: '#e8eef5',

                        borderDash: [3,3],

                        zeroLineColor: '#e8eef5'

                    }

                }],

                xAxes: [{

                    ticks: {

                        fontColor: '#8297b2',

                        fontSize: 11

                    },

                    gridLines: {

                        display: false

                    }

                }]

            },

            animation: {

                duration: 900

            }

        }

    });

}


/*----------------------------------
    RESET FILTERS
-----------------------------------*/

$('.performance-reset').on('click', function() {

    $('.performance-filter').each(function() {

        $(this).prop('selectedIndex', 0);

    });

});

</script>

<script type="text/javascript">
    $(document).ready(function() {
        $.ajax({
            url: '{{ route("college_data") }}',
            method: 'GET',
            success: function(data) {

                if (typeof data === 'string') {
                    data = JSON.parse(data);
                }

                console.log('Parsed data:', data);

                $('#totalStudents').text(data.totalStudents);
                $('#activeCourses').text(data.activeCourses);
                $('#facultyMembers').text(data.facultyMembers);
                $('#totalEnrollments').text(data.totalEnrollments);
                $('#enrollmentCount').text(data.enrollmentCount);
                // Assuming you have a chart or some element to display the enrollment trend
                // You can update it here using data.enrollmentTrend
            }
        });
    });
</script>

@endpush