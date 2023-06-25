<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Occupation extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'occupation',
        'occupation_id'
    ];

    public function movieUser()
    {
        return $this->belongsTo(MovieUser::class, 'occupation_id', 'occupation_id');
    }
}
