@extends('backend.master')

@section('mainContent')

<div id="sectionsContainer">

    <!-- ===== SINGLE SECTION TEMPLATE ===== -->
    <div class="report-section">

        <!-- FILTER -->
        <div class="white_box p-3 mb-3">

            <h4>Advanced Filter</h4>

            <div class="row align-items-center">

                <div class="col-md-5">
                    <label>Group Type</label>

                    <select class="group_type form-control">
                        <option value="">Select</option>
                        <option value="1">Profiles</option>
                        <option value="2">Business Unit</option>
                        <option value="3">Department</option>
                    </select>
                </div>

                <div class="col-md-5">
                    <label>Group Value</label>

                    <select class="group_value form-control"
                            name="multi_select[]"
                            multiple>
                    </select>
                </div>

                <div class="col-md-2">

                    <div class="d-flex gap-3 mt-4">

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

        </div>

        <!-- HEADER -->
        <div class="white_box p-3">

            <div class="d-flex justify-content-between mb-3">

                <h4>Feedback Report</h4>

                <div>
                    <button class="listBtn btn btn-primary btn-sm">
                        List
                    </button>

                    <button class="graphBtn btn btn-info btn-sm">
                        Graph
                    </button>

                    <button class="exportBtn btn btn-warning btn-sm">
                        Export
                    </button>

                    <button class="removeSection btn btn-danger btn-sm">
                        Remove
                    </button>
                </div>

            </div>

            <!-- LIST -->
            <div class="listView">

                <table class="table table-bordered">

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

                    <tbody class="tableBody"></tbody>

                </table>

            </div>

            <!-- GRAPH -->
            <div class="graphView" style="display:none;">
                <canvas></canvas>
            </div>

        </div>

    </div>

</div>

<br>

<button id="addSectionBtn" class="btn btn-dark">
    + Add Section
</button>

@endsection

@push('scripts')

<!-- SELECT2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css"
      rel="stylesheet" />

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- CHART -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

$(function () {

    // =====================================================
    // INIT FIRST SECTION
    // =====================================================

    initSection($('.report-section').first());

    // =====================================================
    // INIT SECTION
    // =====================================================

    function initSection(section) {

        section.find('.group_value').select2({
            placeholder: "Select Value",
            width: '100%'
        });

        section.find('.tableBody').empty();

        updateColumns(section);
    }

    // =====================================================
    // ADD SECTION
    // =====================================================

    $('#addSectionBtn').click(function () {

        let clone = $('.report-section').first().clone();

        // destroy select2
        clone.find('.group_value').select2('destroy');

        // remove select2 html
        clone.find('.select2').remove();

        // reset fields
        clone.find('select').val('');

        clone.find('.group_value').empty();

        clone.find('.tableBody').empty();

        clone.find('input[type="checkbox"]').prop('checked', true);

        clone.find('.graphView').hide();

        clone.find('.listView').show();

        // reset graph
        clone.find('canvas').remove();

        clone.find('.graphView').html('<canvas></canvas>');

        $('#sectionsContainer').append(clone);

        initSection(clone);

    });

    // =====================================================
    // REMOVE SECTION
    // =====================================================

    $(document).on('click', '.removeSection', function () {

        if ($('.report-section').length > 1) {

            $(this).closest('.report-section').remove();

        }

    });

    // =====================================================
    // GROUP TYPE CHANGE
    // =====================================================

    $(document).on('change', '.group_type', function () {

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

            success: function (response) {

                select.empty();

                $.each(response, function (key, value) {

                    select.append(`
                        <option value="${value.id}">
                            ${value.name}
                        </option>
                    `);

                });

                select.trigger('change');
            }

        });

    });

    // =====================================================
    // GROUP VALUE CHANGE
    // =====================================================

    $(document).on('change', '.group_value', function () {

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

            url: `${baseUrl}/admin/get-feedback-report`,

            type: "POST",

            data: {

                group_type: groupType,

                group_values: groupValues,

                _token: $('meta[name="csrf-token"]').attr('content')

            },

            success: function (response) {

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

                $.each(response, function (index, row) {

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
        function () {

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

    $(document).on('click', '.listBtn', function () {

        let section = $(this).closest('.report-section');

        section.find('.listView').show();

        section.find('.graphView').hide();

    });

    // =====================================================
    // GRAPH VIEW
    // =====================================================

    $(document).on('click', '.graphBtn', function () {

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

        $.each(response, function(index, row){

            labels.push(row.course);

            values.push(row.reach);

        });

        let canvas = section.find('canvas')[0];

        if(section.data('chart')){

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

    $(document).on('click', '.deleteRow', function () {

        $(this).closest('tr').remove();

    });

    // =====================================================
    // EXPORT CSV
    // =====================================================

    $(document).on('click', '.exportBtn', function () {

        let rows = $(this)
            .closest('.report-section')
            .find('tbody tr');

        let csv = [];

        rows.each(function () {

            let cols = [];

            $(this).find('td').each(function () {

                cols.push($(this).text().trim());

            });

            csv.push(cols.join(','));

        });

        let blob = new Blob([csv.join('\n')]);

        let link = document.createElement("a");

        link.href = URL.createObjectURL(blob);

        link.download = "report.csv";

        link.click();

    });

});

</script>

@endpush