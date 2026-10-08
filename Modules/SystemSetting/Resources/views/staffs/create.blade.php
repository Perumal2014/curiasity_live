@extends('backend.master')
@section('mainContent')

    {!! generateBreadcrumb() !!}
    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row justify-content-center">

                <div class="col-12">
                    <div class="white-box">
                        <form action="{{ route('staffs.store') }}" method="POST" id="staff_addForm"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="main-title d-flex">
                                        <h3 class="mb-0 mr-30">{{ __('common.Basic Info') }}</h3>
                                    </div>
                                </div>
                                <hr>
                                <div class="col-xl-4">
                                    <div class="primary_input mb-25">
                                        <label class="primary_input_label" for="">{{ __('common.Name') }} <span
                                                class="required_mark">*</span></label>
                                        <input name="name" class="primary_input_field name"
                                               placeholder="{{ __('common.Name') }}" type="text"
                                               value="{{old('name')}}">
                                        <span class="text-danger">{{$errors->first('name')}}</span>
                                    </div>
                                </div>

                                <div class="col-xl-4">
                                    <div class="primary_input mb-25">
                                        <label class="primary_input_label" for="">{{ __('common.Email') }} <span
                                                class="required_mark">*</span></label>
                                        <input name="email" class="primary_input_field name"
                                               placeholder="{{ __('common.Email') }}" type="email"
                                               value="{{old('email')}}">
                                        <span class="text-danger">{{$errors->first('email')}}</span>
                                    </div>
                                </div>
                                <div class="col-xl-4">
                                            <div class="primary_input mb-35">
                                                <label class="primary_input_label" for="">{{__('common.gender')}}
                                                </label>

                                                <select class="primary_select"
                                                        data-course_id="{{@$course->id}}" name="gender">
                                                    <option
                                                        data-display="{{__('common.Select')}} {{__('common.gender')}}"
                                                        value="">{{__('common.Select')}} {{__('common.gender')}} </option>

                                                    <option
                                                        value="male" {{(old('gender',isset($user)?$user->gender:'')=='male')?'checked':''}}>{{__('common.Male')}}</option>
                                                    <option
                                                        value="female" {{(old('gender',isset($user)?$user->gender:'')=='female')?'checked':''}}>{{__('common.Female')}}</option>
                                                    <option
                                                        value="other" {{(old('gender',isset($user)?$user->gender:'')=='other')?'checked':''}}>{{__('common.Other')}}</option>


                                                </select>
                                            </div>
                                        </div>
                                <div class="col-xl-4">
                                    <div class="primary_input mb-25">
                                        <label class="primary_input_label" for="">{{ __('common.Phone') }}</label>
                                        <input type="number" class="primary_input_field user_id name phoneNumberInput"
                                               placeholder="{{ __('common.Phone') }}" name="username"
                                               value="{{old('username')}}">
                                        <span class="text-danger">{{$errors->first('username')}}</span>
                                    </div>
                                </div>


                                <div class="col-xl-4">
                                    <div class="primary_input mb-25">
                                        <label class="primary_input_label" for="">{{ __('common.Password') }}
                                            ({{trans('Minimum 8 Letter')}}) <span class="required_mark">*</span></label>
                                        <input name="password" class="primary_input_field name"
                                               autocomplete="new-password"
                                               placeholder="{{ __('common.Password') }}" type="password" minlength="6">
                                        <span class="text-danger">{{$errors->first('password')}}</span>
                                    </div>
                                </div>

                                <div class="col-xl-4">
                                    <div class="primary_input mb-25">
                                        <?php 
                                        $departments = \Modules\SystemSetting\Entities\Department::where('organization_id', auth()->user()->organization_id)->get();
                                        ?>
                                        <label class="primary_input_label" for="">{{ __('department.Department') }}
                                            <span class="required_mark">*</span></label>
                                        <select class="primary_select mb-25" name="department_id" id="department_id">
                                            <option value="0">{{__('common.Select')}} {{__('department.Department')}}</option>
                                            @foreach ($departments as $dept)
                                                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger">{{$errors->first('department_id')}}</span>
                                    </div>
                                </div>

                                
                                <div class="col-xl-4 date_of_birth_div">
                                    <div class="primary_input mb-15">
                                        <label class="primary_input_label"
                                               for="">{{ __('common.Date of Birth') }} </label>
                                        <div class="primary_datepicker_input">
                                            <div class="g-0  input-right-icon">
                                                <div class="col">
                                                    <div class="">
                                                        <input placeholder="{{__('common.Date')}}"
                                                               class="primary_input_field primary-input date form-control"
                                                               id="date_of_birth" type="text"
                                                               name="date_of_birth" value="" autocomplete="off"
                                                               readonly max="{{ now()->subYears(18)->toDateString() }}">
                                                    </div>
                                                </div>
                                                <button class="" type="button">
                                                    <i class="ti-calendar" id="start-date-icon"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <span class="text-danger">{{$errors->first('date_of_birth')}}</span>
                                    </div>
                                </div>

                                <div class="col-xl-4 current_address_div">
                                    <div class="primary_input mb-25">
                                        <label class="primary_input_label"
                                               for="">{{ __('common.Current Address') }}</label>
                                        <input name="current_address" id="current_address"
                                               class="primary_input_field name"
                                               placeholder="{{ __('common.Current Address') }}" type="text"
                                               value="{{old('current_address')}}">
                                        <span class="text-danger">{{$errors->first('current_address')}}</span>
                                    </div>
                                </div>

                                <div class="col-xl-4 permanent_address_div">
                                    <div class="primary_input mb-25">
                                        <label class="primary_input_label"
                                               for="">{{ __('common.Permanent Address') }}</label>
                                        <input name="permanent_address" id="permanent_address"
                                               class="primary_input_field name"
                                               placeholder="{{ __('common.Permanent Address') }}" type="text"
                                               value="{{old('permanent_address')}}">
                                        <span class="text-danger">{{$errors->first('permanent_address')}}</span>
                                    </div>
                                </div>

                                
                                <input type="hidden" name="role_type" id="role_type" value="">
                                <div class="col-lg-4">
                                    <div class=" mb-15">
                                        <x-upload-file
                                            name="image"
                                            type="image"
                                            label="{{ __('common.Profile Picture') }}"/>
                                    </div>
                                </div>
                                

                                <div class="col-xl-4">
                                    <div class="primary_input mb-15">
                                        <label class="primary_input_label"
                                               for="">{{ __('common.Position') }} </label>
                                        <div class="primary_datepicker_input">
                                            <div class="g-0  input-right-icon">
                                                <div class="col">
                                                    <div class="">
                                                        <?php 
                                                        $roles = \Modules\RolePermission\Entities\Role::whereIn('id', [4,9])->get();
                                                        ?>
                                                        <select name="role_id" id="role_id" class="primary_input_field primary-input form-control">
                                                            <option value="0">Select Position</option>
                                                            @foreach($roles as $role)
                                                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </div>
                                        <span class="text-danger">{{$errors->first('leave_applicable_date')}}</span>
                                    </div>
                                </div>

                                <div class="col-xl-4" id="year_wrapper">
                                            <div class="primary_input mb-25">
                                                <label class="primary_input_label"
                                                    for="">{{__('common.Year')}}
                                                </label>
                                                <select class="primary_select mb-25" name="handle_year" id="handle_year">

                                                    <option value="0">{{__('common.Select')}}</option>
                                                    <option value="1" @if(isset($stud_detail) && $stud_detail->student_year == 1) selected @endif>1st Year</option>
                                                    <option value="2" @if(isset($stud_detail) && $stud_detail->student_year == 2) selected @endif>2nd Year</option>
                                                    <option value="3" @if(isset($stud_detail) && $stud_detail->student_year == 3) selected @endif>3rd Year</option>
                                                    <option value="4" @if(isset($stud_detail) && $stud_detail->student_year == 4) selected @endif>4th Year</option>
                                                </select>

                                            </div>
                                        </div>
                                <div class="col-xl-12 mt-5 bank_info_div">
                                    <div class="main-title d-flex">
                                        <h3 class="mb-0 mr-30">{{ __('common.Professional Info') }}</h3>
                                    </div>
                                </div>
                                <hr>
                                <div class="col-xl-6 bank_name_div">
                                    <div class="primary_input mb-25">
                                        <label class="primary_input_label" for="">{{ __('common.Qualification Info') }}</label>
                                        <input name="qualification_info" id="qualification_info" class="primary_input_field name"
                                               value="{{old('qualification_info')}}" placeholder="{{ __('common.Qualification Info') }}"
                                               type="text">
                                        <span class="text-danger">{{$errors->first('qualification_info')}}</span>
                                    </div>
                                </div>

                                <div class="col-xl-6 bank_account_name_div">
                                    <div class="primary_input mb-25">
                                        <label class="primary_input_label" for="">{{ __('common.Subject') }}</label>
                                        <input name="subject" value="{{old('subject')}}"
                                               id="subject" class="primary_input_field name"
                                               placeholder="{{ __('common.Subject') }}" type="text">
                                        <span class="text-danger">{{$errors->first('subject')}}</span>
                                    </div>
                                </div>

                                <div class="col-lg-12 text-center">
                                    <div class="d-flex justify-content-center pt_20">
                                        <button type="submit" class="primary-btn semi_large2 fix-gr-bg"
                                                id="save_button_parent"><i class="ti-check"></i>{{ __('common.Save') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
@push('scripts')
    <script type="text/javascript">
        function getField() {
            var employment_type = $('#employment_type').val();
            if (employment_type == "Provision") {
                $("#provisional_time").removeAttr("disabled");
            } else if (employment_type == "Contract") {
                $("#provisional_time").attr('disabled', true);
            } else {
                $("#bank_name").attr('Permanent', true);
                $("#provisional_time").attr('disabled', true);
            }
        }

    </script>

    <script>
$(document).ready(function () {

    function toggleYearField() {
        let roleId = $('#role_id').val();

        if (roleId == 9) {
            $('#year_wrapper').show();
        } else {
            $('#year_wrapper').hide();
            // ✅ reset select value
            $('#handle_year').val('');
        }
    }

    // On page load (edit case)
    toggleYearField();

    // On role change
    $('#role_id').on('change', function () {
        toggleYearField();
    });

});
</script>
✅ Step 3: Hide by default (optional but clean)
<style>
    #year_wrapper {
        display: none;
    }
</style>

@endpush
