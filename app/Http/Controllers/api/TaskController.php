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
 * @OA\Get(
 *     path="/api/tasks",
 *     tags={"tasks"},
 *     security={{"bearer_token":{}}},
 *     summary="Retrieve a list of tasks for the authenticated user.",
 *     description="This endpoint returns a list of tasks along with their details, including status and priority.",
 *     @OA\Response(
 *         response=200,
 *         description="Successfully retrieved tasks.",
 *         @OA\JsonContent(
 *              @OA\Property(property="response", type="boolean", example=true, description="Indicates whether the request was successful."),
 *              @OA\Property(property="messages", type="array", @OA\Items(type="string", example="", description="An array of messages related to the request.")),
 *              @OA\Property(
 *                  property="data",
 *                  type="array",
 *                  @OA\Items(
 *                      @OA\Property(property="id", type="integer", example=1, description="Unique identifier for the task."),
 *                      @OA\Property(property="id_user", type="integer", example=1, description="Identifier of the user who owns the task."),
 *                      @OA\Property(property="id_status", type="integer", example=3, description="Identifier for the task's status."),
 *                      @OA\Property(property="id_priority", type="integer", example=3, description="Identifier for the task's priority."),
 *                      @OA\Property(property="title", type="string", example="pruebas", description="Title of the task."),
 *                      @OA\Property(property="description", type="string", nullable=true, example=null, description="Detailed description of the task. Can be null."),
 *                      @OA\Property(property="date_limit", type="string", format="date-time", example="2024-10-06 23:01:15", description="Deadline for the task."),
 *                      @OA\Property(property="created_at", type="string", format="date-time", example="2024-10-07T23:28:22.000000Z", description="Timestamp when the task was created."),
 *                      @OA\Property(property="updated_at", type="string", format="date-time", example="2024-10-07T23:28:22.000000Z", description="Timestamp when the task was last updated."),
 *                      @OA\Property(
 *                          property="status",
 *                          type="object",
 *                          @OA\Property(property="id", type="integer", example=3, description="Unique identifier for the task's status."),
 *                          @OA\Property(property="title", type="string", example="completada", description="Title of the status.")
 *                      ),
 *                      @OA\Property(
 *                          property="priority",
 *                          type="object",
 *                          @OA\Property(property="id", type="integer", example=3, description="Unique identifier for the task's priority."),
 *                          @OA\Property(property="title", type="string", example="alta", description="Title of the priority.")
 *                      )
 *                  )
 *              )
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal server error.",
 *         @OA\JsonContent(
 *              @OA\Property(property="response", type="boolean", example=false, description="Indicates whether the request was unsuccessful."),
 *              @OA\Property(property="messages", type="array", @OA\Items(type="string", example="An error occurred.")),
 *              @OA\Property(property="data", type="object", example={}),
 *          )
 *     )
 * )
 */


    public function index(): JsonResponse
    {
        try {
            $user = auth()->user();
            $tasks = Task::where('id_user', $user['id'])->with('status')->with('priority')->get();
            return response()->json([
                'response' => true,
                'messages' => [],
                'data' =>  $tasks
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
     *           @OA\Property(property="date_limit", type="string", format="date-time", example="2024-10-15")
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
 * @OA\Get(
 *     path="/api/tasks/{id}",
 *     tags={"tasks"},
 *     security={{"bearer_token":{}}},
 *     summary="Retrieve a task by its ID.",
 *     description="This endpoint returns details of a specific task identified by its unique ID, including status and priority.",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Unique identifier of the task to retrieve.",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successfully retrieved the task.",
 *         @OA\JsonContent(
 *              @OA\Property(property="response", type="boolean", example=true, description="Indicates whether the request was successful."),
 *              @OA\Property(property="messages", type="array", @OA\Items(type="string", example="", description="An array of messages related to the request.")),
 *              @OA\Property(
 *                  property="data",
 *                  type="object",
 *                  @OA\Property(property="id", type="integer", example=1, description="Unique identifier for the task."),
 *                  @OA\Property(property="id_user", type="integer", example=1, description="Identifier of the user who owns the task."),
 *                  @OA\Property(property="id_status", type="integer", example=3, description="Identifier for the task's status."),
 *                  @OA\Property(property="id_priority", type="integer", example=3, description="Identifier for the task's priority."),
 *                  @OA\Property(property="title", type="string", example="pruebas", description="Title of the task."),
 *                  @OA\Property(property="description", type="string", nullable=true, example=null, description="Detailed description of the task. Can be null."),
 *                  @OA\Property(property="date_limit", type="string", format="date-time", example="2024-10-06 23:01:15", description="Deadline for the task."),
 *                  @OA\Property(property="created_at", type="string", format="date-time", example="2024-10-07T23:28:22.000000Z", description="Timestamp when the task was created."),
 *                  @OA\Property(property="updated_at", type="string", format="date-time", example="2024-10-07T23:28:22.000000Z", description="Timestamp when the task was last updated."),
 *                  @OA\Property(
 *                      property="status",
 *                      type="object",
 *                      @OA\Property(property="id", type="integer", example=3, description="Unique identifier for the task's status."),
 *                      @OA\Property(property="title", type="string", example="completada", description="Title of the status.")
 *                  ),
 *                  @OA\Property(
 *                      property="priority",
 *                      type="object",
 *                      @OA\Property(property="id", type="integer", example=3, description="Unique identifier for the task's priority."),
 *                      @OA\Property(property="title", type="string", example="alta", description="Title of the priority.")
 *                  )
 *              )
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Task not found.",
 *         @OA\JsonContent(
 *              @OA\Property(property="response", type="boolean", example=false, description="Indicates whether the request was unsuccessful."),
 *              @OA\Property(property="messages", type="array", @OA\Items(type="string", example="Task not found.")),
 *              @OA\Property(property="data", type="object", example={}),
 *          )
 *     )
 * )
 */

    public function show(string $id): JsonResponse
    {
        try {

            $task = Task::where('id', $id)->with('status')->with('priority')->first();
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
     *           @OA\Property(property="date_limit", type="string", format="date-time", example="2024-10-15T")
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
