<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Modules\RolePermission\Entities\Role;
use Illuminate\Database\Migrations\Migration;
use Modules\SystemSetting\Entities\EmailTemplate;
use Modules\NotificationSetup\Entities\RoleEmailTemplate;

class AddEventEmailTemplate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $template = EmailTemplate::where('act', 'Event_Invitation')->first();
        if (!$template) {
            $template = new EmailTemplate();
            $template->act = 'Event_Invitation';
        }

        $shortCode = '{"name":"Student Name","event_title":"Event Title","event_host":"Host Name","event_url":"Event URL","start_date":"Start Date",
        "end_date":"End Date","start_time":"Start Time","end_time":"End Time","description":"Description,","event_location":"Event Location"}';
        $subject = 'Event Invitation';
        $br = "<br/>";
        $body = "Hello {{name}}," . $br . " You are invited to {{event_title}}. Which will be held on {{start_date}} to {{end_date}} at {{start_time}} to {{end_time}}." . $br . "
                {{description}}." . $br . "
                Event Host : {{event_host}}" . $br . "
                Event Location: {{event_location}}." . $br . "
                Event Link: {{event_url}}." . $br .
            "{{footer}}";
        $template->name = $subject;
        $template->subj = $subject;
        $template->browser_message = "You are invited to {{event_title}}. Which will be held on {{start_date}} to {{end_date}} at {{start_time}} to {{end_time}}." . $br . "
                {{description}}." . $br . "
                Event Host : {{event_host}}" . $br . "
                Event Location: {{event_location}}." . $br . "
                Event Link: {{event_url}}.";
        $template->shortcodes = $shortCode;
        $template->status = 1;

        $template->email_body = htmlPart($subject, $body);
        $template->save();

        $roles = Role::get();
        foreach ($roles as $key => $role) {
            $role_template = new RoleEmailTemplate();
            $role_template->role_id = $role->id;
            $role_template->template_act = 'Event_Invitation';
            $role_template->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
