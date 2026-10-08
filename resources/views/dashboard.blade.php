@extends('backend.master')

@push('styles')
<style>

@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

#main-content{
    padding:80px 15px 20px !important;
}

.container{
    padding-left:0 !important;
    padding-right:0 !important;
    margin-bottom:20px;
}

.dashboard-heading{
    font-size:18px;
    font-weight:500;
    color:#343a40;
    margin-bottom:25px;
    text-transform:uppercase;
}

.cards{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:30px;
    margin-bottom:30px;
}

.card{
    background:#fff;
    border-radius:6px;
    padding:20px 18px;
    min-height:100px;
    border:none;
}

.card-content{
    display:flex;
    justify-content:space-between;
    align-items:stretch;
    width:100%;
    height:100%;
}

.card-label{
    font-size:18px;
    font-weight:500;
    color:#343a40;
}

.card-subtitle{
    display: flex;
    justify-content: end;
    font-size:14px;
    color:#6c757d;
}

.card-left{
    display:flex;
    flex-direction:column;
    justify-content:space-between;
    height:100%;
}

.card-value{
    align-self:flex-start;
    font-size:20px;
    font-weight:500;
    color:#343a40;
    line-height:1;
}

.card-icon{
display:none;
}

.blue{background:#eef4ff;color:#2563eb;}
.purple{background:#f5edff;color:#9333ea;}
.green{background:#ecfdf3;color:#16a34a;}
.orange{background:#fff7ed;color:#ea580c;}


.top-nav{
background:#fff;
border:1px solid #e5e7eb;
border-radius:18px;
padding:10px;
display:flex;
justify-content:space-between;
align-items:center;
margin-bottom:25px;
}

.tabs{
display:flex;
gap:10px;
}

.tab{
padding:12px 20px;
border-radius:12px;
cursor:pointer;
font-weight:600;
}

.tab.active{
background:#2563eb;
color:#fff;
}

.filter-area{
display:flex;
align-items:center;
gap:15px;
}

.filter-btn{
border:1px solid #d1d5db;
padding:10px 16px;
border-radius:12px;
background:#fff;
cursor:pointer;
}

.title-row{
display:flex;
justify-content:space-between;
align-items:center;
margin-bottom:20px;
}

.page-title{
font-size:22px;
font-weight:500;
}

.toggle{
display:flex;
background:#fff;
border:1px solid #e5e7eb;
border-radius:14px;
padding:5px;
}

.toggle button{
border:none;
background:transparent;
padding:10px 18px;
border-radius:10px;
cursor:pointer;
}

.toggle button.active{
background:#2563eb;
color:#fff;
}

.panel{
background:#fff;
border:1px solid #e5e7eb;
border-radius:18px;
padding:25px;
}

.toolbar{
display:flex;
justify-content:space-between;
margin-bottom:20px;
}

.search{
width:400px;
padding:12px 15px;
border:1px solid #d1d5db;
border-radius:12px;
}

.export-btn{
background:#2563eb;
color:#fff;
border:none;
padding:12px 20px;
border-radius:12px;
cursor:pointer;
}

table{
width:100%;
border-collapse:collapse;
margin-bottom:10px;
}

thead{
background:#f8fafc;
}

th{
padding:16px;
text-align:left;
color:#64748b;
font-weight:600;
}

td{
padding:16px;
border-top:1px solid #e5e7eb;
}

.table-wrapper{
    width:100%;
    overflow-x:auto;
    overflow-y:auto;
    max-height:400px;
    border-radius:12px;
}

.table-wrapper table{
    width:100%;
    min-width:1000px;
    /* border-collapse:collapse; */
}

/* Sticky header while vertical scrolling */
.table-wrapper thead th{
    position:sticky;
    top:0;
    z-index:10;
    background:#f8fafc;
}

/* #chartView{
display:none;
height:600px;
} */

.filter-popup{
display:none;
position:absolute;
right:0;
top:55px;
width:320px;
background:#fff;
border:1px solid #e5e7eb;
border-radius:16px;
padding:20px;
box-shadow:0 10px 25px rgba(0,0,0,.08);
z-index:999;
}

.filter-wrapper{
position:relative;
}

.filter-tabs{
display:flex;
gap:10px;
margin-bottom:15px;
}

.filter-tab{
flex:1;
padding:10px;
border:none;
background:#f3f4f6;
border-radius:10px;
cursor:pointer;
}

.filter-tab.active{
background:#2563eb;
color:#fff;
}

.date-row{
display:flex;
gap:10px;
}

.date-row input{
flex:1;
padding:10px;
border:1px solid #d1d5db;
border-radius:10px;
}

.apply-btn{
margin-top:15px;
width:100%;
padding:12px;
background:#2563eb;
color:#fff;
border:none;
border-radius:10px;
cursor:pointer;
}


#chartView{
    display:none;
    height:520px;
    padding-bottom:15px;
}

#dashboardChart{
    width:100% !important;
    height:100% !important;
}

@media (max-width:1200px){

    .cards{
        grid-template-columns:repeat(2,1fr);
    }

    .table-wrapper table{
        min-width:1000px;
    }
}

@media (max-width:768px){

    #main-content{
        padding:80px 10px 20px !important;
    }

    .cards{
        grid-template-columns:1fr;
        gap:15px;
    }

    .tabs{
        overflow-x:auto;
        white-space:nowrap;
        width:100%;
    }

    .top-nav{
        flex-direction:column;
        align-items:flex-start;
        gap:15px;
    }

    .filter-area{
        width:100%;
        justify-content:space-between;
    }

    .toolbar{
        flex-direction:column;
        gap:15px;
    }

    .search{
        width:100%;
    }

    .export-btn{
        width:100%;
    }

    .title-row{
        flex-direction:column;
        align-items:flex-start;
        gap:15px;
    }
}

@media (max-width:576px){

    .page-title{
        font-size:20px;
    }

    .card-label{
        font-size:16px;
    }

    .card-value{
        font-size:18px;
    }

    .table-wrapper{
        max-height:400px;
    }
}


@media(max-width:992px){

.cards{
grid-template-columns:1fr 1fr;
}

.top-nav{
flex-direction:column;
gap:15px;
align-items:flex-start;
}

.title-row{
flex-direction:column;
align-items:flex-start;
gap:15px;
}

.search{
width:100%;
}
}

/* Tablet */
@media (max-width: 768px){

    .dashboard-heading{
        font-size:16px;
    }

    .card{
        padding:15px;
        min-height:90px;
    }

    .card-label{
        font-size:16px;
    }

    .card-subtitle{
        font-size:13px;
        line-height:1.3;
    }

    .card-value{
        font-size:18px;
    }

    .page-title{
        font-size:18px;
    }

    .tab{
        font-size:13px;
        padding:10px 14px;
    }
}

@media (max-width: 480px){

    .cards{
        grid-template-columns:repeat(2,1fr);
        gap:10px;
    }

    .card{
        padding:12px;
        min-height:80px;
    }

    .card-label{
        font-size:14px;
        margin-bottom:4px;
    }

    .card-subtitle{
        font-size:11px;
        line-height:1.2;
    }

    .card-value{
        font-size:14px;
        font-weight:600;
    }

    .page-title{
        font-size:16px;
        line-height:1.3;
    }

    .tab{
        font-size:12px;
        padding:8px 12px;
    }

    .filter-area span{
        font-size:12px;
    }

    .search{
        font-size:13px;
    }

    th,
    td{
        font-size:12px;
        padding:10px;
    }
}

.dashboard-loader{
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:rgba(255,255,255,0.9);
    display:none;
    justify-content:center;
    align-items:center;
    flex-direction:column;
    z-index:99999;
}

.loader-spinner{
    width:50px;
    height:50px;
    border:4px solid #e5e7eb;
    border-top:4px solid #2563eb;
    border-radius:50%;
    animation:spin 1s linear infinite;
}

.loader-text{
    margin-top:15px;
    font-size:16px;
    font-weight:500;
    color:#343a40;
}

@keyframes spin{
    0%{
        transform:rotate(0deg);
    }
    100%{
        transform:rotate(360deg);
    }
}

</style>
@endpush

@section('mainContent')

<div class="container-fluid">

    <div class="container">

    <div class="dashboard-heading">
        DASHBOARD
    </div>

        <div class="cards">

           <div class="card">
    <div class="card-content">
        <div class="card-left">
            <div class="card-label">Learners</div>
            <div class="card-subtitle">Number of Learners</div>
        </div>

        <div class="card-value" id="learners"></div>
    </div>
</div>


<div class="card">
    <div class="card-content">
        <div class="card-left">
            <div class="card-label">Courses</div>
            <div class="card-subtitle">Number of Courses</div>
        </div>

        <div class="card-value" id="courses"></div>
    </div>
</div>


<div class="card">
    <div class="card-content">
        <div class="card-left">
            <div class="card-label">Enrolled</div>
            <div class="card-subtitle">Total Enrolled</div>
        </div>

        <div class="card-value" id="enrolled"></div>
    </div>
</div>


<div class="card">
    <div class="card-content">
        <div class="card-left">
            <div class="card-label">Subjects</div>
            <div class="card-subtitle">Number of Subjects</div>
        </div>

        <div class="card-value" id="subjects"></div>
    </div>
</div>

        </div>

        <!-- Navigation -->
        <div class="top-nav">

            <div class="tabs">

                <div class="tab active" data-tab="category">
                    Category Wise
                </div>

                <div class="tab" data-tab="management">
                    Management Wise
                </div>

                <div class="tab" data-tab="country">
                    Country Wise
                </div>

                <div class="tab" data-tab="business">
                    Business Unit Wise
                </div>

            </div>

            <div class="filter-area">

                <span>
                    Current Filter:
                    <strong id="currentFilter">Weekly</strong>
                </span>

                <div class="filter-wrapper">

                    <button class="filter-btn" id="filterButton">
                        <i class="bi bi-funnel"></i> Filter
                    </button>

                    <div class="filter-popup" id="filterPopup">

                        <div class="filter-tabs">

                            <button class="filter-tab active" data-filter="Weekly">
                                Weekly
                            </button>

                            <button class="filter-tab" data-filter="Monthly">
                                Monthly
                            </button>

                            <button class="filter-tab" data-filter="Custom">
                                Custom
                            </button>

                        </div>

                        <div class="date-row" id="customDateRow" style="display:none;">
                            <input type="date" id="fromDate">
                            <input type="date" id="toDate">
                        </div>

                        <button class="apply-btn" id="applyFilterBtn">
                            Apply Filter
                        </button>

                    </div>

                </div>

            </div>

        </div>

        <!-- Title -->
         <div class="panel">
        <div class="title-row">

            <div class="page-title" id="pageTitle">
                Category Wise Course Completion
            </div>

            <div class="toggle">

                <button id="tableBtn" class="active">
                    <i class="bi bi-table"></i> Table
                </button>

                <button id="chartBtn">
                    <i class="bi bi-bar-chart"></i> Chart
                </button>

            </div>

        </div>

        <!-- Panel -->
        

            <div class="toolbar">

                <input
                    type="text"
                    id="searchInput"
                    class="search"
                    placeholder="Search..."
                >

                <button
                    id="exportBtn"
                    class="export-btn"
                    onclick="exportCSV()">

                    <i class="bi bi-download"></i>
                    Export CSV

                </button>

            </div>

<div id="tableView">
    <div class="table-wrapper">
        <table>
            <thead>
                <tr id="tableHead"></tr>
            </thead>
            <tbody id="tableBody"></tbody>
        </table>
    </div>
</div>

            <div id="chartView">
                <canvas id="dashboardChart"></canvas>
            </div>

        </div>

    </div>

</div>
<div id="dashboardLoader" class="dashboard-loader">
    <div class="loader-spinner"></div>
    <div class="loader-text">Loading Dashboard...</div>
</div>
@endsection


@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>


    const dashboardData = {

    summary:{
        learners:12480,
        courses:348,
        enrolled:28440,
        subjects:96
    },

    category:[
        {
            name:"",
            uniqueReach:0,
            totalReaches:0,
            totalTime:"0 hrs",
            enrolled:0,
            completion:0,
            date:""
        },
    ],

    management:[
        {
            name:"",
            uniqueReach:0,
            totalReaches:0,
            totalTime:"0 hrs",
            enrolled:0,
            completion:0,
            date:""
        },
    ],

    country:[
        {
            name:"",
            uniqueReach:3450,
            totalReaches:8920,
            totalTime:"6234 hrs",
            enrolled:4120,
            completion:83.8,
            date:"2026-06-02"
        },
        
    ],

    business:[
        {
            name:"Engineering & Technology",
            uniqueReach:2890,
            totalReaches:6780,
            totalTime:"5234 hrs",
            enrolled:3450,
            completion:83.8,
            date:"2026-06-04"
        },
    ]
};


    

let currentTab = "category";
let chart;



/* KPI Cards */

// document.getElementById("learners").innerText =
// dashboardData.summary.learners.toLocaleString();

// document.getElementById("courses").innerText =
// dashboardData.summary.courses.toLocaleString();

// document.getElementById("enrolled").innerText =
// dashboardData.summary.enrolled.toLocaleString();

// document.getElementById("subjects").innerText =
// dashboardData.summary.subjects.toLocaleString();


let filteredRows = [];
let filterApplied = false;

/* TABLE */

function renderTable() {

  const rows = filterApplied
    ? filteredRows
    : dashboardData[currentTab];

    document.getElementById("tableHead").innerHTML = `
        <th>Name</th>
        <th>Unique Reach</th>
        <th>Total Reaches</th>
        <th>Total Time</th>
        <th>Total Enrolled</th>
        <th>Completion Rate (%)</th>
    `;

    if(rows.length === 0){
    document.getElementById("tableBody").innerHTML = `
        <tr>
            <td colspan="6" style="text-align:center;padding:40px;">
                No data found
            </td>
        </tr>
    `;
    return;
}


    document.getElementById("tableBody").innerHTML =
        rows.map(row => `
        <tr>
            <td>${row.name}</td>
            <td>${row.uniqueReach}</td>
            <td>${row.totalReaches}</td>
            <td>${row.totalTime}</td>
            <td>${row.enrolled}</td>
            <td>${row.completion}%</td>
        </tr>
        `).join("");

}


function renderChart(){

   

const rows = filterApplied
    ? filteredRows
    : dashboardData[currentTab];

     if(rows.length === 0){
    document.getElementById("chartView").innerHTML =
        '<div style="padding:40px;text-align:center;">No data found</div>';
    return;
}

    const labels = rows.map(x => x.name);

    const uniqueReach = rows.map(x => x.uniqueReach);
    const totalReaches = rows.map(x => x.totalReaches);
    const enrolled = rows.map(x => x.enrolled);
    const completion = rows.map(x => x.completion);

    if(chart){
        chart.destroy();
    }

    chart = new Chart(
        document.getElementById("dashboardChart"),
        {
            type: "bar",

            data: {
                labels: labels,

                datasets: [

                    {
                        label: "Unique Reach",
                        data: uniqueReach,
                        backgroundColor: "#3b82f6",
                        borderRadius: 0,
                        barPercentage: 0.8,
                        categoryPercentage: 0.7
                    },

                    {
                        label: "Total Reaches",
                        data: totalReaches,
                        backgroundColor: "#8b5cf6",
                        borderRadius: 0,
                        barPercentage: 0.8,
                        categoryPercentage: 0.7
                    },

                    {
                        label: "Total Enrolled",
                        data: enrolled,
                        backgroundColor: "#10b981",
                        borderRadius: 0,
                        barPercentage: 0.8,
                        categoryPercentage: 0.7
                    },

                   {
                        label: "Completion Rate (%)",
                        data: completion,
                        backgroundColor: "#f59e0b",
                        borderRadius: 0,
                        yAxisID: "y1",
                        barPercentage: 0.8,
                        categoryPercentage: 0.7
                   }

                ]
            },

            options: {

                responsive: true,
                maintainAspectRatio: false,

                plugins: {

                    legend: {
                        position: "bottom",
                        labels: {
                            boxWidth: 16,
                            usePointStyle: false,
                            font: {
                                size: 13
                            }
                        }
                    },

                    tooltip: {

                        backgroundColor: "#fff",
                        titleColor: "#111827",
                        bodyColor: "#111827",
                        borderColor: "#d1d5db",
                        borderWidth: 1,

                        callbacks: {

                            label: function(context){

                                return context.dataset.label +
                                       " : " +
                                       context.raw;
                            }
                        }
                    }
                },

                scales: {

    x: {

        ticks: {
            maxRotation: 50,
            minRotation: 50
        },

        grid: {
            color: "#d1d5db",
            borderDash: [4,4]
        }
    },

    y: {

        beginAtZero: true,

        title:{
            display:true,
            text:"Reach / Enrolled"
        },

        grid: {
            color: "#d1d5db",
            borderDash: [4,4]
        }
    },

    y1: {

        beginAtZero:true,
        max:100,

        position:"right",

        title:{
            display:true,
            text:"Completion %"
        },

        grid:{
            drawOnChartArea:false
        }
    }
}
            }
        }
    );
}

/* TABS */

document.querySelectorAll(".tab").forEach(tab => {

    tab.addEventListener("click", () => {

        document
            .querySelectorAll(".tab")
            .forEach(t => t.classList.remove("active"));

        tab.classList.add("active");

        currentTab = tab.dataset.tab;

        document.getElementById("pageTitle").innerText =
            tab.innerText + " Course Completion";

        document.getElementById("filterPopup").style.display = "none";

        // IMPORTANT
        if(filterApplied){

            document.getElementById("applyFilterBtn").click();

        }else{

            renderTable();

            if(
                document.getElementById("chartView").style.display === "block"
            ){
                renderChart();
            }
        }

    });

});


/* SEARCH */

document
.getElementById("searchInput")
.addEventListener("keyup",function(){

    const value =
    this.value.toLowerCase();

    document
    .querySelectorAll("#tableBody tr")
    .forEach(row => {

        row.style.display =
        row.innerText
        .toLowerCase()
        .includes(value)
        ? ""
        : "none";

    });

});


/* EXPORT CSV */

function exportCSV(){

 const rows = filterApplied
    ? filteredRows
    : dashboardData[currentTab];
    let csv =
    "Name,Unique Reach,Total Reaches,Total Time,Total Enrolled,Completion Rate\n";

    rows.forEach(r => {

        csv +=
        `${r.name},${r.uniqueReach},${r.totalReaches},${r.totalTime},${r.enrolled},${r.completion}\n`;

    });

    const blob =
    new Blob([csv],{
        type:"text/csv"
    });

    const link =
    document.createElement("a");

    link.href =
    URL.createObjectURL(blob);

    link.download =
    currentTab + ".csv";

    link.click();
}


/* TABLE / CHART TOGGLE */

document
.getElementById("tableBtn")
.addEventListener("click",() => {

document
.getElementById("tableView")
.style.display = "block";

document
.getElementById("chartView")
.style.display = "none";

document
.getElementById("searchInput")
.style.display = "block";

document
.getElementById("exportBtn")
.style.display = "block";

    document
    .getElementById("tableBtn")
    .classList.add("active");

    document
    .getElementById("chartBtn")
    .classList.remove("active");

});

document
.getElementById("chartBtn")
.addEventListener("click",() => {

document
.getElementById("tableView")
.style.display = "none";

document
.getElementById("chartView")
.style.display = "block";

document
.getElementById("searchInput")
.style.display = "none";

document
.getElementById("exportBtn")
.style.display = "none";

    document
    .getElementById("chartBtn")
    .classList.add("active");

    document
    .getElementById("tableBtn")
    .classList.remove("active");

    renderChart();

});

/* FILTER TYPE */

let selectedFilter = "Weekly";

document.querySelectorAll(".filter-tab").forEach(btn => {

    btn.addEventListener("click", function () {

        document
        .querySelectorAll(".filter-tab")
        .forEach(x => x.classList.remove("active"));

        this.classList.add("active");

        selectedFilter = this.dataset.filter;

        const customDateRow =
            document.getElementById("customDateRow");

        if(selectedFilter === "Custom"){
            customDateRow.style.display = "flex";
        }else{
            customDateRow.style.display = "none";
        }

        // Update label immediately
        if(selectedFilter !== "Custom"){
            document.getElementById("currentFilter").innerText =
                selectedFilter;
        }

    });

});


function applyCurrentFilter() {

    const allRows = dashboardData[currentTab];
    const today = new Date();

    filteredRows = [];
    filterApplied = true;

    if(selectedFilter === "Weekly"){

        const weekAgo = new Date(today);
        weekAgo.setDate(today.getDate() - 7);

        filteredRows = allRows.filter(row =>
            new Date(row.date) >= weekAgo &&
            new Date(row.date) <= today
        );

        document.getElementById("currentFilter").innerText = "Weekly";
    }

    else if(selectedFilter === "Monthly"){

        const monthAgo = new Date(today);
        monthAgo.setMonth(today.getMonth() - 1);

        filteredRows = allRows.filter(row =>
            new Date(row.date) >= monthAgo &&
            new Date(row.date) <= today
        );

        document.getElementById("currentFilter").innerText = "Monthly";
    }

    else if(selectedFilter === "Custom") {

        const from = document.getElementById("fromDate").value;
        const to = document.getElementById("toDate").value;

        if(!from || !to){
            filteredRows = [];
            renderTable();
            return;
        }

        filteredRows = allRows.filter(row =>
            row.date >= from &&
            row.date <= to
        );

        document.getElementById("currentFilter").innerText =
            `${from} → ${to}`;
    }
   

    renderTable();

    if(document.getElementById("chartView").style.display === "block"){
        renderChart();
    }
}


document
.getElementById("applyFilterBtn")
.addEventListener("click", function(){

    applyCurrentFilter();

    document.getElementById("filterPopup").style.display = "none";
});


/* FILTER POPUP */

document
.getElementById("filterButton")
.addEventListener("click",() => {

    const popup =
    document.getElementById("filterPopup");

    popup.style.display =
    popup.style.display === "block"
    ? "none"
    : "block";

});

// filteredRows = [];
// filterApplied = false;


renderTable();


document
.getElementById("tableView")
.style.display = "block";

document
.getElementById("chartView")
.style.display = "none";


document.addEventListener('click', function(e){

    const popup = document.getElementById('filterPopup');
    const btn = document.getElementById('filterButton');

    if(
        !popup.contains(e.target) &&
        !btn.contains(e.target)
    ){
        popup.style.display = 'none';
    }
});

    

    const dashboardUrl = "{{ tenantRoute('tenant.getDashboardData') }}";
    
    let categoryTable;
    let managementTable;
    let countryTable;
    let businessUnitTable;

    $(document).ready(function () {

        loadDashboardData('7');
        // loadDashboardData('30');
        // loadDashboardData('custom', startDate, endDate);

        // Category Search
        $('#categorySearch').on('keyup', function () {

            if (categoryTable) {
                categoryTable.search(this.value).draw();
            }

        });

        // Management Search
        $('#managementSearch').on('keyup', function () {

            if (managementTable) {
                managementTable.search(this.value).draw();
            }

        });

        // Date Filter
        $('#reportRange').change(function () {

            let value = $(this).val();

            if (value === 'custom') {

                $('#customDateRange').removeClass('d-none');

            } else {

                $('#customDateRange').addClass('d-none');

                loadCategoryReport(value);
            }

        });

        // Apply Custom Date
        $('#applyDateFilter').click(function () {

            let startDate = $('#startDate').val();
            let endDate = $('#endDate').val();

            if (!startDate || !endDate) {
                alert('Please select Start Date and End Date');
                return;
            }

            loadCategoryReport(
                'custom',
                startDate,
                endDate
            );

        });

        function loadDashboardData(type = '7', startDate = '', endDate = '') {
            $('#dashboardLoader').css('display', 'flex');
            $.ajax({
                type: 'GET',
                url: dashboardUrl,
                data: {
                    type: type,
                    start_date: startDate,
                    end_date: endDate
                },

                success: function(data) {

                    dashboardData.summary = {
                        learners: data.student || 0,
                        courses: data.totalCourses || 0,
                        enrolled: data.totalEnrolled || 0,
                        subjects: data.totalSubjects || 0
                    };

                    dashboardData.category = (data.categoryReports || []).map(item => {

                    let categoryName = item.category_name;

                    try {
                        categoryName = JSON.parse(categoryName).en;
                    } catch(e) {}

                    return {
                        name: categoryName,
                        uniqueReach: item.unique_reach,
                        totalReaches: item.total_reaches,
                        totalTime: item.total_time_spent,
                        enrolled: item.total_enrolled,
                        completion: item.completion_rate,
                        date: item.created_at || ''
                    };
                });

                    dashboardData.management = (data.managementReports || []).map(item => {

                    let managementName = item.management_name;

                    try {
                        managementName = JSON.parse(managementName).en;
                    } catch(e) {}

                    return {
                        name: managementName,
                        uniqueReach: item.unique_reach,
                        totalReaches: item.total_reaches,
                        totalTime: item.total_time_spent,
                        enrolled: item.total_enrolled,
                        completion: item.completion_rate,
                        date: item.created_at || ''
                    };
                });

                    dashboardData.country = (data.countryReports || []).map(item => {
                        let countryName = item.country_name;

                        try {
                            countryName = JSON.parse(countryName).en;
                        } catch(e) {}

                        return {
                            name: countryName,
                            uniqueReach: item.unique_reach,
                            totalReaches: item.total_reaches,
                            totalTime: item.total_time_spent,
                            enrolled: item.total_enrolled,
                            completion: item.completion_rate,
                            date: item.created_at || ''
                        };
                    });

                    dashboardData.business = (data.buReports || []).map(item => {
                        let buName = item.bu_name;

                        try {
                            buName = JSON.parse(buName).en;
                        } catch(e) {}

                        return {
                            name: buName,
                            uniqueReach: item.unique_reach,
                            totalReaches: item.total_reaches,
                            totalTime: item.total_time_spent,
                            enrolled: item.total_enrolled,
                            completion: item.completion_rate,
                            date: item.created_at || ''
                        };
                    });

                    filterApplied = false;
                    filteredRows = [];

                    updateSummaryCards();

                    renderTable();

                    if($('#chartView').is(':visible')){
                        renderChart();
                    }
                },

                error: function (xhr) {

                    console.log(xhr.responseText);

                },

                complete: function() {
                    $('#dashboardLoader').hide();
                }
            });

        }

        function updateSummaryCards()
        {
            $('#learners').text(dashboardData.summary.learners || 0);
            $('#courses').text(dashboardData.summary.courses || 0);
            $('#enrolled').text(dashboardData.summary.enrolled || 0);
            $('#subjects').text(dashboardData.summary.subjects || 0);
        }

        function loadCategoryReport(type, startDate = '', endDate = '') {

            $.ajax({
                url: dashboardUrl,
                type: 'GET',
                data: {
                    type: type,
                    start_date: startDate,
                    end_date: endDate
                },
                success: function (response) {

                    // rebuild table

                }
            });

        }

        function buildCategoryTable(reports) {

            let html = '';

            if (reports && reports.length > 0) {

                $.each(reports, function (index, report) {

                    let categoryName = report.category_name || '-';

                    try {

                        let parsed = JSON.parse(categoryName);

                        categoryName =
                            parsed.en ||
                            parsed[Object.keys(parsed)[0]] ||
                            categoryName;

                    } catch (e) {}

                    html += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${categoryName}</td>
                            <td>${report.unique_reach ?? '-'}</td>
                            <td>${report.total_reaches ?? '-'}</td>
                            <td>${report.total_time_spent ?? '-'}</td>
                            <td>${report.total_enrolled ?? '-'}</td>
                            <td>${parseFloat(report.completion_rate || 0).toFixed(2)}%</td>
                        </tr>
                    `;
                });

            } else {

                html = `
                    <tr>
                        <td colspan="7" class="text-center">
                            No Records Found
                        </td>
                    </tr>
                `;
            }

            $('#categoryReportBody').html(html);

            if ($.fn.DataTable.isDataTable('#categoryReportTable')) {
                $('#categoryReportTable').DataTable().destroy();
            }

            $('#datatableButtons').html('');

            categoryTable = $('#categoryReportTable').DataTable({
                destroy: true,
                responsive: true,
                pageLength: 10,
                dom: 'Bfrtip',
                buttons: [
                    'copy',
                    'csv',
                    'excel',
                    'pdf',
                    'print'
                ]
            });

            categoryTable.buttons().container().appendTo('#datatableButtons');

            buildCategoryChart(reports);
        }

         function buildCategoryChart(reports) {

            let labels = [];
            let values = [];

            $.each(reports, function(index, report){

                labels.push(report.category_name);
                values.push(report.total_enrolled);

            });

            const ctx = document.getElementById('categoryChart');

            if(window.categoryChartObj){
                window.categoryChartObj.destroy();
            }

            window.categoryChartObj = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Total Enrolled',
                        data: values
                    }]
                }
            });
        }


        function buildManagementTable(reports) {

            let html = '';

            if (reports && reports.length > 0) {

                $.each(reports, function (index, report) {

                    html += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${report.management_name ?? '-'}</td>
                            <td>${report.unique_reach ?? '-'}</td>
                            <td>${report.total_reaches ?? '-'}</td>
                            <td>${report.total_time_spent ?? '-'}</td>
                            <td>${report.total_enrolled ?? '-'}</td>
                            <td>${parseFloat(report.completion_rate || 0).toFixed(2)}%</td>
                        </tr>
                    `;
                });

            } else {

                html = `
                    <tr>
                        <td colspan="7" class="text-center">
                            No Records Found
                        </td>
                    </tr>
                `;
            }

            $('#managementReportBody').html(html);

            if ($.fn.DataTable.isDataTable('#managementReportTable')) {
                $('#managementReportTable').DataTable().destroy();
            }

            $('#ManagementButtons').html('');

            managementTable = $('#managementReportTable').DataTable({
                destroy: true,
                responsive: true,
                pageLength: 10,
                dom: 'Bfrtip',
                buttons: [
                    'copy',
                    'csv',
                    'excel',
                    'pdf',
                    'print'
                ]
            });

            managementTable.buttons().container().appendTo('#ManagementButtons');
        }

        function buildCountryTable(reports) 
        {

            let html = '';

            if (reports && reports.length > 0) {

                $.each(reports, function (index, report) {

                    html += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${report.country_name ?? '-'}</td>
                            <td>${report.unique_reach ?? '-'}</td>
                            <td>${report.total_reaches ?? '-'}</td>
                            <td>${report.total_time_spent ?? '-'}</td>
                            <td>${report.total_enrolled ?? '-'}</td>
                            <td>${parseFloat(report.completion_rate || 0).toFixed(2)}%</td>
                        </tr>
                    `;
                });

            } else {

                html = `
                    <tr>
                        <td colspan="7" class="text-center">
                            No Records Found
                        </td>
                    </tr>
                `;
            }

            $('#countryReportBody').html(html);

            if ($.fn.DataTable.isDataTable('#countryReportTable')) {
                $('#countryReportTable').DataTable().destroy();
            }

            $('#CountryButtons').html('');

            countryTable = $('#countryReportTable').DataTable({
                destroy: true,
                responsive: true,
                pageLength: 10,
                dom: 'Bfrtip',
                buttons: [
                    'copy',
                    'csv',
                    'excel',
                    'pdf',
                    'print'
                ]
            });

            countryTable.buttons().container().appendTo('#CountryButtons');
        }

        function buildBusinessUnitTable(reports) 
        {

            let html = '';

            if (reports && reports.length > 0) {

                $.each(reports, function (index, report) {

                    html += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${report.bu_name ?? '-'}</td>
                            <td>${report.unique_reach ?? '-'}</td>
                            <td>${report.total_reaches ?? '-'}</td>
                            <td>${report.total_time_spent ?? '-'}</td>
                            <td>${report.total_enrolled ?? '-'}</td>
                            <td>${parseFloat(report.completion_rate || 0).toFixed(2)}%</td>
                        </tr>
                    `;
                });

            } else {

                html = `
                    <tr>
                        <td colspan="7" class="text-center">
                            No Records Found
                        </td>
                    </tr>
                `;
            }

            $('#businessUnitReportBody').html(html);

            if ($.fn.DataTable.isDataTable('#businessUnitReportTable')) {
                $('#businessUnitReportTable').DataTable().destroy();
            }

            $('#businessUnitButtons').html('');

            businessUnitTable = $('#businessUnitReportTable').DataTable({
                destroy: true,
                responsive: true,
                pageLength: 10,
                dom: 'Bfrtip',
                buttons: [
                    'copy',
                    'csv',
                    'excel',
                    'pdf',
                    'print'
                ]
            });

            businessUnitTable.buttons().container().appendTo('#businessUnitButtons');
        }

        $('#listViewBtn').click(function () 
        {

                $('#categoryTableContainer').show();
                $('#categoryChartContainer').hide();

                $('#graphViewBtn')
                    .removeClass('btn-primary active')
                    .addClass('btn-outline-primary');

                $(this)
                    .removeClass('btn-outline-primary')
                    .addClass('btn-primary active');
            });

            $('#graphViewBtn').click(function () {

                $('#categoryTableContainer').hide();
                $('#categoryChartContainer').show();

                $('#listViewBtn')
                    .removeClass('btn-primary active')
                    .addClass('btn-outline-primary');

                $(this)
                    .removeClass('btn-outline-primary')
                    .addClass('btn-primary active');
        });

       
    });
</script>

@endpush