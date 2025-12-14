<?php
namespace App\Services;

use App\Models\Otp;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use App\Jobs\SendOtpEmailJob;

class OtpService
{
    public function generateNumeric($length = 6)
    {
        $min = (int) str_repeat('0', $length);
        $max = (int) str_repeat('9', $length);
        // Ensure leading zeros possible:
        return str_pad((string) random_int(0, (int) ('1' . str_repeat('0', $length-1)) - 1), $length, '0', STR_PAD_LEFT);
    }

    public function hashOtp(string $otp): string
    {
        // Use HMAC with app key for deterministic verification-friendly hash OR Hash::make for bcrypt.
        // We'll use hash_hmac for faster comparison with hash_equals.
        return hash_hmac('sha256', $otp, config('app.key'));
    }

    public function createForRequest($citizenRequest, $length = 6, $ttlMinutes = 10, $sentVia = 'azure_graph')
    {
        $plain = $this->generateNumeric($length);
        $hash = $this->hashOtp($plain);
        $expiresAt = now()->addMinutes($ttlMinutes);

        $otp = Otp::create([
            'citizen_request_id' => $citizenRequest->id,
            'otp_hash' => $hash,
            'expires_at' => $expiresAt,
            'sent_via' => $sentVia,
        ]);

        // Dispatch job to send email (queued)
        SendOtpEmailJob::dispatch($citizenRequest->citizen_email, $plain, $citizenRequest, $otp);

        return $otp;
    }

    public function verify($otpRecord, $providedOtp, $maxAttempts = 5)
    {
        if ($otpRecord->is_used) {
            return ['status' => false, 'reason' => 'used'];
        }

        if ($otpRecord->attempts >= $maxAttempts) {
            return ['status' => false, 'reason' => 'locked'];
        }

        if (now()->greaterThan($otpRecord->expires_at)) {
            return ['status' => false, 'reason' => 'expired'];
        }

        $providedHash = $this->hashOtp($providedOtp);

        // timing-safe comparison
        if (hash_equals($otpRecord->otp_hash, $providedHash)) {
            $otpRecord->is_used = true;
            $otpRecord->save();
            return ['status' => true];
        }

        $otpRecord->increment('attempts');
        return ['status' => false, 'reason' => 'invalid'];
    }
}
