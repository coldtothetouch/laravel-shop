<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordFormRequest;
use App\Http\Requests\ResetPasswordFormRequest;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    public function forgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function store(ResetPasswordFormRequest $request): RedirectResponse
    {
        $status = Password::reset(
            $request->validated(),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('auth.login')->with('message', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }

    public function sendEmail(ForgotPasswordFormRequest $request): RedirectResponse
    {
        $request->validated();

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['message' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    public function resetPassword(string $token): View
    {
        return view('auth.reset-password', compact('token'));
    }
}
