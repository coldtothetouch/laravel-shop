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

class ForgotPasswordControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_visit_forgot_password_page()
    {
        $response = $this->get(action([ForgotPasswordController::class, 'index']));

        $response
            ->assertOk()
            ->assertViewIs('auth.forgot-password');
    }

    public function test_user_receives_reset_password_letter()
    {
        Notification::fake();

        $user = User::factory([
            'email' => 'test@example.com',
        ])->create();

        $response = $this->post(action([ForgotPasswordController::class, 'store']), [
            'email' => $user->email,
        ]);

        $response->assertValid();

        Notification::assertSentTo($user, ResetPassword::class);

        $response
            ->assertSessionHas(Flash::MESSAGE_KEY);
    }
}
