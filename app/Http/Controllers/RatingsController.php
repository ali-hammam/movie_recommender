<?php

namespace App\Http\Controllers;

use App\Models\MovieUser;
use App\Models\User;
use App\Models\UserRating;
use Illuminate\Http\Request;

class RatingsController extends Controller
{
    public function dummy()
    {
        return response()->json([
            'name' => 'hamma'
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
