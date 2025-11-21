<?php

namespace App\Http\Controllers;

use App\Models\ShopifyErrorLog;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function home(Request $request)
    {
        ShopifyErrorLog::query()->create(
            [
                'user_id' => 1,
                'method' => 'main',
                'data' => $request->all(),
            ]
        );

        return Inertia::render('Home', [
            'message' => 'Welcome Daniel!'
        ]);
    }
}
