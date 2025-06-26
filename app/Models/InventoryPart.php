<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryPart extends Model
{
    use HasFactory;

    protected $fillable = ['car_brand_id', 'model', 'name', 'quantity'];

    public function carBrand()
    {
        return $this->belongsTo(CarBrand::class);
    }
}
