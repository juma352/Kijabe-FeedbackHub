<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, $role)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $roles = is_array($role) ? $role : explode('|', $role);

        if (!in_array(auth()->user()->role, $roles)) {
            abort(403, 'You do not have access to this resource.');
        }

        return $next($request);
    }
}
