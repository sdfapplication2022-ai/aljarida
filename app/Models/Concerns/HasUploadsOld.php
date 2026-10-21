<?php

namespace App\Models\Concerns;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Gère l'upload de fichiers depuis les mutators (setXAttribute).
 * Les fichiers sont stockés sur le disque "public" (php artisan storage:link).
 */
trait HasUploads
{
    protected function uploadFile($value, string $attribute, string $dir, array $allowed = []): void
    {
        if ($value instanceof UploadedFile) {
            $ext = strtolower($value->getClientOriginalExtension());
            if ($allowed && ! in_array($ext, $allowed, true)) {
                return;
            }
            // supprime l'ancien fichier
            $old = $this->attributes[$attribute] ?? null;
            if ($old && Storage::disk('public')->exists($old)) {
                Storage::disk('public')->delete($old);
            }
            $this->attributes[$attribute] = $value->store($dir, 'public');
        } elseif (is_string($value) && $value !== '') {
            $this->attributes[$attribute] = $value; // chemin déjà stocké
        } elseif ($value === null) {
            // on conserve la valeur existante
        }
    }

    protected function fileUrl(?string $path, ?string $default = null): ?string
    {
        if (! $path) {
            return $default;
        }
        if (str_starts_with($path, 'http') || str_starts_with($path, 'assets/')) {
            return str_starts_with($path, 'http') ? $path : asset($path);
        }
        return asset('storage/'.$path);
    }
}
