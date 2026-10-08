@extends(theme('layouts.dashboard_master'))

@section('title')
{{ Settings('site_title') ? Settings('site_title') : 'Infix LMS' }} |
{{ __('Corporate Resources') }}
@endsection

@section('css')

<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet" />

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
* {
    font-family: 'Inter', sans-serif;
}

body {
    background: #f5f7fb;
    color: #111827;
}

.resources-wrapper {
    padding: 24px;
    background: #f5f7fb;
    min-height: 100vh;
}

/* =====================================================
TOP BAR
===================================================== */

.top-action-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 24px;
    flex-wrap: wrap;
}

.page-title {
    font-size: 32px;
    font-weight: 800;
    line-height: 1.1;
    letter-spacing: -1px;
    color: #111827;
    margin-bottom: 8px;
}

.page-subtitle {
    font-size: 15px;
    color: #6b7280;
}

/* =====================================================
VIEW SWITCHER
===================================================== */

.view-switcher {
    display: flex;
    align-items: center;
    gap: 10px;
    background: #fff;
    border: 1px solid #e5e7eb;
    padding: 8px;
    border-radius: 20px;
}

.view-btn {
    width: 52px;
    height: 52px;
    border: none;
    border-radius: 14px;
    background: transparent;
    color: #64748b;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: .25s ease;
    cursor: pointer;
}

.view-btn i {
    font-size: 24px;
}

.view-btn:hover {
    background: #f3f4f6;
}

.view-btn.active {
    background: #17172d;
    color: #fff;
}

/* =====================================================
VIEWS
===================================================== */

.view-section {
    display: none;
}

.view-section.active-view {
    display: block;
}

/* =====================================================
TOP GLOBAL SEARCH
===================================================== */

.global-search-section {
    width: 80%;
    margin-left: 10%;
    margin-bottom: 28px;

}

.global-search-bar {
    background: #fff;
    border: 1px solid #d1d5db;

    border-radius: 30px;

    display: flex;
    align-items: center;

    padding: 10px;

    gap: 10px;

    box-shadow:
        0 6px 18px rgba(0, 0, 0, .05);
}

.global-search-group {
    flex: 1;

    display: flex;
    align-items: center;

    gap: 14px;

    padding: 0 20px;
}

.global-search-group i {
    font-size: 24px;
    color: #444;
}

.global-search-group input {
    width: 100%;
    height: 58px;

    border: none;
    outline: none;
    background: transparent;

    font-size: 17px;
    font-weight: 500;

    color: #111827;
}

.global-search-group input::placeholder {
    color: #6b7280;
}

.search-border {
    border-left: 1px solid #e5e7eb;
}

.global-search-btn {
    min-width: 170px;
    height: 58px;

    border: none;
    border-radius: 20px;

    background: #0d5bd7;
    color: #fff;

    font-size: 18px;
    font-weight: 700;

    transition: .25s ease;
}

.global-search-btn:hover {
    background: #0a4bb3;
}

.global-search-btn:disabled {
    background: #cbd5e1;
    cursor: not-allowed;
    opacity: .7;
}

/* =====================================================
FILTER CHIPS
===================================================== */

.global-filter-wrapper {
    display: flex;
    flex-wrap: wrap;

    gap: 12px;

    margin-top: 22px;
}

.global-filter-chip {
    height: 48px;

    padding: 0 22px;

    border-radius: 40px;

    background: #fff;

    border: 1px solid #e5e7eb;

    font-size: 15px;
    font-weight: 500;

    color: #111827;

    transition: .25s ease;
}

.global-filter-chip:hover {
    border-color: #111827;
}

.active-global-chip {
    border: 2px solid #111827;
    font-weight: 700;
}

/* =====================================================
FILTER DROPDOWN
===================================================== */

.filter-dropdown-wrapper {
    position: relative;
}

.filter-dropdown-btn {
    display: flex;
    align-items: center;
    gap: 10px;
}

.filter-dropdown-btn i {
    font-size: 18px;
}

/* =====================================================
DROPDOWN MENU
===================================================== */

.filter-dropdown-menu {
    position: absolute;

    top: 62px;
    left: 0;

    width: 340px;

    background: #fff;

    border: 1px solid #e5e7eb;

    border-radius: 24px;

    padding: 18px;

    box-shadow:
        0 12px 40px rgba(0, 0, 0, .12);

    z-index: 100;

    display: none;
}

