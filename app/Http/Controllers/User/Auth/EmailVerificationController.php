<?php

namespace App\Http\Controllers\User\Auth;


use App\Http\Controllers\Controller;
use App\Services\User\Auth\EmailVerificationService;

class EmailVerificationController extends Controller
{

    protected $userEmailVerificationService;

    public function __construct(EmailVerificationService $userEmailVerificationService)
    {
        $this->userEmailVerificationService = $userEmailVerificationService;
    }

    public function sendVerificationEmail()
    {
        $response = $this->userEmailVerificationService->sendVerificationEmail();
        return response()->json($response, $response['status'] ? 201 : 400);
    }

    public function verify()
    {
        $response = $this->userEmailVerificationService->verifyEmail();
        return response()->json($response, $response['status'] ? 201 : 400);
    }
}
