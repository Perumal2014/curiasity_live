$(".toggle-password").click(function () {

    var input = $(this).closest('.input-group').find('input');

    if (input.attr("type") == "password") {
        input.attr("type", "text");
    } else {
        input.attr("type", "password");
    }
});
$(".imgBrowse").change(function (e) {
    e.preventDefault();
    var file = $(this).closest('.primary_file_uploader').find('.imgName');
    var filename = $(this).val().split('\\').pop();
    file.val(filename);
});

$(document).on('click', '.editOrganization', function () {
    let organization_id = $(this).data('item-id');
    let url = $('#url').val();
    url = url + '/admin/get-user-data/' + organization_id
    let token = $('.csrf_token').val();

    $.ajax({
        type: 'POST',
        url: url,
        data: {
            '_token': token,
        },
        success: function (organization) {
            $('#organizationId').val(organization.id);
            $('#organizationName').val(organization.name);
            $('#organizationAbout').summernote("code", organization.about);
            $('#organizationDob').val(organization.dob);
            $('#organizationPhone').val(organization.phone);
            $('#organizationEmail').val(organization.email);
            $('#organizationImage').val(organization.image);
            $('#organizationFacebook').val(organization.facebook);
            $('#organizationTwitter').val(organization.twitter);
            $('#organizationLinkedin').val(organization.linkedin);
            $('#organizationInstragram').val(organization.instagram);
            $("#editOrganization").modal('show');
        },
        error: function (data) {
            toastr.error('Something Went Wrong', 'Error');
        }
    });


});


$(document).on('click', '.deleteOrganization', function () {
    let id = $(this).data('id');
    $('#organizationDeleteId').val(id);
    $("#deleteOrganization").modal('show');
})

$(document).on('click', '#add_organization_btn', function () {
    $('#addName').val('');
    $('#addAbout').html('');
    $('#startDate').val('');
    $('#addPhone').val('');
    $('#addEmail').val('');
    $('#addPassword').val('');
    $('#addCpassword').val('');
    $('#addFacebook').val('');
    $('#addTwitter').val('');
    $('#addLinked').val('');
    $('#addInstagram').val('');
});
dataTableOptions.serverSide = true
dataTableOptions.processing = true
dataTableOptions.ajax = $('#getAllTenants').val();
dataTableOptions.columns = [
    {data: 'DT_RowIndex', name: 'id'},
    {data: 'tenant_slug', name: 'tenant_slug'},
    {data: 'tenant_name', name: 'tenant_name'},
    {data: 'tenant_email', name: 'tenant_email'},
    {data: 'phone', name: 'phone'},
    {data: 'tenant_type', name: 'tenant_type'},
    {data: 'verified', name: 'verified'},
    {data: 'plan', name: 'plan'},
    {data: 'action', name: 'action', orderable: false},
]

dataTableOptions = updateColumnExportOption(dataTableOptions, [0, 1, 2, 3, 4, 5]);

let table = $('#lms_table').DataTable(dataTableOptions);
