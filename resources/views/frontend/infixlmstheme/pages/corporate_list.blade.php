@extends(theme('layouts.dashboard_master'))

@section('title')
    {{ Settings('site_title') ? Settings('site_title') : 'Infix LMS' }} |
    {{ __('Corporate Resources') }}
@endsection

@section('css')

<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet"/>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>

*{
    font-family:'Inter',sans-serif;
}

body{
    background:#f5f7fb;
    color:#111827;
}

.resources-wrapper{
    padding:24px;
    background:#f5f7fb;
    min-height:100vh;
}

/* =====================================================
TOP BAR
===================================================== */

.top-action-bar{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:20px;
    margin-bottom:24px;
    flex-wrap:wrap;
}

.page-title{
    font-size:32px;
    font-weight:800;
    line-height:1.1;
    letter-spacing:-1px;
    color:#111827;
    margin-bottom:8px;
}

.page-subtitle{
    font-size:15px;
    color:#6b7280;
}

/* =====================================================
VIEW SWITCHER
===================================================== */

.view-switcher{
    display:flex;
    align-items:center;
    gap:10px;
    background:#fff;
    border:1px solid #e5e7eb;
    padding:8px;
    border-radius:20px;
}

.view-btn{
    width:52px;
    height:52px;
    border:none;
    border-radius:14px;
    background:transparent;
    color:#64748b;
    display:flex;
    align-items:center;
    justify-content:center;
    transition:.25s ease;
    cursor:pointer;
}

.view-btn i{
    font-size:24px;
}

.view-btn:hover{
    background:#f3f4f6;
}

.view-btn.active{
    background:#17172d;
    color:#fff;
}

/* =====================================================
VIEWS
===================================================== */

.view-section{
    display:none;
}

.view-section.active-view{
    display:block;
}

/* =====================================================
TOP GLOBAL SEARCH
===================================================== */

.global-search-section{
    width: 80%;
    margin-left: 10%;
    margin-bottom:28px;
    
}

.global-search-bar{
    background:#fff;
    border:1px solid #d1d5db;

    border-radius:30px;

    display:flex;
    align-items:center;

    padding:10px;

    gap:10px;

    box-shadow:
        0 6px 18px rgba(0,0,0,.05);
}

.global-search-group{
    flex:1;

    display:flex;
    align-items:center;

    gap:14px;

    padding:0 20px;
}

.global-search-group i{
    font-size:24px;
    color:#444;
}

.global-search-group input{
    width:100%;
    height:58px;

    border:none;
    outline:none;
    background:transparent;

    font-size:17px;
    font-weight:500;

    color:#111827;
}

.global-search-group input::placeholder{
    color:#6b7280;
}

.search-border{
    border-left:1px solid #e5e7eb;
}

.global-search-btn{
    min-width:170px;
    height:58px;

    border:none;
    border-radius:20px;

    background:#0d5bd7;
    color:#fff;

    font-size:18px;
    font-weight:700;

    transition:.25s ease;
}

.global-search-btn:hover{
    background:#0a4bb3;
}

.global-search-btn:disabled{
    background:#cbd5e1;
    cursor:not-allowed;
    opacity:.7;
}
/* =====================================================
FILTER CHIPS
===================================================== */

.global-filter-wrapper{
    display:flex;
    flex-wrap:wrap;

    gap:12px;

    margin-top:22px;
}

.global-filter-chip{
    height:48px;

    padding:0 22px;

    border-radius:40px;

    background:#fff;

    border:1px solid #e5e7eb;

    font-size:15px;
    font-weight:500;

    color:#111827;

    transition:.25s ease;
}

.global-filter-chip:hover{
    border-color:#111827;
}

.active-global-chip{
    border:2px solid #111827;
    font-weight:700;
}

/* =====================================================
FILTER DROPDOWN
===================================================== */

.filter-dropdown-wrapper{
    position:relative;
}

.filter-dropdown-btn{
    display:flex;
    align-items:center;
    gap:10px;
}

.filter-dropdown-btn i{
    font-size:18px;
}

/* =====================================================
DROPDOWN MENU
===================================================== */

.filter-dropdown-menu{
    position:absolute;

    top:62px;
    left:0;

    width:340px;

    background:#fff;

    border:1px solid #e5e7eb;

    border-radius:24px;

    padding:18px;

    box-shadow:
        0 12px 40px rgba(0,0,0,.12);

    z-index:100;

    display:none;
}

.filter-dropdown-menu.show-dropdown{
    display:block;
}

