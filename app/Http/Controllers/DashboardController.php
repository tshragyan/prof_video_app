<?php

namespace App\Http\Controllers;

use App\Http\Services\TelegramService;
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


    public function downloader()
    {
        return view('downloader');
    }


    public function download(Request $request, TelegramService $telegramService)
    {
        return response()->download($telegramService->sendMessage($request->input('insta-url')));
    }


}
