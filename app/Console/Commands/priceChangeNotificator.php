<?php

namespace App\Console\Commands;

use App\Traits\Messenger;
use Illuminate\Support\Number;
use App\Notifications\Telegram;
use Illuminate\Console\Command;

class priceChangeNotificator extends Command
{
    use Messenger;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:price-change-notificator';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Price falling over 5% notificator';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $price = Number::currency(1500123.00);

        $message = sprintf("The Bitcoin price has already fall over 5%% since last bought.\n\rThe current BTC price is: <b>%s</b>", $price);

        $this->notify(new Telegram, $message);
    }
}
