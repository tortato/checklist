<?php

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->task = Task::factory()->create([
        'user_id' => $this->user->id,
        'priority' => TaskPriority::Low,
        'status' => TaskStatus::Pending,
        'due_date' => Carbon::now()->addDays(30),
    ]);
});

test('guests cannot access any task routes', function () {
    $routes = [
        'get' => "/api/tasks",
        'post' => "/api/tasks",
        'get-single' => "/api/tasks/{$this->task->id}",
        'put' => "/api/tasks/{$this->task->id}",
        'delete' => "/api/tasks/{$this->task->id}",
        'restore' => "/api/tasks/{$this->task->id}/restore",
    ];

    foreach ($routes as $method => $url) {
        $response = match ($method) {
            'get', 'get-single' => $this->getJson($url),
            'post' => $this->postJson($url, []),
            'put' => $this->putJson($url, []),
            'delete' => $this->deleteJson($url),
            'restore' => $this->postJson($url),
        };

        $response->assertUnauthorized();
    }
});
