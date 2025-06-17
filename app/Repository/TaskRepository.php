<?php

namespace App\Repository;

use App\Models\Task;

class TaskRepository
{
    public function create(array $data): Task
    {
        return Task::create($data);
    }

    public function update(Task $task, array $data)
    {
        return $task->update($data);
    }

    public function delete(Task $task)
    {
        $task->delete();
    }

    public function restore(Task $task)
    {
        $task->restore();
    }

    public function getAllForUser(int $userId)
    {
        return Task::where('user_id', $userId)->get();
    }

    public function getAllTrashedForUser(int $userId)
    {
        return Task::where('user_id', $userId)->onlyTrashed()->get();
    }
}