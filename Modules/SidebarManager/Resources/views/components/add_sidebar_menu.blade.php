@php
    $LanguageList = getLanguageList();
@endphp

<div class="modal-dialog modal-dialog-centered student-details">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">{{__('common.Add Menu')}}</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">

            <form method="POST" id="menuAddForm" enctype="multipart/form-data">
            @csrf    
                <!-- <div class="row pt-0">
                   
                        <ul class="nav nav-tabs no-bottom-border  mt-sm-md-20 mb-10 ms-3"
                            role="tablist">
                            
                                <li class="nav-item">
                                    <a class="nav-link active"
                                       href="#element2"
                                       role="tab"
                                       data-bs-toggle="tab"> </a>
                                </li>
                            
                        </ul>
                   
                </div> -->
                <div class="tab-content">
                    
                        <div role="tabpanel"
                             class="tab-pane fade show active "
                             id="element2">
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="primary_input mb-25">
                                        <label class="primary_input_label"
                                               for="">{{ __('common.Name') }}

                                            <span
                                                class="textdanger">*</span></label>
                                        <input class="primary_input_field" placeholder="" type="text" id=""
                                               name="name"
                                               >
                                    </div>
                                </div>
                            </div>
                        </div>
                    
                </div>
                
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="primary_input mb-25">
                                <label class="primary_input_label"
                                       for="">{{__('common.Route')}}
                                </label>
                                <input class="primary_input_field" placeholder="" type="text" id="route"
                                       name="route"
                                       >
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-12">
                            <div class="primary_input mb-25">
                                <label class="primary_input_label"
                                       for="">{{__('common.Menu Type')}}
                                </label>
                                <select class="primary_select mb-25" name="type" id="menu_type">
                                    <option value="1">{{__('common.Main Menu')}}</option>
                                    <option value="2">{{__('common.Sub Menu')}}</option>
                                    <option value="3">{{__('common.Action')}}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-12">
                            <div class="primary_input mb-25">
                                <label class="primary_input_label"
                                       for="">{{__('common.Page Type')}}
                                </label>
                                <select class="primary_select mb-25" name="backend" id="menu_type">
                                    <option value="1">{{__('common.Backend')}}</option>
                                    <option value="0">{{__('common.Frontend')}}</option>
                                    
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-12">
                            <div class="primary_input mb-25">
                                <label class="primary_input_label"
                                       for="">{{__('common.Parent Route')}}
                                </label>
                                <select class="select2 primary_select mb-25" name="parent_route" id="parent_route">
                                    <option value="">{{__('common.Select Parent Route')}}</option>
                                    @foreach($parentRoutes as $route)
                                        <option value="{{ $route }}">{{ $route }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-12">
                            <div class="primary_input mb-25">
                                <label class="primary_input_label"
                                       for="">{{__('common.Ecommerce')}}
                                </label>
                                <select class="primary_select mb-25" name="ecommerce" id="ecommerce">
                                    <option value="1">{{__('common.Yes')}}</option>
                                    <option value="0">{{__('common.No')}}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-12">
                            <div class="primary_input mb-25">
                                <label class="primary_input_label"
                                       for="">{{__('common.Icon')}}
                                </label>
                                <input class="primary_input_field" placeholder="" type="text" id="menuIcon"
                                       name="icon"
                                       >
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-12">
                            <div class="primary_input mb-25">
                                <label class="primary_input_label"
                                       for="">{{__('common.Menu Status')}}
                                </label>
                                <select class="primary_select mb-25" name="menu_status" id="menu_status">
                                    <option value="1">{{__('common.Active')}}</option>
                                    <option value="0">{{__('common.InActive')}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="primary_input mb-25">
                                <label class="primary_input_label"
                                       for="">{{__('common.Old Name')}}
                                </label>
                                <input class="primary_input_field" placeholder="" type="text" id="old_name"
                                       name="old_name"
                                       >
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-12">
                            <div class="primary_input mb-25">
                                <label class="primary_input_label"
                                       for="">{{__('common.Old Menu Type')}}
                                </label>
                                <select class="primary_select mb-25" name="old_type" id="menu_type">
                                    <option value="1">{{__('common.Main Menu')}}</option>
                                    <option value="2">{{__('common.Sub Menu')}}</option>
                                    <option value="3">{{__('common.Action')}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="primary_input mb-25">
                                <label class="primary_input_label"
                                       for="">{{__('common.Old Parent Route')}}
                                </label>
                                <input class="primary_input_field" placeholder="" type="text" id="old_parent_route"
                                       name="old_parent_route"
                                       >
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-12">
                            <div class="primary_input mb-25">
                                <label class="primary_input_label"
                                       for="">{{__('common.Tenant Type')}}
                                </label>
                                <select class="primary_select mb-25" name="tenant_id" id="tenant_id">
                                    @foreach($tenantTypes as $tenantType)
                                        <option value="{{$tenantType->id}}">{{$tenantType->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-12">
                            <div class="primary_input mb-25">
                                <label class="primary_input_label"
                                       for="">{{__('common.Sections')}}
                                </label>
                                <select class="primary_select mb-25" name="section_id" id="section_id">
                                    @foreach($sections as $section)
                                        <option value="{{$section->id}}">{{$section->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                <div class=" d-flex justify-content-between">
                    <button type="button" class="primary-btn tr-bg"
                            data-bs-dismiss="modal">@lang('common.Cancel')</button>

                    <button class="primary-btn fix-gr-bg" id="menuAdd"
                            type="button">@lang('common.Submit')</button>
                </div>
            </form>

        </div>
    </div>
    @if(request()->get('type')!='module')
        <script>
            $(document).on('mouseover', 'body', function () {
                $('#menuIcon').iconpicker({
                    animation: true,
                    hideOnSelect: true
                });
            });
        </script>
    @endif
</div>

