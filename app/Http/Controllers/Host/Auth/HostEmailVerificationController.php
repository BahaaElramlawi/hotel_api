<?php

namespace App\Http\Controllers\Host\Auth;

use App\Http\Controllers\Controller;
use App\Services\Host\Auth\HostEmailVerificationService;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class HostEmailVerificationController extends Controller
{

    protected $hostEmailVerificationService;

    public function __construct(HostEmailVerificationService $hostEmailVerificationService)
    {
        $this->hostEmailVerificationService = $hostEmailVerificationService;
    }

    public function sendVerificationEmail()
    {
        $response = $this->hostEmailVerificationService->sendVerificationEmail();
        return response()->json($response, $response['status'] ? 201 : 400);
    }

    public function verify()
    {
        $response = $this->hostEmailVerificationService->verifyEmail();
        return response()->json($response, $response['status'] ? 201 : 400);
    }
}
