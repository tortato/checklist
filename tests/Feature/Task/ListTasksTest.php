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

it('lists only tasks belonging to authenticated user', function () {
    Task::factory()->count(3)->create(['user_id' => $this->user->id]);
    Task::factory()->count(2)->create(['user_id' => $this->otherUser->id]); 

    $response = $this->getJson('/api/tasks');

    $response->assertOk();
    $response->assertJsonCount(3);
});

it('returns unauthorized for guests', function () {
    auth()->logout();
    $response = $this->getJson('/api/tasks');
    $response->assertUnauthorized();
});
