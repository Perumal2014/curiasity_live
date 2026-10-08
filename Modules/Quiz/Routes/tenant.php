<?php

use Illuminate\Support\Facades\Route;

Route::prefix('{tenant_slug}')
    ->middleware(['tenant.slug'])
    ->group(function () {

        Route::prefix('quiz')
            ->middleware(['auth'])
            ->group(function () {

                Route::get('question-bank/{id}', 'QuestionBankController@show')->name('question-bank-edit');
                Route::get('course-question-bank/{id}', 'QuestionBankController@CourseQuetionShow')
                    ->name('course-question-bank-edit');

                Route::put('question-bank/{id}', 'QuestionBankController@update')->name('question-bank-update');
                // AJAX for DataTable
                Route::get('all/quiz-data', 'QuestionBankController@getAllQuizData')
                    ->name('tenant.getAllQuizData');

                // Question bank list
                Route::get('question-bank-list', 'QuestionBankController@index')
                    ->name('question-bank-list');

                Route::get('quiz-setup', 'OnlineQuizController@quizSetup')->name('quizSetup')->middleware('RoutePermissionCheck:quizSetup');
                Route::POST('quiz-setup', 'OnlineQuizController@SaveQuizSetup')->name('quizSetup.store')->middleware('RoutePermissionCheck:quiz-setup.store');

                Route::get('question-bank-bulk', 'QuestionBankController@questionBulkImport')->name('question-bank-bulk')->middleware('RoutePermissionCheck:question-bank-bulk');
                Route::post('question-bank-bulk', 'QuestionBankController@questionBulkImportSubmit')->name('question-bank-bulk-submit')->middleware('RoutePermissionCheck:question-bank-bulk');

                Route::get('quiz-enrolled-student/{id}', 'OnlineQuizController@enrolledStudent')->name('set-quiz.enrolled-student')->middleware('RoutePermissionCheck:set-quiz.enrolled-student');
                Route::get('quiz-enrolled-marking/{quiz_test_id}', 'OnlineQuizController@markingScript')->name('set-quiz.mark-register')->middleware('RoutePermissionCheck:set-quiz.mark-register');
                Route::post('quiz-enrolled-marking', 'OnlineQuizController@quizMarkingStore')->name('quizMarkingStore');

                Route::get('download-sample', 'QuestionBankController@downloadSample')->name('download-sample');

                
                Route::get('quiz-result', 'OnlineQuizController@quizResult')->name('quizResult')->middleware('RoutePermissionCheck:quizResult');
                Route::get('quiz-result-data', 'OnlineQuizController@quizResultData')->name('quizResultData')->middleware('RoutePermissionCheck:quizResult');
                Route::get('quiz-result-export', 'OnlineQuizController@quizResultExport')->name('quizResultExport')->middleware('RoutePermissionCheck:quizResult');
                Route::POST('quiz-result', 'OnlineQuizController@getQuizResult')->middleware('RoutePermissionCheck:quizResult');

                Route::post('online-exam-question-assign', ['as' => 'online_exam_question_assign', 'uses' => 'OnlineQuizController@onlineExamQuestionAssign']);
                Route::post('online-exam-question-assign-by-ajax', ['as' => 'online_exam_question_assign_by_ajax', 'uses' => 'OnlineQuizController@onlineExamQuestionAssignByAjax']);
                Route::post('online-exam-question-delete', 'OnlineQuizController@onlineExamQuestionDelete')->name('online-exam-question-delete');
                
                // Question group
                Route::get('set-quiz', 'OnlineQuizController@index')->name('online-quiz')->middleware('RoutePermissionCheck:online-quiz');
                Route::post('online-exam', 'OnlineQuizController@store')->name('online-exam')->middleware('RoutePermissionCheck:set-quiz.store');
                Route::get('online-exam/{id}', 'OnlineQuizController@edit')->name('online-exam-edit')->middleware('RoutePermissionCheck:set-quiz.edit');
                Route::put('online-exam/{id}', 'OnlineQuizController@update')->name('online-exam-update')->middleware('RoutePermissionCheck:set-quiz.edit');
                Route::post('online-exam-delete', 'OnlineQuizController@delete')->name('online-exam-delete')->middleware('RoutePermissionCheck:set-quiz.delete');

                Route::get('manage-online-exam-question/{id}', ['as' => 'set-quiz.set-question', 'uses' => 'OnlineQuizController@manageOnlineExamQuestion'])->middleware('RoutePermissionCheck:set-quiz.manage-question');
                Route::post('online_exam_question_store', ['as' => 'online_exam_question_store', 'uses' => 'OnlineQuizController@manageOnlineExamQuestionStore'])->middleware('RoutePermissionCheck:set-quiz.set-question');

                Route::get('getTotalQuizNumbers', 'OnlineQuizController@getTotalQuizNumbers')->name('getTotalQuizNumbers');
                Route::get('quiz-re-test/{id}', 'OnlineQuizController@quizReTest')->name('quizReTest')->middleware('RoutePermissionCheck:quizReTest');
                
                
                Route::get('question-group', 'QuizController@index')->name('question-group')->middleware('RoutePermissionCheck:question-group');
                Route::post('question-group', 'QuizController@store')->name('question-group.store')->middleware('RoutePermissionCheck:question-group.store');
                Route::get('question-group/{id}', 'QuizController@show')->name('question-group.edit')->middleware('RoutePermissionCheck:question-group.edit');
                Route::put('question-group/{id}', 'QuizController@update')->name('question-group-update')->middleware('RoutePermissionCheck:question-group.edit');
                Route::delete('question-group/{id}', 'QuizController@destroy')->name('question-group-delete')->middleware('RoutePermissionCheck:question-group.delete');

                // Question bank
                Route::get('question-bank', 'QuestionBankController@form')->name('question-bank')->middleware('RoutePermissionCheck:question-bank');
                Route::post('question-bank', 'QuestionBankController@store')->name('question-bank.store')->middleware('RoutePermissionCheck:question-bank.store');
                
               
                
                

                Route::post('question-bank-delete', 'QuestionBankController@destroy')->name('question-bank-delete')->middleware('RoutePermissionCheck:question-bank.delete');
                Route::post('question-bank-bulk-delete', 'QuestionBankController@bulkDestroy')->name('question-bank-bulk-delete')->middleware('RoutePermissionCheck:question-bank.delete');

                // Course question bank
                

                Route::post('course-question-bank', 'QuestionBankController@storeCourse')->name('question-bank.course');
                Route::put('course-question-bank-update/{id}', 'QuestionBankController@updateCourse')->name('question-bank-update.course');
            });
    });