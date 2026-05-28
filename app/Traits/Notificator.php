<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;
use App\Notifications\Telegram;

trait Notificator
{
    public function sendNotification(string $message, $parseMode = "MarkdownV2")
    {
        try {
            $telegram = new Telegram();
            $telegram->sendMessage($message, $parseMode);

        } catch (\Exception $e){
            Log::error("ERROR: ". $e->getMessage());

            throw new \Exception("Error al enviar notificación: {$e->getMessage()}");
        }
    }

    public function sendMail()
    {
        // TODO: Implement
    }

    public function escapeMarkdown($message)
    {
        return preg_replace('/([_*\[\]()~`>#+\-=|{}.!])/', '\\\\$1', $message);
    }
}