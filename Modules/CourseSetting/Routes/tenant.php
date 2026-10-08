<?php

use Illuminate\Support\Facades\Route;
use Modules\CourseSetting\Http\Controllers\CourseSettingController;

Route::prefix('{tenant_slug}')
    ->where(['tenant_slug' => '[a-z0-9\-]+'])
    ->middleware(['tenant.slug', 'auth'])
    ->group(function () {

        Route::prefix('admin/course')->group(function () {
            // Route::get('allCourse', 'CourseSettingController@getAllCourse');

            Route::get('allCategory', 'CourseSettingController@allCategory');

            Route::get('getSubcat/{id}', 'CourseSettingController@getSubcat');

            Route::get('lesson-files/{id}', 'CourseSettingController@lessonFlies')
                ->name('lesson.files');

            Route::get('lesson-file-restore/{id}', 'InstructorCourseSettingController@restore')
                ->name('lesson.file-restore');

            Route::delete('lesson-file-delete', 'InstructorCourseSettingController@fileDelete')
                ->name('lesson.file-delete');
        });
    });

    Route::group(['prefix' => 'admin/course', 'middleware' => ['auth', 'admin']], function () {
        

    });

//Route For Admin

Route::prefix('{tenant_slug}')
    ->middleware(['tenant.slug', 'auth'])
    ->where(['tenant_slug' => '[a-z0-9\-]+'])
    ->group(function () {

        Route::prefix('admin/course')
            ->middleware(['admin'])
            ->group(function () {
                Route::get('search-users', 'CourseInvitationController@search_users_data')->name('searchUsers');
                Route::get('search-single-users', 'CourseInvitationController@search_single_users')->name('searchSingleUsers');
                Route::post('/bulk-enroll-store', 'CourseInvitationController@bulkEnrollStore')->name('bulk.enroll.store');
                Route::post('/saveCourse', 'CourseSettingController@saveCourse')->name('AdminSaveCourse')->middleware('RoutePermissionCheck:course.store');
            Route::post('/savePlans', 'CourseSettingController@savePlans')->name('savePlans');        
            Route::get('/course-modal/{course_id}/{type}', 'CourseSettingController@courseModal')->name('courseModal');
            Route::any('/change-chapter-position', 'CourseSettingController@changeChapterPosition')->name('changeChapterPosition');
            Route::any('/change-lesson-position', 'CourseSettingController@changeLessonPosition')->name('changeLessonPosition');
            Route::any('/change-lesson-chapter', 'CourseSettingController@changeLessonChapter')->name('changeLessonChapter');

            

            //Get Course Subcategory
            Route::get('/ajaxGetCourseSubCategory', 'CourseSettingController@ajaxGetCourseSubCategory');
            Route::get('/ajaxGetCourseByLevel', 'CourseSettingController@ajaxGetCourseByLevel');

            //Manage Category
            Route::get('/messages', 'CourseSettingController@toastrMessages')->name('toastrMessages');

            Route::get('bulk_enroll_upload/{course_id}', 'CourseInvitationController@bulkEnrollUpload')->name('bulk_enroll_upload');
            Route::post('bulk_enroll_upload/{course_id}', 'CourseInvitationController@bulkEnrollImport')->name('bulk_enroll_import');
            Route::get('/searchCategory', 'CourseSettingController@searchCategory')->name('searchCategory');
            Route::get('/searchCourse', 'CourseSettingController@searchCourse')->name('searchCourse');
            Route::post('/saveCategory', 'CourseSettingController@saveCategory')->name('saveCategory');
            Route::get('/categoryEdit/{id}', 'CourseSettingController@categoryEdit')->name('categoryEdit');


            Route::post('/updateCategory', 'CourseSettingController@updateCategory')->name('updateCategory');
            Route::get('/categoryStatus/{id}', 'CourseSettingController@categoryStatus')->name('categoryStatus');

            //Manage Subcategory
            Route::get('/editSubCategory/{id}', 'CourseSettingController@editSubCategory')->name('editSubCategory');
            Route::post('/updateSubCategory', 'CourseSettingController@updateSubCategory')->name('updateSubCategory');
            Route::post('/disableSubCategory', 'CourseSettingController@disableSubCategory')->name('disableSubCategory');

            Route::get('/enrollment-report', 'CourseSettingController@enrollmentReport')->name('course.enrollment.report');
            Route::get('/enrollment-export', 'CourseSettingController@exportEnrollmentReport')->name('course.enrollment.export');

            Route::post('/group-course-enroll', 'CourseSettingController@groupCourseEnroll')->name('group.course.enroll');

            //Course Invitation
            //    Route::get('/course-invitation/{id}', 'CourseInvitationController@courseInvitation')->name('course.courseInvitation')->middleware('RoutePermissionCheck:course.courseInvitation');
            Route::get('/course-statistics', 'CourseInvitationController@courseStatistics')->name('course.courseStatistics')->middleware('RoutePermissionCheck:course.courseStatistics');
            Route::get('/course-statistics-course-report', 'CourseInvitationController@courseStatisticsCourseReport')->name('course.courseStatisticsCourseReport')->middleware('RoutePermissionCheck:course.courseStatistics');
            Route::get('/course-statistics-quiz-report', 'CourseInvitationController@courseStatisticsQuizReport')->name('course.courseStatisticsQuizReport')->middleware('RoutePermissionCheck:course.courseStatistics');

            Route::get('/course-statistics-course-data', 'CourseInvitationController@courseStatisticsCourseData')->name('course.courseStatisticsCourseData')->middleware('RoutePermissionCheck:course.courseStatistics');
            Route::get('/course-statistics-quiz-data', 'CourseInvitationController@courseStatisticsQuizData')->name('course.courseStatisticsQuizData')->middleware('RoutePermissionCheck:course.courseStatistics');
            Route::get('/course-statistics-class-data', 'CourseInvitationController@courseStatisticsClassData')->name('course.courseStatisticsClassData')->middleware('RoutePermissionCheck:course.courseStatistics');


            Route::get('/course-students/{course_id}', 'CourseInvitationController@enrolled_students')->name('course.enrolled_students');
            
            Route::get('/course-students-list/{course_id}', 'CourseInvitationController@getAllStudentData')->name('course.getAllStudentData');

            Route::get('/feedback-answer-list/{form_id}', 'CourseInvitationController@getAllAnsweredData')->name('feedback.getAllAnsweredData');

            Route::get('/feedback-answer-list/{form_id}', 'CourseInvitationController@getAllAnsweredData')->name('feedback.getAllAnsweredData');
            
            Route::get('/feedback/preview/{id}', 'CourseInvitationController@questionPreview')->name('feedback.preview');
            
            Route::get('/add-feedback-questions/{form_id}', 'CourseInvitationController@addFeedbackQuestions')->name('feedback.add.questions');
            
            Route::get('/feedback-submission/answers/{id}','CourseInvitationController@getSubmissionAnswers')->name('feedback.submission.answers');

            Route::get('/feedback/overall-rating/{form_id}','CourseInvitationController@getOverallRating')->name('feedback.overall_rating');

            Route::get('/feedback/download-excel/{id}', 'CourseInvitationController@downloadExcel')->name('feedback.download.excel');

            Route::get('/feedback-answered-list/{form_id}', 'CourseInvitationController@answered_users')->name('feedback.answered_users');
           
            Route::get('/course-enroll-users/{course_id}', 'CourseInvitationController@enrolled_users')->name('course.enrolled_users');
            Route::get('/course-users-list/{course_id}', 'CourseInvitationController@getAllData')->name('course.getAllData');
            Route::post('/bulk-enroll', 'CourseInvitationController@bulkEnroll')->name('bulkEnroll');
            Route::post('/bulk-quiz-enroll', 'CourseInvitationController@bulkQuizEnroll')->name('bulkquizEnroll');
            Route::get('/search-email-users', 'CourseInvitationController@searchEmailUsers')->name('searchEmailUsers');
            Route::post('/check-course-seats', 'CourseInvitationController@checkSeats')->name('checkCourseSeats');
            Route::post('/check-seats-based-course', 'CourseInvitationController@checkSeatsBasedOnCourse')->name('checkSeatsBasedOnCourse');
            Route::get('/course-student-notify/{course_id}/{student_id}', 'CourseInvitationController@courseStudentNotify')->name('course.courseStudentNotify')->middleware('RoutePermissionCheck:course.courseStudentNotify');

            
            Route::get('/course-details/{id}', 'CourseSettingController@courseDetails')->name('courseDetails')->middleware('RoutePermissionCheck:course.edit');
            Route::get('/course-user-details', 'CourseSettingController@courseUserDetails')->name('course.user_enroll_list')->middleware('RoutePermissionCheck:course.user_enroll_list');
            Route::get('/course-feature/{id}/{type}', 'CourseSettingController@courseMakeAsFeature')->name('courseMakeAsFeature');
            Route::get('/course-lesson-show/{course_id}/{chapter_id}/{lesson_id}', 'CourseSettingController@CourseLessonShow')->name('CourseQuetionShow');
            Route::get('/course-question-show/{question_id}/{course_id}/{chapter_id}/{lesson_id}', 'CourseSettingController@CourseQuetionShow')->name('CourseQuetionShow');
            Route::get('/course-chapter-show/{course_id}/{chapter_id}', 'CourseSettingController@CourseChapterShow')->name('CourseChapterShow');

            Route::get('/course-question-delete/{quiz_id}/{question_id}', 'CourseSettingController@CourseQuestionDelete')->name('CourseQuestionDelete');


            Route::post('/setCourseDripContent', 'CourseSettingController@setCourseDripContent')->name('setCourseDripContent');
            // Route::get('/course-test/{id}', 'CourseSettingController@courseDetails2')->name('courseDetails2');
            Route::get('/learning-path/private', 'CourseSettingController@private')->name('learning-path.private');
            Route::get('/learning-path/recommended', 'CourseSettingController@recommended')->name('learning-path.recommended');

            //Manage course
            Route::get('/all/courses', 'CourseSettingController@getAllCourse')->name('getAllCourse')->middleware('RoutePermissionCheck:getAllCourse');

            Route::get('/all/catalogs', 'CourseSettingController@getAllCatalogs')->name('getAllCatalogs')->middleware('RoutePermissionCheck:getAllCatalogs');

            Route::get('/learning_plans', 'CourseSettingController@getAllLearningPlans')->name('learning_plans')->middleware('RoutePermissionCheck:learning_plans');

            Route::get('/editLearningPlan/{plan_id}', 'CourseSettingController@editLearningPlan')->name('editLearningPlan');
            

            Route::get('/learning_paths', 'CourseSettingController@getAllLearningPath')->name('learning_paths')->middleware('RoutePermissionCheck:learning_paths');

            Route::get('/learning_groups', 'CourseSettingController@getAllLearningroups')->name('learning_groups')->middleware('RoutePermissionCheck:learning_groups');
        
            Route::post('/learning_groups', 'CourseSettingController@groupStore')->name('learning_groups')->middleware('RoutePermissionCheck:learning_groups');
            
            Route::get('/new/learningplan', 'CourseSettingController@addNewLearningPlan')->name('learningplan.store')->middleware('RoutePermissionCheck:learningplan.store');

            Route::get('/new/employee-feedback-form', 'CourseSettingController@employeeFeedbackForm')->name('employeefeedbackform.store')->middleware('RoutePermissionCheck:employeefeedbackform.store');

            Route::post('/new/employee-feedback-form', 'CourseSettingController@employeeFeedbackFormStore')->name('employeefeedbackform.store')->middleware('RoutePermissionCheck:employeefeedbackform.store');


            Route::get('/feedbackform-reports', 'CourseSettingController@employeeFeedbackFormReports')->name('empfeedbackreports.index')->middleware('RoutePermissionCheck:empfeedbackreports.index');

            Route::get('/feedback-reports', 'CourseSettingController@getFeedbackFormData')->name('feedback.reports')->middleware('RoutePermissionCheck:empfeedbackreports.index');

            Route::get('/feedback-reports/questions', 'CourseSettingController@getFeedbackReportQuestions')->name('feedback.reports')->middleware('RoutePermissionCheck:empfeedbackreports.index');

            Route::get('/feedback-reports/report', 'CourseSettingController@getFeedbackReportQuestions')->name('feedback.reports.questions')->middleware('RoutePermissionCheck:empfeedbackreports.index');

            Route::get('/feedback-reports/reports', 'CourseSettingController@getFeedbackQuestionReport')->name('feedback.reports.question')->middleware('RoutePermissionCheck:empfeedbackreports.index');

            // Route::get('new/employee-feedback-form')
            Route::get('/new/assign-questions/{id}','CourseSettingController@assignQuestions')->name('assign.questions');

            
            Route::post('/new/store-assigned-questions', 'CourseSettingController@storeAssignedQuestions')->name('assign.questions.store')->middleware('RoutePermissionCheck:employeefeedbackform.store');
            
            Route::get('/new/course-feedback-form', 'CourseSettingController@courseFeedbackForm')->name('coursefeedbackform.store')->middleware('RoutePermissionCheck:coursefeedbackform.store');
            
            Route::get('/Answered-users/{id}', 'CourseSettingController@answeredUsers')->name('feedback.answeredusers')->middleware('RoutePermissionCheck:coursefeedbackform.store');
            
            Route::post('/update/course-feedback-form', 'CourseSettingController@courseFeedbackFormUpdate')->name('coursefeedbackform.update')->middleware('RoutePermissionCheck:coursefeedbackform.update');

            Route::get('/new/feedback-questions', 'CourseSettingController@feedbackQuestions')->name('feedbackquestions.store')->middleware('RoutePermissionCheck:feedbackquestions.store');
            // 
            Route::post('/new/feedback-questions', 'CourseSettingController@feedbackQuestionsStore')->name('feedbackquestions.store');

            Route::get('/edit/feedback-questions/{id}', 'CourseSettingController@feedbackQuestionsEdit')->name('feedback_questions.edit')->middleware('RoutePermissionCheck:feedbackquestions.store');

            Route::post('/update/feedback-questions', 'CourseSettingController@feedbackQuestionsUpdate')->name('feedbackquestions.update');

            Route::post('/delete/feedback-questions/{id}', 'CourseSettingController@feedbackQuestionsDelete')->name('feedback_questions.destroy')->middleware('RoutePermissionCheck:feedbackquestions.store');

            Route::get('/new/learningpath', 'CourseSettingController@addNewLearningPath')->name('learningpath.store')->middleware('RoutePermissionCheck:learningpath.store');

            Route::get('/new/learningpathcourse/{id}', 'CourseSettingController@addLearningPathCourse')->name('addLearningPathCourse');
        
            Route::post('/new/learningpathstore/{id}', 'CourseSettingController@learningPathstore')->name('learningPathstore');

            Route::post('/AdminLearningPathStore', 'CourseSettingController@AdminLearningPathStore')->name('AdminLearningPathStore');

            Route::get('/new/course', 'CourseSettingController@addNewCourse')->name('course.store')->middleware('RoutePermissionCheck:course.store');

            Route::get('/new/learner', 'CourseSettingController@addNewLearner')->name('learner.store')->middleware('RoutePermissionCheck:learner.store');

            Route::get('/new/learner/edit/{id}', 'CourseSettingController@learnerEdit')->name('staff.learner.edit');

            Route::post('/AdminSaveLearner', 'CourseSettingController@AdminSaveLearner')->name('AdminSaveLearner')->middleware('RoutePermissionCheck:learner.store');
            //    Route::get('/edit/course/{id}', 'CourseSettingController@editCourse')->name('addNewCourse')->middleware('RoutePermissionCheck:addNewCourse');
            Route::get('/external-trainers', 'CourseSettingController@externalTrainers')->name('externaltrainers.store')->middleware('RoutePermissionCheck:externaltrainers.store');

            Route::get('/external-trainers-data', 'CourseSettingController@getExternalTrainersData')->name('getExternalTrainersData');

            Route::post('/external-trainers/store', 'CourseSettingController@storeExternalTrainers')->name('storeExternalTrainers');
            Route::get('/active/courses', 'CourseSettingController@getAllCourse')->name('getActiveCourse')->middleware('RoutePermissionCheck:getAllCourse');
            Route::get('/pending/courses', 'CourseSettingController@getAllCourse')->name('getPendingCourse')->middleware('RoutePermissionCheck:getAllCourse');

            
            Route::get('/editCourse/{id}', 'CourseSettingController@editCourse')->name('editCourse')->middleware('RoutePermissionCheck:course.edit');
            Route::post('/updateCourse', 'CourseSettingController@AdminUpdateCourse')->name('AdminUpdateCourse')->middleware('RoutePermissionCheck:course.edit');
            Route::post('/updatecourse-certificate', 'CourseSettingController@AdminUpdateCourseCertificate')->name('AdminUpdateCourseCertificate')->middleware('RoutePermissionCheck:course.edit');
            Route::post('/unpublishCourse', 'CourseSettingController@unpublishCourse')->name('AdminUnpublishCourse');
            Route::get('/publishCourse/{id}', 'CourseSettingController@publishCourse')->name('publishCourse');
            Route::post('/courseStatus', 'CourseSettingController@courseStatus')->name('AdminCourseStatus')->middleware('RoutePermissionCheck:course.status_update');


            Route::get('/getEnroll/{id}', 'CourseSettingController@getEnroll')->name('getEnroll');
            Route::post('/rejectEnroll', 'CourseSettingController@rejectEnroll')->name('rejectEnroll');
            Route::post('/enableEnroll', 'CourseSettingController@enableEnroll')->name('enableEnroll');
            Route::post('/submitEnroll/{id}', 'CourseSettingController@submitEnroll')->name('submitEnroll');
        
            
            //    Route::post('/course-sort-by', 'CourseSettingController@getAllCourse')->name('courseSortBy');
            //    Route::get('/course-sort-by', 'CourseSettingController@getAllCourse')->name('courseSortByGet');
            Route::get('/courseSortByCat/{id}', 'CourseSettingController@courseSortByCat')->name('courseSortByCat');
            Route::get('/courseSort/{value}', 'CourseSettingController@courseSort')->name('courseSort');
            Route::get('/courseSortByInstructor/{value}', 'CourseSettingController@courseSortByInstructor')->name('courseSortByInstructor');
            Route::get('/course-delete/{id}', 'CourseSettingController@courseDelete')->name('course.delete');


            Route::get('chapter', 'ChapterController@index')->name('chapterPage');
            Route::POST('chapter', 'ChapterController@store')->name('saveChapterPage');
            Route::POST('chapter-search', 'ChapterController@chapterSearchByCourse')->name('chapterSearchByCourse');
            Route::get('chapter/{id}', 'ChapterController@chapterEdit')->name('chapterEdit');
            Route::PUT('chapter-update', 'ChapterController@chapterUpdate')->name('chapterUpdate');

            Route::get('lesson/{id}', 'LessonController@index')->name('lessonPage');
            Route::post('/addLesson', 'LessonController@addLesson')->name('addLesson');
            Route::get('/edit-lesson/{id}', 'LessonController@editLesson')->name('editLesson');
            Route::put('/updateLesson', 'LessonController@updateLesson')->name('updateLesson');
            Route::post('/deleteLesson', 'LessonController@deleteLesson')->name('deleteLesson');
            Route::post('/deleteLessonAssignment', 'LessonController@deleteLessonAssignment')->name('deleteLessonAssignment');

            Route::post('/addAssignment', 'CourseAssignmentController@AssignmentStore')->name('course_assignment_store');
            Route::get('/course-assignment-show/{course_id}/{chapter_id}/{lesson_id}', 'CourseAssignmentController@CourseAssignmentShow')->name('course_assignment_show');
            Route::post('/updateAssignment', 'CourseAssignmentController@AssignmentUpdate')->name('course_assignment_update');


            Route::post('/add-chapter', 'InstructorCourseSettingController@saveChapter')->name('saveChapter');
            Route::post('/saveFile', 'InstructorCourseSettingController@saveFile')->name('saveFile');
            Route::get('/download-file/{id}', 'InstructorCourseSettingController@download_course_file')->name('download_course_file');
            Route::get('/edit-chapter/{id}/{course}', 'InstructorCourseSettingController@editChapter')->name('editChapter');
            Route::get('/delete-chapter/{id}/{course}', 'InstructorCourseSettingController@deleteChapter')->name('deleteChapter');
            Route::put('/update-chapter', 'InstructorCourseSettingController@updateChapter')->name('updateChapter');
            Route::get('/updateFile/{id}', 'InstructorCourseSettingController@updateFileAjax')->name('updateFileAjax');
            Route::POST('/updateFile', 'InstructorCourseSettingController@updateFile')->name('updateFile');
            Route::get('/course_chapters/{id}', 'InstructorCourseSettingController@course_chapters')->name('course_chapters');
            Route::post('/deleteFile2', 'InstructorCourseSettingController@deleteFile')->name('deleteFile');


            Route::resource('course-level', 'CourseLevelController')->middleware('RoutePermissionCheck:course-level.index')->except('destroy');
            Route::get('course-level-delete/{id}', 'CourseLevelController@delete')->middleware('RoutePermissionCheck:course-level.destroy')->name('course-level.destroy');


            Route::get('/all/courses-data', 'CourseSettingController@getAllCourseData')->name('getAllCourseData')->middleware('RoutePermissionCheck:getAllCourse');

            Route::get('/all/catalog-data', 'CourseSettingController@getAllCatalogData')->name('getAllCatalogData')->middleware('RoutePermissionCheck:getAllCatalogs');

            Route::get('/all/learning-plan-data', 'CourseSettingController@getAllLearningPlanData')->name('getAllLearningPlanData');
            Route::get('/all/learning-path-data', 'CourseSettingController@getAllLearningPathData')->name('getAllLearningPathData');

            Route::get('/vdocipher/video-list', 'VdocipherController@getAllVdocipherData')->name('getAllVdocipherData');
            Route::get('/vdocipher/video/{id}', 'VdocipherController@getSingleVdocipherData')->name('getSingleVdocipherData');

            Route::get('/vimeo/video-list', 'VimeoController@getAllVimeoData')->name('getAllVimeoData');
            Route::get('/vimeo/video', 'VimeoController@getSingleVimeoData')->name('getSingleVimeoData');

            Route::get('/course-setting', 'CourseSettingController@setting')->name('course.setting');
            Route::post('/course-setting', 'CourseSettingController@settingSubmit');

            Route::get('/assigned-classes', 'CourseSettingController@assignedClasses')->name('assigned.index')->middleware('RoutePermissionCheck:assigned.index');
            Route::get('/all/assigned-list-data', 'CourseSettingController@getAssignedListData')->name('getAssignedListData');
            Route::post('/assign-instructor',  'CourseSettingController@assignInstructor')
            ->name('courses.assign.instructor');

            Route::get('/sample-dashboard', 'CourseSettingController@sampleDashboard')->name('sampledashboard');

            Route::get('/school-subject', 'SchoolSubjectController@index')->name('schoolSubject')->middleware('RoutePermissionCheck:schoolSubject');
            Route::post('/school-subject', 'SchoolSubjectController@store')->name('schoolSubject.store')->middleware('RoutePermissionCheck:schoolSubject.store');
            Route::get('/school-subject/{id}', 'SchoolSubjectController@edit')->name('schoolSubject.edit')->middleware('RoutePermissionCheck:schoolSubject.edit');
            Route::patch('/school-subject/{id}', 'SchoolSubjectController@update')->name('schoolSubject.update')->middleware('RoutePermissionCheck:schoolSubject.edit');
            Route::get('/school-subject-delete/{id}', 'SchoolSubjectController@destroy')->name('schoolSubject.destroy')->middleware('RoutePermissionCheck:schoolSubject.destroy');

            Route::get('/new/users/{id}', 'CourseSettingController@getFeedbackUsers')->name('feedback.users');

            Route::post('/new/users/save','CourseSettingController@saveFeedbackUsers')->name('feedback.users.save');
            
            Route::get('/new/getcourses','CourseSettingController@getCourses')->name('feedback.courses');

            Route::post('/new/courses/save','CourseSettingController@saveFeedbackCourses')->name('feedback.courses.save');

    });
});


