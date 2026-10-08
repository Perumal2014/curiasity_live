<div class="dropdown CRM_dropdown">
    <button class="btn btn-secondary dropdown-toggle" type="button"
            id="dropdownMenu1"
            data-bs-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false">
        {{ __('common.Select') }}
    </button>
    <div class="dropdown-menu dropdown-menu-right"
         aria-labelledby="dropdownMenu1">
        @if(permissionCheck('organization.payout.completed') && $action_flag)
            <a class="dropdown-item payout_completed" data-id="{{$row->id}}">{{__('organization.completed')}}</a>
        @endif
    </div>
</div>
