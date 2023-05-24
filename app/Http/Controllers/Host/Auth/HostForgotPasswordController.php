<?php

namespace App\Http\Controllers\Host\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Host\Auth\ForgotPasswordRequest;
use App\Http\Requests\Host\Auth\ResetPasswordRequest;
use App\Services\Host\Auth\HostForgotPasswordService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class HostForgotPasswordController extends Controller
{
    protected $hostForgotPasswordService;

    public function __construct(HostForgotPasswordService $hostForgotPasswordService)
    {
        $this->hostForgotPasswordService = $hostForgotPasswordService;
    }

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $response = $this->hostForgotPasswordService->sendResetLink($request->email);

        return response()->json($response, $response['status'] ? 200 : 500);
    }

    public function reset(ResetPasswordRequest $request)
    {
        $response = $this->hostForgotPasswordService->resetPassword($request->email, $request->token, $request->password);
        return response()->json($response, $response['status'] ? 200 : 500);
    }

}



