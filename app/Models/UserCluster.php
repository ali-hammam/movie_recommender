<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCluster extends Model
{
    use HasFactory;

    public function movieUser()
    {
        return $this->belongsTo(MovieUser::class, 'user_id', 'user_id');
    }
}
