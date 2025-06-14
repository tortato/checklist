<?php

use App\Models\User;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user, 'sanctum');

    $this->otherUser = User::factory()->create();
});

it('allows user to soft delete their task', function () {
    $task = Task::factory()->create(['user_id' => $this->user->id]);

    $this->deleteJson("/api/tasks/{$task->id}")->assertNoContent();
    
    $this->assertSoftDeleted('tasks', [
        'id' => $task->id,
    ]);
});

it('forbids user from deleting a task that is not theirs', function () {
    $task = Task::factory()->create(['user_id' => $this->otherUser->id]); 

    $this->deleteJson("/api/tasks/{$task->id}")->assertForbidden();

    $this->assertDatabaseHas('tasks', [
        'id' => $task->id,
        'deleted_at' => null,
    ]);
});
