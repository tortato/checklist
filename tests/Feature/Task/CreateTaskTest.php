<?php

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user, 'sanctum');
});

test('allows a user to create a task with valid data', function () {
    $data = [
        'title' => 'New task',
        'description' => 'Task detail description',
        'priority' => TaskPriority::High->value,
        'status' => TaskStatus::Pending->value,
        'due_date' => Carbon::now()->addDays(30),
    ];

    $response = $this->postJson('/api/tasks', $data);

    $response->assertCreated()
        ->assertJsonFragment([
            'priority' => TaskPriority::High->value,
            'status' => TaskStatus::Pending->value,
        ]);

    $this->assertDatabaseHas('tasks', [
        'title' => 'New task',
        'user_id' => $this->user_id
    ]);
});

test('fails to create a task with invalid enum values', function () {
    $data = [
        'title' => 'New Error Task',
        'priority' => 'urgent', 
        'status' => 'done',   
    ];

    $response = $this->postJson('/api/tasks', $data);

    $response->assertUnprocessable()
             ->assertJsonValidationErrors(['priority', 'status']);
});
