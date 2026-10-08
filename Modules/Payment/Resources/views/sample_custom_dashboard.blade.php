@extends('backend.master')

@push('styles')
<link rel="stylesheet" href="{{ asset('public/backend/css/student_list.css') }}">

<style>
.report-section {
    margin-bottom: 30px;
}

.filter-header,
.report-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

.filter-checkbox {
    display: flex;
    align-items: center;
    gap: 20px;
    margin: 0;
}

.filter-checkbox label {
    display: flex;
    align-items: center;
    gap: 6px;
    margin: 0;
    font-weight: 500;
    white-space: nowrap;
}

/* .filter-checkbox{
        display:flex;
        align-items:center;
        gap:20px;
        margin-top:38px;
        flex-wrap:wrap;
    }

    .filter-checkbox label{
        margin-bottom:0;
        font-weight:500;
        cursor:pointer;
    } */

.action-btns {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.graphView {
    padding: 20px;
    background: #fff;
}

.select2-container {
    width: 100% !important;
}



#addSectionBtn {
    margin-top: 20px;
}

.deleteRow {
    border: none;
    background: #ff4d4f;
    color: #fff;
    padding: 4px 10px;
    border-radius: 4px;
    cursor: pointer;
}

a.ms-selectall.global {
    display: none !important;
}

.group_value+.ms-options-wrap>button {
    border: 1px solid #eff2f7 !important;
    border-radius: 6px !important;
    background: #fff !important;
    padding: 0 18px !important;
    font-size: 14px !important;
    color: #495057 !important;
    box-shadow: none !important;
}

.group_value+.ms-options-wrap>button span {
    color: #6c757d !important;
    line-height: 54px !important;
}

.group_value+.ms-options-wrap>button:after {
    top: 18px !important;
    right: 18px !important;
}

.group_value+.ms-options-wrap>.ms-options {
    border: 1px solid #eff2f7 !important;
    border-radius: 6px !important;
    margin-top: 5px !important;
    box-shadow: 0 4px 20px rgba(0, 0, 0, .08) !important;
}

.ms-search input {
    height: 42px !important;
    border: 1px solid #d9dee8 !important;
    border-radius: 4px !important;
}

.ms-options ul li {
    padding: 6px 0 !important;
}

.ms-options ul li label {
    font-size: 12px !important;
    color: #495057 !important;
    line-height: 30px !important;
}
</style>
@endpush

@section('mainContent')

{!! generateBreadcrumb() !!}

