<?php

namespace App\Traits;

use App\Contracts\Notificator;

trait Messenger
{
    public function notify(Notificator $notificator, $message)
    {
        $notificator->send($message);
    }

    public function write(string $line)
    {
        // Write log message
    }
}