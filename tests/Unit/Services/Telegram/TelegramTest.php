<?php

namespace Services\Telegram;

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Http;
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
