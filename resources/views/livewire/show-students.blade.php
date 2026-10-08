<div>
    <style>

        .QA_section.check_box_table .QA_table .table thead tr th:first-child, .QA_section.check_box_table .QA_table .table thead tr th {
            padding-left: 12px !important;
        }

        .QA_section .QA_table .table thead th {
            vertical-align: middle !important;
        }

    </style>
      <td class=""><input type="checkbox" id="student{{$row->id}}"
               data-student="{{$row->id}}"
               class=" singleStudent common-checkbox student{{$row->id}}"
               value="">
        <label for="student{{$row->id}}" class="mt-2"></label>
    </td>
    <x-livewire-tables::table.cell>
        {{ ++$index*request()->input('page',1)  }}
    </x-livewire-tables::table.cell>
    <x-livewire-tables::table.cell>
        {{$row->name}}
    </x-livewire-tables::table.cell>

    <x-livewire-tables::table.cell>
        {{$row->org_chart_code}}
    </x-livewire-tables::table.cell>

 <x-livewire-tables::table.cell>
        {{$row->org_position_code}}
    </x-livewire-tables::table.cell>


 <x-livewire-tables::table.cell>
        {{$row->employee_id}}
    </x-livewire-tables::table.cell>

 <x-livewire-tables::table.cell>
        {{$row->email}}
    </x-livewire-tables::table.cell>



 <x-livewire-tables::table.cell>
        {{$row->dob}}
    </x-livewire-tables::table.cell>


 <x-livewire-tables::table.cell>
        {{$row->gender}}
    </x-livewire-tables::table.cell>

 <x-livewire-tables::table.cell>
     {{showDate($row->start_working_date)}}
    </x-livewire-tables::table.cell>


 <x-livewire-tables::table.cell>
     {{$row->phone}}
 </x-livewire-tables::table.cell>


 <x-livewire-tables::table.cell>
     @if($row->status==1)
         Active
     @else
         <span class="text-danger">Deactivate</span>
     @endif
 </x-livewire-tables::table.cell>

</div>
