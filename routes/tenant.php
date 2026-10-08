<?php



use App\Http\Controllers\StoreGoogleAnalyticsClientIdController;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;

use App\Http\Controllers\Auth\LoginController;

use App\Http\Controllers\Skills\CorporateSkillController;

use App\Http\Controllers\Skills\CorporateRoleSkillController;

use App\Http\Controllers\Skills\CorporateEmployeeSkillController;

use App\Http\Controllers\Skills\CorporateSkillAnalyticsController;

use App\Http\Controllers\Skills\CorporateTrainingController;

use App\Http\Controllers\AuthController;

use App\Http\Controllers\AjaxController;

use App\Http\Controllers\ProfileController;

use App\Http\Controllers\AttendanceSessionController;

use App\Http\Controllers\Frontend\CustomHomeController;







Auth::routes(['verify' => true]);



Route::get('/nbtc', function () {

    return redirect('/nbtc/home');

});



Route::get('/clear-tenant', function () {

    session()->forget('tenant_slug');

    return 'Tenant cleared';

});



Route::prefix('{tenant_slug}')

    ->middleware(['tenant.slug'])

    ->group(function () {

        // Tenant login

        Route::get('login', [LoginController::class, 'showLoginForm'])

            ->name('tenant.login');

         Route::get('getLoginHours', 'Frontend\WebsiteController@getLoginHours')->name('tenant.getLoginHours');

        Route::post('login', [LoginController::class, 'login'])

            ->name('tenant.login.submit');

        Route::get('dashboard', 'HomeController@dashboard')->middleware(['auth', 'verified'])->name('tenant.dashboard');

        Route::get('/dashboard/data', 'HomeController@getInstituteData')->name('dashboard.data');

        // Route::get('dashboard', 'HomeController@instituteDashboard')->middleware(['auth', 'verified'])->name('tenant.instituteDashboard');

       

});







Route::post('store-google-analytics-client-id', StoreGoogleAnalyticsClientIdController::class)->middleware('web');

Route::get('send-password-reset-link', 'Auth\ForgotPasswordController@SendPasswordResetLink')->name('SendPasswordResetLink');

Route::get('reset-password', 'Auth\ForgotPasswordController@ResetPassword')->name('ResetPassword');

Route::get('register', 'Auth\RegisterController@RegisterForm')->name('register');

Route::get('saas-signup', 'Auth\RegisterController@LmsRegisterForm')->name('lms_register');



Route::post('send-otp', 'Auth\RegisterController@sendOtp')->name('send.otp');



Route::post('verify-email', 'Auth\RegisterController@verifyOtp')->name('verify.otp');



Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout')->name('tenant.logout');

Route::post('/resend', '\App\Http\Controllers\Auth\VerificationController@resend_mail')->name('verification_mail_resend');

Route::get('auto-login/{key}', '\App\Http\Controllers\Auth\LoginController@autologin')->name('auto.login');





Route::get('/test', 'Frontend\FrontendHomeController@test');

Route::get('/update-version', 'Frontend\FrontendHomeController@version');







Route::prefix('{tenant_slug}')

    ->middleware(['tenant.slug', 'restore.tenant.login'])

    ->group(function () {



        Route::group([

            'namespace' => 'Frontend',

        ], function () {



    Route::get('/get-calendar/{date}', 'WebsiteController@getCalendar')

    ->name('get-calendar');

    Route::get('/calendar-events/{year}/{month}', 'WebsiteController@calendarEvents')->name('calendar-events');

            

    // Route::get('{slug}', 'WebsiteController@frontPage')

    // ->name('tenant.frontPage');

    // Route::fallback('WebsiteController@frontPage')->name('frontPage');

    

   

    //Route::group(['namespace' => 'Frontend'], function () {

    

    



    Route::get('/get-courses-by-category/{category_id}', 'EdumeFrontendThemeController@getCourseByCategory')->name('getCourseByCategory');

    //wetech theme controller

    Route::get('/wetech/{route_name}', 'WeTechFrontendThemeController@route')->name('weTechController');



    Route::get('/offline', 'WebsiteController@offline')->name('offline');

    Route::get('/app-mode', 'WebsiteController@onlyAppMode')->name('onlyAppMode');



    //    Route::get('/footer/page/{slug}', 'WebsiteController@page')->name('dynamic.page');

    Route::get('/about-us', 'WebsiteController@aboutData')->name('about');

    Route::get('/contact-us', 'WebsiteController@contact')->name('contact');

    Route::post('/contact-submit', 'WebsiteController@contactMsgSubmit')->name('contactMsgSubmit');

    Route::get('privacy', 'WebsiteController@privacy')->name('privacy');

    Route::get('calendar-view', 'WebsiteController@calendarView')->name('calendar-view');



    Route::get('instructors', 'InstructorController@instructors')->name('instructors');

    Route::get('become-instructor', 'InstructorController@becomeInstructor')->name('becomeInstructor');

    Route::get('instructorDetails/{id}/{name}', 'InstructorController@instructorDetails')->name('instructorDetails');



    

    Route::get('offer', 'CourseController@offer')->name('offer');

    Route::get('courses-details1/{slug}/{notification?}', 'CourseController@courseDetails1')->name('courseDetailsView');



    Route::get('free-course', 'CourseController@freeCourses')->name('freeCourses');



    Route::get('classes', 'ClassController@classes')->name('classes');

    Route::get('class-details/{slug}/{notification?}', 'ClassController@classDetails')->name('classDetails');

    Route::get('class-start/{slug}/{host}/{meeting_id}', 'ClassController@classStart')->name('classStart');



    Route::get('quizzes', 'QuizController@quizzes')->name('quizzes');

    Route::get('quiz-details/{slug}', 'QuizController@quizDetails')->name('quizDetailsView');

    Route::get('quizStart/{id}/{quiz_id}/{slug}', 'QuizController@quizStart')->name('quizStart');

    Route::post('quizSubmit', 'QuizController@quizSubmit')->name('quizSubmit');

    Route::post('quizTestStart', 'QuizController@quizTestStart')->name('quizTestStart')->middleware('auth');

    Route::post('singleQuizSubmit', 'QuizController@singleQuizSubmit')->name('singleQuizSubmit')->middleware('auth');

    Route::get('quizResult/{id}', 'QuizController@quizResult')->name('getQuizResult');

    Route::get('quizHistory/{id}', 'QuizController@quizHistory')->name('getQuizHistory')->middleware('auth');



    Route::get('quizResultPreview/{id}', 'QuizController@quizResultPreview')->name('quizResultPreview');

    Route::get('quizResultPreviewApi/{quiz_id}', 'QuizController@quizResultPreviewApi')->name('quizResultPreviewApi')->middleware('auth');





    Route::get('search', 'WebsiteController@search')->name('search');

    Route::get('category/{id}/{name}', 'WebsiteController@categoryCourse')->name('categoryCourse');

    Route::get('sub_category/{id}/{slug}', 'WebsiteController@subCategoryCourse')->name('subCategory.course');



    Route::get('/certificate-verification', 'WebsiteController@searchCertificate')->name('searchCertificate');

    Route::post('/certificate-verification', 'WebsiteController@showCertificate')->name('showCertificate');

    Route::get('/verify-certificate/{number}', 'WebsiteController@certificateCheck')->name('certificateCheck');

    Route::get('/download-certificate/{number}', 'WebsiteController@certificateDownload')->name('certificateDownload');



    Route::get('blog', 'BlogController@allBlog')->name('blogs');

    Route::get('blog-details/{slug}/{notification?}', 'BlogController@blogDetails')->name('blogDetails');

    Route::post('blog-comment-submit', 'BlogController@blogCommentSubmit')->name('blogCommentSubmit');

    Route::post('blog-reply-submit', 'BlogController@submitBlogReply')->name('submitBlogReply');

    Route::post('blog-comment-delete/{id}', 'BlogController@deleteBlogComment')->name('deleteBlogComment');

    Route::get('load-blog-data', 'BlogController@loadMoreData')->name('load-blog-data');



    Route::post('deleteBlogCommentRepliesReply/{id}', 'BlogController@deleteBlogCommentRepliesReply')->name('deleteBlogCommentRepliesReply');

    Route::get('/addToCart/{id}/{store_qty?}', 'CartController@addToCart1')->name('tenant.addToCart1');

    Route::get('/buyNow/{id}/{store_qty?}', 'WebsiteController@buyNow')->name('tenant.buyNow');

    Route::get('/emptyCart', 'WebsiteController@emptyCart')->name('tenant.emptyCart');

    Route::post('enrollOrCart/{id}', 'WebsiteController@enrollOrCart')->name('tenant.enrollOrCart');

    Route::get('my-cart', 'WebsiteController@myCart')->name('tenant.myCart');

    Route::get('ajaxCounterCity', 'WebsiteController@ajaxCounterCity')->name('tenant.ajaxCounterCity');

    Route::get('ajaxCounterState', 'WebsiteController@ajaxCounterState')->name('tenant.ajaxCounterState');

    Route::get('/home/removeItem/{id}', 'WebsiteController@removeItem')->name('tenant.removeItem');

    Route::get('/home/removeItemAjax/{id}', 'WebsiteController@removeItemAjax')->name('tenant.removeItemAjax');

    Route::post('/submit_ans', 'WebsiteController@submitAns')->name('tenant.submitAns');



    Route::get('referral/{code}', 'ReferalController@referralCode')->name('tenant.referralCode');

    Route::get('referral', 'ReferalController@referral')->name('tenant.referral')->middleware('auth');





    Route::post('/search', 'SearchController@search')->name('tenant.search');

    Route::post('subscribe', 'WebsiteController@subscribe')->name('tenant.subscribe');

    Route::get('getItemList', 'CartController@getItemList')->name('tenant.getItemList');



    //subscription module

    Route::get('/course/subscription', 'WebsiteController@subscription')->name('tenant.courseSubscription');

    Route::get('/course/subscription/{plan_id}', 'WebsiteController@subscriptionCourseList')->name('tenant.subscriptionCourseList');

    Route::get('/course-subscription/checkout', 'WebsiteController@subscriptionCheckout')->name('tenant.courseSubscriptionCheckout')->middleware(['auth']);

    Route::get('/subscription-courses', 'WebsiteController@subscriptionCourses')->name('tenant.subscriptionCourses');

//    Route::get('/bundle-subscription-courses', 'WebsiteController@bundleSubscriptionCourses')->name('tenant.bundleSubscriptionCourses');

    Route::get('/continue-course/{slug}', 'WebsiteController@continueCourse')->name('tenant.continueCourse');

    Route::get('/class-records/{slug}', 'WebsiteController@classRecords')->name('tenant.classRecords')->middleware('auth');

    Route::get('/class-record/{class_id}/{record_id}', 'WebsiteController@classRecord')->name('tenant.classRecordView')->middleware('auth');



    //saas module

    Route::get('/saas-packages', 'FrontendSaasController@index')->name('tenant.saasPackages');

    Route::get('/saas-packages/checkout', 'FrontendSaasController@saasCheckout')->name('tenant.saasCheckout');



    Route::get('/private-path-course-enrolled/{id}', 'StudentController@privatePathCourseEnrolled')->name('tenant.privatePathCourseEnrolled');

    Route::get('/private-path-course-enrolled/{id}', 'StudentController@privatePathCourseEnrolled')->name('tenant.privatePathCourseEnrolled');



    //org subscription module

    Route::get('/org-subscription-courses', 'WebsiteController@orgSubscriptionCourses')->name('tenant.orgSubscriptionCourses');

    Route::get('/org-subscription-plan-list/{id}', 'WebsiteController@orgSubscriptionPlanList')->name('tenant.orgSubscriptionPlanList');





    Route::post('comment', 'CommentController@saveComment')->name('tenant.saveComment')->middleware('auth');

    Route::post('commentAjax', 'CommentController@saveCommentAjax')->name('tenant.saveCommentAjax')->middleware('auth');

    Route::post('comment-replay', 'CommentController@submitCommnetReply')->name('tenant.submitCommnetReply')->middleware('auth');

    Route::post('comment-replay-ajax', 'CommentController@submitCommnetReplyAjax')->name('tenant.submitCommnetReplyAjax')->middleware('auth');

    Route::post('comment-delete/{id}', 'CommentController@deleteComment')->name('tenant.deleteComment')->middleware('auth');

    Route::post('review-delete/{id}', 'CommentController@deleteReview')->name('tenant.deleteReview')->middleware('auth');

    Route::post('comment-replay-delete/{id}', 'CommentController@deleteCommnetReply')->name('tenant.deleteCommentReply')->middleware('auth');



        });

});

