<div class="dropdown CRM_dropdown">

    <button class="btn btn-secondary dropdown-toggle"
            type="button"
            id="dropdownMenu2"
            data-bs-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false">
        {{ trans('common.Action') }}
    </button>

    <ul class="dropdown-menu dropdown-menu-end"
        aria-labelledby="dropdownMenu2">

        <li>
            <a class="dropdown-item"
               href="{{ route('staff.learner.edit', $query->id) }}">

                <i class="ti-pencil-alt"></i>
                {{ trans('common.Edit') }}

            </a>
        </li>

        <li>
            <a href="javascript:void(0)"
            class="dropdown-item moveToBenchBtn"
            data-id="{{ $query->id }}">

                <i class="ti-briefcase"></i>
                Move To Bench
            </a>
        </li>

    </ul>

</div>