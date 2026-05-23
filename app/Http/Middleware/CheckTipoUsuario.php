<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTipoUsuario
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, $tipo)
{
    if (!auth()->check()) {
        return redirect('/login');
    }

    if (auth()->user()->tipo !== $tipo) {
        return redirect('/login'); // ou dashboard
    }

    return $next($request);
}
}
