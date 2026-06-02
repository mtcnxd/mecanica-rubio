<?php

namespace App\Console\Commands;

use Exception;
use App\Traits\Notificator;
use Illuminate\Support\Number;
use Illuminate\Console\Command;
use App\Services\Investments\CryptoService as BitsoService;
use App\Services\Investments\BitsoApi;
use App\Http\Helpers;

class PriceChangeNotificator extends Command
{
    use Notificator;

    protected $bitsoService;

    public const MIN_PRICE_CHANGE = -5;
    public const MAX_PRICE_CHANGE = 10;
    public const DAYS = 15;
    public const BOOK = "btc_usdt";

    public function __construct()
    {
        parent::__construct();
        $this->bitsoService = new BitsoService(new BitsoApi());
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
        /**
         * TODO:: Implement to get last price
         */

        return;

        $lastPurchased = $this->bitsoService->lastPurchasedPrice(self::BOOK);

        if (is_null($lastPurchased) || empty($lastPurchased)){
            return;
        }

        try {
            $currentPrice = $this->bitsoService->getBookPrice(self::BOOK);
            $percentage = Helpers::convertToPercentage($currentPrice, $lastPurchased->price);

            $priceFormated = Number::currency($currentPrice);
            $lastBoughtFormated = Number::currency($lastPurchased->price);
            $percentageFormated = Number::percentage($percentage, 1);

            if ($percentage < self::MIN_PRICE_CHANGE){
                $diffDays = now()->diffInDays($lastPurchased->created_at);

                $message = "The Bitcoin price has already changed {$percentageFormated}\n\r".
                           "Last bought: {$lastBoughtFormated}\n\r".
                           "Current price: {$priceFormated}\n\r".
                           "Days before last bought: {$diffDays}";

                $this->sendNotification($message, "HTML");

                if ($diffDays < self::DAYS){
                    if ($this->bitsoService->placeOrder(self::BOOK, $currentPrice)){
                        $this->sendNotification("Order placed successfully");
                    }
                }
            }

        } catch (Exception $err){
            $this->sendNotification("*Error while placing order:* {$err->getMessage()}");
        }
    }
}
