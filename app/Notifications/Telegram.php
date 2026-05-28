<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class Telegram extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */

    public function sendMessage(string $text, $parseMode)
    {
        $url = 'https://api.telegram.org/bot' . config('services.telegram.api_key') . '/sendMessage';
        
        $response = Http::post($url, array(
            "chat_id"    => config('services.telegram.chat_id'),
            "text" 	     => $text,
            "parse_mode" => $parseMode
        ));

        if ($response->getStatusCode() == 400){
            throw new \Exception("Error Processing Request: ". $response['description']);
        }
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
