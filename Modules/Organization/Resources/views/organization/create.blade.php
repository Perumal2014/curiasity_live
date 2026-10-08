@extends('backend.master')
@push('styles')
    <link rel="stylesheet" href="{{asset('public/backend/css/student_list.css')}}"/>

@endpush

@section('mainContent')

    {!! generateBreadcrumb() !!}

    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid p-0">

            <div class=" ">
                <div class="white_box">
                    <form action="{{isset($tenant)?route('organization.tenantupdate',$tenant->id):route('organization.tenantStore')}}"
                          method="POST"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="primary_input mb-25">
                                    <label class="primary_input_label" for="">{{__('common.Slug')}} <strong
                                            class="text-danger">*</strong></label>
                                    <input class="primary_input_field" name="slug" placeholder="-"
                                           id="addSlug"
                                           type="text"
                                           value="{{ old('slug',isset($tenant)?$tenant->tenant_slug:'') }}" {{$errors->first('slug') ? 'autofocus' : ''}}>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="primary_input mb-25">
                                    <label class="primary_input_label" for="">{{__('common.Name')}} <strong
                                            class="text-danger">*</strong></label>
                                    <input class="primary_input_field" name="name" placeholder="-"
                                           id="addName"
                                           type="text"
                                           value="{{ old('name',isset($tenant)?$tenant->tenant_name:'') }}" {{$errors->first('name') ? 'autofocus' : ''}}>
                                </div>
                            </div>

                        </div>
                        
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="primary_input mb-25">
                                    <label class="primary_input_label" for="">{{__('common.Email')}} <strong
                                            class="text-danger">*</strong></label>
                                    <input class="primary_input_field" name="email" placeholder="-"
                                           id="addEmail"
                                           value="{{old('email',isset($tenant)?$tenant->tenant_email : '') }}"
                                           {{$errors->first('email') ? 'autofocus' : ''}}
                                           type="email">
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="primary_input mb-25">
                                    <label class="primary_input_label"
                                           for="">{{__('common.Phone')}} </label>
                                    <input class="primary_input_field"
                                           value="{{old('phone',isset($tenant)?$tenant->phone : '')}}" name="phone"
                                           id="addPhone"
                                           placeholder="-" {{$errors->first('phone') ? 'autofocus' : ''}}
                                           type="number">
                                </div>
                            </div>

                            <div class="col-xl-6">
                                <div class="mb-35">
                                    <x-upload-file
                                            media_id="{{ isset($tenant) ? $tenant->tenant_logo : '' }}"
                                            name="image"
                                            type="image"
                                            label="{{ __('common.Image') }}"
                                            note="{{ __('student.Recommended size') }} {{ translatedNumber('330x400') }}"
                                        />
                                </div>
                            </div>

                            <div class="col-xl-6">
                                <div class="mb-35">
                                    <x-upload-file
                                        media_id="{{ isset($tenant) ? $tenant->tenant_banner : '' }}"
                                        name="banner"
                                        type="image"
                                        label="{{ __('common.Banner') }}"
                                        note="{{ __('student.Recommended size') }} {{ translatedNumber('330x400') }}"
                                    />
                                </div>
                            </div>

                            <div class="col-xl-6">
                                <div class="primary_input mb-25">
                                    <label class="primary_input_label"
                                        for="">{{__('common.Tenant Type')}}
                                    </label>
                                    <select class="primary_select mb-25" name="tenant_id" id="tenant_id">
                                        @foreach($tenantTypes as $tenantType)
                                            <option value="{{ $tenantType->id }}"
                                                @php
                                                    if (isset($tenant) && $tenant->tenant_type == $tenantType->id) echo 'selected';
                                                @endphp>
                                                {{ $tenantType->name }}
                                            </option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>

                            <div class="col-xl-6">
                                <div class="primary_input mb-25">
                                    <label class="primary_input_label"
                                        for="">{{__('common.Plan')}}
                                    </label>
                                    <select class="primary_select mb-25" name="plan_id" id="plan_id">
                                        @foreach($plans as $plan)
                                            <option value="{{ $plan->id }}"
                                                @php
                                                    if (isset($tenant) && $tenant->tenant_plan_id == $plan->id) echo 'selected';
                                                @endphp>
                                                {{ $plan->plan_name }}
                                            </option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>

                           <div class="col-xl-6">
                                <div class="mb-25 d-flex align-items-center">
                                    <input
                                        type="checkbox"
                                        class="form-check-input me-2"
                                        id="verified_status"
                                        name="verified_status"
                                        value="1"
                                        {{ isset($tenant) && $tenant->verified_status == 1 ? 'checked' : '' }}
                                    >
                                    <label class="primary_input_label mb-0" for="verified_status">
                                        {{ __('common.Verified Status') }}
                                    </label>
                                </div>
                            </div>
                        
                       

                        <div class="col-lg-12 text-center  ">
                            <div class="d-flex justify-content-center">
                                <button class="primary-btn semi_large2  fix-gr-bg" id="save_button_parent"
                                        type="submit"><i
                                        class="ti-check"></i>{{ isset($tenant) ? __('common.Update') : __('common.Save') }}
                        {{ __('organization.tenant') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </section>

@endsection

@push('scripts')

    <script src="{{asset('public/backend/js/organization_list.js')}}"></script>
@endpush


