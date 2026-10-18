<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('categories')) Schema::create('categories', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('slug')->unique();
            $t->text('description')->nullable();
            $t->timestamps();
        });

        if (! Schema::hasTable('tags')) Schema::create('tags', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('slug')->unique();
            $t->timestamps();
        });

        if (! Schema::hasTable('articles')) Schema::create('articles', function (Blueprint $t) {
            $t->id();
            $t->string('title');
            $t->string('slug')->unique();
            $t->longText('content');
            $t->string('image')->nullable();
            $t->string('video_url')->nullable();
            $t->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $t->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $t->boolean('is_published')->default(false);
            $t->timestamp('published_at')->nullable();
            $t->unsignedBigInteger('views')->default(0);
            $t->timestamps();
        });

        if (! Schema::hasTable('article_tag')) Schema::create('article_tag', function (Blueprint $t) {
            $t->foreignId('article_id')->constrained()->cascadeOnDelete();
            $t->foreignId('tag_id')->constrained()->cascadeOnDelete();
            $t->primary(['article_id', 'tag_id']);
        });

        if (! Schema::hasTable('article_likes')) Schema::create('article_likes', function (Blueprint $t) {
            $t->id();
            $t->foreignId('article_id')->constrained()->cascadeOnDelete();
            $t->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $t->string('ip', 45)->nullable();
            $t->timestamps();
        });

        if (! Schema::hasTable('comments')) Schema::create('comments', function (Blueprint $t) {
            $t->id();
            $t->text('content');
            $t->foreignId('article_id')->constrained()->cascadeOnDelete();
            $t->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $t->boolean('is_approved')->default(false);
            $t->timestamps();
        });

        if (! Schema::hasTable('blogs')) Schema::create('blogs', function (Blueprint $t) {
            $t->id();
            $t->string('title');
            $t->longText('content')->nullable();
            $t->string('author')->nullable();
            $t->timestamp('published_at')->nullable();
            $t->string('image')->nullable();
            $t->timestamps();
        });

        if (! Schema::hasTable('services')) Schema::create('services', function (Blueprint $t) {
            $t->id();
            $t->string('title');
            $t->string('icon')->nullable();
            $t->text('description')->nullable();
            $t->string('photo')->nullable();
            $t->timestamps();
        });

        if (! Schema::hasTable('testimonials')) Schema::create('testimonials', function (Blueprint $t) {
            $t->id();
            $t->string('author_name');
            $t->text('content');
            $t->unsignedTinyInteger('rating')->default(5);
            $t->string('author_role')->nullable();
            $t->timestamps();
        });

        if (! Schema::hasTable('team_members')) Schema::create('team_members', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('position')->nullable();
            $t->text('bio')->nullable();
            $t->string('photo')->nullable();
            $t->string('facebook')->nullable();
            $t->string('linkedin')->nullable();
            $t->string('twitter')->nullable();
            $t->string('instagram')->nullable();
            $t->timestamps();
        });

        if (! Schema::hasTable('abouts')) Schema::create('abouts', function (Blueprint $t) {
            $t->id();
            $t->string('title');
            $t->string('subtitle')->nullable();
            $t->text('description')->nullable();
            $t->string('main_image')->nullable();
            $t->string('secondary_image')->nullable();
            $t->string('video_url')->nullable();
            $t->json('team_links')->nullable();
            $t->timestamps();
        });

        if (! Schema::hasTable('subscribers')) Schema::create('subscribers', function (Blueprint $t) {
            $t->id();
            $t->string('email')->unique();
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });

        if (! Schema::hasTable('contact_messages')) Schema::create('contact_messages', function (Blueprint $t) {
            $t->id();
            $t->string('first_name');
            $t->string('last_name');
            $t->string('email');
            $t->string('phone')->nullable();
            $t->string('service_type')->nullable();
            $t->text('message');
            $t->timestamps();
        });
    }

    public function down(): void
    {
        foreach (['contact_messages','subscribers','abouts','team_members','testimonials','services','blogs','comments','article_likes','article_tag','articles','tags','categories'] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
