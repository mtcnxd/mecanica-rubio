<?php

namespace App\Console\Commands;

use App\Models\Investment;
use App\Notifications\Telegram;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Number;

class updateInvestmentBalances extends Command
{
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
        $telegram = new Telegram();
        
        try {
            Investment::all()->each(function ($investment) {
                if ($investment->investmentData->last()){
                    DB::table('assets_increment')->insert([
                        'investment_id' => $investment->id,
                        'amount'     => $investment->investmentData->last()->amount,
                        'date'       => Carbon::now(),
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                }
            });

            $all = Investment::all();
    
            $telegram->send(
                sprintf("Process finished at: %s \n\rToday total amount: <b>%s</b>", now(), Number::currency($all->sum('current_amount')))
            );        
        }

        catch (\Exception $e){
            Log::error('ERROR | MESSAGE: '. $e->getMessage())
            $telegram->send(
                sprintf('Error while updating data | Error: %s', $e->getMessage())
            );
        }
    }
}
