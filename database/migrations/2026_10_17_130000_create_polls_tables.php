<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('polls', function (Blueprint $t) {
            $t->id();
            $t->string('question');
            $t->boolean('is_active')->default(true);
            $t->timestamp('closes_at')->nullable();
            $t->timestamps();
        });

        Schema::create('poll_options', function (Blueprint $t) {
            $t->id();
            $t->foreignId('poll_id')->constrained()->cascadeOnDelete();
            $t->string('label');
            $t->unsignedSmallInteger('position')->default(0);
            $t->unsignedInteger('votes_count')->default(0);
            $t->timestamps();
            $t->unique(['poll_id', 'label']);
        });

        Schema::create('poll_votes', function (Blueprint $t) {
            $t->id();
            $t->foreignId('poll_id')->constrained()->cascadeOnDelete();
            $t->foreignId('poll_option_id')->constrained('poll_options')->cascadeOnDelete();
            $t->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $t->string('visitor_token', 36)->nullable();
            $t->string('ip', 45)->nullable();
            $t->timestamps();
            // un vote par utilisateur connecté, un vote par visiteur (cookie)
            $t->unique(['poll_id', 'user_id']);
            $t->unique(['poll_id', 'visitor_token']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('poll_votes');
        Schema::dropIfExists('poll_options');
        Schema::dropIfExists('polls');
    }
};
