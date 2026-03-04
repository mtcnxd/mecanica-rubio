<?php

namespace App\Traits;

use App\Contracts\Notificator;
use Illuminate\Support\Facades\Log;
use App\Notifications\Telegram;

trait Messenger
{
    public function telegram($message)
    {
        try {
            $telegram = new Telegram();
            $telegram->send($message);
        }
        catch (\Exception $e){
            Log::error("ERROR: ". $e->getMessage());
        }
    }

    public function notify(Notificator $notificator, $message)
    {
        try {
            $notificator->send($message);
        }
        catch (\Exception $e){
            Log::error("ERROR: ". $e->getMessage());
        }
    }

    public function write(string $line)
    {
        // Write log message
    }
}