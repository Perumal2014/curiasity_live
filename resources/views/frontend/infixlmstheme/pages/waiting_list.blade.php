@extends(theme('layouts.dashboard_master'))

@section('title')
    {{ Settings('site_title') ? Settings('site_title') : 'Infix LMS' }} |
    {{ __('Available Resources') }}
@endsection

@section('css')

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>

.resources_page{
    background:#f8f9fb;
    padding:30px;
    border-radius:18px;
    overflow:visible !important;
    margin-bottom:80px;
}

/* =====================================================
    HEADER
===================================================== */

    .page-title{
        font-size:32px;
        font-weight:800;
        color:#111827;
        margin-bottom:6px;
    }

    .page-subtitle{
        font-size:15px;
        color:#6b7280;
        margin-bottom:28px;
    }

/* =====================================================
    TOP FILTER BAR
===================================================== */

.top-filter-bar{
    display:flex;
    align-items:center;
    gap:14px;
    margin-bottom:8px;
    flex-wrap:wrap;
}

.top-view-toggle{
    margin-left:auto;
}
/* =====================================================
    FILTER BUTTON
===================================================== */

.filter-toggle-wrapper{
    margin-bottom:0;
}

.filter-toggle-btn{
    height:50px;
    padding:0 24px;
    border:none;
    border-radius:16px;
    background:#111433;
    color:#fff;
    font-size:18px;
    font-weight:700;
    display:flex;
    align-items:center;
    gap:10px;
    cursor:pointer;
    transition:0.3s;
}

.filter-toggle-btn:hover{
    transform:translateY(-2px);
}

.filter-toggle-btn i{
    font-size:16px;
}

/* =====================================================
    TOP RIGHT TOGGLE
===================================================== */

.top-view-toggle{
    display:flex;
    align-items:center;
    gap:10px;
    background:#fff;
    border:1px solid #e7ecf3;
    padding:8px;
    border-radius:18px;
    box-shadow:0 8px 24px rgba(15,23,42,0.04);
}

.toggle-btn{
    width:42px;
    height:42px;
    border-radius:14px;
    border:1px solid #e2e8f0;
    background:#fff;
    color:#64748b;
    display:flex;
    align-items:center;
    justify-content:center;
    cursor:pointer;
    transition:0.3s;
    font-size:18px;
}

.toggle-btn.active{
    background:#020826;
    border-color:#020826;
    color:#fff;
}

/* =====================================================
    FILTER PANEL
===================================================== */

.filter-panel{
    background:#fff;
    border:1px solid #e7ecf3;
    border-radius:28px;
    padding:10px;
    margin-bottom:5px;

    max-height:0;
    overflow:hidden;
    opacity:0;

    transition:
        max-height 0.45s ease,
        opacity 0.3s ease,
        padding 0.3s ease;
}

.filter-panel.active{
    max-height:1000px;
    opacity:1;
}

/* =====================================================
    FILTER GRID
===================================================== */

.filter-grid{
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:28px;
}

.filter-group{
    display:flex;
    flex-direction:column;
    gap:12px;
}

.filter-group label{
    font-size:16px;
    font-weight:700;
    color:#0f172a;
}

/* =====================================================
    SEARCH BOX
===================================================== */

.search-box{
    position:relative;
    width:100%;
}

.search-input{
    width:100%;
    height:60px;
    border:1px solid #e2e8f0;
    border-radius:18px;
    background:#fff;
    padding:0 16px 0 50px;
    font-size:15px;
    font-weight:400;
    color:#0f172a;
    outline:none;
    transition:0.3s;
}

.search-input:focus{
    border-color:#020826;
    box-shadow:0 0 0 3px rgba(2,8,38,0.05);
}

.search-input::placeholder{
    color:#94a3b8;
}

.search-icon{
    position:absolute;
    top:50%;
    left:18px;
    transform:translateY(-50%);
    color:#94a3b8;
    font-size:16px;
}

/* =====================================================
    FILTER SELECT
===================================================== */

.filter-select{
    width:100%;
    height:60px;
    border:1px solid #e2e8f0;
    border-radius:18px;
    background:#fff;
    padding:0 16px;
    font-size:15px;
    font-weight:500;
    color:#0f172a;
    outline:none;
    box-shadow:none;
}

