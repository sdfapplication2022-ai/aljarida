<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            if (! Schema::hasColumn('articles', 'excerpt')) {
                $table->text('excerpt')->nullable()->after('title');
            }
            if (! Schema::hasColumn('articles', 'meta_title')) {
                $table->string('meta_title', 100)->nullable()->after('content');
            }
            if (! Schema::hasColumn('articles', 'meta_description')) {
                $table->text('meta_description')->nullable()->after('meta_title');
            }
            if (! Schema::hasColumn('articles', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('is_published');
            }
        });
    }

    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            foreach (['excerpt', 'meta_title', 'meta_description', 'is_featured'] as $col) {
                if (Schema::hasColumn('articles', $col)) $table->dropColumn($col);
            }
        });
    }
};
