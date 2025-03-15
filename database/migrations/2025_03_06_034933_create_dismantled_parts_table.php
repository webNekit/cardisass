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
        Schema::create('dismantled_parts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dismantled_car_id')->constrained('dismantled_cars')->onDelete('cascade');
            $table->string('name'); // Название запчасти
            $table->text('image')->nullable(); // Фото запчасти
            $table->decimal('price', 10, 2)->nullable(); // Цена
            $table->enum('quality', ['S', 'E', 'D', 'C'])->comment('S - новое, E - хорошее состояние, D - поврежденное, C - на запчасти'); // Состояние детали
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dismantled_parts');
    }
};
