{{-- =========================================================
     feedback.show
========================================================= --}}

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        {{ $feedbackForm->title ?? 'Employee Feedback Form' }}
    </title>

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
    >

    <style>

        /* =====================================================
           RESET
        ====================================================== */

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }


        /* =====================================================
           BODY
        ====================================================== */

        body {
            font-family:
                Inter,
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                Roboto,
                Arial,
                sans-serif;

            background:
                linear-gradient(
                    135deg,
                    #f5f7ff 0%,
                    #eef2ff 50%,
                    #f8fafc 100%
                );

            min-height: 100vh;
            color: #1f2937;
        }


        /* =====================================================
           PAGE
        ====================================================== */

        .feedback-page {
            min-height: 100vh;
            padding: 40px 20px 60px;
        }


        /* =====================================================
           MAIN WRAPPER
        ====================================================== */

        .feedback-wrapper {
            width: 100%;
            max-width: 900px;
            margin: 0 auto;
        }


        /* =====================================================
           TOP BRAND
        ====================================================== */

        .brand-section {
            text-align: center;
            margin-bottom: 25px;
        }

        .brand-logo {
            display: inline-flex;
            align-items: center;
            justify-content: center;

            width: 78px;
            height: 78px;

            background: #ffffff;

            border-radius: 20px;

            box-shadow:
                0 10px 30px rgba(65, 80, 148, 0.12);

            padding: 12px;

            margin-bottom: 15px;
        }

        .brand-logo img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .brand-name {
            color: #415094;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
        }


        /* =====================================================
           MAIN CARD
        ====================================================== */

        .feedback-card {
            background: #ffffff;

            border-radius: 24px;

            overflow: hidden;

            box-shadow:
                0 20px 60px rgba(31, 41, 55, 0.10);
        }


        /* =====================================================
           FORM HEADER
        ====================================================== */

        .feedback-header {
            position: relative;

            padding: 42px 45px 38px;

            background:
                linear-gradient(
                    135deg,
                    #415094 0%,
                    #5366b5 100%
                );

            color: #ffffff;

            overflow: hidden;
        }

        .feedback-header::before {
            content: "";

            position: absolute;

            width: 220px;
            height: 220px;

            border-radius: 50%;

            background: rgba(255, 255, 255, 0.06);

            right: -80px;
            top: -100px;
        }

        .feedback-header::after {
            content: "";

            position: absolute;

            width: 140px;
            height: 140px;

            border-radius: 50%;

            background: rgba(255, 255, 255, 0.05);

            left: -50px;
            bottom: -70px;
        }

        .header-content {
            position: relative;
            z-index: 2;
        }

        .feedback-header h1 {
            font-size: 30px;
            line-height: 1.3;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .feedback-header p {
            font-size: 15px;
            line-height: 1.7;
            opacity: 0.88;
            max-width: 680px;
        }


        /* =====================================================
           PROGRESS
        ====================================================== */

        .progress-section {
            margin-top: 28px;
        }

        .progress-info {
            display: flex;
            justify-content: space-between;
            align-items: center;

            margin-bottom: 9px;

            font-size: 12px;
            font-weight: 600;

            opacity: 0.9;
        }

        .progress-track {
            width: 100%;
            height: 7px;

            background: rgba(255, 255, 255, 0.22);

            border-radius: 50px;

            overflow: hidden;
        }

        .progress-bar {
            height: 100%;

            width: 0%;

            background: #ffffff;

            border-radius: 50px;

            transition: width 0.3s ease;
        }


        /* =====================================================
           FORM BODY
        ====================================================== */

        .feedback-body {
            padding: 38px 45px 45px;
        }


        /* =====================================================
           USER DETAILS
        ====================================================== */

        .section-heading {
            display: flex;
            align-items: center;
            gap: 12px;

            margin-bottom: 20px;
        }

        .section-icon {
            width: 38px;
            height: 38px;

            display: flex;
            align-items: center;
            justify-content: center;

            background: #eef1ff;

            color: #415094;

            border-radius: 10px;

            font-size: 15px;
        }

        .section-heading h3 {
            font-size: 17px;
            color: #1f2937;
            font-weight: 700;
        }

        .section-heading p {
            color: #9ca3af;
            font-size: 12px;
            margin-top: 2px;
        }


        .details-grid {
            display: grid;

            grid-template-columns:
                repeat(2, minmax(0, 1fr));

            gap: 18px;

            margin-bottom: 38px;
        }


        .form-group label {
            display: block;

            margin-bottom: 8px;

            font-size: 13px;

            color: #374151;

            font-weight: 600;
        }

        .required-star {
            color: #ef4444;
        }


        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;

            left: 14px;
            top: 50%;

            transform: translateY(-50%);

            color: #9ca3af;

            font-size: 14px;

            pointer-events: none;
        }


        .form-control {
            width: 100%;

            height: 48px;

            border: 1px solid #e5e7eb;

            border-radius: 12px;

            background: #f9fafb;

            padding: 0 15px 0 42px;

            font-size: 14px;

            color: #1f2937;

            outline: none;

            transition:
                border-color 0.2s,
                box-shadow 0.2s,
                background 0.2s;
        }

        .form-control:focus {
            background: #ffffff;

            border-color: #415094;

            box-shadow:
                0 0 0 4px rgba(65, 80, 148, 0.08);
        }

        textarea.form-control {
            height: auto;

            min-height: 110px;

            padding: 14px;

            resize: vertical;
        }


        /* =====================================================
           QUESTION SECTION
        ====================================================== */

        .questions-heading {
            margin-bottom: 22px;
        }


        /* =====================================================
           QUESTION CARD
        ====================================================== */

        .question-card {
            position: relative;

            background: #ffffff;

            border: 1px solid #e9ebf0;

            border-radius: 16px;

            padding: 25px;

            margin-bottom: 18px;

            transition:
                border-color 0.2s,
                box-shadow 0.2s,
                transform 0.2s;
        }

        .question-card:hover {
            border-color: #d8dcf0;

            box-shadow:
                0 8px 25px rgba(31, 41, 55, 0.05);
        }

        .question-card.question-invalid {
            border-color: #ef4444;

            box-shadow:
                0 0 0 3px rgba(239, 68, 68, 0.06);
        }


        .question-top {
            display: flex;

            justify-content: space-between;

            gap: 15px;

            margin-bottom: 20px;
        }


        .question-number {
            flex-shrink: 0;

            width: 34px;
            height: 34px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 10px;

            background: #eef1ff;

            color: #415094;

            font-size: 13px;

            font-weight: 700;
        }


        .question-text-wrapper {
            flex: 1;
        }

        .question-text {
            font-size: 15px;

            line-height: 1.6;

            color: #1f2937;

            font-weight: 650;
        }

        .question-type-label {
            display: inline-block;

            margin-top: 7px;

            font-size: 10px;

            text-transform: uppercase;

            letter-spacing: 0.6px;

            color: #9ca3af;

            font-weight: 700;
        }


        /* =====================================================
           OPTIONS
        ====================================================== */

        .options-list {
            display: grid;

            gap: 10px;
        }


        .option-card {
            position: relative;

            display: flex;

            align-items: center;

            gap: 12px;

            min-height: 48px;

            padding: 12px 15px;

            border: 1px solid #e5e7eb;

            border-radius: 11px;

            background: #fafafa;

            cursor: pointer;

            transition:
                border-color 0.2s,
                background 0.2s,
                transform 0.15s;
        }

        .option-card:hover {
            background: #f5f7ff;

            border-color: #cfd5f0;

            transform: translateY(-1px);
        }


        .option-card input {
            position: absolute;

            opacity: 0;

            pointer-events: none;
        }


        .custom-radio {
            width: 19px;
            height: 19px;

            flex-shrink: 0;

            border: 2px solid #cfd3dc;

            border-radius: 50%;

            display: flex;

            align-items: center;

            justify-content: center;
        }

        .custom-radio::after {
            content: "";

            width: 9px;
            height: 9px;

            border-radius: 50%;

            background: #ffffff;

            transform: scale(0);

            transition: transform 0.15s;
        }


        .option-card input:checked ~ .custom-radio {
            border-color: #415094;

            background: #415094;
        }

        .option-card input:checked ~ .custom-radio::after {
            transform: scale(1);
        }


        .custom-checkbox {
            width: 19px;
            height: 19px;

            flex-shrink: 0;

            border: 2px solid #cfd3dc;

            border-radius: 5px;

            display: flex;

            align-items: center;

            justify-content: center;

            color: #ffffff;

            font-size: 10px;
        }

        .option-card input:checked ~ .custom-checkbox {
            background: #415094;

            border-color: #415094;
        }


        .option-text {
            font-size: 14px;

            color: #4b5563;

            line-height: 1.4;
        }

        .option-card input:checked ~ .option-text {
            color: #415094;

            font-weight: 600;
        }


        /* =====================================================
           RATING
        ====================================================== */

        .rating-options {
            display: grid;

            grid-template-columns:
                repeat(5, minmax(0, 1fr));

            gap: 10px;
        }

        .rating-option {
            position: relative;
        }

        .rating-option input {
            position: absolute;

            opacity: 0;

            pointer-events: none;
        }

        .rating-option label {
            display: flex;

            flex-direction: column;

            align-items: center;

            justify-content: center;

            gap: 7px;

            min-height: 75px;

            padding: 10px;

            border: 1px solid #e5e7eb;

            background: #fafafa;

            border-radius: 12px;

            cursor: pointer;

            text-align: center;

            transition: 0.2s;
        }

        .rating-option label:hover {
            border-color: #cfd5f0;

            background: #f5f7ff;
        }

        .rating-number {
            width: 32px;
            height: 32px;

            border-radius: 50%;

            display: flex;

            align-items: center;
            justify-content: center;

            background: #ffffff;

            border: 1px solid #dfe2e8;

            color: #6b7280;

            font-weight: 700;

            font-size: 13px;
        }

        .rating-text {
            font-size: 10px;

            line-height: 1.25;

            color: #9ca3af;
        }

        .rating-option input:checked + label {
            background: #eef1ff;

            border-color: #415094;

            box-shadow:
                0 5px 15px rgba(65, 80, 148, 0.08);
        }

        .rating-option input:checked + label .rating-number {
            background: #415094;

            color: #ffffff;

            border-color: #415094;
        }

        .rating-option input:checked + label .rating-text {
            color: #415094;

            font-weight: 700;
        }


        /* =====================================================
           STAR RATING
        ====================================================== */

        .star-rating {
            display: flex;

            justify-content: center;

            gap: 7px;

            padding: 8px 0;
        }

        .star-item {
            position: relative;
        }

        .star-item input {
            position: absolute;

            opacity: 0;

            pointer-events: none;
        }

        .star-item label {
            display: block;

            font-size: 37px;

            line-height: 1;

            color: #d9dce4;

            cursor: pointer;

            transition:
                color 0.15s,
                transform 0.15s;
        }

        .star-item label:hover {
            color: #f5b301;

            transform: scale(1.08);
        }

        .star-item input:checked + label {
            color: #f5b301;
        }


        /* =====================================================
           LINEAR SCALE
        ====================================================== */

        .linear-scale {
            display: flex;

            align-items: center;

            gap: 7px;

            overflow-x: auto;

            padding-bottom: 5px;
        }

        .linear-item {
            flex: 1;

            min-width: 48px;

            position: relative;
        }

        .linear-item input {
            position: absolute;

            opacity: 0;

            pointer-events: none;
        }

        .linear-item label {
            height: 45px;

            display: flex;

            align-items: center;

            justify-content: center;

            border: 1px solid #e1e4ea;

            border-radius: 10px;

            background: #fafafa;

            color: #6b7280;

            font-weight: 600;

            font-size: 13px;

            cursor: pointer;

            transition: 0.2s;
        }

        .linear-item label:hover {
            border-color: #cfd5f0;

            background: #f5f7ff;

            color: #415094;
        }

        .linear-item input:checked + label {
            background: #415094;

            border-color: #415094;

            color: #ffffff;
        }

        .linear-labels {
            display: flex;

            justify-content: space-between;

            margin-top: 9px;

            font-size: 11px;

            color: #9ca3af;
        }


        /* =====================================================
           TEXT ANSWER
        ====================================================== */

        .text-answer textarea {
            width: 100%;

            min-height: 115px;

            border: 1px solid #e5e7eb;

            background: #fafafa;

            border-radius: 12px;

            padding: 14px;

            font-size: 14px;

            color: #1f2937;

            outline: none;

            resize: vertical;

            transition: 0.2s;
        }

        .text-answer textarea:focus {
            background: #ffffff;

            border-color: #415094;

            box-shadow:
                0 0 0 4px rgba(65, 80, 148, 0.08);
        }


        /* =====================================================
           ERROR
        ====================================================== */

        .question-error {
            display: none;

            margin-top: 12px;

            color: #ef4444;

            font-size: 12px;

            font-weight: 500;
        }


        /* =====================================================
           COMMENTS
        ====================================================== */

        .comments-section {
            margin-top: 30px;

            padding-top: 30px;

            border-top: 1px solid #edf0f4;
        }


        /* =====================================================
           SUBMIT AREA
        ====================================================== */

        .submit-area {
            display: flex;

            justify-content: flex-end;

            align-items: center;

            gap: 15px;

            margin-top: 35px;

            padding-top: 25px;

            border-top: 1px solid #edf0f4;
        }

        .submit-note {
            color: #9ca3af;

            font-size: 11px;
        }

        .submit-button {
            display: inline-flex;

            align-items: center;

            justify-content: center;

            gap: 9px;

            min-width: 175px;

            height: 50px;

            border: none;

            border-radius: 12px;

            background: #415094;

            color: #ffffff;

            font-size: 14px;

            font-weight: 700;

            cursor: pointer;

            box-shadow:
                0 8px 20px rgba(65, 80, 148, 0.20);

            transition:
                background 0.2s,
                transform 0.2s,
                box-shadow 0.2s;
        }

        .submit-button:hover {
            background: #35447f;

            transform: translateY(-1px);

            box-shadow:
                0 10px 25px rgba(65, 80, 148, 0.25);
        }

        .submit-button:disabled {
            opacity: 0.65;

            cursor: not-allowed;

            transform: none;
        }


        /* =====================================================
           THANK YOU
        ====================================================== */

        #thankYouSection {
            display: none;

            padding: 70px 40px;

            text-align: center;
        }

        .success-icon {
            width: 82px;
            height: 82px;

            margin: 0 auto 22px;

            border-radius: 50%;

            display: flex;

            align-items: center;

            justify-content: center;

            background: #eaf8ef;

            color: #22a559;

            font-size: 36px;
        }

        #thankYouSection h2 {
            font-size: 28px;

            color: #1f2937;

            margin-bottom: 10px;
        }

        #thankYouSection p {
            max-width: 500px;

            margin: 0 auto 25px;

            color: #6b7280;

            font-size: 14px;

            line-height: 1.7;
        }

        .home-button {
            display: inline-flex;

            align-items: center;

            gap: 8px;

            padding: 12px 22px;

            border-radius: 10px;

            background: #415094;

            color: #ffffff;

            text-decoration: none;

            font-size: 13px;

            font-weight: 600;
        }


        /* =====================================================
           FOOTER
        ====================================================== */

        .footer {
            text-align: center;

            padding-top: 22px;

            color: #9ca3af;

            font-size: 11px;
        }


        /* =====================================================
           EMAIL ERROR
        ====================================================== */

        #emailError {
            display: none;

            color: #ef4444;

            font-size: 11px;

            margin-top: 6px;
        }


        /* =====================================================
           RESPONSIVE
        ====================================================== */

        @media (max-width: 767px) {

            .feedback-page {
                padding: 20px 12px 40px;
            }

            .feedback-header {
                padding: 30px 22px;
            }

            .feedback-header h1 {
                font-size: 23px;
            }

            .feedback-body {
                padding: 28px 18px 30px;
            }

            .details-grid {
                grid-template-columns: 1fr;
            }

            .question-card {
                padding: 18px;
            }

            .rating-options {
                grid-template-columns:
                    repeat(5, minmax(48px, 1fr));

                gap: 5px;
            }

            .rating-option label {
                min-height: 65px;
            }

            .rating-text {
                display: none;
            }

            .star-item label {
                font-size: 31px;
            }

            .submit-area {
                flex-direction: column;

                align-items: stretch;
            }

            .submit-note {
                text-align: center;
            }

            .submit-button {
                width: 100%;
            }

            #thankYouSection {
                padding: 55px 20px;
            }

        }

        /* =====================================================
   OPTIONAL LABEL
====================================================== */

