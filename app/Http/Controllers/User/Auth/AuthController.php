<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Auth\LoginRequest;
use App\Http\Requests\User\Auth\RegisterRequest;
use App\Http\Requests\User\Auth\UploadImageRequest;
use App\Services\User\Auth\AuthService;

class AuthController extends Controller
{
    protected $userService;

    public function __construct(AuthService $userService)
    {
        $this->userService = $userService;
    }

    public function register(RegisterRequest $request)
    {
        $response = $this->userService->createUser($request);

        return response()->json($response, $response['status'] ? 201 : 400);
    }

    public function uploadImage(UploadImageRequest $request)
    {
        $response = $this->userService->uploadImage($request);

        return response()->json($response, $response['status'] ? 200 : 404);
    }

    public function login(LoginRequest $request)
    {
        $response = $this->userService->loginUser($request);
        return response()->json($response, $response['status'] ? 200 : 401);
    }

    public function logout()
    {
        return $this->userService->logoutUser();
    }
}
