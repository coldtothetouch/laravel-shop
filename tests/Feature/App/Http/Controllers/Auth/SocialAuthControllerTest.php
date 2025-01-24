<?php

namespace Tests\Feature\App\Http\Controllers\Auth;

use App\Http\Controllers\Auth\SocialAuthController;
use DomainException;
use Domains\Auth\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Laravel\Socialite\Facades\Socialite;
use Mockery\MockInterface;
use Tests\TestCase;

class SocialAuthControllerTest extends TestCase
{

    use RefreshDatabase;

    private function githubCallbackRequest()
    {
        return $this->get(action(
            [SocialAuthController::class, 'callback'],
            ['driver' => 'github'],
        ));
    }

    private function mockSocialiteCallback(string $githubId): MockInterface
    {
        $user = $this->mock(SocialiteUser::class,
            function (MockInterface $mock) use ($githubId) {
                $mock
                    ->shouldReceive('getId')
                    ->once()
                    ->andReturn($githubId);

                $mock
                    ->shouldReceive('getName')
                    ->once()
                    ->andReturn(fake()->name());

                $mock
                    ->shouldReceive('getEmail')
                    ->once()
                    ->andReturn(fake()->email());
            },
        );

        Socialite::shouldReceive('driver->user')->once()->andReturn($user);

        return $user;
    }

    public function test_user_created_after_github_callback()
    {
        $githubId = str()->random(10);

        $this->assertDatabaseMissing('users', [
            'github_id' => $githubId,
        ]);

        $this->mockSocialiteCallback($githubId);

        $this
            ->githubCallbackRequest()
            ->assertRedirect(route('home'));

        $this->assertAuthenticated();

        $this->assertDatabaseHas('users', [
            'github_id' => $githubId,
        ]);
    }

    public function test_existing_user_can_authenticate()
    {
        $user = User::factory()->create([
            'github_id' => str()->random(10),
        ]);

        $this->assertDatabaseHas('users', [
            'github_id' => $user->github_id,
        ]);

        $this->mockSocialiteCallback($user->github_id);

        $this
            ->githubCallbackRequest()
            ->assertRedirect(route('home'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_throws_driver_not_found_exception()
    {
        $this->expectException(DomainException::class);

        $this->withoutExceptionHandling()->get(action([
            SocialAuthController::class, 'redirect',
        ], ['driver' => 'vk']));

        $this->withoutExceptionHandling()->get(action([
            SocialAuthController::class, 'callback',
        ], ['driver' => 'vk']));
    }

}
