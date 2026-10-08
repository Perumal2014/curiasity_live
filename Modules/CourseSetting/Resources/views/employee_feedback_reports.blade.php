@extends('backend.master')

@section('table')
    tenant_galleries
@endsection

@section('mainContent')

@include('backend.partials.alertMessage')

<style>
    .text-answer-card {
    display: flex;
    align-items: flex-start;
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 16px 18px;
    margin-bottom: 12px;
    transition: all 0.2s ease;
}

.text-answer-card:hover {
    border-color: #d1d5db;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
}

.text-answer-number {
    width: 32px;
    height: 32px;
    min-width: 32px;
    border-radius: 50%;
    background: #eef4ff;
    color: #4154f1;
    font-size: 13px;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 14px;
}

.text-answer-content {
    display: flex;
    align-items: flex-start;
    flex: 1;
    min-width: 0;
}

.text-answer-icon {
    color: #c7ccd5;
    font-size: 15px;
    margin-right: 10px;
    padding-top: 2px;
}

.text-answer-text {
    color: #444;
    font-size: 14px;
    line-height: 1.7;
    word-break: break-word;
}

.overall-rating-card {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
}

.overall-rating-header {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
}

.overall-rating-icon {
    width: 38px;
    height: 38px;
    min-width: 38px;
    border-radius: 50%;
    background: #fff7e6;
    color: #f5a623;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 12px;
}

.overall-rating-header h5 {
    font-size: 15px;
    font-weight: 600;
    color: #333;
}

.overall-rating-chart {
    height: 300px;
}
</style>

<style>
    .report-loader {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 250px;
        flex-direction: column;
    }

    .report-spinner {
        width: 40px;
        height: 40px;
        border: 4px solid #e5e7eb;
        border-top-color: #4154f1;
        border-radius: 50%;
        animation: reportSpin 0.8s linear infinite;
    }

    @keyframes reportSpin {
        to {
            transform: rotate(360deg);
        }
    }
</style>


<div class="container-fluid">

    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}
    <div class="row">
        <div class="col-lg-12">

            <div class="white_box mb_30">

                <div class="main-title mb-25">
                    <h3 class="mb-0">
                        Feedback Reports
                    </h3>

                    <p class="text-muted mb-0">
                        View employee feedback responses and question-wise reports.
                    </p>
                </div>


                {{-- =====================================================
                     FILTERS
                ====================================================== --}}
                <div class="row">

                    {{-- Feedback Form --}}
                    <div class="col-lg-6 mb-20">

                        <label class="primary_label">
                            Feedback Form
                        </label>

                        <select
                            id="feedback_form_id"
                            class="primary_select"
                        >

                            <option value="">
                                Select Feedback Form
                            </option>

                            @foreach($feedbackforms as $form)

                                <option value="{{ $form->id }}">
                                    {{ $form->form_name }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Question --}}
                    <div class="col-lg-6 mb-20">

                        <label class="primary_label">
                            Question
                        </label>

                        <select
                            id="question_id"
                            class="primary_select"
                            disabled
                        >

                            <option value="">
                                Select Feedback Form First
                            </option>

                        </select>

                    </div>

                </div>

            </div>

        </div>
    </div>


    {{-- =========================================================
         REPORT AREA
    ========================================================== --}}
    <div id="reportLoader" style="display:none;">
        <div class="white_box text-center mb_30">
            <div class="py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>

                <p class="text-muted mt-3 mb-0">
                    Loading report...
                </p>
            </div>
        </div>
    </div>

    <div id="reportArea" style="display:none;">

        {{-- =====================================================
             NORMAL QUESTION REPORT
        ====================================================== --}}
        <div id="normalQuestionReport">

            {{-- Question Title --}}
            <!-- <div class="row">
                <div class="col-lg-12">

                    <div class="white_box mb_30">

                        <div class="main-title mb-0">

                            <h3
                                id="chartQuestionTitle"
                                class="mb-0"
                            ></h3>

                        </div>

                    </div>

                </div>
            </div> -->


            <div class="row">

                <div class="col-lg-6 col-md-6 col-sm-12 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="mb-3">
                                Bar Chart
                            </h5>

                            <div style="height: 350px;">
                                <canvas id="feedbackBarChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-lg-6 col-md-6 col-sm-12 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="mb-3">
                                Pie Chart
                            </h5>

                            <div style="height: 350px;">
                                <canvas id="feedbackPieChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>


        {{-- =====================================================
             TEXT ANSWERS
        ====================================================== --}}
        <div
            id="textQuestionReport"
            style="display:none;"
        >

            <div class="row">

                <div class="col-lg-12">

                    <div class="white_box mb_30">

                        <div class="mb-3">
                            <h5 id="textQuestionTitle" class="mb-1"></h5>

                            <small class="text-muted">
                                Text responses
                            </small>
                        </div>


                        <div
                            id="textAnswersContainer"
                        ></div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
             OVERALL RATING
        ====================================================== --}}
        <div
            id="overallRatingReport"
            style="display:none;"
        >

            <div id="overallRatingContainer"></div>

        </div>

    </div>


    {{-- =========================================================
         EMPTY STATE
    ========================================================== --}}
    <div
        id="emptyReport"
        class="row"
    >

        <div class="col-lg-12">

            <div class="white_box text-center mb_30">

                <div class="py-5">

                    <i
                        class="fas fa-chart-bar"
                        style="
                            font-size:45px;
                            color:#ccc;
                            margin-bottom:15px;
                        "
                    ></i>

                    <h4>
                        Select a feedback question
                    </h4>

                    <p class="text-muted mb-0">
                        Select a feedback form and question to view the report.
                    </p>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- =============================================================
     CHART JS
