<?php

namespace App\Models\Api;

use App\Models\Specialize as ModelsSpecialize;

class Specialize extends ModelsSpecialize
{
    protected $table = 'specializes';

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
        'sequence',
        'user_id',
        'category_id'
    ];
}
