<?php

namespace App\Models\Api;

use App\Models\User;

class SpecialistUser extends User
{
    protected $table = 'users';

    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
        'deleted_at',
        'sequence',
        'email_verified_at'
    ];

    
}
