@extends(theme('layouts.dashboard_master'))

@section('title')
    {{ Settings('site_title') ? Settings('site_title') : 'Infix LMS' }} |
    {{ __('Resource Details') }}
@endsection

@section('css')

<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet"/>

<style>

.resource-details-page{
    padding:30px;
    background:#f5f7fb;
    min-height:100vh;
}

/* =====================================================
BREADCRUMB
===================================================== */

.breadcrumb-wrapper{
    display:flex;
    align-items:center;
    gap:10px;

    margin-bottom:26px;

    font-size:14px;
    font-weight:600;
}

.breadcrumb-wrapper a{
    color:#4f46e5;
    text-decoration:none;
}

.breadcrumb-wrapper span{
    color:#94a3b8;
}

/* =====================================================
PROFILE CARD
===================================================== */

.profile-card{
    background:#fff;

    border:1px solid #e5e7eb;

    border-radius:32px;

    padding:34px;

    margin-bottom:28px;
}

.profile-top{
    display:flex;
    align-items:flex-start;
    justify-content:space-between;

    gap:30px;

    flex-wrap:wrap;
}

.profile-user{
    display:flex;
    align-items:center;
    gap:24px;
}

.profile-avatar{
    width:120px;
    height:120px;

    border-radius:28px;

    background:#eef2ff;

    display:flex;
    align-items:center;
    justify-content:center;

    flex-shrink:0;
}

.profile-avatar i{
    font-size:60px;
    color:#4f46e5;
}

.profile-name{
    font-size:34px;
    font-weight:800;
    color:#111827;
    margin-bottom:10px;
}

.profile-role{
    font-size:17px;
    font-weight:700;
    color:#4f46e5;
    margin-bottom:8px;
}

.profile-company{
    font-size:15px;
    color:#6b7280;
}

/* =====================================================
BUTTONS
===================================================== */

.profile-actions{
    display:flex;
    align-items:center;
    gap:16px;
    flex-wrap:wrap;
}

.primary-btn{
    height:58px;

    padding:0 28px;

    border:none;

    border-radius:18px;

    background:linear-gradient(
        135deg,
        #2563eb,
        #4f46e5
    );

    color:#fff;

    font-size:15px;
    font-weight:700;

    display:inline-flex;
    align-items:center;
    justify-content:center;
    gap:12px;

    transition:.25s ease;
}

.primary-btn:hover{
    transform:translateY(-2px);
    box-shadow:0 12px 24px rgba(37,99,235,.18);
}

.secondary-btn{
    height:58px;

    padding:0 26px;

    border:none;

    border-radius:18px;

    background:#eef2ff;
    color:#4f46e5;

    font-size:15px;
    font-weight:700;

    display:inline-flex;
    align-items:center;
    justify-content:center;
    gap:12px;

    transition:.25s ease;
}

.secondary-btn:hover{
    background:#e0e7ff;
}

/* =====================================================
INFO CARD
===================================================== */

.info-card{
    background:#fff;

    border:1px solid #e5e7eb;

    border-radius:28px;

    padding:30px;

    height:auto;
}

.info-title{
    font-size:20px;
    font-weight:800;
    color:#111827;
    margin-bottom:26px;
}

/* =====================================================
DETAIL LIST
===================================================== */

.detail-list{
    display:flex;
    flex-direction:column;
    gap:20px;
}

.detail-item{
    display:flex;
    align-items:flex-start;
    gap:16px;
}

.detail-icon{
    width:50px;
    height:50px;

    border-radius:16px;

    background:#eef2ff;

    display:flex;
    align-items:center;
    justify-content:center;

    flex-shrink:0;
}

.detail-icon i{
    font-size:24px;
    color:#4f46e5;
}

.detail-content h6{
    font-size:15px;
    font-weight:700;
    color:#111827;
    margin-bottom:5px;
}

.detail-content p{
    margin:0;
    font-size:14px;
    color:#6b7280;
}

/* =====================================================
ABOUT
===================================================== */

.about-text{
    font-size:15px;
    line-height:1.9;
    color:#6b7280;
}

/* =====================================================
SKILLS LIST VIEW
===================================================== */

