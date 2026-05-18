<?php

namespace App\Http\Controllers;

use App\Models\AppNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(): View
    {
        $notifications = AppNotification::where('user_id', Auth::id())
            ->orderByRaw('is_read ASC, created_at DESC')
            ->paginate(25);

        $unreadCount = AppNotification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->count();

        return view('notifications.index', compact('notifications', 'unreadCount'));
    }

    public function markRead(AppNotification $notification): RedirectResponse
    {
        abort_unless($notification->user_id === Auth::id(), 403);
        $notification->markAsRead();

        return back();
    }

    public function markAllRead(): RedirectResponse
    {
        AppNotification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        return back()->with('success', 'All notifications marked as read.');
    }

    public function destroy(AppNotification $notification): RedirectResponse
    {
        abort_unless($notification->user_id === Auth::id(), 403);

        try {
            $notification->delete();
        } catch (\Throwable $e) {
            Log::error('Notification delete failed', ['id' => $notification->id, 'error' => $e->getMessage()]);
            throw $e;
        }

        return back()->with('success', 'Notification deleted.');
    }
}
