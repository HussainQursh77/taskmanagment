<?php

namespace App\Http\Controllers;

use App\Services\TaskService;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class TaskController extends Controller
{
    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->middleware('auth:api');
        $this->taskService = $taskService;
    }

    /**
     * Show list all tasks with filtering by priority and status.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {

            $priority = $request->get('priority');
            $status = $request->get('status');
            $tasks = $this->taskService->getAllTasks($priority, $status, 10);

            return response()->json(TaskResource::collection($tasks), 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }

    // Create a new task
    public function store(StoreTaskRequest $request)
    {
        try {
            $validated = $request->validated();
            $task = $this->taskService->createTask($validated);
            return response()->json(new TaskResource($task), 201);
        } catch (Exception $e) {

            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }

    // Show a task
    public function show(Task $task)
    {
        try {
            return response()->json(new TaskResource($task), 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }

    // Update a task
    public function update(UpdateTaskRequest $request, Task $task)
    {
        try {
            $validated = $request->validated();
            $updatedTask = $this->taskService->updateTask($task, $validated, Auth::id());
            return response()->json(new TaskResource($updatedTask), 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }

    // Delete a task
    public function destroy(Task $task)
    {
        try {
            $this->taskService->deleteTask($task);
            return response()->json(null, 204);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }
}
