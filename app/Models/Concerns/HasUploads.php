<?php

namespace App\Models\Concerns;

use Cloudinary\Api\Upload\UploadApi;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

trait HasUploads
{
    protected function uploadFile($value, string $attribute, string $dir, array $allowed = []): void
    {
        if ($value instanceof UploadedFile) {
            $ext = strtolower($value->getClientOriginalExtension());
            if ($allowed && ! in_array($ext, $allowed, true)) {
                return;
            }

            try {
                // 1. Envoyer le nouveau fichier (images + mp4)
                $result = (new UploadApi())->upload($value->getRealPath(), [
                    'folder'        => $dir,
                    'resource_type' => 'auto',
                ]);

                // 2. Supprimer l'ancien seulement si l'upload a réussi
                $old = $this->attributes[$attribute] ?? null;
                if ($old && str_contains($old, 'res.cloudinary.com')) {
                    $this->deleteCloudinaryFile($old);
                }

                // 3. Sauvegarder l'URL HTTPS
                $this->attributes[$attribute] = $result['secure_url'];
            } catch (\Throwable $e) {
                Log::error('Erreur upload Cloudinary: '.$e->getMessage());
            }
        } elseif (is_string($value) && $value !== '') {
            $this->attributes[$attribute] = $value;
        }
    }

    protected function fileUrl(?string $path, ?string $default = null): ?string
    {
        if (! $path) {
            return $default;
        }
        if (str_starts_with($path, 'http')) {
            // images Cloudinary : format et qualité automatiques
            if (str_contains($path, '/image/upload/') && ! str_contains($path, '/image/upload/f_auto')) {
                return str_replace('/image/upload/', '/image/upload/f_auto,q_auto/', $path);
            }
            return $path;
        }
        if (str_starts_with($path, 'assets/')) {
            return asset($path);
        }
        return asset('storage/'.$path);
    }
    protected function deleteCloudinaryFile(string $url): void
    {
        try {
            // https://res.cloudinary.com/<cloud>/<type>/upload/[transfo/]v123/folder/name.ext
            if (! preg_match('#/(image|video|raw)/upload/(?:.*?/)?(?:v\d+/)?(.+)$#', $url, $m)) {
                return;
            }

            $resourceType = $m[1];
            $publicId = urldecode($m[2]);

            // Pour image/video l'extension ne fait pas partie du public_id (pour raw, si)
            if ($resourceType !== 'raw') {
                $publicId = preg_replace('/\.[^.\/]+$/', '', $publicId);
            }

            (new UploadApi())->destroy($publicId, [
                'resource_type' => $resourceType,
                'invalidate'    => true,
            ]);
        } catch (\Throwable $e) {
            Log::warning('Suppression Cloudinary échouée: '.$e->getMessage());
        }
    }
}
