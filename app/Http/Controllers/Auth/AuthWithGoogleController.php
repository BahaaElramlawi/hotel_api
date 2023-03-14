<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Validator;

class AuthWithGoogleController extends Controller
{
    public function handleProviderCallback(Request $request)
    {
        $validator = Validator::make($request->only('provider', 'access_provider_token'), [
            'provider' => ['required', 'string'],
            'access_provider_token' => ['required', 'string']
        ]);
        if ($validator->fails())
            return response()->json($validator->errors(), 400);
        $provider = $request->provider;
        $validated = $this->validateProvider($provider);
        if (!is_null($validated))
            return $validated;
        $providerUser = Socialite::driver($provider)->userFromToken($request->access_provider_token);
        $user = User::firstOrCreate(
            [
                'email' => $providerUser->getEmail()
            ],
            [
                'name' => $providerUser->getName(),
            ]
        );
        $data = [
            'token' => $user->createToken('Sanctom+Socialite')->plainTextToken,
            'user' => $user,
        ];
        return response()->json($data, 200);
    }

    protected function validateProvider($provider)
    {
        if (!in_array($provider, ['google'])) {
            return response()->json(["message" => 'You can only login via google account'], 400);
        }
    }
}
