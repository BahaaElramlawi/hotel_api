<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HotelLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'location_name',
        'longitude',
        'latitude',
        'hotel_id',
        'region_id',
    ];

    public function region()
    {
        return $this->belongsTo(HotelRegion::class);
    }

    public function hotel()
    {
        return $this->hasMany(Hotel::class);
    }

    public function toArray()
    {
        $array = parent::toArray();
        $array['region'] = $this->region;
        return $array;
    }

    public function location()
    {
        return $this->belongsTo(HotelLocation::class);
    }
}
