<?php

namespace App\Support\Flash;


class Flash
{
    public const MESSAGE_KEY = 'flash.message';
    public const CLASS_KEY = 'flash.class';

    public function get(): null|FlashMessage
    {
        $message = session(self::MESSAGE_KEY);

        if (!$message) {
            return null;
        }

        return new FlashMessage($message, session(self::CLASS_KEY));
    }

    public function info(string $message): void
    {
        $this->flash($message, 'info');
    }

    public function alert(string $message): void
    {
        $this->flash($message, 'alert');
    }

    private function flash(string $message, string $type)
    {
        session()->flash(self::MESSAGE_KEY, $message);
        session()->flash(self::CLASS_KEY, config("flash.$type", ''));
    }
}
