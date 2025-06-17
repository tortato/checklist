<?php

namespace Database\Factories;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->optional()->paragraph(),
            'priority' => $this->faker->randomElement(TaskPriority::cases())->value,
            'status' => $this->faker->randomElement(TaskStatus::cases())->value,
            'due_date' => Carbon::now()->addDays(30)->format('Y-m-d H:i:s'),
            'user_id' => User::factory(),
        ];
    }
}
