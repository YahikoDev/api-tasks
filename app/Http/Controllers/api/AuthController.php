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
    
    /**
     * Signup user
     * @OA\Post (
     *     path="/api/auth/signup",
     *     tags={"auth"},
     *     security={{"bearer_token":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *           @OA\Property(property="name", type="number", example="text"),
     *           @OA\Property(property="email", type="number", example="text"),
     *           @OA\Property(property="password", type="number", example="text"),
     *       ),
     *  ),
     *     @OA\Response(
     *         response=401,
     *         description="OK",
     *         @OA\JsonContent(
     *              @OA\Property(property="response", type="boolean", example=true),
     *              @OA\Property(property="messages", type="list", example="[...]"),
     *              @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      @OA\Property(property="token", type="string", example="text"),
     *                      @OA\Property(
     *                          property="user",
     *                          type="array",
     *                          @OA\Items(
     *                              @OA\Property(property="id", type="number", example=1),
     *                              @OA\Property(property="name", type="string", example="text"),
     *                              @OA\Property(property="email", type="string", example="text"),
     *                              @OA\Property(property="updated_at", type="string", format="date-time", example="2023-02-23T12:33:45.000000Z"),
     *                              @OA\Property(property="created_at", type="string", format="date-time", example="2023-02-23T12:33:45.000000Z"),
     *                          )
     *                      )
     *                  )
     *              )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Not found",
     *         @OA\JsonContent(
     *              @OA\Property(property="response", type="boolean", example=false),
     *              @OA\Property(property="messages", type="list", example="[...]"),
     *              @OA\Property(property="data", type="list", example="[]"),
     *          )
     *     )
     * )
     */
    public function signup(SignupRequest $request)
    {
        $request->validated();

        $credentials = $request->only('email', 'password');

        $user = User::create([
            'name' => strtolower($request['name']),
            'email' => strtolower($request['email']),
            'password' => bcrypt($request['password']),
        ]);

        if (!$token = $this->generaToken($credentials)) {
            return response()->json(['response' => false, 'messages' => ['Unautorized']], 401);
        }

        return response()->json([
            'messages' => ['User created'],
            'response' => true,
            'data' =>  [
                'token' => $token,
                'user' => $user,
            ]
        ]);
    }

    /**
     * login user
     * @OA\Post (
     *     path="/api/auth/login",
     *     tags={"auth"},
     *     security={{"bearer_token":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *           @OA\Property(property="email", type="number", example="text"),
     *           @OA\Property(property="password", type="number", example="text"),
     *       ),
     *  ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *              @OA\Property(property="response", type="boolean", example=true),
     *              @OA\Property(property="messages", type="list", example="[...]"),
     *              @OA\Property(property="data", type="string", example="text"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unautorized",
     *         @OA\JsonContent(
     *              @OA\Property(property="response", type="boolean", example=false),
     *              @OA\Property(property="messages", type="list", example="[...]"),
     *              @OA\Property(property="data", type="list", example="[]"),
     *          )
     *     )
     * )
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $request->validated();

        $credentials = $request->only('email', 'password');

        $user = User::where('email', $request['email'])->first();

        if (!$user) {
            return response()->json(['response' => false, 'messages' => ['invalid_credentials']], 401);
        }

        if (!$token = $this->generaToken($credentials)) {
            return response()->json(['response' => false, 'messages' => ['Unautorized']], 401);
        }

        return response()->json(['response' => true, 'messages' => [''], 'data' =>  $token], 200);
    }

/**
     * logout user
     * @OA\Post (
     *     path="/api/auth/logout",
     *     tags={"auth"},
     *     security={{"bearer_token":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *              @OA\Property(property="response", type="boolean", example=true),
     *              @OA\Property(property="messages", type="list", example="[...]"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Not found",
     *         @OA\JsonContent(
     *              @OA\Property(property="response", type="boolean", example=false),
     *              @OA\Property(property="messages", type="list", example="[...]"),
     *          )
     *     )
     * )
     */
    public function logout()
    {
        try {
            auth()->logout();
            return response()->json(['response' => true, 'messages' => ['User logout']]);
        } catch (JWTException $e) {
            return response()->json(['response' => false, 'messages' => ['Invalid or empty token']], 500);
        }
    }

    protected function generaToken(array $data){
        if (!$token = auth(guard: 'api')->attempt($data)) {
            return '';
        }
        return $token;
    }
}
