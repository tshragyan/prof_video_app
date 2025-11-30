<?php

namespace App\Http\Middleware;

use App\Models\ShopifyErrorLog;
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

//        $request = json_decode('{"embedded":"1","hmac":"1f57466022f039dc63c6152b03734ac96e3caafdcc8a2d81ed3863e074b20682","host":"YWRtaW4uc2hvcGlmeS5jb20vc3RvcmUvdmlkZW9jcmF0","id_token":"eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczpcL1wvdmlkZW9jcmF0Lm15c2hvcGlmeS5jb21cL2FkbWluIiwiZGVzdCI6Imh0dHBzOlwvXC92aWRlb2NyYXQubXlzaG9waWZ5LmNvbSIsImF1ZCI6ImI1NDViZmVhMTY4MWNlOTg1NzMxYzg1M2IyMDY4YjVmIiwic3ViIjoiODY5MTAxNDA0NjQiLCJleHAiOjE3NjQ1MDY4NzEsIm5iZiI6MTc2NDUwNjgxMSwiaWF0IjoxNzY0NTA2ODExLCJqdGkiOiIwMTUwY2JmYi03NDU4LTQwOWItYTcxZS01ZjYwNDdlMzkwYTIiLCJzaWQiOiI1M2E4Y2Q5OC1kNTY3LTRlMjctODJmYS1mZTFhMDZkZTc1ZTEiLCJzaWciOiIyZDhlMTVkZTEwNTk4MzY1Y2EwOGYyMmMxOWNhMGUwN2E3MTlhZmM0ZThhZmEzNDI0ZWEzYmI3ZGQxNDJmNTllIn0.q2ZslDX6tspl95H4ifzJ_vnetFRBg6YOdfpvysvuN5g","locale":"en","session":"670cc2da7ca1b4b8b99cad4f7de5ee4aa77c34777994e5bbf5eba175f285410e","shop":"videocrat.myshopify.com","timestamp":"1764506811"}', true);
//        dd($request);
//
        ShopifyErrorLog::query()->create(
            [
                'user_id' => 1,
                'method' => 'dashboard',
                'data' => json_encode($request->all()),
            ]
        );

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
        $queryString = http_build_query($query);
        $calculatedHmac = hash_hmac('sha256', $queryString, env('SHOPIFY_API_SECRET'));

        if (!hash_equals($hmac, $calculatedHmac)) {
            return redirect(route('access_denied'));
        }

        return $next($request);
    }
}
