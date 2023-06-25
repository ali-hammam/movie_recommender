<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MovieController extends Controller
{
    public function getListOfGenres(Request $request)
    {
        //$movies = Movie::whereJsonContains('genres', ['Animation', 'Action'])->get();
        $uniqueGenres = Movie::select(DB::raw('distinct JSON_UNQUOTE(JSON_EXTRACT(genres, "$[*]")) as genre'))
            ->pluck('genre');

        $temp = [];
        for ($i = 0; $i < $uniqueGenres->count(); $i++) {
            array_push($temp, json_decode($uniqueGenres[$i]));
        }

        $temp = collect($temp)->flatten()->unique();

        return response()->json([
            'genres' => $temp->values()
        ]);
    }

    public function getMoviesByGenre(Request $request)
    {
        //$movies = Movie::whereJsonContains('genres', $request['genres'])->get();
        $selectedGenres = $request['genres'];
        $movies = Movie::where(function ($query) use ($selectedGenres) {
            foreach ($selectedGenres as $genre) {
                $query->orWhereJsonContains('genres', $genre);
            }
        })->join('user_ratings', "movies.id", "=", "user_ratings.movie_id")
            ->select('movies.id', 'movies.title', 'movies.genres', DB::raw('AVG(user_ratings.rating) as average_rating'))
            ->groupBy('movies.id', 'movies.title', 'movies.genres')
            ->orderBy('average_rating', 'desc')
            ->paginate(15, ['*'], 'page');;

        return response()->json([
            'movies' => $movies
        ]);
    }
}
