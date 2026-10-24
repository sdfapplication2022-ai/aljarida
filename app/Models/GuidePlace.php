<?php

namespace App\Models;

use App\Models\Concerns\HasUploads;
use Database\Factories\GuidePlaceFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class GuidePlace extends Model
{
    /** @use HasFactory<GuidePlaceFactory> */
    use HasFactory;

    use HasUploads;

    public const TYPES = [
        'housing',
        'restaurant',
        'hotel',
        'rental',
        'cafe',
        'pharmacy',
        'transport',
        'service',
    ];

    public const TYPE_LABELS = [
        'housing' => 'سكن',
        'restaurant' => 'مطعم',
        'hotel' => 'نزل',
        'rental' => 'كراء',
        'cafe' => 'مقهى',
        'pharmacy' => 'صيدلية',
        'transport' => 'نقل ومواصلات',
        'service' => 'خدمات',
    ];

    protected $fillable = [
        'name',
        'slug',
        'type',
        'city',
        'address',
        'near_campus',
        'description',
        'price_from',
        'price_unit',
        'phone',
        'whatsapp',
        'website',
        'latitude',
        'longitude',
        'amenities',
        'image',
        'is_verified',
        'is_published',
    ];

    protected $attributes = [
        'city' => 'جندوبة',
    ];

    protected function casts(): array
    {
        return [
            'amenities' => 'array',
            'is_verified' => 'boolean',
            'is_published' => 'boolean',
            'price_from' => 'integer',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getImageUrlAttribute(): ?string
    {
        if (! $this->image) {
            return null;
        }

        if (Str::startsWith(Str::lower($this->image), ['http://', 'https://'])) {
            return $this->image;
        }

        if (Str::startsWith($this->image, 'assets/')) {
            return asset($this->image);
        }

        return asset('storage/'.$this->image);
    }

    public function setImageAttribute(mixed $value): void
    {
        $this->uploadFile($value, 'image', 'uploads/guide-places', ['jpg', 'jpeg', 'png', 'webp']);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    protected static function booted(): void
    {
        $clearSitemap = static function (): void {
            Cache::forget('sitemap_xml');
        };

        static::saved($clearSitemap);
        static::deleted($clearSitemap);
    }
}
