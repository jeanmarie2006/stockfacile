<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/** Limite une route à certains rôles : ->middleware('role:gerant'). */
class Role
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        abort_unless($request->user() && in_array($request->user()->role, $roles, true), 403, 'Cette action est réservée au gérant.');

        return $next($request);
    }
}
