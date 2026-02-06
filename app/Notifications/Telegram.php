<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

use App\Contracts\Notificator;

class Telegram extends Notification implements Notificator
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    public function send(string $text)
    {
        $token = DB::table('settings')->where('name','telegram_api')->first()->value;
        
        $url = 'https://api.telegram.org/'. $token .'/sendMessage';
        
        $response = Http::post($url, array(
            "chat_id"    => '-1002434117829',
            "text" 	     => $text,
            "parse_mode" => "HTML"
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
