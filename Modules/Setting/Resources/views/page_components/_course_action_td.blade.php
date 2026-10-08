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
        data-title="{{$query->title}}"
        data-description="{{$query->description}}"
        data-link="{{$query->link}}"
        data-start="{{$query->start_date}}"
        data-end="{{$query->end_date}}">
            Edit
        </a>

    </div>
</div>