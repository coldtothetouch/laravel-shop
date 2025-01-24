<?php

namespace Services\Telegram;

interface TelegramContract
{

    public static function sendMessage(
        string $token,
        string $chatId,
        string $message,
    ): bool;

}