Route::group(['prefix' => 'saas', 'middleware' => ['auth']], function () {

    Route::post('payment', 'SaasPaymentController@payment')->name('saasPayment');

    Route::post('submit', 'SaasPaymentController@subscriptionSubmit')->name('saasSubmit');

    Route::get('paypalSaasSuccess', 'SaasPaymentController@paypalSubscriptionSuccess')->name('paypalSaasSuccess');

    Route::get('paypalSaasFailed', 'SaasPaymentController@paypalSubscriptionFailed')->name('paypalSaasFailed');



});



Route::group(['namespace' => 'Frontend', 'middleware' => ['auth']], function () {

    Route::post('logged-out/device', 'StudentController@logOutDevice')->name('log.out.device');

});



Route::prefix('{tenant_slug}')

    ->middleware('tenant.slug', 'restore.tenant.login')

    ->group(function () {



        Route::group([

            'namespace'  => 'Frontend',

            'middleware' => ['student']

        ], function () {



        Route::get('catalog', 'StudentController@catalog')->name('catalog');



        Route::get('student-dashboard', 'StudentController@myDashboard')->name('tenant.studentDashboard');

        Route::get('my-courses', 'StudentController@myCourses')->name('tenant.myCourses');

        Route::get('my-feedbacks', 'StudentController@myFeedbacks')->name('tenant.myFeedbacks');

        Route::get('courses', 'CourseController@courses')->name('courses');

        Route::get('my-organization-catalog', 'StudentController@myOrganizationCatalog')->name('myOrganizationCatalog');



        Route::get('learning-plan', 'StudentController@learningPlan')->name('learningPlan');

        Route::get('learning-plan-list/{id}', 'StudentController@learningPlanList')->name('LearningPlanList');

        Route::get('online-classes', 'StudentController@getOnlineClasses')->name('onlineclasses');

        Route::get('available-resources', 'StudentController@waitingList')->name('waitingList');

        Route::get('online-classes', 'StudentController@getOnlineClasses')->name('onlineclasses');

        Route::get('corporate-list', 'StudentController@corporateList')->name('corporateList');



        Route::get('corporate-resource-list', 'StudentController@CorporateResourceList')->name('corporateResourceList');



        Route::get('corporate-resource-details', 'StudentController@CorporateResourceDetails')->name('corporateResourceDetails');



        Route::get('send-request-list', 'StudentController@requestList')->name('requestList');



        Route::get('received-request-list', 'StudentController@receivedRequestList')->name('receivedRequestList');



        Route::get('hr-dashboard', 'StudentController@hrDashboard')->name('hrDashboard');


         Route::post('/bench-users-revert','StudentController@revertBenchUsers')->name('bench.users.revert');
         
      

        Route::get('hr-report', 'StudentController@hrReport')->name('hrReport');



        Route::get('internal-bench-dashboard', 'StudentController@internalBenchDashboard')->name('internalBenchDashboard');

        Route::get('internal-bench-pool', 'StudentController@internalBenchPool')->name('internalBenchPool');

        Route::get('internal-training-management', 'StudentController@internalTraining')->name('internalTraining');

        Route::get('assign-training', 'StudentController@assignTraining')->name('assignTraining');

        Route::get('internal-mobility', 'StudentController@internalMobility')->name('internalMobility');

        Route::get('raise-demand', 'StudentController@raiseDemand')->name('raiseDemand');

        Route::get('internal-skill-gap-readiness-engine', 'StudentController@internalSkillGapReadinessEngine')->name('internalSkillGapReadinessEngine');

        Route::get('internal-lifecycle-tracker', 'StudentController@internalLifecycleTracker')->name('internalLifecycleTracker');

        Route::get('add-bench-resource', 'StudentController@addBenchResource')->name('addBenchResource');

        Route::get('internal_bench_setting', 'StudentController@internalBenchSetting')->name('internalBenchSetting');







        

        Route::get('/get-secondary-skills/{id}', 'StudentController@getSecondarySkills')->name('getSecondarySkills');

        



        Route::get('/corporate-resources-datatable', 'StudentController@getCorporateLists')->name('corporate.resources.datatable');



        Route::get('/organization-resources-datatable', 'StudentController@getOrganizationResourceLists')->name('organization.resources.datatable');



        Route::get('/organization-requests-datatable', 'StudentController@getOrganizationRequestLists')->name('organization.requests.datatable');



        Route::get('/organization-received-requests-datatable', 'StudentController@getOrganizationReceivedRequestLists')->name('organization.received.requests.datatable');



        Route::get('/corporate-resources/{id}', 'StudentController@corporateUsers')->name('corporate.resource.users');



        Route::post('/assign-users', 'StudentController@assignUsers')->name('assign.users');



        Route::post('/approve-users', 'StudentController@approveUsers')->name('approve.users');



        Route::get('corporate-resource-users/{id}',

                'StudentController@corporateUsers'

            )->name('corporate.resource.users');



        Route::get('/corporate-user-details/{id}', 'StudentController@userDetails')->name('corporate.user.details');



            Route::get('corporate-resource-users-datatable/{id}',

                'StudentController@corporateUsersDatatable'

            )->name('corporate.resource.users.datatable');



        Route::post('/upload-users', 'StudentController@uploadUsers')->name('upload.users');



        Route::get('learning-path', 'StudentController@learningPath')->name('learningPath');

        Route::get('private-courses', 'StudentController@privateCourses')->name('PrivateCourses');

        Route::get('learning-path-private-courses', 'StudentController@learningPathPrivateCourses')->name('LearningPathPrivateCourses');

        Route::get('learning-path-private-course-group/{id}', 'StudentController@learningPathPrivateCourseGroup')->name('learning_path_group');

        Route::get('learning-path-recommended', 'StudentController@learningPathRecommended')->name('LearningPathRecommended');

        Route::get('my-classes', 'StudentController@myclasses')->name('tenant.myClasses');

        Route::get('my-private-courses', 'StudentController@myPrivateCourses')->name('tenant.myPrivateClasses');

        Route::get('my-online-course', 'StudentController@myCourses')->name('myOnlineCourse');

        Route::get('my-offline-course', 'StudentController@myCourses')->name('myOfflineCourse');

        Route::get('my-quizzes', 'StudentController@myCourses')->name('tenant.myQuizzes');

        Route::get('my-report', 'StudentController@myReports')->name('myReports');

        Route::get('my-certificate', 'StudentController@myCertificate')->name('tenant.myCertificate');

        Route::get('my-badges', 'StudentController@myBadges')->name('myBadges');



        Route::get('my-assignment', 'StudentController@myAssignment')->name('myAssignment');

        Route::get('my-assignment/{id}', 'StudentController@myAssignmentDetails')->name('myAssignment_details');

        Route::get('my-wishlist', 'StudentController@myWishlists')->name('tenant.myWishlists');

        Route::get('my-purchases', 'StudentController@myPurchases')->name('myPurchases');

        Route::get('my-refund-dispute', 'StudentController@myRefundDispute')->name('myRefundDispute');

        Route::get('/my-refund-details/{id}', 'StudentController@my_refund_show')->name('refund.frontend.my_refund_order_detail');





        Route::get('enrollment-cancellation', 'StudentController@enrollmentCancellation')->name('enrollmentCancellation');

        Route::post('enrollment-cancellation', 'StudentController@enrollmentCancellationSubmit')->name('enrollmentCancellationSubmit');

        Route::get('my-bundle', 'StudentController@myBundle')->name('myBundle');

        Route::get('topic-report/{id}', 'StudentController@topicReport')->name('topicReport');

        Route::get('my-profile', 'StudentController@myProfile')->name('tenant.myProfile');

        Route::any('ajax-update-profile-image', 'StudentController@ajaxUploadProfilePic')->name('ajaxUploadProfilePic');

        Route::post('my-profile-update', 'StudentController@myProfileUpdate')->name('myProfileUpdate');

        Route::get('my-account', 'StudentController@myAccount')->name('myAccount');

        Route::post('my-password-update', 'StudentController@MyUpdatePassword')->name('MyUpdatePassword');

        Route::post('my-account-delete', 'StudentController@MyAccountDelete')->name('MyAccountDelete');

        Route::post('my-email-update', 'StudentController@MyEmailUpdate')->name('MyEmailUpdate');

        Route::post('reword-point-convert', 'StudentController@rewardPontConvert')->name('rewardPontConvert');



        Route::get('deposit', 'StudentController@deposit')->name('deposit');

        Route::post('deposit', 'StudentController@deposit')->name('depositSelectOption');

        Route::get('logged-in/devices', 'StudentController@loggedInDevices')->name('logged.in.devices');

        Route::get('invoice/{id}', 'StudentController@Invoice')->name('invoice');

        Route::get('my-purchase-order-detail/{id}', 'StudentController@my_purchase_order_detail')->name('my_purchase_order_detail');

        Route::get('my-virtual-file-download/{id}', 'StudentController@my_virtual_file_download')->name('my_virtual_file_download');

        Route::get('my-virtual-file/{slug}', 'StudentController@downloadVirtualFile')->name('downloadVirtualFile');

        Route::get('/make-refund-request/{id}', 'StudentController@make_refund_request')->name('refund.make_request');

        Route::post('/make-refund-request-store', 'StudentController@store_refund_request')->name('refund.refund_make_request_store');

        Route::get('/my-purchase-order-pdf/{id}', 'StudentController@my_purchase_order_pdf')->name('frontend.my_purchase_order_pdf');





        Route::get('subscription-invoice/{id}', 'StudentController@subInvoice')->name('subInvoice');

        Route::get('checkout/{shipping_method?}', 'StudentController@CheckOut')->name('CheckOut');

        Route::get('remove-profile-pic', 'StudentController@removeProfilePic')->name('removeProfilePic');

        Route::get('course-certificate/{id}/{slug}', 'StudentController@getCertificate')->name('getCertificate')->middleware('auth');

        Route::post('/submitReview', 'StudentController@submitReview')->name('tenant.submitReview');



        Route::get('my-study-materials', 'StudyMaterialController@myHomework')->name('myHomework');

        Route::get('my-study-materials/{id}', 'StudyMaterialController@myHomeworkDetails')->name('myHomework_details');





        Route::get('my-leaderboard', 'StudentController@leaderboard')->name('my-leaderboard');

        Route::get('my-skill', 'StudentSkillController@mySkill')->name('mySkill');





        Route::get('getCommentsReply', 'CommentController@getCommentsReply')->name('getCommentsReply');



        Route::get('my-qa', 'LessonQuestionController@index')->name('myQA');

        Route::get('my-qa-data', 'LessonQuestionController@indexData')->name('myQA.data');

        Route::get('load-qa/{course_id}/{lesson_id}', 'LessonQuestionController@loadQna')->name('myQA.loadQna');

        Route::post('my-qa', 'LessonQuestionController@store')->name('myQA.store');

        Route::get('my-qa/edit/{id}', 'LessonQuestionController@edit')->name('myQA.edit');

        Route::post('my-qa/edit/{id}', 'LessonQuestionController@update')->name('myQA.update');

        Route::get('my-qa/show/{id}', 'LessonQuestionController@show')->name('myQA.show');

        Route::get('my-qa/delete/{id}', 'LessonQuestionController@delete')->name('myQA.delete');





        Route::get('payment-success/{data}', 'StudentController@paymentSuccess')->name('paymentSuccess');



        Route::get('course-badges', 'StudentController@courseBadges')->name('course_badges');



        Route::get('/announcements/{id}', 'StudentController@userAnnouncements')->name('announcements.list');

    });

});



