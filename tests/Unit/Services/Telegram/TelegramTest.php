<?php

namespace Tests\Unit\Services\Telegram;

use Illuminate\Support\Facades\Http;
use Services\Telegram\Telegram;
use Services\Telegram\TelegramContract;
use Tests\TestCase;

class TelegramTest extends TestCase
{
    public function test_telegram_sends_message()
    {
        Http::fake([
            Telegram::HOST . '*' => Http::response(['ok' => true])
        ]);

        $result = Telegram::sendMessage('', '', '');

        $this->assertTrue($result);
    }

    public function test_telegram_sends_message_success_with_fake_instance() {
        Telegram::fake()->returnTrue();

        $this->assertTrue(
            app(TelegramContract::class)::sendMessage('', '', '')
        );
    }

    public function test_telegram_sends_message_fail_with_fake_instance() {
        Telegram::fake()->returnFalse();

        $this->assertFalse(
            app(TelegramContract::class)::sendMessage('', '', '')
        );
    }
}
