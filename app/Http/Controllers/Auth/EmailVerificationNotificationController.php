<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EmailVerificationNotificationController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        try {
            if ($request->user()->hasVerifiedEmail()) {
                return redirect()->intended(route('dashboard', absolute: false));
            }

            $request->user()->sendEmailVerificationNotification();

            return back()->with('status', 'verification-link-sent');
        } catch (\Exception $e) {
            Log::error('Resend verification email error', [
                'user_id' => $request->user()?->id,
                'error'   => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
