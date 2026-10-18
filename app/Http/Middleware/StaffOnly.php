<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/** Autorise uniquement les rôles admin / editor dans le panneau d'administration. */
class StaffOnly
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if (! $user || ! $user->isStaff()) {
            abort(403, 'غير مصرح لك بالدخول');
        }
        return $next($request);
    }
}
