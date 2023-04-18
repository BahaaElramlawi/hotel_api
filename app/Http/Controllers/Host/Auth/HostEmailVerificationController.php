<?php

namespace App\Http\Controllers\Host\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class HostEmailVerificationController extends Controller
{

    public function sendVerificationEmail(Request $request)
    {
        try {
            $host = Auth::user();
            if ($host->hasVerifiedEmail()) {
                $response = [
                    'status' => false,
                    'data' => null,
                    'message' => 'Email already verified'
                ];
                return response($response, 400);
            } else {
                // Set the sender information to the host's email address and name
                Mail::alwaysFrom($host->email, $host->name);

                $host->sendEmailVerificationNotification();
                $response = [
                    'status' => true,
                    'data' => null,
                    'message' => 'Verification email sent'
                ];
                return response($response, 200);
            }
        } catch (\Exception $e) {
            $response = [
                'status' => false,
                'data' => null,
                'message' => 'Failed to send verification email: ' . $e->getMessage()
            ];
            return response($response, 500);
        }
    }

    public function verify(EmailVerificationRequest $request)
    {
        $host = Auth::user();
        if ($host->hasVerifiedEmail()) {
            return response([
                'status' => false,
                'data' => null,
                'message' => 'Email already verified'
            ], 400);
        }

        if ($host->markEmailAsVerified()) {
            // Set the sender information to the host's email address and name
            Mail::alwaysFrom($host->email, $host->name);

            event(new Verified($host));
            return response([
                'status' => true,
                'data' => null,
                'message' => 'Email has been verified'
            ], 200);
        }

        return response([
            'status' => false,
            'data' => null,
            'message' => 'Failed to verify email'
        ], 500);
    }
}