.skills-list-wrapper{
    display:flex;
    flex-direction:column;
    gap:22px;
}

.skill-list-item{
    width:100%;
}

.skill-top{
    display:flex;
    align-items:center;
    justify-content:space-between;

    margin-bottom:10px;

    gap:20px;
}

.skill-name{
    font-size:15px;
    font-weight:700;
    color:#111827;
}

.skill-meta{
    font-size:13px;
    font-weight:600;
    color:#6b7280;
}

.skill-progress{
    width:100%;
    height:10px;

    background:#eef2f7;

    border-radius:30px;

    overflow:hidden;
}

.skill-progress-bar{
    height:100%;

    border-radius:30px;

    background:linear-gradient(
        90deg,
        #2563eb,
        #4f46e5
    );
}


/* =====================================================
PROJECTS
===================================================== */

.project-card{
    background:#f8fafc;

    border-radius:22px;

    padding:24px;

    margin-bottom:18px;
}

.project-card:last-child{
    margin-bottom:0;
}

.project-name{
    font-size:17px;
    font-weight:700;
    color:#111827;
    margin-bottom:12px;
}

.project-desc{
    font-size:15px;
    line-height:1.8;
    color:#6b7280;
}

/* =====================================================
RESPONSIVE
===================================================== */

@media(max-width:991px){

    .resource-details-page{
        padding:20px;
    }

}

@media(max-width:767px){

    .profile-user{
        flex-direction:column;
        align-items:flex-start;
    }

    .profile-name{
        font-size:28px;
    }

}

</style>

@endsection

@section('mainContent')

<div class="container-fluid resource-details-page">

    <!-- BREADCRUMB -->

    <div class="breadcrumb-wrapper">

        <a href="">
            Dashboard
        </a>

        <span>/</span>

        <span>
            Resource Details
        </span>

    </div>

    <!-- PROFILE CARD -->

    <div class="profile-card">

        <div class="profile-top">

            <!-- LEFT -->

            <div class="profile-user">

                <div class="profile-avatar">
                    <i class="ri-user-3-line"></i>
                </div>

                <div>

                    <h2 class="profile-name">
                        {{ $detail->name ?? 'Name' }}
                    </h2>

                    <div class="profile-role">
                        {{ $detail->profile->profile_name ?? 'Role' }}
                    </div>

                    <div class="profile-company">
                        {{ $detail->TenantUser->tenant_name ?? 'Company' }}
                    </div>

                </div>

            </div>

            <!-- RIGHT -->

            <div class="profile-actions">

                <button class="primary-btn">

                    <i class="ri-send-plane-line"></i>
                    Send Request

                </button>

            </div>

        </div>

    </div>

    <!-- MAIN GRID -->

    <div class="row g-4">

        <!-- LEFT SIDEBAR -->

        <div class="col-xl-4">

            <!-- PERSONAL INFO -->

            <div class="info-card mb-4">

                <h4 class="info-title">
                    Personal Information
                </h4>

                <div class="detail-list">

                    <div class="detail-item">

                        <div class="detail-icon">
                            <i class="ri-map-pin-line"></i>
                        </div>

                        <div class="detail-content">

                            <h6>Location</h6>

                            <p>
                                {{ $detail->locationcode->location_code ?? 'N/A' }}
                            </p>

                        </div>

                    </div>

                    <div class="detail-item">

                        <div class="detail-icon">
                            <i class="ri-briefcase-line"></i>
                        </div>

                        <div class="detail-content">

                            <h6>Experience</h6>

                            <p>
                                {{ $detail->experience ?? 'N/A' }}
                            </p>

                        </div>

                    </div>

                    <div class="detail-item">

                        <div class="detail-icon">
                            <i class="ri-building-line"></i>
                        </div>

                        <div class="detail-content">

                            <h6>Company</h6>

                            <p>
                                {{ $detail->TenantUser->tenant_name ?? 'N/A' }}
                            </p>

                        </div>

                    </div>

                    <!-- <div class="detail-item">

                        <div class="detail-icon">
                            <i class="ri-time-line"></i>
                        </div>

                        <div class="detail-content">

                            <h6>Availability</h6>

                            <p>
                                Immediate Joiner
                            </p>

                        </div>

                    </div> -->

                </div>

            </div>

