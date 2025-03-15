<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dismantled_cars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_brand_id')->constrained('car_brands')->onDelete('cascade');
            $table->string('model');
            $table->string('vin')->unique();
            $table->text('image')->nullable();
            $table->integer('mileage');
            $table->integer('power');
            $table->enum('condition', ['S', 'E', 'D', 'C'])
                ->comment('S - хорошее качество, E - небольшие царапины, D - повреждение кузова, C - полное повреждение');
            $table->json('damaged_parts')->nullable(); // Хранит массив поврежденных деталей
            $table->json('parts_for_sale')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dismantled_cars');
    }
};
