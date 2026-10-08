@extends('backend.master')

@section('mainContent')
    <style>
        .select2-container {
            width: unset;
        }

        .select2-container--default .select2-selection--single {
            background-color: transparent;
            width: 100%;
            height: 46px;
            line-height: 46px;
            font-size: 13px;
            padding: 3px 20px;
            padding-left: 20px;
            font-weight: 300;
            border-radius: 4px;
            color: var(--dynamic-text-color);
            border: 1px solid var(--backend-border-color);
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 46px;
            position: absolute;
            top: 1px;
            right: 20px;
            width: 20px;
            color: var(--text-color);
        }

        .select2-dropdown {
            background-color: white;
            border: 1px solid var(--backend-border-color);
            border-radius: 4px;
            box-sizing: border-box;
            display: block;
            position: absolute;
            left: -100000px;
            width: 100%;
            background: var(--backend-main-bg);
            overflow: auto !important;
            border-radius: 0px 0px 10px 10px;
            margin-top: 1px;
            z-index: 1051 !important;
            border: 0;
            box-shadow: var(--selector-shadow);
            min-width: 200px;
        }

        .select2-search--dropdown .select2-search__field {
            padding: 4px;
            width: 100%;
            box-sizing: border-box;
            background-color: transparent;
            border: 1px solid rgba(130, 139, 178, 0.3) !important;
            border-radius: 3px;
            box-shadow: none;
            color: var(--theme-default-color);
            display: inline-block;
            vertical-align: middle;
            padding: 0px 8px;
            width: 100% !important;
            height: 46px;
            line-height: 46px;
            outline: 0 !important;
        }

        .select2-container {
            width: 100% !important;
            min-width: 90px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: var(--dynamic-text-color);
            line-height: 40px;
        }

        .select2-container--default.select2-container--open.select2-container--below .select2-selection--single {
            border-radius: 10px 10px 0px 0px;
        }

        .select2-container--default .select2-selection--single,
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 35px;
            line-height: 35px;
            display: flex;
            align-items: center;
        }

        .groupList {
            height: 35px;
            display: flex;
            align-items: center;
        }

        .select2-container--default .select2-results > .select2-results__options li {
            padding-right: 0;
            white-space: normal;
            line-height: 1.5;
            height: auto;
            padding-top: 10px;
            padding-bottom: 10px;
        }

        .select2-container--default .select2-results > .select2-results__options {
            max-height: 400px;
        }

        .min_width_300 {
            min-width: 300px;
        }

        .primary_checkbox {
            display: flex;
            align-items: center;
        }
    </style>

    @php
        $tenantSlug = request()->route('tenant_slug');
    @endphp

    {!! generateBreadcrumb() !!}

    @if(!isModuleActive('AdvanceQuiz'))
        <div class="row">
            <div class="col-lg-12">
                <div class="white-box mb-30">
                    <form method="GET" action="" class="form-horizontal" id="search_group">
                        <div class="row">
                            <div class="col-lg-4 mt-30-md md_mb_20">
                                <label class="primary_input_label" for="group_filter">{{ __('quiz.Group') }}</label>
                                <select class="primary_select" id="group_filter" name="group">
                                    <option data-display="{{ __('common.Select') }}" value="">
                                        {{ __('quiz.Group') }}
                                    </option>
                                    @foreach($groups as $g)
                                        <option value="{{ $g->id }}" {{ $group == $g->id ? 'selected' : '' }}>
                                            {{ $g->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-12 mt-100-md md_mb_20 mt-2">
                                <label class="primary_input_label" for=""></label>
                                <button type="submit" class="primary-btn fix-gr-bg">
                                    <span class="ti-search pe-2"></span>
                                    {{ __('quiz.Search') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
    
    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid p-0">
            <div class="white-box">
                <div class="row">
                    <div class="col-lg-12">
                        

                        <div class="QA_section QA_section_heading_custom check_box_table">
                            <div class="QA_table">
                                <div>
                                    <table id="question_table" class="table Crm_table_active3 quiz-bank-checkbox">
                                        <thead>
                                        <tr>
                                            
                                            <th>{{ __('common.SL') }}</th>
                                            <th>{{ __('quiz.Group') }}</th>
                                            <th>{{ __('quiz.Question') }}</th>
                                            <th>{{ __('common.Type') }}</th>
                                            @if(isModuleActive('AdvanceQuiz'))
                                                <th>{{ __('common.Level') }}</th>
                                            @endif
                                            
                                            <th>{{ __('common.Action') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @if($queries)
                                            @foreach ($queries as $key => $query)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $query->questionGroup->title }}</td>
                                                    <td>{{ Str::limit(strip_tags($query->question ?? ''), 100) ?: '-' }}</td>
                                                    <td>{{ $query->type ?? '-' }}</td>
                                                    <td><div class="dropdown CRM_dropdown">
                                                        <button class="btn btn-secondary dropdown-toggle" type="button"
                                                                id="dropdownMenu2"
                                                                data-bs-toggle="dropdown"
                                                                aria-haspopup="true"
                                                                aria-expanded="false">
                                                            {{ trans('common.Action') }}
                                                        </button>

                                                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenu2">
                                                            
                                                                <a class="dropdown-item edit_brand"
                                                                href="{{ route('question-bank-edit', [
                                                                        'tenant_slug' => request()->route('tenant_slug'),
                                                                        'id' => $query->id
                                                                ]) }}">
                                                                    {{ trans('common.Edit') }}
                                                                </a>
                                                            

                                                            
                                                                <button class="dropdown-item deleteQuiz_bank"
                                                                        data-id="{{ $query->id }}"
                                                                        data-total="{{ $query->quiz_assign_count }}"
                                                                        type="button">
                                                                    {{ trans('common.Delete') }}
                                                                </button>
                                                        
                                                        </div>
                                                    </div></td>
                                                </tr>
                                            @endforeach
                                            @endif
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

    <div class="modal fade admin-query" id="deleteBank">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{__('common.Delete')}} </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"><i
                            class="ti-close "></i></button>
                </div>

                <div class="modal-body">
                    <form action="{{route('question-bank-delete')}}" method="post">
                        @csrf

                        <div class="text-center">

                            <h4>{{__('common.Are you sure to delete ?')}} </h4>
                        </div>
                        <input type="hidden" name="id" value="" id="classQusId">
                        <div class="mt-40 d-flex justify-content-between">
                            <button type="button" class="primary-btn tr-bg"
                                    data-bs-dismiss="modal">{{__('common.Cancel')}}</button>

                            <button class="primary-btn fix-gr-bg"
                                    type="submit">{{__('common.Delete')}}</button>

                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>


    <div class="modal fade admin-query" id="deleteBankWarring">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{__('common.Warring')}} </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"><i
                            class="ti-close "></i></button>
                </div>

                <div class="modal-body">
                    <div class="text-center">

                        <h4>{{__('quiz.You cannot delete this question because it has been used in')}} <span
                                id="totalAssignQus"></span> {{__('quiz.quiz already')}} </h4>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <div class="modal fade admin-query" id="deleteAllBank">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{__('common.Delete')}} </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"><i
                            class="ti-close "></i></button>
                </div>

                <div class="modal-body">
                    <form action="{{route('question-bank-bulk-delete')}}" method="post">
                        @csrf

                        <div class="text-center">
                            <h4>{{__('common.Are you sure to delete ?')}} </h4>
                        </div>
                        <input type="hidden" name="questions" value="" id="qusList">
                        <div class="mt-40 d-flex justify-content-between">
                            <button type="button" class="primary-btn tr-bg"
                                    data-bs-dismiss="modal">{{__('common.Cancel')}}</button>

                            <button class="primary-btn fix-gr-bg"
                                    type="submit">{{__('common.Delete')}}</button>

                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    {{-- Delete single --}}
    
@endsection


<script type="text/javascript">
    $(document).on('click', '.deleteQuiz_bank', function () {
            let id = $(this).data('id');
            let total = $(this).data('total');
            if (total == 0) {
                $('#classQusId').val(id);
                $("#deleteBank").modal('show');
            } else {
                $("#totalAssignQus").text(total);
                $("#deleteBankWarring").modal('show');

            }

        });
    </script>

