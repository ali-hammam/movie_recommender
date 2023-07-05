<?php

namespace App\Http\Controllers;

use App\Models\MovieUser;
use App\Models\UserCluster;
use Illuminate\Http\Request;

class MovieUserController extends Controller
{
    public function getCurrentListOfUsers()
    {
        $users = MovieUser::with(['occupation' => function ($query) {
            $query->select('occupation_id', 'occupation');
        }])->select('id', 'user_id', 'gender', 'age', 'occupation_id', 'zip')
            ->get();

        unset($users['occupation_id']);

        return response()->json([
            'users' => $users
        ]);
    }

    public function getUsersByClusters(Request $request)
    {
        $usersInCluster = UserCluster::where('cluster_id', $request['cluster_id'])
            ->select('id', 'cluster_id', 'user_id')
            ->with(['movieUser' => function ($query) {
                $query->select(
                    'id',
                    'user_id',
                    'gender',
                    'age',
                    'zip',
                    'occupation_id'
                );
            }, 'movieUser.occupation' => function ($query) {
                $query->select('id', 'occupation', 'occupation_id');
            }])
            ->get();

        return response()->json([
            'users' => $usersInCluster
        ]);
    }
}