/* =====================================================
    SELECT2 MULTI SELECT
===================================================== */

.select2-container{
    width:100% !important;
}

.select2-container--default .select2-selection--multiple{
    min-height:60px;
    border:1px solid #e2e8f0 !important;
    border-radius:18px !important;
    padding:8px 12px !important;
    display:flex;
    align-items:center;
    flex-wrap:wrap;
    gap:6px;
    background:#fff;
}

.select2-container--default.select2-container--focus
.select2-selection--multiple{
    border-color:#020826 !important;
    box-shadow:0 0 0 3px rgba(2,8,38,0.05);
}

.select2-container--default
.select2-selection--multiple
.select2-selection__choice{
    background:#eef2ff !important;
    border:none !important;
    color:#3563ff !important;
    border-radius:999px !important;
    padding:6px 12px !important;
    font-size:14px;
    font-weight:600;
    margin-top:0 !important;
}

.select2-container--default
.select2-selection--multiple
.select2-selection__choice__remove{
    color:#3563ff !important;
    margin-right:6px;
    border:none !important;
}

.select2-container--default
.select2-search--inline
.select2-search__field{
    margin-top:0 !important;
    margin-left:0 !important;
    padding-left:0 !important;
    height:24px;
    font-size:14px !important;
}

.select2-container--default
.select2-selection--multiple
.select2-selection__rendered{
    padding-left:0 !important;
    margin-left:0 !important;
}

.select2-container--default
.select2-selection--multiple{
    padding:6px 10px !important;
}

.select2-dropdown{
    border:1px solid #e2e8f0 !important;
    border-radius:14px !important;
    overflow:hidden;
}

.select2-search__field{
    outline:none !important;
}

.select2-results__option{
    padding:10px 14px !important;
    font-size:14px;
}

.select2-container--default
.select2-results__option--highlighted.select2-results__option--selectable{
     background:#7c3aed !important;
    color:#fff !important;
}

.select2-results__option--selected{
    background:#f3f4f6 !important;
    color:#111827 !important;
}
/* =====================================================
    EXPERIENCE RANGE
===================================================== */

.experience-box{
    border:1px solid #e2e8f0;
    border-radius:22px;
    padding:22px;
    background:#fff;
}

.experience-top{
    display:flex;
    align-items:center;
    justify-content:space-between;
    margin-bottom:24px;
    gap:16px;
}

.experience-label{
    font-size:16px;
    font-weight:700;
    color:#0f172a;
}

.experience-badge{
    background:#eef2ff;
    color:#3563ff;
    padding:10px 18px;
    border-radius:999px;
    font-size:15px;
    font-weight:700;
}

.experience-range{
    width:100%;
    appearance:none;
    height:8px;
    border-radius:999px;
    background:#dbe4f0;
    outline:none;
}

.experience-range::-webkit-slider-thumb{
    appearance:none;
    width:24px;
    height:24px;
    border-radius:50%;
    background:#3563ff;
    border:4px solid #fff;
    box-shadow:0 4px 12px rgba(53,99,255,0.35);
    cursor:pointer;
}

.experience-range::-moz-range-thumb{
    width:24px;
    height:24px;
    border:none;
    border-radius:50%;
    background:#3563ff;
    cursor:pointer;
}

/* =====================================================
    FILTER ACTIONS
===================================================== */

.filter-actions{
    display:flex;
    align-items:center;
    gap:16px;
    margin-top:34px;
    flex-wrap:wrap;
}

.apply-filter-btn{
    height:58px;
    padding:0 34px;
    border:none;
    border-radius:18px;
    background:#020826;
    color:#fff;
    font-size:18px;
    font-weight:700;
    cursor:pointer;
    transition:0.3s;
}

.cancel-filter-btn{
    height:58px;
    padding:0 34px;
    border:1px solid #dbe2ea;
    border-radius:18px;
    background:#fff;
    color:#0f172a;
    font-size:18px;
    font-weight:600;
    cursor:pointer;
    transition:0.3s;
}

