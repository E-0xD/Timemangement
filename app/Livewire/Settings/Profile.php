<?php

namespace App\Livewire\Settings;

use App\Concerns\ProfileValidationRules;
use App\Models\Department;
use Flux\Flux;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Title('Profile settings')]
class Profile extends Component
{
    use ProfileValidationRules, WithFileUploads;

    public string $name = '';
    public string $email = '';
    public string $bio = '';
    public string $phone = '';
    public string $school = '';
    public string $timezone = 'UTC';
    public string $language = 'en';
    public ?int $department_id = null;
    public $avatar_upload;

    public function mount(): void
    {
        $user = Auth::user();
        $this->name          = $user->name;
        $this->email         = $user->email;
        $this->bio           = $user->bio ?? '';
        $this->phone         = $user->phone ?? '';
        $this->school        = $user->school ?? '';
        $this->timezone      = $user->timezone ?? 'UTC';
        $this->language      = $user->language ?? 'en';
        $this->department_id = $user->department_id;
    }

    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            ...$this->profileRules($user->id),
            'bio'           => ['nullable', 'string', 'max:500'],
            'phone'         => ['nullable', 'string', 'max:20'],
            'school'        => ['nullable', 'string', 'max:255'],
            'timezone'      => ['nullable', 'string', 'max:100'],
            'language'      => ['nullable', 'string', 'max:10'],
            'department_id' => ['nullable', 'exists:departments,id'],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        Flux::toast(variant: 'success', text: __('Profile updated.'));
    }

    public function updateAvatar(): void
    {
        $this->validate(['avatar_upload' => ['required', 'image', 'max:2048']]);

        $user = Auth::user();
        $path = $this->avatar_upload->store("avatars/{$user->id}", 'public');

        // Delete old avatar if present
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->update(['avatar' => $path]);
        $this->reset('avatar_upload');

        Flux::toast(variant: 'success', text: __('Avatar updated.'));
    }

    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Flux::toast(text: __('A new verification link has been sent to your email address.'));
    }

    #[Computed]
    public function hasUnverifiedEmail(): bool
    {
        return Auth::user() instanceof MustVerifyEmail && ! Auth::user()->hasVerifiedEmail();
    }

    #[Computed]
    public function showDeleteUser(): bool
    {
        return ! Auth::user() instanceof MustVerifyEmail
            || (Auth::user() instanceof MustVerifyEmail && Auth::user()->hasVerifiedEmail());
    }

    public function render()
    {
        return view('livewire.settings.profile', [
            'departments' => Department::orderBy('name')->get(),
            'timezones'   => self::commonTimezones(),
            'languages'   => self::supportedLanguages(),
        ]);
    }

    public static function commonTimezones(): array
    {
        return [
            'UTC'                    => 'UTC',
            'Africa/Cairo'           => 'Africa / Cairo (EET)',
            'Africa/Lagos'           => 'Africa / Lagos (WAT)',
            'Africa/Nairobi'         => 'Africa / Nairobi (EAT)',
            'Africa/Johannesburg'    => 'Africa / Johannesburg (SAST)',
            'America/New_York'       => 'America / New York (EST)',
            'America/Chicago'        => 'America / Chicago (CST)',
            'America/Denver'         => 'America / Denver (MST)',
            'America/Los_Angeles'    => 'America / Los Angeles (PST)',
            'America/Toronto'        => 'America / Toronto (EST)',
            'Asia/Dubai'             => 'Asia / Dubai (GST)',
            'Asia/Kolkata'           => 'Asia / Kolkata (IST)',
            'Asia/Singapore'         => 'Asia / Singapore (SGT)',
            'Asia/Tokyo'             => 'Asia / Tokyo (JST)',
            'Asia/Shanghai'          => 'Asia / Shanghai (CST)',
            'Europe/London'          => 'Europe / London (GMT)',
            'Europe/Paris'           => 'Europe / Paris (CET)',
            'Europe/Berlin'          => 'Europe / Berlin (CET)',
            'Europe/Moscow'          => 'Europe / Moscow (MSK)',
            'Pacific/Auckland'       => 'Pacific / Auckland (NZST)',
            'Pacific/Sydney'         => 'Pacific / Sydney (AEST)',
        ];
    }

    public static function supportedLanguages(): array
    {
        return [
            'en' => 'English',
            'fr' => 'French',
            'es' => 'Spanish',
            'ar' => 'Arabic',
            'sw' => 'Swahili',
            'zh' => 'Chinese',
            'pt' => 'Portuguese',
            'de' => 'German',
        ];
    }
}
