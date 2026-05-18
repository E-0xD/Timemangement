# System Prompts — Student Time Management System

> **Keep this file updated.** These are the standing instructions for building this project.
> Every AI build session should read this file first, then `BUILD_PROGRESS.md`, then `TECHNICAL_OVERVIEW.md`.

---

## Core Architectural Rules

### 1. String-backed Enums
All fixed-option fields (priority, status, category, role, etc.) use **PHP string-backed enums** in `App\Enums\`.
- Cast directly on models via `casts()`.
- DB stores the string value (no integer IDs for enums).
- Validate with `Rule::enum(EnumClass::class)` in Request classes.
- Each enum exposes `values(): array` (plain strings) and `label(): string` (display text).

### 2. Form Request Classes
All controller form inputs are validated via **dedicated Request classes** in `App\Http\Requests\` sub-namespaced by feature (e.g., `App\Http\Requests\Auth\LoginRequest`, `App\Http\Requests\Task\StoreTaskRequest`).
Never validate directly in controller methods.

### 3. Blade-first, Livewire when Heavy
- Use **Blade + standard controller** for most pages (list views, detail pages, forms without live feedback).
- Use **Livewire** only for heavy interactivity: kanban drag-drop, Pomodoro timer, real-time dashboard widgets, group chat, live-search with instant results.

### 4. No `@extends`
Use `<x-layouts::app>` (sidebar layout) and `<x-layouts::auth>` (auth layout) component wrappers. Never `@extends('layouts.app')`.

### 5. Component-based UI
Reusable UI fragments live in `resources/views/components/` as anonymous Blade components with the `x-` prefix (e.g., `<x-stat-card>`, `<x-task-badge>`).

### 6. Namespacing
Every class must be in its own proper namespace. No classes dumped in root namespace. Sub-namespace by feature where the directory gets large.

### 7. Try-catch + Logging
Every **controller method** and **Livewire action** wraps its logic in:
```php
try {
    // logic
} catch (\Illuminate\Validation\ValidationException $e) {
    throw $e; // let Laravel handle validation errors
} catch (\Exception $e) {
    Log::error('Descriptive error message', [
        'user_id' => Auth::id(),
        'error'   => $e->getMessage(),
        // ... relevant context
    ]);
    throw $e;
}
```

### 8. Config-driven Branding
Always use `config('site.name')`, `config('site.logo')`, `config('site.tagline')`, `config('site.support_email')`.
**Never hardcode** the app name or any branding string.
Edit `config/site.php` or `.env` to change branding globally.
Env variables: `APP_NAME`, `APP_TAGLINE`, `APP_LOGO`, `APP_SUPPORT_EMAIL`.

### 9. Import at Top
All `use` statements go at the **top of every file**. Never use `\Full\Namespace\Class` inline in method bodies. This applies to facades, models, enums, requests, and all other classes.

### 10. Seeders for All Fixed Data
Every table with fixed types (achievements, sample departments, sample courses) has a **dedicated seeder**. Running `php artisan db:seed` should produce a complete, usable demo environment with an admin and a student account.

### 11. Build Gradually — Follow Phases
Complete one phase fully (controllers + requests + views + tests) before moving to the next. Follow the order in `BUILD_PROGRESS.md`. Always update `BUILD_PROGRESS.md` and `TECHNICAL_OVERVIEW.md` at the end of every session.

### 12. Commit After Every Feature or Bug Fix
- **Every completed feature and every bug fix must be committed before proceeding to the next.**
- Do **not** batch everything into one commit. Each logical unit of work gets its own commit.
- Commit order must follow build order — commit what was done first, then the next, then the next.
- Commit message format:
  - Features: `feat: <short description>` (e.g. `feat: custom auth system with email verification`)
  - Bug fixes: `fix: <short description>` (e.g. `fix: remove stale Fortify bootstrap cache reference`)
  - Chores/cleanup: `chore: <short description>` (e.g. `chore: remove Laravel Fortify and all dependencies`)
- Always `git add` only the files relevant to that specific feature/fix — do not `git add .` unless all staged files belong to that single commit.

---

## Tech Stack Reference

| | |
|---|---|
| Framework | Laravel 13 |
| PHP | 8.3+ |
| Frontend | Blade + Livewire 4 + Flux UI |
| Reactivity | Alpine.js (via Flux) |
| CSS | Tailwind CSS (via Flux) |
| Auth | Custom (no Fortify) |
| DB | MySQL |
| Real-time | Laravel Reverb + WebSockets |
| Notifications | Laravel Mail + Telegram Bot API |
| Storage | Laravel Local Storage |
| Testing | PestPHP |
| Queue | Database driver |

---

## Project Status

See `BUILD_PROGRESS.md` for current phase and remaining work.

---

## Files to Always Read at Start of Session

1. `docs/SYSTEM_PROMPTS.md` ← this file
2. `BUILD_PROGRESS.md` ← what's been done and what's next
3. `TECHNICAL_OVERVIEW.md` ← architecture reference
4. `student_time_management_system_specification.md` ← full feature spec
