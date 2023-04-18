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
            'password' => 'required|string'
        ]);

        $user = User::create([
            'first_name' => $fields['first_name'],
            'last_name' => $fields['last_name'],
            'birthdate' => $fields['birthdate'],
            'email' => $fields['email'],
            'phone_number' => $fields['phone_number'],
            'gender' => $fields['gender'],
            'image' => null,
            'password' => bcrypt($fields['password']),
        ]);

        if (!$user) {
            $response = [
                'status' => false,
                'data' => null,
                'message' => 'Registration failed'
            ];
            return response($response, 400);
        }

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'status' => true,
            'data' => [
                'user' => $user,
                'token' => $token
            ],
            'message' => 'Register successful'
        ];

        return response($response, 201);
    }


    public function uploadImage(Request $request)
    {
        $fields = $request->validate([
            'user_id' => 'required|integer',
            'image' => 'required|image|max:2048', // max file size of 2MB
        ]);

        $user = User::find($fields['user_id']);
        if (!$user) {
            $response = [
                'status' => false,
                'data' => null,
                'message' => 'User not found'
            ];
            return response()->json($response, 404);
        }

        $image = $request->file('image');
        $name = time() . '_' . $image->getClientOriginalName();
        $path = $image->storeAs('public/images', $name);

        $user->image = $name;
        $user->save();

        $response = [
            'status' => true,
            'data' => [
                'image_url' => asset('storage/images/' . $name)
            ],
            'message' => 'Image uploaded successfully'
        ];

        return response()->json($response, 200);
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
                'status' => false,
                'data' => null,
                'message' => 'Invalid email or password'
            ], 401);
        }

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'status' => true,
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
                    'status' => false,
                    'data' => null,
                    'message' => 'Logout failed: ' . $e->getMessage()
                ];
            return response($response, 500);
        }

        $response =
            [
                'status' => true,
                'data' => null,
                'message' => 'Logged out'
            ];

        return response($response, 200);
    }


}
