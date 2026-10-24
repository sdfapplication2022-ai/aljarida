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
        Schema::create('local_events', function (Blueprint $table): void {
            $table->id();
            $table->string('title');
            $table->string('summary', 500)->nullable();
            $table->longText('description')->nullable();
            $table->string('city', 100)->default('جندوبة');
            $table->string('venue')->nullable();
            $table->string('organizer')->nullable();
            $table->string('url')->nullable();
            $table->dateTime('starts_at');
            $table->dateTime('ends_at')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamps();
            $table->index(['is_published', 'starts_at']);
        });

        Schema::create('sports_results', function (Blueprint $table): void {
            $table->id();
            $table->string('competition');
            $table->string('home_team');
            $table->string('away_team');
            $table->unsignedTinyInteger('home_score');
            $table->unsignedTinyInteger('away_score');
            $table->string('city', 100)->nullable();
            $table->string('venue')->nullable();
            $table->dateTime('played_at');
            $table->boolean('is_published')->default(false);
            $table->timestamps();
            $table->index(['is_published', 'played_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sports_results');
        Schema::dropIfExists('local_events');
    }
};
