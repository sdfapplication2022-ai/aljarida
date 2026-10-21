<?php

namespace App\Models;

use App\Models\Concerns\HasUploads;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasUploads;

    protected $fillable = [
        'title', 'slug', 'excerpt', 'content',
        'meta_title', 'meta_description',
        'image', 'video_url', 'category_id', 'user_id',
        'is_published', 'is_featured', 'published_at', 'views',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'is_featured'  => 'boolean',
        'published_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $article) {
            if (empty($article->slug) || $article->isDirty('title')) {
                $slug = Str::slug($article->title) ?: 'article';
                $original = $slug;
                $i = 1;
                while (static::where('slug', $slug)->where('id', '!=', $article->id)->exists()) {
                    $slug = $original.'-'.$i++;
                }
                $article->slug = $slug;
            }
            if (empty($article->user_id) && auth()->check()) {
                $article->user_id = auth()->id();
            }
        });

        // Vide les caches (accueil, sitemap, RSS) quand un article change
        $flush = function () {
            Cache::forget('home_data');
            Cache::forget('sitemap_xml');
            Cache::forget('rss_feed');
        };
        static::saved($flush);
        static::deleted($flush);
    }

    /** Articles visibles publiquement. */
    public function scopePublished(Builder $q): Builder
    {
        return $q->where('is_published', true)
                 ->where(fn ($w) => $w->whereNull('published_at')->orWhere('published_at', '<=', now()));
    }

    /** Recherche publique : titre, contenu, tags, catégorie. Titre exact en premier. */
    public function scopeSearch(Builder $query, string $term): Builder
    {
        $like = '%'.addcslashes($term, '\\%_').'%';

        return $query->where(function ($w) use ($like) {
                $w->where('title', 'like', $like)
                  ->orWhere('content', 'like', $like)
                  ->orWhereHas('tags', fn ($t) => $t->where('name', 'like', $like))
                  ->orWhereHas('category', fn ($c) => $c->where('name', 'like', $like));
            })
            ->orderByRaw('CASE WHEN title LIKE ? THEN 0 ELSE 1 END', [$like])
            ->latest('published_at');
    }

    /** Nettoie le HTML à l'enregistrement (composer require mews/purifier). */
    public function setContentAttribute($value): void
    {
        $this->attributes['content'] = clean((string) $value);
    }

    public function setImageAttribute($value): void
    {
        $this->uploadFile($value, 'image', 'uploads/articles', ['jpg','jpeg','png','webp']);
    }

    public function setVideoUrlAttribute($value): void
    {
        $this->uploadFile($value, 'video_url', 'uploads/videos', ['mp4']);
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->fileUrl($this->attributes['image'] ?? null);
    }

    public function getVideoPlayerUrlAttribute(): ?string
    {
        return $this->fileUrl($this->attributes['video_url'] ?? null);
    }

    public function category() { return $this->belongsTo(Category::class); }
    public function user()     { return $this->belongsTo(User::class); }
    public function comments() { return $this->hasMany(Comment::class); }
    public function tags()     { return $this->belongsToMany(Tag::class); }
    public function likes()    { return $this->hasMany(ArticleLike::class); }

public function getEstimatedReadingTimeAttribute(): int
{
    $text  = strip_tags((string) $this->content);
    $words = preg_match_all('/\p{L}+/u', $text, $m) ?: 0;
    return max(1, (int) ceil($words / 200));
}

public function getExcerptAttribute(): string
{
    $manual = trim((string) ($this->attributes['excerpt'] ?? ''));
    if ($manual !== '') {
        return $manual;
    }
    $text = html_entity_decode(strip_tags((string) $this->content), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    return Str::limit(trim(preg_replace('/\s+/u', ' ', $text)), 140);
}

    /** « منذ ساعتين » */
    public function getTimeAgoAttribute(): string
    {
        $date = $this->published_at ?? $this->created_at;

        return $date ? $date->copy()->locale('ar')->diffForHumans() : '';
    }

    /** Données structurées Google (NewsArticle). */
    public function toJsonLd(): array
    {
        return array_filter([
            '@context'         => 'https://schema.org',
            '@type'            => 'NewsArticle',
            'headline'         => Str::limit($this->title, 110, ''),
            'description'      => Str::limit(strip_tags($this->content), 160),
            'mainEntityOfPage' => route('article', $this->slug),
            'image'            => $this->image_url ? [$this->image_url] : null,
            'datePublished'    => optional($this->published_at ?? $this->created_at)->toAtomString(),
            'dateModified'     => optional($this->updated_at)->toAtomString(),
            'inLanguage'       => 'ar',
            'author'           => [['@type' => 'Person', 'name' => $this->user->name ?? 'التحرير']],
            'publisher'        => [
                '@type' => 'Organization',
                'name'  => config('app.name'),
                'logo'  => ['@type' => 'ImageObject', 'url' => asset('assets/img/logo/logo.png')],
            ],
        ]);
    }
}
