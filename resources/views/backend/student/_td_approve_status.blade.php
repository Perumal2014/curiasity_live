@if ($query->refund != 1 && permissionCheck('course.delete'))

    <div class="dropdown CRM_dropdown">

        @if($query->status == 0)

            <button
                class="btn btn-warning"
                type="button"
                data-id="{{ $query->id }}"
            >
                {{ trans('common.Pending') }}
            </button>

        @elseif($query->status == 1)

            <button
                class="btn btn-success"
                type="button"
                data-id="{{ $query->id }}"
            >
                {{ trans('common.Approved') }}
            </button>

        @else

            <button
                class="btn btn-danger"
                type="button"
                data-id="{{ $query->id }}"
            >
                {{ trans('common.Rejected') }}
            </button>

        @endif

    </div>

@endif