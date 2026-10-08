(function ($) {
    "use strict";

    const isAdmin = $('#is_admin').val();

    dataTableOptions.serverSide = true
    dataTableOptions.processing = true
    dataTableOptions.ajax = {
        url: $('#organization_sales_report_route').val(),
        data: function (d) {
            d.f_type = $('#f_type').val(),
                d.f_user = $('#f_user').val(),
                d.f_topic = $('#f_topic').val(),
                d.f_date = $('.date_range_input').val()
        }
    };
    dataTableOptions.columns = [
        {data: 'DT_RowIndex', name: 'id'},
        {data: 'created_at', name: 'created_at'},
        {data: 'student', name: 'user.name'},
        {data: 'organization', name: 'organization', visible: isAdmin},
        {data: 'topic_title', name: 'course.title'},
        {data: 'topic_type', name: 'course.type'},
        {data: 'purchase_price', name: 'purchase_price'},
        {data: 'discount_amount', name: 'discount_amount'},
        {data: 'reveune', name: 'reveune'},
        {data: 'admin_profit', name: 'admin_profit'},
    ];
    dataTableOptions = updateColumnExportOption(dataTableOptions, [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]);

    let table = $('#lms_table').DataTable(dataTableOptions);

    $(document).ready(function () {


        $(document).on('change', '#f_type, #f_topic, #f_user', function (event) {
            event.preventDefault();
            resetAfterChange();
        });

        $(document).on('click', '.reset_btn', function (event) {
            event.preventDefault();
            $('#f_type').val('').niceSelect('update');
            $('#f_user').val('').niceSelect('update');
            $('#f_topic').val('').niceSelect('update');
            $('.date_range_input').val('');
            resetAfterChange();
        });


        function resetAfterChange() {
            table.ajax.reload();
        }


    });

})(jQuery);

