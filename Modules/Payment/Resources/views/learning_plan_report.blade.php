@php use Illuminate\Support\Facades\Auth; @endphp
@extends('backend.master')
<style type="text/css">
    .dt-buttons {
    display: none;
}
</style>
@php
    $table_name='course_enrolleds';
$role_id =Auth::user()->role_id;
@endphp
@section('table')
    {{$table_name}}
@stop
@section('mainContent')

    {!! generateBreadcrumb() !!}
    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid p-0">

            <div class="row">
                <div class="col-lg-12">
                    <div class="white_box">
                        <div class="white_box_tittle list_header main-title mb-0">
                            <h3 class="mb-0">{{__('courses.Advanced Filter')}} </h3>
                        </div>
                        <form method="GET">

                            <div class="row">
                                @if($role_id==1)

                                    <div class="col-lg-4 mt-20">

                                        <label class="primary_input_label"
                                               for="instructor">{{__('courses.Instructor')}}</label>
                                        <select class="primary_select" name="instructor" id="instructor">
                                            <option data-display="{{__('common.Select')}} {{__('courses.Instructor')}}"
                                                    value="">{{__('common.Select')}} {{__('courses.Instructor')}}</option>
                                            @foreach($instructors as $instructor)
                                                <option {{$search_instructor==$instructor->id?'selected':''}}
                                                        value="{{$instructor->id}}">{{@$instructor->name}} </option>
                                            @endforeach
                                        </select>

                                    </div>
                                @endif
                                <div class="col-lg-4 mt-20 ">
                                    <label class="primary_input_label" for="course_id">{{__('courses.Select Courses')}}</label>
                                    <select class="primary_select" name="course_id" id="course_id">
                                        <option data-display="{{__('courses.Select Courses')}}"
                                                value="">{{__('common.Select')}} {{__('courses.Select Learning Plan')}}</option>
                                                @if ($courses) 
                                                    @foreach ($courses as $course) 
                                                        <option value="{{ $course->id}}" {{ request('course_id') == $course->id ? 'selected' : '' }}>{{ $course->title }}</option>
                                                    @endforeach
                                                @endif
                                    </select>

                                </div>
                                

                                <div class="col-lg-4 mt-20">
                                    <label class="primary_input_label">
                                     Module wise <span class="required_mark">*</span>
                                    </label>

                                    <div class="d-flex align-items-center gap-3">
                                            <label class="primary_checkbox d-flex">
                                                <input type="radio" name="module_wise" value="1"
                                                    {{ request('module_wise') == '1' ? 'checked' : '' }}>
                                                <span class="checkmark me-2"></span>
                                                Yes
                                            </label>

                                            <label class="primary_checkbox d-flex">
                                                <input type="radio" name="module_wise" value="0"
                                                    {{ request('module_wise', '0') == '0' ? 'checked' : '' }}>
                                                <span class="checkmark me-2"></span>
                                                No
                                            </label>
                                        </div>
                                </div>

                                <div class="col-lg-4 mt-20">
                                    <label class="primary_input_label">
                                     Lesson wise <span class="required_mark">*</span>
                                    </label>
                                    <div class="d-flex align-items-center gap-3">
                                        <label class="primary_checkbox d-flex">
                                            <input type="radio" name="lesson_wise" value="1"
    {{ request('lesson_wise') == '1' ? 'checked' : '' }}>
                                            <span class="checkmark me-2"></span>
                                            Yes
                                        </label>

                                        <label class="primary_checkbox d-flex">
                                            <input type="radio" name="lesson_wise" value="0"
    {{ request('lesson_wise', '0') == '0' ? 'checked' : '' }}>
                                            <span class="checkmark me-2"></span>
                                            No
                                        </label>
                                    </div>
                                    
                                </div>
                                
                               
                                <div class="{{$role_id==1?"col-12 mt-20":'col-lg-4 float-end mt-40'}}">
                                    {{-- @if($role_id!=1)
                                        <label class="primary_input_label pt-4"
                                               style="    margin-top: 5px;"> </label>
                                    @endif --}}

                                    <div
                                        class="search_course_btn  @if($role_id==1) text-end @endif">

                                        <button type="submit"
                                                class="primary-btn radius_30px   fix-gr-bg">{{__('courses.Filter')}} </button>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

            <div class="white-box mt-30">
                <div class="row mb-25">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-6 col-md-12 g-0 ">
                                <div class="main-title">
                                    <h3 class="mb-20"
                                        id="page_title">Course Report</h3>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12 g-0 d-flex justify-content-end align-items-center">
                                <a href="javascript:void(0)" id="exportBtn"
                                class="primary-btn radius_30px fix-gr-bg">
                                    Export Excel
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- </div> -->
                <div class="QA_section QA_section_heading_custom check_box_table mt-30">
                    <div class="QA_table ">
                        <table id="lms_table" class="table Crm_table_active3">
                            <thead>
                                <tr>
                                    <th>Purchase ID</th>
                                    <th><span class="m-2">Course Title</span></th>
                                    <th>Enrollment Date</th>
                                    <th>Instructor</th>
                                    <th>Purchase By</th>
                                    <th>Purchase Price</th>
                                    <th>Instructor Revenue</th>
                                </tr>
                            </thead>

                            <tbody>

                                {{-- Subscription Data --}}
                                @if(!empty($subscriptions))
                                    @foreach($subscriptions as $subscription)
                                        <tr>
                                            <td>S-{{ $subscription['checkout_id'] + 1000 }}</td>

                                            <td>
                                                <strong>Subscription - </strong>
                                                {{ $subscription['plan'] ?? '' }}
                                            </td>

                                            <td>{{ showDate($subscription['date'] ?? '') }}</td>

                                            <td>{{ $subscription['instructor'] ?? '' }}</td>

                                            <td>-</td>
                                            <td>-</td>

                                            <td>{{ getPriceFormat($subscription['price'] ?? 0) }}</td>
                                        </tr>
                                    @endforeach
                                @endif


                                {{-- Course Enrollments --}}
                                @forelse($enrolls as $enroll)
                                    <tr>
                                        <td>C-{{ $enroll->id + 1000 }}</td>

                                        <td>
                                            <span class="m-2">
                                                {{ optional($enroll->course)->title }}
                                            </span>
                                        </td>

                                        <td>{{ showDate($enroll->created_at) }}</td>

                                        <td>{{ optional($enroll->course->user)->name }}</td>

                                        <td>{{ optional($enroll->user)->name }}</td>

                                        <td>{{ getPriceFormat($enroll->purchase_price) }}</td>

                                        <td>{{ getPriceFormat($enroll->reveune) }}</td>
                                    </tr>

                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            No data found
                                        </td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </section>

@endsection
@push('scripts')
    <script type="application/javascript">


        dataTableOptions = updateColumnExportOption(dataTableOptions, [0, 1, 2, 3, 4, 5, 6]);

        let table = $('#lms_table').DataTable(dataTableOptions);


    </script>

@endpush
