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
        Schema::dropIfExists('category_follows');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('category_follows')) {
            Schema::create('category_follows', function (Blueprint $table): void {
                $table->id();
                $table->string('reader_key', 64);
                $table->foreignId('category_id')->constrained()->cascadeOnDelete();
                $table->timestamps();
                $table->unique(['reader_key', 'category_id']);
            });
        }
    }
};
