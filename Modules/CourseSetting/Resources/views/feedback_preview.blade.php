@extends('backend.master')

@section('table')
    tenant_galleries
@endsection

@section('mainContent')

<div class="container-fluid feedback-builder-page">

    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}
    <div class="feedback-page-header">

        <div>
            <div class="feedback-breadcrumb">
                <span>Feedback</span>
                <i class="fa fa-angle-right"></i>
                <span>Preview</span>
            </div>

            <h2 class="feedback-page-title">
                {{ $form->form_name ?? 'Feedback Form' }}
            </h2>

            <p class="feedback-page-subtitle">
                Preview how this feedback form will appear to employees.
            </p>
        </div>

        <div class="feedback-header-actions">
            <a href="{{ url()->previous() }}" class="btn btn-light feedback-back-btn">
                <i class="fa fa-arrow-left"></i>
                Back
            </a>
        </div>

    </div>


    {{-- =========================================================
         FORM INFORMATION
    ========================================================== --}}
    <div class="feedback-form-info-card">

        <div class="feedback-form-info-left">

            <div class="feedback-form-icon">
                <i class="fa fa-comments"></i>
            </div>

            <div>
                <h4>
                    {{ $form->form_name ?? 'Feedback Form' }}
                </h4>

                @if(!empty($form->description))
                    <p>
                        {{ $form->description }}
                    </p>
                @else
                    <p class="text-muted">
                        Please complete the feedback form below.
                    </p>
                @endif
            </div>

        </div>

        <div class="feedback-form-status">

            @if(isset($form->status) && $form->status == 1)
                <span class="status-badge active">
                    <span class="status-dot"></span>
                    Active
                </span>
            @else
                <span class="status-badge inactive">
                    <span class="status-dot"></span>
                    Inactive
                </span>
            @endif

        </div>

    </div>


    {{-- =========================================================
         PREVIEW FORM
    ========================================================== --}}
    <form id="feedbackPreviewForm">

        @csrf

        @if(isset($questions) && $questions->count() > 0)

            @foreach($questions as $question)

                @php

                    /*
                    |--------------------------------------------------------------------------
                    | QUESTION TYPE
                    |--------------------------------------------------------------------------
                    */

                    $questionType = strtolower(
                        trim($question->question_type ?? 'radio')
                    );

                    if (in_array($questionType, [
                        'multiple_choice',
                        'multiple choice',
                        'mcq'
                    ])) {
                        $questionType = 'radio';
                    }

                    if (in_array($questionType, [
                        'checkboxes',
                        'check_box',
                        'check box',
                        'multi_checkbox',
                        'multiple_checkbox'
                    ])) {
                        $questionType = 'checkbox';
                    }

                    if (in_array($questionType, [
                        'text_answer',
                        'text answer',
                        'textarea',
                        'text-area'
                    ])) {
                        $questionType = 'text';
                    }

                    if (in_array($questionType, [
                        'star_rating',
                        'star rating',
                        'stars',
                        'star'
                    ])) {
                        $questionType = 'starrating';
                    }

                    if (in_array($questionType, [
                        'linear_scale',
                        'linear scale',
                        'linear'
                    ])) {
                        $questionType = 'linearscale';
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | OPTIONS
                    |--------------------------------------------------------------------------
                    |
                    | Supports:
                    |
                    | 1. ["Yes","No"]
                    |
                    | 2. [{"option_text":"Yes"},{"option_text":"No"}]
                    |
                    | 3. [{"option":"Yes"},{"option":"No"}]
                    |
                    | 4. [{"value":"Yes"},{"value":"No"}]
                    |
                    | 5. {"0":"Yes","1":"No"}
                    |
                    | 6. "Yes,No,Maybe"
                    |
                    */

                    $options = $question->options ?? [];

                    if (is_string($options)) {

                        $decodedOptions = json_decode($options, true);

                        if (
                            json_last_error() === JSON_ERROR_NONE &&
                            is_array($decodedOptions)
                        ) {
                            $options = $decodedOptions;
                        } else {
                            $options = array_filter(
                                array_map(
                                    'trim',
                                    explode(',', $options)
                                )
                            );
                        }
                    }

                    if ($options instanceof \Illuminate\Support\Collection) {
                        $options = $options->toArray();
                    }

                    if (!is_array($options)) {
                        $options = [];
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | NORMALIZE OPTIONS
                    |--------------------------------------------------------------------------
                    */

                    $normalizedOptions = [];

                    foreach ($options as $option) {

                        $optionText = '';

                        if (is_array($option)) {

                            $optionText =
                                $option['option_text']
                                ?? $option['option']
                                ?? $option['value']
                                ?? $option['text']
                                ?? $option['label']
                                ?? '';

                        } elseif (is_object($option)) {

                            $optionText =
                                $option->option_text
                                ?? $option->option
                                ?? $option->value
                                ?? $option->text
                                ?? $option->label
                                ?? '';

                        } else {

                            $optionText = $option;
                        }

                        $optionText = trim((string) $optionText);

                        if ($optionText !== '') {
                            $normalizedOptions[] = $optionText;
                        }
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | REQUIRED
                    |--------------------------------------------------------------------------
                    */

                    $isRequired = !empty($question->is_required);


                    /*
                    |--------------------------------------------------------------------------
                    | LINEAR SCALE
                    |--------------------------------------------------------------------------
                    */

                    $points = (int) ($question->linear_points ?? 5);

                    if ($points < 2) {
                        $points = 2;
                    }

                    if ($points > 15) {
                        $points = 15;
                    }


                    $linearMinLabel =
                        $question->linear_min_label
                        ?? 'Strongly Disagree';

                    $linearMaxLabel =
                        $question->linear_max_label
                        ?? 'Strongly Agree';

                @endphp


                {{-- =====================================================
                     QUESTION CARD
                ====================================================== --}}
                <div class="feedback-question-card">

                    {{-- Question Header --}}
                    <div class="feedback-question-header">

                        <div class="feedback-question-number">
                            {{ $loop->iteration }}
                        </div>

                        <div class="feedback-question-heading">

                            <div class="feedback-question-title">

                                {{ $question->question_text ?? 'Question' }}

                                @if($isRequired)
                                    <span class="required-star">*</span>
                                @endif

                            </div>

                        </div>

                        <div class="feedback-question-type">

                            @switch($questionType)

                                @case('radio')
                                    <span class="question-type-badge">
                                        <i class="fa fa-dot-circle-o"></i>
                                        Multiple Choice
                                    </span>
                                    @break

                                @case('checkbox')
                                    <span class="question-type-badge">
                                        <i class="fa fa-check-square-o"></i>
                                        Checkbox
                                    </span>
                                    @break

                                @case('text')
                                    <span class="question-type-badge">
                                        <i class="fa fa-align-left"></i>
                                        Text
                                    </span>
                                    @break

                                @case('rating')
                                    <span class="question-type-badge">
                                        <i class="fa fa-star-half-o"></i>
                                        Rating
                                    </span>
                                    @break

                                @case('starrating')
                                    <span class="question-type-badge">
                                        <i class="fa fa-star"></i>
                                        Star Rating
                                    </span>
                                    @break

                                @case('linearscale')
                                    <span class="question-type-badge">
                                        <i class="fa fa-sliders"></i>
                                        Linear Scale
                                    </span>
                                    @break

                                @default
                                    <span class="question-type-badge">
                                        {{ ucfirst($questionType) }}
                                    </span>

                            @endswitch

                        </div>

                    </div>


                    {{-- =================================================
                         QUESTION BODY
                    ================================================== --}}
                    <div class="feedback-question-body">


                        {{-- =================================================
                             RADIO / MULTIPLE CHOICE
                        ================================================== --}}
                        @if($questionType === 'radio')

                            <div class="preview-options-list">

                                @if(count($normalizedOptions) > 0)

                                    @foreach($normalizedOptions as $optionIndex => $optionText)

                                        <label
                                            class="preview-option-row"
                                            for="preview_radio_{{ $question->id }}_{{ $optionIndex }}"
                                        >

                                            <input
                                                type="radio"
                                                id="preview_radio_{{ $question->id }}_{{ $optionIndex }}"
                                                name="preview_question_{{ $question->id }}"
                                                value="{{ $optionText }}"
                                            >

                                            <span class="preview-radio"></span>

                                            <span class="preview-option-text">
                                                {{ $optionText }}
                                            </span>

                                        </label>

                                    @endforeach

                                @else

                                    <div class="preview-no-options">
                                        <i class="fa fa-info-circle"></i>
                                        No options configured for this question.
                                    </div>

                                @endif

                            </div>


                        {{-- =================================================
                             CHECKBOX
                        ================================================== --}}
                        @elseif($questionType === 'checkbox')

                            <div class="preview-options-list">

                                @if(count($normalizedOptions) > 0)

                                    @foreach($normalizedOptions as $optionIndex => $optionText)

                                        <label
                                            class="preview-option-row"
                                            for="preview_checkbox_{{ $question->id }}_{{ $optionIndex }}"
                                        >

                                            <input
                                                type="checkbox"
                                                id="preview_checkbox_{{ $question->id }}_{{ $optionIndex }}"
                                                name="preview_question_{{ $question->id }}[]"
                                                value="{{ $optionText }}"
                                            >

                                            <span class="preview-checkbox"></span>

                                            <span class="preview-option-text">
                                                {{ $optionText }}
                                            </span>

                                        </label>

                                    @endforeach

                                @else

                                    <div class="preview-no-options">
                                        <i class="fa fa-info-circle"></i>
                                        No options configured for this question.
                                    </div>

                                @endif

                            </div>


                        {{-- =================================================
                             TEXT
                        ================================================== --}}
                        @elseif($questionType === 'text')

                            <div class="preview-text-wrapper">

                                <textarea
                                    class="form-control preview-textarea"
                                    name="preview_question_{{ $question->id }}"
                                    rows="4"
                                    placeholder="Enter your answer..."
                                    @if($isRequired) required @endif
                                ></textarea>

                            </div>


                        {{-- =================================================
                             RATING
                        ================================================== --}}
                        @elseif($questionType === 'rating')

                            <div class="preview-rating-wrapper">

                                <div class="rating-scale">

                                    @for($rating = 1; $rating <= 5; $rating++)

                                        <label
                                            class="rating-item"
                                            for="rating_{{ $question->id }}_{{ $rating }}"
                                        >

                                            <input
                                                type="radio"
                                                id="rating_{{ $question->id }}_{{ $rating }}"
                                                name="preview_question_{{ $question->id }}"
                                                value="{{ $rating }}"
                                            >

                                            <span class="rating-number">
                                                {{ $rating }}
                                            </span>

                                        </label>

                                    @endfor

                                </div>

                                <div class="rating-labels">
                                    <span>Strongly Disagree</span>
                                    <span>Strongly Agree</span>
                                </div>

                            </div>


                        {{-- =================================================
                             STAR RATING
                        ================================================== --}}
                        @elseif($questionType === 'starrating')

                            <div class="preview-star-rating">

                                @for($star = 1; $star <= 5; $star++)

                                    <label
                                        class="star-item"
                                        for="star_{{ $question->id }}_{{ $star }}"
                                    >

                                        <input
                                            type="radio"
                                            id="star_{{ $question->id }}_{{ $star }}"
                                            name="preview_question_{{ $question->id }}"
                                            value="{{ $star }}"
                                        >

                                        <i class="fa fa-star"></i>

                                    </label>

                                @endfor

                            </div>


                        {{-- =================================================
                             LINEAR SCALE
                        ================================================== --}}
                        @elseif($questionType === 'linearscale')

                            <div class="preview-linear-wrapper">

                                <div class="linear-label-row">

                                    <span>
                                        {{ $linearMinLabel }}
                                    </span>

                                    <span>
                                        {{ $linearMaxLabel }}
                                    </span>

                                </div>

                                <div class="linear-scale">

                                    @for($scale = 1; $scale <= $points; $scale++)

                                        <label
                                            class="linear-item"
                                            for="linear_{{ $question->id }}_{{ $scale }}"
                                        >

                                            <input
                                                type="radio"
                                                id="linear_{{ $question->id }}_{{ $scale }}"
                                                name="preview_question_{{ $question->id }}"
                                                value="{{ $scale }}"
                                            >

                                            <span class="linear-number">
                                                {{ $scale }}
                                            </span>

                                        </label>

                                    @endfor

                                </div>

                            </div>


                        {{-- =================================================
                             UNKNOWN TYPE
                        ================================================== --}}
                        @else

                            <div class="preview-text-wrapper">

                                <textarea
                                    class="form-control preview-textarea"
                                    name="preview_question_{{ $question->id }}"
                                    rows="4"
                                    placeholder="Enter your answer..."
                                ></textarea>

                            </div>

                        @endif

                    </div>


                    {{-- =================================================
                         QUESTION FOOTER
                    ================================================== --}}
                    <div class="feedback-question-footer">

                        @if($isRequired)

                            <span class="required-label">
                                <i class="fa fa-asterisk"></i>
                                Required
                            </span>

                        @else

                            <span class="optional-label">
                                Optional
                            </span>

                        @endif

                    </div>

                </div>

            @endforeach


            {{-- =========================================================
                 SUBMIT PREVIEW
            ========================================================== --}}
            <div class="feedback-preview-submit">

                <button
                    type="button"
                    class="btn btn-primary preview-submit-btn"
                    id="previewSubmitBtn"
                >
                    <i class="fa fa-paper-plane"></i>
                    Submit Feedback
                </button>

            </div>


        @else

            {{-- =========================================================
                 EMPTY STATE
            ========================================================== --}}
            <div class="feedback-empty-state">

                <div class="empty-icon">
                    <i class="fa fa-comments-o"></i>
                </div>

                <h4>
                    No Questions Found
                </h4>

                <p>
                    This feedback form does not have any active questions yet.
                </p>

                <a
                    href="{{ url()->previous() }}"
                    class="btn btn-primary"
                >
                    <i class="fa fa-arrow-left"></i>
                    Back
                </a>

            </div>

        @endif

    </form>

</div>


{{-- =============================================================
     STYLES
============================================================= --}}
@push('styles')

<style>

.feedback-builder-page {
    padding-bottom: 40px;
}

/* =========================================================
   HEADER
========================================================= */

.feedback-page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 24px;
}

.feedback-breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #8b93a7;
    font-size: 13px;
    margin-bottom: 8px;
}

.feedback-breadcrumb i {
    font-size: 12px;
}

.feedback-page-title {
    margin: 0;
    font-size: 24px;
    font-weight: 700;
    color: #252b3b;
}

.feedback-page-subtitle {
    margin: 6px 0 0;
    color: #8b93a7;
    font-size: 14px;
}

.feedback-header-actions {
    display: flex;
    gap: 8px;
}

.feedback-back-btn {
    border: 1px solid #e1e5eb;
    background: #fff;
    color: #555f72;
    border-radius: 7px;
    padding: 8px 15px;
}

.feedback-back-btn:hover {
    background: #f7f8fa;
}


/* =========================================================
   FORM INFO
========================================================= */

.feedback-form-info-card {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #fff;
    border: 1px solid #e5e8ee;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
}

.feedback-form-info-left {
    display: flex;
    align-items: center;
    gap: 15px;
}

.feedback-form-icon {
    width: 48px;
    height: 48px;
    border-radius: 10px;
    background: #f0f2ff;
    color: #667eea;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 21px;
}

.feedback-form-info-card h4 {
    margin: 0 0 5px;
    color: #252b3b;
    font-size: 17px;
    font-weight: 700;
}

.feedback-form-info-card p {
    margin: 0;
    color: #8b93a7;
    font-size: 13px;
}

.feedback-form-status {
    flex-shrink: 0;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 6px 11px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.status-badge.active {
    background: #eaf8ef;
    color: #2e9d57;
}

.status-badge.inactive {
    background: #f3f4f6;
    color: #8b93a7;
}

.status-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: currentColor;
}


/* =========================================================
   QUESTION CARD
========================================================= */

.feedback-question-card {
    background: #fff;
    border: 1px solid #e5e8ee;
    border-radius: 10px;
    margin-bottom: 18px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
}

.feedback-question-header {
    display: flex;
    align-items: center;
    gap: 13px;
    padding: 17px 20px;
    border-bottom: 1px solid #edf0f4;
}

.feedback-question-number {
    width: 34px;
    height: 34px;
    min-width: 34px;
    border-radius: 8px;
    background: #f0f2ff;
    color: #667eea;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 13px;
}

.feedback-question-heading {
    flex: 1;
    min-width: 0;
}

.feedback-question-title {
    color: #252b3b;
    font-size: 15px;
    line-height: 1.5;
    font-weight: 600;
}

.required-star {
    color: #e74c3c;
    margin-left: 3px;
}

.feedback-question-type {
    flex-shrink: 0;
}

.question-type-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 10px;
    background: #f6f7fa;
    border: 1px solid #e5e8ee;
    color: #697386;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 600;
}


