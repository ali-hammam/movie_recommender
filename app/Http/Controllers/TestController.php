<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    public function moveMoviesToDb()
    {
        $file = fopen(base_path('movies.dat'), 'r');
        $collectorArray = [];

        if ($file) {
            while (($line = fgets($file)) !== false) {
                $seperateSentence = explode("::", $line);
                $removeNewLine = str_replace("\n", "", $seperateSentence[2]);
                $genres = explode("|", $removeNewLine);

                Movie::create([
                    'id' => (int) $seperateSentence[0],
                    'title' => $seperateSentence[1],
                    'genres' => json_encode($genres)
                ]);
            }
        } else {
            // Error opening file
        }

        print_r((array_slice($collectorArray, 0, 100)));
    }

    public function moveOccupationsToDb()
    {
        $file = fopen(base_path('occupation.dat'), 'r');
        $collectorArray = [];
        DB::table('occupations')->truncate();

        if ($file) {
            while (($line = fgets($file)) !== false) {
                $seperateSentence = explode("::", $line);
                $removeNewLine = str_replace("\r\n", "", $seperateSentence[1]);
                array_push($collectorArray, [
                    'occupation_id' => $seperateSentence[0],
                    'occupation' => $removeNewLine
                ]);
            }
        } else {
            // Error opening file
        }

        DB::table('occupations')->insert($collectorArray);
        print_r(json_encode(array_slice($collectorArray, 0, 100)));
    }

    public function moveUsersToDb()
    {
        $file = fopen(base_path('users_from_5941.dat'), 'r');
        $collectorArray = [];
        DB::table('movie_users')->truncate();

        if ($file) {
            while (($line = fgets($file)) !== false) {
                $seperateSentence = explode("::", $line);
                $removeNewLine = str_replace("\r\n", "", $seperateSentence[4]);

                array_push($collectorArray, [
                    'user_id' => (int) $seperateSentence[0],
                    'gender' => $seperateSentence[1],
                    'age' => $seperateSentence[2],
                    'occupation_id' => (int) $seperateSentence[3],
                    'zip' => $removeNewLine
                ]);
            }
        } else {
            // Error opening file
        }

        DB::table('movie_users')->insert($collectorArray);
        print_r(json_encode(array_slice($collectorArray, 0, 100)));
    }

    public function moveUsersRatingsToDb()
    {
        $file = fopen(base_path('ratings_from_5941.dat'), 'r');
        $collectorArray = [];
        DB::table('user_ratings')->truncate();

        if ($file) {
            while (($line = fgets($file)) !== false) {
                $seperateSentence = explode("::", $line);

                array_push($collectorArray, [
                    'user_id' => (int) $seperateSentence[0],
                    'movie_id' => (int) $seperateSentence[1],
                    'rating' => $seperateSentence[2],
                ]);
            }
        } else {
            // Error opening file
        }

        $chunks = array_chunk($collectorArray, 100);

        foreach ($chunks as $chunk) {
            DB::table('user_ratings')->insert($chunk);
        }

        print_r(json_encode(array_slice($collectorArray, 0, 100)));
    }
}
