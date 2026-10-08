<div class="dropdown CRM_dropdown">
    <button class="btn btn-secondary dropdown-toggle" type="button"
            id="dropdownMenu2" data-bs-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false">
        {{trans('common.Action')}}
    </button>
    <div class="dropdown-menu dropdown-menu-right"
         aria-labelledby="dropdownMenu2">

        @if (Auth::user()->tenant_id == CORPORATE)
            @if(Auth::user()->role_id == 11)
                <a href="{{route('addLearningPathCourse', [$query->id])}}" class="dropdown-item">{{trans('student.Add Course')}}</a>
                <a href="{{route('editLearningPlan', [$query->id])}}" class="dropdown-item">{{trans('student.Edit')}}</a>
                <a href="{{route('bulk_enroll_upload', [$query->id])}}" class="dropdown-item">{{trans('student.View')}}</a>
            @endif
        @endif
    </div>
</div>


