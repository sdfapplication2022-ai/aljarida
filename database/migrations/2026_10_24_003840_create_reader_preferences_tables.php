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
        Schema::create('reader_bookmarks', function (Blueprint $table) {
            $table->id();
            $table->string('reader_key', 64);
            $table->foreignId('article_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['reader_key', 'article_id']);
        });

        Schema::create('category_follows', function (Blueprint $table) {
            $table->id();
            $table->string('reader_key', 64);
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['reader_key', 'category_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_follows');
        Schema::dropIfExists('reader_bookmarks');
    }
};