.filter-dropdown-menu.show-dropdown {
    display: block;
}

/* =====================================================
SEARCH BOX
===================================================== */

.filter-search-box {
    margin-bottom: 16px;
}

.filter-search-box input {
    width: 100%;
    height: 48px;

    border: 1px solid #e5e7eb;
    border-radius: 14px;

    padding: 0 16px;

    font-size: 14px;

    outline: none;
}

/* =====================================================
OPTIONS
===================================================== */

.filter-options-scroll {
    max-height: 260px;
    overflow-y: auto;

    display: flex;
    flex-direction: column;
    gap: 14px;

    padding-right: 6px;
}

.filter-options-scroll::-webkit-scrollbar {
    width: 6px;
}

.filter-options-scroll::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 20px;
}

.filter-option-item {
    display: flex;
    align-items: center;
    gap: 14px;

    font-size: 16px;
    font-weight: 500;

    color: #374151;

    cursor: pointer;
}

.filter-option-item input {
    width: 22px;
    height: 22px;

    border-radius: 6px;
}

/* =====================================================
FOOTER
===================================================== */

.filter-footer {
    display: flex;
    justify-content: flex-end;
    gap: 12px;

    margin-top: 20px;
}

.clear-filter-btn {
    height: 46px;
    padding: 0 18px;

    border-radius: 14px;

    background: #fff;
    border: 1px solid #e5e7eb;

    font-weight: 600;
}

.apply-filter-btn {
    height: 46px;
    padding: 0 20px;

    border: none;
    border-radius: 14px;

    background: #0d5bd7;
    color: #fff;

    font-weight: 700;
}

/* =====================================================
COMPANY VIEW
===================================================== */

.resource-card {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 26px;
    padding: 28px;
    height: 100%;
    transition: .25s ease;
}

.resource-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, .05);
}

.card-top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 20px;
    margin-bottom: 24px;
}

.company-header {
    display: flex;
    gap: 16px;
}

.company-icon {
    width: 72px;
    height: 72px;
    border-radius: 22px;
    background: #eef2ff;
    display: flex;
    align-items: center;
    justify-content: center;
}

.company-icon i {
    font-size: 32px;
    color: #4f46e5;
}

.company-name {
    font-size: 20px;
    font-weight: 700;
    letter-spacing: -0.3px;
    color: #111827;
    margin-bottom: 6px;
}

.company-desc {
    font-size: 14px;
    line-height: 1.8;
    color: #64748b;
}

.resource-badge {
    background: #eef4ff;
    color: #2563ff;
    border-radius: 14px;
    padding: 10px 16px;
    font-size: 13px;
    font-weight: 700;
}

.info-list {
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 10px;
    color: #64748b;
    font-size: 14px;
}

.info-item i {
    font-size: 18px;
}

/* =====================================================
MASTER DETAIL LAYOUT
===================================================== */

.resource-layout {
    display: flex;
    gap: 20px;
    height: calc(100vh - 180px);
}

/* =====================================================
LEFT PANEL
===================================================== */

.resource-list-panel {
    width: 500px;
    min-width: 500px;
    max-width: 500px;
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 28px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
}

.resource-list-header {
    padding: 24px;
    border-bottom: 1px solid #eef2f7;
}

.resource-panel-title {
    font-size: 24px;
    font-weight: 800;
    color: #111827;
    margin-bottom: 6px;
}

.resource-panel-subtitle {
    font-size: 14px;
    color: #64748b;
}

.resource-list-scroll {
    flex: 1;
    overflow-y: auto;
    padding: 18px;
}

/* =====================================================
RESOURCE LIST CARD
===================================================== */

.resource-list-card {
    border: 1px solid #e5e7eb;
    border-radius: 24px;
    padding: 20px;
    margin-bottom: 18px;
    cursor: pointer;
    transition: .25s ease;
    background: #fff;
}

.resource-list-card:hover {
    border-color: #6366f1;
    transform: translateY(-2px);
}

.active-resource {
    border: 2px solid #4f46e5;
    background: #f5f7ff;
}

.resource-list-top {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 14px;
}

.resource-mini-avatar {
    width: 62px;
    height: 62px;
    border-radius: 18px;
    background: #eef2ff;
    display: flex;
    align-items: center;
    justify-content: center;
}

