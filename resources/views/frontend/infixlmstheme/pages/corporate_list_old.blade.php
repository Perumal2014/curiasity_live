@extends(theme('layouts.dashboard_master'))

@section('title')
{{ Settings('site_title') ? Settings('site_title') : 'Infix LMS' }} |
{{ __('Corporate Resources') }}
@endsection

@section('css')

<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
.resources-wrapper {
    padding: 30px;
    background: #f5f7fb;
    min-height: 100vh;
}

/* =====================================================
        PAGE TITLE
    ===================================================== */

.page-title {
    font-size: 32px;
    font-weight: 800;
    color: #111827;
    margin-bottom: 6px;
}

.page-subtitle {
    font-size: 15px;
    color: #6b7280;
    margin-bottom: 28px;
}

/* =====================================================
        FILTER BUTTON
    ===================================================== */

.filter-btn {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: #17172d;
    color: #fff;
    border: none;
    border-radius: 16px;
    padding: 14px 28px;
    font-size: 16px;
    font-weight: 700;
    margin-bottom: 24px;
    transition: .3s;
}

.filter-btn:hover {
    background: #0f1023;
}

.filter-btn i {
    font-size: 18px;
}

/* =====================================================
        FILTER BOX
    ===================================================== */

.filter-box {
    background: #fff;
    border: 1px solid #e3e5ea;
    border-radius: 26px;
    padding: 28px;
    margin-bottom: 30px;
    display: none;
}

.filter-title {
    font-size: 14px;
    font-weight: 700;
    margin-bottom: 10px;
    color: #111827;
}

.search-input {
    width: 100%;
    height: 52px;
    border: none;
    background: #f3f4f7;
    border-radius: 14px;
    padding: 0 16px;
    font-size: 14px;
    color: #555;
    outline: none;
}

.search-input::placeholder {
    color: #8b93a7;
}

/* =====================================================
        SELECT2 GLOBAL
    ===================================================== */

.searchable-select {
    width: 100%;
}

.select2-container {
    width: 100% !important;
    z-index: 9999 !important;
}

/* =====================================================
        SINGLE SELECT
    ===================================================== */

.select2-container--default .select2-selection--single {

    height: 52px !important;

    border: 1px solid #dfe3eb !important;
    border-radius: 14px !important;

    background: #fff !important;

    display: flex !important;
    align-items: center !important;
    justify-content: flex-start !important;

    padding: 0 14px !important;

    transition: .25s ease;
}

.select2-container--default.select2-container--focus .select2-selection--single {

    border-color: #7c3aed !important;
    box-shadow: 0 0 0 4px rgba(124, 58, 237, .08);
}

.select2-container--default .select2-selection--single .select2-selection__rendered {

    width: 100% !important;

    display: flex !important;
    align-items: center !important;
    justify-content: flex-start !important;

    text-align: left !important;

    padding-left: 0 !important;
    padding-right: 30px !important;

    margin: 0 !important;

    line-height: normal !important;

    font-size: 14px;
    color: #111827 !important;
}

.select2-container--default .select2-selection--single .select2-selection__placeholder {

    color: #6b7280 !important;
}

.select2-container--default .select2-selection--single .select2-selection__arrow {

    height: 52px !important;
    width: 30px !important;

    right: 10px !important;
    top: 0 !important;
}

.select2-container--default .select2-selection--single .select2-selection__arrow b {

    display: none !important;
}

.select2-container--default .select2-selection--single .select2-selection__arrow::after {

    content: "\ea4e";
    font-family: 'remixicon';

    position: absolute;

    top: 50%;
    left: 50%;

    transform: translate(-50%, -50%);

    font-size: 18px;
    color: #64748b;
}

/* =====================================================
        MULTI SELECT
    ===================================================== */

.select2-container--default .select2-selection--multiple {

    width: 100% !important;

    min-height: 52px !important;
    height: auto !important;

    border: 1px solid #dfe3eb !important;
    border-radius: 14px !important;

    background: #fff !important;

    padding: 8px 45px 8px 14px !important;

    position: relative;

    overflow: hidden !important;

    transition: .25s ease;
}

/* =====================================================
        PLACEHOLDER USING BEFORE
    ===================================================== */

