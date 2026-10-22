<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            if (! Schema::hasColumn('blogs', 'excerpt')) {
                $table->text('excerpt')->nullable()->after('title');
            }
            if (! Schema::hasColumn('blogs', 'meta_title')) {
                $table->string('meta_title', 100)->nullable()->after('content');
            }
            if (! Schema::hasColumn('blogs', 'meta_description')) {
                $table->text('meta_description')->nullable()->after('meta_title');
            }
        });
    }

    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            foreach (['excerpt', 'meta_title', 'meta_description'] as $col) {
                if (Schema::hasColumn('blogs', $col)) $table->dropColumn($col);
            }
        });
    }
};