/* =========================================================
   QUESTION BODY
========================================================= */

.feedback-question-body {
    padding: 20px;
}


/* =========================================================
   OPTIONS
========================================================= */

.preview-options-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
    max-width: 700px;
}

.preview-option-row {
    position: relative;
    display: flex;
    align-items: center;
    gap: 11px;
    min-height: 42px;
    padding: 9px 12px;
    background: #fff;
    border: 1px solid #e0e5ec;
    border-radius: 8px;
    cursor: pointer;
    margin: 0;
    transition: all .2s ease;
}

.preview-option-row:hover {
    border-color: #667eea;
    background: #fafbff;
}


/*
|--------------------------------------------------------------------------
| Hide Native Input
|--------------------------------------------------------------------------
*/

.preview-option-row input[type="radio"],
.preview-option-row input[type="checkbox"] {
    position: absolute;
    opacity: 0;
    width: 1px;
    height: 1px;
    margin: 0;
}


/*
|--------------------------------------------------------------------------
| Radio / Checkbox Visual
|--------------------------------------------------------------------------
*/

.preview-radio,
.preview-checkbox {
    position: relative;
    width: 18px;
    height: 18px;
    min-width: 18px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 2px solid #a1a9b5;
    background: #fff;
    transition: all .2s ease;
}

