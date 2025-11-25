<?php

namespace App\Http\Controllers;

use App\Models\ShopifyErrorLog;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function home(Request $request)
    {
        try {

            return Inertia::render('Home', [
                'message' => 'Welcome Daniel!',
                'shop' => $request->get('shop')
            ]);
        } catch (\Throwable $e) {


            ShopifyErrorLog::query()->create(
                [
                    'user_id' => 1,
                    'method' => 'dashboard',
                    'data' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(),

                ]
            );
        }
    }
}
