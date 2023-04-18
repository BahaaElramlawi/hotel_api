<?php

namespace App\Http\Controllers\Host\Auth;

use App\Http\Controllers\Controller;
use App\Models\Host;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class HostAuthController extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'birthdate' => 'required|string',
            'email' => 'required|string|email|unique:hosts|unique:users',
            'phone_number' => 'required|string',
            'gender' => 'required|string',
            'image' => 'required|file',
            'password' => 'required|string'
        ]);

        // Upload the image and get the file name
        $name = $this->uploadImage($request->file('image'));

        $host = Host::create([
            'first_name' => $fields['first_name'],
            'last_name' => $fields['last_name'],
            'birthdate' => $fields['birthdate'],
            'email' => $fields['email'],
            'phone_number' => $fields['phone_number'],
            'gender' => $fields['gender'],
            'image' => $name,
            'password' => bcrypt($fields['password']),
        ]);

        if (!$host) {
            $response = [
                'status' => false,
                'data' => null,
                'message' => 'Registration failed'
            ];
            return response($response, 400);
        }

        $token = $host->createToken('myapptoken')->plainTextToken;

        $response = [
            'status' => true,
            'data' => [
                'host' => $host,
                'token' => $token
            ],
            'message' => 'Register successful'
        ];

        return response($response, 201);
    }

    private function uploadImage($image)
    {
        // Generate a unique file name
        $name = time() + rand(1, 1000000) . '.' . $image->getClientOriginalName();
        // Store the uploaded image in the public disk
        $path = $image->storeAs('public/images', $name);
        return $name;
    }


    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        // Check email
        $host = Host::where('email', $fields['email'])->first();

        // Check password
        if (!$host || !Hash::check($fields['password'], $host->password)) {
            return response([
                'status' => false,
                'data' => null,
                'message' => 'Invalid email or password'
            ], 401);
        }

        $token = $host->createToken('myapptoken')->plainTextToken;

        $response = [
            'status' => true,
            'data' => [
                'host' => $host,
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
