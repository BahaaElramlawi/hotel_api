<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HotelTag extends Model
{
    use HasFactory;

    protected $fillable =
        [
            'name'
        ];

    public function hotels()
    {
        return $this->belongsToMany(Hotel::class, 'hotel_tag_pivot',
            'hotel_tag_id', 'hotel_id')->withTimestamps();
    }

}
