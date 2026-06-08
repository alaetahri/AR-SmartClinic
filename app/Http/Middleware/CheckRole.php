<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Vérifier si l'utilisateur est connecté
        if (!session('user_id')) {
            return redirect()->route('login');
        }

        // Vérifier si le rôle est autorisé
        if (!in_array(session('user_role'), $roles)) {
            abort(403, 'Accès non autorisé');
        }

        return $next($request);
    }
}