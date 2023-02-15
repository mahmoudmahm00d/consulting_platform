<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'from_wallet',
        'to_wallet',
        'type',
        'appointment_id',
        'amount',
    ];

    public function fromWallet()
    {
        $this->belongsTo(Wallet::class, 'from_wallet');
    }

    public function toWallet()
    {
        $this->belongsTo(Wallet::class, 'to_wallet');
    }

    public function appointment()
    {
        $this->belongsTo(Appointment::class, 'to_wallet');
    }
}