.select2-container--default .select2-selection--multiple::before {

    content: "Select";

    position: absolute;

    left: 14px;
    top: 50%;

    transform: translateY(-50%);

    font-size: 14px;
    font-weight: 400;

    color: #9ca3af;

    pointer-events: none;

    transition: .2s ease;
}

/* HIDE PLACEHOLDER WHEN VALUE EXISTS */

.select2-container--default .select2-selection--multiple:has(.select2-selection__choice)::before {

    display: none;
}

/* =====================================================
        FOCUS
    ===================================================== */

.select2-container--default.select2-container--focus .select2-selection--multiple {

    border-color: #7c3aed !important;
    box-shadow: 0 0 0 4px rgba(124, 58, 237, .08);
}

/* =====================================================
        RENDER AREA
    ===================================================== */

.select2-container--default .select2-selection--multiple .select2-selection__rendered {

    display: flex !important;

    align-items: center !important;

    flex-wrap: wrap !important;

    gap: 8px !important;

    width: 100% !important;

    min-height: 34px !important;

    padding: 0 !important;
    margin: 0 !important;

    list-style: none !important;
}

/* =====================================================
        INLINE SEARCH
    ===================================================== */

.select2-container--default .select2-search--inline {

    display: flex !important;

    align-items: center !important;

    flex: 1 0 120px !important;

    margin: 0 !important;
    padding: 0 !important;
}

/* =====================================================
        INPUT FIELD
    ===================================================== */

.select2-container--default .select2-search--inline .select2-search__field {

    width: 100% !important;

    min-width: 120px !important;

    height: 34px !important;

    margin: 0 !important;
    padding: 0 !important;

    border: none !important;
    outline: none !important;

    background: transparent !important;

    font-size: 14px !important;

    color: #111827 !important;

    text-align: left !important;

    box-shadow: none !important;
}

/* HIDE DEFAULT PLACEHOLDER */

.select2-container--default .select2-search--inline .select2-search__field::placeholder {

    color: transparent !important;
}

/* =====================================================
        TAGS
    ===================================================== */

.select2-container--default .select2-selection--multiple .select2-selection__choice {

    background: #eef2ff !important;
    border: 1px solid #dbe4ff !important;

    color: #3730a3 !important;

    border-radius: 10px !important;

    padding: 6px 12px 6px 26px !important;

    font-size: 13px !important;
    font-weight: 600 !important;

    margin: 0 !important;

    position: relative;

    height: 34px !important;

    line-height: 20px !important;
}

/* =====================================================
        REMOVE BUTTON
    ===================================================== */

.select2-container--default .select2-selection--multiple .select2-selection__choice__remove {

    position: absolute !important;

    left: 10px !important;
    top: 50% !important;

    transform: translateY(-50%) !important;

    border: none !important;
    background: transparent !important;

    color: #6366f1 !important;

    font-size: 14px !important;
    font-weight: 700 !important;

    padding: 0 !important;
    margin: 0 !important;
}

.select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {

    color: #ef4444 !important;
    background: transparent !important;
}

/* =====================================================
        HIDE CLEAR BUTTON
    ===================================================== */

.select2-selection__clear {
    display: none !important;
}

/* =====================================================
        CUSTOM ARROW USING AFTER
    ===================================================== */

.select2-container--default .select2-selection--multiple::after {

    content: "\ea4e";

    font-family: 'remixicon';

    position: absolute;

    right: 16px;
    top: 50%;

    transform: translateY(-50%);

    font-size: 18px;

    color: #64748b;

    pointer-events: none;
}

/* =====================================================
        DROPDOWN
    ===================================================== */

.select2-dropdown {

    z-index: 99999 !important;

    border: 1px solid #dfe3eb !important;
    border-radius: 14px !important;

    overflow: hidden;

    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
}

/* =====================================================
        SEARCH AREA
    ===================================================== */

.select2-search--dropdown {
    padding: 10px;
    background: #fff;
}

.select2-search--dropdown .select2-search__field {

    height: 42px !important;

    border: 1px solid #dfe3eb !important;
    border-radius: 10px !important;

    padding: 0 12px !important;

    font-size: 14px !important;

    outline: none !important;

    text-align: left !important;
}

/* =====================================================
        OPTIONS
    ===================================================== */

.select2-results {
    max-height: 220px;
    overflow-y: auto;
}

.select2-results__option {

    padding: 12px 14px !important;
    font-size: 14px;

    text-align: left !important;

    transition: .2s ease;
}

