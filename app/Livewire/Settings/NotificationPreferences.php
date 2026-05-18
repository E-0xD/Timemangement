<?php

namespace App\Livewire\Settings;

use App\Enums\NotificationType;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Notification settings')]
class NotificationPreferences extends Component
{
    public array $preferences = [];

    public function mount(): void
    {
        $saved = Auth::user()->notification_preferences ?? [];

        foreach (NotificationType::cases() as $type) {
            $this->preferences[$type->value] = (bool) ($saved[$type->value] ?? true);
        }
    }

    public function save(): void
    {
        Auth::user()->update(['notification_preferences' => $this->preferences]);

        Flux::toast(variant: 'success', text: __('Notification preferences saved.'));
    }

    public function render()
    {
        return view('livewire.settings.notification-preferences', [
            'types' => NotificationType::cases(),
        ]);
    }
}
