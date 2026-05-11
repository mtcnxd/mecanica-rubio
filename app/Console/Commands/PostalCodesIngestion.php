<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class PostalCodesIngestion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:postal-codes-ingestion';

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
        print("Start import postal codes".PHP_EOL);

        try {
            $filename = "codigos.csv";
            $this->storeData($filename);
        
        } catch (\Exception $e){
            print("Error: ". $e->getMessage().PHP_EOL);
        }
    }

    private function storeData($filename)
    {
        $path = __DIR__."/".$filename; 

        $fopen = fopen ($path, "r");

        $count = 0;
        while ($data = fgetcsv ($fopen, 1000, ",")) {
            if ($count > 0){
                $this->insert([
                    "postalcode" => $data[0],
                    "address"    => $data[1],
                    "city"       => $data[2],
                    "state"      => $data[3],
                ]);
            }
            $count++;
        }

        print("Data inserted successfully");
    }

    private function insert($data)
    {
        DB::table('postalcodes')->insert([
            'postalcode' => $data['postalcode'],
            'address'    => $data['address'],
            'city'       => $data['city'],
            'state'      => $data['state']
        ]);
    }
}
