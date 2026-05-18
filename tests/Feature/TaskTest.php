<?php

use App\Enums\TaskCategory;
use App\Enums\TaskPriority;
use App\Models\Task;
use App\Models\User;

test('guests are redirected from task index', function () {
    $this->get(route('tasks.index'))->assertRedirect(route('login'));
});

test('authenticated user can view task list', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->get(route('tasks.index'))->assertOk();
});

test('user can create a task', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->post(route('tasks.store'), [
        'title'    => 'Write lab report',
        'category' => TaskCategory::Assignment->value,
        'priority' => TaskPriority::High->value,
    ])->assertStatus(302);

    $this->assertDatabaseHas('tasks', [
        'user_id' => $user->id,
        'title'   => 'Write lab report',
    ]);
});

test('task title is required', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->post(route('tasks.store'), [
        'category' => TaskCategory::Homework->value,
        'priority' => TaskPriority::Low->value,
    ])->assertSessionHasErrors('title');
});

test('user can view their own task', function () {
    $user = User::factory()->create();
    $task = Task::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)->get(route('tasks.show', $task))->assertOk();
});

test('user cannot view another users task', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $task  = Task::factory()->create(['user_id' => $owner->id]);

    $this->actingAs($other)->get(route('tasks.show', $task))->assertStatus(403);
});

test('user can delete their own task', function () {
    $user = User::factory()->create();
    $task = Task::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)->delete(route('tasks.destroy', $task))->assertRedirect(route('tasks.index'));

    $this->assertSoftDeleted('tasks', ['id' => $task->id]);
});

test('user cannot delete another users task', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $task  = Task::factory()->create(['user_id' => $owner->id]);

    $this->actingAs($other)->delete(route('tasks.destroy', $task))->assertStatus(403);
});
