<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Continue only when the authenticated user has the role required by the route.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Middleware auth harus dijalankan lebih dulu; role di database menjadi sumber otoritas.
        abort_unless($request->user()?->role === $role, 403);

        return $next($request);
    }
}