Route::prefix('{tenant_slug}')

    ->middleware(['tenant.slug', 'restore.tenant.login'])

    ->group(function () {



        Route::group([

            'middleware' => ['student']

        ], function () {



    

    Route::get('my-notification-setup', 'NotificationController@myNotificationSetup')->name('myNotificationSetup');

    Route::get('my-notifications', 'NotificationController@myNotification')->name('myNotification');

    Route::get('my-noticeboard', 'NotificationController@myNoticeboard')->name('myNoticeboard');

    Route::get('show-noticeboard/{id}', 'NotificationController@showNoticeboard')->name('showNoticeboard');

});

    });



//in this controller we can use for place order

Route::group(['prefix' => 'order', 'middleware' => ['auth']], function () {



    Route::post('submit', 'PaymentController@makePlaceOrder')->name('makePlaceOrder');

    Route::get('/payment', 'PaymentController@payment')->name('orderPayment');

    Route::post('/paymentSubmit', 'PaymentController@paymentSubmit')->name('paymentSubmit');

    //paypal url

    Route::get('paypal/success', 'PaymentController@paypalSuccess')->name('paypalSuccess');

    Route::get('paypal/failed', 'PaymentController@paypalFailed')->name('paypalFailed');

});

//deposit

Route::group(['prefix' => 'deposit', 'middleware' => ['auth']], function () {



    Route::post('submit', 'DepositController@depositSubmit')->name('depositSubmit');

    Route::get('paypalDepositSuccess', 'DepositController@paypalDepositSuccess')->name('paypalDepositSuccess');

    Route::get('paypalDepositFailed', 'DepositController@paypalDepositFailed')->name('paypalDepositFailed');



});



