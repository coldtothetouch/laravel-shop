<?php

namespace Tests\Feature\App\Http\Controllers\Auth;

use App\Http\Controllers\Auth\RegisterController;
use App\Listeners\SendEmailToNewUser;
use App\Notifications\NewUserNotification;
use Domains\Auth\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    use RefreshDatabase;

    protected array $request;

    public function getTestUser()
    {
        return User::query()
            ->where('email', $this->request['email'])
            ->first();
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = [
            'name'                  => 'Test User',
            'email'                 => 'coldtothetouch@dirtyanimals.ru',
            'password'              => '1234567890',
            'password_confirmation' => '1234567890',
        ];
    }

    private function request(): TestResponse
    {
        return $this->post(
            action([RegisterController::class, 'store']),
            $this->request,
        );
    }

    public function test_user_can_visit_register_page()
    {
        $this
            ->get(action([RegisterController::class, 'index']))
            ->assertOk()
            ->assertSee('Регистрация')
            ->assertViewIs('auth.register');
    }

    public function test_request_is_valid()
    {
        $this->request()->assertValid();
    }

    public function test_validation_fails_on_password_confirmation()
    {
        $this->request['password'] = '123';
        $this->request['password_confirmation'] = '1234';

        $this->request()->assertInvalid(['password']);
    }

    public function test_user_created_successfully()
    {
        $this->assertDatabaseMissing('users', [
            'email' => $this->request['email'],
        ]);

        $this->request();

        $this->assertDatabaseHas('users', [
            'email' => $this->request['email'],
        ]);
    }

    public function test_validation_fails_on_unique_email()
    {
        User::factory()->create([
            'email' => $this->request['email'],
        ]);

        $this->assertDatabaseHas('users', [
            'email' => $this->request['email'],
        ]);

        $this->request()->assertInvalid('email');
    }

    public function test_registered_event_is_firing_and_listeners_dispatching()
    {
        Event::fake();

        $this->request();

        Event::assertDispatched(Registered::class);
        Event::assertListening(Registered::class, SendEmailToNewUser::class);
    }

    public function test_notification_sent()
    {
        $this->request();

        Notification::assertSentTo(
            $this->getTestUser(),
            NewUserNotification::class,
        );
    }

    public function test_user_is_authenticated_and_redirected()
    {
        $this->request()->assertRedirect(route('home'));

        $this->assertAuthenticatedAs($this->getTestUser());
    }

}