.resource-mini-avatar i {
    font-size: 28px;
    color: #4f46e5;
}

.resource-mini-info {
    flex: 1;
}

.resource-mini-name {
    font-size: 18px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 6px;
}

.resource-mini-role {
    font-size: 15px;
    color: #64748b;
}

.resource-mini-meta {
    margin-top: 16px;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.resource-mini-meta span {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    color: #64748b;
}

/* =====================================================
MATCH BADGE
===================================================== */

.match-badge {
    min-width: 86px;
    height: 42px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #645edf;
    color: #fff;
    border-radius: 14px;
    font-size: 14px;
    font-weight: 800;
}

/* =====================================================
RIGHT PANEL
===================================================== */

.resource-details-panel {
    flex: 1;
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 28px;
    overflow: hidden;
}

.resource-details-scroll {
    height: 100%;
    overflow-y: auto;
    padding: 28px;
}

/* =====================================================
PROFILE HEADER
===================================================== */

.profile-header-card {
    border: 1px solid #e5e7eb;
    border-radius: 26px;
    padding: 28px;
    margin-bottom: 24px;
}

.profile-top {
    display: flex;
    justify-content: space-between;
    gap: 20px;
}

.profile-user-section {
    display: flex;
    gap: 20px;
}

.profile-avatar {
    width: 92px;
    height: 92px;
    border-radius: 24px;
    background: #eef2ff;
    display: flex;
    align-items: center;
    justify-content: center;
}

.profile-avatar i {
    font-size: 44px;
    color: #4f46e5;
}

.profile-name {
    font-size: 30px;
    font-weight: 800;
    line-height: 1.1;
    letter-spacing: -0.8px;
    color: #111827;
}

.profile-role {
    font-size: 17px;
    color: #4f46e5;
    margin-top: 4px;
}

.profile-company {
    font-size: 15px;
    color: #64748b;
    margin-top: 6px;
}

.profile-actions {
    margin-top: 24px;
}

.action-btn {
    height: 50px;
    padding: 0 22px;
    border-radius: 14px;
    background: #3DC13C;
    color: #fff !important;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    text-decoration: none !important;
    font-size: 14px;
    font-weight: 700;
    border: none;
}

/* =====================================================
PROFILE GRID
===================================================== */

.profile-info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 24px;
}

.profile-info-card {
    border: 1px solid #e5e7eb;
    border-radius: 24px;
    padding: 24px;
    background: #fff;
}

.full-width-card {
    grid-column: 1 / -1;
}

.profile-info-title {
    font-size: 18px;
    font-weight: 700;
    margin-bottom: 20px;
    color: #111827;
}

.profile-info-item {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 16px;
    color: #64748b;
    font-size: 15px;
}

/* =====================================================
SKILL LIST
===================================================== */

.skill-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.skill-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
}

.skill-left {
    display: flex;
    align-items: center;
    gap: 12px;
}

.skill-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: #645edf;
}

.skill-name {
    font-size: 15px;
    font-weight: 600;
    color: #111827;
}

.skill-level {
    padding: 6px 12px;
    border-radius: 30px;
    font-size: 12px;
    font-weight: 700;
}

.level-advanced {
    background: #dcfce7;
    color: #15803d;
}

.level-intermediate {
    background: #fef3c7;
    color: #b45309;
}

.level-beginner {
    background: #fee2e2;
    color: #b91c1c;
}

/* =====================================================
PROJECTS
===================================================== */

.project-list {
    display: flex;
    flex-direction: column;
    gap: 18px;
}

.project-item {
    border: 1px solid #e5e7eb;
    border-radius: 18px;
    padding: 18px;
    transition: .25s ease;
}

.project-item:hover {
    border-color: #645edf;
    background: #fafaff;
}

.project-title {
    font-size: 16px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 8px;
}

.project-desc {
    font-size: 14px;
    line-height: 1.8;
    color: #64748b;
    margin-bottom: 14px;
}

