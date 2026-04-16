<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    public $verificationCode;
    public $userType;

    public function __construct($token, $verificationCode, $userType)
    {
        $this->token = $token;
        $this->verificationCode = $verificationCode;
        $this->userType = $userType;
    }

    public function build()
    {
        return $this->subject('إعادة تعيين كلمة المرور')
                    ->view('emails.reset-password', [
                        'resetUrl' => route('password.reset', ['token' => $this->token, 'userType' => $this->userType]),
                        'verificationCode' => $this->verificationCode,
                    ]);
    }
}