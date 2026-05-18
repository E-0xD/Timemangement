<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class EmailVerificationController extends Controller
{
    public function notice(Request $request): View|RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        return view('auth.verify-email');
    }

    public function verify(EmailVerificationRequest $request): RedirectResponse
    {
        try {
            if ($request->user()->hasVerifiedEmail()) {
                return redirect()->intended(route('dashboard', absolute: false) . '?verified=1');
            }

            if ($request->fulfill()) {
                event(new Verified($request->user()));
            }

            return redirect()->intended(route('dashboard', absolute: false) . '?verified=1');
        } catch (\Exception $e) {
            Log::error('Email verification error', [
                'user_id' => $request->user()?->id,
                'error'   => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