<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">

        <div id="sectionsContainer">

            <!-- ===== SINGLE SECTION TEMPLATE ===== -->
            <div class="report-section">

                <!-- FILTER CARD -->
                <div class="white-box">

                    <div class="filter-header">

                        <h3 class="mb-0">
                            Advanced Filter
                        </h3>

                        <div class="action-btns">
                            <button class="listBtn primary-btn small fix-gr-bg">
                                List
                            </button>

                            <button class="graphBtn primary-btn small fix-gr-bg">
                                Graph
                            </button>

                            <button class="removeSection primary-btn small">
                                Remove
                            </button>
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-lg-4">

                            <div class="primary_input mb-25">
                                <label class="primary_input_label">
                                    Group Type
                                </label>

                                <select class="primary_select group_type">
                                    <option value="">Select</option>
                                    <option value="1">Profiles</option>
                                    <option value="2">Business Unit</option>
                                    <option value="3">Department</option>
                                </select>
                            </div>

                        </div>

                        <div class="col-lg-4">

                            <div class="primary_input mb-25">
                                <label class="primary_input_label">
                                    Group Value
                                </label>

                                <select class="group_value multypol_check_select active mb-15" multiple>
                                </select>
                            </div>

                        </div>

                        <div class="col-lg-4">

                            <input type="button" class="primary-btn fix-gr-bg filterButton" value="Apply Filter">

                        </div>

                        <!-- <div class="col-lg-2">

                            <div class="filter-checkbox">

                                <label>
                                    <input type="checkbox"
                                           class="timeCheck"
                                           checked>
                                    Time
                                </label>

                                <label>
                                    <input type="checkbox"
                                           class="reachCheck"
                                           checked>
                                    Reach
                                </label>

                                <label>
                                    <input type="checkbox"
                                           class="courseCheck"
                                           checked>
                                    Courses
                                </label>

                            </div>

                        </div> -->

                    </div>

                </div>

                <!-- REPORT CARD -->
                <div class="white-box">

                    <div class="box_header common_table_header">

                        <div class="main-title d-flex justify-content-between align-items-center w-100">

                            <h3 class="mb-0">
                                Report
                            </h3>

                            <div class="filter-checkbox">
                                <label>
                                    <input type="checkbox" class="timeCheck" checked>
                                    Time
                                </label>

                                <label>
                                    <input type="checkbox" class="reachCheck" checked>
                                    Reach
                                </label>

                                <label>
                                    <input type="checkbox" class="courseCheck" checked>
                                    Courses
                                </label>
                            </div>


                        </div>

                    </div>

                    <!-- LIST VIEW -->
                    <div class="listView">

                        <div class="QA_section QA_section_heading_custom check_box_table">
                            <div class="QA_table">

                                <table class="table Crm_table_active3">

                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Course</th>
                                            <th>Date</th>
                                            <th class="col-time">Time</th>
                                            <th class="col-reach">Reach</th>
                                            <th class="col-courses">Courses</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>

                                    <tbody class="tableBody">
                                    </tbody>

                                </table>

                            </div>
                        </div>

                    </div>

                    <!-- GRAPH VIEW -->
                    <div class="graphView" style="display:none;">
                        <canvas></canvas>
                    </div>

                </div>

            </div>

        </div>

        <button id="addSectionBtn" class="primary-btn fix-gr-bg">
            <i class="ti-plus"></i>
            Add Section
        </button>

    </div>
</section>

@endsection

@push('scripts')

