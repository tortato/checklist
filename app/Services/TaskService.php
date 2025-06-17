<?php

namespace App\Services;

use App\Models\Task;
use App\Repository\TaskRepository;

class TaskService
{
    public function __construct(protected TaskRepository $repo){ }

    public function create(array $data): Task
    {
        return $this->repo->create($data);
    }

    public function update(Task $task, array $data): Task
    {
        return $this->repo->update($task, $data);
    }

    public function delete(Task $task): void
    {
        $this->repo->delete($task);
    }

    public function restore(Task $task): void
    {
        $this->repo->restore($task);
    }

    public function list()
    {
        $user = auth()->user();
        return $this->repo->getAllForUser($user->id);
    }

    public function trashed()
    {
        $user = auth()->user();
        return $this->repo->getAllTrashedForUser($user->id);
    }
}