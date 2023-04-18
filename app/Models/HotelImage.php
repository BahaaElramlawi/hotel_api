<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelImage extends Model
{
    use HasFactory;

    protected $fillable =
        [
            'image_url',
            'hotel_id'
        ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }
}
