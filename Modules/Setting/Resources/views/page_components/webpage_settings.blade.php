<div class="main-title mb-20">
    <div class="main-title mb-20">
        <h3 class="mb-0">{{ __('setting.General') }}</h3>
    </div>
    
        <form id="form_data_id" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="tenant_id" value="{{ $page->id }}">

            <div class="General_system_wrap_area">
                <div class="single_system_wrap">
                    <div class=" mb-25">
                        <x-upload-file
                            name="tenant_banner"
                            type="image"
                            media_id="{{isset($page)?$page->tenant_banner_media_id:''}}"
                            label="{{ __('setting.Banner') }}"
                            note="{{__('student.Recommended size')}} ({{translatedNumber('1800x1200')}})"
                        />
                    </div>
                    <div class=" mb-25">
                        <x-upload-file
                            name="tenant_logo"
                            type="image"
                            media_id="{{isset($page)?$page->tenant_logo:''}}"
                            label="{{ __('setting.Header Logo') }}"
                            note="{{__('student.Recommended size')}} ({{translatedNumber('230x90')}})"
                        />
                    </div>
                    
                    <div class=" mb-25">
                        <x-upload-file
                            name="footer_logo"
                            type="image"
                            media_id="{{isset($page->footer_logo)?$page->footer_logo_media_id:''}}"
                            label="{{ __('setting.Footer Logo') }}"
                            note="{{__('student.Recommended size')}} ({{translatedNumber('230x90')}})"
                        />
                    </div>
                    
                    <div class=" mb-25">
                        <x-upload-file
                            name="fav_icon"
                            type="image"
                            media_id="{{isset($page->fav_icon)?$page->fav_icon_media_id:''}}"
                            label="{{ __('setting.Fav Icon') }}"
                            note="{{__('student.Recommended size')}} ({{translatedNumber('16x16')}})"
                        />
                    </div>
                    <div class=" mb-25">
                        <x-upload-file
                            name="brouchure"
                            type="image"
                            media_id="{{isset($page->brouchure)?$page->brouchure_media_id:''}}"
                            label="{{ __('setting.Brouchure') }}"
                            note="{{__('student.Recommended size')}} ({{translatedNumber('16x16')}})"
                        />
                    </div>
                </div>

                <div class="single_system_wrap">
                    <div class="row">
                        <div class="col-xl-6">
                            <div class="primary_input mb-25">
                                <label class="primary_input_label" for="">{{ __('setting.Name') }}</label>
                                <input class="primary_input_field" placeholder="Tenant Name" type="text" id="tenant_name"
                                        name="tenant_name" value="{{ $page->tenant_name }}">
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="primary_input mb-25">
                            <label class="primary_input_label" for="">{{ __('setting.Slogan') }}</label>
                            <input class="primary_input_field" placeholder="Tenant Slogan" type="text" id="tenant_slogan"
                                    name="tenant_slogan" value="{{ $page->tenant_slogan }}">
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="primary_input mb-25">
                            <label class="primary_input_label" for="">{{ __('setting.Experience') }}</label>
                            <input class="primary_input_field" placeholder="Tenant Experience" type="text" id="tenant_experience"
                                    name="tenant_experience" value="{{ $page->tenant_experience }}">
                            </div>
                        </div>

                        <div class="col-xl-6">
                            <div class="primary_input mb-25">
                                <label class="primary_input_label" for="">{{__('setting.Email')}}</label>
                                <input class="primary_input_field" placeholder="demo@infix.com" type="email" id="email"
                                       name="tenant_email" value="{{ $page->tenant_email }}">
                            </div>
                        </div>


                        <div class="col-xl-6">
                            <div class="primary_input mb-25">
                                <label class="primary_input_label" for="">{{__('setting.Phone')}}</label>
                                <input class="primary_input_field" placeholder="-" type="text" id="phone" name="phone"
                                       value="{{ $page->phone }}">
                            </div>
                        </div>

                         <div class="col-xl-6">
                            <div class="primary_input mb-25">
                                <label class="primary_input_label" for="">{{__('setting.Services')}}</label>
                                <input class="primary_input_field" placeholder="-" type="text" id="services" name="tenant_services"
                                       value="{{ $page->tenant_services }}">
                            </div>
                        </div>
                         <div class="col-xl-6">
                            <div class="primary_input mb-25">
                                <label class="primary_input_label" for="">{{__('setting.Twitter')}}</label>
                                <input class="primary_input_field" placeholder="-" type="text" id="twitter" name="twitter_link"
                                       value="{{ $page->twitter_link }}">
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="primary_input mb-25">
                                <label class="primary_input_label" for="">{{__('setting.Facebook')}}</label>
                                <input class="primary_input_field" placeholder="-" type="text" id="facebook" name="facebook_link"
                                       value="{{ $page->facebook_link }}">
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="primary_input mb-25">
                                <label class="primary_input_label" for="">{{__('setting.LinkedIn')}}</label>
                                <input class="primary_input_field" placeholder="-" type="text" id="linkedin" name="linkedin_link"
                                       value="{{ $page->linkedin_link }}">
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="primary_input mb-25">
                                <label class="primary_input_label" for="">{{__('setting.Instagram')}}</label>
                                <input class="primary_input_field" placeholder="-" type="text" id="instagram" name="instagram_link"
                                       value="{{ $page->instagram_link }}">
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="primary_input mb-25">
                                <label class="primary_input_label"
                                       for="">{{ __('setting.Public Student Registration') }}</label>
                                <select class="primary_select mb-25" name="public_student_reg" id="student_reg">
                                    <option value="1"
                                            @if ($page->public_student_reg == 1) selected @endif>{{ __('common.Enable') }}</option>
                                    <option value="0"
                                            @if ($page->public_student_reg == 0) selected @endif>{{ __('common.Disable') }}</option>

                                </select>
                            </div>
                        </div>
                        

                        <div class="col-xl-6">
                            <div class="primary_input mb-25">
                                <label class="primary_input_label"
                                       for="">{{ __('setting.Public Instructor Registration') }}</label>
                                <select class="primary_select mb-25" name="public_teacher_reg" id="instructor_reg">
                                    <option value="1"
                                            @if ($page->public_teacher_reg == 1) selected @endif>{{ __('common.Enable') }}</option>
                                    <option value="0"
                                            @if ($page->public_teacher_reg == 0) selected @endif>{{ __('common.Disable') }}</option>

                                </select>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="primary_input mb-25">
                                <label class="primary_input_label" for="address">{{__('common.Address')}}</label>
                                <textarea class="primary_textarea" placeholder="Address Info" id="address"
                                          cols="30" rows="10"
                                          name="address">{{ $page->address }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @php
                $tooltip = "";
                $hasPermission=false;
                if(!permissionCheck('settings.general_setting_update')){
                    $tooltip = "You have no permission to add";
                }else{
                    $hasPermission=true;
                }

                 if(config('app.demo_mode')){
                     $hasPermission=false;
                    $tooltip =trans('common.For the demo version, you cannot change this');
                }
            @endphp
            <div class="row mt-4 justify-content-center">
                <div class="col-auto">
                    <button type="button"
                            class="primary-btn fix-gr-bg"
                            id="general_info_sbmt_btn">
                        <i class="ti-check"></i> Save
                    </button>
                </div>

                <div class="col-auto">
                    <a href="{{ route('webpage.preview') }}"
                    target="_blank"
                    class="primary-btn fix-gr-bg">
                        <i class="ti-eye"></i> Preview
                    </a>
                </div>
            </div>
        </form>
</div>



<script>
$(document).ready(function () {

    $('#general_info_sbmt_btn').click(function (e) {
        e.preventDefault();
        let formData = new FormData($('#form_data_id')[0]);

        $.ajax({
            url: "{{ route('webpage_settings.update') }}", // Your update route
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function () {
                $('#general_info_sbmt_btn')
                    .prop('disabled', true)
                    .html('<i class="fa fa-spinner fa-spin"></i> Saving...');
            },
            success: function (response) {
                
                if (response.status == 200) {
                    toastr.success(response.message ?? 'Settings updated successfully.');
                } 

                $('#general_info_sbmt_btn')
                    .prop('disabled', false)
                    .html('<i class="ti-check"></i> Save');

            },
            error: function(xhr) {

                if (xhr.status == 422) {
                    $.each(xhr.responseJSON.errors, function(key, value) {
                        toastr.error(value[0]);
                    });
                      $('#general_info_sbmt_btn')
                    .prop('disabled', false)
                    .html('<i class="ti-check"></i> Save');
                    return;
                }
               
                toastr.error("Something went wrong.");
            }
        });

    });

});
</script>