============================================================= --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<script>

    let feedbackBarChart = null;
    let feedbackPieChart = null;


    /*
    |--------------------------------------------------------------------------
    | Escape HTML
    |--------------------------------------------------------------------------
    */
    function escapeHtml(value) {

        return $('<div>')
            .text(value ?? '')
            .html();
    }


    /*
    |--------------------------------------------------------------------------
    | Refresh Primary Select
    |--------------------------------------------------------------------------
    */
    function refreshQuestionSelect() {

        /*
        |--------------------------------------------------------------------------
        | Nice Select
        |--------------------------------------------------------------------------
        */
        if ($('#question_id').next('.nice-select').length) {

            $('#question_id').niceSelect('update');

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Select2
        |--------------------------------------------------------------------------
        */
        if ($('#question_id').hasClass('select2-hidden-accessible')) {

            $('#question_id').trigger('change');

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Bootstrap Select
        |--------------------------------------------------------------------------
        */
        if ($('#question_id').hasClass('selectpicker')) {

            $('#question_id').selectpicker('refresh');

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Reset Report
    |--------------------------------------------------------------------------
    */
    function resetReport() {

        $('#reportArea').hide();

        $('#normalQuestionReport').hide();

        $('#textQuestionReport').hide();

        $('#overallRatingReport').hide();

        $('#emptyReport').show();


        if (feedbackBarChart) {

            feedbackBarChart.destroy();

            feedbackBarChart = null;

        }


        if (feedbackPieChart) {

            feedbackPieChart.destroy();

            feedbackPieChart = null;

        }


        $('#textAnswersContainer').html('');

        $('#overallRatingContainer').html('');

    }


    /*
    |--------------------------------------------------------------------------
    | Feedback Form Change
    |--------------------------------------------------------------------------
    */
    $('#feedback_form_id').on('change', function() {

        let feedbackFormId = $(this).val();


        resetReport();


        /*
        |--------------------------------------------------------------------------
        | Reset Question Dropdown
        |--------------------------------------------------------------------------
        */
        $('#question_id')
            .html(
                '<option value="">Loading Questions...</option>'
            )
            .prop('disabled', true);


        refreshQuestionSelect();


        if (!feedbackFormId) {

            $('#question_id')
                .html(
                    '<option value="">Select Feedback Form First</option>'
                )
                .prop('disabled', true);

            refreshQuestionSelect();

            return;
        }


        $.ajax({

            url: "{{ route('feedback.reports.questions') }}",

            type: "GET",

            data: {
                feedback_form_id: feedbackFormId
            },

            beforeSend: function() {

                $('#question_id')
                    .html(
                        '<option value="">Loading Questions...</option>'
                    )
                    .prop('disabled', true);

                refreshQuestionSelect();

            },

            success: function(response) {

                if (!response.success) {

                    $('#question_id')
                        .html(
                            '<option value="">No Questions Found</option>'
                        )
                        .prop('disabled', true);

                    refreshQuestionSelect();

                    return;
                }

                if (response.type === 'overall_rating') {

                    showOverallRating(response.reports);

                    return;
                }



                let html =
                    '<option value="">Select Question</option>';


                /*
                |--------------------------------------------------------------------------
                | Check Rating Questions
                |--------------------------------------------------------------------------
                */
                let hasRatingQuestions =
                    response.questions.some(function(question) {

                        return [
                            'rating',
                            'star',
                            'linear_scale'
                        ].includes(
                            String(
                                question.question_type
                            ).toLowerCase()
                        );

                    });


                /*
                |--------------------------------------------------------------------------
                | Overall Rating
                |--------------------------------------------------------------------------
                */
                if (hasRatingQuestions) {

                    html += `
                        <option value="overall_rating">
                            Overall Rating
                        </option>
                    `;

                }


                /*
                |--------------------------------------------------------------------------
                | Add ALL Questions
                |--------------------------------------------------------------------------
                */
                $.each(
                    response.questions,
                    function(index, question) {

                        html += `
                            <option value="${question.id}">
                                ${escapeHtml(question.question_text)}
                            </option>
                        `;

                    }
                );


                $('#question_id')
                    .html(html)
                    .prop('disabled', false);


                /*
                |--------------------------------------------------------------------------
                | IMPORTANT:
                | Refresh Nice Select after dynamically adding options.
                |--------------------------------------------------------------------------
                */
                refreshQuestionSelect();

            },

            error: function(xhr) {

                console.error(
                    xhr.responseText
                );


                $('#question_id')
                    .html(
                        '<option value="">Unable to load questions</option>'
                    )
                    .prop('disabled', true);

                refreshQuestionSelect();

            }

        });

    });


    /*
    |--------------------------------------------------------------------------
    | Question Change
    |--------------------------------------------------------------------------
    */
    $('#question_id').on('change', function() {

        let questionId = $(this).val();

        let feedbackFormId =
            $('#feedback_form_id').val();


        resetReport();


        if (!questionId || !feedbackFormId) {

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Loading
        |--------------------------------------------------------------------------
        */
        $('#emptyReport').hide();

        $('#reportArea').show();


        /*
        |--------------------------------------------------------------------------
        | AJAX Report
        |--------------------------------------------------------------------------
        */
        $.ajax({

            url: "{{ route('feedback.reports.question') }}",

            type: "GET",

            data: {

                feedback_form_id:
                    feedbackFormId,

                question_id:
                    questionId

            },

            beforeSend: function() {

                $('#reportArea').show();

                $('#normalQuestionReport')
                    .hide();

                $('#textQuestionReport')
                    .hide();

                $('#overallRatingReport')
                    .hide();

                 // Show loader
                $('#reportArea').hide();
                $('#reportLoader').show();

            },

            success: function(response) {

                console.log(
                    'Feedback Report:',
                    response
                );

                $('#reportLoader').hide();

                // Show report area
                $('#reportArea').show()


                if (!response.success) {

                    resetReport();

                    return;

                }


                /*
                |--------------------------------------------------------------------------
                | Overall Rating
                |--------------------------------------------------------------------------
                */
                if (
                    response.type ===
                    'overall_rating'
                ) {

                    showOverallRating(
                        response.reports || []
                    );

                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | Text
                |--------------------------------------------------------------------------
                */
                if (
                    response.type ===
                    'text'
                ) {

                    showTextAnswers(
                        response.question,
                        response.answers || []
                    );

                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | Chart
                |--------------------------------------------------------------------------
                */
                if (
                    response.type ===
                    'chart'
                ) {

                    showCharts(

                        response.question,

                        response.labels || [],

                        response.values || []

                    );

                }

            },

            error: function(xhr) {

                console.error(
                    xhr.responseText
                );

                resetReport();

            }

        });

    });


    /*
    |--------------------------------------------------------------------------
    | Show Bar + Pie Charts
    |--------------------------------------------------------------------------
    */
    function showCharts(question, labels, values) {

    $('#normalQuestionReport').show();
    $('#textQuestionReport').hide();
    $('#overallRatingReport').hide();
    $('#emptyReport').hide();

    if (feedbackBarChart) {
        feedbackBarChart.destroy();
    }

    if (feedbackPieChart) {
        feedbackPieChart.destroy();
    }


    /*
    |--------------------------------------------------------------------------
    | BAR CHART
    |--------------------------------------------------------------------------
    */
    const barCanvas = document.getElementById(
        'feedbackBarChart'
    );

    feedbackBarChart = new Chart(
        barCanvas,
        {
            type: 'bar',

            data: {
                labels: labels,

                datasets: [
                    {
                        label: 'Responses',
                        data: values,

                        borderWidth: 1
                    }
                ]
            },

            options: {
                responsive: true,

                maintainAspectRatio: false,

                scales: {
                    y: {
                        beginAtZero: true,

                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        }
    );


    /*
    |--------------------------------------------------------------------------
    | PIE CHART
    |--------------------------------------------------------------------------
    */
    const pieCanvas = document.getElementById(
        'feedbackPieChart'
    );

    feedbackPieChart = new Chart(
        pieCanvas,
        {
            type: 'pie',

            data: {
                labels: labels,

                datasets: [
                    {
                        data: values
                    }
                ]
            },

            options: {
                responsive: true,

                maintainAspectRatio: false
            }
        }
    );
}


    /*
    |--------------------------------------------------------------------------
    | Show Text Answers
    |--------------------------------------------------------------------------
    */
    function showTextAnswers(question, answers) {

        $('#emptyReport').hide();
        $('#normalQuestionReport').hide();
        $('#overallRatingReport').hide();

        $('#textQuestionReport').show();
        $('#reportArea').show();

        $('#textQuestionTitle').text(question);

        let html = '';

        if (!answers.length) {

            html = `
                <div class="text-center py-5">
                    <i class="fas fa-comment-slash"
                        style="font-size:40px;color:#ccc;">
                    </i>

                    <h5 class="mt-3 mb-1">
                        No responses found
                    </h5>

                    <p class="text-muted mb-0">
                        There are no text responses for this question.
                    </p>
                </div>
            `;

        } else {

            $.each(answers, function(index, answer) {

                html += `
                    <div class="text-answer-card">

                        <div class="text-answer-number">
                            ${index + 1}
                        </div>

                        <div class="text-answer-content">

                            <div class="text-answer-icon">
                                <i class="fas fa-quote-left"></i>
                            </div>

                            <div class="text-answer-text">
                                ${escapeHtml(answer)}
                            </div>

                        </div>

                    </div>
                `;

            });
        }

        $('#textAnswersContainer').html(html);
        }


    /*
    |--------------------------------------------------------------------------
    | Show Overall Rating
    |--------------------------------------------------------------------------
    */
    function showOverallRating(reports) {

        $('#normalQuestionReport').hide();
        $('#textQuestionReport').hide();
        $('#overallRatingReport').show();
        $('#emptyReport').hide();

        $('#overallRatingReport').html('');

        if (!reports || reports.length === 0) {

            $('#overallRatingReport').html(
                '<div class="alert alert-info">No questions found.</div>'
            );

            return;
        }


        reports.forEach(function (report, index) {

            /*
            |--------------------------------------------------------------------------
            | TEXT / TEXTAREA QUESTION
            |--------------------------------------------------------------------------
            */
            if (report.question_type === 'text') {

                let answers = report.answers || [];

                let html = `
                    <div class="overall-rating-card">

                        <div class="overall-rating-header">

                            <div class="overall-rating-icon">
                                <i class="fas fa-comment-alt"></i>
                            </div>

                            <div>
                                <h5 class="mb-1">
                                    ${escapeHtml(report.question_text)}
                                </h5>

                                <small class="text-muted">
                                    Text responses
                                </small>
                            </div>

                        </div>

                        <div>
                `;


                if (!answers.length) {

                    html += `
                        <div class="text-center py-4">

                            <i class="fas fa-comment-slash"
                            style="
                                    font-size:35px;
                                    color:#ccc;
                            ">
                            </i>

                            <h6 class="mt-3 mb-1">
                                No responses found
                            </h6>

                            <p class="text-muted mb-0">
                                There are no text responses for this question.
                            </p>

                        </div>
                    `;

                } else {

                    $.each(answers, function(answerIndex, answer) {

                        html += `
                            <div class="text-answer-card">

                                <div class="text-answer-number">
                                    ${answerIndex + 1}
                                </div>

                                <div class="text-answer-content">

                                    <div class="text-answer-icon">
                                        <i class="fas fa-quote-left"></i>
                                    </div>

                                    <div class="text-answer-text">
                                        ${escapeHtml(answer)}
                                    </div>

                                </div>

                            </div>
                        `;

                    });

                }


                html += `
                        </div>
                    </div>
                `;


                $('#overallRatingReport').append(html);

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | RATING QUESTIONS
            |--------------------------------------------------------------------------
            */
            const chartId = 'overallRatingChart_' + index;

            $('#overallRatingReport').append(`

                <div class="overall-rating-card">

                    <div class="overall-rating-header">

                        <div class="overall-rating-icon">
                            <i class="fas fa-star"></i>
                        </div>

                        <div>

                            <h5 class="mb-1">
                                ${escapeHtml(report.question_text)}
                            </h5>

                            <small class="text-muted">
                                Overall rating responses
                            </small>

                        </div>

                    </div>

                    <div class="overall-rating-chart">
                        <canvas id="${chartId}"></canvas>
                    </div>

                </div>

            `);


            new Chart(
                document.getElementById(chartId),
                {
                    type: 'bar',

                    data: {
                        labels: report.labels || [],

                        datasets: [
                            {
                                label: 'Responses',
                                data: report.values || [],
                                borderWidth: 1
                            }
                        ]
                    },

                    options: {
                        responsive: true,

                        maintainAspectRatio: false,

                        scales: {
                            y: {
                                beginAtZero: true,

                                ticks: {
                                    precision: 0
                                }
                            }
                        }
                    }
                }
            );

        });
    }

</script>

@endsection
