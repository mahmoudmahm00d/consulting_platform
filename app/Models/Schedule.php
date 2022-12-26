<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'user_id',
        'day',
        'from',
        'to',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
