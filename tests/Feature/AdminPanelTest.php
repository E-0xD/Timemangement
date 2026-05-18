<?php

use App\Enums\UserRole;
use App\Models\User;

test('guests cannot access admin dashboard', function () {
    $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
});

test('students cannot access admin dashboard', function () {
    $student = User::factory()->create(['role' => UserRole::Student->value]);

    $this->actingAs($student)->get(route('admin.dashboard'))->assertForbidden();
});

test('admin can access admin dashboard', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin->value]);

    $this->actingAs($admin)->get(route('admin.dashboard'))->assertOk();
});

test('admin can view user list', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin->value]);

    $this->actingAs($admin)->get(route('admin.users.index'))->assertOk();
});

test('admin can view a specific user', function () {
    $admin   = User::factory()->create(['role' => UserRole::Admin->value]);
    $student = User::factory()->create(['role' => UserRole::Student->value]);

    $this->actingAs($admin)->get(route('admin.users.show', $student))->assertOk();
});

test('admin can toggle a user role', function () {
    $admin   = User::factory()->create(['role' => UserRole::Admin->value]);
    $student = User::factory()->create(['role' => UserRole::Student->value]);

    $this->actingAs($admin)->patch(route('admin.users.toggle-role', $student));

    expect($student->fresh()->role)->toBe(UserRole::Admin);
});

test('admin cannot toggle their own role', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin->value]);

    $this->actingAs($admin)->patch(route('admin.users.toggle-role', $admin))->assertForbidden();
});

test('admin cannot delete themselves', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin->value]);

    $this->actingAs($admin)->delete(route('admin.users.destroy', $admin))->assertForbidden();
});

test('student cannot access admin user list', function () {
    $student = User::factory()->create(['role' => UserRole::Student->value]);
    $target  = User::factory()->create();

    $this->actingAs($student)->get(route('admin.users.index'))->assertForbidden();
});
