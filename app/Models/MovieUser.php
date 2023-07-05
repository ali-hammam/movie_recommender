<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovieUser extends Model
{
    use HasFactory;
    public function occupation()
    {
        return $this->hasOne(Occupation::class, 'occupation_id', 'occupation_id');
    }

    public function userCluster()
    {
        return $this->haseOne(UserCluster::class, 'user_id', 'user_id');
    }
}
