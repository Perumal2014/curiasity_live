@extends('backend.master')


@section('table')
    tenant_galleries
@endsection


@section('mainContent')

@include("backend.partials.alertMessage")


<div class="container-fluid feedback-builder-page">

    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}

    <div class="feedback-page-header">

        <div>

            <div class="feedback-breadcrumb">

                <span>Feedback</span>

                <i class="fa fa-angle-right"></i>

                <span>Question Builder</span>

            </div>


            <h3 class="feedback-page-title">

                {{ ($isEdit ?? false)
                    ? 'Edit Feedback Questions'
                    : 'Create Feedback Form'
                }}

            </h3>


            <p class="feedback-page-subtitle">

                Create questions and customize how employees can respond.

            </p>

        </div>


        <div>

            <a
                href="{{ route('employeefeedbackform.store') }}"
                class="feedback-back-btn"
            >
                <i class="fa fa-arrow-left"></i>

                Back

            </a>

        </div>

    </div>



    {{-- =========================================================
         FORM
    ========================================================== --}}

    <form
        method="POST"
        action="{{ ($isEdit ?? false)
            ? route('feedbackquestions.update')
            : route('feedbackquestions.store')
        }}"
        id="feedbackQuestionForm"
    >

        @csrf


       


        {{-- =====================================================
             FEEDBACK FORM ID
        ====================================================== --}}

        <input
            type="hidden"
            name="feedback_form_id"
            value="{{ $feedback_form_id ?? '' }}"
        >


        {{-- =====================================================
             QUESTIONS
        ====================================================== --}}

        <div id="questionsContainer"></div>


        {{-- =====================================================
             ADD QUESTION
        ====================================================== --}}

        <div class="add-question-section">

            <button
                type="button"
                id="addQuestionBtn"
                class="add-question-btn"
            >

                <span class="add-question-icon">

                    <i class="fa fa-plus"></i>

                </span>

                <span>
                    Add Question
                </span>

            </button>


            <p class="add-question-help">

                Add another question to your feedback form.

            </p>

        </div>



        {{-- =====================================================
             FORM STATUS
        ====================================================== --}}

        <div class="feedback-settings-card">

            <div class="settings-card-left">

                <div class="settings-icon">

                    <i class="fa fa-toggle-on"></i>

                </div>


                <div>

                    <h5>
                        Form Status
                    </h5>

                    <p>
                        Active forms can be used by employees.
                    </p>

                </div>

            </div>


            <div class="settings-card-right">

                <label class="modern-switch">

                    <input
                        type="hidden"
                        name="status"
                        value="0"
                    >

                    <input
                        type="checkbox"
                        name="status"
                        value="1"
                        {{ old(
                            'status',
                            $feedbackForm->status ?? 1
                        ) ? 'checked' : '' }}
                    >

                    <span class="modern-slider"></span>

                </label>


                <span class="status-label">
                    Active
                </span>

            </div>

        </div>



        {{-- =====================================================
             SAVE
        ====================================================== --}}

        <div class="feedback-save-section">

            <button
                type="submit"
                id="saveFeedbackBtn"
                class="save-feedback-btn"
            >

                <i class="fa fa-check"></i>

                {{ ($isEdit ?? false)
                    ? 'Update Questions'
                    : 'Save Questions'
                }}

            </button>

        </div>


    </form>

</div>

@endsection



{{-- =============================================================
     CSS
============================================================== --}}

@push('styles')

<style>

/* ================================================================
   PAGE
================================================================ */

.feedback-builder-page {

    max-width: 1100px;

    margin: 0 auto;

    padding: 25px 20px 60px;

}


/* ================================================================
   HEADER
================================================================ */

.feedback-page-header {

    display: flex;

    align-items: flex-start;

    justify-content: space-between;

    margin-bottom: 28px;

}


.feedback-breadcrumb {

    display: flex;

    align-items: center;

    gap: 8px;

    font-size: 13px;

    color: #8a94a6;

    margin-bottom: 8px;

}


.feedback-breadcrumb i {

    font-size: 11px;

}


.feedback-page-title {

    margin: 0;

    font-size: 25px;

    font-weight: 700;

    color: #202938;

}


.feedback-page-subtitle {

    margin: 7px 0 0;

    color: #7b8494;

    font-size: 14px;

}


.feedback-back-btn {

    display: inline-flex;

    align-items: center;

    gap: 8px;

    padding: 9px 15px;

    border: 1px solid #e3e7ed;

    border-radius: 8px;

    background: #fff;

    color: #596273;

    font-size: 13px;

    text-decoration: none;

    transition: all .2s ease;

}


.feedback-back-btn:hover {

    background: #f8f9fb;

    color: #202938;

    text-decoration: none;

}


/* ================================================================
   QUESTION CARD
================================================================ */

.question-builder-card {

    background: #fff;

    border: 1px solid #e5e9ef;

    border-radius: 14px;

    margin-bottom: 20px;

    box-shadow: 0 4px 18px rgba(30,42,60,.04);

    overflow: hidden;

    transition: all .2s ease;

}


.question-builder-card:hover {

    border-color: #dce2ea;

    box-shadow: 0 7px 25px rgba(30,42,60,.06);

}


/* ================================================================
   CARD HEADER
================================================================ */

.question-card-top {

    padding: 20px 22px;

    border-bottom: 1px solid #edf0f4;

    display: flex;

    align-items: center;

    justify-content: space-between;

}


.question-card-heading {

    display: flex;

    align-items: center;

    gap: 12px;

}


.question-number {

    width: 35px;

    height: 35px;

    border-radius: 9px;

    background: #f0f3ff;

    color: #4b5fc4;

    display: flex;

    align-items: center;

    justify-content: center;

    font-size: 13px;

    font-weight: 700;

}


.question-heading-text {

    font-size: 14px;

    font-weight: 600;

    color: #303949;

}


.question-heading-subtitle {

    font-size: 12px;

    color: #98a1af;

    margin-top: 2px;

}


.question-drag-handle {

    color: #b6bdc8;

    cursor: grab;

    display: flex;

    gap: 2px;

}


/* ================================================================
   CARD BODY
================================================================ */

.question-card-body {

    padding: 24px 22px;

}


