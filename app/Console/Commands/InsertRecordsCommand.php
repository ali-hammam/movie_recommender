<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class InsertRecordsCommand extends Command
{
    protected $signature = 'insert:fromfile {file}';

    protected $description = 'Insert records from a file into the database';

    public function handle()
    {
        $file = $this->argument('file');

        $handle = fopen($file, "r");
        if ($handle) {
            $batchSize = 1000;
            $data = [];
            $count = 0;

            while (($line = fgets($handle)) !== false) {
                $seperateSentence = explode("::", $line);
                $data[] = [
                    'user_id' => (int) $seperateSentence[0],
                    'movie_id' => (int) $seperateSentence[1],
                    'rating' => $seperateSentence[2],
                ];

                if (++$count % $batchSize === 0) {
                    DB::table('user_ratings')->insert($data);
                    $data = [];
                }
            }

            // Insert the remaining records if any
            if (!empty($data)) {
                DB::table('user_ratings')->insert($data);
            }

            fclose($handle);
        }

        $this->info('Records inserted successfully.');
    }
}
