<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class EmailVerificationController extends Controller
{
    public function sendVerificationEmail(Request $request)
    {
        try {
            $user = Auth::user();
            if ($user->hasVerifiedEmail()) {
                $response = [
                    'status' => 'error',
                    'data' => null,
                    'message' => 'Email already verified'
                ];
                return response($response, 400);
            } else {
                // Set the sender information to the user's email address and name
                Mail::alwaysFrom($user->email, $user->name);

                $user->sendEmailVerificationNotification();
                $response = [
                    'status' => 'success',
                    'data' => null,
                    'message' => 'Verification email sent'
                ];
                return response($response, 200);
            }
        } catch (\Exception $e) {
            $response = [
                'status' => 'error',
                'data' => null,
                'message' => 'Failed to send verification email: ' . $e->getMessage()
            ];
            return response($response, 500);
        }
    }


    public function verify(EmailVerificationRequest $request)
    {
        $user = Auth::user();
        if ($user->hasVerifiedEmail()) {
            return response([
                'status' => 'error',
                'data' => null,
                'message' => 'Email already verified'
            ], 400);
        }

        if ($user->markEmailAsVerified()) {
            // Set the sender information to the user's email address and name
            Mail::alwaysFrom($user->email, $user->name);

            event(new Verified($user));
            return response([
                'status' => 'success',
                'data' => null,
                'message' => 'Email has been verified'
            ], 200);
        }

        return response([
            'status' => 'error',
            'data' => null,
            'message' => 'Failed to verify email'
        ], 500);
    }
}

