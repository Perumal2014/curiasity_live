@php
    $approve = false;
    if (Auth::user()->role_id != 2) {
    $approve = true;
    } else {
    $courseApproval = Settings('course_approval');
    if ($courseApproval == 0) {
    $approve = true;
    }
    }

@endphp
@if ($approve)
    @php
        if (permissionCheck('course.status_update')) {
        $status_enable_eisable = "status_enable_disable";
        } else {
        $status_enable_eisable = "";
        }
        $checked = $query->status == 1 ? "checked" : "";
    @endphp

    <label class="switch_toggle">
        <input type="checkbox"
       class="course_status"
       id="course_status_{{ $query->id }}"
       data-table="courses"
       value="{{ $query->id }}" {{$checked}}><i class="slider round"></i></label>
@else
    {{$query->status == 1 ? trans('common.Approved') : trans('common.Pending')}}
@endif


<script type="text/javascript">
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
   $(document).on('change', '.course_status', function () {

    let id = $(this).val();
    let table = $(this).data('table');
    let status = $(this).is(':checked') ? 1 : 0;

    $.ajax({
        url: "{{ route('tenant.statusEnableDisable', request()->route('tenant_slug')) }}",
        type: "POST",
        data: {
            id: id,
            table: table,
            status: status
        },
        success: function (response) {
            console.log(response);
            if (response.success) {
                alert(response.success);
            } else {
                alert(response.error);
            }
        },
        error: function (xhr) {
            console.log(xhr.responseText);
        }
    });

});

</script>


