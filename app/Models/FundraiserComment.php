<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FundraiserComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'fundraiser_id',
        'content',
    ];


    public function fundraiser()
    {
        return $this->belongsTo(Fundraiser::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
