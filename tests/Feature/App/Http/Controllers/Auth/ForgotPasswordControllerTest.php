<?php

namespace Tests\Feature\App\Http\Controllers\Auth;

use App\Http\Controllers\Auth\ForgotPasswordController;
use Domains\Auth\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Support\Flash\Flash;
use Tests\TestCase;

class ForgotPasswordControllerTest extends TestCase
{

    use RefreshDatabase;

    private function testingCredentials()
    {
        return [
            'email' => 'test@example.com',
        ];
    }

    public function test_user_can_visit_forgot_password_page()
    {
        $this
            ->get(action([ForgotPasswordController::class, 'index']))
            ->assertOk()
            ->assertViewIs('auth.forgot-password');
    }

    public function test_user_receives_reset_password_letter()
    {
        $user = User::factory()->create();

        $this
            ->post(
                action([ForgotPasswordController::class, 'store']),
                $this->testingCredentials(),
            )
            ->assertValid()
            ->assertSessionHas(Flash::MESSAGE_KEY);

        Notification::assertSentTo($user, ResetPassword::class);
    }

    public function test_validation_fails_on_email()
    {
        $this->assertDatabaseMissing('users', $this->testingCredentials());

        $this
            ->post(
                action([ForgotPasswordController::class, 'store']),
                $this->testingCredentials(),
            )
            ->assertInvalid(['email']);

        Notification::assertNothingSent();
    }

}
