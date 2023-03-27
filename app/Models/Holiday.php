<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'startingDate',
        'endingDate',
        'status',
    ];

    public function userHoliday()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
}
