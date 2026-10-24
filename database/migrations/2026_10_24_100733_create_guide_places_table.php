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
        Schema::create('guide_places', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('type', 30);
            $table->string('city', 100)->default('جندوبة');
            $table->string('address')->nullable();
            $table->string('near_campus')->nullable();
            $table->text('description')->nullable();
            $table->unsignedInteger('price_from')->nullable();
            $table->string('price_unit', 20)->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('whatsapp', 30)->nullable();
            $table->string('website')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->json('amenities')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_published')->default(false);
            $table->timestamps();
            $table->index(['is_published', 'type', 'city']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guide_places');
    }
};
