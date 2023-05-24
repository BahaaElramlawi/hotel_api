<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Hotel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'rate',
        'description',
        'location_id',
    ];

    public function images()
    {
        return $this->hasMany(HotelImage::class);
    }

    public function hosts()
    {
        return $this->belongsTo(Host::class);
    }

    public function locations()
    {
        return $this->belongsTo(HotelLocation::class);
    }

    public function tags()
    {
        return $this->belongsToMany(HotelTag::class, 'hotel_tag_pivot', 'hotel_id', 'hotel_tag_id')->withTimestamps();
    }

    public function facilities()
    {
        return $this->belongsToMany(HotelFacility::class, 'hotel_facility_pivot', 'hotel_id', 'hotel_facility_id')->withTimestamps();
    }

}