<!-- SKILLS -->

<div class="info-card">

    <h4 class="info-title">
        Skills & Technologies
    </h4>

    <div class="skills-list-wrapper">

        <!-- ITEM -->

        <div class="skill-list-item">

            <div class="skill-top">

                <div class="skill-name">
                    {{ $skill->name ?? 'Skill' }}
                </div>

                <div class="skill-meta">
                    {{ $skill->level ?? 'Level' }} • {{ $skill->percentage ?? '0%' }}
                </div>

            </div>

            <div class="skill-progress">

                <div class="skill-progress-bar"
                     style="width:95%">
                </div>

            </div>

        </div>

        <!-- ITEM -->

        <div class="skill-list-item">

            <div class="skill-top">

                <div class="skill-name">
                    PHP
                </div>

                <div class="skill-meta">
                    Advanced • 90%
                </div>

            </div>

            <div class="skill-progress">

                <div class="skill-progress-bar"
                     style="width:90%">
                </div>

            </div>

        </div>

        <!-- ITEM -->

        <div class="skill-list-item">

            <div class="skill-top">

                <div class="skill-name">
                    MySQL
                </div>

                <div class="skill-meta">
                    Intermediate • 82%
                </div>

            </div>

            <div class="skill-progress">

                <div class="skill-progress-bar"
                     style="width:82%">
                </div>

            </div>

        </div>

        <!-- ITEM -->

        <div class="skill-list-item">

            <div class="skill-top">

                <div class="skill-name">
                    REST API
                </div>

                <div class="skill-meta">
                    Expert • 96%
                </div>

            </div>

            <div class="skill-progress">

                <div class="skill-progress-bar"
                     style="width:96%">
                </div>

            </div>

        </div>

        <!-- ITEM -->

        <div class="skill-list-item">

            <div class="skill-top">

                <div class="skill-name">
                    Docker
                </div>

                <div class="skill-meta">
                    Intermediate • 75%
                </div>

            </div>

            <div class="skill-progress">

                <div class="skill-progress-bar"
                     style="width:75%">
                </div>

            </div>

        </div>

        <!-- ITEM -->

        <div class="skill-list-item">

            <div class="skill-top">

                <div class="skill-name">
                    AWS
                </div>

                <div class="skill-meta">
                    Intermediate • 78%
                </div>

            </div>

            <div class="skill-progress">

                <div class="skill-progress-bar"
                     style="width:78%">
                </div>

            </div>

        </div>

    </div>

</div>

        </div>

        <!-- RIGHT CONTENT -->

        <div class="col-xl-8">

            <!-- ABOUT -->

            <div class="info-card mb-4">

                <h4 class="info-title">
                    About Resource
                </h4>

                <p class="about-text">

                    Experienced Laravel developer with strong expertise
                    in backend architecture, scalable enterprise
                    applications, REST API development, cloud deployment,
                    and performance optimization.

                    Passionate about clean coding standards,
                    reusable architecture patterns,
                    and modern web technologies.

                </p>

            </div>

            <!-- PROJECTS -->

            <div class="info-card">

                <h4 class="info-title">
                    Recent Projects
                </h4>

                <div class="project-card">

                    <div class="project-name">
                        Enterprise CRM Platform
                    </div>

                    <div class="project-desc">

                        Developed scalable CRM platform using Laravel,
                        Vue JS, and MySQL with role-based permissions,
                        advanced analytics dashboards,
                        and API integrations.

                    </div>

                </div>

                <div class="project-card">

                    <div class="project-name">
                        HR Management System
                    </div>

                    <div class="project-desc">

                        Built employee and payroll management solution
                        integrated with REST APIs,
                        attendance tracking,
                        and cloud deployment architecture.

                    </div>

                </div>

                <div class="project-card">

                    <div class="project-name">
                        Multi Vendor Marketplace
                    </div>

                    <div class="project-desc">

                        Developed large-scale eCommerce marketplace
                        with payment gateway integration,
                        order management,
                        and optimized admin reporting system.

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection