@extends('backend.master')

@push('styles')

    <link rel="stylesheet"
          href="{{ asset('public/backend/css/student_list.css') }}"/>

    <style>

        .dt-buttons {
            display: none !important;
        }

        /*
        |--------------------------------------------------------------------------
        | Feedback Answer Modal
        |--------------------------------------------------------------------------
        */

        .feedback-answer-item {
            padding: 18px 20px;
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .feedback-question-number {
            font-weight: 600;
            color: #9734f2;
            margin-right: 5px;
        }

        .feedback-question-text {
            font-size: 15px;
            font-weight: 600;
            color: #2f3441;
            line-height: 1.5;
        }

        .feedback-type {
            display: inline-block;
            margin-top: 7px;
            padding: 4px 10px;
            border-radius: 20px;
            background: #f1f3f5;
            color: #6c757d;
            font-size: 12px;
            font-weight: 500;
        }

        .feedback-answer-box {
            margin-top: 15px;
            padding: 12px 15px;
            background: #f8f9fa;
            border-radius: 6px;
            border-left: 3px solid #9734f2;
        }

        .feedback-answer-label {
            font-size: 12px;
            color: #6c757d;
            font-weight: 600;
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        .feedback-answer-value {
            color: #212529;
            font-size: 14px;
            line-height: 1.6;
            word-break: break-word;
        }

        .feedback-score {
            margin-top: 12px;
            font-size: 13px;
            color: #6c757d;
        }

        .feedback-score strong {
            color: #212529;
        }

        .feedback-stars {
            font-size: 18px;
            letter-spacing: 2px;
        }

        .feedback-empty {
            color: #999;
            font-style: italic;
        }

        .feedback-overall-card {
            border: 1px solid #e9ecef;
            border-radius: 8px;
            background: #f8f9fa;
        }

        .feedback-overall-score {
            font-size: 20px;
            font-weight: 600;
            color: #212529;
        }

        .feedback-overall-percentage {
            font-size: 14px;
            font-weight: 600;
            color: #9734f2;
        }

        /*
        |--------------------------------------------------------------------------
        | Feedback Options
        |--------------------------------------------------------------------------
        */

        .feedback-options {
            margin-top: 10px;
        }

        .feedback-option {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            margin-bottom: 7px;
            border: 1px solid #e9ecef;
            border-radius: 6px;
            background: #fff;
            color: #495057;
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .feedback-option:last-child {
            margin-bottom: 0;
        }

        .feedback-option.selected {
            border-color: #9734f2;
            background: #f7efff;
            color: #212529;
            font-weight: 600;
        }

        .feedback-option-icon {
            width: 22px;
            min-width: 22px;
            text-align: center;
            font-size: 16px;
            color: #adb5bd;
        }

        .feedback-option.selected .feedback-option-icon {
            color: #9734f2;
        }

        .feedback-option-label {
            flex: 1;
        }

        .feedback-selected-label {
            margin-left: auto;
            font-size: 11px;
            padding: 3px 8px;
            border-radius: 12px;
            background: #9734f2;
            color: #fff;
            white-space: nowrap;
        }

        /*
        |--------------------------------------------------------------------------
        | Text Answer
        |--------------------------------------------------------------------------
        */

        .feedback-text-answer {
            padding: 10px 12px;
            border: 1px solid #e9ecef;
            background: #fff;
            border-radius: 6px;
            white-space: pre-wrap;
        }

        /*
        |--------------------------------------------------------------------------
        | Star Rating
        |--------------------------------------------------------------------------
        */

        .feedback-star-option {
            display: inline-flex;
            align-items: center;
            margin-right: 3px;
        }

        .feedback-star-option i {
            font-size: 20px;
        }

        /*
        |--------------------------------------------------------------------------
        | Selected Summary
        |--------------------------------------------------------------------------
        */

        .feedback-selected-summary {
            margin-top: 10px;
            font-size: 13px;
            color: #6c757d;
        }

        .feedback-selected-summary strong {
            color: #212529;
        }

    </style>

@endpush


@php
    $table_name = 'feedback_submissions';
@endphp


@section('table')
    {{ $table_name }}
@endsection


@section('mainContent')

    <p>&nbsp;</p>

    <section class="admin-visitor-area up_st_admin_visitor">

        <div class="container-fluid p-0">

            <div class="row justify-content-center">

                <div class="col-12">

                    <div class="box_header common_table_header">

                        <div class="main-title d-md-flex">

                            <h3 class="mb-0 mr-30 mb_xs_15px mb_sm_20px"
                                id="page_title">

                                {{ __('student.Answered List') }}
                                :
                                {{ $feedback_form->form_name }}

                            </h3>

                        </div>

                    </div>

                </div>


                <div class="col-lg-12 mt-40">

                    <div class="QA_section QA_section_heading_custom check_box_table">

                        <div class="QA_table">

                            <div>

                                <table id="lms_table"
                                       class="table Crm_table_active3">

                                    <thead>

                                        <tr>

                                            <th>
                                                {{ __('common.SL') }}
                                            </th>

                                            <th>
                                                {{ __('common.Name') }}
                                            </th>

                                            <th>
                                                {{ __('common.Email') }}
                                            </th>

                                            <th>
                                                {{ __('common.Comments') }}
                                            </th>

                                            <th>
                                                {{ __('common.Action') }}
                                            </th>

                                        </tr>

                                    </thead>

                                    <tbody></tbody>

                                </table>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =========================================================
                     DELETE MODAL
                ========================================================== --}}

                <div class="modal fade admin-query"
                     id="deleteStudent">

                    <div class="modal-dialog modal-dialog-centered">

                        <div class="modal-content">

                            <div class="modal-header">

                                <h4 class="modal-title">

                                    {{ __('common.Delete') }}
                                    {{ __('student.Student') }}

                                </h4>

                                <button type="button"
                                        class="btn-close"
                                        data-bs-dismiss="modal">

                                    <i class="ti-close"></i>

                                </button>

                            </div>


                            <div class="modal-body">

                                <form action="{{ route('student.delete') }}"
                                      method="post">

                                    @csrf

                                    <div class="text-center">

                                        <h4>
                                            {{ __('common.Are you sure to delete ?') }}
                                        </h4>

                                    </div>

                                    <input type="hidden"
                                           name="id"
                                           value=""
                                           id="studentDeleteId">


                                    <div class="mt-40 d-flex justify-content-between">

                                        <button type="button"
                                                class="primary-btn tr-bg"
                                                data-bs-dismiss="modal">

                                            {{ __('common.Cancel') }}

                                        </button>


                                        <button class="primary-btn fix-gr-bg"
                                                type="submit">

                                            {{ __('common.Delete') }}

                                        </button>

                                    </div>

                                </form>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =========================================================
                     ANSWER MODAL
                ========================================================== --}}

                <div class="modal fade"
                     id="answerModal"
                     tabindex="-1"
                     aria-hidden="true">

                    <div class="modal-dialog modal-lg modal-dialog-centered">

                        <div class="modal-content">

                            {{-- =================================================
                                 MODAL HEADER
                            ================================================== --}}

                            <div class="modal-header">

                                <div>

                                    <h5 class="modal-title mb-1">
                                        Feedback Result
                                    </h5>

                                    <small class="text-muted"
                                           id="submissionName">
                                    </small>

                                    <br>

                                    <small class="text-muted"
                                           id="submissionEmail">
                                    </small>

                                </div>


                                <button type="button"
                                        class="btn-close"
                                        data-bs-dismiss="modal">
                                </button>

                            </div>


                            {{-- =================================================
                                 MODAL BODY
                            ================================================== --}}

                            <div class="modal-body">


                                {{-- =================================================
                                     LOADER
                                ================================================== --}}

                                <div id="answerLoader"
                                     class="text-center py-5">

                                    <div class="spinner-border"></div>

                                    <div class="mt-2">
                                        Loading answers...
                                    </div>

                                </div>


                                {{-- =================================================
                                     CONTENT
                                ================================================== --}}

                                <div id="answerContent"
                                     style="display:none;">


                                    {{-- =================================================
                                         EMPLOYEE INFORMATION
                                    ================================================== --}}

                                    <div class="card border-0 bg-light mb-4">

                                        <div class="card-body">

                                            <div class="row">

                                                <div class="col-md-6 mb-3">

                                                    <small class="text-muted">
                                                        Location
                                                    </small>

                                                    <div class="fw-semibold"
                                                         id="submissionLocation">
                                                        -
                                                    </div>

                                                </div>


                                                <div class="col-md-6 mb-3">

                                                    <small class="text-muted">
                                                        Designation
                                                    </small>

                                                    <div class="fw-semibold"
                                                         id="submissionDesignation">
                                                        -
                                                    </div>

                                                </div>


                                                <div class="col-md-6">

                                                    <small class="text-muted">
                                                        Department
                                                    </small>

                                                    <div class="fw-semibold"
                                                         id="submissionDepartment">
                                                        -
                                                    </div>

                                                </div>


                                                <div class="col-md-6">

                                                    <small class="text-muted">
                                                        Function
                                                    </small>

                                                    <div class="fw-semibold"
                                                         id="submissionFunction">
                                                        -
                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                    </div>


                                    {{-- =================================================
                                         OVERALL SCORE
                                    ================================================== --}}

                                    <div class="card border-0 feedback-overall-card mb-4">

                                        <div class="card-body">

                                            <div class="d-flex justify-content-between align-items-center">

                                                <div>

                                                    <h6 class="mb-1">
                                                        Overall Score
                                                    </h6>

                                                    <small class="text-muted">
                                                        Total feedback score
                                                    </small>

                                                </div>


                                                <div class="text-end">

                                                    <div class="feedback-overall-score"
                                                         id="overallScore">
                                                        0 / 0
                                                    </div>

                                                    <div class="feedback-overall-percentage"
                                                         id="overallPercentage">
                                                        0%
                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                    </div>


                                    {{-- =================================================
                                         QUESTIONS
                                    ================================================== --}}

                                    <div id="questionsContainer"></div>


                                    {{-- =================================================
                                         COMMENTS
                                    ================================================== --}}

                                    <div class="card border-0 bg-light mt-4">

                                        <div class="card-body">

                                            <h6 class="mb-2">
                                                Comments
                                            </h6>

                                            <div id="submissionComments"
                                                 class="text-muted">
                                                -
                                            </div>

                                        </div>

                                    </div>

                                </div>


                                {{-- =================================================
                                     ERROR
                                ================================================== --}}

                                <div id="answerError"
                                     class="alert alert-danger"
                                     style="display:none;">
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>

