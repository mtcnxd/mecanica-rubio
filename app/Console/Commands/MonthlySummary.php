<?php

namespace App\Console\Commands;

use App\Traits\Messenger;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Number;

class MonthlySummary extends Command
{
    use Messenger;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:monthly-summary';

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
        $startOfmonth = DB::table('assets_increment')
            ->where('date', now()->startOfMonth())
            ->get();

        $endOfmonth = DB::table('assets_increment')
            ->where('date', now()->endOfMonth())
            ->get();

        if ($startOfmonth && $endOfmonth){
            $totalStart = $startOfmonth->sum('amount');
            $totalEnd = $endOfmonth->sum('amount');

            $diff = Number::currency($totalEnd - $totalStart);
            $message = sprintf("Last month you saved: <b>%s MXN</b>", $diff);

            $this->telegram($message);
        }
    }
}
