<?php

namespace App\Http\Controllers\Auth;

use Domains\Auth\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Support\Flash\Flash;
use Tests\TestCase;

class ResetPasswordControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_visit_reset_password_page()
    {
        $token = Password::createToken(User::factory()->create());

        $response = $this->get(action(
            [ResetPasswordController::class, 'index'],
            $token
        ));

        $response
            ->assertOk()
            ->assertViewIs('auth.reset-password');
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
}
