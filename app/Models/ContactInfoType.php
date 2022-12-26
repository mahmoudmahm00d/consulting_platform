<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactInfoType extends Model
{
    protected $table = 'contact_info_types';
    
    protected $fillable = [
        'name',
        'url',
        'description',
        'deleted'
    ];

    public function contacts()
    {
        return $this->hasMany(ContactInfo::class);
    }
}
