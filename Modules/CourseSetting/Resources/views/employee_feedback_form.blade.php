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
                            <h3 class="mb-0"> @if(!isset($question))
                                {{__('courses.Add New Feedback Form') }}
                                @else
                                {{__('courses.Update Feedback Form')}}
                                @endif</h3>

                        </div>
                    </div>

                    <div class="row pt-0">
                        @if(isModuleActive('FrontendMultiLang'))
                        <ul class="nav nav-tabs no-bottom-border  mt-sm-md-20 mb-10 ml-3" role="tablist">
                            @foreach ($LanguageList as $key => $language)
                            <li class="nav-item">
                                <a class="nav-link  @if (auth()->user()->language_code == $language->code) active @endif"
                                    href="#element{{$language->code}}" role="tab"
                                    data-bs-toggle="tab">{{ $language->native }} </a>
                            </li>
                            @endforeach
                        </ul>
                        @endif
                    </div>


                    @if (isset($feedbackform))
                    <form action="{{route('feedbackforms.update')}}" method="POST" id="gallery-form"
                        name="gallery-form" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="{{$feedbackform->id}}">
                        @else

                        <form action="{{route('employeefeedbackform.store') }}" method="POST" id="gallery-form"
                            name="gallery-form" enctype="multipart/form-data">

                            @endif
                            @csrf


                            <div class="row">
                                <div class="col-lg-12 mt-10">
                                    <div class="mb-15">
                                        <label class="primary_input_label" for="question">{{ __('courses.Form Name') }}
                                            <span class="text-danger">*</span></label>
                                        <input class="primary_input_field" type="text" name="form_name"
                                            id="question" value="{{ isset($feedbackform) ? $feedbackform->form_name : '' }}"
                                            placeholder="{{ __('courses.Form Name') }}">
                                        @error('form_name')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror

                                    </div>
                                </div>





                                <div class="col-lg-12 text-center">
                                    <div class="d-flex justify-content-center pt_20">
                                        <button type="submit" class="primary-btn semi_large fix-gr-bg"
                                            data-bs-toggle="tooltip" title="Save Form" id="save_button_parent">
                                            <i class=" fa fa-check "></i>
                                            @if(!isset($question))
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
                            <h3 class="mb-0" id="page_title">{{__('courses.Feedback Questions')}}</h3>
                        </div>
                    </div>
                    <div class="QA_section QA_section_heading_custom check_box_table">
                        <div class="QA_table ">
                            <!-- table-responsive -->
                            <div class="">
                                <table id="lms_table" class="table table-data">
                                    <thead>
                                        <tr>
                                            <th scope="col">{{ __('common.SL') }}</th>
                                            <th scope="col">{{ __('courses.Form Name') }}</th>
                                            <th scope="col">{{ __('common.Status') }}</th>
                                            <th scope="col">{{ __('common.Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($feedbackforms->isEmpty())
                                        <tr>
                                            <td colspan="4" class="text-center">{{ __('courses.No Data Available') }}
                                            </td>
                                        </tr>
                                        @else
                                        @foreach($feedbackforms as $key => $form)
                                        <tr>
                                            <td>{{++$key}}</td>
                                            <td>{{ $form->form_name }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $form->status == 1 ? 'success' : 'danger' }}">
                                                    {{ $form->status == 1 ? __('common.Active') : __('common.Inactive') }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-primary dropdown-toggle"
                                                        type="button" id="dropdownMenuButton" data-bs-toggle="dropdown"
                                                        aria-haspopup="true" aria-expanded="false">
                                                        {{ __('common.Action') }}
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <!-- <a class="dropdown-item answered-users overall-rating" 
                                                            href="{{ route('feedback.answered_users', $form->id) }}" data-id="{{ $form->id }}" data-name="{{ $form->form_name }}" >{{ __('common.OverAll Rating') }}</a> -->
                                                        <a class="dropdown-item answered-users" 
                                                            href="{{ route('feedback.answered_users', $form->id) }}" data-id="{{ $form->id }}" data-name="{{ $form->form_name }}" >{{ __('common.Answered Users') }}</a>
                                                        <!-- <a class="dropdown-item"
                                                            href="{{ route('feedback_questions.edit', $form->id) }}">{{ __('common.Edit') }}</a> -->
                                                         <a class="dropdown-item add-questions" 
                                                            href="{{ route('feedback.add.questions', $form->id) }}"  data-id="{{ $form->id }}" data-name="{{ $form->form_name }}">{{ __('common.Add/Edit Questions') }}</a>
                                                        
                                                        <a class="dropdown-item btn btn-sm btn-primary copy-link" 
                                                            href="javascript:void(0)"  data-id="{{ $form->id }}" data-name="{{ $form->form_name }}" data-url="{{ route('feedback.show', $form->id) }}">{{ __('common.Copy Link') }}</a>
                                                        
                                                        <a class="dropdown-item btn btn-sm btn-primary download-excel" 
                                                            href="javascript:void(0)"  data-id="{{ $form->id }}" data-name="{{ $form->form_name }}" data-url="{{ route('feedback.show', $form->id) }}">{{ __('common.Download Excel') }}</a>
                                                        <a href="{{ route('feedback.preview', $form->id) }}"
                                                            class="dropdown-item btn btn-sm btn-primary form-preview"
                                                            target="_blank">
                                                                {{ __('common.Preview') }}
                                                            </a> 
                                                            
                                                        <form
                                                            action=""
                                                            method="POST" style="display: inline;">
                                                            @csrf
                                                            <button type="submit"
                                                                class="dropdown-item text-danger">{{ __('common.Delete') }}</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </td>
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

    <div class="modal fade" id="assignQuestionsModal" tabindex="-1" aria-hidden="true">
 
    <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">
                        Assign Questions
                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">
                    </button>
                </div>

                <form id="assignQuestionsForm" method="post">

                    @csrf

                    <input type="hidden"
                        name="feedback_form_id"
                        id="feedback_form_id">

                    <div class="modal-body">

                        <div id="questionsContainer">

                            <div class="text-center py-4">
                                Loading questions...
                            </div>

                        </div>

                    </div>

                    <div class="modal-footer">

                        <button type="button"
                                class="btn btn-secondary"
                                data-bs-dismiss="modal">
                            Cancel
                        </button>

                        <button type="submit"
                                class="btn btn-primary">
                            Save Questions
                        </button>

                    </div>

                </form>

            </div>
        </div>
    </div>
</section>


<div class="modal fade" id="overallRatingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <div>
                    <h5 class="modal-title" id="overallFormName">
                        Overall Rating
                    </h5>

                    <small class="text-muted">
                        Feedback form summary
                    </small>
                </div>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>
            </div>

            <div class="modal-body">

                {{-- Loader --}}
                <div id="overallRatingLoader" class="text-center py-5">
                    <div class="spinner-border" role="status"></div>
                    <div class="mt-2">
                        Loading overall rating...
                    </div>
                </div>

                {{-- Error --}}
                <div id="overallRatingError"
                     class="alert alert-danger"
                     style="display:none;">
                </div>

                <div id="overallRatingContent" style="display:none;">

                    {{-- Summary --}}
                    <div class="row g-3 mb-4">

                        <div class="col-md-4">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <div class="text-muted">
                                        Total Users
                                    </div>

                                    <h3 class="mb-0"
                                        id="overallUserCount">
                                        0
                                    </h3>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <div class="text-muted">
                                        Total Questions
                                    </div>

                                    <h3 class="mb-0"
                                        id="overallQuestionCount">
                                        0
                                    </h3>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <div class="text-muted">
                                        Overall Rating
                                    </div>

                                    <h3 class="mb-2">
                                        <span id="overallPercentage">0%</span>
                                    </h3>

                                    <div class="progress"
                                         style="height:10px;">

                                        <div class="progress-bar"
                                             id="overallRatingProgress"
                                             role="progressbar"
                                             style="width:0%;">
                                        </div>

                                    </div>

                                    <div class="mt-2 text-muted">
                                        <span id="overallScore">
                                            0 / 0
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>


                    {{-- Questions --}}
                    <div class="card border-0 shadow-sm">

                        <div class="card-header bg-white">
                            <h5 class="mb-0">
                                Question Wise Rating
                            </h5>
                        </div>

                        <div class="card-body">

                            <div id="overallQuestionsContainer">
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>
</div>

<input type="hidden" name="status_route" class="status_route" value="{{ route('gallery.update') }}">
@include('backend.partials.delete_modal')
@endsection
@push('scripts')

<script type="application/javascript">

dataTableOptions = updateColumnExportOption(
    dataTableOptions,
    [0, 1, 2, 3, 4]
);

let table = $('#lms_table').DataTable(dataTableOptions);


// ========================================
// OPEN ASSIGN QUESTIONS MODAL
// ========================================

$(document).on('click', '.assign-questions', function () {

    let formId = $(this).data('id');
    let formName = $(this).data('name');

    $('#feedback_form_id').val(formId);

    $('#assignQuestionsModal .modal-title').text(
        'Assign Questions - ' + formName
    );

    $('#questionsContainer').html(`
        <div class="text-center py-4">
            Loading questions...
        </div>
    `);

    // Bootstrap 4
    $('#assignQuestionsModal').modal('show');


    $.ajax({

        url: "{{ route('assign.questions', ':id') }}".replace(':id', formId),

        type: "GET",

        success: function (response) {

            let html = '';

            if (response.questions.length === 0) {

                html = `
                    <div class="alert alert-warning">
                        No active questions available.
                    </div>
                `;

            } else {

                // Check if all questions are already assigned
                let allChecked =
                    response.assigned_question_ids.length ===
                    response.questions.length;


                // ========================================
                // CHECK ALL
                // ========================================

                html += `
                    <div class="form-check mb-3 pb-2 border-bottom">

                        <input
                            class="form-check-input"
                            type="checkbox"
                            id="checkAllQuestions"
                            ${allChecked ? 'checked' : ''}
                        >

                        <label
                            class="form-check-label fw-bold"
                            for="checkAllQuestions">

                            Check All

                        </label>

                    </div>
                `;


                // ========================================
                // QUESTIONS
                // ========================================

                response.questions.forEach(function (question) {

                    let checked =
                        response.assigned_question_ids.includes(
                            question.id
                        )
                        ? 'checked'
                        : '';


                    html += `
                        <div class="form-check mb-3">

                            <input
                                class="form-check-input question-checkbox"
                                type="checkbox"
                                name="question_ids[]"
                                value="${question.id}"
                                id="question_${question.id}"
                                ${checked}
                            >

                            <label
                                class="form-check-label"
                                for="question_${question.id}">

                                ${question.question}

                            </label>

                        </div>
                    `;

                });

            }

            $('#questionsContainer').html(html);
        },


        error: function () {

            $('#questionsContainer').html(`
                <div class="alert alert-danger">
                    Unable to load questions.
                </div>
            `);

        }

    });

});


// ========================================
// CHECK ALL → SELECT / UNSELECT ALL
// ========================================

$(document).on('change', '#checkAllQuestions', function () {

    $('.question-checkbox').prop(
        'checked',
        this.checked
    );

});


// ========================================
// INDIVIDUAL QUESTION CHECKBOX
// ========================================

$(document).on('change', '.question-checkbox', function () {

    let total = $('.question-checkbox').length;

    let checked = $('.question-checkbox:checked').length;

    $('#checkAllQuestions').prop(
        'checked',
        total === checked
    );

});


// ========================================
// SAVE ASSIGNED QUESTIONS
// ========================================

$('#assignQuestionsForm').on('submit', function (e) {

    e.preventDefault();

    let form = this;

    let formData = new FormData(form);


    $.ajax({

        url: "{{ route('assign.questions.store') }}",

        type: "POST",

        data: formData,

        processData: false,

        contentType: false,


        beforeSend: function () {

            $(form)
                .find('button[type="submit"]')
                .prop('disabled', true)
                .text('Saving...');

        },


        success: function (response) {

            if (response.success) {

                $('#assignQuestionsModal').modal('hide');

                alert(response.message);

            }

        },


        error: function (xhr) {

            if (xhr.status === 422) {

                alert(
                    Object.values(xhr.responseJSON.errors)
                        .flat()
                        .join('\n')
                );

            } else {

                alert('Something went wrong.');

            }

        },


        complete: function () {

            $(form)
                .find('button[type="submit"]')
                .prop('disabled', false)
                .text('Save Questions');

        }

    });

});

$(document).on('click', '.copy-link', function () {

    let url = $(this).data('url');

    navigator.clipboard.writeText(url).then(function () {

        alert('Link copied successfully!');

    }).catch(function () {

        alert('Unable to copy link.');

    });

});

$(document).on('click', '.overall-rating', function (e) {

    e.preventDefault();

    let formId = $(this).data('id');
    let formName = $(this).data('name');

    $('#overallFormName').text(formName);

    $('#overallRatingLoader').show();
    $('#overallRatingContent').hide();
    $('#overallRatingError').hide();

    $('#overallQuestionsContainer').html('');

    $('#overallRatingModal').modal('show');


    let url = "{{ route('feedback.overall_rating', ':id') }}"
        .replace(':id', formId);


    $.ajax({

        url: url,

        type: 'GET',

        success: function (response) {

            $('#overallRatingLoader').hide();

            if (!response.success) {

                $('#overallRatingError')
                    .text('Unable to load overall rating.')
                    .show();

                return;
            }


            $('#overallRatingContent').show();


            /*
             * Summary
             */

            $('#overallUserCount')
                .text(response.user_count);

            $('#overallQuestionCount')
                .text(response.question_count);

            $('#overallPercentage')
                .text(response.percentage + '%');

            $('#overallScore')
                .text(
                    response.total_score +
                    ' / ' +
                    response.maximum_score
                );


            $('#overallRatingProgress')
                .css(
                    'width',
                    response.percentage + '%'
                )
                .attr(
                    'aria-valuenow',
                    response.percentage
                );


            /*
             * Questions
             */

            let html = '';

            $.each(response.questions, function (index, item) {

                let answerHtml = '';

                /*
                 * Rating question
                 */
                if (item.question_type === 'rating') {

                    let answers = item.answers;

                    answerHtml = `
                        <div class="mt-3">

                            <div class="row text-center g-2">

                                <div class="col">
                                    <div class="border rounded p-2">
                                        <div class="fw-bold">
                                            ${answers[1] ?? 0}
                                        </div>
                                        <small class="text-muted">
                                            Strongly Disagree
                                        </small>
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="border rounded p-2">
                                        <div class="fw-bold">
                                            ${answers[2] ?? 0}
                                        </div>
                                        <small class="text-muted">
                                            Disagree
                                        </small>
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="border rounded p-2">
                                        <div class="fw-bold">
                                            ${answers[3] ?? 0}
                                        </div>
                                        <small class="text-muted">
                                            Neutral
                                        </small>
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="border rounded p-2">
                                        <div class="fw-bold">
                                            ${answers[4] ?? 0}
                                        </div>
                                        <small class="text-muted">
                                            Agree
                                        </small>
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="border rounded p-2">
                                        <div class="fw-bold">
                                            ${answers[5] ?? 0}
                                        </div>
                                        <small class="text-muted">
                                            Strongly Agree
                                        </small>
                                    </div>
                                </div>

                            </div>

                        </div>
                    `;
                }


                /*
                 * Radio question
                 */
                else if (item.question_type === 'radio') {

                    let yesCount = item.answers.yes ?? 0;
                    let noCount = item.answers.no ?? 0;

                    let yesPercentage = item.answered_count > 0
                        ? Math.round(
                            (yesCount / item.answered_count) * 100
                        )
                        : 0;

                    let noPercentage = item.answered_count > 0
                        ? Math.round(
                            (noCount / item.answered_count) * 100
                        )
                        : 0;


                    answerHtml = `

                        <div class="mt-3">

                            <div class="mb-3">

                                <div class="d-flex justify-content-between">
                                    <span>
                                        <strong>Yes</strong>
                                    </span>

                                    <span>
                                        ${yesCount}
                                        (${yesPercentage}%)
                                    </span>
                                </div>

                                <div class="progress"
                                     style="height:8px;">

                                    <div class="progress-bar bg-success"
                                         style="width:${yesPercentage}%;">
                                    </div>

                                </div>

                            </div>


                            <div>

                                <div class="d-flex justify-content-between">
                                    <span>
                                        <strong>No</strong>
                                    </span>

                                    <span>
                                        ${noCount}
                                        (${noPercentage}%)
                                    </span>
                                </div>

                                <div class="progress"
                                     style="height:8px;">

                                    <div class="progress-bar bg-danger"
                                         style="width:${noPercentage}%;">
                                    </div>

                                </div>

                            </div>

                        </div>
                    `;
                }


                html += `

                    <div class="question-item mb-4">

                        <div class="d-flex justify-content-between
                                    align-items-start mb-2">

                            <div>

                                <div class="fw-semibold">

                                    ${index + 1}.
                                    ${item.question}

                                </div>

                                <small class="text-muted">

                                    Answered:
                                    ${item.answered_count}
                                    / ${item.user_count} users

                                </small>

                            </div>

                            <div class="text-end">

                                <strong>
                                    ${item.percentage}%
                                </strong>

                            </div>

                        </div>


                        <div class="progress"
                             style="height:10px;">

                            <div class="progress-bar"
                                 role="progressbar"
                                 style="width:${item.percentage}%">

                            </div>

                        </div>


                        <div class="d-flex justify-content-between
                                    mt-2">

                            <small class="text-muted">

                                Score:
                                ${item.total_score}
                                / ${item.maximum_score}

                            </small>

                            <small class="text-muted">

                                ${item.answered_count}
                                responses

                            </small>

                        </div>


                        ${answerHtml}

                    </div>

                    <hr>

                `;
            });


            $('#overallQuestionsContainer')
                .html(html);

        },

        error: function (xhr) {

            $('#overallRatingLoader').hide();

            $('#overallRatingError')
                .text(
                    'Something went wrong while loading overall rating.'
                )
                .show();

            console.log(xhr);
        }

    });

});

$(document).on('click', '.download-excel', function (e) {

    e.preventDefault();

    let id = $(this).data('id');

    let url = "{{ route('feedback.download.excel', ':id') }}"
        .replace(':id', id);

    window.location.href = url;
});

</script>
@endpush
