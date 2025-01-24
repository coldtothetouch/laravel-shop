<?php

namespace Tests\Feature\App\Http\Controllers\Auth;

use App\Http\Controllers\Auth\LoginController;
use Domains\Auth\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_visit_login_page()
    {
        $response = $this->get(action([LoginController::class, 'index']));

        $response
            ->assertOk()
            ->assertSee('Вход в аккаунт')
            ->assertViewIs('auth.login');
    }

    public function test_user_can_login()
    {
        $password = '1234567890';

        $credentials = [
            'email' => 'test@example.com',
            'password' => bcrypt($password),
        ];

        $user = User::factory()->create($credentials);

        $response = $this->post(
            action([LoginController::class, 'store']), [
                'email' => $user->email,
                'password' => $password,
            ]
        );

        $response->assertValid()
            ->assertRedirect(route('home'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_validation_fail_on_email()
    {
        $this->post(action([LoginController::class, 'store']), [
            'email' => 'notfound@dirtyanimals.ru',
            'password' => '1234567890',
        ])->assertInvalid(['email']);

        $this->assertGuest();
    }

    public function test_user_can_logout()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->delete(action([LoginController::class, 'destroy']));

        $response->assertRedirect(route('home'));

        $this->assertGuest();
    }
}
