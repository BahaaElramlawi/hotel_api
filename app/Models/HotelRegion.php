<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelRegion extends Model
{
    use HasFactory;

    protected $fillable = [
        'region_name',
    ];

    public function location()
    {
        return $this->hasMany(HotelLocation::class);
    }
}
