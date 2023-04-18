<?php

namespace App\Http\Controllers\Host\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Validation\Rules\Password as RulesPassword;


class HostForgotPasswordController extends Controller
{
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $status = Password::broker('hosts')->sendResetLink(['email' => $request->email]);


        if ($status == Password::RESET_LINK_SENT) {
            return response([
                'status' => true,
                'data' => null,
                'message' => __('A reset link has been sent to your email address.')
            ], 200);
        }

        return response([
            'status' => false,
            'data' => null,
            'message' => __('Unable to send reset link'),
        ], 500);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', RulesPassword::defaults()],
        ]);

        $status = Password::broker('hosts')->reset($request->only(
            'email', 'password', 'password_confirmation', 'token'
        ), function ($user, $password) {
            $user->password = Hash::make($password);
            $user->save();
        });

        if ($status == Password::PASSWORD_RESET) {
            return response([
                'status' => true,
                'data' => null,
                'message' => __('Your password has been reset.'),
            ], 200);
        }

        return response([
            'status' => false,
            'data' => null,
            'message' => __('Unable to reset password'),
        ], 500);
    }
}



