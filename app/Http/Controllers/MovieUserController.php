<?php

namespace App\Http\Controllers;

use App\Models\MovieUser;
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
}
