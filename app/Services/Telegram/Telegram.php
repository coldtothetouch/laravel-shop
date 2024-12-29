<?php

namespace App\Services\Telegram;

use App\Services\Telegram\Exceptions\TelegramException;
use Illuminate\Support\Facades\Http;
use Throwable;

class Telegram
{
    public const HOST = 'https://api.telegram.org/bot';

    public static function sendMessage(string $token, int $chatId, string $message): bool
    {
        try {
            $response = Http::get(self::HOST.$token.'/sendMessage', [
                'chat_id' => $chatId,
                'text' => $message
            ])->throw();

            return $response->json('ok') === true;
        } catch (Throwable $e) {
            report(new TelegramException($e->getMessage(), $e->getCode()));
            return false;
        }
    }
}
