<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Support\Flash\Flash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Tests\TestCase;

class ResetPasswordControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_visit_forgot_password_page()
    {
        $response = $this->get(action([ResetPasswordController::class, 'forgotPassword']));

        $response
            ->assertOk()
            ->assertViewIs('auth.forgot-password');
    }

    public function test_user_can_reset_password()
    {
        Event::fake();

        $user = User::factory()->create();
        $token = Password::createToken(User::first());;
        $newPassword = 'new_password';

        $response = $this->post(action([ResetPasswordController::class, 'store']), [
            'token' => $token,
            'email' => $user->email,
            'password' => $newPassword,
            'password_confirmation' => $newPassword,
        ]);

        $response->assertValid();

        $this->assertTrue(Hash::check($newPassword, User::first()->password));
        Event::assertDispatched(PasswordReset::class);

        $response
            ->assertRedirect(route('auth.login.index'))
            ->assertSessionHas(Flash::MESSAGE_KEY);
    }

    public function test_user_receives_reset_password_letter()
    {
        Notification::fake();

        $user = User::factory([
            'email' => 'test@example.com',
        ])->create();

        $response = $this->post(action([ResetPasswordController::class, 'sendEmail']), [
            'email' => $user->email,
        ]);

        $response->assertValid();

        Notification::assertSentTo($user, ResetPassword::class);

        $response
            ->assertSessionHas(Flash::MESSAGE_KEY);
    }
}