/* =====================================================
SEARCH BOX
===================================================== */

.filter-search-box{
    margin-bottom:16px;
}

.filter-search-box input{
    width:100%;
    height:48px;

    border:1px solid #e5e7eb;
    border-radius:14px;

    padding:0 16px;

    font-size:14px;

    outline:none;
}

/* =====================================================
OPTIONS
===================================================== */

.filter-options-scroll{
    max-height:260px;
    overflow-y:auto;

    display:flex;
    flex-direction:column;
    gap:14px;

    padding-right:6px;
}

.filter-options-scroll::-webkit-scrollbar{
    width:6px;
}

.filter-options-scroll::-webkit-scrollbar-thumb{
    background:#cbd5e1;
    border-radius:20px;
}

.filter-option-item{
    display:flex;
    align-items:center;
    gap:14px;

    font-size:16px;
    font-weight:500;

    color:#374151;

    cursor:pointer;
}

.filter-option-item input{
    width:22px;
    height:22px;

    border-radius:6px;
}

/* =====================================================
FOOTER
===================================================== */

.filter-footer{
    display:flex;
    justify-content:flex-end;
    gap:12px;

    margin-top:20px;
}

.clear-filter-btn{
    height:46px;
    padding:0 18px;

    border-radius:14px;

    background:#fff;
    border:1px solid #e5e7eb;

    font-weight:600;
}

.apply-filter-btn{
    height:46px;
    padding:0 20px;

    border:none;
    border-radius:14px;

    background:#0d5bd7;
    color:#fff;

    font-weight:700;
}

/* =====================================================
COMPANY VIEW
===================================================== */

.resource-card{
    background:#fff;
    border:1px solid #e5e7eb;
    border-radius:26px;
    padding:28px;
    height:100%;
    transition:.25s ease;
}

.resource-card:hover{
    transform:translateY(-3px);
    box-shadow:0 10px 25px rgba(0,0,0,.05);
}

.card-top{
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    gap:20px;
    margin-bottom:24px;
}

.company-header{
    display:flex;
    gap:16px;
}

.company-icon{
    width:72px;
    height:72px;
    border-radius:22px;
    background:#eef2ff;
    display:flex;
    align-items:center;
    justify-content:center;
}

.company-icon i{
    font-size:32px;
    color:#4f46e5;
}

.company-name{
    font-size:20px;
    font-weight:700;
    letter-spacing:-0.3px;
    color:#111827;
    margin-bottom:6px;
}

.company-desc{
    font-size:14px;
    line-height:1.8;
    color:#64748b;
}

.resource-badge{
    background:#eef4ff;
    color:#2563ff;
    border-radius:14px;
    padding:10px 16px;
    font-size:13px;
    font-weight:700;
}

.info-list{
    display:flex;
    flex-direction:column;
    gap:14px;
}

.info-item{
    display:flex;
    align-items:center;
    gap:10px;
    color:#64748b;
    font-size:14px;
}

.info-item i{
    font-size:18px;
}

/* =====================================================
MASTER DETAIL LAYOUT
===================================================== */

.resource-layout{
    display:flex;
    gap:20px;
    height:calc(100vh - 180px);
}

/* =====================================================
LEFT PANEL
===================================================== */

.resource-list-panel{
    width:500px;
    min-width:500px;
    max-width:500px;
    background:#fff;
    border:1px solid #e5e7eb;
    border-radius:28px;
    overflow:hidden;
    display:flex;
    flex-direction:column;
}

.resource-list-header{
    padding:24px;
    border-bottom:1px solid #eef2f7;
}

.resource-panel-title{
    font-size:24px;
    font-weight:800;
    color:#111827;
    margin-bottom:6px;
}

.resource-panel-subtitle{
    font-size:14px;
    color:#64748b;
}

.resource-list-scroll{
    flex:1;
    overflow-y:auto;
    padding:18px;
}

/* =====================================================
RESOURCE LIST CARD
===================================================== */

.resource-list-card{
    border:1px solid #e5e7eb;
    border-radius:24px;
    padding:20px;
    margin-bottom:18px;
    cursor:pointer;
    transition:.25s ease;
    background:#fff;
}

.resource-list-card:hover{
    border-color:#6366f1;
    transform:translateY(-2px);
}

.active-resource{
    border:2px solid #4f46e5;
    background:#f5f7ff;
}

.resource-list-top{
    display:flex;
    align-items:flex-start;
    justify-content:space-between;
    gap:14px;
}

