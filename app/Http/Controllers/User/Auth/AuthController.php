<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{


    public function register(Request $request)
    {
        $fields = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'birthdate' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'phone_number' => 'required|string',
            'gender' => 'required|string',
            'image' => 'required|file',
            'password' => 'required|string'
        ]);
        // Get the uploaded image
        $image = $request->file('image');
        // Generate a unique file name
        $name = time() + rand(1, 1000000) . '.' . $image->getClientOriginalName();
        // Store the uploaded image in the public disk
        $path = $image->storeAs('public/images', $name);


        $user = User::create([
            'first_name' => $fields['first_name'],
            'last_name' => $fields['last_name'],
            'birthdate' => $fields['birthdate'],
            'email' => $fields['email'],
            'phone_number' => $fields['phone_number'],
            'gender' => $fields['gender'],
            'image' => $name,
            'password' => bcrypt($fields['password']),
        ]);

        if (!$user) {
            $response = [
                'status' => 'error',
                'data' => null,
                'message' => 'Registration failed'
            ];
            return response($response, 400);
        }

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'status' => 'success',
            'data' => [
                'user' => $user,
                'token' => $token
            ],
            'message' => 'Register successful'
        ];

        return response($response, 201);
    }

    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        // Check email
        $user = User::where('email', $fields['email'])->first();

        // Check password
        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'status' => 'error',
                'data' => null,
                'message' => 'Invalid email or password'
            ], 401);
        }

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'status' => 'success',
            'data' => [
                'user' => $user,
                'token' => $token
            ],
            'message' => 'Login successful'
        ];

        return response($response, 201);

    }

    public function logout(Request $request)
    {
        try {
            auth()->user()->tokens()->delete();
        } catch (Exception $e) {
            $response =
                [
                    'status' => 'error',
                    'data' => null,
                    'message' => 'Logout failed: ' . $e->getMessage()
                ];
            return response($response, 500);
        }

        $response =
            [
                'status' => 'success',
                'data' => null,
                'message' => 'Logged out'
            ];

        return response($response, 200);
    }


}
