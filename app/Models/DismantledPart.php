<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DismantledPart extends Model
{
    use HasFactory;

    protected $fillable = [
        'dismantled_car_id',
        'name',
        'image',
        'price',
        'quality',
    ];

    /**
     * Связь с разобранным автомобилем, откуда запчасть.
     */
    public function dismantledCar()
    {
        return $this->belongsTo(DismantledCar::class);
    }
}
