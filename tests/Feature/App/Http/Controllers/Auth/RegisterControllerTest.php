<?php

namespace App\Http\Controllers\Auth;

use App\Listeners\SendEmailToNewUser;
use App\Notifications\NewUserNotification;
use Domains\Auth\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_visit_register_page()
    {
        $response = $this->get(action([RegisterController::class, 'index']));

        $response
            ->assertOk()
            ->assertViewIs('auth.register');
    }

    public function test_user_can_register()
    {
        Notification::fake();
        Event::fake();

        $request = [
            'name' => 'Test Name',
            'email' => 'test@google.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $this->assertDatabaseMissing('users', [
            'email' => $request['email']
        ]);

        $response = $this->post(
            action([RegisterController::class, 'store']),
            $request,
        );

        $response->assertValid();

        $this->assertDatabaseHas('users', [
            'email' => $request['email']
        ]);

        $user = User::query()->where('email', $request['email'])->first();

        Event::assertDispatched(Registered::class);
        Event::assertListening(Registered::class, SendEmailToNewUser::class);

        new SendEmailToNewUser()->handle(new Registered($user));
        Notification::assertSentTo($user, NewUserNotification::class);

        $this->assertAuthenticatedAs($user);

        $response->assertRedirect(route('home'));
    }
}