.resource-mini-avatar{
    width:62px;
    height:62px;
    border-radius:18px;
    background:#eef2ff;
    display:flex;
    align-items:center;
    justify-content:center;
}

.resource-mini-avatar i{
    font-size:28px;
    color:#4f46e5;
}

.resource-mini-info{
    flex:1;
}

.resource-mini-name{
    font-size:18px;
    font-weight:700;
    color:#111827;
    margin-bottom:6px;
}

.resource-mini-role{
    font-size:15px;
    color:#64748b;
}

.resource-mini-meta{
    margin-top:16px;
    display:flex;
    flex-direction:column;
    gap:10px;
}

.resource-mini-meta span{
    display:flex;
    align-items:center;
    gap:8px;
    font-size:14px;
    color:#64748b;
}

/* =====================================================
MATCH BADGE
===================================================== */

.match-badge{
    min-width:86px;
    height:42px;
    display:flex;
    align-items:center;
    justify-content:center;
    background:#645edf;
    color:#fff;
    border-radius:14px;
    font-size:14px;
    font-weight:800;
}

/* =====================================================
RIGHT PANEL
===================================================== */

.resource-details-panel{
    flex:1;
    background:#fff;
    border:1px solid #e5e7eb;
    border-radius:28px;
    overflow:hidden;
}

.resource-details-scroll{
    height:100%;
    overflow-y:auto;
    padding:28px;
}

/* =====================================================
PROFILE HEADER
===================================================== */

.profile-header-card{
    border:1px solid #e5e7eb;
    border-radius:26px;
    padding:28px;
    margin-bottom:24px;
}

.profile-top{
    display:flex;
    justify-content:space-between;
    gap:20px;
}

.profile-user-section{
    display:flex;
    gap:20px;
}

.profile-avatar{
    width:92px;
    height:92px;
    border-radius:24px;
    background:#eef2ff;
    display:flex;
    align-items:center;
    justify-content:center;
}

.profile-avatar i{
    font-size:44px;
    color:#4f46e5;
}

.profile-name{
    font-size:30px;
    font-weight:800;
    line-height:1.1;
    letter-spacing:-0.8px;
    color:#111827;
}

.profile-role{
    font-size:17px;
    color:#4f46e5;
    margin-top:4px;
}

.profile-company{
    font-size:15px;
    color:#64748b;
    margin-top:6px;
}

.profile-actions{
    margin-top:24px;
}

.action-btn{
    height:50px;
    padding:0 22px;
    border-radius:14px;
    background:#3DC13C;
    color:#fff !important;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    gap:8px;
    text-decoration:none !important;
    font-size:14px;
    font-weight:700;
    border:none;
}

/* =====================================================
PROFILE GRID
===================================================== */

.profile-info-grid{
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:24px;
}

.profile-info-card{
    border:1px solid #e5e7eb;
    border-radius:24px;
    padding:24px;
    background:#fff;
}

.full-width-card{
    grid-column:1 / -1;
}

.profile-info-title{
    font-size:18px;
    font-weight:700;
    margin-bottom:20px;
    color:#111827;
}

.profile-info-item{
    display:flex;
    align-items:center;
    gap:10px;
    margin-bottom:16px;
    color:#64748b;
    font-size:15px;
}

/* =====================================================
SKILL LIST
===================================================== */

.skill-list{
    display:flex;
    flex-direction:column;
    gap:16px;
}

.skill-row{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:20px;
}

.skill-left{
    display:flex;
    align-items:center;
    gap:12px;
}

.skill-dot{
    width:10px;
    height:10px;
    border-radius:50%;
    background:#645edf;
}

.skill-name{
    font-size:15px;
    font-weight:600;
    color:#111827;
}

.skill-level{
    padding:6px 12px;
    border-radius:30px;
    font-size:12px;
    font-weight:700;
}

.level-advanced{
    background:#dcfce7;
    color:#15803d;
}

.level-intermediate{
    background:#fef3c7;
    color:#b45309;
}

.level-beginner{
    background:#fee2e2;
    color:#b91c1c;
}

/* =====================================================
PROJECTS
===================================================== */

.project-list{
    display:flex;
    flex-direction:column;
    gap:18px;
}

.project-item{
    border:1px solid #e5e7eb;
    border-radius:18px;
    padding:18px;
    transition:.25s ease;
}

.project-item:hover{
    border-color:#645edf;
    background:#fafaff;
}