<!-- SELECT2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- CHART -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
$(function() {

    // =====================================================
    // INIT FIRST SECTION
    // =====================================================

    initSection($('.report-section').first());

    // =====================================================
    // INIT SECTION
    // =====================================================

    function initSection(section) {

        section.find('.group_value').multiselect({
            columns: 1,
            placeholder: 'Select Value',
            search: true,
            selectAll: true
        });

        updateColumns(section);
    }

    // =====================================================
    // ADD SECTION
    // =====================================================

    $('#addSectionBtn').click(function() {

        let clone = $('.report-section').first().clone(false);

        clone.find('.ms-options-wrap').remove();

        clone.find('.group_value')
            .empty()
            .removeClass('jqmsLoaded');

        clone.find('.tableBody').empty();

        clone.find('input[type="checkbox"]')
            .prop('checked', true);

        clone.find('.graphView').hide();
        clone.find('.listView').show();

        clone.find('canvas').remove();
        clone.find('.graphView').html('<canvas></canvas>');

        $('#sectionsContainer').append(clone);

        initSection(clone);
    });

    // =====================================================
    // REMOVE SECTION
    // =====================================================

    $(document).on('click', '.removeSection', function() {

        if ($('.report-section').length > 1) {

            $(this).closest('.report-section').remove();

        }

    });

    // =====================================================
    // GROUP TYPE CHANGE
    // =====================================================

    $(document).on('change', '.group_type', function() {

        let section = $(this).closest('.report-section');

        let type = $(this).val();

        let select = section.find('.group_value');

        let baseUrl =
            window.location.origin +
            window.location.pathname.split('/admin')[0];

        $.ajax({

            url: `${baseUrl}/admin/get-group-values`,

            type: "GET",

            data: {
                type: type
            },

            success: function(response) {

                select.empty();

                $.each(response, function(key, value) {
                    select.append(
                        `<option value="${value.id}">${value.name}</option>`
                    );
                });

                select.multiselect('reload');
            }

        });

    });

    //     select.next('.ms-options-wrap').remove();
    // select.multiselect({
    //     columns:1,
    //     placeholder:'Select Value',
    //     search:true,
    //     selectAll:true
    // });

    // =====================================================
    // GROUP VALUE CHANGE
    // =====================================================

    $(document).on('change', '.group_value', function() {

        let section = $(this).closest('.report-section');

        let groupValues = $(this).val();

        let groupType = section.find('.group_type').val();

        if (!groupValues || groupValues.length == 0) {

            section.find('.tableBody').empty();

            return;
        }

        let baseUrl =
            window.location.origin +
            window.location.pathname.split('/admin')[0];

        $.ajax({

            url: `${baseUrl}/admin/custom-dashboard-data`,

            type: "POST",

            data: {

                group_type: groupType,

                group_values: groupValues,

                _token: $('meta[name="csrf-token"]').attr('content')

            },

            success: function(response) {

                console.log(response);

                let tbody = section.find('.tableBody');

                tbody.empty();

                if (response.length == 0) {

                    tbody.append(`
                        <tr>
                            <td colspan="7" class="text-center">
                                No Data Found
                            </td>
                        </tr>
                    `);

                    return;
                }

                $.each(response, function(index, row) {

                    tbody.append(`
                        <tr>
                            <td>${index + 1}</td>
                            <td>${row.course}</td>
                            <td>${row.date}</td>
                            <td class="col-time">${row.time}</td>
                            <td class="col-reach">${row.reach}</td>
                            <td class="col-courses">
                                ${row.total_courses}
                            </td>
                            <td>
                                <button class="deleteRow btn btn-danger btn-sm">
                                    X
                                </button>
                            </td>
                        </tr>
                    `);

                });

                updateColumns(section);

                renderGraph(section, response);

            }

        });

    });

    // =====================================================
    // COLUMN TOGGLE
    // =====================================================

    $(document).on(
        'change',
        '.timeCheck, .reachCheck, .courseCheck',
        function() {

            let section = $(this).closest('.report-section');

            updateColumns(section);

        }
    );

    function updateColumns(section) {

        section.find('.col-time')
            .toggle(section.find('.timeCheck').is(':checked'));

        section.find('.col-reach')
            .toggle(section.find('.reachCheck').is(':checked'));

        section.find('.col-courses')
            .toggle(section.find('.courseCheck').is(':checked'));

    }

    // =====================================================
    // LIST VIEW
    // =====================================================

    $(document).on('click', '.listBtn', function() {

        let section = $(this).closest('.report-section');

        section.find('.listView').show();

        section.find('.graphView').hide();

    });

    // =====================================================
    // GRAPH VIEW
    // =====================================================

    $(document).on('click', '.graphBtn', function() {

        let section = $(this).closest('.report-section');

        section.find('.listView').hide();

        section.find('.graphView').show();

    });

    // =====================================================
    // GRAPH RENDER
    // =====================================================

    function renderGraph(section, response) {

        let labels = [];

        let values = [];

        $.each(response, function(index, row) {

            labels.push(row.course);

            values.push(row.reach);

        });

        let canvas = section.find('canvas')[0];

        if (section.data('chart')) {

            section.data('chart').destroy();

        }

        let chart = new Chart(canvas, {

            type: 'bar',

            data: {

                labels: labels,

                datasets: [{
                    label: 'Reach',
                    data: values
                }]
            }

        });

        section.data('chart', chart);

    }

    // =====================================================
    // DELETE ROW
    // =====================================================

    $(document).on('click', '.deleteRow', function() {

        $(this).closest('tr').remove();

    });

    // =====================================================
    // EXPORT CSV
    // =====================================================

    // $(document).on('click', '.exportBtn', function () {

    //     let rows = $(this)
    //         .closest('.report-section')
    //         .find('tbody tr');

    //     let csv = [];

    //     rows.each(function () {

    //         let cols = [];

    //         $(this).find('td').each(function () {

    //             cols.push($(this).text().trim());

    //         });

    //         csv.push(cols.join(','));

    //     });

    //     let blob = new Blob([csv.join('\n')]);

    //     let link = document.createElement("a");

    //     link.href = URL.createObjectURL(blob);

    //     link.download = "report.csv";

    //     link.click();

    // });

    $(document).on('click', '.filterButton', function() {

        $(this).trigger('change');
    });

});
</script>



@endpush