<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'specialist_id',
        'specialize_id',
        'schedule_id',
        'date',
        'start_at',
        'finish_at',
        'status',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function specialist()
    {
        return $this->belongsTo(User::class, 'specialist_id');
    }

    public function specialize()
    {
        return $this->belongsTo(Specialize::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }
}
