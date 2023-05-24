<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HotelFacility extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'icon'
    ];

    public function hotels()
    {
        return $this->belongsToMany(Hotel::class, 'hotel_facility_pivot', 'hotel_facility_id', 'hotel_id')->withTimestamps();
    }

}
