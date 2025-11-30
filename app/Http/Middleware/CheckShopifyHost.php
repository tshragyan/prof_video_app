<?php

namespace App\Http\Middleware;

use App\Models\User;
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

        $query = $request->all();
        $hmac = $query['hmac'] ?? null;

        if (!$hmac) {
            return redirect(route('access_denied'));
        }

        unset($query['hmac']);
        ksort($query);
        $queryString = urldecode(http_build_query($query));
        $calculatedHmac = hash_hmac('sha256', $queryString, config('services.shopify.client_secret'));

        if (!hash_equals($hmac, $calculatedHmac)) {
            return redirect(route('access_denied'));
        }

        /** @var User $user */
        $user = User::query()->where('shopify_username', $query['shop'])->first();
        auth()->login($user);
            $user->shopify_data =  json_encode($user->getService()->getStoreInfo());
            $user->save();

        return $next($request);
    }
}
