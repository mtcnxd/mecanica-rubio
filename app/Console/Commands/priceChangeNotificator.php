<?php

namespace App\Console\Commands;

use Exception;
use App\Traits\Messenger;
use Illuminate\Support\Number;
use Illuminate\Console\Command;
use App\Services\Bitso\BitsoService;
use App\Http\Helpers;

class priceChangeNotificator extends Command
{
    use Messenger;

    protected $bitsoService;

    public const MIN_PRICE_CHANGE = -5;
    public const MAX_PRICE_CHANGE = 10;
    public const DAYS = 15;
    public const BOOK = "btc_mxn";

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
        $book = self::BOOK;
        $lastPurchased = $this->bitsoService->lastPurchasedPrice($book);

        if (is_null($lastPurchased) || empty($lastPurchased)){
            return;
        }

        try {
            $currentPrice = $this->bitsoService->getBookPrice($book);
            $percentage = Helpers::convertToPercentage($currentPrice, $lastPurchased->price);

            $priceFormated = Number::currency($currentPrice);
            $lastBoughtFormated = Number::currency($lastPurchased->price);
            $percentageFormated = Number::percentage($percentage, 1);

            if ($percentage < self::MIN_PRICE_CHANGE){
                $diffDays = now()->diffInDays($lastPurchased->created_at);

                $message = "The Bitcoin price has already changed <b>{$percentageFormated}</b>\n\r".
                            "Last bought: <b>{$lastBoughtFormated}</b>\n\r".
                            "Current price: <b>{$priceFormated}</b>\n\r".
                            "Days before last bought: <b>{$diffDays}</b>";

                $this->telegram($message);

                if ($diffDays < self::DAYS){
                    if ($this->bitsoService->placeOrder($book, $currentPrice)){
                        $this->telegram("Order placed successfully");
                    }
                }
            }

        } catch (Exception $err){
            $this->telegram("Error while placing order | <b>{$err->getMessage()}</b>");
        }
    }
}
