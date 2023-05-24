<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Admin\UserManagementService;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    protected $userService;

    public function __construct(UserManagementService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $response = $this->userService->getUsers($perPage);

        return response()->json($response, $response['status'] ? 200 : 404);
    }

    public function show($id)
    {
        return $this->userService->getUserByID($id);
    }

    public function destroy($id)
    {
        $response = $this->userService->deleteUserByID($id);

        return response($response, $response['status'] ? 200 : 404);
    }

}
