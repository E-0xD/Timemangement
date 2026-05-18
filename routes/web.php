<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TimetableController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard');

    // Tasks
    Route::resource('tasks', TaskController::class);
    Route::post('tasks/{task}/toggle', [TaskController::class, 'toggle'])->name('tasks.toggle');
    Route::post('tasks/{task}/subtasks/{subtask}/toggle', [TaskController::class, 'toggleSubtask'])->name('tasks.subtasks.toggle');

    // Timetable
    Route::resource('timetable', TimetableController::class)->except(['show']);
});

require __DIR__.'/auth.php';
require __DIR__.'/settings.php';
