<?php

namespace App\Models;

use App\Models\Concerns\HasUploads;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasUploads;

    protected $fillable = ['title','content','author','published_at','image'];
    protected $casts = ['published_at' => 'datetime'];
    public function scopePublished(Builder $q): \Illuminate\Database\Eloquent\Builder
    {
        return $q->where(fn ($w) => $w->whereNull('published_at')->orWhere('published_at', '<=', now()));
    }
    /** Contenu nettoyé pour l'affichage : emojis recollés, paragraphes vides supprimés. */
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

    // Parcourir les blocs de premier niveau
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

        // retirer les styles inline parasites (centrage, tailles, marges…)
        $el->removeAttribute('style');
        $el->removeAttribute('align');

        $text     = trim(preg_replace('/\x{00A0}/u', ' ', $el->textContent));
        $hasMedia = $xp->query('.//img|.//video|.//iframe|.//table', $el)->length > 0;

        // paragraphe vide
        if ($text === '' && ! $hasMedia) {
            $el->parentNode->removeChild($el);
            continue;
        }

        // paragraphe "emoji seul" : on le colle au bloc suivant
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
    public function setImageAttribute($value): void
    {
        $this->uploadFile($value, 'image', 'uploads/blogs', ['jpg','jpeg','png','webp']);
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->fileUrl($this->attributes['image'] ?? null);
    }
}
