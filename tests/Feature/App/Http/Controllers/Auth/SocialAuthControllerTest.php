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

class SocialAuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_is_redirected_to_github()
    {
        $response = $this->get(action(
            [SocialAuthController::class, 'redirect'],
            ['driver' => 'github']
        ));

        $response->assertRedirect();
    }

//    public function test_user_can_login_via_github()
//    {
//        $response = $this->get(action([SocialAuthController::class, 'callback']));
//
//        Socialite::shouldReceive('driver')
//            ->once()
//            ->with('github')
//            ->andReturn(Socialite::shouldReceive('user')->once());
//    }
}