.preview-radio {
    border-radius: 50%;
}

.preview-checkbox {
    border-radius: 4px;
}


/*
|--------------------------------------------------------------------------
| Checked Radio
|--------------------------------------------------------------------------
*/

.preview-option-row input[type="radio"]:checked + .preview-radio {
    border-color: #667eea;
}

.preview-option-row input[type="radio"]:checked + .preview-radio::after {
    content: "";
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #667eea;
}


/*
|--------------------------------------------------------------------------
| Checked Checkbox
|--------------------------------------------------------------------------
*/

.preview-option-row input[type="checkbox"]:checked + .preview-checkbox {
    background: #667eea;
    border-color: #667eea;
}

.preview-option-row input[type="checkbox"]:checked + .preview-checkbox::after {
    content: "\f00c";
    font-family: "FontAwesome";
    font-size: 10px;
    color: #fff;
}


/*
|--------------------------------------------------------------------------
| Selected Row
|--------------------------------------------------------------------------
*/

.preview-option-row:has(input:checked) {
    border-color: #667eea;
    background: #f8f9ff;
}

.preview-option-text {
    color: #41495a;
    font-size: 14px;
    line-height: 1.4;
}

.preview-no-options {
    padding: 13px 15px;
    background: #fff8e8;
    border: 1px solid #f4dfae;
    color: #98731d;
    border-radius: 7px;
    font-size: 13px;
}

