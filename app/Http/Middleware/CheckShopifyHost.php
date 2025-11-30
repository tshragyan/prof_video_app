<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckShopifyHost
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (app()->environment('local') || $request->getHost() === 'localhost') {
            return $next($request);
        }

        $query = $request->query();
        $hmac = $query['hmac'] ?? null;

        if (!$hmac) {
            return redirect(route('access_denied'));
        }

        unset($query['hmac']);
        ksort($query);
        $queryString = http_build_query($query);
        $calculatedHmac = hash_hmac('sha256', $queryString, env('SHOPIFY_API_SECRET'));

        if (!hash_equals($hmac, $calculatedHmac)) {
            return redirect(route('access_denied'));
        }

        return $next($request);
    }
}
