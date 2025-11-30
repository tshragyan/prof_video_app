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
//        if (app()->environment('local') || $request->getHost() === 'localhost') {
//            return $next($request);
//        }
//
//        $query = $request->all();
        $query = json_decode('{"embedded":"1","hmac":"734d367c941e8006410798250860f9fd66ac93c3d886273f28b00cdfd0006036","host":"YWRtaW4uc2hvcGlmeS5jb20vc3RvcmUvdmlkZW9jcmF0","id_token":"eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczpcL1wvdmlkZW9jcmF0Lm15c2hvcGlmeS5jb21cL2FkbWluIiwiZGVzdCI6Imh0dHBzOlwvXC92aWRlb2NyYXQubXlzaG9waWZ5LmNvbSIsImF1ZCI6ImI1NDViZmVhMTY4MWNlOTg1NzMxYzg1M2IyMDY4YjVmIiwic3ViIjoiODY5MTAxNDA0NjQiLCJleHAiOjE3NjQ1MDczNjEsIm5iZiI6MTc2NDUwNzMwMSwiaWF0IjoxNzY0NTA3MzAxLCJqdGkiOiI5ZDgyY2NjZi02ZDJhLTQxOGEtYmY3Yy01Zjc5YWY3ZDk5NGQiLCJzaWQiOiI1M2E4Y2Q5OC1kNTY3LTRlMjctODJmYS1mZTFhMDZkZTc1ZTEiLCJzaWciOiJkNGRmYjE2ODgzY2RkOWIwNGUwZDNjNWVkOTY2MDM0ZWViNTA3MjI1MjA4NzUwYWNlMzBiNDk3YzRhOWUzMzcwIn0.q52xOGK771sfXXe17eFhrrlw4la8mpV1tDpqUkd-eOs","locale":"en","session":"670cc2da7ca1b4b8b99cad4f7de5ee4aa77c34777994e5bbf5eba175f285410e","shop":"videocrat.myshopify.com","timestamp":"1764507301"}', true);

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
        if (empty(json_decode($user->shopify_data)['shop'])) {
            $user->shopify_data =  json_encode($user->getService()->getStoreInfo());
        }

        return $next($request);
    }
}
