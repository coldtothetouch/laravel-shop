<?php

namespace Tests\Unit\Services\Telegram;

use Illuminate\Support\Facades\Http;
use Services\Telegram\Telegram;
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
}
