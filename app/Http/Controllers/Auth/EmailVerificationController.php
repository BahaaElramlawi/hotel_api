<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class EmailVerificationController extends Controller
{
    public function sendVerificationEmail(Request $request)
    {
        try {
            if ($request->user()->hasVerifiedEmail()) {
                $response = [
                    'status' => 'error',
                    'message' => 'Email already verified'
                ];
                return response($response, 400);
            } else {
                $request->user()->sendEmailVerificationNotification();
                $response = [
                    'status' => 'success',
                    'message' => 'Verification email sent'
                ];
                return response($response, 200);
            }
        } catch (\Exception $e) {
            $response = [
                'status' => 'error',
                'message' => 'Failed to send verification email: ' . $e->getMessage()
            ];
            return response($response, 500);
        }
    }


    public function verify(EmailVerificationRequest $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response([
                'status' => 'error',
                'message' => 'Email already verified'
            ], 400);
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
            return response([
                'status' => 'success',
                'message' => 'Email has been verified'
            ], 200);
        }

        return response([
            'status' => 'error',
            'message' => 'Failed to verify email'
        ], 500);
    }

}
