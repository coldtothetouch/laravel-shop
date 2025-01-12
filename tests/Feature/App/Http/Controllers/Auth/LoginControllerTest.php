<?php

namespace App\Http\Controllers\Auth;

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
            ->assertViewIs('auth.login');
    }

    public function test_user_can_login()
    {
        $credentials = [
            'email' => 'test@example.com',
            'password' => 'password',
        ];

        $user = User::factory($credentials)->create();

        $response = $this->post(
            action([LoginController::class, 'store']),
            $credentials
        );

        $response->assertValid();

        $this->assertAuthenticatedAs($user);

        $response->assertRedirect(route('home'));
    }

    public function test_user_can_logout()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->delete(action([LoginController::class, 'destroy']));

        $response->assertRedirect(route('home'));

        $this->assertGuest();
    }
}