/* =====================================================
        ACTIVE OPTION
    ===================================================== */

.select2-results__option--highlighted.select2-results__option--selectable {

    background: linear-gradient(90deg,
            #6d28d9,
            #c026d3) !important;

    color: #fff !important;
}

/* =====================================================
        SELECTED OPTION
    ===================================================== */

.select2-results__option--selected {

    background: #f3f4f6 !important;
    color: #111827 !important;
}

/* =====================================================
        EXPERIENCE RANGE
    ===================================================== */

.range-wrapper {
    width: 100%;
    background: #fff;
    border: 1px solid #dfe3eb;
    border-radius: 18px;
    padding: 22px;
}

.range-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
}

.range-label {
    font-size: 14px;
    font-weight: 700;
    color: #111827;
}

.range-count {
    background: #eef4ff;
    color: #2563ff;
    padding: 7px 16px;
    border-radius: 40px;
    font-size: 13px;
    font-weight: 700;
}

.custom-range {
    -webkit-appearance: none;
    appearance: none;

    width: 100%;
    height: 8px;

    border-radius: 30px;
    outline: none;

    background: linear-gradient(to right,
            #2563ff 0%,
            #2563ff 0%,
            #dbe4f0 0%,
            #dbe4f0 100%);

    transition: background .2s ease;
}

.custom-range::-webkit-slider-runnable-track {
    height: 8px;
    border-radius: 30px;
}

.custom-range::-webkit-slider-thumb {
    -webkit-appearance: none;
    appearance: none;

    width: 24px;
    height: 24px;

    border-radius: 50%;
    background: #2563ff;

    border: 4px solid #fff;

    box-shadow: 0 4px 14px rgba(37, 99, 255, 0.35);

    margin-top: -8px;

    cursor: pointer;

    transition: .2s ease;
}

.custom-range::-webkit-slider-thumb:hover {
    transform: scale(1.08);
}

.custom-range::-moz-range-thumb {
    width: 24px;
    height: 24px;
    border: none;
    border-radius: 50%;
    background: #2563ff;
    cursor: pointer;
}

.custom-range::-moz-range-track {
    height: 8px;
    border-radius: 30px;
    background: #dbe4f0;
}

/* =====================================================
        BUTTONS
    ===================================================== */

.filter-actions {
    margin-top: 24px;
    display: flex;
    gap: 14px;
}

.apply-btn {
    background: #020028;
    color: #fff;
    border: none;
    border-radius: 14px;
    padding: 12px 24px;
    font-size: 14px;
    font-weight: 700;
}

.cancel-btn {
    background: #fff;
    color: #111827;
    border: 1px solid #d6d9e0;
    border-radius: 14px;
    padding: 12px 24px;
    font-size: 14px;
    font-weight: 600;
}

/* =====================================================
        RESOURCE CARD
    ===================================================== */

.resource-card {
    background: #fff;
    border: 1px solid #e4e7ec;
    border-radius: 26px;
    padding: 34px;
    height: 100%;
    transition: .3s;
}

.resource-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
}

.card-top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 20px;
    margin-bottom: 26px;
}

.company-header {
    display: flex;
    gap: 18px;
}

.company-icon {
    width: 72px;
    height: 72px;
    border-radius: 22px;
    background: #dfe8ff;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.company-icon i {
    font-size: 30px;
    color: #3b63ff;
}

.company-name {
    font-size: 18px;
    font-weight: 600;
    color: #000;
    margin-bottom: 8px;
}

.company-desc {
    font-size: 15px;
    line-height: 1.7;
    color: #6c7688;
    max-width: 340px;
}

.resource-badge {
    background: #eef4ff;
    border: 1px solid #cfe0ff;
    color: #2f63ff;
    padding: 12px 20px;
    border-radius: 16px;
    font-size: 14px;
    font-weight: 700;
    white-space: nowrap;
}

.info-list {
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 15px;
    color: #556070;
}

.info-item i {
    font-size: 20px;
}


/* =====================================================
    TOP ACTION BAR
===================================================== */

.top-action-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 24px;
    flex-wrap: wrap;
}

/* REMOVE OLD FILTER BUTTON MARGIN */

.filter-btn {
    margin-bottom: 0 !important;
}

/* =====================================================
    VIEW SWITCHER
===================================================== */

