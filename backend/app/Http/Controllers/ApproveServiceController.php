<?php

namespace App\Http\Controllers;

use App\Mail\OtpMail;
use App\Models\CitizenRequest;
use App\Models\Otp;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ApproveServiceController extends Controller
{
    /**
     * Get all pending requests
     */
    public function index()
    {
        $pending = CitizenRequest::where('status', 'pending')->get();

        return response()->json([
            'message' => 'Pending services',
            'data' => $pending
        ]);
    }

    /**
     * Approve request and send OTP
     */
    public function update(Request $request, $id)
    {
        $citizenRequest = CitizenRequest::findOrFail($id);

        if ($citizenRequest->status === 'approved') {
            return response()->json([
                'message' => 'Request already approved'
            ], 409);
        }

        // Approve request
        $citizenRequest->status = 'approved';
        $citizenRequest->save();

        // Invalidate previous OTPs
        Otp::where('citizen_request_id', $citizenRequest->id)
            ->where('is_used', false)
            ->update(['is_used' => true]);

        // Generate OTP
        $otpPlain = random_int(100000, 999999);

        // Store OTP (hashed)
        Otp::create([
            'citizen_request_id' => $citizenRequest->id,
            'otp_hash' => bcrypt($otpPlain),
            'expires_at' => Carbon::now()->addMinutes(10),
            'sent_via' => 'azure_graph',
        ]);

        // Send OTP email to citizen
        try {
            Mail::to($citizenRequest->email)
                ->send(new OtpMail($otpPlain, $citizenRequest->name));
        } catch (\Throwable $e) {
            \Log::error('OTP mail failed', [
                'error' => $e->getMessage(),
                'citizen_email' => $citizenRequest->email,
            ]);

            return response()->json([
                'message' => 'Request approved, but OTP email failed'
            ], 500);
        }

        return response()->json([
            'message' => 'Service approved and OTP sent'
        ]);
    }

    /**
     * Verify OTP
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'citizen_request_id' => 'required|exists:citizen_requests,id',
            'otp' => 'required|string',
        ]);

        $otp = Otp::where('citizen_request_id', $request->citizen_request_id)
            ->where('is_used', false)
            ->latest()
            ->first();

        if (!$otp) {
            return response()->json(['message' => 'OTP not found'], 404);
        }

        if ($otp->expires_at->isPast()) {
            return response()->json(['message' => 'OTP expired'], 403);
        }

        if ($otp->attempts >= 5) {
            return response()->json([
                'message' => 'OTP locked due to too many attempts'
            ], 429);
        }

        if (!Hash::check($request->otp, $otp->otp_hash)) {
            $otp->increment('attempts');
            return response()->json(['message' => 'Invalid OTP'], 401);
        }

        // Mark OTP as used
        $otp->update(['is_used' => true]);

        return response()->json([
            'message' => 'OTP verified successfully'
        ]);
    }

    /**
     * Activate account after OTP verification
     */
    public function activateAccount(Request $request)
    {
        $request->validate([
            'citizen_request_id' => 'required|exists:citizen_requests,id',
            'username' => 'required|unique:users,username',
            'password' => 'required|min:8|confirmed',
        ]);

        $otp = Otp::where('citizen_request_id', $request->citizen_request_id)
            ->where('is_used', true)
            ->latest()
            ->first();

        if (!$otp) {
            return response()->json([
                'message' => 'OTP verification required'
            ], 403);
        }

        $citizenRequest = CitizenRequest::findOrFail($request->citizen_request_id);

        // Create user account
        $user = User::create([
            'name' => $request->name,
            'email' => $citizenRequest->email,
            'password' => Hash::make($request->password),
        ]);

        // Link request to user
        $citizenRequest->update([
            'user_id' => $user->id
        ]);

        return response()->json([
            'message' => 'Account activated successfully'
        ]);
    }

    /**
     * Decline request
     */
    public function destroy($id)
    {
        $service = CitizenRequest::findOrFail($id);
        $service->delete();

        return response()->json([
            'message' => 'Service declined'
        ]);
    }
}
