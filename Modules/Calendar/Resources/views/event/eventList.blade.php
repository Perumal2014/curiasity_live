@extends('backend.master')
@push('styles')
    <link rel="stylesheet" href="{{asset('public/backend/css/class.css')}}"/>
    <style>
        .link_icon {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #f0f1f2;
            background: #f7f9fc;
        }
    </style>
@endpush
@php
    $table_name=' ';
@endphp
@section('table')
    {{$table_name}}
@endsection
@section('mainContent')

    {{generateBreadcrumb()}}

    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid p-0">

            <div class="white-box">
                <div class="row m-0">
                    <div class="col-lg-12 g-0 d-flex gap-3 justify-content-between flex-column flex-sm-row">
                        <div class="main-title">
                            <h3 class="mb-0" id="page_title">{{__('communicate.Event')}} {{__('common.List')}}</h3>
                        </div>

                        <a href="{{route('event_create')}}" class="primary-btn small fix-gr-bg w-fit">
                            <span class="ti-plus pe-2"></span>
                            {{__('common.Add')}}
                        </a>
                    </div>
                </div>
                <div class="row">

                    <div class="col-lg-12">


                        <div class="row mt-20">
                            <div class="col-lg-12">
                                <div class="QA_section QA_section_heading_custom check_box_table">
                                    <div class="QA_table ">
                                        <!-- table-responsive -->
                                        <div class="">
                                            <table id="lms_table" class="table Crm_table_active3">
                                                <thead>
                                                <tr>
                                                    <th>{{__('common.SL')}}</th>
                                                    <th>{{__('communicate.Event')}} {{__('common.Title')}}</th>
                                                    <th>{{__('communicate.For Whom')}}</th>
                                                    <th>{{__('communicate.From Date')}}</th>
                                                    <th>{{__('communicate.To Date')}}</th>
                                                    <th>{{__('communicate.Location')}}</th>
                                                    <th>{{__('common.Action')}}</th>
                                                </tr>
                                                </thead>

                                                <tbody>
                                                @if(isset($events))
                                                    @foreach($events as $key=>$value)
                                                        <tr>
                                                            <td>{{ $key+1 }}</td>
                                                            <td>{{ @$value->event_title}}</td>
                                                            <td>{{ @$value->for_whom}}</td>

                                                            <td>{{ @$value->from_date != ""? showDate(@$value->from_date):''}}</td>


                                                            <td data-sort="{{strtotime(@$value->to_date)}}">{{$value->to_date != ""? showDate(@$value->to_date):''}}</td>

                                                            <td>{{ @$value->event_location}}</td>

                                                            <td>


                                                                <div class="dropdown CRM_dropdown">
                                                                    <button class="btn btn-secondary dropdown-toggle"
                                                                            type="button"
                                                                            id="dropdownMenu2{{@$value->id }}"
                                                                            data-bs-toggle="dropdown"
                                                                            aria-haspopup="true"
                                                                            aria-expanded="false">
                                                                        {{ __('common.Select') }}
                                                                    </button>
                                                                    <div class="dropdown-menu dropdown-menu-right"
                                                                         aria-labelledby="dropdownMenu2{{@$value->id }}">
                                                                        <a class="dropdown-item" data-bs-toggle="modal"
                                                                           data-bs-target="#view_event{{$value->id}}"
                                                                           href="#">{{__('common.View')}}</a>

                                                                        @if (permissionCheck('event-edit'))
                                                                            <a class="dropdown-item"
                                                                               href="{{route('event-edit',$value->id)}}">{{__('common.Edit')}}</a>
                                                                        @endif
                                                                        @if (permissionCheck('delete-event'))
                                                                            <a data-bs-toggle="modal"
                                                                               data-bs-target="#delete-event{{$value->id}}"
                                                                               href="#"
                                                                               class="dropdown-item">{{__('common.Delete')}}</a>
                                                                        @endif

                                                                        @if($value->uplad_image_file != "")
                                                                            <a class="dropdown-item"
                                                                               href="{{url(@$value->uplad_image_file)}}"
                                                                               download>
                                                                                {{__('common.Download')}} <span
                                                                                    class="pl ti-download"></span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>

                                                        <div class="modal fade admin-query"
                                                             id="view_event{{$value->id}}">
                                                            <div
                                                                class="modal-dialog modal_1000px modal-dialog-centered">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h4 class="modal-title">{{__('communicate.Event')}} {{__('common.View')}}</h4>
                                                                        <button type="button" class="close "
                                                                                data-bs-dismiss="modal">
                                                                            <i class="ti-close "></i>
                                                                        </button>
                                                                    </div>

                                                                    <div class="modal-body">
                                                                        <h3 class="text-center">{{$value->event_title}}</h3>
                                                                        <div class="row">
                                                                            <div class="col-lg-6">
                                                                                {{__('common.Date')}}
                                                                                : {{showDate($value->from_date)}}
                                                                                - {{showDate($value->to_date)}}
                                                                            </div>
                                                                            <div class="col-lg-6 ">
                                                                                @if ($value->url !=null)
                                                                                    <a target="_blank" class="float-end"
                                                                                       href="{{$value->url}}">
                                                                                        <img
                                                                                            src="{{asset('Modules/Communicate/Resources/assets/link_icon.svg')}}"
                                                                                            alt="">
                                                                                    </a>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-lg-6">
                                                                                {{__('common.Time')}}
                                                                                : {{$value->start_time}}
                                                                                - {{$value->end_time}}
                                                                            </div>
                                                                            <div class="col-lg-6">
                                                                                Host : {{$value->host}}
                                                                            </div>
                                                                        </div>
                                                                        <div class="row mt-20">
                                                                            <div class="col-lg-12">
                                                                                <img style="width: 100%; height:auto"
                                                                                     src="{{asset($value->uplad_image_file)}}"
                                                                                     alt="">
                                                                            </div>
                                                                            <div class="col-lg-12 mt-20">
                                                                                {!! $value->event_des !!}
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-lg-12">
                                                                                Event Creator
                                                                                : {{$value->creator->name}}
                                                                            </div>
                                                                        </div>


                                                                        <div class="col-lg-12 text-center pt_15">
                                                                            <div class="d-flex justify-content-center">
                                                                                <button
                                                                                    class="primary-btn semi_large2  fix-gr-bg"
                                                                                    data-bs-dismiss="modal"
                                                                                    type="button"><i
                                                                                        class="ti-check"></i> {{__('common.Close')}}
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="modal fade admin-query"
                                                             id="delete-event{{$value->id}}">
                                                            <div class="modal-dialog modal-dialog-centered">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h4 class="modal-title">{{ __('common.Delete Confirmation') }}</h4>
                                                                        <button type="button" class="btn-close"
                                                                                data-bs-dismiss="modal">
                                                                            <i class="ti-close "></i>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <h3 class="text-center">{{__('common.Are you sure to delete ?')}}</h3>

                                                                        <div class="col-lg-12 text-center">
                                                                            <div
                                                                                class="mt-40 d-flex justify-content-between">
                                                                                <button type="button"
                                                                                        class="primary-btn tr-bg"
                                                                                        data-bs-dismiss="modal">{{__('common.Cancel')}}</button>
                                                                                <a href="{{route('delete-event',$value->id)}}"
                                                                                   class="primary-btn semi_large2 fix-gr-bg">{{__('common.Delete')}}</a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    @endforeach
                                                @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script type="application/javascript">

        console.log(dataTableOptions)
        dataTableOptions = updateColumnExportOption(dataTableOptions, [0, 1, 2, 3, 4, 5]);

        let table = $('#lms_table').DataTable(dataTableOptions);


    </script>
@endpush

