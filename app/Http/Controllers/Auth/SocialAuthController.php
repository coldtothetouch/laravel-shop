<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use DomainException;
use Domains\Auth\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class SocialAuthController extends Controller
{
    public function redirect(string $driver): RedirectResponse
    {
        try {
            return Socialite::driver($driver)->redirect();
        } catch (Throwable $e) {
            throw new DomainException('Произошла ошибка или драйвер не поддерживается');
        }
    }

    public function callback(string $driver): RedirectResponse
    {
        if ($driver !== 'github') {
            throw new DomainException('Произошла ошибка или драйвер не поддерживается');
        }

        $socialUser = Socialite::driver($driver)->user();

        $user = User::updateOrCreate([
            $driver.'_id' => $socialUser->id,
        ], [
            'name' => $socialUser->name ?? $socialUser->email,
            'email' => $socialUser->email,
            'password' => str()->random(20)
        ]);

        Auth::login($user);

        return redirect()->intended(route('home'));
    }
}