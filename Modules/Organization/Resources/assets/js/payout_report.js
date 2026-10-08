(function ($) {
    "use strict";
    const isAdmin = $('#is_admin').val();

    dataTableOptions.serverSide = true
    dataTableOptions.processing = true
    dataTableOptions.ajax = {
        url: $('#organization_payout_report_route').val(),
        data: function (d) {
            d.f_status = $('#f_status').val(),
                d.f_users = $('#f_users').val(),
                d.f_date = $('.date_range_input').val()
        }
    };
    dataTableOptions.columns = [{data: 'DT_RowIndex', name: 'id'}, {data: 'amount', name: 'amount'}, {
        data: 'created_at',
        name: 'created_at'
    }, {data: 'payment_type', name: 'payment_type'}, {
        data: 'user',
        name: 'user.name',
        visible: isAdmin
    }, {data: 'payout_account', name: 'payout_account', visible: isAdmin}, {
        data: 'action',
        name: 'action',
        visible: isAdmin
    },];

    let table = $('#lms_table').DataTable(dataTableOptions);

    $(document).ready(function () {

        $(document).on('change', '#f_status, #f_users', function (event) {
            event.preventDefault();
            resetAfterChange();
        });

        $(document).on('click', '.reset_btn', function (event) {
            event.preventDefault();
            $('#f_status').val('').niceSelect('update');
            $('#f_users').val('').niceSelect('update');
            $('.date_range_input').val('');
            resetAfterChange();
        });

        $(document).on('click', '#request_payout_btn', function (event) {
            event.preventDefault();
            $('#request_payout_modal').modal('show');
        });

        $(document).on('click', '.user_payout_account', function (event) {
            event.preventDefault();
            let id = $(this).data('id');
            let url = $('#user_payout_account_route').val();
            url = url.replace(':id', id);
            $.ajax({
                url: url, type: "GET", success: function (response) {
                    $('#append_html').html(response);
                    $('#user_payout_account_modal').modal('show');
                }, error: function (response) {
                    toastr.error(response.responseJSON.error);
                }
            });

        });

        $(document).on('click', '.payout_completed', function (event) {
            event.preventDefault();
            let id = $(this).data('id');
            $('button[data-confirm="confirmModal"]').attr('data-id', id);
            $('#confirmModal').modal('show');

        });

        $(document).on('click', 'button[data-confirm="confirmModal"]', function (event) {
            event.preventDefault();
            let id = $(this).data('id');
            $('#confirmModal').modal('hide');
            let url = $('#organization_payout_completed_route').val();
            url = url.replace(':id', id);
            $.ajax({
                url: url, type: "GET", success: function (response) {
                    toastr.success(response.msg);
                    resetAfterChange();
                }, error: function (response) {
                    toastr.error(response.responseJSON.error);
                }
            });

        });


        function resetAfterChange() {
            table.ajax.reload();
        }


    });

})(jQuery);

