<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ResetPasswordRequest;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ResetPasswordController extends Controller
{
    public function create(string $token): View
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    public function store(ResetPasswordRequest $request): RedirectResponse
    {
        try {
            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user) use ($request) {
                    $user->forceFill([
                        'password'       => Hash::make($request->password),
                        'remember_token' => Str::random(60),
                    ])->save();

                    event(new PasswordReset($user));
                }
            );

            if ($status === Password::PASSWORD_RESET) {
                return redirect()->route('login')->with('status', __($status));
            }

            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => __($status)]);
        } catch (\Exception $e) {
            Log::error('Reset password error', [
                'email' => $request->email,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
