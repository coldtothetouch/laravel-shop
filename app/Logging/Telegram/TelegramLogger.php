<?php

namespace App\Logging\Telegram;

use Monolog\Logger;

class TelegramLogger
{
    public function __invoke(array $config): Logger
    {
        return new Logger('telegram')->pushHandler(new TelegramHandler($config));
    }
}
