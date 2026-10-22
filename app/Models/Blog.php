<?php

namespace App\Models;

use App\Models\Concerns\HasUploads;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Blog extends Model
{
    use HasUploads;

    // ✅ 3 nouveaux champs ajoutés
    protected $fillable = [
        'title', 'excerpt', 'content',
        'meta_title', 'meta_description',
        'author', 'published_at', 'image',
    ];

    protected $casts = ['published_at' => 'datetime'];

    public function scopePublished(Builder $q): Builder
    {
        return $q->where(fn ($w) => $w->whereNull('published_at')->orWhere('published_at', '<=', now()));
    }

    /* ============================================================
       UPLOADS
       ============================================================ */
    public function setImageAttribute($value): void
    {
        $this->uploadFile($value, 'image', 'uploads/blogs', ['jpg','jpeg','png','webp']);
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->fileUrl($this->attributes['image'] ?? null);
    }

    /* ============================================================
       EXTRAIT — manuel prioritaire, sinon auto depuis contenu
       ============================================================ */
    public function getExcerptAttribute(): string
    {
        // ✅ Priorité au résumé saisi dans le formulaire
        if (! empty($this->attributes['excerpt'])) {
            return (string) $this->attributes['excerpt'];
        }

        // Sinon : généré depuis le contenu (comme avant, mais borné)
        $text = html_entity_decode(strip_tags((string) $this->content), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        return Str::limit(trim(preg_replace('/\s+/u', ' ', $text)), 140);
    }

    /* ============================================================
       TEMPS DE LECTURE (200 mots / min)
       ============================================================ */
    public function getReadingTimeAttribute(): int
    {
        $words = str_word_count(strip_tags((string) $this->content));
        return max(1, (int) ceil($words / 200));
    }

    /* ============================================================
       CONTENU NETTOYÉ POUR L'AFFICHAGE PUBLIC (existant)
       ============================================================ */
    public function getDisplayContentAttribute(): string
    {
        $html = trim((string) $this->content);
        if ($html === '') {
            return '';
        }

        $emojiOnly = '/^[\s\x{00A0}\x{200D}\x{FE0F}\x{2300}-\x{23FF}\x{2600}-\x{27BF}\x{2B00}-\x{2BFF}\x{1F000}-\x{1FAFF}]+$/u';

        $prev = libxml_use_internal_errors(true);
        $dom  = new \DOMDocument('1.0', 'UTF-8');
        $dom->loadHTML('<?xml encoding="UTF-8"><div id="root">'.$html.'</div>',
            LIBXML_HTML_NODEFDTD | LIBXML_HTML_NOIMPLIED);
        libxml_clear_errors();
        libxml_use_internal_errors($prev);

        $xp   = new \DOMXPath($dom);
        $root = $xp->query('//div[@id="root"]')->item(0);
        if (! $root) {
            return $html;
        }

        $blocks = [];
        foreach ($root->childNodes as $n) {
            if ($n instanceof \DOMElement) {
                $blocks[] = $n;
            }
        }

        foreach ($blocks as $el) {
            if (! in_array($el->nodeName, ['p', 'div', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'], true)) {
                continue;
            }

            $el->removeAttribute('style');
            $el->removeAttribute('align');

            $text     = trim(preg_replace('/\x{00A0}/u', ' ', $el->textContent));
            $hasMedia = $xp->query('.//img|.//video|.//iframe|.//table', $el)->length > 0;

            if ($text === '' && ! $hasMedia) {
                $el->parentNode->removeChild($el);
                continue;
            }

            if (! $hasMedia && preg_match($emojiOnly, $text)) {
                $next = $el->nextSibling;
                while ($next && ! ($next instanceof \DOMElement)) {
                    $next = $next->nextSibling;
                }
                if ($next && in_array($next->nodeName, ['p', 'div', 'h2', 'h3', 'h4'], true)) {
                    $next->insertBefore($dom->createTextNode($text.' '), $next->firstChild);
                    $el->parentNode->removeChild($el);
                }
            }
        }

        $out = '';
        foreach ($root->childNodes as $n) {
            $out .= $dom->saveHTML($n);
        }

        return $out;
    }
}
