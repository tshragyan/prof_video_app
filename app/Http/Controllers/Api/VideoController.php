<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\VideoUpladRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class VideoController extends Controller
{
    public function upload(VideoUpladRequest $request)
    {
        $file = $request->file('video');
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('videos', $filename, 'public');

        return response()->json([
            'success' => true,
            'path' => asset('storage/' . $path),
            'filename' => $filename,
        ]);
    }

    public function list(Request $request)
    {
        $videos = auth()->user()->videos;

        return Inertia::render('Videos', [
            'message' => 'Videos',
            'videos' => $videos,
        ]);
    }
}
