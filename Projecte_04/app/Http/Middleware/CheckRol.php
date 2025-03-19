<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRol
{
    public function handle(Request $request, Closure $next, $rol)
    {
        if (!$request->user() || $request->user()->rol !== $rol) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'No autorizado.'], 403);
            }
            return redirect('/');
        }

        return $next($request);
    }
}
