<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Specialize extends Model
{
    protected $fillable = [
        'name',
        'description',
        'certificate',
        'user_id',
        'category_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
        'sequence'
    ];

    public function toApi()
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'certificate' => $this->certificate,
            'category_id' => $this->category_id,
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
