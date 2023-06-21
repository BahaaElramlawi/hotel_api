<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Auth\ForgotPasswordRequest;
use App\Http\Requests\User\Auth\ResetPasswordRequest;
use App\Http\Requests\User\Auth\VerifyCodeRequest;
use App\Services\User\Auth\ForgotPasswordService;

class ForgotPasswordController extends Controller
{
    protected $userForgotPasswordService;

    public function __construct(ForgotPasswordService $userForgotPasswordService)
    {
        $this->userForgotPasswordService = $userForgotPasswordService;
    }

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $response = $this->userForgotPasswordService->sendVerificationCode($request->email);

        return response()->json($response, $response['status'] ? 200 : 500);
    }

    public function verifyCode(VerifyCodeRequest $request)
    {
        $response = $this->userForgotPasswordService->verifyCode($request->email, $request->verificationCode);

        return response()->json($response, $response['status'] ? 200 : 500);
    }


    public function reset(ResetPasswordRequest $request)
    {
        $response = $this->userForgotPasswordService->resetPassword($request->email, $request->newPassword);

        return response()->json($response, $response['status'] ? 200 : 500);
    }

}
