<?php

namespace App\Models\Api;

use App\Models\ContactInfoType as ModelsContactInfoType;

class ContactInfoType extends ModelsContactInfoType
{
    protected $table = 'contact_info_types';
    
    protected $hidden = [
        'id',
        'deleted',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function contacts()
    {
        return $this->hasMany(ContactInfo::class);
    }
}
