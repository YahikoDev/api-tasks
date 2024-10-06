<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignupRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function signup(SignupRequest $request)
    {
        $request->validated();
        $user = User::create([
            'name' => strtolower($request['name']),
            'email' => strtolower($request['email']),
            'password' => bcrypt($request['password']),
        ]);

        return response()->json([
            'emssage' => 'User created',
            'response' => true,
            'data' =>  [
                'user' => $user
            ]
        ]);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $request->validated();

        $credentials = $request->only('email', 'password');

        $user = User::where('email', $request['email'])->first();

        if (!$user) {
            return response()->json(['response' => false, 'memssage' => 'invalid_credentials'], 401);
        }

        if (!$token = auth(guard: 'api')->attempt($credentials)) {
            return response()->json(['response' => false, 'memssage' => 'Unautorized'], 401);
        }

        return response()->json(['response' => true, 'memssage' => '', 'data' => ['token' => $token]], 200);
    }

    public function logout()
    {
        try {
            auth()->logout();
            return response()->json(['response' => true, 'memssage' => 'User logout']);
        } catch (JWTException $e) {
            return response()->json(['response' => false, 'memssage' => 'Invalid or empty token']);
        }
    }
}
