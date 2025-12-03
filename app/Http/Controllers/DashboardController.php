<?php

namespace App\Http\Controllers;

use App\Models\ShopifyErrorLog;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function home(Request $request)
    {
        /** @var User $user */
        $user = auth()->user();
        dd($user->getService()->getStoreInfo());
        ShopifyErrorLog::query()->create(
            [
                'user_id' => 6,
                'method' => 'dashboard',
                'data' => json_encode($request->all()),
            ]
        );
        /** @var User $user */
            return Inertia::render('Home', [
                'message' => 'Welcome Daniel!',
                'shop' => $request->get('shop'),
            ]);
        }
}