.project-tech {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.project-pill {
    background: #eef2ff;
    color: #4f46e5;
    padding: 8px 14px;
    border-radius: 30px;
    font-size: 12px;
    font-weight: 700;
}

.candidate-about {
    color: #64748b;
    line-height: 1.9;
    font-size: 15px;
}

/* =====================================================
SCROLLBAR
===================================================== */

.resource-list-scroll::-webkit-scrollbar,
.resource-details-scroll::-webkit-scrollbar {
    width: 8px;
}

.resource-list-scroll::-webkit-scrollbar-thumb,
.resource-details-scroll::-webkit-scrollbar-thumb {
    background: #d1d5db;
    border-radius: 20px;
}

/* =====================================================
RESPONSIVE
===================================================== */

@media(max-width:1200px) {

    .resource-layout {
        flex-direction: column;
        height: auto;
    }

    .resource-list-panel {
        width: 100%;
        min-width: 100%;
        max-width: 100%;
        height: 500px;
    }

    .profile-info-grid {
        grid-template-columns: 1fr;
    }

}


@media(max-width:992px) {

    .global-search-bar {
        flex-direction: column;
        align-items: stretch;
    }

    .search-border {
        border-left: none;
        border-top: 1px solid #e5e7eb;
    }

    .global-search-btn {
        width: 100%;
    }

}

@media(max-width:768px) {

    .profile-top {
        flex-direction: column;
    }

    .profile-user-section {
        flex-direction: column;
    }

    .resources-wrapper {
        padding: 16px;
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

            <button type="button" class="view-btn active" data-view="company">

                <i class="ri-building-4-line"></i>

            </button>

            <button type="button" class="view-btn" data-view="resource">

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

                <input type="text" id="globalKeyword" placeholder="keyword, skill or company">

            </div>

            <!-- LOCATION -->

            <div class="global-search-group search-border">

                <i class="ri-map-pin-line"></i>

                <input type="text" id="globalLocation" placeholder="Location">

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

                        <input type="text" placeholder="Search business unit">

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

                        <input type="text" placeholder="Search profile">

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
        <div id="paginationLinks" class="mt-4 text-center"></div>
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

                <div class="resource-list-scroll" id="resourceCardList">

                </div>
                <div id="resourcePaginationLinks" class="p-3 text-center"></div>

            </div>

            <!-- RIGHT -->


            <div class="resource-details-panel" id="resourceDetailsPanel">

                <div class="text-center py-5">
                    <i class="ri-user-search-line" style="font-size:60px"></i>

                    <h4 class="mt-3">
                        Select Resource
                    </h4>

                    <p>
                        Choose a resource from the left panel to view details.
                    </p>
                </div>

            </div>

        </div>

    </div>

</div>

@endsection

@section('js')

<script>
$(document).ready(function() {

    $('.view-btn').click(function() {

        $('.view-btn').removeClass('active');

        $(this).addClass('active');

        let view = $(this).data('view');

        $('.view-section').removeClass('active-view');

        if (view === 'company') {
            $('#companyView').addClass('active-view');
        }

        if (view === 'resource') {
            $('#resourceView').addClass('active-view');
        }

    });

});
</script>

<script>
$(document).ready(function() {

    // =====================================================
    // VIEW SWITCHER
    // =====================================================

    $('.view-btn').click(function() {

        $('.view-btn').removeClass('active');

        $(this).addClass('active');

        let view = $(this).data('view');

        $('.view-section').removeClass('active-view');

        if (view === 'company') {

            $('#companyView').addClass('active-view');

        }

        if (view === 'resource') {

            $('#resourceView').addClass('active-view');

        }

    });

    // =====================================================
    // DEFAULT VIEW
    // =====================================================

    $('#companyView').addClass('active-view');

    // =====================================================
    // AUTO SWITCH TO RESOURCE VIEW
    // =====================================================

    function activateResourceView() {

        $('.view-btn').removeClass('active');

        $('.view-btn[data-view="resource"]')
            .addClass('active');

        $('.view-section').removeClass('active-view');

        $('#resourceView').addClass('active-view');

    }

    // =====================================================
    // GLOBAL SEARCH
    // =====================================================

    function filterResources() {

        let keyword = $('#globalKeyword')
            .val()
            .toLowerCase();

        let location = $('#globalLocation')
            .val()
            .toLowerCase();

        $('.resource-list-card').each(function() {

            let name = $(this)
                .find('.resource-mini-name')
                .text()
                .toLowerCase();

            let role = $(this)
                .find('.resource-mini-role')
                .text()
                .toLowerCase();

            let company = $(this)
                .find('.resource-mini-meta span')
                .eq(0)
                .text()
                .toLowerCase();

            let city = $(this)
                .find('.resource-mini-meta span')
                .eq(1)
                .text()
                .toLowerCase();

            let keywordMatch =
                name.includes(keyword) ||
                role.includes(keyword) ||
                company.includes(keyword);

            let locationMatch =
                city.includes(location);

            if (keywordMatch && locationMatch) {

                $(this).show();

            } else {

                $(this).hide();

            }

        });

    }

    // =====================================================
    // SEARCH BUTTON CLICK
    // =====================================================

    $(document).ready(function() {

        function toggleSearchButton() {

            let keyword = $('#globalKeyword').val().trim();
            let location = $('#globalLocation').val().trim();

            $('.global-search-btn').prop(
                'disabled',
                keyword === '' && location === ''
            );
        }

        // Initial state
        toggleSearchButton();

        // Check while typing
        $('#globalKeyword, #globalLocation').on('input', function() {
            toggleSearchButton();
        });

    });

    $('.global-search-btn').click(function() {

        $('.global-filter-wrapper').removeClass('d-none');

        activateResourceView();

        filterResources();

    });

    // =====================================================
    // FILTER CHIPS
    // =====================================================

    $('.global-filter-chip').click(function() {

        $('.global-filter-chip')
            .removeClass('active-global-chip');

        $(this)
            .addClass('active-global-chip');

        activateResourceView();

        let filter = $(this)
            .data('filter')
            .toLowerCase();

        if (filter === 'all') {

            $('.resource-list-card').show();

            return;

        }

        $('.resource-list-card').each(function() {

            let role = $(this)
                .find('.resource-mini-role')
                .text()
                .toLowerCase();

            let company = $(this)
                .find('.resource-mini-meta span')
                .eq(0)
                .text()
                .toLowerCase();

            if (
                role.includes(filter) ||
                company.includes(filter)
            ) {

                $(this).show();

            } else {

                $(this).hide();

            }

        });

    });

    // =====================================================
    // SORT BY PERCENTAGE
    // =====================================================

    function sortResourcesByPercentage() {

        let container = $('.resource-list-scroll');

        let cards = $('.resource-list-card').get();

        cards.sort(function(a, b) {

            let aValue = parseInt(
                $(a).find('.match-badge').text()
            );

            let bValue = parseInt(
                $(b).find('.match-badge').text()
            );

            return bValue - aValue;

        });

        $.each(cards, function(index, card) {

            container.append(card);

        });

    }

    sortResourcesByPercentage();

});
</script>

<script>
$(document).ready(function() {

    // =====================================================
    // FILTER DROPDOWN TOGGLE
    // =====================================================

    $('.filter-dropdown-btn').click(function(e) {

        e.stopPropagation();

        let parent = $(this)
            .closest('.filter-dropdown-wrapper');

        $('.filter-dropdown-menu')
            .not(parent.find('.filter-dropdown-menu'))
            .removeClass('show-dropdown');

        parent.find('.filter-dropdown-menu')
            .toggleClass('show-dropdown');

    });

    // =====================================================
    // CLOSE DROPDOWN
    // =====================================================

    $(document).click(function() {

        $('.filter-dropdown-menu')
            .removeClass('show-dropdown');

    });

    $('.filter-dropdown-menu').click(function(e) {

        e.stopPropagation();

    });

    // =====================================================
    // SEARCH INSIDE FILTER
    // =====================================================

    $('.filter-search-box input').on('keyup', function() {

        let value = $(this)
            .val()
            .toLowerCase();

        $(this)
            .closest('.filter-dropdown-menu')
            .find('.filter-option-item')
            .filter(function() {

                $(this).toggle(
                    $(this)
                    .text()
                    .toLowerCase()
                    .indexOf(value) > -1
                );

            });

    });

});
</script>

<script>
$(document).ready(function() {

    // =====================================================
    // SORT RESOURCE CARDS BY PERCENTAGE
    // =====================================================

    function sortResourcesByPercentage() {

        let container = $('.resource-list-scroll');

        let cards = $('.resource-list-card').get();

        cards.sort(function(a, b) {

            let aValue = parseInt(
                $(a).find('.match-badge').text()
            );

            let bValue = parseInt(
                $(b).find('.match-badge').text()
            );

            return bValue - aValue;

        });

        $.each(cards, function(index, card) {

            container.append(card);

        });

    }

    // CALL SORT
    sortResourcesByPercentage();

});
</script>

<script>
$(document).ready(function() {

    loadTenantCards();
    // =====================================================
    // VIEW SWITCHER
    // =====================================================

    $('.view-btn').click(function() {

        $('.view-btn').removeClass('active');

        $(this).addClass('active');

        let view = $(this).data('view');

        $('.view-section').removeClass('active-view');

        if (view === 'company') {

            $('#companyView').addClass('active-view');

        }

        if (view === 'resource') {

            $('#resourceView').addClass('active-view');

        }

    });

    // =====================================================
    // SORT RESOURCE CARDS BY PERCENTAGE
    // =====================================================

    function sortResourcesByPercentage() {

        let container = $('.resource-list-scroll');

        let cards = $('.resource-list-card').get();

        cards.sort(function(a, b) {

            let aValue = parseInt(
                $(a).find('.match-badge').text()
            );

            let bValue = parseInt(
                $(b).find('.match-badge').text()
            );

            return bValue - aValue;

        });

        $.each(cards, function(index, card) {

            container.append(card);

        });

    }

    sortResourcesByPercentage();

    // =====================================================
    // DYNAMIC PROFILE DATA
    // =====================================================

    
    // =====================================================
    // DYNAMIC DETAIL PANEL
    // =====================================================

    $('.resource-list-card').click(function() {

       console.log('tests');

        $('.resource-list-card').removeClass('active-resource');

        $(this).addClass('active-resource');

        let profileKey = $(this).data('profile');

        let profile = profiles[profileKey];

        // =====================================================
        // BASIC INFO
        // =====================================================

        $('.profile-name').text(profile.name);

        $('.profile-role').text(profile.role);

        $('.profile-company').text(profile.company);

        $('.profile-header-card .match-badge')
            .text(profile.percentage);

        // =====================================================
        // INFORMATION SECTION
        // =====================================================

        let infoHtml = `

            <div class="profile-info-title">
                Information
            </div>

            <div class="profile-info-item">
                <i class="ri-map-pin-line"></i>
                ${profile.location}
            </div>

            <div class="profile-info-item">
                <i class="ri-briefcase-line"></i>
                ${profile.experience}
            </div>


        `;

        $('.profile-info-card').eq(0).html(infoHtml);

        // =====================================================
        // SKILLS SECTION
        // =====================================================

        let skillHtml = `

            <div class="profile-info-title">
                Technical Skills
            </div>

            <div class="skill-list">

        `;

        profile.skills.forEach(function(skill) {

            let levelClass = '';

            if (skill[1] === 'Advanced') {
                levelClass = 'level-advanced';
            }

            if (skill[1] === 'Intermediate') {
                levelClass = 'level-intermediate';
            }

            if (skill[1] === 'Beginner') {
                levelClass = 'level-beginner';
            }

            skillHtml += `

                <div class="skill-row">

                    <div class="skill-left">

                        <div class="skill-dot"></div>

                        <div class="skill-name">
                            ${skill[0]}
                        </div>

                    </div>

                    <div class="skill-level ${levelClass}">
                        ${skill[1]}
                    </div>

                </div>

            `;

        });

        skillHtml += `</div>`;

        $('.profile-info-card').eq(1).html(skillHtml);

        // =====================================================
        // ABOUT SECTION
        // =====================================================

        let aboutHtml = `

            <div class="profile-info-title">
                About Candidate
            </div>

            <p class="candidate-about">
                ${profile.about}
            </p>

        `;

        $('.profile-info-card').eq(2).html(aboutHtml);

        // =====================================================
        // PROJECT SECTION
        // =====================================================

        let projectHtml = `

            <div class="profile-info-title">
                Recent Projects
            </div>

            <div class="project-list">

        `;

        profile.projects.forEach(function(project) {

            let techHtml = '';

            project.tech.forEach(function(tech) {

                techHtml += `

                    <span class="project-pill">
                        ${tech}
                    </span>

                `;

            });

            projectHtml += `

                <div class="project-item">

                    <div class="project-title">
                        ${project.title}
                    </div>

                    <div class="project-desc">
                        ${project.desc}
                    </div>

                    <div class="project-tech">
                        ${techHtml}
                    </div>

                </div>

            `;

        });

        projectHtml += `</div>`;

        $('.profile-info-card').eq(3).html(projectHtml);

    });

    /* =====================================================
        LOAD TENANT CARDS
    ===================================================== */

    function loadTenantCards(page = 1) {
        $('#cardLoader').show();

        $('#tenantCompanyList').hide();
        $('#resourceTableBody').hide();

        $('#paginationLinks').hide();
        $('#tablePaginationLinks').hide();
        $('#resourcePaginationLinks').hide();

        $.ajax({

            url: "{{ route('corporate.resources.datatable', ['tenant_slug' => request()->route('tenant_slug')]) }}",

            type: "GET",

            data: {

                page: page,

                keyword: $('#keyword').val(),

                business_unit: $('#business_unit').val(),

                primary_skills: $('#primarySkills').val(),

                secondary_skills: $('#secondarySkills').val(),

                experience: $('#experienceRange').val(),

                location: $('#location').val(),

            },

            success: function(response) {
                // =========================================
                // CLEAR OLD DATA
                // =========================================

                $('#tenantCompanyList').html('');
                $('#resourceTableBody').html('');
                $('#resourceCardList').html('');

                let tenants = response.data;

                // =========================================
                // DATA EXISTS
                // =========================================

                if (tenants.length > 0) {
                    $.each(tenants, function(index, tenant) {
                        // =========================================
                        // SERIAL NUMBER
                        // =========================================

                        let serialNumber =
                            ((response.current_page - 1) * response.per_page) +
                            (index + 1);

                        // =========================================
                        // COMPANY CARD VIEW
                        // =========================================

                        let card = `

                            <div class="col-lg-6">

                                <div class="resource-card">

                                    <div class="card-top">

                                        <div class="company-header">

                                            <div class="company-icon">
                                                <i class="ri-building-line"></i>
                                            </div>

                                            <div>

                                                <div class="company-name">
                                                    ${tenant.tenant_name ?? ''}
                                                </div>

                                                <div class="company-desc">
                                                    ${tenant.tenant_email ?? ''}
                                                </div>

                                            </div>

                                        </div>

                                        <div class="resource-badge">
                                            ${tenant.waiting_users_count ?? 0} Resources
                                        </div>

                                    </div>

                                    <div class="mt-4">

                                        <a href="${tenant.view_url ?? '#'}"
                                        class="action-btn">

                                            <i class="ri-eye-line"></i>

                                            View Details

                                        </a>

                                    </div>

                                </div>

                            </div>

                        `;

                        $('#tenantCompanyList').append(card);

                        // =========================================
                        // TABLE VIEW
                        // =========================================

                        let tableRow = `

                            <tr>

                                <td>${serialNumber}</td>

                                <td>${tenant.tenant_name ?? '-'}</td>

                                <td>${tenant.tenant_email ?? '-'}</td>

                                <td class="text-center">

                                    <a href="${tenant.view_url ?? '#'}"
                                    class="action-btn">

                                        <i class="ri-eye-line"></i>

                                        View

                                    </a>

                                </td>

                            </tr>

                        `;

                        $('#resourceTableBody').append(tableRow);

                        // =========================================
                        // RESOURCE VIEW
                        // =========================================
                        

                        window.profiles = {};

                            $.each(response.data, function(index, tenant) {

                                if (tenant.waiting_users && tenant.waiting_users.length > 0) {

                                    $.each(tenant.waiting_users, function(userIndex, user) {

                                        window.profiles[user.id] = {
                                            name: user.name,
                                            role: user.designation,
                                            company: tenant.tenant_name,
                                            percentage: user.matchPercentage + '%',
                                            location: user.location_code,
                                            experience: user.experience ?? '-',
                                            skills: user.skills ?? [],
                                            about: user.about ?? '-',
                                            projects: user.projects ?? []
                                        };

                                    });

                                }

                            });

                    });

                    // =========================================
                    // PAGINATION
                    // =========================================

                    let paginationHtml = '';

                    // PREVIOUS BUTTON

                    if (response.current_page > 1) {
                        paginationHtml += `

                            <button class="pagination-btn btn-light"
                                    data-page="${response.current_page - 1}">

                                Prev

                            </button>

                        `;
                    }

                    // PAGE NUMBERS

                    for (let i = 1; i <= response.last_page; i++) {
                        paginationHtml += `

                            <button class="pagination-btn
                                ${i == response.current_page ? 'btn-primary' : 'btn-light'}"
                                data-page="${i}">

                                ${i}

                            </button>

                        `;
                    }

                    // NEXT BUTTON

                    if (response.current_page < response.last_page) {
                        paginationHtml += `

                            <button class="pagination-btn btn-light"
                                    data-page="${response.current_page + 1}">

                                Next

                            </button>

                        `;
                    }

                    // =========================================
                    // APPEND PAGINATION
                    // =========================================

                    $('#paginationLinks').html(paginationHtml);

                    $('#tablePaginationLinks').html(paginationHtml);

                    $('#resourcePaginationLinks').html(paginationHtml);
                } else {
                    $('#tenantCompanyList').html(`

                        <div class="col-12">

                            <div class="text-center py-5">

                                <h5>No Data Found</h5>

                            </div>

                        </div>

                    `);

                    $('#resourceTableBody').html(`

                        <tr>

                            <td colspan="4" class="text-center py-5">

                                No Data Found

                            </td>

                        </tr>

                    `);

                    $('#resourceCardList').html(`

                        <div class="col-12">

                            <div class="text-center py-5">

                                <h5>No Resources Found</h5>

                            </div>

                        </div>

                    `);

                    $('#paginationLinks').html('');
                    $('#tablePaginationLinks').html('');
                    $('#resourcePaginationLinks').html('');
                }
            },

            error: function(xhr) {
                console.log(xhr.responseText);
            },

            complete: function() {
                $('#cardLoader').hide();

                $('#tenantCompanyList').show();
                $('#resourceTableBody').show();

                $('#paginationLinks').show();
                $('#tablePaginationLinks').show();
                $('#resourcePaginationLinks').show();
            }

        });
    }

    // Auto select first resource
// setTimeout(function () {

//     let firstCard = $('#resourceCardList .resource-detail-btn').first();

//     if (firstCard.length) {

//         firstCard.addClass('active-resource');

//         firstCard.trigger('click');

//     }

// }, 100);

    $(document).on('click', '.resource-list-card', function() {
        console.log('clicked');
        let profileKey = $(this).data('profile');
        console.log(profileKey);
        let user = $(this).data('user');
        let company = $(this).data('company');

        console.log(user);
        console.log(company);

        let skillsHtml = '';

        // if (user.skills) {
        //     user.skills.forEach(skill => {

        //         skillsHtml += `
        //         <div class="skill-row">
        //             <div class="skill-left">
        //                 <div class="skill-dot"></div>
        //                 <div class="skill-name">
        //                     ${skill.name}
        //                 </div>
        //             </div>

        //             <div class="skill-level level-advanced">
        //                 ${skill.level ?? 'N/A'}
        //             </div>
        //         </div>
        //     `;
        //     });
        // }

        // $('#resourceDetailsPanel').html(`

        //     <div class="profile-header-card">

        //         <div class="profile-top">

        //             <div class="profile-user-section">

        //                 <div class="profile-avatar">
        //                     <i class="ri-user-3-line"></i>
        //                 </div>

        //                 <div>

        //                     <div class="profile-name">
        //                         ${user.name ?? '-'}
        //                     </div>

        //                     <div class="profile-role">
        //                         ${user.designation ?? '-'}
        //                     </div>

        //                     <div class="profile-company">
        //                         ${company}
        //                     </div>

        //                 </div>

        //             </div>

        //             <div class="match-badge">
        //                 ${user.match_percentage ?? 0}%
        //             </div>

        //         </div>

        //     </div>

        //     <div class="profile-info-grid">

        //         <div class="profile-info-card">

        //             <div class="profile-info-title">
        //                 Information
        //             </div>

        //             <div class="profile-info-item">
        //                 <i class="ri-map-pin-line"></i>
        //                 ${user.location_code ?? '-'}
        //             </div>

        //             <div class="profile-info-item">
        //                 <i class="ri-mail-line"></i>
        //                 ${user.email ?? '-'}
        //             </div>

        //         </div>

        //         <div class="profile-info-card">

        //             <div class="profile-info-title">
        //                 Skills
        //             </div>

        //             <div class="skill-list">
        //                 ${skillsHtml}
        //             </div>

        //         </div>

        //     </div>

        // `);
    });


    // $(document).on('click', '.resource-list-card', function() {

    //     let profileKey = $(this).data('profile');

    //     console.log(profileKey);

    //     let profile = window.profiles[profileKey];

    //     console.log(profile);

    // });

  
});
</script>

@endsection