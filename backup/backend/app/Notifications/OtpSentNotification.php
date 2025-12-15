<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OtpSentNotification extends Notification
{
    use Queueable;
    protected $otp;

    /**
     * Create a new notification instance.
     */
    public function __construct($otp)
    {
        //
        
        $this->otp = $otp;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
          return (new MailMessage)
                    ->subject('Your OTP Code')
                    ->line("Your OTP code is: {$this->otp}")
                    ->line('Use this to activate your account.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    // public function toArray(object $notifiable): array
    // {
    //     return [
    //         //
    //     ];
    // }
      public function toDatabase($notifiable)
    {
        return [
            'message' => "Your request was approved. OTP sent to your email.",
            'otp' => $this->otp,
            'request_id' => $notifiable->id, // or $this->otpRequestId
        ];
    }
}
