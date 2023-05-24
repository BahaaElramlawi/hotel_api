<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Auth\LoginRequest;
use App\Services\Admin\Auth\AdminAuthService;


class AdminAuthController extends Controller
{
    protected $adminAuthService;

    public function __construct(AdminAuthService $adminAuthService)
    {
        $this->adminAuthService = $adminAuthService;
    }

    public function login(LoginRequest $request, AdminAuthService $adminAuthService)
    {
        return $adminAuthService->login($request);
    }


    public function logout()
    {
        return $this->adminAuthService->logout();
    }
}
