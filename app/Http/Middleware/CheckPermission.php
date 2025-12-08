<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, $permission)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (!auth()->user()->hasPermission($permission)) {
            abort(403, 'You do not have permission to perform this action.');
        }

        return $next($request);
    }
}