Route::group(['prefix' => 'subscription', 'middleware' => ['auth']], function () {

    Route::post('payment', 'SubscriptionPaymentController@payment')->name('subscriptionPayment');

    Route::post('submit', 'SubscriptionPaymentController@subscriptionSubmit')->name('subscriptionSubmit');

    Route::get('paypalSubscriptionSuccess', 'SubscriptionPaymentController@paypalSubscriptionSuccess')->name('paypalSubscriptionSuccess');

    Route::get('paypalSubscriptionFailed', 'SubscriptionPaymentController@paypalSubscriptionFailed')->name('paypalSubscriptionFailed');



});



Route::group(['namespace' => 'Frontend'], function () {



    Route::get('/home', 'CustomHomeController@index')->name('CustomHome');

});



Route::prefix('{tenant_slug}')

    ->middleware('tenant.slug', 'restore.tenant.login')

    ->group(function () {

        Route::group(['namespace' => 'Frontend'], function () {

            Route::get('/home', 'CustomHomeController@index')->name('tenant.home');

            Route::get('/courses', 'CourseController@CustomCourses')->name('tenant.customCourses');

            Route::get('courses-details/{slug}/{notification?}', 'CourseController@CustomCourseDetails')->name('tenant.courseDetailsView');

            Route::get('custom-courses-details/{slug}/{notification?}', 'CourseController@CustomCourseDetails')->name('tenant.customCourseDetails');

            Route::get('courses', 'CourseController@courses')->name('tenant.courses');



        });

    });



Route::group([

    'namespace' => 'Frontend',

], function () {

    

    Route::get('courses', 'CourseController@courses')->name('courses');

    Route::get('courses-details/{slug}/{notification?}', 'CourseController@courseDetails')->name('courseDetailsView');
    

    Route::get('about-us', 'WebsiteController@aboutData')->name('about');

    Route::get('contact-us', 'WebsiteController@contact')->name('contact');

    Route::get('classes', 'ClassController@classes')->name('classes');

    Route::get('quizzes', 'QuizController@quizzes')->name('quizzes');

    Route::get('instructorDetails/{id}/{name}', 'InstructorController@instructorDetails')->name('instructorDetails');

    Route::get('/addToCart/{id}/{store_qty?}', 'CartController@addToCart')->name('addToCart');

    Route::get('/buyNow/{id}/{store_qty?}', 'WebsiteController@buyNow')->name('buyNow');

    Route::get('cancel-course-coupon/{course_id}', 'Frontend\StudentController@StudentCancelCourseCoupon')->name('StudentCancelCourseCoupon');

    Route::post('/submitReview', 'StudentController@submitReview')->name('submitReview');

    Route::get('/continue-course/{slug}', 'WebsiteController@continueCourse')->name('continueCourse');

    Route::post('comment', 'CommentController@saveComment')->name('saveComment')->middleware('auth');

    Route::post('commentAjax', 'CommentController@saveCommentAjax')->name('saveCommentAjax')->middleware('auth');

    Route::get('/bookmarkSave/{id}', 'Frontend\StudentController@bookmarkSave')->name('bookmarkSave');

    Route::get('/bookmarksDelete/{id}', 'Frontend\StudentController@bookmarksDelete');

    Route::get('/bookmarks/show/{id}', 'Frontend\StudentController@show');

    Route::post('/search', 'SearchController@search')->name('search');

    Route::get('class-details/{slug}/{notification?}', 'ClassController@classDetails')->name('classDetails');

     Route::get('/college-dashboard', 'HomeController@collegeDashboard')->name('college_dashboard');
        Route::get('/college-data', 'HomeController@getCollegeData')->name('college_data');

});



Route::prefix('{tenant_slug}')

    ->middleware(['tenant.slug', 'restore.tenant.login'])

    ->group(function () {

    

    Route::group(['middleware' => ['auth']], function () {

    Route::get('/home1', 'HomeController@index1')->name('home1');

    Route::get('getDashboardData', 'HomeController@getDashboardData')->name('tenant.getDashboardData');

    

    Route::get('userLoginChartByDays', 'HomeController@userLoginChartByDays')->name('userLoginChartByDays');

    Route::get('/userLoginChartByTime', 'HomeController@userLoginChartByTime')->name('userLoginChartByTime');

    Route::get('/validateGenerate', 'HomeController@validateGenerate')->name('validateGenerate');

    Route::post('/validateGenerate', 'HomeController@validateGenerateSubmit')->name('validateGenerateSubmit');

    Route::post('lesson-complete', 'Frontend\WebsiteController@lessonComplete')->name('lesson.complete');

    Route::any('lesson-complete-ajax', 'Frontend\WebsiteController@lessonCompleteAjax')->name('lesson.complete.ajax');



    Route::get('get-notifications', 'NotificationController@getNotificationUpdate')->name('getNotificationUpdate');

    Route::get('ajaxNotificationMakeRead', 'NotificationController@ajaxNotificationMakeRead')->name('ajaxNotificationMakeRead');

    Route::get('NotificationMakeAllRead', 'NotificationController@NotificationMakeAllRead')->name('NotificationMakeAllRead');

    Route::get('notification-delete/{id}', 'NotificationController@delete')->name('notificationDelete');



    Route::get('StudentApplyCoupon', 'Frontend\StudentController@StudentApplyCoupon')->name('StudentApplyCoupon');

    Route::get('apply-course-coupon', 'Frontend\StudentController@StudentApplyCourseCoupon')->name('StudentApplyCourseCoupon')->withoutMiddleware(['auth']);

    Route::get('cancel-course-coupon/{course_id}', 'Frontend\StudentController@StudentCancelCourseCoupon')->name('tenant.StudentCancelCourseCoupon')->withoutMiddleware(['auth']);

    Route::get('fullscreen-view/{course_id}/{lesson_id}', 'Frontend\WebsiteController@fullScreenView')->name('tenant.fullScreenView');

    Route::get('/bookmarkSave/{id}', 'Frontend\StudentController@bookmarkSave')->name('tenant.bookmarkSave');

    Route::get('/bookmarksDelete/{id}', 'Frontend\StudentController@bookmarksDelete');

    Route::get('/bookmarks/show/{id}', 'Frontend\StudentController@show');

Route::post('lesson/start', 'Frontend\WebsiteController@lessonStart')->name('lesson.start');

 Route::post('lesson/end', 'Frontend\WebsiteController@lessonEnd')->name('lesson.end');

    });

});