.apply-filter-btn:hover,
.cancel-filter-btn:hover{
    transform:translateY(-2px);
}

/* =====================================================
    CARD VIEW
===================================================== */

.resource-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(320px,1fr));
    gap:20px;
    margin-top:0;
}

.resource-card{
    background:#fff;
    border:1px solid #e9edf5;
    border-radius:20px;
    padding:24px;
    transition:0.3s;
}

.resource-card:hover{
    transform:translateY(-2px);
    box-shadow:0 8px 24px rgba(15,23,42,0.05);
}

.card-top{
    display:flex;
    align-items:center;
    justify-content:space-between;
    margin-bottom:24px;
}

.resource-name{
    font-size:20px;
    font-weight:600;
    color:#0f172a;
}

.resource-info{
    font-size:15px;
    font-weight:400;
    color:#64748b;
    margin-bottom:10px;
}

.resource-info strong{
    font-weight:600;
    color:#334155;
}

.view-btn{
    width:100%;
    height:48px;
    margin-top:22px;
    border-radius:14px;
    border:1px solid #dbe1ea;
    background:#fff;
    color:#2563eb;
    font-size:16px;
    font-weight:600;
    transition:0.3s;
    display:flex;
    align-items:center;
    justify-content:center;
    gap:10px;
}

.view-btn i{
    font-size:16px;
}

.view-btn:hover{
    background:#2563eb;
    border-color:#2563eb;
    color:#fff;
}

/* =====================================================
    TABLE VIEW
===================================================== */

.table-wrapper{
    display:block;
    margin-top:0;
    background:#fff;
    border:1px solid #e9edf5;
    border-radius:20px;
    overflow:hidden;
}

.custom-table{
    width:100%;
    border-collapse:collapse;
    table-layout:fixed;
}

.custom-table thead th{
    padding:18px 20px;
    font-size:15px;
    font-weight:700;
    color:#0f172a;
    border-bottom:1px solid #edf2f7;
    background:#fff;
    text-align:left;
}

.custom-table tbody td{
    padding:18px 20px;
    font-size:15px;
    font-weight:500;
    color:#475569;
    border-bottom:1px solid #edf2f7;
    vertical-align:middle;
}

/* COLUMN WIDTHS */

.custom-table th:nth-child(1),
.custom-table td:nth-child(1){
    width:80px;
}

.custom-table th:nth-child(2),
.custom-table td:nth-child(2){
    width:240px;
}

.custom-table th:nth-child(3),
.custom-table td:nth-child(3){
    width:180px;
}

.custom-table th:nth-child(4),
.custom-table td:nth-child(4){
    width:180px;
}

.custom-table th:nth-child(5),
.custom-table td:nth-child(5){
    width:220px;
}

.custom-table th:nth-child(6),
.custom-table td:nth-child(6){
    width:180px;
    text-align:center;
}

/* LAST ROW */

.custom-table tbody tr:last-child td{
    border-bottom:none;
}

/* EMPLOYEE NAME */

.employee-name{
    font-weight:700;
    color:#334155;
}

/* BUTTON */

.table-view-btn{
    min-width:130px;
    height:44px;
    padding:0 18px;
    border-radius:12px;
    border:1px solid #dbe1ea;
    background:#fff;
    color:#2563eb;
    font-size:15px;
    font-weight:600;
    transition:0.3s;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    gap:8px;
}

.table-view-btn:hover{
    background:#2563eb;
    border-color:#2563eb;
    color:#fff;
}

/* =====================================================
    PAGINATION
===================================================== */

.pagination-area{
    display:flex;
    align-items:center;
    justify-content:space-between;
    margin-top:26px;
    flex-wrap:wrap;
    gap:20px;
    padding-bottom:40px;
}

.entries-wrapper{
    font-size:15px;
    color:#64748b;
}

.pagination{
    display:flex;
    align-items:center;
    gap:8px;
}

.page-btn{
    width:40px;
    height:40px;
    border-radius:10px;
    border:1px solid #e2e8f0;
    background:#fff;
    color:#475569;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:14px;
    font-weight:600;
    cursor:pointer;
    transition:0.3s;
}

