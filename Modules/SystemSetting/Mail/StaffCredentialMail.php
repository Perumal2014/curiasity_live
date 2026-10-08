<?php

namespace Modules\StudentSetting\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StaffCredentialsMail extends Mailable
{
    use Queueable, SerializesModels;
    
    public string $username;
    public string $password;

    /**
     * Create a new message instance.
     */
    public function __construct(string $username, string $password)
    {
        $this->username = $username;
        $this->password = $password;

       
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        //dd($this->username);
        return $this->subject('Your LMS Login Credentials')
            ->view('systemsetting::emails.staff_credentials_mail');
    }
}