.project-title{
    font-size:16px;
    font-weight:700;
    color:#111827;
    margin-bottom:8px;
}

.project-desc{
    font-size:14px;
    line-height:1.8;
    color:#64748b;
    margin-bottom:14px;
}

.project-tech{
    display:flex;
    flex-wrap:wrap;
    gap:10px;
}

.project-pill{
    background:#eef2ff;
    color:#4f46e5;
    padding:8px 14px;
    border-radius:30px;
    font-size:12px;
    font-weight:700;
}

.candidate-about{
    color:#64748b;
    line-height:1.9;
    font-size:15px;
}

/* =====================================================
SCROLLBAR
===================================================== */

.resource-list-scroll::-webkit-scrollbar,
.resource-details-scroll::-webkit-scrollbar{
    width:8px;
}

.resource-list-scroll::-webkit-scrollbar-thumb,
.resource-details-scroll::-webkit-scrollbar-thumb{
    background:#d1d5db;
    border-radius:20px;
}

/* =====================================================
RESPONSIVE
===================================================== */

@media(max-width:1200px){

    .resource-layout{
        flex-direction:column;
        height:auto;
    }

    .resource-list-panel{
        width:100%;
        min-width:100%;
        max-width:100%;
        height:500px;
    }

    .profile-info-grid{
        grid-template-columns:1fr;
    }

}


@media(max-width:992px){

    .global-search-bar{
        flex-direction:column;
        align-items:stretch;
    }

    .search-border{
        border-left:none;
        border-top:1px solid #e5e7eb;
    }

    .global-search-btn{
        width:100%;
    }

}

@media(max-width:768px){

    .profile-top{
        flex-direction:column;
    }

    .profile-user-section{
        flex-direction:column;
    }

    .resources-wrapper{
        padding:16px;
    }

}

</style>

@endsection

@section('mainContent')

<div class="container-fluid resources-wrapper">



<!-- =====================================================
TOP BAR
===================================================== -->

<div class="top-action-bar">

    <div>

        <div class="page-title">
            Corporate Resources
        </div>

        <div class="page-subtitle">
            Browse bench resources and employee profiles
        </div>

    </div>

    <div class="view-switcher">

        <button type="button"
                class="view-btn active"
                data-view="company">

            <i class="ri-building-4-line"></i>

        </button>

        <button type="button"
                class="view-btn"
                data-view="resource">

            <i class="ri-team-line"></i>

        </button>

    </div>

</div>

    <!-- =====================================================
TOP SEARCH + FILTER
===================================================== -->

<div class="global-search-section">

    <!-- SEARCH BAR -->

    <div class="global-search-bar">

        <!-- KEYWORD -->

        <div class="global-search-group">

            <i class="ri-search-line"></i>

            <input type="text"
                   id="globalKeyword"
                   placeholder="keyword, skill or company">

        </div>

        <!-- LOCATION -->

        <div class="global-search-group search-border">

            <i class="ri-map-pin-line"></i>

            <input type="text"
                   id="globalLocation"
                   placeholder="Location">

        </div>

        <!-- BUTTON -->

        <button class="global-search-btn">

            Find Resource

        </button>

    </div>

    <!-- FILTER CHIPS -->

    <div class="global-filter-wrapper d-none">

    <!-- BUSINESS UNIT -->

    <div class="filter-dropdown-wrapper">

        <button class="global-filter-chip filter-dropdown-btn active-global-chip">

            Business Unit

            <i class="ri-arrow-down-s-line"></i>

        </button>

        <div class="filter-dropdown-menu">

            <div class="filter-search-box">

                <input type="text"
                       placeholder="Search business unit">

            </div>

            <div class="filter-options-scroll">

                <label class="filter-option-item">
                    <input type="checkbox">
                    TechCorp
                </label>

                <label class="filter-option-item">
                    <input type="checkbox">
                    Nexa Labs
                </label>

                <label class="filter-option-item">
                    <input type="checkbox">
                    CloudSphere
                </label>

                <label class="filter-option-item">
                    <input type="checkbox">
                    SecureNet
                </label>

                <label class="filter-option-item">
                    <input type="checkbox">
                    PixelCraft
                </label>

            </div>

            <div class="filter-footer">

                <button class="clear-filter-btn">
                    Clear
                </button>

                <button class="apply-filter-btn">
                    Apply
                </button>

            </div>

        </div>

    </div>

    <!-- PROFILE -->

    <div class="filter-dropdown-wrapper">

        <button class="global-filter-chip filter-dropdown-btn">

            Profile

            <i class="ri-arrow-down-s-line"></i>

        </button>

        <div class="filter-dropdown-menu">

            <div class="filter-search-box">

                <input type="text"
                       placeholder="Search profile">

            </div>

            <div class="filter-options-scroll">

                <label class="filter-option-item">
                    <input type="checkbox">
                    Laravel Developer
                </label>

                <label class="filter-option-item">
                    <input type="checkbox">
                    React Developer
                </label>

                <label class="filter-option-item">
                    <input type="checkbox">
                    UI/UX Designer
                </label>

                <label class="filter-option-item">
                    <input type="checkbox">
                    DevOps Engineer
                </label>

                <label class="filter-option-item">
                    <input type="checkbox">
                    QA Engineer
                </label>

            </div>

            <div class="filter-footer">

                <button class="clear-filter-btn">
                    Clear
                </button>

                <button class="apply-filter-btn">
                    Apply
                </button>

            </div>

        </div>

    </div>

    <!-- EXPERIENCE -->

    <div class="filter-dropdown-wrapper">

        <button class="global-filter-chip filter-dropdown-btn">

            Experience

            <i class="ri-arrow-down-s-line"></i>

        </button>

        <div class="filter-dropdown-menu">

            <div class="filter-options-scroll">

                <label class="filter-option-item">
                    <input type="checkbox">
                    0 - 2 Years
                </label>

                <label class="filter-option-item">
                    <input type="checkbox">
                    2 - 4 Years
                </label>

                <label class="filter-option-item">
                    <input type="checkbox">
                    4 - 6 Years
                </label>

                <label class="filter-option-item">
                    <input type="checkbox">
                    6+ Years
                </label>

            </div>

            <div class="filter-footer">

                <button class="clear-filter-btn">
                    Clear
                </button>

                <button class="apply-filter-btn">
                    Apply
                </button>

            </div>

        </div>

    </div>

