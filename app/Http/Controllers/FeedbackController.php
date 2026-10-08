<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Jobs\EventNotification;
use App\Traits\UploadMedia;
use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Models\LmsEvent;
use App\Http\Requests\EventRequest;
use Modules\RolePermission\Entities\Role;
use App\Models\FeedbackQuestion;
use App\Models\FeedbackSubmission;
use App\Models\FeedbackAnswers;
use App\Models\FeedbackForms;
use App\Models\FeedbackFormQuestions;
use Illuminate\Support\Facades\DB;

class FeedbackController extends Controller
{
    
    public function show($id)
    {
        // Temporary organization ID
        $org_id = 28;

        $feedbackform_id = (int) $id;

        /*
        |--------------------------------------------------------------------------
        | Get Feedback Form
        |--------------------------------------------------------------------------
        */

        $feedbackForm = FeedbackForms::where('id', $feedbackform_id)
            ->where('organization_id', $org_id)
            ->where('status', 1)
            ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | Get Questions Through Mapping Table
        |--------------------------------------------------------------------------
        */

        $feedbackquestions = FeedbackFormQuestions::where(
            'feedback_form_id',
            $feedbackform_id
        )
        ->with([
            'feedbackQuestion' => function ($query) {
                $query->where('status', 1);
            },
            'feedbackQuestion.options'
        ])
        ->orderBy('id', 'asc')
        ->get()
        ->filter(function ($mapping) {
            return $mapping->feedbackQuestion !== null;
        })
        ->map(function ($mapping) {
            return $mapping->feedbackQuestion;
        })
        ->values();


        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view(
            'feedback.show',
            compact(
                'feedbackquestions',
                'feedbackform_id',
                'feedbackForm'
            )
        );
    }

    public function submit(Request $request)
    {
        // print_r($request->all()); exit;

        $request->validate([
        'name'        => 'nullable|string|max:255',
        'email'       => 'nullable',
        'location'    => 'nullable|integer',
        'designation' => 'nullable|string|max:255',
        'department'  => 'nullable|string|max:255',
        'function'    => 'nullable|string|max:255',
        'comments'    => 'nullable|string',
        'answers'     => 'nullable|array',
    ]);

    /*
    |--------------------------------------------------------------------------
    | Check duplicate email only when email is provided
    |--------------------------------------------------------------------------
    */
    

    DB::beginTransaction();

    try {

        /*
        |--------------------------------------------------------------------------
        | Store Employee / User Information
        |--------------------------------------------------------------------------
        */
        $feedbackSubmission = FeedbackSubmission::create([
            'name' => $request->name ?: null,

            'email' => $request->email ?: null,

            'location' => $request->location ?: null,

            'designation' => $request->designation ?: null,

            'department' => $request->department ?: null,

            'function_name' => $request->function ?: null,

            'user_id' => 10,

            'comments' => $request->comments ?: null,

            'feedback_form_id' => $request->feedbackform_id,

            'submitted_at' => now(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Store Answers
        |--------------------------------------------------------------------------
        */
        $feedbackAnswers = [];

        foreach ($request->input('answers', []) as $questionId => $answer) {

            // Checkbox can return an array
            if (is_array($answer)) {
                $answer = json_encode($answer);
            }

            $feedbackAnswers[] = [
                'feedback_submission_id' => $feedbackSubmission->id,

                'question_id' => $questionId,

                'answer' => $answer,

                'created_at' => now(),

                'updated_at' => now(),
            ];
        }

        if (!empty($feedbackAnswers)) {
            FeedbackAnswers::insert($feedbackAnswers);
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Thank you! Your feedback has been submitted successfully.',
        ]);

    } catch (\Throwable $e) {

        DB::rollBack();

        return response()->json([
            'success' => false,
            'message' => 'Unable to submit feedback. Please try again.',
        ], 500);
    }
}

    public function thankyou()
    {
        return view('feedback.thankyou');
    }

    public function checkEmail1(Request $request)
    {
        dd($request->email);
        $exists = FeedbackSubmission::where('email', $request->email)->exists();

        // dd($exists);

        return response()->json([
            'exists' => $exists,
        ]);
    }

    public function checkEmail(Request $request)
    {
        \Log::info('CHECK EMAIL REQUEST', [
            'all' => $request->all(),
            'email' => $request->input('email'),
        ]);

        $email = trim($request->input('email'));

        if (!$email) {
            return response()->json([
                'exists' => false,
                'message' => 'Email is required.'
            ], 422);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return response()->json([
                'exists' => false,
                'message' => 'Please enter a valid email address.'
            ], 422);
        }

        $exists = FeedbackSubmission::where('email', $email)->exists();

        return response()->json([
            'exists' => $exists,
            'message' => $exists
                ? 'Email exists.'
                : 'This email address does not exist.'
        ]);
    }
}