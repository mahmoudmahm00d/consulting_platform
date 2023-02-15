<?php

namespace App\Models\Api;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

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

    public function specializes()
    {
        return $this->hasMany(Specialize::class, 'user_id', 'id');
    }

    public function contacts()
    {
        return $this->hasMany(ContactInfo::class, 'user_id', 'id');
    }

    public function schedule()
    {
        return $this->hasMany(Schedule::class, 'user_id', 'id');
    }
}