.view-switcher {
    display: flex;
    align-items: center;
    gap: 10px;
    background: #fff;
    border: 1px solid #e4e7ec;
    padding: 10px;
    border-radius: 22px;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
}

.view-btn {
    width: 50px;
    height: 50px;
    border: none;
    border-radius: 16px;
    background: transparent;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: .25s ease;
    color: #64748b;
}

.view-btn i {
    font-size: 22px;
}

.view-btn:hover {
    background: #f3f4f6;
    color: #111827;
}

.view-btn.active {
    background: #17172d;
    color: #fff;
    box-shadow: 0 8px 20px rgba(23, 23, 45, .18);
}

.view-btn.active i {
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
    TABLE VIEW
===================================================== */

.resource-table-wrapper {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 26px;
    overflow: hidden;
}

.resource-table {
    width: 100%;
    border-collapse: collapse;
}

.resource-table thead {
    background: #f8fafc;
}

.resource-table th {
    padding: 18px 22px;
    font-size: 14px;
    font-weight: 700;
    color: #111827;
    border-bottom: 1px solid #e5e7eb;
}

.resource-table td {
    padding: 18px 22px;
    font-size: 14px;
    color: #556070;
    border-bottom: 1px solid #f1f5f9;
}

/* =====================================================
    RESOURCE GRID VIEW
===================================================== */

.resource-grid-card {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 24px;
    padding: 26px;
    height: 100%;
}

.resource-user {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 20px;
}

.resource-avatar {
    width: 64px;
    height: 64px;
    border-radius: 18px;
    background: #eef2ff;
    display: flex;
    align-items: center;
    justify-content: center;
}

.resource-avatar i {
    font-size: 30px;
    color: #4f46e5;
}

.resource-user-name {
    font-size: 18px;
    font-weight: 700;
    color: #111827;
}

.resource-role {
    font-size: 14px;
    color: #64748b;
}

.resource-skill {
    display: inline-block;
    background: #eef4ff;
    color: #2563ff;
    padding: 8px 14px;
    border-radius: 30px;
    font-size: 13px;
    font-weight: 600;
    margin-top: 14px;
}

/* =====================================================
    MATCH PERCENTAGE
===================================================== */

.resource-user {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 14px;
    margin-bottom: 20px;
}

.resource-user-left {
    display: flex;
    align-items: center;
    gap: 16px;
}

.match-badge {
    min-width: 74px;
    height: 38px;

    display: flex;
    align-items: center;
    justify-content: center;

    background: linear-gradient(135deg,
            #22c55e,
            #16a34a);

    color: #fff;

    border-radius: 14px;

    font-size: 13px;
    font-weight: 700;

    box-shadow: 0 8px 18px rgba(34, 197, 94, .18);
}

/* OPTIONAL DIFFERENT COLORS */

.match-high {
    background: linear-gradient(135deg, #22c55e, #16a34a);
}

.match-medium {
    background: linear-gradient(135deg, #f59e0b, #d97706);
}

.match-low {
    background: linear-gradient(135deg, #ef4444, #dc2626);
}

@media(max-width:991px) {

    .resources-wrapper {
        padding: 20px;
    }

    .filter-box {
        padding: 20px;
    }

    .card-top {
        flex-direction: column;
    }

    .resource-badge {
        align-self: flex-start;
    }

}

@media(max-width:767px) {

    .page-title {
        font-size: 26px;
    }

    .company-header {
        flex-direction: column;
    }

    .company-icon {
        width: 72px;
        height: 72px;
    }

    .company-icon i {
        font-size: 36px;
    }

    .company-name {
        font-size: 20px;
    }

}

/* =========================================
   PAGINATION DESIGN
========================================= */

#paginationLinks
{
    display: flex;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 35px;
}

#paginationLinks .pagination-btn
{
    min-width: 42px;
    height: 42px;
    border: 1px solid #e2e8f0;
    background: #ffffff;
    color: #1e293b;
    font-size: 14px;
    font-weight: 600;
    border-radius: 12px;
    transition: all 0.3s ease;
    box-shadow: 0 2px 10px rgba(0,0,0,0.04);
}

#paginationLinks .pagination-btn:hover
{
    background: #4f46e5;
    color: #ffffff;
    border-color: #4f46e5;
    transform: translateY(-2px);
}

#paginationLinks .btn-primary
{
    background: linear-gradient(135deg, #4f46e5, #7c3aed);
    color: #ffffff !important;
    border-color: transparent;
    box-shadow: 0 5px 15px rgba(79, 70, 229, 0.35);
}

#paginationLinks .btn-light
{
    background: #ffffff;
    color: #334155;
}

#paginationLinks .pagination-btn:focus
{
    outline: none;
    box-shadow: none;
}

/* PREV & NEXT BUTTONS */

#paginationLinks .pagination-btn:first-child,
#paginationLinks .pagination-btn:last-child
{
    padding: 0 18px;
    width: auto;
}

/* =========================================
   AJAX CARD LOADER
========================================= */

.card-loader
{
    width: 100%;
    min-height: 250px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: rgba(255,255,255,0.7);
    border-radius: 20px;
}

.loader-spinner
{
    width: 60px;
    height: 60px;
    border: 5px solid #e5e7eb;
    border-top: 5px solid #4f46e5;
    border-radius: 50%;
    animation: spinLoader 0.8s linear infinite;
    margin-bottom: 15px;
}

.loader-text
{
    font-size: 16px;
    font-weight: 600;
    color: #4f46e5;
}

@keyframes spinLoader
{
    100%
    {
        transform: rotate(360deg);
    }
}
</style>

@endsection


@section('mainContent')

<div class="container-fluid resources-wrapper">

    <h2 class="page-title">
        Corporate Resources
    </h2>

    <p class="page-subtitle">
        Manage and explore all corporate resource providers
    </p>

    <!-- =====================================================
TOP ACTION BAR
===================================================== -->

    <div class="top-action-bar">

        <!-- FILTER BUTTON -->
        <button class="filter-btn" id="toggleFilter">
            <i class="ri-filter-3-line"></i>
            Filters
        </button>

        <!-- VIEW SWITCHER -->
        <div class="view-switcher">

            <!-- COMPANY VIEW -->
            <button type="button" class="view-btn active" data-view="company">

                <i class="ri-building-4-line"></i>

            </button>

            <!-- TABLE VIEW -->
            <button type="button" class="view-btn" data-view="table">

                <i class="ri-table-line"></i>

            </button>

            <!-- RESOURCE VIEW -->
            <button type="button" class="view-btn" data-view="resource">

                <i class="ri-layout-grid-line"></i>

            </button>

        </div>

    </div>

    <!-- FILTER BOX -->
   <div class="filter-box" id="filterBox">

        <form id="filterForm">

            <div class="row g-4">

                <!-- KEYWORDS -->
                <div class="col-lg-4">

                    <div class="filter-title">
                        Keywords
                    </div>

                    <input type="text"
                        class="search-input"
                        id="keyword"
                        name="keyword"
                        placeholder="Search by name, email, or company...">

                </div>

                <!-- BUSINESS UNIT -->
                <div class="col-lg-4">

                    <div class="filter-title">
                        Profile
                    </div>

                    <select class="searchable-select"
                            id="business_unit"
                            multiple>

                        @foreach ($profiles as $profile)

                            <option value="{{ $profile->id }}">
                                {{ $profile->profile_name }}
                            </option>

                        @endforeach

                    </select>

                </div>

                <!-- PRIMARY SKILLS -->
                <div class="col-lg-4">

                    <div class="filter-title">
                        Primary Skills
                    </div>

                    <select class="searchable-select"
                            id="primarySkills"
                            multiple>

                        @foreach ($primaryskills as $primarySkill)

                            <option value="{{ $primarySkill->id }}">
                                {{ $primarySkill->name }}
                            </option>

                        @endforeach

                    </select>

                </div>

                <!-- SECONDARY SKILLS -->
                <div class="col-lg-4">

                    <div class="filter-title">
                        Secondary Skills
                    </div>

                    <select class="searchable-select"
                            id="secondarySkills"
                            multiple>

                        @foreach ($secondaryskills as $secondarySkill)

                            <option value="{{ $secondarySkill->id }}">
                                {{ $secondarySkill->name }}
                            </option>

                        @endforeach

                    </select>

                </div>

                <!-- EXPERIENCE -->
                <div class="col-lg-4">

                    <div class="filter-title">
                        Experience
                    </div>

                    <div class="range-wrapper">

                        <div class="range-top">

                            <span class="range-label">
                                Experience Range
                            </span>

                            <span class="range-count" id="experienceText">
                                All Experience
                            </span>

                        </div>

                        <input type="range"
                            min="0"
                            max="25"
                            value="0"
                            class="custom-range"
                            id="experienceRange">

                    </div>

                </div>

                <!-- LOCATION -->
                <div class="col-lg-4">

                    <div class="filter-title">
                        Location
                    </div>

                    <select class="searchable-select"
                            id="location"
                            multiple>

                        @foreach ($locations as $location)

                            <option value="{{ $location->id }}">
                                {{ $location->location_code }}
                            </option>

                        @endforeach

                    </select>

                </div>

            </div>

            <div class="filter-actions">

                <button type="submit"
                        class="apply-btn"
                        id="applyFilterBtn">

                    Apply Filters

                </button>

            </div>

        </form>

    </div>

  

    
    <div id="cardLoader" class="card-loader" style="display: none;">

        <div class="loader-spinner"></div>

        <div class="loader-text">
            Loading Resources...
        </div>

    </div>

    <div class="view-section active-view" id="companyView">

        <div class="row g-4" id="tenantCompanyList"></div>

        <div class="d-flex justify-content-center mt-4">

            <div id="paginationLinks"></div>

        </div>

    </div>

    <!-- </div> -->

    <!-- =====================================================
TABLE VIEW
===================================================== -->

        <div class="view-section" id="tableView">

    <div class="resource-table-wrapper">

        <table class="resource-table">

            <thead>

                <tr>
                    <th>S.No</th>
                    <th>Company Name</th>
                    <th>Email</th>
                    <th class="text-center">Action</th>

                </tr>

            </thead>

            <tbody id="resourceTableBody">

            </tbody>

        </table>

        <div class="d-flex justify-content-center mt-4">
            <div id="tablePaginationLinks"></div>
        </div>

    </div>

</div>

    <!-- =====================================================
RESOURCE CARD VIEW
===================================================== -->

    <div class="view-section" id="resourceView">

        <div class="row g-4" id="resourceCardList"></div>

        <div class="d-flex justify-content-center mt-4">
            <div id="resourcePaginationLinks"></div>
        </div>

    </div>

    @endsection


    
    @section('js')

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>

$(document).ready(function () {

    /* =====================================================
        SELECT2
    ===================================================== */

    $('.searchable-select').select2({
        placeholder: "Select",
        allowClear: true,
        width: '100%',
        dropdownParent: $('body')
    });

    /* =====================================================
        FILTER TOGGLE
    ===================================================== */

    $('#toggleFilter').on('click', function () {

        $('#filterBox').slideToggle(250);

    });

    /* =====================================================
        EXPERIENCE RANGE
    ===================================================== */

    function updateExperienceSlider()
    {
        let slider = document.getElementById('experienceRange');

        let value = parseInt(slider.value);

        let percentage = (value / 25) * 100;

        slider.style.background = `
            linear-gradient(
                to right,
                #2563ff 0%,
                #2563ff ${percentage}%,
                #dbe4f0 ${percentage}%,
                #dbe4f0 100%
            )
        `;

        if (value === 0)
        {
            $('#experienceText').text('All Experience');
        }
        else
        {
            $('#experienceText').text(value + ' Years');
        }
    }

    updateExperienceSlider();

    $('#experienceRange').on('input', function () {

        updateExperienceSlider();

    });

    /* =====================================================
        VIEW SWITCHER
    ===================================================== */

    $('.view-btn').on('click', function () {

        $('.view-btn').removeClass('active');

        $(this).addClass('active');

        let view = $(this).data('view');

        $('.view-section').removeClass('active-view');

        if (view === 'company')
        {
            $('#companyView').addClass('active-view');
        }

        if (view === 'table')
        {
            $('#tableView').addClass('active-view');
        }

        if (view === 'resource')
        {
            $('#resourceView').addClass('active-view');
        }

    });

    /* =====================================================
        LOAD TENANT CARDS
    ===================================================== */
    
    function loadTenantCards(page = 1)
    {
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

            success: function (response)
            {
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

                if (tenants.length > 0)
                {
                    $.each(tenants, function (index, tenant)
                    {
                        // =========================================
                        // SERIAL NUMBER
                        // =========================================

                        let serialNumber =
                            ((response.current_page - 1) * response.per_page)
                            + (index + 1);

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

                        if (tenant.waiting_users && tenant.waiting_users.length > 0)
                        {
                            $.each(tenant.waiting_users, function(userIndex, user)
                            {
                                let matchPercentage = user.match_percentage ?? 0;

                                let matchClass = 'match-low';

                                if (matchPercentage >= 80)
                                {
                                    matchClass = 'match-high';
                                }
                                else if (matchPercentage >= 60)
                                {
                                    matchClass = 'match-medium';
                                }

                                let primarySkill = '-';

                                if (user.skills && user.skills.length > 0)
                                {
                                    primarySkill = user.skills[0].name;
                                }

                                let resourceCard = `

                                    <div class="col-lg-4">

                                        <div class="resource-grid-card resource-detail-btn  data-user='${JSON.stringify(user)}'
     data-company="${tenant.tenant_name ?? '-'}"">

                                            <div class="resource-user">

                                                <div class="resource-user-left">

                                                    <div class="resource-avatar">
                                                        <i class="ri-user-3-line"></i>
                                                    </div>

                                                    <div>

                                                        <div class="resource-user-name">
                                                            ${user.name ?? '-'}
                                                        </div>

                                                        <div class="resource-role">
                                                            ${user.designation ?? '-'}
                                                        </div>

                                                    </div>

                                                </div>

                                                <div class="match-badge ${matchClass}">
                                                    ${matchPercentage}% Match
                                                </div>

                                            </div>

                                            <div class="info-item">
                                                <i class="ri-building-line"></i>
                                                ${tenant.tenant_name ?? '-'}
                                            </div>

                                            <div class="info-item">
                                                <i class="ri-map-pin-line"></i>
                                                ${user.location_code ?? '-'}
                                            </div>

                                            <div class="resource-skill">
                                                ${primarySkill}
                                            </div>

                                            <div class="mt-4">

                                                <a href="#"
                                                class="action-btn w-100">

                                                    <i class="ri-eye-line"></i>

                                                    View Profile

                                                </a>

                                            </div>

                                        </div>

                                    </div>

                                `;

                                $('#resourceCardList').append(resourceCard);
                            });
                        }

                    });

                    // =========================================
                    // PAGINATION
                    // =========================================

                    let paginationHtml = '';

                    // PREVIOUS BUTTON

                    if (response.current_page > 1)
                    {
                        paginationHtml += `

                            <button class="pagination-btn btn-light"
                                    data-page="${response.current_page - 1}">

                                Prev

                            </button>

                        `;
                    }

                    // PAGE NUMBERS

                    for (let i = 1; i <= response.last_page; i++)
                    {
                        paginationHtml += `

                            <button class="pagination-btn
                                ${i == response.current_page ? 'btn-primary' : 'btn-light'}"
                                data-page="${i}">

                                ${i}

                            </button>

                        `;
                    }

                    // NEXT BUTTON

                    if (response.current_page < response.last_page)
                    {
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
                }
                else
                {
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

            error: function (xhr)
            {
                console.log(xhr.responseText);
            },

            complete: function ()
            {
                $('#cardLoader').hide();

                $('#tenantCompanyList').show();
                $('#resourceTableBody').show();

                $('#paginationLinks').show();
                $('#tablePaginationLinks').show();
                $('#resourcePaginationLinks').show();
            }

        });
    }
    

    $(document).on('click', '.pagination-btn', function ()
    {
        let page = $(this).data('page');

        loadTenantCards(page);
    });

    setTimeout(function () {

        let firstCard = $('#resourceCardList .resource-detail-btn').first();

        if (firstCard.length) {

            firstCard.addClass('active-resource');

            firstCard.trigger('click');
        }

    }, 100);
    

    /* =====================================================
        INITIAL LOAD
    ===================================================== */

    loadTenantCards(1);

    /* =====================================================
        APPLY FILTER
    ===================================================== */

    $('#filterForm').on('submit', function (e) {

        e.preventDefault();

        loadTenantCards(1);

        // AUTO HIDE FILTER
        $('#filterBox').slideUp(250);

    });

    /* =====================================================
        PAGINATION CLICK
    ===================================================== */

    $(document).on('click', '.pagination-btn', function () {

        let page = $(this).data('page');

        loadTenantCards(page);

    });

});

</script>

@endsection