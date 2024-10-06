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
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            return response()->json([
                'response' => true,
                'message' => '',
                'data' => Priority::all()
            ]);
        } catch (JWTException $e) {
            return response()->json(['response' => false, 'memssage' => 'Invalid or empty token']);
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