.question-label {

    display: block;

    font-size: 12px;

    font-weight: 600;

    color: #697386;

    margin-bottom: 8px;

    text-transform: uppercase;

    letter-spacing: .4px;

}


/* ================================================================
   QUESTION INPUT
================================================================ */

.question-text-input {

    width: 100%;

    min-height: 50px;

    resize: vertical;

    border: 1px solid #dfe4eb;

    border-radius: 9px;

    padding: 13px 15px;

    color: #252d3a;

    font-size: 14px;

    outline: none;

    transition:
        border-color .2s,
        box-shadow .2s;

    background: #fff;

    box-sizing: border-box;

}


.question-text-input:focus {

    border-color: #667eea;

    box-shadow:
        0 0 0 3px rgba(102,126,234,.08);

}


.question-text-input::placeholder {

    color: #aab1bc;

}


/* ================================================================
   QUESTION TYPE
================================================================ */

.question-type-area {

    margin-top: 20px;

}


.question-type-select {

    width: 100%;

    height: 47px;

    padding: 0 13px;

    border: 1px solid #dfe4eb;

    border-radius: 9px;

    color: #343c4a;

    background: #fff;

    font-size: 14px;

    outline: none;

    cursor: pointer;

}


.question-type-select:focus {

    border-color: #667eea;

    box-shadow:
        0 0 0 3px rgba(102,126,234,.08);

}


/* ================================================================
   ANSWER PREVIEW
================================================================ */

.answer-preview {

    margin-top: 24px;

    padding: 18px;

    background: #fafbfc;

    border: 1px solid #edf0f4;

    border-radius: 10px;

}


.answer-preview-title {

    font-size: 11px;

    font-weight: 700;

    color: #9aa2af;

    text-transform: uppercase;

    letter-spacing: .6px;

    margin-bottom: 14px;

}


/* ================================================================
   TEXT PREVIEW
================================================================ */

.text-answer-preview {

    width: 100%;

    min-height: 90px;

    border: 1px solid #dfe4eb;

    border-radius: 8px;

    background: #fff;

    padding: 13px;

    font-size: 13px;

    color: #9da5b1;

    resize: none;

    box-sizing: border-box;

}


/* ================================================================
   OPTIONS
================================================================ */

.options-list {

    display: flex;

    flex-direction: column;

    gap: 9px;

}


.option-row {

    display: flex;

    align-items: center;

    gap: 10px;

}


.option-control-icon {

    width: 19px;

    height: 19px;

    flex: 0 0 19px;

    display: flex;

    align-items: center;

    justify-content: center;

}


.radio-preview-icon {

    width: 17px;

    height: 17px;

    border: 2px solid #9ca5b2;

    border-radius: 50%;

    box-sizing: border-box;

}


.checkbox-preview-icon {

    width: 17px;

    height: 17px;

    border: 2px solid #9ca5b2;

    border-radius: 4px;

    box-sizing: border-box;

}


.option-input {

    flex: 1;

    height: 42px;

    border: 1px solid #dfe4eb;

    border-radius: 8px;

    padding: 0 12px;

    background: #fff;

    color: #333b49;

    font-size: 13px;

    outline: none;

    box-sizing: border-box;

}


.option-input:focus {

    border-color: #667eea;

    box-shadow:
        0 0 0 3px rgba(102,126,234,.07);

}


.option-input:disabled {

    background: #f8f9fb;

    cursor: not-allowed;

}


.option-delete-btn {

    width: 35px;

    height: 35px;

    border: 0;

    background: transparent;

    color: #a3abb7;

    border-radius: 7px;

    cursor: pointer;

}


.option-delete-btn:hover {

    background: #fff0f0;

    color: #e25555;

}


/* ================================================================
   ADD OPTION
================================================================ */

.add-option-btn {

    margin-top: 13px;

    border: 1px dashed #cbd2dc;

    background: #fff;

    color: #6571c8;

    border-radius: 8px;

    padding: 9px 13px;

    font-size: 12px;

    font-weight: 600;

    cursor: pointer;

    display: inline-flex;

    align-items: center;

    gap: 7px;

}


.add-option-btn:hover {

    background: #f7f8ff;

}


/* ================================================================
   LINEAR SCALE
================================================================ */

.linear-settings {

    display: none;

    margin-bottom: 18px;

    padding: 16px;

    background: #fff;

    border: 1px solid #e2e6ed;

    border-radius: 9px;

}


.linear-settings-title {

    font-size: 12px;

    font-weight: 700;

    color: #4c5667;

    margin-bottom: 14px;

}


.linear-settings-grid {

    display: grid;

    grid-template-columns:
        1fr
        1fr
        1fr;

    gap: 12px;

}


.linear-field {

    display: flex;

    flex-direction: column;

    gap: 6px;

}


.linear-field label {

    font-size: 11px;

    font-weight: 600;

    color: #788293;

}


.linear-field input,
.linear-field select {

    width: 100%;

    height: 40px;

    border: 1px solid #dfe4eb;

    border-radius: 7px;

    padding: 0 10px;

    background: #fff;

    color: #3d4655;

    font-size: 13px;

    outline: none;

    box-sizing: border-box;

}


.linear-field input:focus,
.linear-field select:focus {

    border-color: #667eea;

}


/* ================================================================
   LINEAR PREVIEW
================================================================ */

.linear-preview-wrapper {

    overflow-x: auto;

    padding: 5px 3px 2px;

}


.linear-preview-points {

    display: flex;

    align-items: flex-start;

    justify-content: space-between;

    gap: 5px;

    min-width: 360px;

}


.linear-point {

    flex: 1;

    min-width: 35px;

    text-align: center;

}


.linear-point-circle {

    width: 18px;

    height: 18px;

    border: 2px solid #a1a9b4;

    border-radius: 50%;

    margin: 0 auto 7px;

    box-sizing: border-box;

}


.linear-point-number {

    font-size: 12px;

    font-weight: 600;

    color: #667181;

}


.linear-preview-labels {

    display: flex;

    justify-content: space-between;

    gap: 20px;

    margin-top: 8px;

    min-width: 360px;

}


.linear-preview-label {

    font-size: 11px;

    color: #8992a0;

}


