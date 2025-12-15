<?php

namespace App\Jobs;
use App\Mail\OtpMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Email;

class SendOtpEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $email;
    public $otpPlain;
    public $citizenRequest;
    public $otpRecord;
    /**
     * Create a new job instance.
     */
    public function __construct($email,$otpPlain,$citizenRequest,$otpRecord)
    {
        //
        $this->email=$email;
        $this->otpPlain=$otpPlain;
        $this->citizenRequest=$citizenRequest;
        $this->otpRecord=$otpRecord;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
       \Mail::to($this->citizenRequest->email)
         ->send(new OtpMail($this->otpPlain, $this->citizenRequest));

    }
}
