<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\SystemSetting\Entities\EmailTemplate;
use Modules\NotificationSetup\Entities\RoleEmailTemplate;

return new class extends Migration {

    public function up()
    {
        $course_assignment_template = EmailTemplate::where('act', 'Communicate_Email')->first();
        if (!$course_assignment_template) {
            $course_assignment_template = new EmailTemplate();
            $course_assignment_template->act = 'Communicate_Email';
        }
        $shortCode = '';
        $subject = 'Communicate Email Template';

        $body = ' This is sample email template. {{footer}}  ';
        $course_assignment_template->name = $subject;
        $course_assignment_template->subj = $subject;
        $course_assignment_template->shortcodes = $shortCode;
        $course_assignment_template->status = 1;
        $course_assignment_template->is_system = 0;
        $course_assignment_template->browser_message = '';

        $course_assignment_template->email_body = htmlPart($subject, $body);
        $course_assignment_template->save();

    }

    public function down()
    {
        //
    }

};
