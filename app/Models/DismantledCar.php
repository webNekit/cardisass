<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DismantledCar extends Model
{
    use HasFactory;

    protected $fillable = [
        'car_brand_id',
        'model',
        'vin',
        'image',
        'mileage',
        'power',
        'condition',
        'damaged_parts',
        'parts_for_sale',
    ];

    protected $casts = [
        'damaged_parts' => 'array', // Автоматическая сериализация поврежденных деталей
        'parts_for_sale' => 'array',
    ];

    /**
     * Связь с брендом автомобиля.
     */
    public function brand()
    {
        return $this->belongsTo(CarBrand::class, 'car_brand_id');
    }

    public function carBrand()
    {
        return $this->belongsTo(CarBrand::class);
    }
    /**
     * Связь с запчастями, которые извлекли из этого авто.
     */
    public function dismantledParts()
    {
        return $this->hasMany(DismantledPart::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::saved(function ($car) {
            if (!empty($car->parts_for_sale)) {
                // Удаляем старые записи, чтобы не дублировать запчасти
                $car->dismantledParts()->delete();

                foreach ($car->parts_for_sale as $partName) {
                    $car->dismantledParts()->create([
                        'name' => $partName,
                        'quality' => $car->condition, // Качество по умолчанию = состоянию машины
                    ]);
                }
            }
        });
    }
}
