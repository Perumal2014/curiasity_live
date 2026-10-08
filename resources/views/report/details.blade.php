@extends('backend.master')
@section('mainContent')

    {!! generateBreadcrumb() !!}



    <section class="mt-20 admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row mt-40">
                <div class="col-lg-12">
                    <div class="box_header">
                        <div class="main-title mb_xs_20px">
                            <h3 class="mb-0 mb_xs_20px">{{__('courses.Lesson List')}} {{__('setting.For')}}
                                "{{$course->title}}"


                            </h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="QA_section QA_section_heading_custom check_box_table">
                <div class="QA_table ">

                    <table id="" class="table Crm_table_active3 scormReportTable">
                        <thead>
                        <tr>
                            <th> {{__('common.SL')}} </th>
                            <th> {{__('common.Name')}} </th>
                            <th> {{__('setting.Version')}} </th>
                            {{--                            <th> Complete Status</th>--}}
                            {{--                            <th> Success Status</th>--}}
                            {{--                            <th>Time</th>--}}

                            <th> {{__('frontend.Complete Status')}} </th>
                            <th> {{__('frontend.Success Status')}} </th>
                            <th> {{__('frontend.Time')}} </th>

                            <th> {{__('frontend.Last Updated')}} </th>
                            {{--                            <th> {{__('common.Action')}} </th>--}}
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            $i=0;
                        @endphp
                        @foreach($lessons as $key=>$lesson)
                            <tr>
                                <td>{{++$i}}</td>
                                <td>{{$lesson->name}}</td>
                                <td>{{$lesson->scorm_version=='scorm_2004'?'SCORM 2004':'Scorm 1.2'}}</td>
                                <td>
                                    @php
                                        $item=  $lesson->reports->filter(function ($report){
                                    return strstr($report->key, 'cmi.completion_status') ||  strstr ($report->key, 'cmi.core.lesson_status');
                                        })->first();
                                        if ($item){
                                            echo ucfirst($item->value);
                                        }
                                    @endphp
                                </td>
                                <td>
                                    @php
                                        $item=  $lesson->reports->filter(function ($report){
                                    return strstr($report->key, 'cmi.success_status') ||  strstr ($report->key, 'cmi.core.lesson_status');
                                        })->first();
                                        if ($item){
                                            echo ucfirst($item->value);
                                        }
                                    @endphp
                                </td>
                                <td>
                                    @php
                                        $item=  $lesson->reports->filter(function ($report){
                                        return strstr($report->key, 'cmi.session_time') ||  strstr ($report->key, 'cmi.core.session_time');
                                          })->first();
                                  if ($item){
                                      if ($lesson->scorm_version=='scorm_2004'){
                                      echo function_exists('isoToReglar')?isoToReglar($item->value):$item->value;
                                      }else{
                                       echo  \Carbon\CarbonInterval::seconds($item->value)->cascade()->forHumans();
                                      }
                                  }
                                    @endphp
                                </td>
                                <td>{{$lesson->updated_at->diffForHumans()}}</td>

                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

@endsection
@push('scripts')

@endpush
