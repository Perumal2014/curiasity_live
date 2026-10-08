@extends('backend.master')
@push('styles')
    <link rel="stylesheet" href="{{ asset('public/backend/css/daterangepicker.css') }}">
@endpush
@section('mainContent')

    {!! generateBreadcrumb() !!}

    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid p-0">
            @if(!$is_admin)
                <div class="row mb-15">
                    <x-backend.card-component :title="trans('organization.total_income')"
                                              :value="showPrice($total_income)"></x-backend.card-component>
                    <x-backend.card-component :title="trans('organization.ready_to_payout')"
                                              :value="showPrice($ready_to_payout)"></x-backend.card-component>
                    <x-backend.card-component :title="trans('organization.request_payout')"
                                              :value="showPrice($request_payout)"></x-backend.card-component>
                    <x-backend.card-component :title="trans('organization.total_payout')"
                                              :value="showPrice($total_payout)"></x-backend.card-component>
                </div>
            @endif

            <div class="row justify-content-center">


                <div class="col-lg-12">
                    <div class="white_box mb_20">
                        <div class="white_box_tittle list_header main-title mb-0">
                            <h3 class="mb-20">{{__('courses.Advanced Filter')}} </h3>
                        </div>
                        <form action="#" method="POST">
                            @csrf
                            <div class="row">


                                <div class="col-lg-4 mb-3">
                                    <label class="primary_input_label" for="f_status">{{__('common.Status')}}</label>
                                    <select class="primary_select" name="f_status" id="f_status">
                                        <option value="">{{__('common.Select One')}}</option>
                                        <option
                                            value="{{\Modules\Organization\Entities\OrganizationFinance::$payment_pending}}">{{\Modules\Organization\Entities\OrganizationFinance::$payment_pending}}</option>
                                        <option
                                            value="{{\Modules\Organization\Entities\OrganizationFinance::$payment_completed}}">{{\Modules\Organization\Entities\OrganizationFinance::$payment_completed}}</option>
                                        {{--                                        <option value="{{\Modules\Organization\Entities\OrganizationFinance::$payment_canceled}}">{{\Modules\Organization\Entities\OrganizationFinance::$payment_canceled}}</option>--}}
                                    </select>
                                </div>

                                @if($is_admin)
                                    <div class="col-lg-4 mb-3">
                                        <label class="primary_input_label" for="f_users">{{__('common.User')}}</label>
                                        <select class="primary_select" name="f_users" id="f_users">
                                            <option value="">{{__('common.Select One')}}</option>
                                            @foreach($users as $user)
                                                <option value="{{$user->id}}">{{$user->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif


                                <div class="col-lg-4 mb-3">
                                    <div class="primary_input mb-15 date_range">
                                        <div class="primary_datepicker_input filter">
                                            <label class="primary_input_label" for="">{{__('common.Date')}}</label>
                                            <div class="g-0 input-right-icon">
                                                <input placeholder="{{__('common.Date')}}" readonly
                                                       class="primary_input_field date_range_input" type="text"
                                                       name="date_range_filter" value="">
                                                <button class="" type="button">
                                                    <i class="fa fa-refresh" id="reset-date-filter"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-lg-3  ">
                                    <div class="search_course_btn">
                                        <a class="primary-btn radius_30px fix-gr-bg reset_btn mt-20 w-fit">{{__('common.Reset')}} </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-12">
                    <div class="white-box">
                        <div class="row">
                            <div class="col-12">
                                <div class="box_header common_table_header">
                                    <div class="main-title d-md-flex">
                                        <h3 class="mb-0 mr-30 mb_xs_15px mb_sm_20px" id="page_title">{{ trans('organization.payout_history') }}</h3>
                                        <ul class="d-flex">
                                            @if (permissionCheck('organization.payout.store'))
                                                <li>
                                                    <a id="request_payout_btn" class="primary-btn radius_30px fix-gr-bg"
                                                       href="javascript:void(0)">
                                                        {{__('organization.request_payout')}}
                                                    </a>
                                                </li>
                                            @endif
                                        </ul>

                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="QA_section QA_section_heading_custom check_box_table">
                                    <div class="QA_table ">
                                        <!-- table-responsive -->
                                        <div class="">
                                            <table id="lms_table" class="table Crm_table_active3">
                                                <thead>
                                                <tr>
                                                    <th scope="col">{{__('common.SL')}}</th>
                                                    <th scope="col">{{__('common.Amount')}}</th>
                                                    <th scope="col">{{__('common.Date')}}</th>
                                                    <th scope="col">{{__('common.Status')}}</th>
                                                    <th scope="col">{{__('common.User')}}</th>
                                                    <th scope="col">{{__('setting.Payout Account')}}</th>
                                                    <th scope="col">{{__('common.Action')}}</th>
                                                </tr>
                                                </thead>
                                                <tbody>

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

            <input type="hidden" value="{{route('organization.payout.datatable')}}"
                   id="organization_payout_report_route">

            <input type="hidden" value="{{route('admin.user_payout_accounts.show',":id")}}"
                   id="user_payout_account_route">

            <input type="hidden" value="{{route('organization.payout.completed',":id")}}"
                   id="organization_payout_completed_route">
            <input type="hidden" value="{{$is_admin}}" id="is_admin">
            @include('organization::financial.payout.request_payout_modal')
            <div class="modal fade admin-query" id="confirmModal">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">{{__('organization.confirmation')}}  </h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"><i class="ti-close "></i>
                            </button>
                        </div>

                        <div class="modal-body">
                            <div class="text-center">
                                <h4>Are you sure you want to confirm?</h4>
                            </div>

                            <div class="mt-40 d-flex justify-content-between">
                                <button type="button" class="primary-btn tr-bg"
                                        data-bs-dismiss="modal">{{__('common.Cancel')}}</button>
                                <button data-confirm="confirmModal" class="primary-btn fix-gr-bg"
                                        type="button">{{__('common.Yes')}}</button>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="append_html"></div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="{{asset('public/backend/js/daterangepicker.min.js')}}"></script>
    <script src="{{asset('public/modules/common/date_range_init.js')}}"></script>
    <script src="{{asset('Modules/Organization/Resources/assets/js/payout_report.js')}}"></script>
@endpush


