<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supporter extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'avatar',
        'amount_donated',
        'fundraiser_id'
    ];

    public function fundraiser()
    {
        return $this->belongsTo(Fundraiser::class);
    }
}