//Admin Routes Here

Route::prefix('{tenant_slug}')

    ->middleware(['tenant.slug', 'restore.tenant.login'])

    ->group(function () {



        Route::group([

            'namespace'  => 'Admin',

            'prefix'     => 'admin',

            'as'         => 'admin.',

            'middleware' => ['auth', 'admin'],

        ], function () {



         Route::post('/bulk-approve', 'AdminController@bulkApprove')->name('bulkApprove');





    Route::post('/get-user-data/{id}', 'AdminController@getUserDate')->name('getUserDate');

    Route::post('remove-image', 'AdminController@removeImageByAjax')->name('removeImageByAjax');





    Route::get('/reveune-list', 'AdminController@reveuneList')->name('reveuneList')->middleware('RoutePermissionCheck:admin.reveuneList');

    Route::get('/reveuneListInstructor', 'AdminController@reveuneListInstructor')->name('reveuneListInstructor')->middleware('RoutePermissionCheck:admin.reveuneListInstructor');



    Route::get('/custom-list', 'AdminController@customList')->name('customList')->middleware('RoutePermissionCheck:admin.customList');



    Route::get('/custom-dashboard', 'AdminController@customDashboard')->name('customDashboard')->middleware('RoutePermissionCheck:admin.customDashboard');

    

    Route::get('/get-group-data', 'AdminController@getGroupData')->name('getGroupData')->middleware('RoutePermissionCheck:admin.customDashboard');



    Route::get('/course-list', 'AdminController@courseList')->name('courseList')->middleware('RoutePermissionCheck:admin.courseList');



    Route::get('/export-lms-excel', 'AdminController@exportExcel');



    Route::get('/feedback-report', 'AdminController@feedbackReport')->name('feedbackReport')->middleware('RoutePermissionCheck:admin.feedbackReport');



    Route::get('/learning-plan-report', 'AdminController@learningPlanReport')->name('learningPlanReport')->middleware('RoutePermissionCheck:admin.learningPlanReport');

    

    Route::get('/institutionWiseUser', 'AdminController@institutionWiseUser')->name('institutionWiseUser')->middleware('RoutePermissionCheck:admin.institutionWiseUser');

    Route::get('/institutionWisePerformance', 'AdminController@institutionWisePerformance')->name('institutionWisePerformance')->middleware('RoutePermissionCheck:admin.institutionWisePerformance');

    Route::get('/userWisePerformance', 'AdminController@userWisePerformance')->name('userWisePerformance')->middleware('RoutePermissionCheck:admin.userWisePerformance');



    Route::get('/enrol-list', 'AdminController@enrollLogs')->name('enrollLogs')->middleware('RoutePermissionCheck:admin.enrollLogs');

    Route::get('/approve-list', 'AdminController@approveLogs')->name('approveLogs')->middleware('RoutePermissionCheck:admin.approveLogs');

   

    

    Route::get('/cancel-list', 'AdminController@cancelLogs')->name('cancelLogs')->middleware('RoutePermissionCheck:admin.enrollLogs');

    Route::post('/enrol-delete', 'AdminController@enrollDelete')->name('enrollDelete')->middleware('RoutePermissionCheck:admin.enrollLogs');

    Route::post('/enrol-delete-bulk', 'AdminController@enrollDeleteBulk')->name('enrollBulkDelete')->middleware('RoutePermissionCheck:admin.enrollLogs');

   

    // enrollBulkDelete

   



    Route::post('/enrol-generate-certificate', 'AdminController@generateCertificate')->name('generateCertificate')->middleware('RoutePermissionCheck:admin.enrollLogs');

    Route::post('/enrol-remove-certificate', 'AdminController@removeCertificate')->name('removeCertificate')->middleware('RoutePermissionCheck:admin.enrollLogs');



    Route::get('/instructor-payout', 'AdminController@instructorPayout')->name('instructor.payout')->middleware('RoutePermissionCheck:admin.instructor.payout');

    Route::post('/instructor-payout-request', 'AdminController@instructorRequestPayout')->name('instructor.instructorRequestPayout')->middleware('RoutePermissionCheck:admin.instructor.payout');

    Route::post('/instructor-payout-complete', 'AdminController@instructorCompletePayout')->name('instructor.instructorCompletePayout')->middleware('RoutePermissionCheck:admin.instructor.payout');

    Route::get('/enrollFilter', 'AdminController@enrollLogs');

    Route::post('/enrollFilter', 'AdminController@enrollFilter')->name('enrollFilter');

    Route::get('/courseEnrolls/{id}', 'AdminController@courseEnrolls')->name('enrollLog');

    Route::post('/courseEnrolls/{id}', 'AdminController@sortByDiscount')->name('sortByDiscount');



    Route::get('/all/enrol-list-data', 'AdminController@getEnrollLogsData')->name('getEnrollLogsData')->middleware('RoutePermissionCheck:admin.enrollLogs');

    Route::get('/all/approve-list-data', 'AdminController@getApproveLogsData')->name('getApproveLogsData')->middleware('RoutePermissionCheck:admin.approveLogs');

    Route::get('/all/cancel-list-data', 'AdminController@getCancelLogsData')->name('getCancelLogsData')->middleware('RoutePermissionCheck:admin.enrollLogs');

    Route::get('/all/payout-data', 'AdminController@getPayoutData')->name('getPayoutData');

        });



});



Route::prefix('{tenant_slug}')

    ->middleware('tenant.slug', 'restore.tenant.login')

    ->group(function () {



        Route::group([

            'namespace'  => 'Admin',

            'prefix'     => 'refund',

            'as'         => 'tenant.refund.',

            'middleware' => ['auth', 'admin'],

        ], function () {



//Route::group(['namespace' => 'Admin', 'prefix' => 'refund', 'as' => 'refund.', 'middleware' => ['auth', 'admin']], function () {

    Route::get('settings', 'RefundController@settings')->name('settings.create');

    Route::post('settings', 'RefundController@settingsUpdate')->name('settings.update');

    Route::get('approved/{id}', 'RefundController@approved')->name('approved');

    Route::post('reject', 'RefundController@reject')->name('reject');



});



    });