.optional-label {
    color: #9ca3af;
    font-size: 11px;
    font-weight: 500;
    margin-left: 4px;
}


/* =====================================================
   SELECT
====================================================== */

.select-control {
    appearance: none;
    -webkit-appearance: none;

    cursor: pointer;

    padding-right: 40px;

    background-image:
        linear-gradient(45deg, transparent 50%, #9ca3af 50%),
        linear-gradient(135deg, #9ca3af 50%, transparent 50%);

    background-position:
        calc(100% - 18px) 21px,
        calc(100% - 13px) 21px;

    background-size:
        5px 5px,
        5px 5px;

    background-repeat: no-repeat;
}

.select-control:focus {
    background-color: #ffffff;
}


/* =====================================================
   FIELD ERROR
====================================================== */

.field-error {
    display: none;

    margin-top: 6px;

    color: #ef4444;

    font-size: 11px;
}


/* =====================================================
   INVALID INPUT
====================================================== */

.form-control.input-error {/* =====================================================
   OPTIONAL LABEL
====================================================== */

.optional-label {
    color: #9ca3af;
    font-size: 11px;
    font-weight: 500;
    margin-left: 4px;
}


/* =====================================================
   SELECT
====================================================== */

.select-control {
    appearance: none;
    -webkit-appearance: none;

    cursor: pointer;

    padding-right: 40px;

    background-image:
        linear-gradient(45deg, transparent 50%, #9ca3af 50%),
        linear-gradient(135deg, #9ca3af 50%, transparent 50%);

    background-position:
        calc(100% - 18px) 21px,
        calc(100% - 13px) 21px;

    background-size:
        5px 5px,
        5px 5px;

    background-repeat: no-repeat;
}

.select-control:focus {
    background-color: #ffffff;
}


/* =====================================================
   FIELD ERROR
====================================================== */

.field-error {
    display: none;

    margin-top: 6px;

    color: #ef4444;

    font-size: 11px;
}


/* =====================================================
   INVALID INPUT
====================================================== */

.form-control.input-error {
    border-color: #ef4444 !important;

    background: #fffafa;
}

.form-control.input-error:focus {
    border-color: #ef4444 !important;

    box-shadow:
        0 0 0 4px rgba(239, 68, 68, 0.08);
}
    border-color: #ef4444 !important;

    background: #fffafa;
}

.form-control.input-error:focus {
    border-color: #ef4444 !important;

    box-shadow:
        0 0 0 4px rgba(239, 68, 68, 0.08);
}   

    </style>

</head>


<body>


<div class="feedback-page">

    <div class="feedback-wrapper">


        {{-- =====================================================
             BRAND
        ====================================================== --}}

        <div class="brand-section">

            <div class="brand-logo">



                    <img
                        src="{{ asset('public/img/nbtclogo.png') }}"
                        alt="NBTC"
                    >

                

            </div>

            <div class="brand-name">
                NBTC
            </div>

        </div>


        {{-- =====================================================
             MAIN CARD
        ====================================================== --}}

        <div class="feedback-card">


            {{-- =================================================
                 FORM SECTION
            ================================================== --}}

            <div id="feedbackFormSection">


                {{-- =============================================
                     HEADER
                ============================================== --}}

                <div class="feedback-header">

                    <div class="header-content">

                        <h1>
                            {{ $feedbackForm->title ?? 'Employee Feedback Form' }}
                        </h1>

                        <p>
                            {{ $feedbackForm->description ?? 'We value your feedback. Please take a few minutes to share your experience with us.' }}
                        </p>


                        {{-- =====================================
                             PROGRESS
                        ====================================== --}}

                        @php
                            $totalQuestions = count($feedbackquestions ?? []);
                        @endphp

                        <div class="progress-section">

                            <div class="progress-info">

                                <span id="progressText">
                                    0 of {{ $totalQuestions }} answered
                                </span>

                                <span id="progressPercent">
                                    0%
                                </span>

                            </div>

                            <div class="progress-track">

                                <div
                                    class="progress-bar"
                                    id="progressBar"
                                ></div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =============================================
                     BODY
                ============================================== --}}

                <div class="feedback-body">


                    <form
                        id="feedbackForm"
                        method="POST"
                        action="{{ route('feedback.submit', ['feedbackform_id' => $feedbackform_id]) }}"
                    >

                        @csrf


                        {{-- =====================================
                             FORM ID
                        ====================================== --}}

                        <input
                            type="hidden"
                            name="feedbackform_id"
                            value="{{ $feedbackform_id }}"
                        >


                        {{-- =====================================
                             PERSONAL INFORMATION
                        ====================================== --}}

                                <div class="section-heading">

                                    <div class="section-icon">
                                        <i class="fas fa-user"></i>
                                    </div>

                                    <div>
                                        <h3>Your Information</h3>

                                        <p>
                                            Please provide your basic details
                                        </p>
                                    </div>

                                </div>


                                <div class="details-grid">



                                    {{-- =================================================
                                        NAME    
                                    ================================================== --}}

                                    <div class="form-group">

                                        <label for="name">

                                            Name

                                            <span class="optional-label">
                                                (Optional)
                                            </span>

                                        </label>

                                        <div class="input-wrapper">

                                            <i class="fas fa-user input-icon"></i>

                                            <input
                                                type="text"
                                                id="name"
                                                name="name"
                                                class="form-control"
                                                value="{{ old('name', request('name')) }}"
                                                placeholder="Enter your name"
                                                autocomplete="name"
                                            >

                                        </div>

                                        <div
                                            id="nameError"
                                            class="field-error"
                                        ></div>

                                    </div>


                                    {{-- =================================================
                                        EMAIL
                                    ================================================== --}}

                                    <div class="form-group">

                                        <label for="email">

                                            Email

                                            <span class="optional-label">
                                                (Optional)
                                            </span>

                                        </label>

                                        <div class="input-wrapper">

                                            <i class="fas fa-envelope input-icon"></i>

                                            <input
                                                type="email"
                                                id="email"
                                                name="email"
                                                class="form-control"
                                                value="{{ old('email', request('email')) }}"
                                                placeholder="Enter your email"
                                                autocomplete="email"
                                            >

                                        </div>

                                        <div id="emailError"></div>

                                    </div>


                                    {{-- =================================================
                                        LOCATION
                                    ================================================== --}}

                                    <div class="form-group">

                                        <label for="location">

                                            Location

                                            <span class="optional-label">
                                                (Optional)
                                            </span>

                                        </label>

                                        <div class="input-wrapper">

                                            <i class="fas fa-map-marker-alt input-icon"></i>

                                            <select
                                                name="location"
                                                id="location"
                                                class="form-control select-control"
                                            >

                                                <option value="">
                                                    Select location
                                                </option>

                                                <option
                                                    value="1"
                                                    {{ old('location', request('location')) == 1 ? 'selected' : '' }}
                                                >
                                                    Kuwait
                                                </option>

                                                <option
                                                    value="2"
                                                    {{ old('location', request('location')) == 2 ? 'selected' : '' }}
                                                >
                                                    UAE
                                                </option>

                                                <option
                                                    value="3"
                                                    {{ old('location', request('location')) == 3 ? 'selected' : '' }}
                                                >
                                                    KSA
                                                </option>

                                            </select>

                                        </div>

                                    </div>


                                    {{-- =================================================
                                        DESIGNATION
                                    ================================================== --}}

                                    <div class="form-group">

                                        <label for="designation">

                                            Designation

                                            <span class="optional-label">
                                                (Optional)
                                            </span>

                                        </label>

                                        <div class="input-wrapper">

                                            <i class="fas fa-id-badge input-icon"></i>

                                            <input
                                                type="text"
                                                id="designation"
                                                name="designation"
                                                class="form-control"
                                                value="{{ old('designation', request('designation')) }}"
                                                placeholder="Enter your designation"
                                            >

                                        </div>

                                    </div>


                                    {{-- =================================================
                                        DEPARTMENT
                                    ================================================== --}}

                                    <div class="form-group">

                                        <label for="department">

                                            Department

                                            <span class="optional-label">
                                                (Optional)
                                            </span>

                                        </label>

                                        <div class="input-wrapper">

                                            <i class="fas fa-building input-icon"></i>

                                            <input
                                                type="text"
                                                id="department"
                                                name="department"
                                                class="form-control"
                                                value="{{ old('department', request('department')) }}"
                                                placeholder="Enter your department"
                                            >

                                        </div>

                                    </div>


                                    {{-- =================================================
                                        FUNCTION
                                    ================================================== --}}

                                    <div class="form-group">

                                        <label for="function">

                                            Function

                                            <span class="optional-label">
                                                (Optional)
                                            </span>

                                        </label>

                                        <div class="input-wrapper">

                                            <i class="fas fa-briefcase input-icon"></i>

                                            <input
                                                type="text"
                                                id="function"
                                                name="function"
                                                class="form-control"
                                                value="{{ old('function', request('function')) }}"
                                                placeholder="Enter your function"
                                            >

                                        </div>

                                    </div>

                                </div>


                        {{-- =====================================
                             QUESTIONS
                        ====================================== --}}

                        <div class="section-heading questions-heading">

                            <div class="section-icon">

                                <i class="fas fa-comment-alt"></i>

                            </div>

                            <div>

                                <h3>
                                    Your Feedback
                                </h3>

                                <p>
                                    Please answer the questions below
                                </p>

                            </div>

                        </div>


                        {{-- =====================================
                             QUESTIONS
                        ====================================== --}}

                        @forelse($feedbackquestions as $index => $question_val)


                            <div
                                class="question-card"
                                data-question-id="{{ $question_val->id }}"
                                data-required="{{ $question_val->is_required ? 1 : 0 }}"
                                data-question-type="{{ $question_val->question_type }}"
                            >


                                <div class="question-top">


                                    <div class="question-number">

                                        {{ $index + 1 }}

                                    </div>


                                    <div class="question-text-wrapper">

                                        <div class="question-text">

                                            {{ $question_val->question_text }}

                                            @if($question_val->is_required)

                                                <span class="required-star">
                                                    *
                                                </span>

                                            @endif

                                        </div>


                                        <span class="question-type-label">

                                            @switch($question_val->question_type)

                                                @case('radio')
                                                    Single Choice
                                                    @break

                                                @case('checkbox')
                                                    Multiple Choice
                                                    @break

                                                @case('text')
                                                    Written Response
                                                    @break

                                                @case('rating')
                                                    Rating
                                                    @break

                                                @case('starrating')
                                                    Star Rating
                                                    @break

                                                @case('linearscale')
                                                    Linear Scale
                                                    @break

                                                @default
                                                    Feedback

                                            @endswitch

                                        </span>

                                    </div>


                                </div>


                                {{-- =================================
                                     RADIO
                                ================================== --}}

                                @if($question_val->question_type == 'radio')


                                    <div class="options-list">


                                        @forelse($question_val->options as $option)


                                            <label class="option-card">

                                                <input
                                                    type="radio"
                                                    name="answers[{{ $question_val->id }}]"
                                                    value="{{ $option->option_text }}"
                                                >

                                                <span class="custom-radio"></span>

                                                <span class="option-text">
                                                    {{ $option->option_text }}
                                                </span>

                                            </label>


                                        @empty


                                            <label class="option-card">

                                                <input
                                                    type="radio"
                                                    name="answers[{{ $question_val->id }}]"
                                                    value="Yes"
                                                >

                                                <span class="custom-radio"></span>

                                                <span class="option-text">
                                                    Yes
                                                </span>

                                            </label>


                                            <label class="option-card">

                                                <input
                                                    type="radio"
                                                    name="answers[{{ $question_val->id }}]"
                                                    value="No"
                                                >

                                                <span class="custom-radio"></span>

                                                <span class="option-text">
                                                    No
                                                </span>

                                            </label>


                                        @endforelse


                                    </div>


                                {{-- =================================
                                     CHECKBOX
                                ================================== --}}

                                @elseif($question_val->question_type == 'checkbox')


                                    <div class="options-list">


                                        @forelse($question_val->options as $option)


                                            <label class="option-card">

                                                <input
                                                    type="checkbox"
                                                    name="answers[{{ $question_val->id }}][]"
                                                    value="{{ $option->option_text }}"
                                                >

                                                <span class="custom-checkbox">

                                                    <i class="fas fa-check"></i>

                                                </span>

                                                <span class="option-text">
                                                    {{ $option->option_text }}
                                                </span>

                                            </label>


                                        @empty


                                            <div style="
                                                color:#9ca3af;
                                                font-size:13px;
                                                padding:10px 0;
                                            ">
                                                No options available.
                                            </div>


                                        @endforelse


                                    </div>


                                {{-- =================================
                                     TEXT
                                ================================== --}}

                                @elseif($question_val->question_type == 'text')


                                    <div class="text-answer">

                                        <textarea
                                            name="answers[{{ $question_val->id }}]"
                                            placeholder="Write your answer here..."
                                        ></textarea>

                                    </div>


                                {{-- =================================
                                     RATING
                                ================================== --}}

                                @elseif($question_val->question_type == 'rating')


                                    @php

                                        $ratingLabels = [
                                            1 => 'Strongly Disagree',
                                            2 => 'Disagree',
                                            3 => 'Neutral',
                                            4 => 'Agree',
                                            5 => 'Strongly Agree',
                                        ];

                                    @endphp


                                    <div class="rating-options">


                                        @for($i = 1; $i <= 5; $i++)


                                            <div class="rating-option">


                                                <input
                                                    type="radio"
                                                    id="rating_{{ $question_val->id }}_{{ $i }}"
                                                    name="answers[{{ $question_val->id }}]"
                                                    value="{{ $i }}"
                                                >


                                                <label
                                                    for="rating_{{ $question_val->id }}_{{ $i }}"
                                                >

                                                    <span class="rating-number">
                                                        {{ $i }}
                                                    </span>

                                                    <span class="rating-text">
                                                        {{ $ratingLabels[$i] }}
                                                    </span>

                                                </label>


                                            </div>


                                        @endfor


                                    </div>


                                {{-- =================================
                                     STAR RATING
                                ================================== --}}

                                @elseif($question_val->question_type == 'starrating')


                                    <div class="star-rating">


                                        @for($i = 1; $i <= 5; $i++)


                                            <div class="star-item">


                                                <input
                                                    type="radio"
                                                    id="star_{{ $question_val->id }}_{{ $i }}"
                                                    name="answers[{{ $question_val->id }}]"
                                                    value="{{ $i }}"
                                                >


                                                <label
                                                    for="star_{{ $question_val->id }}_{{ $i }}"
                                                    title="{{ $i }} Star"
                                                >
                                                    ★
                                                </label>


                                            </div>


                                        @endfor


                                    </div>


                                {{-- =================================
                                     LINEAR SCALE
                                ================================== --}}

                                @elseif($question_val->question_type == 'linearscale')


                                    @php

                                        $points =
                                            (int) (
                                                $question_val->linear_points
                                                ?? 5
                                            );

                                        if ($points < 2) {
                                            $points = 5;
                                        }

                                        if ($points > 15) {
                                            $points = 15;
                                        }

                                    @endphp


                                    <div class="linear-scale">


                                        @for($i = 1; $i <= $points; $i++)


                                            <div class="linear-item">


                                                <input
                                                    type="radio"
                                                    id="linear_{{ $question_val->id }}_{{ $i }}"
                                                    name="answers[{{ $question_val->id }}]"
                                                    value="{{ $i }}"
                                                >


                                                <label
                                                    for="linear_{{ $question_val->id }}_{{ $i }}"
                                                >

                                                    {{ $i }}

                                                </label>


                                            </div>


                                        @endfor


                                    </div>


                                    <div class="linear-labels">

                                        <span>
                                            {{ $question_val->linear_min_label ?? '' }}
                                        </span>

                                        <span>
                                            {{ $question_val->linear_max_label ?? '' }}
                                        </span>

                                    </div>


                                {{-- =================================
                                     FALLBACK
                                ================================== --}}

                                @else


                                    <div class="text-answer">

                                        <textarea
                                            name="answers[{{ $question_val->id }}]"
                                            placeholder="Write your answer here..."
                                        ></textarea>

                                    </div>


                                @endif


                                {{-- =================================
                                     ERROR
                                ================================== --}}

                                <div class="question-error">
                                    Please answer this question.
                                </div>


                            </div>


                        @empty


                            <div style="
                                text-align:center;
                                padding:35px 20px;
                                border:1px dashed #dfe2e8;
                                border-radius:15px;
                                color:#9ca3af;
                            ">

                                <i
                                    class="fas fa-inbox"
                                    style="
                                        font-size:30px;
                                        margin-bottom:10px;
                                    "
                                ></i>

                                <div>
                                    No feedback questions available.
                                </div>

                            </div>


                        @endforelse


                        {{-- =====================================
                             ADDITIONAL COMMENTS
                        ====================================== --}}

                        <div class="comments-section">


                            <div class="section-heading">

                                <div class="section-icon">

                                    <i class="fas fa-pencil-alt"></i>

                                </div>

                                <div>

                                    <h3>
                                        Additional Comments
                                    </h3>

                                    <p>
                                        Share anything else you would like us to know
                                    </p>

                                </div>

                            </div>


                            <div class="form-group">

                                <textarea
                                    id="comments"
                                    name="comments"
                                    class="form-control"
                                    placeholder="Write your comments here..."
                                ></textarea>

                            </div>


                        </div>


                        {{-- =====================================
                             SUBMIT
                        ====================================== --}}

                        <div class="submit-area">

                            <div class="submit-note">

                                <i class="fas fa-lock"></i>

                                Your feedback is securely submitted.

                            </div>


                            <button
                                type="submit"
                                id="submitFeedback"
                                class="submit-button"
                            >

                                <span id="submitText">

                                    Submit Feedback

                                    <i class="fas fa-arrow-right"></i>

                                </span>


                                <span
                                    id="submitLoader"
                                    style="display:none;"
                                >

                                    <i class="fas fa-spinner fa-spin"></i>

                                    Submitting...

                                </span>

                            </button>

                        </div>


                    </form>


                </div>


            </div>


            {{-- =================================================
                 THANK YOU
            ================================================== --}}

            <div id="thankYouSection">


                <div class="success-icon">

                    <i class="fas fa-check"></i>

                </div>


                <h2>
                    Thank You!
                </h2>


                <p>
                    Your feedback has been submitted successfully.
                    We truly appreciate you taking the time to share
                    your valuable feedback with us.
                </p>


                <!-- <a href="{{ url('/nbtc/feedback/' . $feedbackform_id . '/QAm62pfYtYbZ2BSthykWVxulHv2totV3ZZ3ccJ4KWihBAwDUh7jABJX5erFfiPRZ') }}"
                    class="home-button">
                        Return Home
                    </a> -->


            </div>


        </div>


        {{-- =====================================================
             FOOTER
        ====================================================== --}}

        <div class="footer">

            © {{ date('Y') }} NBTC. All rights reserved.

        </div>


    </div>

</div>


<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>

$(document).ready(function () {


    /* =========================================================
       CSRF
    ========================================================== */

    $.ajaxSetup({

        headers: {

            'X-CSRF-TOKEN':
                $('meta[name="csrf-token"]').attr('content')

        }

    });


    /* =========================================================
       QUESTION COUNT
    ========================================================== */

    const totalQuestions =
        $('.question-card').length;


    /* =========================================================
       UPDATE PROGRESS
    ========================================================== */

    function updateProgress()
    {

        let answered = 0;


        $('.question-card').each(function () {

            const card =
                $(this);

            const type =
                card.data('question-type');


            let hasAnswer = false;


            if (
                type === 'radio' ||
                type === 'rating' ||
                type === 'starrating' ||
                type === 'linearscale'
            ) {

                hasAnswer =
                    card.find(
                        'input[type="radio"]:checked'
                    ).length > 0;

            }
            else if (type === 'checkbox') {

                hasAnswer =
                    card.find(
                        'input[type="checkbox"]:checked'
                    ).length > 0;

            }
            else if (type === 'text') {

                hasAnswer =
                    $.trim(
                        card.find('textarea').val() || ''
                    ) !== '';

            }


            if (hasAnswer) {

                answered++;

            }

        });


        let percentage = 0;


        if (totalQuestions > 0) {

            percentage =
                Math.round(
                    (answered / totalQuestions) * 100
                );

        }


        $('#progressText').text(
            answered +
            ' of ' +
            totalQuestions +
            ' answered'
        );


        $('#progressPercent').text(
            percentage + '%'
        );


        $('#progressBar').css(
            'width',
            percentage + '%'
        );

    }


    /* =========================================================
       HIDE QUESTION ERROR
    ========================================================== */

    function hideQuestionError(card)
    {

        card.removeClass(
            'question-invalid'
        );

        card.find(
            '.question-error'
        ).hide();

    }


    /* =========================================================
       SHOW QUESTION ERROR
    ========================================================== */

    function showQuestionError(
        card,
        message = 'Please answer this question.'
    )
    {

        card.addClass(
            'question-invalid'
        );

        card.find(
            '.question-error'
        )
        .text(message)
        .show();

    }


    /* =========================================================
       INPUT EVENTS
    ========================================================== */

    $(document).on(
        'change',
        '.question-card input',
        function () {

            const card =
                $(this).closest('.question-card');

            hideQuestionError(card);

            updateProgress();

        }
    );


    $(document).on(
        'input',
        '.question-card textarea',
        function () {

            const card =
                $(this).closest('.question-card');

            if (
                $.trim($(this).val()) !== ''
            ) {

                hideQuestionError(card);

            }

            updateProgress();

        }
    );


    /* =========================================================
       NAME INPUT
    ========================================================== */

    $('#name').on('input', function () {

        $(this).css(
            'border-color',
            '#e5e7eb'
        );

    });


    /* =========================================================
       EMAIL INPUT
    ========================================================== */

    $('#email').on('input', function () {

        $(this).css(
            'border-color',
            '#e5e7eb'
        );

        $('#emailError')
            .hide()
            .text('');

    });


    /* =========================================================
       FORM SUBMIT
    ========================================================== */

    $('#feedbackForm').on(
        'submit',
        function (e) {

            e.preventDefault();


            let isValid = true;

            let firstInvalidElement = null;


            /* =================================================
               NAME
            ================================================== */

            const name =
                $.trim(
                    $('#name').val()
                );


           


            /* =================================================
               EMAIL
            ================================================== */

            const email =
                $.trim(
                    $('#email').val()
                );




            /* =================================================
               REQUIRED QUESTIONS
            ================================================== */

            $('.question-card').each(
                function () {


                    const card =
                        $(this);


                    const required =
                        card.data('required') == 1;


                    if (!required) {

                        return;

                    }


                    const type =
                        card.data('question-type');


                    let answered = false;


                    /* =========================================
                       RADIO / RATING
                    ========================================== */

                    if (
                        type === 'radio' ||
                        type === 'rating' ||
                        type === 'starrating' ||
                        type === 'linearscale'
                    ) {

                        answered =
                            card.find(
                                'input[type="radio"]:checked'
                            ).length > 0;

                    }


                    /* =========================================
                       CHECKBOX
                    ========================================== */

                    else if (
                        type === 'checkbox'
                    ) {

                        answered =
                            card.find(
                                'input[type="checkbox"]:checked'
                            ).length > 0;

                    }


                    /* =========================================
                       TEXT
                    ========================================== */

                    else if (
                        type === 'text'
                    ) {

                        answered =
                            $.trim(
                                card.find(
                                    'textarea'
                                ).val() || ''
                            ) !== '';

                    }


                    /* =========================================
                       INVALID
                    ========================================== */

                    if (!answered) {

                        isValid = false;


                        showQuestionError(
                            card
                        );


                        if (
                            !firstInvalidElement
                        ) {

                            firstInvalidElement =
                                card;

                        }

                    }
                    else {

                        hideQuestionError(
                            card
                        );

                    }


                }
            );


            /* =================================================
               STOP IF INVALID
            ================================================== */

            if (!isValid) {


                if (
                    firstInvalidElement &&
                    firstInvalidElement.length
                ) {

                    $('html, body').animate({

                        scrollTop:
                            firstInvalidElement.offset().top - 80

                    }, 450);

                }


                Swal.fire({

                    icon: 'warning',

                    title: 'Almost there!',

                    text:
                        'Please complete all required fields and questions.',

                    confirmButtonColor:
                        '#415094'

                });


                return;

            }


            /* =================================================
               DISABLE BUTTON
            ================================================== */

            $('#submitFeedback')
                .prop(
                    'disabled',
                    true
                );


            $('#submitText')
                .hide();


            $('#submitLoader')
                .show();


            /* =================================================
               AJAX
            ================================================== */

            const form =
                $(this);


            $.ajax({

                url:
                    form.attr('action'),

                type:
                    'POST',

                data:
                    form.serialize(),


                success:
                    function (response) {


                        /* =====================================
                           HIDE FORM
                        ====================================== */

                        $('#feedbackFormSection')
                            .fadeOut(
                                250,
                                function () {

                                    $('#thankYouSection')
                                        .fadeIn(350);

                                }
                            );


                        $('html, body').animate({

                            scrollTop:
                                $('.feedback-card').offset().top - 30

                        }, 400);


                        Swal.fire({

                            icon: 'success',

                            title: 'Feedback Submitted',

                            text:
                                response.message ||
                                'Your feedback has been submitted successfully.',

                            confirmButtonColor:
                                '#415094'

                        });

                    },


                error:
                    function (xhr) {


                        /* =====================================
                           ENABLE BUTTON
                        ====================================== */

                        $('#submitFeedback')
                            .prop(
                                'disabled',
                                false
                            );


                        $('#submitText')
                            .show();


                        $('#submitLoader')
                            .hide();


                        /* =====================================
                           VALIDATION ERROR
                        ====================================== */

                        if (
                            xhr.status === 422
                        ) {


                            const errors =
                                xhr.responseJSON?.errors ||
                                {};


                            let firstError =
                                null;


                            Object.keys(errors)
                                .forEach(
                                    function (key) {


                                        /* =====================
                                           EMAIL
                                        ====================== */

                                        if (
                                            key === 'email'
                                        ) {

                                            $('#email')
                                                .css(
                                                    'border-color',
                                                    '#ef4444'
                                                );


                                            $('#emailError')
                                                .text(
                                                    errors[key][0]
                                                )
                                                .show();


                                            if (
                                                !firstError
                                            ) {

                                                firstError =
                                                    $('#email');

                                            }

                                        }


                                        /* =====================
                                           NAME
                                        ====================== */

                                        if (
                                            key === 'name'
                                        ) {

                                            $('#name')
                                                .css(
                                                    'border-color',
                                                    '#ef4444'
                                                );


                                            if (
                                                !firstError
                                            ) {

                                                firstError =
                                                    $('#name');

                                            }

                                        }


                                        /* =====================
                                           answers.28
                                        ====================== */

                                        const answerMatch =
                                            key.match(
                                                /^answers\.(\d+)$/
                                            );


                                        if (
                                            answerMatch
                                        ) {


                                            const questionId =
                                                answerMatch[1];


                                            const card =
                                                $('.question-card[data-question-id="' +
                                                questionId +
                                                '"]');


                                            if (
                                                card.length
                                            ) {


                                                showQuestionError(
                                                    card,
                                                    errors[key][0]
                                                );


                                                if (
                                                    !firstError
                                                ) {

                                                    firstError =
                                                        card;

                                                }

                                            }

                                        }


                                        /* =====================
                                           answers.28.0
                                        ====================== */

                                        const arrayMatch =
                                            key.match(
                                                /^answers\.(\d+)\.(\d+)$/
                                            );


                                        if (
                                            arrayMatch
                                        ) {


                                            const questionId =
                                                arrayMatch[1];


                                            const card =
                                                $('.question-card[data-question-id="' +
                                                questionId +
                                                '"]');


                                            if (
                                                card.length
                                            ) {


                                                showQuestionError(
                                                    card,
                                                    errors[key][0]
                                                );


                                                if (
                                                    !firstError
                                                ) {

                                                    firstError =
                                                        card;

                                                }

                                            }

                                        }


                                    }
                                );


                            if (
                                firstError &&
                                firstError.length
                            ) {

                                $('html, body').animate({

                                    scrollTop:
                                        firstError.offset().top - 80

                                }, 450);

                            }


                            Swal.fire({

                                icon: 'warning',

                                title: 'Please check your form',

                                text:
                                    'Some information is missing or invalid.',

                                confirmButtonColor:
                                    '#415094'

                            });


                            return;

                        }


                        /* =====================================
                           SERVER ERROR
                        ====================================== */

                        let message =
                            'Something went wrong. Please try again.';


                        if (
                            xhr.responseJSON &&
                            xhr.responseJSON.message
                        ) {

                            message =
                                xhr.responseJSON.message;

                        }


                        Swal.fire({

                            icon: 'error',

                            title: 'Submission Failed',

                            text: message,

                            confirmButtonColor:
                                '#415094'

                        });

                    }

            });


        }
    );


    /* =========================================================
       INITIAL PROGRESS
    ========================================================== */

    updateProgress();


});

</script>


</body>

</html>