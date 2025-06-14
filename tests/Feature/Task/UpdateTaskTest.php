<?php

use App\Enums\TaskStatus;
use App\Models\User;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user, 'sanctum');

    $this->otherUser = User::factory()->create();
});

it('allows user to update their task', function () {
    $task = Task::factory()->create(['user_id' => $this->user->id]);

    $response = $this->putJson("/api/tasks/{$task->id}", [
        'status' => TaskStatus::Completed->value,
    ]);

    $response->assertOk()
             ->assertJsonFragment(['status' => TaskStatus::Completed->value]);

    $this->assertDatabaseHas('tasks', [
        'id' => $task->id,
        'status' => TaskStatus::Completed->value,
    ]);
});

it('forbids user from updating task that is not theirs', function () {
    $task = Task::factory()->create(['user_id' => $this->otherUser->id]); 

    $response = $this->putJson("/api/tasks/{$task->id}", [
        'status' => TaskStatus::Completed->value,
    ]);

    $response->assertForbidden();

    $this->assertDatabaseHas('tasks', [
        'id' => $task->id,
        'status' => $task->status->value,
    ]);
});
