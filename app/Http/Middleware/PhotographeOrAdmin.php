<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class PhotographeOrAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('mainviews.forbidden');
        }

        $user = Auth::user();

        if ($user->role === 'photographe' || $user->role === 'admin') {
            return $next($request);
        }

        return redirect()->route('mainviews.forbidden')->withErrors(['error' => 'Vous n\'avez pas l\'autorisation d\'accéder à cette page.']);
    }
}
