<div>
    <div>
        <div>

            @if (!$columnSelect || ($columnSelect && $this->isColumnSelectEnabled('title')))
                <x-livewire-tables::table.cell>
                    {{$row->title}}
                </x-livewire-tables::table.cell>
            @endif



            @if (!$columnSelect || ($columnSelect && $this->isColumnSelectEnabled('days')))

                <x-livewire-tables::table.cell>
                    {{$row->days!=0?$row->days.' Days':''}}
                </x-livewire-tables::table.cell>
            @endif

            @if (!$columnSelect || ($columnSelect && $this->isColumnSelectEnabled('created_by')))

                <x-livewire-tables::table.cell>
                    {{$row->createdBy->name}}
                </x-livewire-tables::table.cell>
            @endif

            @if (!$columnSelect || ($columnSelect && $this->isColumnSelectEnabled('created_at')))

                <x-livewire-tables::table.cell>
                    {{showDate($row->created_at)}}
                </x-livewire-tables::table.cell>
            @endif

        </div>

    </div>

</div>
