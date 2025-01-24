<?php

namespace Tests\Feature\App\Http\Controllers\Auth;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Domains\Auth\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Password;
use Illuminate\Testing\TestResponse;
use Support\Flash\Flash;
use Tests\TestCase;

class ResetPasswordControllerTest extends TestCase
{

    use RefreshDatabase;

    private User $user;
    private string $token;
    private array $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user  = User::factory()->create([
            'email' => 'coldtothetouch@dirtyanimals.ru',
        ]);


        $this->token = Password::createToken($this->user);

        $password = 'new_password';

        $this->request = [
            'email'                 => $this->user->email,
            'password'              => $password,
            'password_confirmation' => $password,
            'token'                 => $this->token,
        ];
    }

    private function request(): TestResponse
    {
        return $this->post(action([ResetPasswordController::class, 'store',]),
            $this->request);
    }

    public function test_user_can_visit_reset_password_page()
    {
        $this
            ->get(action([ResetPasswordController::class, 'index'],
                ['token' => $this->token]))
            ->assertOk()
            ->assertViewIs('auth.reset-password');
    }

    public function test_user_can_reset_password()
    {
        Password::shouldReceive('reset')
            ->once()
            ->withSomeOfArgs($this->request)
            ->andReturn(Password::PASSWORD_RESET);

        $this
            ->request()
            ->assertRedirect(action([LoginController::class, 'index',]))
            ->assertSessionHas(Flash::MESSAGE_KEY);
    }

    public function test_password_reset_event_was_dispatched()
    {
        Event::fake();

        $this->request();

        Event::assertDispatched(PasswordReset::class);
    }

}
