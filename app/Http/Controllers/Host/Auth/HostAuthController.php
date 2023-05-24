<?php

namespace App\Http\Controllers\Host\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Host\Auth\LoginRequest;
use App\Http\Requests\Host\Auth\RegisterRequest;
use App\Models\Host;
use App\Services\Host\Auth\HostAuthService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class HostAuthController extends Controller
{
    protected $hostAuthService;

    public function __construct(HostAuthService $hostAuthService)
    {
        $this->hostAuthService = $hostAuthService;
    }

    public function register(RegisterRequest $request)
    {
        $response = $this->hostAuthService->createHost($request);
        return response()->json($response, $response['status'] ? 201 : 400);
    }

    public function login(LoginRequest $request)
    {
        $validated = $request->validated();
        $response = $this->hostAuthService->loginHost($validated['email'], $validated['password']);
        return response()->json($response, $response['status'] ? 200 : 401);
    }

    public function logout()
    {
        return $this->hostAuthService->logoutHost();
    }

}
