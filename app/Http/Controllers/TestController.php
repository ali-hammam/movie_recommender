<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

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
        $file = fopen(base_path('users.dat'), 'r');
        $collectorArray = [];
        // DB::table('movie_users')->truncate();

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

    public function moveMovieClustersToDb()
    {
        $file = fopen(base_path('cluster_items.dat'), 'r');
        $collectorArray = [];
        DB::table('movie_clusters')->truncate();

        if ($file) {
            while (($line = fgets($file)) !== false) {
                $seperateSentence = explode("|", $line);
                $removeNewLine = str_replace("\r\n", "", $seperateSentence[3]);

                array_push($collectorArray, [
                    'cluster_id' => (int) $seperateSentence[0],
                    'movie_id' => (int) $seperateSentence[1],
                    'title' => $seperateSentence[2],
                    'predicted_rating' => $removeNewLine
                ]);
            }
        } else {
            // Error opening file
        }

        $chunks = array_chunk($collectorArray, 1000);

        foreach ($chunks as $chunk) {
            DB::table('movie_clusters')->insert($chunk);
        }

        print_r(json_encode(array_slice($collectorArray, 0, 100)));
    }

    public function moveUserClustersToDb()
    {
        $file = fopen(base_path('cluster_users.dat'), 'r');
        $collectorArray = [];
        DB::table('user_clusters')->truncate();

        if ($file) {
            while (($line = fgets($file)) !== false) {
                $seperateSentence = explode("|", $line);
                $removeNewLine = str_replace("\r\n", "", $seperateSentence[1]);

                array_push($collectorArray, [
                    'cluster_id' => (int) $seperateSentence[0],
                    'user_id' => (int) $removeNewLine,
                ]);
            }
        } else {
            // Error opening file
        }

        $chunks = array_chunk($collectorArray, 1000);

        foreach ($chunks as $chunk) {
            DB::table('user_clusters')->insert($chunk);
        }

        print_r(json_encode(array_slice($collectorArray, 0, 100)));
    }

    public function addImagesToMovies()
    {
        $imagePath = public_path('images'); // Specify the directory where your images are stored
        $files = File::allFiles($imagePath);

        $imagePaths = [];

        foreach ($files as $file) {
            // Save the image path to the database
            $imagePaths[] = [
                'image' => asset('images/' . $file->getFilename()),
                'movie_id' => (int) pathinfo($file->getFilename(), PATHINFO_FILENAME)
            ];
        }

        $chunks = array_chunk($imagePaths, 1000);

        foreach ($chunks as $chunk) {
            DB::table('movie_images')->insert($chunk);
        }

        print_r(json_encode($imagePaths));
    }
}