.page-btn.active{
    background:#020826;
    border-color:#020826;
    color:#fff;
}

.page-btn:hover{
    background:#020826;
    border-color:#020826;
    color:#fff;
}

/* =====================================================
    RESPONSIVE
===================================================== */

@media(max-width:991px){

    .page-title{
        font-size:34px;
    }

    .filter-grid{
        grid-template-columns:1fr;
    }

    .table-wrapper{
        overflow-x:auto;
    }

    .custom-table{
        min-width:900px;
    }

}

@media(max-width:768px){

    .resources_page{
        padding:20px;
    }

    .top-filter-bar{
        flex-direction:column;
        align-items:stretch;
    }

    .top-view-toggle{
        justify-content:center;
    }

    .filter-panel{
        padding:24px;
    }

    .filter-toggle-btn{
        width:100%;
        justify-content:center;
    }

    .resource-grid{
        grid-template-columns:1fr;
    }

}

.page-loader{
    position:fixed;
    inset:0;
    background:rgba(255,255,255,0.7);
    z-index:99999;
    display:none;
    align-items:center;
    justify-content:center;
    backdrop-filter:blur(2px);
}

.loader{
    width:50px;
    height:50px;
    border:4px solid #e5e7eb;
    border-top:4px solid #2563eb;
    border-radius:50%;
    animation:spin 0.8s linear infinite;
}

@keyframes spin{
    100%{
        transform:rotate(360deg);
    }
}

.custom-table {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed;
}

.custom-table th,
.custom-table td {
    padding: 10px 12px;
    vertical-align: middle;
    white-space: nowrap;
    line-height: 1.2;
}

.custom-table th:nth-child(2),
.custom-table td:nth-child(2) {
    width: 70px;
    text-align: center;
}

.custom-table th:nth-child(3),
.custom-table td:nth-child(3) {
    width: 160px;
}

.custom-table td:nth-child(6) {
    max-width: 180px;
    overflow: hidden;
    text-overflow: ellipsis;
}

.employee-name {
    font-weight: 600;
    margin: 0;
}