Route::prefix('{tenant_slug}')

    ->where(['tenant_slug' => '[a-z0-9\-]+'])

    ->middleware('tenant.slug', 'restore.tenant.login')

    ->group(function () {



        Route::group([

            'namespace'  => 'Admin',

            'prefix'     => 'course',

            'as'         => 'course.',

            'middleware' => ['auth', 'admin'],

        ], function () {







    Route::get('categories', 'CourseController@category')->name('category')->middleware('RoutePermissionCheck:course.category');

    Route::post('categories/status-update', 'CourseController@category_status_update')->name('category.status_update')->middleware('RoutePermissionCheck:course.category.status_update');

    Route::post('categories/store', 'CourseController@category_store')->name('category.store')->middleware('RoutePermissionCheck:course.category.store');

    Route::post('categories/update', 'CourseController@category_update')->name('category.update')->middleware('RoutePermissionCheck:course.category.edit');

    Route::get('categories/edit/{id}', 'CourseController@category_edit')->name('category.edit')->middleware('RoutePermissionCheck:course.category.edit');

    Route::get('categories/delete/{id}', 'CourseController@category_delete')->name('category.delete')->middleware('RoutePermissionCheck:course.category.delete');



    Route::get('ajax-category', 'CourseController@ajaxCategory')->name('ajax-category');





    Route::get('sub-categories', 'CourseController@sub_category')->name('subcategory')->middleware('RoutePermissionCheck:course.subcategory');

    Route::post('sub-categories/status-update', 'CourseController@sub_category_status_update')->name('subcategory.status_update')->middleware('RoutePermissionCheck:course.subcategory.status_update');

    Route::post('sub-categories/store', 'CourseController@sub_category_store')->name('subcategory.store')->middleware('RoutePermissionCheck:course.subcategory.store');

    Route::post('sub-categories/update', 'CourseController@sub_category_update')->name('subcategory.update')->middleware('RoutePermissionCheck:course.subcategory.edit');

    Route::get('sub-categories/edit/{id}', 'CourseController@sub_category_edit')->name('subcategory.edit')->middleware('RoutePermissionCheck:course.subcategory.edit');

    Route::get('sub-categories/delete/{id}', 'CourseController@sub_category_delete')->name('subcategory.delete')->middleware('RoutePermissionCheck:course.subcategory.delete');



        });

});



Route::prefix('{tenant_slug}')

    ->where(['tenant_slug' => '[a-z0-9\-]+'])

    ->middleware(['tenant.slug', 'auth', 'restore.tenant.login'])

    ->group(function () {



        Route::post('status-enable-disable', [AjaxController::class, 'statusEnableDisable'])

            ->name('tenant.statusEnableDisable');



    });



Route::prefix('{tenant_slug}')

    ->middleware('tenant.slug', 'restore.tenant.login')

    ->group(function () {



        Route::group([

            'middleware' => ['auth'],

        ], function () {



//Route::group(['middleware' => ['auth']], function () {

    Route::get('profile-settings', 'UserController@changePassword')->name('changePassword');

    Route::post('profile-settings', 'UserController@UpdatePassword')->name('updatePassword');

    Route::post('profile-update', 'UserController@update_user')->name('update_user');



    Route::get('admin/user-delete-request', 'DeleteUserRequestManageController@index')->name('admin.user_delete_request.index');

    Route::get('admin/user-delete-request/datatable', 'DeleteUserRequestManageController@datatable')->name('admin.user_delete_request.datatable');

    Route::post('admin/user-delete-request/delete', 'DeleteUserRequestManageController@destroy')->name('admin.user_delete_request.destroy');

    Route::post('admin/user-delete-request/reject', 'DeleteUserRequestManageController@reject')->name('admin.user_delete_request.reject');

});

});

//Route::post('get-user-by-role', 'UserController@getUsersByRole')->name('getUsersByRole')->middleware('auth');



//user profile

Route::prefix('{tenant_slug}')

    ->middleware('tenant.slug', 'restore.tenant.login')

    ->group(function () {



        Route::group([

            'namespace'  => 'Admin',

            'prefix'     => 'users',

            'as'         => 'tenant.users.',

            

        ], function () {

    //Route::group(['as' => 'users.', 'prefix' => 'users'], function () {

    Route::get('follow/{id}', 'ProfileController@follow')->name('follow');

    Route::get('unfollow/{id}', 'ProfileController@unfollow')->name('unfollow');

    Route::get('/settings', 'ProfileController@userSettings')->name('settings');

    //educations

    Route::get('educations/create', 'ProfileController@educationCreate')->name('educations.create');

    Route::post('educations/store', 'ProfileController@educationStore')->name('educations.store');

    Route::get('educations/{id}/edit', 'ProfileController@educationEdit')->name('educations.edit');

    Route::put('educations/{id}/update', 'ProfileController@educationUpdate')->name('educations.update');

    Route::get('educations/{id}/destroy', 'ProfileController@educationDestroy')->name('educations.destroy');



    //experiences

    Route::get('experiences/create', 'ProfileController@experienceCreate')->name('experiences.create');

    Route::post('experiences/store', 'ProfileController@experienceStore')->name('experiences.store');

    Route::get('experiences/{id}/edit', 'ProfileController@experienceEdit')->name('experiences.edit');

    Route::put('experiences/{id}/update', 'ProfileController@experienceUpdate')->name('experiences.update');

    Route::get('experiences/{id}/destroy', 'ProfileController@experienceDestroy')->name('experiences.destroy');



    //skills

    Route::get('skills/create', 'ProfileController@skillCreate')->name('skills.create');

    Route::post('skills/store', 'ProfileController@skillStore')->name('skills.store');



    //about

    Route::post('about/update', 'ProfileController@aboutUpdate')->name('about.update');



    //extra-info

    Route::post('extra-info/update', 'ProfileController@extraInfoUpdate')->name('extra_info.update');



    //user document

    Route::post('document/update', 'ProfileController@documentUpdate')->name('document.update');

    Route::get('document/{id}/delete', 'ProfileController@documentDelete')->name('document.destroy');



    //social info

    Route::post('social-info/update', 'ProfileController@socialInfoUpdate')->name('social_info.update');



    //basic info

    Route::post('basic-info/update', 'ProfileController@basicInfoUpdate')->name('basic_info.update');



    Route::post('2fa/update', 'ProfileController@faUpdate')->name('2Fa.update');



    //image update

    Route::post('photos/update', 'ProfileController@photoUpdate')->name('photo.update');



    //financial

    Route::get('payout-account-type/{id}', 'ProfileController@payoutAccountType')->name('payout_account_type');

    Route::post('finance-account', 'ProfileController@financeAccount')->name('finance.account');



    //delete-account-request

    Route::post('account-delete-request', 'ProfileController@deleteAccount')->name('account.delete');



    //followers

   



    //profile data hide show

    Route::post('profile-data-toggle', 'ProfileController@profileDataToggle')->name('profile_data_toggle');





    Route::get('{id}/profile', 'ProfileController@profile')->name('profile');

    //offline status

    Route::get('offline-status', 'ProfileController@offlineStatusChange')->name('offline_status_change');

    Route::post('offline-status-submit', 'ProfileController@offlineStatusSubmit')->name('offline_status.submit');





    //api route

    Route::post('zoom-settings', '\Modules\Zoom\Http\Controllers\SettingController@updateSettings')->name('zoom.settings.update');



    

});

});



Route::prefix('{tenant_slug}')

    ->middleware(['tenant.slug', 'restore.tenant.login'])

    ->group(function () {



        Route::get('profile/{username}', 'ProfileController@profile')

            ->name('profileUniqueUrl');

        Route::get('change-panel/{role_id}', 'ProfileController@changePanel')->name('student.changePanel');



    });



Route::get('profile/{username}/course-badges/{id}', 'ProfileController@courseBadge')->name('profileGetCourseBadge')->withoutMiddleware('auth');



Route::get('nbtc/feedback/{form_id}/QAm62pfYtYbZ2BSthykWVxulHv2totV3ZZ3ccJ4KWihBAwDUh7jABJX5erFfiPRZ', 'FeedbackController@show')->name('feedback.show')->withoutMiddleware('auth');





Route::post('nbtc/feedback', 'FeedbackController@submit')->name('feedback.submit')->withoutMiddleware('auth');



Route::get('nbtc/feedback/thankyou', 'FeedbackController@thankyou')->name('feedback.thankyou')->withoutMiddleware('auth');

// print_r('tets'); exit;

Route::post('nbtc/feedback/check-email', 'FeedbackController@checkEmail')->name('feedback.checkEmail');



//user my enrollment/study/assign