.preview-no-options i {
    margin-right: 5px;
}


/* =========================================================
   TEXTAREA
========================================================= */

.preview-text-wrapper {
    max-width: 700px;
}

.preview-textarea {
    border: 1px solid #dfe3e9;
    border-radius: 8px;
    padding: 12px 14px;
    color: #41495a;
    font-size: 14px;
    resize: vertical;
    box-shadow: none;
}

.preview-textarea:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, .08);
}


/* =========================================================
   RATING
========================================================= */

.preview-rating-wrapper {
    max-width: 700px;
}

.rating-scale {
    display: flex;
    align-items: center;
    gap: 10px;
}

.rating-item {
    cursor: pointer;
    margin: 0;
}

.rating-item input {
    display: none;
}

.rating-number {
    width: 42px;
    height: 42px;
    border: 1px solid #dfe3e9;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #596273;
    font-size: 14px;
    font-weight: 600;
    transition: all .2s ease;
}

.rating-item:hover .rating-number {
    border-color: #667eea;
    color: #667eea;
}

.rating-item input:checked + .rating-number {
    background: #667eea;
    border-color: #667eea;
    color: #fff;
}

.rating-labels {
    display: flex;
    justify-content: space-between;
    max-width: 260px;
    margin-top: 9px;
    color: #9aa1af;
    font-size: 11px;
}