.linear-preview-label:last-child {

    text-align: right;

}


/* ================================================================
   RATING
================================================================ */

.rating-preview {

    display: flex;

    gap: 7px;

    flex-wrap: wrap;

}


.rating-item {

    min-width: 45px;

    height: 39px;

    padding: 0 10px;

    border: 1px solid #dfe4eb;

    border-radius: 7px;

    background: #fff;

    display: flex;

    align-items: center;

    justify-content: center;

    color: #687283;

    font-size: 12px;

    box-sizing: border-box;

}


/* ================================================================
   STAR RATING
================================================================ */

.star-rating-preview {

    display: flex;

    gap: 7px;

}


.star-rating-preview i {

    color: #c2c8d2;

    font-size: 22px;

}


/* ================================================================
   CARD FOOTER
================================================================ */

.question-card-footer {

    padding: 15px 22px;

    border-top: 1px solid #edf0f4;

    display: flex;

    align-items: center;

    justify-content: space-between;

}


.question-actions {

    display: flex;

    align-items: center;

    gap: 7px;

}


.question-action-btn {

    border: 1px solid #e2e6ec;

    background: #fff;

    color: #697386;

    border-radius: 7px;

    height: 35px;

    padding: 0 11px;

    font-size: 12px;

    cursor: pointer;

    display: inline-flex;

    align-items: center;

    gap: 7px;

}


.question-action-btn:hover {

    background: #f8f9fb;

}


.question-action-btn.delete:hover {

    color: #e25555;

    background: #fff5f5;

}


/* ================================================================
   REQUIRED
================================================================ */

.required-wrapper {

    display: flex;

    align-items: center;

    gap: 9px;

}


.required-text {

    font-size: 12px;

    color: #687283;

    font-weight: 500;

}


.required-switch {

    position: relative;

    width: 40px;

    height: 22px;

    display: inline-block;

}


.required-switch input {

    opacity: 0;

    width: 0;

    height: 0;

}


.required-slider {

    position: absolute;

    inset: 0;

    background: #d5dae2;

    border-radius: 30px;

    cursor: pointer;

    transition: .2s;

}


.required-slider:before {

    content: "";

    position: absolute;

    width: 16px;

    height: 16px;

    left: 3px;

    top: 3px;

    background: #fff;

    border-radius: 50%;

    transition: .2s;

    box-shadow:
        0 1px 3px rgba(0,0,0,.15);

}


.required-switch
input:checked
+ .required-slider {

    background: #667eea;

}


.required-switch
input:checked
+ .required-slider:before {

    transform: translateX(18px);

}


/* ================================================================
   ADD QUESTION
================================================================ */

.add-question-section {

    text-align: center;

    padding: 8px 0 30px;

}


.add-question-btn {

    border: 1px dashed #aeb8ca;

    background: #fff;

    color: #5868c6;

    border-radius: 10px;

    padding: 12px 18px;

    font-size: 13px;

    font-weight: 600;

    display: inline-flex;

    align-items: center;

    gap: 9px;

    cursor: pointer;

}


.add-question-btn:hover {

    background: #f7f8ff;

}


.add-question-icon {

    width: 23px;

    height: 23px;

    border-radius: 6px;

    background: #eef0ff;

    display: flex;

    align-items: center;

    justify-content: center;

}


.add-question-help {

    margin: 9px 0 0;

    font-size: 11px;

    color: #a1a9b5;

}


/* ================================================================
   SETTINGS CARD
================================================================ */

.feedback-settings-card {

    border: 1px solid #e5e9ef;

    background: #fff;

    border-radius: 13px;

    padding: 19px 21px;

    display: flex;

    justify-content: space-between;

    align-items: center;

    box-shadow:
        0 4px 18px rgba(30,42,60,.03);

}


.settings-card-left {

    display: flex;

    align-items: center;

    gap: 13px;

}


.settings-icon {

    width: 40px;

    height: 40px;

    border-radius: 9px;

    background: #f1f3ff;

    color: #6170cc;

    display: flex;

    align-items: center;

    justify-content: center;

}


.settings-card-left h5 {

    margin: 0 0 3px;

    font-size: 14px;

    font-weight: 600;

    color: #303949;

}


.settings-card-left p {

    margin: 0;

    font-size: 11px;

    color: #969fac;

}


.settings-card-right {

    display: flex;

    align-items: center;

    gap: 9px;

}


.status-label {

    font-size: 12px;

    font-weight: 600;

    color: #5e6979;

}


/* ================================================================
   STATUS SWITCH
================================================================ */

.modern-switch {

    position: relative;

    width: 42px;

    height: 23px;

    display: inline-block;

}


.modern-switch input[type="checkbox"] {

    opacity: 0;

    width: 0;

    height: 0;

}


.modern-slider {

    position: absolute;

    inset: 0;

    background: #d4dae2;

    border-radius: 30px;

    cursor: pointer;

}


.modern-slider:before {

    content: "";

    position: absolute;

    width: 17px;

    height: 17px;

    top: 3px;

    left: 3px;

    background: #fff;

    border-radius: 50%;

    transition: .2s;

}


.modern-switch
input:checked
+ .modern-slider {

    background: #667eea;

}


.modern-switch
input:checked
+ .modern-slider:before {

    transform: translateX(19px);

}


/* ================================================================
   SAVE
================================================================ */

.feedback-save-section {

    display: flex;

    justify-content: flex-end;

    padding-top: 20px;

}


.save-feedback-btn {

    min-width: 155px;

    height: 45px;

    border: 0;

    border-radius: 9px;

    background: #5969c9;

    color: #fff;

    font-size: 13px;

    font-weight: 600;

    cursor: pointer;

    display: inline-flex;

    align-items: center;

    justify-content: center;

    gap: 8px;

}


.save-feedback-btn:hover {

    background: #4e5db7;

}


.save-feedback-btn:disabled {

    opacity: .65;

    cursor: not-allowed;

}


/* ================================================================
   INVALID
================================================================ */

.question-text-input.is-invalid,
.question-type-select.is-invalid,
.option-input.is-invalid {

    border-color: #e25555 !important;

    box-shadow:
        0 0 0 3px rgba(226,85,85,.07);

}