Route::prefix('{tenant_slug}')

    ->middleware(['tenant.slug', 'restore.tenant.login'])

    ->group(function () {



        Route::group([

            'as' => 'users.',

            'prefix' => 'user',

            'middleware' => ['auth']

        ], function () {



            Route::get('my-topics', 'MyPanelController@myTopics')->name('my_topics.index');

            Route::get('my-topics/datatable', 'MyPanelController@myTopicsDatatable')->name('my_topics.datatable');



            Route::get('my-certificate', 'MyPanelController@myCertificates')->name('my_certificates.index');

            Route::get('my-certificate/datatable', 'MyPanelController@myCertificatesDatatable')->name('my_certificates.datatable');



            Route::get('deposit', 'MyPanelController@deposit')->name('deposit.index');

            Route::get('deposit/datatable', 'MyPanelController@depositDatatable')->name('deposit.datatable');



            Route::get('logged-in/devices', 'MyPanelController@loggedInDevices')->name('logged_in_devices.index');

            Route::get('logged-in/devices/datatable', 'MyPanelController@loggedInDevicesDatatable')->name('logged_in_devices.datatable');



            Route::get('my-referral', 'MyPanelController@myReferral')->name('my_referral.index');

            Route::get('my-referral/datatable', 'MyPanelController@myReferralDatatable')->name('my_referral.datatable');



            Route::get('my-purchase', 'MyPanelController@myPurchase')->name('my_purchase.index');

            Route::get('my-purchase/datatable', 'MyPanelController@myPurchaseDatatable')->name('my_purchase.datatable');



            Route::get('my-store-purchase-order-detail/{id}', 'MyPanelController@my_store_purchase_order_detail')->name('my_store_purchase_order_detail');

            Route::get('my-virtual-file-download/{id}', 'MyPanelController@my_virtual_file_download')->name('my_virtual_file_download');

            Route::get('my-virtual-file/{slug}', 'MyPanelController@downloadVirtualFile')->name('downloadVirtualFile');



            Route::get('refund-instructor-make-request/{id}', 'MyPanelController@refund_instructor_make_request')->name('instructor_make_request');



            Route::get('my-refund-dispute', 'MyPanelController@myRefundDispute')->name('store_refund_dispute.index');

            Route::get('my-refund-dispute/datatable', 'MyPanelController@myRefundDisputeDatatable')->name('myRefundDispute.datatable');



            Route::get('instructor-refund-details/{id}', 'MyPanelController@instructor_refund_show')->name('instructor_refund_order_detail');



            Route::post('change-receive-status-by-customer', 'MyPanelController@changeReceiveStatusByCustomer')->name('change_receive_status_by_customer');



            Route::post('instructor-refund-make-request-store', 'MyPanelController@instructor_refund_make_request_store')->name('instructor_refund_make_request_store');



            Route::get('my-refund', 'MyPanelController@myRefund')->name('my_refund.index');

            Route::get('my-refund/datatable', 'MyPanelController@myRefundeDatatable')->name('my_refund.datatable');



            Route::get('my-noticeboard', 'MyPanelController@myNoticeboard')->name('myNoticeboard');

            Route::get('show-noticeboard/{id}', 'MyPanelController@showNoticeboard')->name('showNoticeboard');

        });

    });





Route::prefix('{tenant_slug}')

    ->middleware('tenant.slug', 'restore.tenant.login')

    ->group(function () {



        Route::group([

            'namespace'  => 'Admin',

            'prefix'     => 'communication',

            'as'         => 'tenant.communication.',

            'middleware' => ['auth', 'admin'],

        ], function () {



            Route::get('private-messages', 'CommunicationController@PrivateMessage')

                ->name('PrivateMessage')

                ->middleware('RoutePermissionCheck:communication.PrivateMessage');



            Route::get('questions-answer', 'CommunicationController@QuestionAnswer')

                ->name('QuestionAnswer')

                ->middleware('RoutePermissionCheck:communication.QuestionAnswer');



            Route::any('StorePrivateMessage', 'CommunicationController@StorePrivateMessage')

                ->name('StorePrivateMessage')

                ->middleware('RoutePermissionCheck:communication.send');



            Route::post('getMessage', 'CommunicationController@getMessage')

                ->name('getMessage');

        });



});







Route::prefix('{tenant_slug}')

    ->middleware('tenant.slug', 'restore.tenant.login')

    ->group(function () {

        Route::get('change-language/{language_code}', 'UserController@changeLanguage')->name('changeLanguage');



        Route::get('change-theme/{color}', 'UserController@changeDarkMode')->name('changeDarkMode');

        Route::get('change-menu/{menu}', 'UserController@changeMenuStyle')->name('changeMenuStyle');



        Route::get('change-currency/{id}', 'UserController@changeCurrency')->name('changeCurrency');



        Route::get('secret-login/{user_id}', 'UserController@secretLogin')->name('secretLogin');

        Route::get('secret-login-exit', 'UserController@secretLoginExit')->name('secretLoginExit');

        

    });

    

Route::prefix('filepond/api')->group(function () {



    Route::get('/process', 'FilepondController@load')->name('filepond.load');



    Route::post('/process', 'FilepondController@upload')->name('filepond.upload');



    Route::patch('/process', 'FilepondController@chunk')->name('filepond.chunk');



    Route::delete('/process', 'FilepondController@delete')->name('filepond.delete');



});

Route::get('get_preview_modal/{id}', 'AjaxController@get_preview_modal')->name('get_preview_modal');

Route::get('ajaxGetSubCategoryList', 'AjaxController@ajaxGetSubCategoryList')->name('ajaxGetSubCategoryList');

Route::get('ajaxGetSecondarySkillList', 'AjaxController@ajaxGetSecondarySkillList')->name('ajaxGetSecondarySkillList');

Route::get('ajaxGetDepartmentList', 'AjaxController@ajaxGetDepartmentList')->name('ajaxGetDepartmentList');

Route::get('ajaxGetBuList', 'AjaxController@ajaxGetBuList')->name('ajaxGetBuList');

Route::get('ajaxGetOverAllList', 'AjaxController@ajaxGetOverAllList')->name('ajaxGetOverAllList');

Route::get('ajaxGetDepartmentWithBUList', 'AjaxController@ajaxGetDepartmentWithBUList')->name('ajaxGetDepartmentWithBUList');





Route::get('ajaxGetCourseList', 'AjaxController@ajaxGetCourseList')->name('ajaxGetCourseList');

Route::get('ajaxGetQuizList', 'AjaxController@ajaxGetQuizList')->name('ajaxGetQuizList');

Route::get('update-activity', 'AjaxController@updateActivity')->name('updateActivity');

Route::get('get_preview_modal/{id}', 'AjaxController@get_preview_modal')->name('get_preview_modal');

Route::get('get_cart_price', 'AjaxController@get_cart_price')->name('get_cart_price');



Route::post('summer-note-file-upload', 'UploadFileController@upload_image')->name('summerNoteFileUpload');





//auth adding

Route::get('auth/social', 'Auth\LoginController@showLoginForm')->name('social.login');

Route::get('oauth/{driver}', 'Auth\LoginController@redirectToProvider')->name('social.oauth');

Route::get('oauth/{driver}/callback', 'Auth\LoginController@handleProviderCallback')->name('social.callback');



Route::get('vimeo/video/{vimeo_id}', 'Frontend\WebsiteController@vimeoPlayer')->name('vimeoPlayer');

Route::get('scorm/video/{lesson_id}/{id}', 'Frontend\WebsiteController@scormPlayer')->name('scormPlayer');

Route::get('document/video/{lesson_id}', 'Frontend\WebsiteController@documentPlayer')->name('documentPlayer');

Route::get('get-dynamic-data', 'Frontend\ThemeDynamicData')->name('getDynamicData');

Route::get('read-some-part-of-books/{id}', 'Frontend\WebsiteController@readSomePartOfBooks')->name('readSomePartOfBooks');



