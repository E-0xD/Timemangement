<?php

use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\CalendarEventController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\GamificationController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\StudyGroupController;
use App\Http\Controllers\StudySessionController;
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

    // Calendar
    Route::resource('calendar', CalendarEventController::class)->except(['show']);

    // Notifications
    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.readAll');
    Route::post('notifications/{notification}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::delete('notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');

    // Focus Timer + Study Sessions
    Route::get('focus', [StudySessionController::class, 'focus'])->name('focus.index');
    Route::post('sessions', [StudySessionController::class, 'store'])->name('sessions.store');
    Route::delete('sessions/{session}', [StudySessionController::class, 'destroy'])->name('sessions.destroy');

    // Analytics
    Route::get('analytics', [AnalyticsController::class, 'index'])->name('analytics.index');

    // Goals
    Route::resource('goals', GoalController::class)->except(['show']);
    Route::patch('goals/{goal}/progress', [GoalController::class, 'updateProgress'])->name('goals.progress');

    // Study Groups
    Route::post('groups/join', [StudyGroupController::class, 'join'])->name('groups.join');
    Route::delete('groups/{group}/leave', [StudyGroupController::class, 'leave'])->name('groups.leave');
    Route::post('groups/{group}/messages', [StudyGroupController::class, 'postMessage'])->name('groups.messages.store');
    Route::delete('groups/{group}/messages/{message}', [StudyGroupController::class, 'deleteMessage'])->name('groups.messages.destroy');
    Route::delete('groups/{group}/members/{user}', [StudyGroupController::class, 'removeMember'])->name('groups.members.destroy');
    Route::resource('groups', StudyGroupController::class);

    // Gamification
    Route::get('achievements', [GamificationController::class, 'index'])->name('achievements.index');

    // Notes
    Route::resource('notes', NoteController::class);

    // Files
    Route::get('files', [FileController::class, 'index'])->name('files.index');
    Route::post('files', [FileController::class, 'store'])->name('files.store');
    Route::get('files/{file}/download', [FileController::class, 'download'])->name('files.download');
    Route::delete('files/{file}', [FileController::class, 'destroy'])->name('files.destroy');
});

require __DIR__.'/auth.php';
require __DIR__.'/settings.php';