/* ================================================================
   RESPONSIVE
================================================================ */

@media (max-width: 767px) {

    .feedback-builder-page {

        padding:
            18px
            12px
            40px;

    }


    .feedback-page-header {

        flex-direction: column;

        gap: 15px;

    }


    .feedback-page-title {

        font-size: 21px;

    }


    .question-card-top,
    .question-card-body,
    .question-card-footer {

        padding-left: 15px;

        padding-right: 15px;

    }


    .question-card-footer {

        align-items: flex-start;

        gap: 15px;

        flex-direction: column;

    }


    .feedback-settings-card {

        align-items: flex-start;

        gap: 15px;

    }


    .question-actions {

        flex-wrap: wrap;

    }


    .linear-settings-grid {

        grid-template-columns: 1fr;

    }

}

</style>

@endpush



{{-- =============================================================
     JAVASCRIPT
============================================================== --}}

@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>

$(document).ready(function () {


    /* ============================================================
       INITIAL DATA

       IMPORTANT:
       The controller creates this data.

       Blade does NOT access:
           $question->id
           $question->options
           $option->id

       This avoids the string/object error.
    ============================================================ */

    let initialQuestions =
        @json($initialQuestions ?? []);


    let questionCounter = 0;



    /* ============================================================
       SWEET ALERT
    ============================================================ */

    function showAlert(options)
    {

        if (
            typeof window.Swal !== 'undefined'
        ) {

            return window.Swal.fire(options);

        }


        alert(
            options.text ||
            options.title ||
            'Please check the form.'
        );

    }



    /* ============================================================
       ESCAPE HTML
    ============================================================ */

    function escapeHtml(value)
    {

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



    /* ============================================================
       NORMALIZE QUESTION TYPE
    ============================================================ */

    function normalizeQuestionType(type)
    {

        type = String(type || '')
            .toLowerCase()
            .trim();


        /*
        |--------------------------------------------------------------------------
        | Multiple Choice
        |--------------------------------------------------------------------------
        */

        if (
            type === 'multiple_choice' ||
            type === 'multiple choice' ||
            type === 'mcq'
        ) {

            return 'radio';

        }


        /*
        |--------------------------------------------------------------------------
        | Checkbox
        |--------------------------------------------------------------------------
        */

        if (
            type === 'checkboxes' ||
            type === 'checkbox'
        ) {

            return 'checkbox';

        }


        /*
        |--------------------------------------------------------------------------
        | Text
        |--------------------------------------------------------------------------
        */

        if (
            type === 'text_answer' ||
            type === 'text answer'
        ) {

            return 'text';

        }


        /*
        |--------------------------------------------------------------------------
        | Star Rating
        |--------------------------------------------------------------------------
        */

        if (
            type === 'star_rating' ||
            type === 'star rating' ||
            type === 'stars'
        ) {

            return 'starrating';

        }


        /*
        |--------------------------------------------------------------------------
        | Linear Scale
        |--------------------------------------------------------------------------
        */

        if (
            type === 'linear_scale' ||
            type === 'linear scale'
        ) {

            return 'linearscale';

        }


        return type || 'radio';

    }



    /* ============================================================
       OPTION VALUE
    ============================================================ */

    function getOptionValue(option)
    {

        /*
        |--------------------------------------------------------------------------
        | String
        |--------------------------------------------------------------------------
        */

        if (
            typeof option === 'string' ||
            typeof option === 'number'
        ) {

            return String(option);

        }


        /*
        |--------------------------------------------------------------------------
        | Null
        |--------------------------------------------------------------------------
        */

        if (!option) {

            return '';

        }


        /*
        |--------------------------------------------------------------------------
        | Object
        |--------------------------------------------------------------------------
        */

        return String(
            option.option_text ??
            option.option ??
            option.value ??
            option.text ??
            ''
        );

    }



    /* ============================================================
       CREATE QUESTION CARD
    ============================================================ */

    function createQuestionCard(
        question = {},
        existingIndex = null
    )
    {

        const index =
            existingIndex !== null
                ? existingIndex
                : questionCounter++;


        const questionType =
            normalizeQuestionType(
                question.question_type ||
                'radio'
            );


        const questionText =
            question.question_text ||
            '';


        const isRequired =
            parseInt(
                question.is_required || 0
            ) === 1;


        let options = [];


        if (
            Array.isArray(
                question.options
            )
        ) {

            options =
                question.options.map(
                    getOptionValue
                );

        }


        /*
        |--------------------------------------------------------------------------
        | Radio / Checkbox
        |--------------------------------------------------------------------------
        */

        if (
            (
                questionType === 'radio' ||
                questionType === 'checkbox'
            )
            &&
            options.length === 0
        ) {

            options = [''];

        }


        /*
        |--------------------------------------------------------------------------
        | Existing database ID
        |--------------------------------------------------------------------------
        */

        let hiddenId = '';


        if (
            question.id !== null &&
            question.id !== undefined &&
            question.id !== ''
        ) {

            hiddenId = `

                <input
                    type="hidden"
                    name="questions[${index}][id]"
                    value="${escapeHtml(question.id)}"
                >

            `;

        }


        /*
        |--------------------------------------------------------------------------
        | QUESTION CARD
        |--------------------------------------------------------------------------
        */

        const card = $(`
        
            <div
                class="question-builder-card"
                data-question-index="${index}"
            >

                ${hiddenId}


                {{-- HEADER --}}

                <div class="question-card-top">

                    <div class="question-card-heading">

                        <div class="question-number">
                            01
                        </div>


                        <div>

                            <div class="question-heading-text">
                                Feedback Question
                            </div>

                            <div class="question-heading-subtitle">
                                Configure question and response type
                            </div>

                        </div>

                    </div>


                    <div class="question-drag-handle">

                        <i class="fa fa-ellipsis-v"></i>

                        <i class="fa fa-ellipsis-v"></i>

                    </div>

                </div>



                {{-- BODY --}}

                <div class="question-card-body">


                    {{-- QUESTION --}}

                    <label class="question-label">

                        Question

                    </label>


                    <textarea
                        class="question-text-input"
                        name="questions[${index}][question_text]"
                        placeholder="Enter your question..."
                        rows="2"
                    >${escapeHtml(questionText)}</textarea>



                    {{-- QUESTION TYPE --}}

                    <div class="question-type-area">

                        <label class="question-label">

                            Question Type

                        </label>


                        <select
                            class="question-type-select"
                            name="questions[${index}][question_type]"
                        >

                            <option
                                value="radio"
                                ${questionType === 'radio'
                                    ? 'selected'
                                    : ''}
                            >
                                Multiple Choice
                            </option>


                            <option
                                value="checkbox"
                                ${questionType === 'checkbox'
                                    ? 'selected'
                                    : ''}
                            >
                                Checkboxes
                            </option>


                            <option
                                value="text"
                                ${questionType === 'text'
                                    ? 'selected'
                                    : ''}
                            >
                                Text Answer
                            </option>


                            <option
                                value="rating"
                                ${questionType === 'rating'
                                    ? 'selected'
                                    : ''}
                            >
                                Rating
                            </option>


                            <option
                                value="starrating"
                                ${questionType === 'starrating'
                                    ? 'selected'
                                    : ''}
                            >
                                Star Rating
                            </option>


                            <option
                                value="linearscale"
                                ${questionType === 'linearscale'
                                    ? 'selected'
                                    : ''}
                            >
                                Linear Scale
                            </option>

                        </select>

                    </div>



                    {{-- ANSWER PREVIEW --}}

                    <div class="answer-preview">


                        <div class="answer-preview-title">

                            Answer Preview

                        </div>



                        {{-- TEXT --}}

                        <div
                            class="preview-type preview-text"
                            style="display:none;"
                        >

                            <textarea
                                class="text-answer-preview"
                                placeholder="Type your answer here..."
                                disabled
                            ></textarea>

                        </div>



                        {{-- OPTIONS --}}

                        <div
                            class="preview-type preview-options"
                            style="display:none;"
                        >

                            <div class="options-list"></div>


                            <button
                                type="button"
                                class="add-option-btn"
                            >

                                <i class="fa fa-plus"></i>

                                Add option

                            </button>

                        </div>



                        {{-- LINEAR SCALE --}}

                        <div
                            class="preview-type preview-linearscale"
                            style="display:none;"
                        >

                            <div class="linear-settings">


                                <div class="linear-settings-title">

                                    Linear Scale Settings

                                </div>


                                <div class="linear-settings-grid">


                                    {{-- POINTS --}}

                                    <div class="linear-field">

                                        <label>
                                            Number of Points
                                        </label>


                                        <select
                                            class="linear-points"
                                            name="questions[${index}][linear_points]"
                                        >

                                            <option value="2">
                                                2 Points
                                            </option>

                                            <option value="3">
                                                3 Points
                                            </option>

                                            <option value="4">
                                                4 Points
                                            </option>

                                            <option value="5">
                                                5 Points
                                            </option>


                                        </select>

                                    </div>



                                    {{-- MIN LABEL --}}

                                    <div class="linear-field">

                                        <label>
                                            Minimum Label
                                        </label>


                                        <input
                                            type="text"
                                            class="linear-min-label"
                                            name="questions[${index}][linear_min_label]"
                                            placeholder="Example: Least"
                                        >

                                    </div>



                                    {{-- MAX LABEL --}}

                                    <div class="linear-field">

                                        <label>
                                            Maximum Label
                                        </label>


                                        <input
                                            type="text"
                                            class="linear-max-label"
                                            name="questions[${index}][linear_max_label]"
                                            placeholder="Example: Most"
                                        >

                                    </div>


                                </div>

                            </div>



                            <div class="linear-preview-wrapper">


                                <div
                                    class="linear-preview-labels"
                                >

                                    <span
                                        class="linear-preview-label linear-left-label"
                                    >
                                    </span>


                                    <span
                                        class="linear-preview-label linear-right-label"
                                    >
                                    </span>

                                </div>


                                <div
                                    class="linear-preview-points"
                                >
                                </div>

                            </div>

                        </div>



                        {{-- RATING --}}

                        <div
                            class="preview-type preview-rating"
                            style="display:none;"
                        >

                            <div class="rating-preview">

                                <span class="rating-item">
                                    1
                                </span>

                                <span class="rating-item">
                                    2
                                </span>

                                <span class="rating-item">
                                    3
                                </span>

                                <span class="rating-item">
                                    4
                                </span>

                                <span class="rating-item">
                                    5
                                </span>

                            </div>

                        </div>



                        {{-- STAR RATING --}}

                        <div
                            class="preview-type preview-starrating"
                            style="display:none;"
                        >

                            <div class="star-rating-preview">

                                <i class="fa fa-star"></i>

                                <i class="fa fa-star"></i>

                                <i class="fa fa-star"></i>

                                <i class="fa fa-star"></i>

                                <i class="fa fa-star"></i>

                            </div>

                        </div>


                    </div>

                </div>



                {{-- FOOTER --}}

                <div class="question-card-footer">


                    <div class="question-actions">


                        <button
                            type="button"
                            class="question-action-btn duplicate-question"
                        >

                            <i class="fa fa-copy"></i>

                            Duplicate

                        </button>



                        <button
                            type="button"
                            class="question-action-btn delete delete-question"
                        >

                            <i class="fa fa-trash"></i>

                            Delete

                        </button>


                    </div>



                    {{-- REQUIRED --}}

                    <div class="required-wrapper">

                        <span class="required-text">

                            Required

                        </span>


                        <label class="required-switch">


                            <input
                                type="hidden"
                                name="questions[${index}][is_required]"
                                value="0"
                            >


                            <input
                                type="checkbox"
                                class="required-input"
                                name="questions[${index}][is_required]"
                                value="1"
                                ${isRequired ? 'checked' : ''}
                            >


                            <span class="required-slider"></span>


                        </label>

                    </div>


                </div>


            </div>

        `);


        /*
        |--------------------------------------------------------------------------
        | APPEND CARD
        |--------------------------------------------------------------------------
        */

        $('#questionsContainer')
            .append(card);


        /*
        |--------------------------------------------------------------------------
        | LINEAR VALUES
        |--------------------------------------------------------------------------
        */

        card
            .find('.linear-points')
            .val(
                question.linear_points || 5
            );


        card
            .find('.linear-min-label')
            .val(
                question.linear_min_label || ''
            );


        card
            .find('.linear-max-label')
            .val(
                question.linear_max_label || ''
            );


        /*
        |--------------------------------------------------------------------------
        | ADD EXISTING OPTIONS
        |--------------------------------------------------------------------------
        */

        if (
            questionType === 'radio' ||
            questionType === 'checkbox'
        ) {

            options.forEach(
                function (option, optionIndex) {

                    addOption(
                        card,
                        option,
                        optionIndex
                    );

                }
            );

        }


        /*
        |--------------------------------------------------------------------------
        | RENDER
        |--------------------------------------------------------------------------
        */

        renderAnswerPreview(card);

        updateQuestionNumbers();


        return card;

    }



    /* ============================================================
       ADD OPTION
    ============================================================ */

    function addOption(
        card,
        value = '',
        optionIndex = null
    )
    {

        const questionIndex =
            card.data('question-index');


        const optionsList =
            card.find('.options-list');


        if (optionIndex === null) {

            optionIndex =
                optionsList
                    .find('.option-row')
                    .length;

        }


        const type =
            normalizeQuestionType(
                card
                    .find('.question-type-select')
                    .val()
            );


        const iconClass =
            type === 'checkbox'
                ? 'checkbox-preview-icon'
                : 'radio-preview-icon';


        const optionRow = $(`

            <div
                class="option-row"
                data-option-index="${optionIndex}"
            >

                <span class="option-control-icon">

                    <span class="${iconClass}"></span>

                </span>


                <input
                    type="text"
                    class="option-input"
                    name="questions[${questionIndex}][options][${optionIndex}]"
                    value="${escapeHtml(value)}"
                    placeholder="Enter option"
                >


                <button
                    type="button"
                    class="option-delete-btn delete-option"
                    title="Delete option"
                >

                    <i class="fa fa-trash"></i>

                </button>

            </div>

        `);


        optionsList.append(optionRow);


        updateOptionIndexes(card);

    }



    /* ============================================================
       UPDATE OPTION INDEX
    ============================================================ */

    function updateOptionIndexes(card)
    {

        const questionIndex =
            card.data('question-index');


        card
            .find('.option-row')
            .each(function (index) {

                $(this)
                    .attr(
                        'data-option-index',
                        index
                    );


                $(this)
                    .find('.option-input')
                    .attr(
                        'name',
                        `questions[${questionIndex}][options][${index}]`
                    );

            });

    }



    /* ============================================================
       RENDER ANSWER PREVIEW
    ============================================================ */

    function renderAnswerPreview(card)
    {

        const type =
            normalizeQuestionType(
                card
                    .find('.question-type-select')
                    .val()
            );


        /*
        |--------------------------------------------------------------------------
        | Hide everything
        |--------------------------------------------------------------------------
        */

        card
            .find('.preview-type')
            .hide();


        card
            .find('.linear-settings')
            .hide();


        /*
        |--------------------------------------------------------------------------
        | TEXT
        |--------------------------------------------------------------------------
        */

        if (type === 'text') {

            card
                .find('.preview-text')
                .show();


            disableOptions(card);

        }


        /*
        |--------------------------------------------------------------------------
        | RADIO
        |--------------------------------------------------------------------------
        */

        else if (type === 'radio') {

            card
                .find('.preview-options')
                .show();


            enableOptions(card);


            ensureOneOption(card);


            refreshOptionIcons(card);

        }


        /*
        |--------------------------------------------------------------------------
        | CHECKBOX
        |--------------------------------------------------------------------------
        */

        else if (type === 'checkbox') {

            card
                .find('.preview-options')
                .show();


            enableOptions(card);


            ensureOneOption(card);


            refreshOptionIcons(card);

        }


        /*
        |--------------------------------------------------------------------------
        | RATING
        |--------------------------------------------------------------------------
        */

        else if (type === 'rating') {

            card
                .find('.preview-rating')
                .show();


            disableOptions(card);

        }


        /*
        |--------------------------------------------------------------------------
        | STAR RATING
        |--------------------------------------------------------------------------
        */

        else if (type === 'starrating') {

            card
                .find('.preview-starrating')
                .show();


            disableOptions(card);

        }


        /*
        |--------------------------------------------------------------------------
        | LINEAR SCALE
        |--------------------------------------------------------------------------
        */

        else if (type === 'linearscale') {

            card
                .find('.preview-linearscale')
                .show();


            card
                .find('.linear-settings')
                .show();


            disableOptions(card);


            renderLinearScale(card);

        }

    }



    /* ============================================================
       ENABLE OPTIONS
    ============================================================ */

    function enableOptions(card)
    {

        card
            .find('.option-input')
            .prop('disabled', false);


        card
            .find('.option-delete-btn')
            .prop('disabled', false);


        card
            .find('.add-option-btn')
            .show();

    }



    /* ============================================================
       DISABLE OPTIONS
    ============================================================ */

    function disableOptions(card)
    {

        card
            .find('.option-input')
            .prop('disabled', true);


        card
            .find('.option-delete-btn')
            .prop('disabled', true);


        card
            .find('.add-option-btn')
            .hide();

    }



    /* ============================================================
       ENSURE ONE OPTION
    ============================================================ */

    function ensureOneOption(card)
    {

        const optionsList =
            card.find('.options-list');


        if (
            optionsList
                .find('.option-row')
                .length === 0
        ) {

            addOption(
                card,
                '',
                0
            );

        }

    }



    /* ============================================================
       REFRESH OPTION ICON
    ============================================================ */

    function refreshOptionIcons(card)
    {

        const type =
            normalizeQuestionType(
                card
                    .find('.question-type-select')
                    .val()
            );


        const iconClass =
            type === 'checkbox'
                ? 'checkbox-preview-icon'
                : 'radio-preview-icon';


        card
            .find('.option-control-icon')
            .each(function () {

                $(this).html(
                    `<span class="${iconClass}"></span>`
                );

            });

    }



    /* ============================================================
       LINEAR SCALE
    ============================================================ */

    function renderLinearScale(card)
    {

        let points =
            parseInt(
                card
                    .find('.linear-points')
                    .val()
            );


        if (
            isNaN(points) ||
            points < 2
        ) {

            points = 5;

        }


        if (points > 15) {

            points = 15;

        }


        /*
        |--------------------------------------------------------------------------
        | Points
        |--------------------------------------------------------------------------
        */

        const preview =
            card.find(
                '.linear-preview-points'
            );


        preview.empty();


        for (
            let number = 1;
            number <= points;
            number++
        ) {

            preview.append(`

                <div class="linear-point">

                    <div class="linear-point-circle"></div>

                    <div class="linear-point-number">
                        ${number}
                    </div>

                </div>

            `);

        }


        /*
        |--------------------------------------------------------------------------
        | Labels
        |--------------------------------------------------------------------------
        */

        const leftLabel =
            card
                .find('.linear-min-label')
                .val();


        const rightLabel =
            card
                .find('.linear-max-label')
                .val();


        card
            .find('.linear-left-label')
            .text(
                leftLabel || ' '
            );


        card
            .find('.linear-right-label')
            .text(
                rightLabel || ' '
            );

    }



    /* ============================================================
       UPDATE QUESTION NUMBERS
    ============================================================ */

    function updateQuestionNumbers()
    {

        $('#questionsContainer')
            .find('.question-builder-card')
            .each(function (index) {

                const number =
                    String(index + 1)
                        .padStart(2, '0');


                $(this)
                    .find('.question-number')
                    .text(number);

            });

    }



    /* ============================================================
       LOAD EXISTING QUESTIONS
    ============================================================ */

    if (
        Array.isArray(initialQuestions) &&
        initialQuestions.length > 0
    ) {

        initialQuestions.forEach(
            function (question, index) {

                createQuestionCard(
                    question,
                    index
                );


                questionCounter =
                    Math.max(
                        questionCounter,
                        index + 1
                    );

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | CREATE MODE FALLBACK
    |--------------------------------------------------------------------------
    */

    else {

        createQuestionCard({

            id: null,

            question_text: '',

            question_type: 'radio',

            is_required: 0,

            options: [''],

            linear_points: 5,

            linear_min_label: '',

            linear_max_label: ''

        });

    }



    /* ============================================================
       ADD QUESTION
    ============================================================ */

    $('#addQuestionBtn').on(
        'click',
        function () {

            const newCard =
                createQuestionCard({

                    id: null,

                    question_text: '',

                    question_type: 'radio',

                    is_required: 0,

                    options: [''],

                    linear_points: 5,

                    linear_min_label: '',

                    linear_max_label: ''

                });


            $('html, body').animate({

                scrollTop:
                    newCard.offset().top - 100

            }, 400);


            setTimeout(
                function () {

                    newCard
                        .find('.question-text-input')
                        .focus();

                },
                450
            );

        }
    );



    /* ============================================================
       QUESTION TYPE CHANGE
    ============================================================ */

    $(document).on(
        'change',
        '.question-type-select',
        function () {

            const card =
                $(this).closest(
                    '.question-builder-card'
                );


            const type =
                normalizeQuestionType(
                    $(this).val()
                );


            if (
                type === 'radio' ||
                type === 'checkbox'
            ) {

                ensureOneOption(card);

            }


            renderAnswerPreview(card);

        }
    );



    /* ============================================================
       ADD OPTION
    ============================================================ */

    $(document).on(
        'click',
        '.add-option-btn',
        function () {

            const card =
                $(this).closest(
                    '.question-builder-card'
                );


            addOption(card);


            card
                .find('.option-input')
                .last()
                .focus();

        }
    );



    /* ============================================================
       DELETE OPTION
    ============================================================ */

    $(document).on(
        'click',
        '.delete-option',
        function () {

            const card =
                $(this).closest(
                    '.question-builder-card'
                );


            const rows =
                card.find(
                    '.options-list .option-row'
                );


            if (
                rows.length <= 1
            ) {

                showAlert({

                    icon: 'info',

                    title:
                        'One option is required',

                    text:
                        'At least one option must remain for this question.',

                    confirmButtonText:
                        'OK'

                });

                return;

            }


            $(this)
                .closest('.option-row')
                .remove();


            updateOptionIndexes(card);

        }
    );



    /* ============================================================
       DELETE QUESTION
    ============================================================ */

    $(document).on(
        'click',
        '.delete-question',
        function () {

            const card =
                $(this).closest(
                    '.question-builder-card'
                );


            const totalQuestions =
                $('#questionsContainer')
                    .find(
                        '.question-builder-card'
                    )
                    .length;


            if (
                totalQuestions <= 1
            ) {

                showAlert({

                    icon: 'info',

                    title:
                        'Cannot delete',

                    text:
                        'At least one question is required.',

                    confirmButtonText:
                        'OK'

                });

                return;

            }


            if (
                typeof window.Swal !== 'undefined'
            ) {

                Swal.fire({

                    title:
                        'Delete this question?',

                    text:
                        'This question will be removed from the form.',

                    icon:
                        'warning',

                    showCancelButton:
                        true,

                    confirmButtonText:
                        'Yes, delete',

                    cancelButtonText:
                        'Cancel',

                    reverseButtons:
                        true

                }).then(
                    function (result) {

                        if (
                            result.isConfirmed
                        ) {

                            card.remove();

                            updateQuestionNumbers();

                        }

                    }
                );

            }

            else {

                if (
                    confirm(
                        'Delete this question?'
                    )
                ) {

                    card.remove();

                    updateQuestionNumbers();

                }

            }

        }
    );



    /* ============================================================
       DUPLICATE QUESTION
    ============================================================ */

    $(document).on(
        'click',
        '.duplicate-question',
        function () {

            const originalCard =
                $(this).closest(
                    '.question-builder-card'
                );


            const type =
                normalizeQuestionType(
                    originalCard
                        .find('.question-type-select')
                        .val()
                );


            const questionText =
                originalCard
                    .find('.question-text-input')
                    .val();


            const isRequired =
                originalCard
                    .find('.required-input')
                    .is(':checked')
                    ? 1
                    : 0;


            const options = [];


            originalCard
                .find('.option-input')
                .each(function () {

                    options.push(
                        $(this).val()
                    );

                });


            const newQuestion = {

                /*
                |--------------------------------------------------------------------------
                | IMPORTANT
                |
                | Duplicate must NOT copy the database ID.
                |--------------------------------------------------------------------------
                */

                id: null,

                question_text:
                    questionText,

                question_type:
                    type,

                is_required:
                    isRequired,

                options:
                    options,

                linear_points:
                    originalCard
                        .find('.linear-points')
                        .val(),

                linear_min_label:
                    originalCard
                        .find('.linear-min-label')
                        .val(),

                linear_max_label:
                    originalCard
                        .find('.linear-max-label')
                        .val()

            };


            const newCard =
                createQuestionCard(
                    newQuestion
                );


            originalCard.after(
                newCard
            );


            updateQuestionNumbers();

        }
    );



    /* ============================================================
       LINEAR POINT CHANGE
    ============================================================ */

    $(document).on(
        'change',
        '.linear-points',
        function () {

            const card =
                $(this).closest(
                    '.question-builder-card'
                );


            renderLinearScale(card);

        }
    );



    /* ============================================================
       LINEAR LABEL CHANGE
    ============================================================ */

    $(document).on(
        'input',
        '.linear-min-label, .linear-max-label',
        function () {

            const card =
                $(this).closest(
                    '.question-builder-card'
                );


            renderLinearScale(card);

        }
    );



    /* ============================================================
       FORM SUBMIT
    ============================================================ */

    $('#feedbackQuestionForm').on(
        'submit',
        function (e) {

            let valid = true;

            let errorMessage = '';


            const cards =
                $('#questionsContainer')
                    .find(
                        '.question-builder-card'
                    );


            /*
            |--------------------------------------------------------------------------
            | At least one question
            |--------------------------------------------------------------------------
            */

            if (
                cards.length === 0
            ) {

                e.preventDefault();


                showAlert({

                    icon: 'warning',

                    title:
                        'No questions',

                    text:
                        'Please add at least one question.',

                    confirmButtonText:
                        'OK'

                });


                return false;

            }


            /*
            |--------------------------------------------------------------------------
            | Validate each question
            |--------------------------------------------------------------------------
            */

            cards.each(
                function (index) {

                    const card =
                        $(this);


                    const questionText =
                        $.trim(
                            card
                                .find(
                                    '.question-text-input'
                                )
                                .val()
                        );


                    const type =
                        normalizeQuestionType(
                            card
                                .find(
                                    '.question-type-select'
                                )
                                .val()
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | QUESTION REQUIRED
                    |--------------------------------------------------------------------------
                    */

                    if (!questionText) {

                        valid = false;

                        errorMessage =
                            `Please enter question ${index + 1}.`;

                        card
                            .find(
                                '.question-text-input'
                            )
                            .addClass(
                                'is-invalid'
                            );

                        return false;

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | OPTIONS
                    |--------------------------------------------------------------------------
                    */

                    if (
                        type === 'radio' ||
                        type === 'checkbox'
                    ) {

                        const optionInputs =
                            card.find(
                                '.option-input:not(:disabled)'
                            );


                        if (
                            optionInputs.length === 0
                        ) {

                            valid = false;

                            errorMessage =
                                `Please add at least one option for question ${index + 1}.`;

                            return false;

                        }


                        let emptyOption =
                            false;


                        optionInputs.each(
                            function () {

                                const value =
                                    $.trim(
                                        $(this).val()
                                    );


                                if (!value) {

                                    emptyOption =
                                        true;

                                    $(this)
                                        .addClass(
                                            'is-invalid'
                                        );

                                }

                            }
                        );


                        if (emptyOption) {

                            valid = false;

                            errorMessage =
                                `Please fill all options for question ${index + 1}.`;

                            return false;

                        }

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | LINEAR SCALE
                    |--------------------------------------------------------------------------
                    */

                    if (
                        type === 'linearscale'
                    ) {

                        const points =
                            parseInt(
                                card
                                    .find(
                                        '.linear-points'
                                    )
                                    .val()
                            );


                        if (
                            isNaN(points) ||
                            points < 2 ||
                            points > 15
                        ) {

                            valid = false;

                            errorMessage =
                                `Please select a valid number of points for question ${index + 1}.`;

                            return false;

                        }

                    }

                }
            );


            /*
            |--------------------------------------------------------------------------
            | STOP SUBMIT
            |--------------------------------------------------------------------------
            */

            if (!valid) {

                e.preventDefault();


                showAlert({

                    icon: 'warning',

                    title:
                        'Check your questions',

                    text:
                        errorMessage,

                    confirmButtonText:
                        'OK'

                });


                return false;

            }


            /*
            |--------------------------------------------------------------------------
            | PREVENT DOUBLE CLICK
            |--------------------------------------------------------------------------
            */

            $('#saveFeedbackBtn')
                .prop(
                    'disabled',
                    true
                )
                .html(
                    '<i class="fa fa-spinner fa-spin"></i> Saving...'
                );

        }
    );



    /* ============================================================
       REMOVE INVALID STATE WHILE TYPING
    ============================================================ */

    $(document).on(
        'input',
        '.question-text-input, .option-input',
        function () {

            if (
                $.trim(
                    $(this).val()
                )
            ) {

                $(this)
                    .removeClass(
                        'is-invalid'
                    );

            }

        }
    );



    /* ============================================================
       VALIDATION ERROR
    ============================================================ */

    @if($errors->any())

        setTimeout(
            function () {

                showAlert({

                    icon: 'error',

                    title:
                        'Please check the form',

                    html:
                        @json(
                            implode(
                                '<br>',
                                $errors->all()
                            )
                        ),

                    confirmButtonText:
                        'OK'

                });

            },
            300
        );

    @endif



    /* ============================================================
       SUCCESS
    ============================================================ */

    @if(session('success'))

        setTimeout(
            function () {

                showAlert({

                    icon: 'success',

                    title:
                        'Success',

                    text:
                        @json(
                            session('success')
                        ),

                    confirmButtonText:
                        'OK'

                });

            },
            300
        );

    @endif


});

</script>

@endpush