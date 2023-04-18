<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fundraiser extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'isFeatured',
        'target',
        'target_currency',
        'amount_raised',
        'start_date',
        'end_date',
        'status',
        'organization_id'
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
