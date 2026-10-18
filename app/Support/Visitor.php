<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

/**
 * Identité anonyme d'un visiteur : un UUID stocké dans un cookie (chiffré par Laravel).
 * Il relie un commentaire ou un like à un navigateur, sans compte.
 */
class Visitor
{
    private const COOKIE = 'visitor_id';
    private const MINUTES = 60 * 24 * 365; // 1 an

    /** Token existant (lecture seule, ne crée pas de cookie). */
    public static function existing(Request $request): ?string
    {
        $t = $request->cookie(self::COOKIE);

        return is_string($t) && Str::isUuid($t) ? $t : null;
    }

    /** Token existant ou nouveau, et (re)pose le cookie. À utiliser lors d'une écriture. */
    public static function token(Request $request): string
    {
        $t = self::existing($request) ?? (string) Str::uuid();

        Cookie::queue(cookie(self::COOKIE, $t, self::MINUTES, '/', null, null, true, false, 'lax'));

        return $t;
    }
}
