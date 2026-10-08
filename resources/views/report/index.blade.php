@extends('backend.master')
@section('mainContent')

    {!! generateBreadcrumb() !!}



    <section class="mt-20 admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid p-0">
            <div class="white-box">
                <div class="row">

                    <div class="col-lg-12">
                        <div class="box_header">
                            <div class="main-title mb_xs_20px">
                                <h3 class="mb-0 mb_xs_20px" id="page_title">{{__('report.Scorm Report')}}</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="  QA_section QA_section_heading_custom check_box_table">
                    <div class="QA_table ">
                        <div class="">
                            <table id="lms_table" class="table table-data  dataTable scormReportTable">
                                <thead>
                                <tr>
                                    <th> {{__('common.SL')}} </th>
                                    <th> {{__('quiz.Student')}} </th>
                                    <th>{{__('courses.Course')}}</th>
                                    <th>{{__('common.Total Scorm')}}</th>
                                    <th> {{__('common.Action')}} </th>
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
    </section>

@endsection
@push('scripts')
    <script src="{{asset('/')}}/Modules/Quiz/Resources/assets/js/quiz.js"></script>
    <script>

        dataTableOptions.serverSide = true
        dataTableOptions.processing = true
        dataTableOptions.ajax = '{!! route('scorm.report.data') !!}';
        dataTableOptions.columns = [
            {data: 'DT_RowIndex', name: 'id'},
            {data: 'user.name', name: 'user.name'},
            {data: 'course.title', name: 'course.title'},
            {data: 'lesson', name: 'lesson', orderable: false, searchable: false, className: 'text-center'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
        let table = $('.scormReportTable').DataTable(dataTableOptions);


    </script>
@endpush
