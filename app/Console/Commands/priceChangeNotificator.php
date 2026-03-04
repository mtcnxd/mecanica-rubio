<?php

namespace App\Console\Commands;

use App\Traits\Messenger;
use Illuminate\Support\Number;
use App\Notifications\Telegram;
use Illuminate\Console\Command;
use App\Services\Bitso\BitsoClient;
use App\Services\Bitso\BitsoService;
use App\Models\BitsoData;
use App\Http\Helpers;

class priceChangeNotificator extends Command
{
    use Messenger;

    public const MIN_PRICE_CHANGE = -5;
    public const MAX_PRICE_CHANGE = 10;

    public function __construct()
    {
        parent::__construct();
        $this->bitsoService = new BitsoService();
    }

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
        $currentBtcPrice = $this->bitsoService->getBookPrice('btc_mxn');
        $lastPurchased = $this->bitsoService->lastPurchasedPrice('btc_mxn');

        $percentage = Helpers::convertToPercentage($currentBtcPrice->last, $lastPurchased->price);

        $currentPrice = Number::currency($currentBtcPrice->last);
        $lastBought = Number::currency($lastPurchased->price);
        $calculated = Number::percentage($percentage, 1);
        
        if ($percentage < -5){
            $message = sprintf("The Bitcoin price has already falls over <b>%s</b> since last bought\n\r".
            				   "Last bought: <b>%s</b>\n\rCurrent price: <b>%s</b>", $calculated, $lastBought, $currentPrice);

            $this->telegram($message);
            $this->placeOrder();
        }
    }

    protected function placeOrder()
    {
        try {
            $response = $this->bitsoService->placeOrder([
                'book' => "btc_mxn",
                'side' => "buy",
                'type' => "limit",
                'major' => "0.0005",
                'price' => "1000000",
            ]);

            $this->telegram("Order placed successfully");
        }

        catch(\Exception $err){
            $this->telegram("Error while placing order | <b>{$err->getMessage()}</b>");
        }
    }
}
