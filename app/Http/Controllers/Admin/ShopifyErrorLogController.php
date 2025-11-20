<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShopifyErrorLog;
use Illuminate\Http\Request;

class ShopifyErrorLogController extends Controller
{
    public function index()
    {
        $shopifyErrorLogs = ShopifyErrorLog::query()->paginate(20);
        return view('admin.shopify_error_logs.index', compact('shopifyErrorLogs'));
    }

    public function show(ShopifyErrorLog $shopifyErrorLog)
    {
        return view('admin.shopify_error_logs.show', compact('shopifyErrorLog'));
    }
}