/* =========================================================
   STAR RATING
========================================================= */

.preview-star-rating {
    display: flex;
    align-items: center;
    gap: 7px;
}

.star-item {
    cursor: pointer;
    margin: 0;
}

.star-item input {
    display: none;
}

.star-item i {
    font-size: 30px;
    color: #d8dce3;
    transition: all .2s ease;
}

.star-item:hover i {
    color: #f5b940;
}

.star-item input:checked + i {
    color: #f5b940;
}


/* =========================================================
   LINEAR SCALE
========================================================= */

.preview-linear-wrapper {
    max-width: 750px;
}

.linear-scale {
    display: flex;
    align-items: center;
    gap: 7px;
    flex-wrap: wrap;
}

.linear-item {
    margin: 0;
    cursor: pointer;
}

.linear-item input {
    display: none;
}

.linear-number {
    width: 38px;
    height: 38px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #dfe3e9;
    border-radius: 7px;
    color: #596273;
    font-size: 13px;
    font-weight: 600;
    transition: all .2s ease;
}

.linear-item:hover .linear-number {
    border-color: #667eea;
    color: #667eea;
}

.linear-item input:checked + .linear-number {
    background: #667eea;
    color: #fff;
    border-color: #667eea;
}

.linear-label-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
    color: #8b93a7;
    font-size: 12px;
}