Route::group(['prefix' => 'admin/questions', 'middleware' => ['auth']], function () {
    Route::get('/list', 'QuestionAnswerController@index')->name('qa.questions')->middleware('RoutePermissionCheck:qa.questions');
    Route::get('/show/{id}', 'QuestionAnswerController@show')->name('qa.questions.show')->middleware('RoutePermissionCheck:qa.questions.show');
    Route::get('/edit/{id}', 'QuestionAnswerController@edit')->name('qa.questions.edit')->middleware('RoutePermissionCheck:qa.questions.edit');
    Route::post('/edit/{id}', 'QuestionAnswerController@update')->middleware('RoutePermissionCheck:qa.questions.edit');
    Route::post('/reply/{id}', 'QuestionAnswerController@reply')->name('qa.questions.reply')->middleware('RoutePermissionCheck:qa.questions.show');
    Route::get('/delete/{id}', 'QuestionAnswerController@delete')->name('qa.questions.delete')->middleware('RoutePermissionCheck:qa.questions.delete');
    Route::get('/list-date', 'QuestionAnswerController@data')->name('qa.questions.data')->middleware('RoutePermissionCheck:qa.questions');

    Route::get('/setting', 'QuestionAnswerController@setting')->name('qa.setting')->middleware('RoutePermissionCheck:qa.setting');
    Route::post('/setting', 'QuestionAnswerController@settingUpdate')->middleware('RoutePermissionCheck:qa.setting');
    Route::post('/check-online', 'QuestionAnswerController@checkOnline')->name('qa.checkOnline');
    Route::any('/exit-online', 'QuestionAnswerController@exitOnline')->name('qa.exitOnline');

});