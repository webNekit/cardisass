<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderPart extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'car_brand_id',
        'dismantled_part_id',
        'quantity',
        'unit_amount',
        'total_amount',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function part()
    {
        return $this->belongsTo(DismantledPart::class);
    }
}
