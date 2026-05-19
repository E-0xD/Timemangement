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
        ]);
    }
}