</div>

</div>


    <!-- COMPANY VIEW -->

    <div class="view-section active-view" id="companyView">

        <div class="row g-4" id="tenantCompanyList">

        </div>

    </div>

    <!-- RESOURCE VIEW -->

    <div class="view-section" id="resourceView">

        <div class="resource-layout">

            <!-- LEFT -->

            <div class="resource-list-panel">

                <div class="resource-list-header">

                    <div class="resource-panel-title">
                        Resource List
                    </div>

                    <div class="resource-panel-subtitle">
                        6 Resources Found
                    </div>

                </div>

                <div class="resource-list-scroll">

                    @php
                        $resources = [
                            ['john','John Carter','Laravel Developer','TechCorp','New York','92%'],
                            ['sarah','Sarah Wilson','UI/UX Designer','Nexa Labs','California','68%'],
                            ['michael','Michael Lee','DevOps Engineer','CloudSphere','Austin','52%'],
                            ['emily','Emily Brown','Data Scientist','Insight Analytics','Chicago','89%'],
                            ['david','David Miller','React Developer','PixelCraft','Seattle','85%'],
                            ['sophia','Sophia Taylor','QA Engineer','SecureNet','Boston','71%']
                        ];
                    @endphp

                    @foreach($resources as $index => $resource)

                    <div class="resource-list-card {{ $index == 0 ? 'active-resource' : '' }}"
                         data-profile="{{ $resource[0] }}">

                        <div class="resource-list-top">

                            <div class="resource-mini-avatar">
                                <i class="ri-user-3-line"></i>
                            </div>

                            <div class="resource-mini-info">

                                <div class="resource-mini-name">
                                    {{ $resource[1] }}
                                </div>

                                <div class="resource-mini-role">
                                    {{ $resource[2] }}
                                </div>

                            </div>

                            <div class="match-badge">
                                {{ $resource[5] }}
                            </div>

                        </div>

                        <div class="resource-mini-meta">

                            <span>
                                <i class="ri-building-line"></i>
                                {{ $resource[3] }}
                            </span>

                            <span>
                                <i class="ri-map-pin-line"></i>
                                {{ $resource[4] }}
                            </span>

                        </div>

                    </div>

                    @endforeach

                </div>

            </div>

            <!-- RIGHT -->


