<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\MovieCluster;
use App\Models\MovieUser;
use App\Models\User;
use App\Models\UserCluster;
use App\Models\UserRating;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class RatingsController extends Controller
{
    public function getAllMoviesWithRatings(Request $request)
    {
        $clusted_id = UserCluster::where(['user_id' => $request['user_id']])->get('cluster_id')[0]['cluster_id'];
        if (!$clusted_id) {
            return response()->json([
                'msg' => 'invalid user id'
            ], 400);
        }

        $moviesWithRealUserRating = UserRating::where('user_id', $request['user_id'])->get();
        $movies = MovieCluster::where('cluster_id', $clusted_id)
            ->leftJoin('movie_images', 'movie_clusters.movie_id', '=', 'movie_images.movie_id')
            ->get();

        $joinedRatings = $movies->map(function ($movie) use ($moviesWithRealUserRating) {
            $isMovieMatched = $moviesWithRealUserRating->firstWhere('movie_id', $movie['movie_id']);
            if ($isMovieMatched) {
                $movie['real_rating'] = $isMovieMatched['rating'];
            } else {
                $movie['real_rating'] = -1;
            }

            $movie['predicted_rating'] = number_format($movie['predicted_rating'], 2);

            return $movie;
        });

        $sortedCollection = collect($joinedRatings)->sortByDesc('real_rating');
        $perPage = request()->get('movies_per_page', 10);
        $currentPage = request()->get('page_number', 1);

        $paginatedCollection = new LengthAwarePaginator(
            $sortedCollection->slice(($currentPage - 1) * $perPage, $perPage),
            $sortedCollection->count(),
            $perPage,
            $currentPage
        );

        return response()->json([
            $paginatedCollection
        ]);
    }

    public function Top10UserRatings(Request $request)
    {
        $user_id = $request['user_id'];
        $userRatings = UserRating::where('user_id', $user_id)
            ->with(['movie' => function ($query) {
                $query->select('id', 'title', 'genres');
            }])
            ->select('id', 'user_id', 'movie_id', 'rating')
            ->orderBy('rating', 'desc')
            ->take(10)
            ->get();

        return response()->json($userRatings);
    }

    public function addRating(Request $request)
    {
        if ($request['rating'] > 5) {
            return response()->json([
                'msg' => 'this rating is not valid'
            ], 422);
        }

        $count = UserRating::where('user_id', $request['user_id'])
            ->where('movie_id', $request['movie_id'])
            ->count();

        if ($count == 0) {
            $rating = UserRating::create([
                'user_id' => $request['user_id'],
                'movie_id' => $request['movie_id'],
                'rating' => $request['rating']
            ]);

            return response()->json([
                'rating' => $rating,
                'msg' => 'success'
            ], 200);
        }

        return response()->json([
            'msg' => 'the user already gave rating'
        ], 422);
    }

    public function updateRating(Request $request)
    {
        $rating = UserRating::where('user_id', $request['user_id'])
            ->where('movie_id', $request['movie_id']);

        if ($rating->count() == 0) {
            return response()->json([
                'msg' => 'this rating is not valid'
            ], 404);
        }

        $rating->update(['rating' => $request['rating']]);

        return response()->json([
            "rating" => $rating->get(),
            "msg" => "rating deleted successfully"
        ], 202);
    }

    public function removeRating(Request $request)
    {
        $removedRating = UserRating::where('user_id', $request['user_id'])
            ->where('movie_id', $request['movie_id'])
            ->delete();

        return response()->json([
            "rating" => $removedRating,
            "msg" => "rating deleted successfully"
        ], 202);
    }
}
