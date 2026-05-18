<?php

namespace Database\Factories;

use App\Enums\TaskCategory;
use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\Task;
use App\Models\User;
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
            'user_id'  => User::factory(),
            'title'    => fake()->sentence(4),
            'category' => fake()->randomElement(TaskCategory::cases())->value,
            'priority' => fake()->randomElement(TaskPriority::cases())->value,
            'status'   => TaskStatus::Pending->value,
            'due_date' => fake()->optional()->dateTimeBetween('now', '+30 days'),
        ];
    }
}
