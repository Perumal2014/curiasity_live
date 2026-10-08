@extends('backend.master')
@php
    $table_name='tenant_galleries';
@endphp
@section('table')
    {{$table_name}}
@endsection
@section('mainContent')
    @include("backend.partials.alertMessage")
    @php
        $LanguageList = getLanguageList();
    @endphp
    {!! generateBreadcrumb() !!}
    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-xxl-3">
                    <div class="white-box mb_30  student-details header-menu">
                        <div class="box_header common_table_header">
                            <div class="main-title d-flex mb-0">
                                <h3 class="mb-0"> @if(!isset($edit))
                                        {{__('setting.Add Tenant Gallery') }}
                                    @else
                                        {{__('setting.Update Tenant Gallery')}}
                                    @endif</h3>
                                @if(isset($edit))
                                    @if (permissionCheck('course.category.store'))
                                        <a href="{{route('course.category')}}"
                                           class="primary-btn small fix-gr-bg ml-4 d-flex justify-content-center align-items-center"
                                           style="line-height: 25px;"
                                           title="{{__('courses.Add New')}}">+</a>
                                    @endif
                                @endif
                            </div>
                        </div>

                        <div class="row pt-0">
                            @if(isModuleActive('FrontendMultiLang'))
                                <ul class="nav nav-tabs no-bottom-border  mt-sm-md-20 mb-10 ml-3"
                                    role="tablist">
                                    @foreach ($LanguageList as $key => $language)
                                        <li class="nav-item">
                                            <a class="nav-link  @if (auth()->user()->language_code == $language->code) active @endif"
                                               href="#element{{$language->code}}"
                                               role="tab"
                                               data-bs-toggle="tab">{{ $language->native }}  </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>


                        @if (isset($edit))
                            <form action="{{route('gallery.update')}}" method="POST"
                                  id="gallery-form"
                                  name="gallery-form" enctype="multipart/form-data">
                                <input type="hidden" name="id"
                                       value="{{$edit->id}}">
                                @else
                                    
                                        <form action="{{route('gallery.store') }}" method="POST"
                                              id="gallery-form" name="gallery-form"
                                              enctype="multipart/form-data">
                                    
                                @endif
                                            @csrf

                                            
                                            <div class="row">
                                                <div class="col-lg-12 mt-10">
                                                    <div class="mb-15">
                                                        <x-upload-file
                                                            name="image"
                                                            type="image"
                                                            :value="isset($edit)?$edit->image_url:''"
                                                            id="image"
                                                            
                                                            label="{{ __('setting.Gallery Image') }}"
                                                            note="{{__('courses.Recommended size 200px x 200px')}}"/>
                                                    </div>
                                                </div>
                                                <div class="col-xl-12">
                                                    <div class="primary_input mb-25">
                                                        <label class="primary_input_label"
                                                               for="status">{{ __('courses.Status') }}</label>
                                                        <select class="primary_select mb-25" name="status"
                                                                id="status"
                                                        >
                                                            <option
                                                                value="1" {{isset($edit)?($edit->status==1?'selected':''):''}}>{{__('common.Active') }}</option>
                                                            <option
                                                                value="0" {{isset($edit)?($edit->status==0?'selected':''):''}}>{{__('common.Inactive') }}</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                

                                                
                                                
                                                <div class="col-lg-12 text-center">
                                                    <div class="d-flex justify-content-center pt_20">
                                                        <button type="submit"
                                                                class="primary-btn semi_large fix-gr-bg"
                                                                data-bs-toggle="tooltip" title="Gallery"
                                                                id="save_button_parent">
                                                            <i class=" fa fa-check "></i>
                                                            @if(!isset($edit))
                                                                {{ __('common.Save') }}
                                                            @else
                                                                {{ __('common.Update') }}
                                                            @endif
                                                        </button>


                                                    </div>
                                                </div>
                                            </div>

                                        </form>
                    </div>
                </div>
                <div class="col-xxl-9">
                    <div class="white-box">
                        <div class="box_header common_table_header">
                            <div class="main-title d-flex flex-wrap mb-0">
                                <h3 class="mb-0" id="page_title">{{__('courses.Category List')}}</h3>
                            </div>
                        </div>
                        <div class="  QA_section QA_section_heading_custom check_box_table">
                            <div class="QA_table ">
                                <!-- table-responsive -->
                                <div class="">
                                    <table id="lms_table" class="table table-data">
                                        <thead>
                                        <tr>
                                            <th scope="col">{{ __('common.SL') }}</th>
                                            <th scope="col">{{ __('courses.Thumbnail Image') }}</th>
                                            <th scope="col">{{ __('common.Status') }}</th>
                                            <th scope="col">{{ __('common.Action') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($galleries as $key => $gallery)
                                            <tr>
                                                <td>{{++$key}}</td>
                                                
                                                <td>
                                                    <div>
                                                        <img style="width: 70px !important;"
                                                             src="@if(isset($gallery->image_url)){{url(@$gallery->image_url)}}@endif"
                                                             alt=""
                                                             class="img img-responsive m-2">
                                                    </div>
                                                </td>

                                                
                                                <td class="nowrap">
                                                    @php
                                                        if(isModuleActive('Organization')){

                                                            $org_id = $category->organization_id;
                                                        }else{
                                                            $org_id = null;
                                                        }

                                                    @endphp
                                                    <x-backend.status :org="$org_id" :id="$gallery->id"
                                                                      :status="$gallery->status"
                                                                      :route="'gallery.update'"></x-backend.status>

                                                </td>

                                                <td>
                                                    @php
                                                        $hasPermission =true;
                                                    @endphp
                                                    <div class="dropdown CRM_dropdown">
                                                        <button class="btn btn-secondary dropdown-toggle" type="button"
                                                                id="dropdownMenu1{{@$gallery->id}}"
                                                                data-bs-toggle="dropdown"
                                                                aria-haspopup="true"
                                                                aria-expanded="false">
                                                            {{ __('common.Select') }}
                                                        </button>


                                                        <div class="dropdown-menu dropdown-menu-right"
                                                             aria-labelledby="dropdownMenu1{{@$gallery->id}}">

                                                                <a class="dropdown-item edit_brand"
                                                                   href="{{route('gallery.edit',$gallery->id)}}">{{__('common.Edit')}}</a>
                                                           
                                                            
                                                                <a onclick="confirm_modal('{{route('gallery.delete', $gallery->id)}}');"
                                                                   class="dropdown-item edit_brand">{{__('common.Delete')}}</a>
                                                            
                                                        </div>
                                                    </div>

                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <input type="hidden" name="status_route" class="status_route" value="{{ route('gallery.update') }}">
    @include('backend.partials.delete_modal')
@endsection
@push('scripts')
    <script type="application/javascript">


        dataTableOptions = updateColumnExportOption(dataTableOptions, [0, 1, 2, 3, 4, ]);

        let table = $('#lms_table').DataTable(dataTableOptions);


    </script>
 @endpush
`   1