<?php

namespace Support\Logging\Telegram;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use Monolog\LogRecord;
use Services\Telegram\Telegram;

class TelegramHandler extends AbstractProcessingHandler
{

    public function __construct(protected array $config)
    {
        parent::__construct(
            Logger::toMonologLevel($config['level']),
        );
    }

    protected function write(LogRecord $record): void
    {
        Telegram::sendMessage(
            $this->config['token'],
            $this->config['chat_id'],
            $record->formatted,
        );
    }

}
