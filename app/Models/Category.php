<?php

namespace App\Models;

use App\Models\Api\SpecialistUser;
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

    public function specialists()
    {
        return $this->belongsToMany(SpecialistUser::class, 'specializes');
    }
}