<div class="resource-details-panel">

    <div class="resource-details-scroll">

        <!-- PROFILE HEADER -->

        <div class="profile-header-card">

            <div class="profile-top">

                <div class="profile-user-section">

                    <div class="profile-avatar">
                        <i class="ri-user-3-line"></i>
                    </div>

                    <div>

                        <div class="profile-name">
                            John Carter
                        </div>

                        <div class="profile-role">
                            Senior Laravel Developer
                        </div>

                        <div class="profile-company">
                            TechCorp Solutions
                        </div>

                    </div>

                </div>

                <div class="match-badge">
                    92%
                </div>

            </div>

            <div class="profile-actions mt-4">

                <button class="action-btn">
                    <i class="ri-send-plane-line"></i>
                    Send Request
                </button>

            </div>

        </div>

        <!-- PROFILE GRID -->

        <div class="profile-info-grid">

            <!-- INFORMATION -->

            <div class="profile-info-card">

                <div class="profile-info-title">
                    Information
                </div>

                <div class="profile-info-item">
                    <i class="ri-map-pin-line"></i>
                    New York, USA
                </div>

                <div class="profile-info-item">
                    <i class="ri-briefcase-line"></i>
                    5+ Years Experience
                </div>

            </div>

            <!-- TECHNICAL SKILLS -->

            <div class="profile-info-card">

                <div class="profile-info-title">
                    Technical Skills
                </div>

                <div class="skill-list">

                    <div class="skill-row">

                        <div class="skill-left">

                            <div class="skill-dot"></div>

                            <div class="skill-name">
                                Laravel
                            </div>

                        </div>

                        <div class="skill-level level-advanced">
                            Advanced
                        </div>

                    </div>

                    <div class="skill-row">

                        <div class="skill-left">

                            <div class="skill-dot"></div>

                            <div class="skill-name">
                                PHP
                            </div>

                        </div>

                        <div class="skill-level level-advanced">
                            Advanced
                        </div>

                    </div>

                    <div class="skill-row">

                        <div class="skill-left">

                            <div class="skill-dot"></div>

                            <div class="skill-name">
                                MySQL
                            </div>

                        </div>

                        <div class="skill-level level-intermediate">
                            Intermediate
                        </div>

                    </div>

                    <div class="skill-row">

                        <div class="skill-left">

                            <div class="skill-dot"></div>

                            <div class="skill-name">
                                REST API
                            </div>

                        </div>

                        <div class="skill-level level-advanced">
                            Advanced
                        </div>

                    </div>

                    <div class="skill-row">

                        <div class="skill-left">

                            <div class="skill-dot"></div>

                            <div class="skill-name">
                                Docker
                            </div>

                        </div>

                        <div class="skill-level level-beginner">
                            Beginner
                        </div>

                    </div>

                </div>

            </div>

            <!-- ABOUT -->

            <div class="profile-info-card full-width-card">

                <div class="profile-info-title">
                    About Candidate
                </div>

                <p class="candidate-about">
                    Experienced Laravel developer with strong backend architecture,
                    scalable enterprise application development experience,
                    REST API integration expertise, and modern DevOps workflow understanding.
                    Passionate about clean architecture, performance optimization,
                    and building maintainable SaaS products.
                </p>

            </div>

            <!-- RECENT PROJECTS -->

            <div class="profile-info-card full-width-card">

                <div class="profile-info-title">
                    Recent Projects
                </div>

                <div class="project-list">

                    <!-- PROJECT -->

                    <div class="project-item">

                        <div class="project-title">
                            Enterprise HR Management System
                        </div>

                        <div class="project-desc">
                            Developed a scalable HR and employee management platform
                            with payroll, attendance tracking, and resource allocation modules
                            for enterprise clients.
                        </div>

                        <div class="project-tech">

                            <span class="project-pill">
                                Laravel
                            </span>

                            <span class="project-pill">
                                MySQL
                            </span>

                            <span class="project-pill">
                                REST API
                            </span>

                            <span class="project-pill">
                                AWS
                            </span>

                        </div>

                    </div>

                    <!-- PROJECT -->

                    <div class="project-item">

                        <div class="project-title">
                            Multi Tenant LMS Platform
                        </div>

                        <div class="project-desc">
                            Built a multi-tenant learning management system with
                            organization-based resource sharing, HR modules,
                            employee skill tracking, and course analytics.
                        </div>

                        <div class="project-tech">

                            <span class="project-pill">
                                Laravel
                            </span>

                            <span class="project-pill">
                                Vue.js
                            </span>

                            <span class="project-pill">
                                Docker
                            </span>

                            <span class="project-pill">
                                Redis
                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

        </div>

    </div>

</div>

@endsection

@section('js')

<script src="/curiasity_stage/public/js/resource.js"></script>


@endsection