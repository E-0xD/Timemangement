<?php

namespace App\Livewire\Settings;

use App\Concerns\PasswordValidationRules;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Security settings')]
class Security extends Component
{
    use PasswordValidationRules;

    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => $this->currentPasswordRules(),
                'password'         => $this->passwordRules(),
            ]);

            Auth::user()->update([
                'password' => Hash::make($validated['password']),
            ]);

            $this->reset('current_password', 'password', 'password_confirmation');

            Flux::toast(variant: 'success', text: __('Password updated successfully.'));
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('Password update error', [
                'user_id' => Auth::id(),
                'error'   => $e->getMessage(),
            ]);

            Flux::toast(variant: 'danger', text: __('Failed to update password. Please try again.'));
        }
    }

    public function render()
    {
        return view('livewire.settings.security');
    }
}