/* =========================================================
   FOOTER
========================================================= */

.feedback-question-footer {
    padding: 11px 20px;
    border-top: 1px solid #edf0f4;
    background: #fafbfc;
}

.required-label {
    color: #e05b55;
    font-size: 11px;
    font-weight: 600;
}

.required-label i {
    font-size: 7px;
    margin-right: 4px;
}

.optional-label {
    color: #8b93a7;
    font-size: 11px;
}


/* =========================================================
   SUBMIT
========================================================= */

.feedback-preview-submit {
    display: flex;
    justify-content: flex-end;
    margin-top: 22px;
}

.preview-submit-btn {
    border: 0;
    border-radius: 7px;
    padding: 10px 18px;
    font-size: 13px;
    font-weight: 600;
}

.preview-submit-btn i {
    margin-right: 5px;
}


/* =========================================================
   EMPTY
========================================================= */

.feedback-empty-state {
    background: #fff;
    border: 1px solid #e5e8ee;
    border-radius: 10px;
    padding: 55px 25px;
    text-align: center;
}

.empty-icon {
    width: 65px;
    height: 65px;
    margin: 0 auto 15px;
    border-radius: 50%;
    background: #f0f2ff;
    color: #667eea;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 26px;
}

.feedback-empty-state h4 {
    margin-bottom: 8px;
    color: #252b3b;
    font-weight: 700;
}

.feedback-empty-state p {
    color: #8b93a7;
    font-size: 13px;
    margin-bottom: 18px;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media(max-width: 767px) {

    .feedback-page-header {
        flex-direction: column;
        gap: 15px;
    }

    .feedback-form-info-card {
        align-items: flex-start;
        gap: 15px;
        flex-direction: column;
    }

    .feedback-question-header {
        align-items: flex-start;
    }

    .feedback-question-type {
        display: none;
    }

    .rating-scale {
        flex-wrap: wrap;
    }

    .feedback-preview-submit {
        justify-content: stretch;
    }

    .preview-submit-btn {
        width: 100%;
    }

}

</style>

@endpush


{{-- =============================================================
     SCRIPTS
============================================================= --}}
@push('scripts')

<script>

$(document).ready(function () {

    /*
    |--------------------------------------------------------------------------
    | Preview Submit
    |--------------------------------------------------------------------------
    */

    $('#previewSubmitBtn').on('click', function () {

        if (typeof Swal !== 'undefined') {

            Swal.fire({
                icon: 'info',
                title: 'Preview Mode',
                text: 'This is only a preview. Feedback cannot be submitted from this page.',
                confirmButtonText: 'OK'
            });

        } else {

            alert(
                'This is only a preview. Feedback cannot be submitted from this page.'
            );

        }

    });


    /*
    |--------------------------------------------------------------------------
    | Radio Option Selection
    |--------------------------------------------------------------------------
    */

    $(document).on(
        'change',
        '.preview-option-row input[type="radio"]',
        function () {

            const questionName = $(this).attr('name');

            $('input[name="' + questionName + '"]')
                .closest('.preview-option-row')
                .removeClass('selected');

            $(this)
                .closest('.preview-option-row')
                .addClass('selected');

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Checkbox Selection
    |--------------------------------------------------------------------------
    */

    $(document).on(
        'change',
        '.preview-option-row input[type="checkbox"]',
        function () {

            const row = $(this).closest('.preview-option-row');

            if ($(this).is(':checked')) {
                row.addClass('selected');
            } else {
                row.removeClass('selected');
            }

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Star Rating
    |--------------------------------------------------------------------------
    */

    $(document).on(
        'change',
        '.star-item input[type="radio"]',
        function () {

            const questionId = $(this)
                .attr('name')
                .replace('preview_question_', '');

            const selectedValue = parseInt($(this).val());

            $('.star-item')
                .filter(function () {

                    const input = $(this).find('input');

                    return input.attr('name') ===
                        'preview_question_' + questionId;

                })
                .each(function () {

                    const input = $(this).find('input');

                    const value = parseInt(input.val());

                    if (value <= selectedValue) {
                        $(this).find('i').css('color', '#f5b940');
                    } else {
                        $(this).find('i').css('color', '#d8dce3');
                    }

                });

        }
    );

});

</script>

@endpush

@endsection