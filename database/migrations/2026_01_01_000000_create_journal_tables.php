<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', fn (Blueprint $t) => $t->string('role')->default('user')->after('email')); // admin|editor|user

        Schema::create('categories', function (Blueprint $t) {
            $t->id(); $t->string('name'); $t->string('slug')->unique(); $t->text('description')->nullable(); $t->timestamps();
        });
        Schema::create('articles', function (Blueprint $t) {
            $t->id();
            $t->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->string('title'); $t->string('slug')->unique(); $t->text('excerpt')->nullable(); $t->longText('content');
            $t->string('image')->nullable(); $t->boolean('is_published')->default(false);
            $t->timestamp('published_at')->nullable(); $t->unsignedBigInteger('views')->default(0);
            $t->timestamps(); $t->softDeletes();
            $t->index(['is_published', 'published_at']);
        });
        Schema::create('tags', function (Blueprint $t) {
            $t->id(); $t->string('name'); $t->string('slug')->unique(); $t->timestamps();
        });
        Schema::create('article_tag', function (Blueprint $t) {
            $t->foreignId('article_id')->constrained()->cascadeOnDelete();
            $t->foreignId('tag_id')->constrained()->cascadeOnDelete();
            $t->primary(['article_id', 'tag_id']);
        });
        Schema::create('comments', function (Blueprint $t) {
            $t->id(); $t->foreignId('article_id')->constrained()->cascadeOnDelete();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->text('content'); $t->boolean('approved')->default(true); $t->timestamps();
        });
        Schema::create('contact_messages', function (Blueprint $t) {
            $t->id(); $t->string('name'); $t->string('email'); $t->string('subject')->nullable();
            $t->text('message'); $t->timestamp('read_at')->nullable(); $t->timestamps();
        });
    }
    public function down(): void
    {
        foreach (['contact_messages','comments','article_tag','tags','articles','categories'] as $t) Schema::dropIfExists($t);
        Schema::table('users', fn (Blueprint $t) => $t->dropColumn('role'));
    }
};
