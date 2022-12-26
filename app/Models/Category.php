<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'image',
        'name',
        'sequence'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
        'sequence'
    ];

    public function specializes()
    {
        return $this->hasMany(Specialize::class);
    }
}