Route::prefix('scorm')->as('scorm.')->middleware('auth')->group(function () {

    Route::get('/', 'SCORMController@index');

    Route::get('/viewer/{version}/{link}', 'SCORMController@viewer')->name('viewer');

    Route::get('/action', 'SCORMController@action')->name('action');

    Route::post('/statement', 'SCORMController@statement')->name('statement');

    Route::post('/report-store', 'SCORMReportController@store')->name('report.store');

    Route::get('/report', 'SCORMReportController@index')->name('report.index')->middleware('RoutePermissionCheck:scorm.report.index');

    Route::get('/report-data', 'SCORMReportController@data')->name('report.data')->middleware('RoutePermissionCheck:scorm.report.index');

    Route::get('/report-details/{id}', 'SCORMReportController@details')->name('report.details')->middleware('RoutePermissionCheck:scorm.report.index');

    Route::get('/report-lesson-details/{user_id}/{lesson_id}', 'SCORMReportController@lessonDetails')->name('report.lesson-details')->middleware('RoutePermissionCheck:scorm.report.index');

});



Route::prefix('calendar')->group(function () {

    Route::get('/', 'CalendarController@index')->name('calendar_show')->middleware('RoutePermissionCheck:calendar_show');

    Route::post('/', 'CalendarController@store')->name('calendar_store');

    Route::post('/update', 'CalendarController@update')->name('calendar_update');

    Route::get('/delete/{id}', 'CalendarController@destroy')->name('calendar_delete');

});



Route::group(['prefix' => 'communicate', 'middleware' => ['auth']], function () {

    //Event



    Route::get('event', 'EventController@index')->name('event')->middleware('RoutePermissionCheck:event');

    Route::get('add-event', 'EventController@create')->name('event_create')->middleware('RoutePermissionCheck:event_create');

    Route::post('event', 'EventController@store')->name('event-store')->middleware('RoutePermissionCheck:event_create');

    Route::get('event/{id}', 'EventController@edit')->name('event-edit')->middleware('RoutePermissionCheck:event-edit');

    Route::put('event/{id}', 'EventController@update')->name('event-update')->middleware('RoutePermissionCheck:event-edit');

    Route::get('delete-event-view/{id}', 'EventController@deleteEventView')->name('delete-event-view');

    Route::get('delete-event/{id}', 'EventController@deleteEvent')->name('delete-event')->middleware('RoutePermissionCheck:delete-event');;

//    Route::get('download-event-document/{file_name}', 'EventController@download')->name('delete-event')->middleware('RoutePermissionCheck:event');





});



// Route::group(['prefix' => 'admin', 'middleware' => ['auth']], function () {

//     Route::group(['prefix' => 'corporate-skills'], function () {

//         Route::get('/', [CorporateSkillController::class, 'index'])->name('corporate.skills.index');

//         Route::post('/assign', [CorporateSkillController::class, 'assignSkill'])->name('corporate.skills.assign');

//         Route::get('/remove/{userId}/{skillId}', [CorporateSkillController::class, 'removeSkill'])->name('corporate.skills.remove');

//     });

// });



// Route::group(['prefix' => 'admin/corporate-role-skills', 'middleware' => ['auth']], function () {

//     Route::get('/', [CorporateRoleSkillController::class, 'index'])->name('corporate.role.skills.index');

//     Route::post('/assign', [CorporateRoleSkillController::class, 'assign'])->name('corporate.role.skills.assign');

//     Route::get('/remove/{roleId}/{skillId}', [CorporateRoleSkillController::class, 'remove'])->name('corporate.role.skills.remove');

// });



// Route::prefix('admin/corporate-skills/library')->middleware(['auth'])->group(function () {

//     Route::get('/', [\App\Http\Controllers\CorporateSkillLibraryController::class, 'index'])->name('corporate.skills.library');

//     Route::post('/store', [\App\Http\Controllers\CorporateSkillLibraryController::class, 'store'])->name('corporate.skills.store');

//     Route::post('/update/{id}', [\App\Http\Controllers\CorporateSkillLibraryController::class, 'update'])->name('corporate.skills.update');

//     Route::get('/delete/{id}', [\App\Http\Controllers\CorporateSkillLibraryController::class, 'destroy'])->name('corporate.skills.delete');

// });



Route::prefix('{tenant_slug}')

    ->middleware('tenant.slug', 'restore.tenant.login')

    ->group(function () {

    Route::group([

            'namespace'  => 'Admin',

            'prefix'     => 'skills',

            'middleware' => ['auth'],

        ], function () {

    // Skills Library

    Route::get('/', [CorporateSkillController::class, 'index'])->name('skills.index');

    Route::post('/store', [CorporateSkillController::class, 'store'])->name('skills.store');

    Route::post('/update/{id}', [CorporateSkillController::class, 'update'])->name('skills.update');

    Route::get('/delete/{id}', [CorporateSkillController::class, 'destroy'])->name('skills.delete');



    // Role Skill Requirements

    Route::get('/roles', [CorporateRoleSkillController::class, 'index'])->name('skills.roles');

    Route::post('/roles/assign', [CorporateRoleSkillController::class, 'assignSkill'])->name('skills.roles.assign');

    Route::get('/roles/remove/{role}/{skill}', [CorporateRoleSkillController::class, 'removeSkill'])->name('skills.roles.remove');



    // Employee Skills

    Route::get('/employees', [CorporateEmployeeSkillController::class, 'index'])->name('skills.employees');

    Route::post('/employees/store', [CorporateEmployeeSkillController::class, 'store'])->name('skills.employees.store');

    Route::get('/employees/remove/{user}/{skill}', [CorporateEmployeeSkillController::class, 'remove'])->name('skills.employees.remove');



    // Analytics

    Route::get('/analytics', [CorporateSkillAnalyticsController::class, 'index'])->name('skills.analytics');



    // Training

    Route::get('/training', [CorporateTrainingController::class, 'index'])->name('skills.training');

});

    });



    



Route::prefix('{tenant_slug}')

    ->middleware('tenant.slug', 'restore.tenant.login')

    ->group(function () {



        Route::middleware(['auth'])

            ->prefix('attendance')

            ->group(function () {



                Route::get('/sessions',

                    [AttendanceSessionController::class, 'index']

                )->name('attendance.sessions.index');



                // Route::get('/sessions/create',

                //     [AttendanceSessionController::class, 'create']

                // )->name('attendance.sessions.create');



                // Route::post('/sessions/store',

                //     [AttendanceSessionController::class, 'store']

                // )->name('attendance.sessions.store');



                Route::get('/sessions/{id}/mark',

                [AttendanceSessionController::class, 'mark']

                )->name('attendance.sessions.mark');

                

                Route::post('/sessions/{id}/save',

                [AttendanceSessionController::class, 'saveAttendance']

                )->name('attendance.sessions.save');



                Route::get('/sessions/{id}/generate-qr', 

                [AttendanceSessionController::class, 'generateQr']

                )->name('attendance.sessions.generateQr');

                



                Route::post('/sessions/{id}/qr-submit',

                [AttendanceSessionController::class, 'qrSubmit']

                )->name('attendance.sessions.qrSubmit');



                Route::get('/sessions/{id}/view-attendance',

                [AttendanceSessionController::class, 'viewAttendance']

                )->name('attendance.sessions.view');



                Route::get('/sessions/{id}/import',

                [AttendanceSessionController::class, 'importPage']

                )->name('attendance.sessions.import.page');



                Route::post('/sessions/{id}/import',

                [AttendanceSessionController::class, 'importAttendance']

                )->name('attendance.sessions.import');





            });



});





Route::prefix('{tenant_slug}')

    ->middleware('tenant.slug', 'restore.tenant.login')

    ->group(function () {

                Route::get('attendance/scan/{token}', 

                [AttendanceSessionController::class, 'scanQr']

                )->name('attendance.scan');



});



