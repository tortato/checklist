<?php

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->otherUser = User::factory()->create();

    $this->task = Task::factory()->for($this->user)->create();
    $this->task->delete(); 
});

test('allows the owner to restore their own soft deleted task', function () {
    $this->actingAs($this->user, 'sanctum');

    $response = $this->postJson("/api/tasks/{$this->task->id}/restore");

    $response->assertOk()
             ->assertJson(['message' => 'Task restored']);

    $this->assertDatabaseHas('tasks', [
        'id' => $this->task->id,
        'deleted_at' => null,
    ]);
});

test('forbids other users from restoring tasks they do not own', function () {
    $this->actingAs($this->otherUser, 'sanctum');

    $response = $this->postJson("/api/tasks/{$this->task->id}/restore");

    $response->assertForbidden();

    $this->assertSoftDeleted('tasks', ['id' => $this->task->id]);
});