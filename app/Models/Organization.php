<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'about',
        'mission',
        'plans',
        'history',
        'founder_details',
        'goals',
        'information',
        'location'
    ];

    public function organizationFiles()
    {
        return $this->hasMany(OrganizationFiles::class);
    }
}
