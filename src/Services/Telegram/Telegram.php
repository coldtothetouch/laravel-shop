<?php

namespace Services\Telegram;

use Illuminate\Support\Facades\Http;
use Services\Telegram\Exceptions\TelegramException;
use Throwable;

class Telegram
{
    public const HOST = 'https://api.telegram.org/bot';

    public static function sendMessage(string $token, string $chatId, string $message): bool
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
