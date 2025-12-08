<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class OptimizePerformance
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Add cache headers for static assets
        if ($this->isStaticAsset($request->path())) {
            $response->header('Cache-Control', 'public, max-age=31536000');
            $response->header('Expires', gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT');
        }

        // Add security and performance headers
        $response->header('X-Content-Type-Options', 'nosniff');
        $response->header('X-Frame-Options', 'SAMEORIGIN');
        $response->header('X-XSS-Protection', '1; mode=block');
        $response->header('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Enable gzip compression (if server supports it)
        $response->header('Vary', 'Accept-Encoding');

        return $response;
    }

    /**
     * Check if the request is for a static asset
     */
    private function isStaticAsset(string $path): bool
    {
        return preg_match('~\.(jpg|jpeg|png|gif|css|js|ico|svg|woff|woff2|ttf|eot)$~i', $path);
    }
}
