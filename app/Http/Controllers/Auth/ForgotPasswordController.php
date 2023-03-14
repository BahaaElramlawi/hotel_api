<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Validation\Rules\Password as RulesPassword;

class ForgotPasswordController extends Controller
{


    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status == Password::RESET_LINK_SENT) {
            return response([
                'status' => 'success',
                'message' => __('A reset link has been sent to your email address.')
            ], 200);
        }

        return response([
            'status' => 'error',
            'message' => __('Unable to send reset link'),
        ], 500);
    }


//    public function reset(Request $request)
//    {
//        $request->validate([
//            'token' => 'required',
//            'email' => 'required|email',
//            'password' => ['required', RulesPassword::defaults()],
//        ]);
//
//        $status = Password::reset(
//            $request->only('email', 'password', 'password_confirmation', 'token'),
//            function ($user) use ($request) {
//                $user->forceFill([
//                    'password' => Hash::make($request->password),
//                    'remember_token' => Str::random(60),
//                ])->save();
//
//                $user->tokens()->delete();
//
//                event(new PasswordReset($user));
//            }
//        );
//
//        if ($status == Password::PASSWORD_RESET) {
//            return response([
//                'status' => 'success',
//                'message' => 'Password reset successfully'
//            ]);
//        }
//
//        return response([
//            'status' => 'error',
//            'message' => __($status)
//        ], 500);
//
//    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', RulesPassword::defaults()],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                ])->save();

                $user->tokens()->delete();

                event(new PasswordReset($user));
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return response([
                'status' => 'success',
                'message' => 'Password reset successfully'
            ]);
        }

        return response([
            'status' => 'error',
            'message' => __($status)
        ], 500);

    }


}
