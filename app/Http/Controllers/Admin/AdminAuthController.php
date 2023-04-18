<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Check email and password
        $admin = Admin::where('email', $request->email)->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => 'Invalid email or password'
            ], 401);
        }

        // Generate token for authenticated admin
        $token = $admin->createToken('myapptoken')->plainTextToken;

        return response()->json([
            'status' => true,
            'data' => [
                'admin' => $admin,
                'token' => $token
            ],
            'message' => 'Login successful'
        ], 201);
    }


    public function logout(Request $request)
    {
        if (!auth()->check()) {
            return response([
                'status' => false,
                'data' => null,
                'message' => 'Not authenticated'
            ], 401);
        }

        $user = auth()->user();

        if (!$user instanceof Admin) {
            return response([
                'status' => false,
                'data' => null,
                'message' => 'User is not an admin'
            ], 403);
        }

        try {
            // revoke the token for the authenticated admin
            $user->tokens()->delete();

            $response = [
                'status' => true,
                'data' => null,
                'message' => 'Logged out'
            ];
        } catch (\Exception $e) {
            $response = [
                'status' => false,
                'data' => null,
                'message' => 'Logout failed: ' . $e->getMessage()
            ];
        }

        return response($response, $response['status'] ? 200 : 500);
    }
}

