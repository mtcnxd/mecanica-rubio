<?php

namespace App\Traits;

use App\Contracts\Notificator;
use Illuminate\Support\Facades\Log;

trait Messenger
{
    public function notify(Notificator $notificator, $message)
    {
        try {
            $notificator->send($message);
        }
        catch (\Exception $e){
            Log::error("AN ERROR OCURRED | ERROR: ". $e->getMessage());
        }
    }

    public function write(string $line)
    {
        // Write log message
    }
}