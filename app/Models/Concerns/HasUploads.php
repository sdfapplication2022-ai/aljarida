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
                return; // Extension non autorisée
            }

            // 1. Supprimer l'ancienne image de Cloudinary si elle existe
            $oldPath = $this->attributes[$attribute] ?? null;
            if ($oldPath && str_contains($oldPath, 'res.cloudinary.com')) {
                $this->deleteCloudinaryFile($oldPath);
            }

            // 2. Envoyer la nouvelle image sur Cloudinary
            try {
                $result = (new UploadApi())->upload($value->getRealPath(), [
                    'folder' => $dir, // ex: uploads/articles
                ]);

                // 3. Sauvegarder l'URL complète HTTPS en BDD
                $this->attributes[$attribute] = $result['secure_url'];

            } catch (\Exception $e) {
                Log::error('Erreur Upload Cloudinary: ' . $e->getMessage());
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

        // Si c'est déjà une URL complète (ex: Cloudinary)
        if (str_starts_with($path, 'http')) {
            return $path;
        }

        // Pour les anciennes images locales (le temps de les re-uploader)
        if (str_starts_with($path, 'assets/')) {
            return asset($path);
        }

        return asset('storage/'.$path);
    }

    /**
     * Supprime une image de Cloudinary via son URL
     */
    protected function deleteCloudinaryFile(string $url): void
    {
        try {
            // Extrait l'ID public de l'URL (ex: 'uploads/articles/abc123')
            $parts = explode('/upload/', $url);
            if (count($parts) > 1) {
                $publicIdWithExt = $parts[1];
                // Enlève le préfixe de version (v123456/) s'il est présent
                if (preg_match('/^v\d+\/(.+)$/', $publicIdWithExt, $matches)) {
                    $publicId = $matches[1];
                } else {
                    $publicId = $publicIdWithExt;
                }
                // Enlève l'extension (.jpg, .png, etc.)
                $publicId = preg_replace('/\.[^.]+$/', '', $publicId);

                (new UploadApi())->destroy($publicId);
            }
        } catch (\Exception $e) {
            // On ignore l'erreur si la suppression échoue (fichier déjà supprimé)
        }
    }
}
