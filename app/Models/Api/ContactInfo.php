<?php

namespace App\Models\Api;

use App\Models\ContactInfo as ModelsContactInfo;

class ContactInfo extends ModelsContactInfo
{
    protected $table = 'contact_infos';

    protected $hidden = [
        'type_id',
        'user_id',
        'created_at',
        'updated_at',
    ];

    public function type()
    {
        return $this->belongsTo(ContactInfoType::class);
    }
}
