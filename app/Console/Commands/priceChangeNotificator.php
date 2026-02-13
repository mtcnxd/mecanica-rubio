<?php

namespace App\Console\Commands;

use App\Traits\Messenger;
use Illuminate\Support\Number;
use App\Notifications\Telegram;
use Illuminate\Console\Command;
use App\Services\ApiBitsoService;
use App\Models\BitsoData;
use App\Http\Helpers;

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
        $api = new ApiBitsoService();
        
        $currentBtcPrice = $api->getBookPrice('btc_mxn');        
        $lastPurchased = $this->lastPurchasedPrice('btc_mxn');

        $percentage = Helpers::convertToPercentage($currentBtcPrice->last, $lastPurchased->price);

        $calculated = Number::percentage($percentage, 1);
        $lastBought = Number::currency($lastPurchased->price);
        $currentPrice = Number::currency($currentBtcPrice->last);

        $telegram = new Telegram('trading');

        if ($percentage < -5){
            $message = sprintf("The Bitcoin price has already fallen over <b>%s</b> \n\rLast bought: <b>%s</b>\n\rCurrent price: <b>%s</b>", $calculated, $lastBought, $currentPrice);
            $this->notify($telegram, $message);
        }
        
        if ($percentage > 10){
            $message = sprintf("The Bitcoin price has already risen over <b>%s</b> \n\rLast bought: <b>%s</b>\n\rCurrent price: <b>%s</b>", $calculated, $lastBought, $currentPrice);
            $this->notify($telegram, $message);
        }
    }

    protected function lastPurchasedPrice(string $book)
    {
        return BitsoData::where('book', $book)
            ->where('active', true)
            ->orderBy('created_at', 'desc')
            ->first();
    }

    protected function placeOrder(string $price)
    {
        $this->notify(new Telegram('trading'), 
            sprintf("We have placed a bitcoin order with current price: %s", $price)
        );
    }
}
