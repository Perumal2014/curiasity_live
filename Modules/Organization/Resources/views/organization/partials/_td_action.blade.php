<div class="dropdown CRM_dropdown">
    <button class="btn btn-secondary dropdown-toggle" type="button"
            id="dropdownMenu2" data-bs-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false">
        {{trans('common.Action')}}
    </button>
    <div class="dropdown-menu dropdown-menu-right"
         aria-labelledby="dropdownMenu2">
            
        @if (permissionCheck('organization.edit'))
            <a href="{{route('organization.update',$query->id)}}"
               class="dropdown-item">
                {{trans('common.Edit')}}
            </a>
        @endif
        @if (permissionCheck('organization.destroy'))
            <button class="dropdown-item deleteOrganization"
                    data-id="{{$query->id}}"
                    type="button"> {{trans('common.Delete')}}
            </button>
        @endif
    </div>
</div>
