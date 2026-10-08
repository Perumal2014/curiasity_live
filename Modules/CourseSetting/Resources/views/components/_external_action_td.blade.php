<div class="dropdown CRM_dropdown">
    <button class="btn btn-secondary dropdown-toggle" type="button"
            id="dropdownMenu2" data-bs-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false">
        {{trans('common.Action')}}
    </button>
    <div class="dropdown-menu dropdown-menu-right">
    
        <a href="javascript:void(0);"
        class="dropdown-item edit-announcement"
        data-id="{{$query->id}}"
        data-name="{{$query->name}}"
        data-email_id="{{$query->email_id}}"
        data-mobile_no="{{$query->mobile_no}}"
        data-company_name="{{$query->company_name}}">
            Edit
        </a>

    </div>
</div>