<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('comments', function (Blueprint $t) {
            $t->string('guest_name', 100)->nullable()->after('user_id');
            $t->string('guest_email')->nullable()->after('guest_name');
            $t->string('visitor_token', 36)->nullable()->index()->after('guest_email');
        });

        Schema::table('article_likes', function (Blueprint $t) {
            $t->string('visitor_token', 36)->nullable()->after('user_id');
            // un like par utilisateur connecté, un like par visiteur (cookie) et par article
            $t->unique(['article_id', 'user_id']);
            $t->unique(['article_id', 'visitor_token']);
        });
    }

    public function down(): void
    {
        Schema::table('article_likes', function (Blueprint $t) {
            $t->dropUnique(['article_id', 'user_id']);
            $t->dropUnique(['article_id', 'visitor_token']);
            $t->dropColumn('visitor_token');
        });

        Schema::table('comments', function (Blueprint $t) {
            $t->dropColumn(['guest_name', 'guest_email', 'visitor_token']);
        });
    }
};
