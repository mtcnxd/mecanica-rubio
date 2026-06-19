<?php

namespace App\Console\Commands;

use App\Traits\Notificator;
use App\Models\Investment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Number;

class UpdateInvestmentBalances extends Command
{
    use Notificator;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-investment-balances';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {        
        try {
            Investment::all()->each(function ($investment) {
                if ($investment->investmentData->last()){
                    DB::table('assets_increment')->insert([
                        'investment_id' => $investment->id,
                        'amount'     => $investment->investmentData->last()->amount,
                        'date'       => now(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            });

            $all = Investment::all();
            $currentBalance = Number::currency($all->sum('current_amount'));
    
            $this->sendNotification(
                sprintf("Process finished at: <b>%s</b> \n\rTotal amount today: <b>%s</b>", now()->format('g:i a'), $currentBalance), "HTML"
            );
        }

        catch (\Exception $e){
            $this->sendNotification("Error while updating balances | Error: {$e->getMessage()}");
        }
    }
}
