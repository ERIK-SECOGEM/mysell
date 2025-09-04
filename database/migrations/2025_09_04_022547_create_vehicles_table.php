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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('make'); // marca
            $table->string('model');
            $table->unsignedSmallInteger('year');
            $table->unsignedInteger('mileage')->nullable(); // km
            $table->string('vin')->nullable();
            $table->string('location')->nullable();
            $table->string('currency', 3)->default('MXN');
            $table->unsignedInteger('price'); // en centavos si quieres exactitud
            $table->string('slug')->unique();
            $table->string('cover_image_path')->nullable();
            $table->enum('status', ['draft','published','sold'])->default('draft');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
