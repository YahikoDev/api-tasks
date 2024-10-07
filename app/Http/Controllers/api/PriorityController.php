<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Priority;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Exceptions\JWTException;

class PriorityController extends Controller
{
        /**
     * List Priorities
     * @OA\Get (
     *     path="/api/priority",
     *     tags={"priority"},
     *     security={{"bearer_token":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *              @OA\Property(property="response", type="boolean", example=true),
     *              @OA\Property(property="messages", type="list", example="[...]"),
     *              @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      @OA\Property(property="id", type="number", example=1),
     *                      @OA\Property(property="title", type="string", example="text"),
     *                      @OA\Property(property="created_at", type="string", format="date-time", example="2023-02-23T12:33:45.000000Z"),
     *                      @OA\Property(property="updated_at", type="string", format="date-time", example="2023-02-23T12:33:45.000000Z")
     *                  )
     *              )
     *         )
     *     ),
     *     @OA\Response(
     *         response=402,
     *         description="Not found",
     *         @OA\JsonContent(
     *              @OA\Property(property="response", type="boolean", example=false),
     *              @OA\Property(property="messages", type="list", example="[...]"),
     *              @OA\Property(property="data", type="list", example={}),
     *          )
     *     )
     * )
     */

    public function index(): JsonResponse
    {
        try {
            return response()->json([
                'response' => true,
                'message' => [],
                'data' => Priority::all()
            ]);
        } catch (JWTException $e) {
            return response()->json(['response' => false, 'memssage' => 'Invalid or empty token'], 402);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
