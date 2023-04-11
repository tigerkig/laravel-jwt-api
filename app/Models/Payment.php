<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'currency',
        'firstname',
        'lastname',
        'email',
        'phone',
        'address',
        'country',
        'state',
        'city',
        'paymentMethod',
        'amount',
        'status',
        'isAnonymous',
        'fundraiser_id'
    ];

    public function fundraiser()
    {
        return $this->belongsTo(Fundraiser::class);
    }
}
