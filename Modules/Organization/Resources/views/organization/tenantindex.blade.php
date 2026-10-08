@extends('backend.master')
@push('styles')

@endpush

@section('table')
    @php
        $table_name='users';
    @endphp
    {{$table_name}}
@stop
@section('mainContent')

    {!! generateBreadcrumb() !!}

    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid p-0">

            <div class="white-box">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <div class="box_header common_table_header">
                            <div class="main-title d-md-flex">
                                <h3 class="mb-0 mr-30 mb_xs_15px mb_sm_20px" id="page_title">{{__('organization.tenant')}} {{__('common.List')}}</h3>
                                @if (permissionCheck('organization.tenantStore'))
                                    <ul class="d-flex">
                                        <li>
                                            <a class="primary-btn radius_30px fix-gr-bg"
                                               href="{{route('organization.tenantStore')}}"><i
                                                    class="ti-plus"></i>{{__('common.Add')}} {{__('organization.tenant')}}
                                            </a>

                                        </li>
                                    </ul>
                                @endif

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
                                            <th scope="col">{{__('common.Slug')}}</th>
                                            <th scope="col">{{__('common.Name')}}</th>
                                            <th scope="col">{{__('common.Email')}}</th>
                                            <th scope="col">{{__('common.Phone')}}</th>
                                            <th scope="col">{{__('common.Tenant Type')}}</th>
                                            <th scope="col">{{__('common.Verified Status')}}</th>
                                            <th scope="col">{{__('common.Plan')}}</th>
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

                    <div class="modal fade admin-query" id="deleteOrganization">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <form action="{{route('organization.destroy')}}" method="POST">
                                    @csrf
                                    <div class="modal-header">
                                        <h4 class="modal-title">{{__('common.Delete')}} {{__('organization.organization')}} </h4>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"><i
                                                class="ti-close "></i></button>
                                    </div>

                                    <div class="modal-body">
                                        <div class="text-center">

                                            <h4>{{__('common.Are you sure to delete ?')}}</h4>
                                        </div>
                                        <input type="hidden" name="id" value="" id="organizationDeleteId">

                                        <div class="mt-40 d-flex justify-content-between">
                                            <button type="button" class="primary-btn tr-bg"
                                                    data-bs-dismiss="modal">{{__('common.Cancel')}}</button>
                                            <button class="primary-btn fix-gr-bg"
                                                    type="submit">{{__('common.Delete')}}</button>

                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <input type="hidden" id="getAllTenants" value="{{ route('organization.alltenant.data', ['tenant_slug' => request()->route('tenant_slug')]) }}">

    @endsection

    @push('scripts')
    @if ($errors->any())
        <script>
            @if(Session::has('type'))
            @if(Session::get('type')=="store")
            $('#add_organization').modal('show');
            @else
            $('#editOrganization').modal('show');
            @endif
            @endif
        </script>
    @endif


    <script src="{{asset('public/backend/js/tenant_list.js')}}{{assetVersion()}}"></script>
@endpush


