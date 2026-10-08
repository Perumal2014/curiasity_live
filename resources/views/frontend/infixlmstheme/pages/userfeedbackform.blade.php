@extends(theme('layouts.dashboard_master'))

@section('title')
    {{ Settings('site_title') ? Settings('site_title') : 'Infix LMS' }} |
    Feedback Form
@endsection

@section('css')

<style>

    .feedback_wrapper{
        max-width:1100px;
        margin:auto;
    }

    .feedback_card{
        background:#fff;
        border-radius:12px;
        padding:30px;
        border:1px solid #e8ebf3;
        box-shadow:0 2px 12px rgba(0,0,0,0.05);
    }

    .feedback_header{
        margin-bottom:30px;
    }

    .feedback_header h3{
        font-size:28px;
        font-weight:700;
        margin-bottom:10px;
        color:#222;
    }

    .feedback_header p{
        color:#666;
        font-size:14px;
    }

    .question_card{
    background:#f8f9fc;
    border:1px solid #e8ebf2;
    border-radius:10px;
    padding:25px;
    margin-bottom:20px;
    }

    .question_top{
        width:100%;
    }

    .question_number{
        font-size:13px;
        font-weight:700;
        color:#444;
        margin-bottom:10px;
    }

    .question_title{
        font-size:15px;
        line-height:25px;
        color:#222;
        margin-bottom:25px;
    }

    .rating_scale{
        display:flex;
        align-items:center;
        gap:10px;
        flex-wrap:wrap;
    }

    .rating_label{
        font-size:13px;
        color:#666;
    }

    .rating_btn input{
        display:none;
    }

    .rating_btn span{
        width:42px;
        height:42px;
        border:1px solid #d8dce5;
        border-radius:6px;
        display:flex;
        align-items:center;
        justify-content:center;
        cursor:pointer;
        background:#fff;
        transition:0.3s;
        font-size:14px;
    }

    .rating_btn input:checked + span{
        background:#415094;
        color:#fff;
        border-color:#415094;
    }

    .likert_options{
        display:flex;
        gap:30px;
        flex-wrap:wrap;
    }

    .likert_item{
        text-align:center;
    }

    .likert_item input{
        margin-bottom:10px;
        cursor:pointer;
    }

    .likert_item span{
        display:block;
        font-size:13px;
        color:#555;
    }

    .loader{
        text-align:center;
        padding:60px 0;
        display:none;
    }

    .loader i{
        font-size:35px;
        color:#415094;
    }

    .submit_btn{
        margin-top:30px;
        text-align:right;
    }

</style>

@endsection

@section('mainContent')

<div class="main_content_iner main_content_padding">

    <div class="dashboard_lg_card">

        <div class="container-fluid">

            <div class="feedback_wrapper">

                <div class="feedback_card">

                    <!-- Header -->
                    <div class="feedback_header">

                        <h3>

                            Course Feedback Survey

                        </h3>

                        <p>

                            Gather feedback on instructor quality, content, and session engagement.

                        </p>

                    </div>

                    <!-- Loader -->
                    <div class="loader">

                        <i class="fas fa-spinner fa-spin"></i>

                    </div>

                    <!-- Form -->
                    <form id="feedbackForm">

                        @csrf

                        <input type="hidden"
                               name="feedback_id"
                               value="{{ $feedbackallocations->id }}">

                        <!-- Questions Load Here -->
                        <div id="questionContainer"></div>

                        <!-- Submit -->
                        <div class="submit_btn">

                            <button type="submit"
                                    class="theme_btn">

                                Submit Feedback

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection

@section('js')

<script>

    "use strict";

    $(document).ready(function(){

        loadQuestions();

    });

    /*
    |--------------------------------------------------------------------------
    | Load Questions
    |--------------------------------------------------------------------------
    */

    function loadQuestions()
    {

        $('.loader').show();

        $.ajax({

            url: "{{ route('feedback.questions.ajax', $feedbackallocations->id) }}",

            type: "GET",

            success:function(response)
            {

                $('.loader').hide();

                let html = '';

                $.each(response.questions, function(index, question){

                    html += `

                        <div class="question_card">

                            <div class="question_top">

                                <div style="display:flex; gap:15px; width:100%;">

                                    <div style="color:#888;">
                                        <i class="fas fa-grip-vertical"></i>
                                    </div>

                                    <div style="width:100%;">

                                        <div class="question_number">

                                            Question ${index + 1}

                                        </div>

                                        <div class="question_title">

                                            ${question.question}

                                        </div>

                    `;

                    /*
                    |--------------------------------------------------------------------------
                    | Rating Question
                    |--------------------------------------------------------------------------
                    */

                    if(question.type == 'rating')
                    {

                        html += `

                            <div class="rating_scale">

                                <span class="rating_label">

                                    Not at all likely

                                </span>

                        `;

                        for(let i = 1; i <= 10; i++)
                        {

                            html += `

                                <label class="rating_btn">

                                    <input type="radio"
                                        name="answers[${question.id}]"
                                        value="${i}">

                                    <span>${i}</span>

                                </label>

                            `;
                        }

                        html += `

                                <span class="rating_label">

                                    Extremely likely

                                </span>

                            </div>

                        `;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Likert Questions
                    |--------------------------------------------------------------------------
                    */

                    if(question.type == 'likert')
                    {

                        let options = [

                            'Strongly Disagree',
                            'Disagree',
                            'Neutral',
                            'Agree',
                            'Strongly Agree'

                        ];

                        html += `

                            <div class="likert_options">

                        `;

                        $.each(options, function(key, option){

                            html += `

                                <label class="likert_item">

                                    <input type="radio"
                                        name="answers[${question.id}]"
                                        value="${option}">

                                    <span>

                                        ${option}

                                    </span>

                                </label>

                            `;
                        });

                        html += `

                            </div>

                        `;
                    }

                    html += `

                                    </div>

                                </div>

                            </div>

                        </div>

                    `;

                });

                $('#questionContainer').html(html);

            }

        });

    }

    /*
    |--------------------------------------------------------------------------
    | Submit Feedback
    |--------------------------------------------------------------------------
    */

    $('#feedbackForm').submit(function(e){

        e.preventDefault();

        $.ajax({

            url: "{{ route('feedback.submit', $feedbackallocations->id) }}",

            type: "POST",

            data: $(this).serialize(),

            success:function(response)
            {

                toastr.success(response.message);

                setTimeout(function(){

                    location.reload();

                },1000);

            },

            error:function(xhr)
            {

                toastr.error('Something went wrong');

            }

        });

    });

</script>

@endsection