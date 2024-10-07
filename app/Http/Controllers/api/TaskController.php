<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Models\Task;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $user = auth()->user();
            $tasks = Task::where('id_user', 1)->get();
            return response()->json($tasks);
        } catch (Exception $exection) {
            return response()->json([
                'response' => false,
                'message' => $exection,
                'data' =>  []
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskRequest $request): JsonResponse
    {
        $user = auth()->user();

        try {
            $request->validated();
            $task = Task::create([
                'id_user' => $user['id'],
                'id_status' => $request['status'],
                'id_priority' => $request['priority'],
                'title' => $request['title'],
                'description' => $request['description'] ? $request['description'] : null,
                'date_limit' => $request['date_limit']
            ]);

            if ($task) {
                return response()->json([
                    'response' => true,
                    'message' => 'Task created',
                    'data' =>  $task
                ], 201);
            } else {
                return response()->json([
                    'response' => false,
                    'message' => 'Somenting was wrong',
                    'data' =>  []
                ], 500);
            }
        } catch (Exception $exection) {
            return response()->json([
                'response' => false,
                'message' => $exection,
                'data' =>  []
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {

        try {
            $task = Task::where('id', $id)->first();
            return response()->json([
                'response' => true,
                'message' => '',
                'data' =>  $task
            ], 200);
        } catch (Exception $exection) {
            return response()->json([
                'response' => false,
                'message' => $exection,
                'data' =>  []
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskRequest $request, int $id): JsonResponse
    {
        $user = auth()->user();

        try {
            $request->validated();

            $task = Task::where('id', $id)->where('id_user', $user->id)->first();


            if (!$task) {
                return response()->json([
                    'response' => false,
                    'message' => 'Task not found',
                    'data' => []
                ], 404);
            }

            // Actualiza los campos de la tarea
            $task->update([
                'id_status' => $request->input('status'),
                'id_priority' => $request->input('priority'),
                'title' => $request->input('title'),
                'description' => $request->input('description') ?: null,
                'date_limit' => $request->input('date_limit')
            ]);

            return response()->json([
                'response' => true,
                'message' => 'Task updated',
                'data' => $task
            ], 200);
        } catch (Exception $exception) {
            return response()->json([
                'response' => false,
                'message' => $exception->getMessage(),
                'data' => []
            ], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $user = auth()->user();

        try {
            $task = Task::where('id', $id)->where('id_user', $user->id)->first();
    
            if (!$task) {
                return response()->json([
                    'response' => false,
                    'message' => 'Task not found',
                    'data' => []
                ], 404);
            }

            $task->delete();
    
            return response()->json([
                'response' => true,
                'message' => 'Task deleted successfully',
                'data' => []
            ], 200);
            
        } catch (Exception $exception) {
            return response()->json([
                'response' => false,
                'message' => $exception->getMessage(),
                'data' => []
            ], 500);
        }
    }
}
