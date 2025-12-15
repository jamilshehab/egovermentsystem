<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $otpCode;
    public string $citizenName;

    public function __construct(string $otpCode, string $citizenName)
    {
        $this->otpCode = $otpCode;
        $this->citizenName = $citizenName;
    }

    public function build()
    {
        // Use HTML directly, no Blade
        return $this->subject('Your OTP Code')
                    ->html("
                        <p>Hello {$this->citizenName},</p>
                        <p>Your OTP code is: <strong>{$this->otpCode}</strong></p>
                        <p>This code expires in 10 minutes.</p>
                    ");
    }
}
