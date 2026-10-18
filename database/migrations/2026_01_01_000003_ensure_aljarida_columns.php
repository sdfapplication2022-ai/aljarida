<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Filet de sécurité : ajoute les colonnes attendues par les modèles
 * si les tables ont été créées par une autre migration sans elles.
 */
return new class extends Migration {
    /** table => [colonne => callable(Blueprint): void] */
    private function columns(): array
    {
        return [
            'comments' => [
                'content'     => fn (Blueprint $t) => $t->text('content')->nullable(),
                'article_id'  => fn (Blueprint $t) => $t->unsignedBigInteger('article_id')->nullable(),
                'user_id'     => fn (Blueprint $t) => $t->unsignedBigInteger('user_id')->nullable(),
                'is_approved' => fn (Blueprint $t) => $t->boolean('is_approved')->default(false),
            ],
            'articles' => [
                'slug'         => fn (Blueprint $t) => $t->string('slug')->nullable()->index(),
                'image'        => fn (Blueprint $t) => $t->string('image')->nullable(),
                'video_url'    => fn (Blueprint $t) => $t->string('video_url')->nullable(),
                'category_id'  => fn (Blueprint $t) => $t->unsignedBigInteger('category_id')->nullable(),
                'user_id'      => fn (Blueprint $t) => $t->unsignedBigInteger('user_id')->nullable(),
                'is_published' => fn (Blueprint $t) => $t->boolean('is_published')->default(false),
                'published_at' => fn (Blueprint $t) => $t->timestamp('published_at')->nullable(),
                'views'        => fn (Blueprint $t) => $t->unsignedBigInteger('views')->default(0),
            ],
            'categories' => [
                'slug'        => fn (Blueprint $t) => $t->string('slug')->nullable(),
                'description' => fn (Blueprint $t) => $t->text('description')->nullable(),
            ],
            'tags' => [
                'slug' => fn (Blueprint $t) => $t->string('slug')->nullable(),
            ],
            'blogs' => [
                'content'      => fn (Blueprint $t) => $t->longText('content')->nullable(),
                'author'       => fn (Blueprint $t) => $t->string('author')->nullable(),
                'published_at' => fn (Blueprint $t) => $t->timestamp('published_at')->nullable(),
                'image'        => fn (Blueprint $t) => $t->string('image')->nullable(),
            ],
            'services' => [
                'icon'        => fn (Blueprint $t) => $t->string('icon')->nullable(),
                'description' => fn (Blueprint $t) => $t->text('description')->nullable(),
                'photo'       => fn (Blueprint $t) => $t->string('photo')->nullable(),
            ],
            'testimonials' => [
                'rating'      => fn (Blueprint $t) => $t->unsignedTinyInteger('rating')->default(5),
                'author_role' => fn (Blueprint $t) => $t->string('author_role')->nullable(),
            ],
            'team_members' => [
                'position'  => fn (Blueprint $t) => $t->string('position')->nullable(),
                'bio'       => fn (Blueprint $t) => $t->text('bio')->nullable(),
                'photo'     => fn (Blueprint $t) => $t->string('photo')->nullable(),
                'facebook'  => fn (Blueprint $t) => $t->string('facebook')->nullable(),
                'linkedin'  => fn (Blueprint $t) => $t->string('linkedin')->nullable(),
                'twitter'   => fn (Blueprint $t) => $t->string('twitter')->nullable(),
                'instagram' => fn (Blueprint $t) => $t->string('instagram')->nullable(),
            ],
            'abouts' => [
                'subtitle'        => fn (Blueprint $t) => $t->string('subtitle')->nullable(),
                'description'     => fn (Blueprint $t) => $t->text('description')->nullable(),
                'main_image'      => fn (Blueprint $t) => $t->string('main_image')->nullable(),
                'secondary_image' => fn (Blueprint $t) => $t->string('secondary_image')->nullable(),
                'video_url'       => fn (Blueprint $t) => $t->string('video_url')->nullable(),
                'team_links'      => fn (Blueprint $t) => $t->json('team_links')->nullable(),
            ],
            'subscribers' => [
                'is_active' => fn (Blueprint $t) => $t->boolean('is_active')->default(true),
            ],
            'contact_messages' => [
                'phone'        => fn (Blueprint $t) => $t->string('phone')->nullable(),
                'service_type' => fn (Blueprint $t) => $t->string('service_type')->nullable(),
            ],
        ];
    }

    public function up(): void
    {
        foreach ($this->columns() as $table => $cols) {
            if (! Schema::hasTable($table)) {
                continue;
            }
            foreach ($cols as $name => $add) {
                if (! Schema::hasColumn($table, $name)) {
                    Schema::table($table, fn (Blueprint $t) => $add($t));
                }
            }
        }

        // user_id des articles : doit pouvoir rester vide (nullOnDelete)
        if (Schema::hasTable('articles') && Schema::hasColumn('articles', 'user_id')) {
            try {
                \Illuminate\Support\Facades\DB::statement('ALTER TABLE articles MODIFY user_id BIGINT UNSIGNED NULL');
            } catch (\Throwable $e) {
                // clé étrangère / autre moteur : on ignore
            }
        }
    }

    public function down(): void
    {
        // volontairement vide
    }
};
