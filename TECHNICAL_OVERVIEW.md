# Technical Overview

> Auto-updated each build session. Last updated: **2026-05-18**
> Reference: `BUILD_PROGRESS.md` | `student_time_management_system_specification.md`

---

## Stack

| Layer | Technology |
|---|---|
| Language | PHP 8.3+ |
| Framework | Laravel 13 |
| Frontend | Blade + Livewire 4 + Flux UI |
| Reactivity | Alpine.js (bundled with Flux) |
| Styling | Tailwind CSS (via Flux) |
| Authentication | Custom (Laravel built-ins — no Fortify) |
| Database | MySQL |
| Real-time | Laravel Reverb + WebSockets |
| Notifications | Laravel Mail + Telegram Bot API |
| File Storage | Laravel Storage (local) |
| Testing | PestPHP |
| Queue | Laravel Queue (database driver) |

---

## Architecture Decisions

### Authentication
- **Custom — no Fortify.** Built with Laravel's `Auth` facade, `Hash`, `Password` broker, and `MustVerifyEmail` interface.
- **Rate limiting** on login: 5 attempts per minute via `RateLimiter`.
- **Email verification** required before accessing the dashboard (`auth` + `verified` middleware).
- **Password reset** via signed email tokens using Laravel's built-in `Password` broker.
- No 2FA (removed with Fortify; can be re-added with a standalone package if required).

### Validation
- All controller form inputs use dedicated **Form Request classes** in `App\Http\Requests\` (sub-namespaced by feature).
- Livewire components use inline `$this->validate()` for interactive forms.
- Enum values validated with `Rule::enum(EnumClass::class)` where applicable.

### Enums (String-backed)
- All fixed-option fields in the database use **PHP string-backed enums** in `App\Enums\`.
- Enums are **cast directly on models**: `'role' => UserRole::class`.
- Database stores the enum string value (human-readable, portable, no integer magic).
- Each enum has a `values(): array` helper for validation rules and `label(): string` for display.

### Views / Blade
- **No `@extends`**. All pages use `<x-layouts::app>` (sidebar) or `<x-layouts::auth>` (auth pages).
- **Livewire** only for heavy interactivity: kanban board, Pomodoro timer, real-time dashboard widgets, group chat.
- **Standard Blade + controller** for everything else.
- Reusable UI: `resources/views/components/` as `x-` anonymous components.

### Configuration
- `config/site.php` — centralised branding: name, tagline, logo path, support email.
- Always use `config('site.name')`, `config('site.logo')`, etc. **Never hardcode.**
- `APP_NAME`, `APP_TAGLINE`, `APP_LOGO`, `APP_SUPPORT_EMAIL` in `.env` control these values.

### Error Handling
- Every controller method and Livewire action wraps logic in `try { … } catch`.
- `ValidationException` is re-thrown unchanged (let Laravel handle it).
- All other exceptions are logged with `Log::error(…)` including `user_id`, relevant context, and `error` message, then re-thrown.

---

## Namespace Map

```
App\Http\Controllers\Auth\       — Login, Register, Logout, ForgotPassword, ResetPassword, EmailVerification*
App\Http\Controllers\            — Feature controllers (Task, Timetable, Calendar, Goal, etc.)
App\Http\Requests\Auth\          — Auth form requests
App\Http\Requests\               — Feature form requests (sub-namespaced by feature)
App\Models\                      — Eloquent models
App\Enums\                       — String-backed enums
App\Livewire\                    — Livewire components
App\Livewire\Settings\           — Settings Livewire components
App\Livewire\Actions\            — Livewire action classes
App\Concerns\                    — Shared traits (PasswordValidationRules, ProfileValidationRules)
App\Providers\                   — Service providers
```

---

## Directory Structure

```
app/
  Concerns/                 — Reusable traits
  Enums/                    — String-backed enums
  Http/
    Controllers/
      Auth/                 — Authentication controllers
    Requests/
      Auth/                 — Auth form request classes
  Livewire/
    Actions/                — Livewire action classes (Logout)
    Settings/               — Settings Livewire components
  Models/                   — Eloquent models
  Providers/                — AppServiceProvider only
config/
  site.php                  — App branding & identity ← edit here for name/logo
  app.php                   — Laravel core config
routes/
  auth.php                  — All auth routes (login, register, password, email verify)
  settings.php              — Settings routes (Livewire)
  web.php                   — Main routes (dashboard, home)
resources/views/
  auth/                     — Auth Blade views (login, register, forgot-password, reset-password, verify-email)
  components/               — x- Blade components (app-logo, auth-header, etc.)
  layouts/
    app/                    — App layout partials (sidebar.blade.php, header.blade.php)
    auth/                   — Auth layout variants (simple, card, split)
    app.blade.php           — Wraps x-layouts::app.sidebar
    auth.blade.php          — Wraps x-layouts::auth.simple
  livewire/
    settings/               — Settings Livewire views
  partials/                 — head.blade.php, settings-heading.blade.php
database/
  migrations/               — All database migrations
  seeders/                  — All database seeders
  factories/                — Model factories
```

---

## Database Schema

### `users`
| Column | Type | Notes |
|---|---|---|
| id | bigint unsigned | PK |
| name | varchar(255) | |
| email | varchar(255) | unique |
| email_verified_at | timestamp | nullable |
| password | varchar(255) | bcrypt hashed |
| role | varchar(255) | enum: `student`, `admin` (default: `student`) |
| avatar | varchar(255) | nullable — path to uploaded avatar |
| bio | varchar(500) | nullable |
| phone | varchar(20) | nullable |
| school | varchar(255) | nullable |
| timezone | varchar(100) | default: `UTC` |
| language | varchar(10) | default: `en` |
| telegram_chat_id | varchar(255) | nullable |
| dark_mode | tinyint(1) | default: `0` |
| remember_token | varchar(100) | nullable |
| created_at / updated_at | timestamp | |

---

## Enums (Current)

| Enum | File | Values | Used In |
|---|---|---|---|
| `UserRole` | `App\Enums\UserRole` | `student`, `admin` | `users.role` |

*(Full enum list will expand in Phase 2)*

---

## Key Conventions

1. **Import at top.** All `use` statements at the top of every file. Never `\Full\Namespace\Class` inline.
2. **Try-catch everywhere.** Controller methods and Livewire actions always wrap in try-catch + `Log::error()`.
3. **Config for branding.** `config('site.name')` not `'StudyFlow'`. `config('site.logo')` not a hardcoded path.
4. **Enum values.** Use `EnumClass::cases()` to iterate. `EnumClass::values()` for plain string array (validation).
5. **No `@extends`.** Use `<x-layouts::app>` and `<x-layouts::auth>` layout components.
6. **Request classes.** Every controller form submission has its own `FormRequest` subclass.
7. **Livewire for interactivity.** Standard Blade for static pages. Livewire for real-time, drag-drop, timers.
8. **Seeders for fixtures.** Every table with fixed options (achievements, etc.) has a seeder.
9. **Gradual build.** Follow phases in `BUILD_PROGRESS.md`. Complete one phase before the next.