.table-view-btn {
    padding: 4px 10px !important;
    font-size: 13px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.card-top{

    display:flex;

    align-items:center;

    justify-content:space-between;

    margin-bottom:18px;
}

.resource-checkbox{

    width:18px;

    height:18px;

    cursor:pointer;

    accent-color:#7c3aed;
}

.revert-btn{

    height:48px;

    padding:0 20px;

    border:none;

    border-radius:12px;

    background:#16a34a;

    color:#fff;

    font-size:15px;

    font-weight:700;

    margin-bottom:7px;
}

.top-filter-bar{

    display:flex;

    align-items:center;

    justify-content:space-between;

    gap:20px;

    flex-wrap:wrap;

    margin-bottom:24px;
}

</style>

@endsection

@section('js')

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>

let resources = [];

// =====================================================
// FILTER EXPAND / COLLAPSE
// =====================================================

const filterToggleBtn = document.getElementById("filterToggleBtn");
const filterPanel = document.getElementById("filterPanel");
const closeFilterBtn = document.getElementById("closeFilterBtn");

filterToggleBtn.addEventListener("click", () => {

    filterPanel.classList.toggle("active");

});

closeFilterBtn.addEventListener("click", () => {

    filterPanel.classList.remove("active");

});

// =====================================================
// VARIABLES
// =====================================================

let currentPage = 1;
let perPage = 20;
let filteredData = [];

const cardContainer = document.getElementById("cardContainer");
const tableBody = document.getElementById("tableBody");
const pagination = document.getElementById("pagination");

// =====================================================
// LOAD AJAX DATA
// =====================================================

function loadResources()
{
    $('#pageLoader').css('display', 'flex');

    $.ajax({

        url: "{{ route('organization.resources.datatable', ['tenant_slug' => request()->route('tenant_slug')]) }}",

        type: "GET",

        data: {

            primary_skill: $('#primarySkill').val(),
            secondary_skill: $('#secondarySkill').val()
        },

        success: function(response)
        {
            resources = response.data || [];

            filteredData = [...resources];

            currentPage = 1;

            renderData();

            $('#pageLoader').hide();
        },

        error: function(xhr)
        {
            console.log(xhr.responseText);

            $('#pageLoader').hide();
        }
    });
}

// =====================================================
// RENDER DATA
// =====================================================

function renderData()
{
    cardContainer.innerHTML = "";
    tableBody.innerHTML = "";

    const start = (currentPage - 1) * perPage;
    const end = start + perPage;

    const paginatedData = filteredData.slice(start, end);

    const totalCount = filteredData.length;

    const showingStart = totalCount === 0
        ? 0
        : start + 1;

    const showingEnd = Math.min(end, totalCount);

    document.getElementById("entriesInfo").innerHTML =
        `Showing ${showingStart} to ${showingEnd} of ${totalCount} entries`;

    paginatedData.forEach((item, index) => {

        // =====================================================
        // CARD VIEW
        // =====================================================

        const detailUrl = `{{ route('corporateViewDetails',['tenant_slug' => request()->route('tenant_slug'),'id' => ':id']) }}`.replace(':id', item.user_id);

        cardContainer.innerHTML += `

            <div class="resource-card" id="resource-fresh">

                <div class="card-top">

                    <div class="resource-name">
                        ${item.name ?? '-'}
                    </div>

                    <div>
                        <input type="checkbox"
                            class="resource-checkbox"
                            value="${item.user_id}">
                    </div>

                </div>

                <div class="resource-info">
                    <strong>Email:</strong>
                    ${item.email ?? '-'}
                </div>

                <div class="resource-info">
                    <strong>Status:</strong>
                    ${item.status_badge ?? '-'}
                </div>

                <a href="${detailUrl}"
                    class="view-btn viewUserBtn">

                    <i class="ti-eye"></i>

                    <span>View Profile</span>

                </a>

            </div>
        `;

        // =====================================================
        // TABLE VIEW
        // =====================================================

        tableBody.innerHTML += `

            <tr>
                <td>${item.checkbox}</td>
                <td>${start + index + 1}</td>
                <td>
                    <span class="employee-name">
                        ${item.name ?? '-'}
                    </span>
                </td>
                <td>${item.profile ?? '-'}</td>
                
                <td>${item.location ?? '-'}</td>
                <td><a href="${detailUrl}" class="table-view-btn viewUserBtn"><i class="ti-eye"></i><span>View Profile</span>
                </a>
                </td>
            </tr>`;
    });

    renderPagination();
}

// =====================================================
// PAGINATION
// =====================================================

function renderPagination()
{
    pagination.innerHTML = "";

    const totalPages = Math.ceil(filteredData.length / perPage);

    if(totalPages <= 1){

        pagination.style.display = "none";

    }else{

        pagination.style.display = "flex";
    }

    // PREVIOUS

    const prevBtn = document.createElement("button");

    prevBtn.className = "page-btn";

    prevBtn.innerHTML = `<i class="ti-angle-left"></i>`;

    prevBtn.disabled = currentPage === 1;

    prevBtn.addEventListener("click", () => {

        if(currentPage > 1){

            currentPage--;

            renderData();
        }
    });

    pagination.appendChild(prevBtn);

    // PAGES

    for(let i = 1; i <= totalPages; i++)
    {
        const btn = document.createElement("button");

        btn.className = "page-btn";

        if(i === currentPage){

            btn.classList.add("active");
        }

        btn.innerText = i;

        btn.addEventListener("click", () => {

            currentPage = i;

            renderData();
        });

        pagination.appendChild(btn);
    }

    // NEXT

    const nextBtn = document.createElement("button");

    nextBtn.className = "page-btn";

    nextBtn.innerHTML = `<i class="ti-angle-right"></i>`;

    nextBtn.disabled = currentPage === totalPages;

    nextBtn.addEventListener("click", () => {

        if(currentPage < totalPages){

            currentPage++;

            renderData();
        }
    });

    pagination.appendChild(nextBtn);
}

// =====================================================
// SELECT2
// =====================================================

$('.multi-select').select2({

    placeholder: "Select options",

    allowClear: true,

    closeOnSelect: false
});

// =====================================================
// FILTERS
// =====================================================

$('#profileFilter').on('change', applyFilters);

$('#locationFilter').on('change', applyFilters);

document.getElementById("searchInput")
.addEventListener("keyup", applyFilters);

const experienceFilter =
    document.getElementById("experienceFilter");

const experienceValue =
    document.getElementById("experienceValue");

experienceFilter.addEventListener("input", function(){

    if(this.value == 0){

        experienceValue.innerText = "All Experience";

    }else{

        experienceValue.innerText =
            this.value + " Years";
    }

    applyFilters();
});

// =====================================================
// APPLY FILTERS
// =====================================================

function applyFilters()
{
    const searchValue =
        document.getElementById("searchInput")
        .value
        .toLowerCase();

    filteredData = resources.filter(item => {

        return (
            (item.name ?? '')
            .toLowerCase()
            .includes(searchValue)

            ||

            (item.email ?? '')
            .toLowerCase()
            .includes(searchValue)
        );
    });

    currentPage = 1;

    renderData();
}

// =====================================================
// VIEW TOGGLE
// =====================================================

const tableViewBtn =
    document.getElementById("tableViewBtn");

const cardViewBtn =
    document.getElementById("cardViewBtn");

const tableContainer =
    document.getElementById("tableContainer");

const cardContainerView =
    document.getElementById("cardContainer");

// DEFAULT CARD VIEW

tableContainer.style.display = "none";

cardContainerView.style.display = "grid";

tableViewBtn.addEventListener("click", () => {

    tableContainer.style.display = "block";

    cardContainerView.style.display = "none";

    tableViewBtn.classList.add("active");

    cardViewBtn.classList.remove("active");
});

cardViewBtn.addEventListener("click", () => {

    tableContainer.style.display = "none";

    cardContainerView.style.display = "grid";

    cardViewBtn.classList.add("active");

    tableViewBtn.classList.remove("active");
});

// =====================================================
// VIEW USER
// =====================================================

$(document).on('click', '.viewUserBtn', function () {

    let userId = $(this).data('id');

    alert("User ID : " + userId);

});

// =====================================================
// INITIAL LOAD
// =====================================================

loadResources();


</script>

@endsection

@section('mainContent')
<div id="pageLoader" class="page-loader">

    <div class="loader"></div>

</div>
<section class="resources_page">

    <!-- HEADER -->
    <h2 class="page-title">
        Available Resources
    </h2>

    <p class="page-subtitle">
        Manage and view all available resources
    </p>

    <!-- TOP FILTER BAR -->
    <div class="top-filter-bar">

    <!-- LEFT SECTION -->
    <div class="d-flex align-items-center gap-3 flex-wrap">

        <button class="filter-toggle-btn" id="filterToggleBtn">

            <i class="ti-filter"></i>

            <span>Filters</span>

        </button>

        <button type="button"
            class="revert-btn"
            id="revertSelectedBtn"
            style="display:none;">

            Revert To Active

        </button>

    </div>

    <!-- RIGHT SECTION -->
    <div class="top-view-toggle">

        <button class="toggle-btn active" id="cardViewBtn">

            <i class="ti-layout-grid2"></i>

        </button>

        <button class="toggle-btn" id="tableViewBtn">

            <i class="ti-layout-list-thumb"></i>

        </button>

    </div>

</div>
   
    <!-- FILTER PANEL -->
    <div class="filter-panel" id="filterPanel">

        <div class="filter-grid">

        <!-- SEARCH -->
        <div class="filter-group">

            <label>
                Keywords
            </label>

            <div class="search-box">

                <span class="search-icon">
                    <i class="ti-search"></i>
                </span>

                <input
                    type="text"
                    id="searchInput"
                    class="search-input"
                    placeholder="Search by name or profile..."
                >

            </div>

        </div>

    <!-- PROFILE -->
    <div class="filter-group">

        <label>
            Profile
        </label>

        <select
            class="filter-select multi-select"
            id="profileFilter"
            multiple
        >

            <option value="Developer">
                Developer
            </option>

            <option value="Designer">
                Designer
            </option>

            <option value="Manager">
                Manager
            </option>

        </select>

    </div>


        <!-- EXPERIENCE -->
    <div class="filter-group">

        <label>
            Experience
        </label>

        <div class="experience-box">

            <div class="experience-top">

                <span class="experience-label">
                    Range
                </span>

                <span class="experience-badge" id="experienceValue">
                    All Experience
                </span>

            </div>

            <input
                type="range"
                min="0"
                max="25"
                value="0"
                class="experience-range"
                id="experienceFilter"
            >

        </div>

    </div>

    <!-- LOCATION -->
    <div class="filter-group">

        <label>
            Location
        </label>

        <select
            class="filter-select multi-select"
            id="locationFilter"
            multiple
        >

            <option value="India">
                India
            </option>

            <option value="New York">
                New York
            </option>

            <option value="Austin">
                Austin
            </option>

            <option value="San Francisco">
                San Francisco
            </option>

        </select>

    </div>

    </div>

        <!-- ACTIONS -->
    <div class="filter-actions">

            <button class="apply-filter-btn">
                Apply Filters
            </button>

            <button
                class="cancel-filter-btn"
                id="closeFilterBtn"
            >
                Cancel
            </button>

        </div>

    </div>

    <!-- CARD VIEW -->
    <div class="resource-grid" id="cardContainer"></div>

    <!-- TABLE VIEW -->
    <div class="table-wrapper" id="tableContainer">

        <table class="custom-table">

            <thead>

                <tr>
                    <th>
                        <input type="checkbox" id="selectAll">
                    </th>

                    <th>Sl.No</th>

                    <th>Employee</th>

                    <th>Profile</th>

                    <th>Location</th>


                    <th>
                        Action
                    </th>

                </tr>

            </thead>

            <tbody id="tableBody"></tbody>

        </table>

    </div>

    <!-- PAGINATION -->
    <div class="pagination-area">

        <!-- LEFT -->
        <div class="entries-wrapper">

            <span id="entriesInfo">
                Showing 0 of 0 entries
            </span>

        </div>

        <!-- RIGHT -->
        <div class="pagination" id="pagination"></div>

    </div>

</section>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script type="text/javascript">

$(document).ready(function () {

    // =====================================================
    // VARIABLES
    // =====================================================

    let selectedUsers = [];

    // =====================================================
    // LOAD USERS
    // =====================================================

    function loadBenchUsers()
    {
        $('#pageLoader').css('display', 'flex');

        $.ajax({

            url: "{{ route('organization.resources.datatable', ['tenant_slug' => request()->route('tenant_slug')]) }}",

            type: "GET",

            success: function (response) {

                resources = response.data || [];

                filteredData = [...resources];

                currentPage = 1;

                renderData();
            },

            complete: function () {

                $('#pageLoader').hide();
            },

            error: function (xhr) {

                console.log(xhr.responseText);

                $('#pageLoader').hide();
            }
        });
    }

    // =====================================================
    // RENDER DATA
    // =====================================================

    function renderData()
    {
        cardContainer.innerHTML = "";
        tableBody.innerHTML = "";

        const start = (currentPage - 1) * perPage;
        const end = start + perPage;

        const paginatedData = filteredData.slice(start, end);

        const totalCount = filteredData.length;

        const showingStart = totalCount === 0 ? 0 : start + 1;
        const showingEnd = Math.min(end, totalCount);

        document.getElementById("entriesInfo").innerHTML =
            `Showing ${showingStart} to ${showingEnd} of ${totalCount} entries`;

        paginatedData.forEach((item, index) => {

            const detailUrl =
                `{{ route('corporateViewDetails',['tenant_slug' => request()->route('tenant_slug'),'id' => ':id']) }}`
                .replace(':id', item.user_id);

            // =====================================================
            // CARD VIEW
            // =====================================================

            cardContainer.innerHTML += `

                <div class="resource-card">

                    <div class="card-top">

                        <div class="resource-name">
                            ${item.name ?? '-'}
                        </div>

                        <input type="checkbox"
                            class="resource-checkbox"
                            value="${item.user_id}">

                    </div>

                    <div class="resource-info">
                        <strong>Email:</strong>
                        ${item.email ?? '-'}
                    </div>

                    <div class="resource-info">
                        <strong>Status:</strong>
                        ${item.status_badge ?? '-'}
                    </div>

                    <a href="${detailUrl}"
                        class="view-btn">

                        <i class="ti-eye"></i>

                        <span>View Profile</span>

                    </a>

                </div>
            `;

            // =====================================================
            // TABLE VIEW
            // =====================================================

            tableBody.innerHTML += `

                <tr>

                    <td>
                        <input type="checkbox"
                            class="resource-checkbox"
                            value="${item.user_id}">
                    </td>

                    <td>${start + index + 1}</td>

                    <td>
                        <span class="employee-name">
                            ${item.name ?? '-'}
                        </span>
                    </td>

                    <td>${item.profile ?? '-'}</td>

                    <td>${item.location ?? '-'}</td>

                    <td>

                        <a href="${detailUrl}"
                            class="table-view-btn">

                            <i class="ti-eye"></i>

                            <span>View Profile</span>

                        </a>

                    </td>

                </tr>
            `;
        });

        renderPagination();
    }

    // =====================================================
    // INITIAL LOAD
    // =====================================================

    loadBenchUsers();

    // =====================================================
    // CHECKBOX CHANGE
    // =====================================================

    $(document).on('change', '.resource-checkbox', function () {

        let userId = $(this).val();

        if ($(this).is(':checked')) {

            if (!selectedUsers.includes(userId)) {

                selectedUsers.push(userId);
            }

        } else {

            selectedUsers = selectedUsers.filter(id => id != userId);
        }

        toggleRevertButton();

        console.log(selectedUsers);
    });

    // =====================================================
    // SELECT ALL
    // =====================================================

    $(document).on('change', '#selectAll', function () {

        let isChecked = $(this).is(':checked');

        $('.resource-checkbox').prop('checked', isChecked);

        selectedUsers = [];

        if (isChecked) {

            $('.resource-checkbox').each(function () {

                selectedUsers.push($(this).val());
            });
        }

        toggleRevertButton();

        console.log(selectedUsers);
    });

    // =====================================================
    // TOGGLE REVERT BUTTON
    // =====================================================

    function toggleRevertButton()
    {
        if (selectedUsers.length > 0) {

            $('#revertSelectedBtn').show();

        } else {

            $('#revertSelectedBtn').hide();
        }
    }

    // =====================================================
    // REVERT USERS
    // =====================================================

    $('#revertSelectedBtn').click(function () {

        if (selectedUsers.length == 0) {

            Swal.fire({

                icon: 'warning',

                title: 'No Users Selected',

                text: 'Please select at least one user',

                confirmButtonColor: '#7c3aed'
            });

            return;
        }

        Swal.fire({

            title: 'Are you sure?',

            text: 'Selected users will be reverted to active users',

            icon: 'question',

            showCancelButton: true,

            confirmButtonColor: '#7c3aed',

            cancelButtonColor: '#d33',

            confirmButtonText: 'Yes, Revert',

            cancelButtonText: 'Cancel'

        }).then((result) => {

            if (result.isConfirmed) {

                $.ajax({

                    url: "{{ route('bench.users.revert') }}",

                    type: "POST",

                    data: {

                        _token: "{{ csrf_token() }}",

                        user_ids: selectedUsers
                    },

                    beforeSend: function () {

                        Swal.fire({

                            title: 'Processing...',

                            text: 'Please wait',

                            allowOutsideClick: false,

                            didOpen: () => {

                                Swal.showLoading();
                            }
                        });
                    },

                    success: function (response) {

                        Swal.fire({

                            icon: 'success',

                            title: 'Success',

                            text: response.message,

                            confirmButtonColor: '#7c3aed'
                        });

                        selectedUsers = [];

                        $('#revertSelectedBtn').hide();

                        $('#selectAll').prop('checked', false);

                        loadBenchUsers();
                    },

                    error: function (xhr) {

                        Swal.fire({

                            icon: 'error',

                            title: 'Something Went Wrong',

                            text: 'Unable to revert users',

                            confirmButtonColor: '#7c3aed'
                        });

                        console.log(xhr.responseText);
                    }
                });
            }
        });
    });

});

</script>
@endsection