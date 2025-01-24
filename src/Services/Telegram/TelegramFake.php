<?php

namespace Services\Telegram;

class TelegramFake extends Telegram {

    static bool $result;

    public function returnTrue(): self
    {
        self::$result = true;
        return $this;
    }

    public function returnFalse(): self
    {
        self::$result = false;
        return $this;
    }

    public static function sendMessage(
        string $token,
        string $chatId,
        string $message
    ): bool
    {
        return self::$result;
    }

}