@endsection


@push('scripts')

    @php

        $url = route(
            'feedback.getAllAnsweredData',
            $feedback_form->id
        );

    @endphp


    {{-- =========================================================
         DATATABLE
    ========================================================== --}}

    <script>

        dataTableOptions.serverSide = true;

        dataTableOptions.processing = true;

        dataTableOptions.ajax = '{!! $url !!}';


        dataTableOptions.columns = [

            {
                data: 'DT_RowIndex',
                name: 'id',
                orderable: true,
                searchable: false
            },

            {
                data: 'name',
                name: 'name',
                orderable: false
            },

            {
                data: 'email',
                name: 'email'
            },

            {
                data: 'comments',
                name: 'comments',
                orderable: false
            },

            {
                data: 'answer',
                name: 'answer',
                orderable: false,
                searchable: false
            }

        ];


        dataTableOptions = updateColumnExportOption(
            dataTableOptions,
            [0, 1, 2, 3]
        );


        let table = $('#lms_table').DataTable(
            dataTableOptions
        );

    </script>


    <script src="{{ asset('public/backend/js/student_list.js') }}"></script>


    {{-- =========================================================
         ANSWER MODAL
    ========================================================== --}}

    <script>

        /*
        |--------------------------------------------------------------------------
        | Format Question Type
        |--------------------------------------------------------------------------
        */

        function formatQuestionType(type) {

            const types = {

                text: 'Text',

                radio: 'Radio',

                checkbox: 'Checkbox',

                rating: 'Rating',

                starrating: 'Star Rating',

                linearscale: 'Linear Scale'

            };

            return types[type] || type || '';

        }


        /*
        |--------------------------------------------------------------------------
        | Escape HTML
        |--------------------------------------------------------------------------
        */

        function escapeHtml(value) {

            if (
                value === null ||
                value === undefined
            ) {

                return '';

            }

            return $('<div>')
                .text(String(value))
                .html();

        }


        /*
        |--------------------------------------------------------------------------
        | Normalize Answer Values
        |--------------------------------------------------------------------------
        */

        function normalizeAnswerValues(answer) {

            if (
                answer === null ||
                answer === undefined ||
                answer === ''
            ) {

                return [];

            }


            /*
            |--------------------------------------------------------------------------
            | Already Array
            |--------------------------------------------------------------------------
            */

            if (Array.isArray(answer)) {

                return answer
                    .map(function(value) {

                        if (
                            value !== null &&
                            typeof value === 'object'
                        ) {

                            value =
                                value.value ??
                                value.label ??
                                value.text ??
                                '';

                        }

                        return String(value)
                            .trim()
                            .toLowerCase();

                    })
                    .filter(function(value) {

                        return value !== '';

                    });

            }


            /*
            |--------------------------------------------------------------------------
            | JSON String
            |--------------------------------------------------------------------------
            */

            if (typeof answer === 'string') {

                try {

                    let parsed =
                        JSON.parse(answer);

                    if (
                        Array.isArray(parsed)
                    ) {

                        return parsed
                            .map(function(value) {

                                if (
                                    value !== null &&
                                    typeof value === 'object'
                                ) {

                                    value =
                                        value.value ??
                                        value.label ??
                                        value.text ??
                                        '';

                                }

                                return String(value)
                                    .trim()
                                    .toLowerCase();

                            })
                            .filter(function(value) {

                                return value !== '';

                            });

                    }

                } catch (e) {

                    // Normal string

                }


                return [

                    answer
                        .trim()
                        .toLowerCase()

                ];

            }


            /*
            |--------------------------------------------------------------------------
            | Numeric / Other
            |--------------------------------------------------------------------------
            */

            return [

                String(answer)
                    .trim()
                    .toLowerCase()

            ];

        }


        /*
        |--------------------------------------------------------------------------
        | Check Whether Option Is Selected
        |--------------------------------------------------------------------------
        */

        function isOptionSelected(option) {

            return (
                option.selected === true ||
                option.selected === 1 ||
                option.selected === '1' ||
                option.selected === 'true'
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Render Feedback Options
        |--------------------------------------------------------------------------
        */

        function renderFeedbackOptions(item) {

    if (
        !item.options ||
        !Array.isArray(item.options) ||
        item.options.length === 0
    ) {
        return '';
    }

    /*
    |--------------------------------------------------------------------------
    | Get Submitted Answers
    |--------------------------------------------------------------------------
    */

    let selectedValues = normalizeAnswerValues(
        item.answer
    );


    /*
    |--------------------------------------------------------------------------
    | Render Options
    |--------------------------------------------------------------------------
    */

    let html =
        '<div class="feedback-options">';


    $.each(
        item.options,
        function(index, option) {

            let optionLabel =
                String(
                    option.label ??
                    option.text ??
                    ''
                )
                .trim();


            let optionValue =
                String(
                    option.value ??
                    option.label ??
                    option.text ??
                    ''
                )
                .trim();


            /*
            |--------------------------------------------------------------------------
            | Lowercase values for comparison
            |--------------------------------------------------------------------------
            */

            let labelLower =
                optionLabel.toLowerCase();


            let valueLower =
                optionValue.toLowerCase();


            /*
            |--------------------------------------------------------------------------
            | Check Backend Selected Flag
            |--------------------------------------------------------------------------
            */

            let backendSelected =
                option.selected === true ||
                option.selected === 1 ||
                option.selected === '1' ||
                option.selected === 'true';


            /*
            |--------------------------------------------------------------------------
            | Check Submitted Answer
            |--------------------------------------------------------------------------
            */

            let answerSelected =
                selectedValues.includes(
                    valueLower
                )
                ||
                selectedValues.includes(
                    labelLower
                );


            /*
            |--------------------------------------------------------------------------
            | Final Selected State
            |--------------------------------------------------------------------------
            */

            let selected =
                backendSelected ||
                answerSelected;


            /*
            |--------------------------------------------------------------------------
            | Icon
            |--------------------------------------------------------------------------
            */

            let icon = '○';


            if (
                item.question_type === 'checkbox'
            ) {

                icon =
                    selected
                        ? '☑'
                        : '☐';

            } else if (
                item.question_type === 'radio'
            ) {

                icon =
                    selected
                        ? '●'
                        : '○';

            } else if (
                item.question_type === 'rating' ||
                item.question_type === 'linearscale'
            ) {

                icon =
                    selected
                        ? '●'
                        : '○';

            } else if (
                item.question_type === 'starrating'
            ) {

                icon =
                    selected
                        ? '★'
                        : '☆';

            }


            /*
            |--------------------------------------------------------------------------
            | Option HTML
            |--------------------------------------------------------------------------
            */

            html += `

                <div class="feedback-option ${selected ? 'selected' : ''}">

                    <span class="feedback-option-icon">

                        ${icon}

                    </span>


                    <span class="feedback-option-label">

                        ${escapeHtml(optionLabel)}

                    </span>


                    ${
                        selected
                            ? `
                                <span class="feedback-selected-label">
                                    Selected
                                </span>
                            `
                            : ''
                    }

                </div>

            `;

        }
    );


    html += '</div>';


    return html;
}


        /*
        |--------------------------------------------------------------------------
        | Render Star Rating
        |--------------------------------------------------------------------------
        */

        function renderStarRating(item) {

            let rating =
                parseInt(item.answer) || 0;


            let maxScore =
                parseInt(item.max_score) || 5;


            rating =
                Math.max(
                    0,
                    Math.min(
                        rating,
                        maxScore
                    )
                );


            let html =
                '<div class="feedback-stars">';


            for (
                let i = 1;
                i <= maxScore;
                i++
            ) {

                if (
                    i <= rating
                ) {

                    html += `

                        <span class="feedback-star-option">

                            <i class="fas fa-star text-warning"></i>

                        </span>

                    `;

                } else {

                    html += `

                        <span class="feedback-star-option">

                            <i class="far fa-star text-muted"></i>

                        </span>

                    `;

                }

            }


            html += `

                <span class="ms-2">

                    ${escapeHtml(rating)}
                    /
                    ${escapeHtml(maxScore)}

                </span>

            `;


            html += '</div>';


            return html;

        }


        /*
        |--------------------------------------------------------------------------
        | Render Text Answer
        |--------------------------------------------------------------------------
        */

        function renderTextAnswer(answer) {

            if (
                answer === null ||
                answer === undefined ||
                String(answer).trim() === ''
            ) {

                return `

                    <span class="feedback-empty">

                        No answer provided

                    </span>

                `;

            }


            return `

                <div class="feedback-text-answer">

                    ${escapeHtml(answer)}

                </div>

            `;

        }


        /*
        |--------------------------------------------------------------------------
        | Find Matching Option
        |--------------------------------------------------------------------------
        */

        function findMatchingOption(
            options,
            value
        ) {

            if (
                !Array.isArray(options)
            ) {

                return null;

            }


            let searchValue =
                String(value ?? '')
                    .trim()
                    .toLowerCase();


            if (
                searchValue === ''
            ) {

                return null;

            }


            let matched =
                options.find(function(option) {

                    let optionValue =
                        String(
                            option.value ??
                            ''
                        )
                        .trim()
                        .toLowerCase();


                    let optionLabel =
                        String(
                            option.label ??
                            option.text ??
                            ''
                        )
                        .trim()
                        .toLowerCase();


                    return (
                        optionValue === searchValue ||
                        optionLabel === searchValue
                    );

                });


            return matched || null;

        }


        /*
        |--------------------------------------------------------------------------
        | Render Normal Answer
        |--------------------------------------------------------------------------
        */

        function renderAnswer(item) {

            let answer =
                item.answer ?? '';


            /*
            |--------------------------------------------------------------------------
            | Text
            |--------------------------------------------------------------------------
            */

            if (
                item.question_type ===
                'text'
            ) {

                return renderTextAnswer(
                    answer
                );

            }


            /*
            |--------------------------------------------------------------------------
            | Star Rating
            |--------------------------------------------------------------------------
            */

            if (
                item.question_type ===
                'starrating'
            ) {

                return renderStarRating(
                    item
                );

            }


            /*
            |--------------------------------------------------------------------------
            | Rating
            |--------------------------------------------------------------------------
            */

            if (
                item.question_type ===
                'rating'
            ) {

                return `

                    <strong>

                        ${escapeHtml(answer || 0)}
                        /
                        ${escapeHtml(item.max_score || 5)}

                    </strong>

                `;

            }


            /*
            |--------------------------------------------------------------------------
            | Linear Scale
            |--------------------------------------------------------------------------
            */

            if (
                item.question_type ===
                'linearscale'
            ) {

                return `

                    <strong>

                        ${escapeHtml(answer || 0)}
                        /
                        ${escapeHtml(item.max_score || 5)}

                    </strong>

                `;

            }


            /*
            |--------------------------------------------------------------------------
            | Checkbox
            |--------------------------------------------------------------------------
            */

            if (
                item.question_type ===
                'checkbox'
            ) {

                let values =
                    normalizeAnswerValues(
                        answer
                    );


                if (
                    values.length === 0
                ) {

                    return `

                        <span class="feedback-empty">

                            No option selected

                        </span>

                    `;

                }


                let displayValues = [];


                /*
                |--------------------------------------------------------------------------
                | Match by BOTH value and label
                |--------------------------------------------------------------------------
                */

                values.forEach(function(value) {

                    let matchedOption =
                        findMatchingOption(
                            item.options,
                            value
                        );


                    if (
                        matchedOption
                    ) {

                        displayValues.push(
                            matchedOption.label ??
                            matchedOption.value
                        );

                    } else {

                        displayValues.push(
                            value
                        );

                    }

                });


                /*
                |--------------------------------------------------------------------------
                | Remove duplicates
                |--------------------------------------------------------------------------
                */

                displayValues =
                    [...new Set(displayValues)];


                return displayValues
                    .map(function(value) {

                        return `

                            <span class="badge bg-primary me-1 mb-1">

                                ${escapeHtml(value)}

                            </span>

                        `;

                    })
                    .join('');

            }


            /*
            |--------------------------------------------------------------------------
            | Radio
            |--------------------------------------------------------------------------
            */

            if (
                item.question_type ===
                'radio'
            ) {

                if (
                    String(answer).trim() === ''
                ) {

                    return `

                        <span class="feedback-empty">

                            No option selected

                        </span>

                    `;

                }


                /*
                |--------------------------------------------------------------------------
                | Match answer with option label/value
                |--------------------------------------------------------------------------
                */

                let selectedOption =
                    findMatchingOption(
                        item.options,
                        answer
                    );


                let displayAnswer =
                    selectedOption
                        ? (
                            selectedOption.label ??
                            selectedOption.value
                        )
                        : answer;


                return `

                    <strong>

                        ${escapeHtml(displayAnswer)}

                    </strong>

                `;

            }


            /*
            |--------------------------------------------------------------------------
            | Default
            |--------------------------------------------------------------------------
            */

            if (
                String(answer).trim() === ''
            ) {

                return `

                    <span class="feedback-empty">

                        No answer provided

                    </span>

                `;

            }


            return escapeHtml(answer);

        }


        /*
        |--------------------------------------------------------------------------
        | Show Answer Modal
        |--------------------------------------------------------------------------
        */

        $(document).on(
            'click',
            '.graph-view',
            function() {

                let submissionId =
                    $(this).data('id');


                /*
                |--------------------------------------------------------------------------
                | Reset Modal
                |--------------------------------------------------------------------------
                */

                $('#answerLoader').show();

                $('#answerContent').hide();

                $('#answerError').hide();

                $('#questionsContainer')
                    .html('');

                $('#submissionName')
                    .text('');

                $('#submissionEmail')
                    .text('');

                $('#submissionLocation')
                    .text('-');

                $('#submissionDesignation')
                    .text('-');

                $('#submissionDepartment')
                    .text('-');

                $('#submissionFunction')
                    .text('-');

                $('#submissionComments')
                    .text('-');

                $('#overallScore')
                    .text('0 / 0');

                $('#overallPercentage')
                    .text('0%');


                /*
                |--------------------------------------------------------------------------
                | Validate ID
                |--------------------------------------------------------------------------
                */

                if (
                    !submissionId
                ) {

                    $('#answerLoader').hide();

                    $('#answerError')
                        .text(
                            'Invalid submission ID.'
                        )
                        .show();

                    $('#answerModal')
                        .modal('show');

                    return;

                }


                /*
                |--------------------------------------------------------------------------
                | Open Modal
                |--------------------------------------------------------------------------
                */

                $('#answerModal')
                    .modal('show');


                /*
                |--------------------------------------------------------------------------
                | AJAX
                |--------------------------------------------------------------------------
                */

                $.ajax({

                    url:
                        "{{ route('feedback.submission.answers', ':id') }}"
                            .replace(
                                ':id',
                                submissionId
                            ),

                    type: 'GET',

                    dataType: 'json',


                    /*
                    |--------------------------------------------------------------------------
                    | Success
                    |--------------------------------------------------------------------------
                    */

                    success: function(response) {

                        $('#answerLoader')
                            .hide();


                        /*
                        |--------------------------------------------------------------------------
                        | Error Response
                        |--------------------------------------------------------------------------
                        */

                        if (
                            !response ||
                            !response.success
                        ) {

                            $('#answerError')
                                .text(
                                    'Unable to load feedback answers.'
                                )
                                .show();

                            return;

                        }


                        $('#answerContent')
                            .show();


                        /*
                        |--------------------------------------------------------------------------
                        | Employee Information
                        |--------------------------------------------------------------------------
                        */

                        let submission =
                            response.submission ||
                            {};


                        let employeeName =
                            submission.name ||
                            'Anonymous';


                        let employeeEmail =
                            submission.email ||
                            '';


                        $('#submissionName')
                            .text(
                                employeeName
                            );


                        $('#submissionEmail')
                            .text(
                                employeeEmail
                            );


                        $('#submissionLocation')
                            .text(
                                submission.location ||
                                '-'
                            );


                        $('#submissionDesignation')
                            .text(
                                submission.designation ||
                                '-'
                            );


                        $('#submissionDepartment')
                            .text(
                                submission.department ||
                                '-'
                            );


                        $('#submissionFunction')
                            .text(
                                submission.function ||
                                '-'
                            );


                        $('#submissionComments')
                            .text(
                                submission.comments ||
                                '-'
                            );


                        /*
                        |--------------------------------------------------------------------------
                        | Overall Score
                        |--------------------------------------------------------------------------
                        */

                        let totalScore =
                            response.total_score ?? 0;


                        let maximumScore =
                            response.maximum_score ?? 0;


                        let overallPercentage =
                            response.percentage ?? 0;


                        $('#overallScore')
                            .text(
                                totalScore +
                                ' / ' +
                                maximumScore
                            );


                        $('#overallPercentage')
                            .text(
                                overallPercentage +
                                '%'
                            );


                        /*
                        |--------------------------------------------------------------------------
                        | Questions
                        |--------------------------------------------------------------------------
                        */

                        let html = '';

                        let questions =
                            Array.isArray(
                                response.questions
                            )
                                ? response.questions
                                : [];


                        $.each(

                            questions,

                            function(index, item) {

                                /*
                                |--------------------------------------------------------------------------
                                | Question
                                |--------------------------------------------------------------------------
                                */

                                let questionText =
                                    escapeHtml(
                                        item.question ||
                                        ''
                                    );


                                /*
                                |--------------------------------------------------------------------------
                                | Options
                                |--------------------------------------------------------------------------
                                */

                                let optionsHtml =
                                    renderFeedbackOptions(
                                        item
                                    );


                                /*
                                |--------------------------------------------------------------------------
                                | Actual Answer
                                |--------------------------------------------------------------------------
                                */

                                let answerDisplay =
                                    renderAnswer(
                                        item
                                    );


                                /*
                                |--------------------------------------------------------------------------
                                | Score
                                |--------------------------------------------------------------------------
                                */

                                let scoreHtml = '';


                                if (
                                    item.is_scored === true ||
                                    item.is_scored === 1 ||
                                    item.is_scored === '1'
                                ) {

                                    scoreHtml = `

                                        <div class="feedback-score">

                                            Score:

                                            <strong>

                                                ${escapeHtml(
                                                    item.score ?? 0
                                                )}

                                                /

                                                ${escapeHtml(
                                                    item.max_score ?? 0
                                                )}

                                            </strong>

                                        </div>

                                    `;

                                }


                                /*
                                |--------------------------------------------------------------------------
                                | Question HTML
                                |--------------------------------------------------------------------------
                                */

                                html += `

                                    <div class="feedback-answer-item">

                                        {{-- Question --}}

                                        <div class="feedback-question-text">

                                            <span class="feedback-question-number">

                                                ${index + 1}.

                                            </span>

                                            ${questionText}

                                        </div>


                                        {{-- Question Type --}}

                                        <div>

                                            <span class="feedback-type">

                                                Type:

                                                ${escapeHtml(
                                                    formatQuestionType(
                                                        item.question_type
                                                    )
                                                )}

                                            </span>

                                        </div>


                                        {{-- Answer Box --}}

                                        <div class="feedback-answer-box">

                                            <div class="feedback-answer-label">

                                                Answer

                                            </div>


                                            {{-- All Options --}}

                                            ${optionsHtml}


                                            {{-- Actual Answer --}}

                                            ${
                                                item.question_type === 'text' ||
                                                !optionsHtml
                                                    ? `

                                                        <div class="feedback-answer-value mt-2">

                                                            ${answerDisplay}

                                                        </div>

                                                      `
                                                    : ''
                                            }


                                            {{-- Score --}}

                                            ${scoreHtml}

                                        </div>

                                    </div>

                                `;

                            }
                        );


                        /*
                        |--------------------------------------------------------------------------
                        | Empty Questions
                        |--------------------------------------------------------------------------
                        */

                        if (
                            questions.length === 0
                        ) {

                            html = `

                                <div class="alert alert-info">

                                    No answers found for this submission.

                                </div>

                            `;

                        }


                        /*
                        |--------------------------------------------------------------------------
                        | Render Questions
                        |--------------------------------------------------------------------------
                        */

                        $('#questionsContainer')
                            .html(
                                html
                            );

                    },


                    /*
                    |--------------------------------------------------------------------------
                    | AJAX Error
                    |--------------------------------------------------------------------------
                    */

                    error: function(xhr) {

                        $('#answerLoader')
                            .hide();

                        $('#answerContent')
                            .hide();


                        let message =
                            'Something went wrong while loading the answers.';


                        /*
                        |--------------------------------------------------------------------------
                        | Try to get Laravel error message
                        |--------------------------------------------------------------------------
                        */

                        if (
                            xhr.responseJSON &&
                            xhr.responseJSON.message
                        ) {

                            message =
                                xhr.responseJSON.message;

                        }


                        $('#answerError')
                            .text(
                                message
                            )
                            .show();

                    }

                });

            }
        );

    </script>

@endpush