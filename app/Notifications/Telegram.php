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

    protected $token;
    protected $channel;

    /**
     * Create a new notification instance.
     */
    public function __construct($channel = 'mecanica')
    {
        /*
        $this->token = DB::table('settings')->where('name','telegram_api')->first();

        if (empty($this->token) || is_null($this->token)){
            throw new \Exception("Telegram API token is not configured");
        }
        */

        $this->setConfig($channel);
    }

    public function setConfig(string $channel)
    {
        switch($channel){
            case 'mecanica':
                $this->channel = '-1002434117829';
                $this->token = 'bot8169963766:AAGGQYcAR-bwEew8p9Amo5SWb-PL79IQGAM';
                break;

            case 'trading':
                $this->channel = '-5014845636';
                $this->token = 'bot8373335422:AAHcXOLPxVUZHMg5gQW1Zb_FZ7itqeuIm6I';
                break;
        }
    }

    protected function getConfig()
    {
        return [
            'token' => $this->token,
            'channel' => $this->channel,
        ];
    }

    public function send(string $text)
    {
        $config = $this->getConfig();

        $url = 'https://api.telegram.org/'. $config['token'] .'/sendMessage';
        
        $response = Http::post($url, array(
            "chat_id"    => $config['channel'],
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
