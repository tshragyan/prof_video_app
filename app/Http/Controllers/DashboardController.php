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
            return Inertia::render('Home', [
                'message' => 'Welcome Daniel!',
                'shop' => $request->get('shop'),
            ]);
        }
}
