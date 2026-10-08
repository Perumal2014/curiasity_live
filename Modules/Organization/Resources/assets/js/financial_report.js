(function ($) {
    "use strict";
    dataTableOptions.serverSide = true
    dataTableOptions.processing = true

    dataTableOptions.ajax = {
        url: $('#organization_financial_report_route').val(),
        data: function (d) {
            d.f_type = $('#f_type').val(),
                d.f_status = $('#f_status').val(),
                d.f_date = $('.date_range_input').val()

        }
    };

    dataTableOptions.columns = [
        {data: 'DT_RowIndex', name: 'id'},
        {data: 'amount', name: 'amount'},
        {data: 'type', name: 'type'},
        {data: 'created_at', name: 'created_at'},
        {data: 'status', name: 'status'},
        {data: 'description', name: 'description', searchable: false}
    ];
    dataTableOptions = updateColumnExportOption(dataTableOptions, [0, 1, 2, 3, 4, 5]);

    let table = $('#lms_table').DataTable(dataTableOptions);

    $(document).ready(function () {


        $(document).on('change', '#f_type, #f_status', function (event) {
            event.preventDefault();
            resetAfterChange();
        });

        $(document).on('click', '.reset_btn', function (event) {
            event.preventDefault();
            $('#f_type').val('').niceSelect('update');
            $('#f_status').val('').niceSelect('update');
            $('.date_range_input').val('');
            resetAfterChange();
        });


        function resetAfterChange() {
            table.ajax.reload();
        }


    });

})(jQuery);

