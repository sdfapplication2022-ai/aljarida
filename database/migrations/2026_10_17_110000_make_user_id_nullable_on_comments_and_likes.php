<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Un visiteur sans compte n'a pas de user_id
        Schema::table('comments', function (Blueprint $t) {
            $t->unsignedBigInteger('user_id')->nullable()->change();
        });

        Schema::table('article_likes', function (Blueprint $t) {
            $t->unsignedBigInteger('user_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        // Volontairement vide : remettre NOT NULL échouerait dès qu'un commentaire/like invité existe.
    }
};
