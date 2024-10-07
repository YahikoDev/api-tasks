<?php

namespace App\Http\Controllers\api;

use App\Events\SendEmailEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Models\Task;
use Exception;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    /**
     * List Tasks by user
     * @OA\Get (
     *     path="/api/tasks",
     *     tags={"tasks"},
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
     *                      @OA\Property(property="id_user", type="number", example=1),
     *                      @OA\Property(property="id_status", type="number", example=1),
     *                      @OA\Property(property="id_priority", type="number", example=1),
     *                      @OA\Property(property="title", type="string", example="text"),
     *                      @OA\Property(property="description", type="string", example="text"),
     *                      @OA\Property(property="date_limit", type="string", format="date-time", example="2023-02-23T12:33:45.000000Z"),
     *                      @OA\Property(property="created_at", type="string", format="date-time", example="2023-02-23T12:33:45.000000Z"),
     *                      @OA\Property(property="updated_at", type="string", format="date-time", example="2023-02-23T12:33:45.000000Z")
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
     *              @OA\Property(property="data", type="list", example={}),
     *          )
     *     )
     * )
     */

    public function index(): JsonResponse
    {
        try {
            $user = auth()->user();
            $tasks = Task::where('id_user', $user['id'])->get();
            return response()->json([
                'response' => true,
                'messages' => [],
                'data' =>  [$tasks]
            ]);
        } catch (Exception $exection) {
            return response()->json([
                'response' => false,
                'messages' => [$exection],
                'data' =>  []
            ], 500);
        }
    }

    /**
     * Tasks create
     * @OA\Post (
     *     path="/api/tasks/create",
     *     tags={"tasks"},
     *     security={{"bearer_token":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *           @OA\Property(property="status", type="number", example=1),
     *           @OA\Property(property="priority", type="number", example=1),
     *           @OA\Property(property="title", type="string", example="New Task"),
     *           @OA\Property(property="description", type="string", example="Task description"),
     *           @OA\Property(property="date_limit", type="string", format="date-time", example="2024-10-15T14:30:00.000000Z")
     *       ),
     *  ),
     *     @OA\Response(
     *         response=201,
     *         description="OK",
     *         @OA\JsonContent(
     *              @OA\Property(property="response", type="boolean", example=true),
     *              @OA\Property(property="messages", type="list", example="[...]"),
     *              @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      @OA\Property(property="id", type="number", example=1),
     *                      @OA\Property(property="id_user", type="number", example=1),
     *                      @OA\Property(property="id_status", type="number", example=1),
     *                      @OA\Property(property="id_priority", type="number", example=1),
     *                      @OA\Property(property="title", type="string", example="text"),
     *                      @OA\Property(property="description", type="string", example="text"),
     *                      @OA\Property(property="date_limit", type="string", format="date-time", example="2023-02-23T12:33:45.000000Z"),
     *                      @OA\Property(property="created_at", type="string", format="date-time", example="2023-02-23T12:33:45.000000Z"),
     *                      @OA\Property(property="updated_at", type="string", format="date-time", example="2023-02-23T12:33:45.000000Z")
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

            if ($request['priority'] === 3) {
                SendEmailEvent::dispatch($task);
            }

            if ($task) {
                return response()->json([
                    'response' => true,
                    'messages' => ['Task created'],
                    'data' =>  $task
                ], 201);
            } else {
                return response()->json([
                    'response' => false,
                    'messages' => ['Somenting was wrong'],
                    'data' =>  []
                ], 500);
            }
        } catch (Exception $exection) {
            return response()->json([
                'response' => false,
                'messages' => [$exection],
                'data' =>  []
            ], 500);
        }
    }

    /**
     * Tasks show one
     * @OA\Get (
     *     path="/api/tasks/{id}",
     *     tags={"tasks"},
     *     security={{"bearer_token":{}}},
     *      @OA\Parameter(
    *         name="id",
    *         in="path",
    *         required=true,
    *         @OA\Schema(
    *             type="integer"
    *         ),
    *         description="ID of the task"
    *     ),
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
     *                      @OA\Property(property="id_user", type="number", example=1),
     *                      @OA\Property(property="id_status", type="number", example=1),
     *                      @OA\Property(property="id_priority", type="number", example=1),
     *                      @OA\Property(property="title", type="string", example="text"),
     *                      @OA\Property(property="description", type="string", example="text"),
     *                      @OA\Property(property="date_limit", type="string", format="date-time", example="2023-02-23T12:33:45.000000Z"),
     *                      @OA\Property(property="created_at", type="string", format="date-time", example="2023-02-23T12:33:45.000000Z"),
     *                      @OA\Property(property="updated_at", type="string", format="date-time", example="2023-02-23T12:33:45.000000Z")
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
    public function show(string $id): JsonResponse
    {
        try {

            $task = Task::where('id', $id)->first();
            return response()->json([
                'response' => true,
                'messages' => [''],
                'data' =>  $task
            ], 200);
        } catch (Exception $exection) {
            return response()->json([
                'response' => false,
                'messages' => [$exection],
                'data' =>  []
            ], 500);
        }
    }

     /**
     * Update task
     * @OA\PUT (
     *     path="/api/tasks/{id}",
     *     tags={"tasks"},
     *     security={{"bearer_token":{}}},
     *     @OA\Parameter(
    *         name="id",
    *         in="path",
    *         required=true,
    *         @OA\Schema(
    *             type="integer"
    *         ),
    *         description="ID of the task"
    *     ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *           @OA\Property(property="status", type="number", example=1),
     *           @OA\Property(property="priority", type="number", example=1),
     *           @OA\Property(property="title", type="string", example="New Task"),
     *           @OA\Property(property="description", type="string", example="Task description"),
     *           @OA\Property(property="date_limit", type="string", format="date-time", example="2024-10-15T14:30:00.000000Z")
     *       ),
     *  ),
     *     @OA\Response(
     *         response=201,
     *         description="OK",
     *         @OA\JsonContent(
     *              @OA\Property(property="response", type="boolean", example=true),
     *              @OA\Property(property="messages", type="list", example="[...]"),
     *              @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      @OA\Property(property="id", type="number", example=1),
     *                      @OA\Property(property="id_user", type="number", example=1),
     *                      @OA\Property(property="id_status", type="number", example=1),
     *                      @OA\Property(property="id_priority", type="number", example=1),
     *                      @OA\Property(property="title", type="string", example="text"),
     *                      @OA\Property(property="description", type="string", example="text"),
     *                      @OA\Property(property="date_limit", type="string", format="date-time", example="2023-02-23T12:33:45.000000Z"),
     *                      @OA\Property(property="created_at", type="string", format="date-time", example="2023-02-23T12:33:45.000000Z"),
     *                      @OA\Property(property="updated_at", type="string", format="date-time", example="2023-02-23T12:33:45.000000Z")
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

    public function update(TaskRequest $request, int $id): JsonResponse
    {
        $user = auth()->user();

        try {
            $request->validated();

            $task = Task::where('id', $id)->where('id_user', $user->id)->first();


            if (!$task) {
                return response()->json([
                    'response' => false,
                    'messages' => ['Task not found'],
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

            if ($request->input('priority') === 3) {
                SendEmailEvent::dispatch($task);
            }

            return response()->json([
                'response' => true,
                'messages' => ['Task updated'],
                'data' => $task
            ], 200);
        } catch (Exception $exception) {
            return response()->json([
                'response' => false,
                'messages' => [$exception],
                'data' => []
            ], 500);
        }
    }


    /**
     * Tasks delete
     * @OA\Delete (
     *     path="/api/tasks/{id}",
     *     tags={"tasks"},
     *     security={{"bearer_token":{}}},
     *   @OA\Parameter(
    *         name="id",
    *         in="path",
    *         required=true,
    *         @OA\Schema(
    *             type="integer"
    *         ),
    *         description="ID of the task"
    *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *              @OA\Property(property="response", type="boolean", example=true),
     *              @OA\Property(property="messages", type="string", example=""),
     *              @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      @OA\Property(property="id", type="number", example=1),
     *                      @OA\Property(property="id_user", type="number", example=1),
     *                      @OA\Property(property="id_status", type="number", example=1),
     *                      @OA\Property(property="id_priority", type="number", example=1),
     *                      @OA\Property(property="title", type="string", example="text"),
     *                      @OA\Property(property="description", type="string", example="text"),
     *                      @OA\Property(property="date_limit", type="string", format="date-time", example="2023-02-23T12:33:45.000000Z"),
     *                      @OA\Property(property="created_at", type="string", format="date-time", example="2023-02-23T12:33:45.000000Z"),
     *                      @OA\Property(property="updated_at", type="string", format="date-time", example="2023-02-23T12:33:45.000000Z")
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
    public function destroy(int $id): JsonResponse
    {
        $user = auth()->user();

        try {
            $task = Task::where('id', $id)->where('id_user', $user->id)->first();

            if (!$task) {
                return response()->json([
                    'response' => false,
                    'messages' => ['Task not found'],
                    'data' => $task
                ], 404);
            }

            $task->delete();

            return response()->json([
                'response' => true,
                'messages' => ['Task deleted successfully'],
                'data' => []
            ], 200);
        } catch (Exception $exception) {
            return response()->json([
                'response' => false,
                'messages' => [$exception],
                'data' => []
            ], 500);
        }
    }
}
