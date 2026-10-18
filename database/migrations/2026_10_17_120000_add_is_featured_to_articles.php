<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $t) {
            $t->boolean('is_featured')->default(false)->index()->after('is_published');
        });
    }

    public function down(): void
    {
        Schema::table('articles', function (Blueprint $t) {
            $t->dropColumn('is_featured');
        });
    }
};
