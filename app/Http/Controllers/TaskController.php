<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Services\TaskService;

class TaskController extends Controller
{
    public function __construct(protected TaskService $taskService) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = $this->taskService->list();

        return response()->json($tasks);
    }

    /**
     * Display a listing of the trashed resource.
     */
    public function trashed()
    {
        $tasks = $this->taskService->trashed();

        return response()->json($tasks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        $task = $this->taskService->create($request->validated());

        return response()->json($task, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        $this->authorize('view', $task);

        return response()->json($task);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $this->authorize('update', $task);
        $updatedTask = $this->taskService->update($task, $request->validated());

        return response()->json($updatedTask);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        $this->taskService->delete($task);

        return response()->noContent();
    }


    /**
     * Restore the specified resource from storage
     */
    public function restore(Task $task)
    {
        $this->authorize('restore', $task);
        $this->taskService->restore($task);

        return response()->json(['message' => 'Task restored']);
    }
}
