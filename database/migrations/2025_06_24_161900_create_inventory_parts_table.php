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
        Schema::create('inventory_parts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_brand_id')->constrained('car_brands')->onDelete('cascade');
            $table->string('model'); // Например: Camry
            $table->string('name');  // Например: Бампер
            $table->integer('quantity')->default(0);
            $table->unique(['car_brand_id', 'model', 'name']); // Чтобы каждая запчасть для модели была уникальна
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_parts');
    }
};
