<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\VideoUpladRequest;
use App\Models\User;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Inertia\Inertia;

class VideoController extends Controller
{
    public function upload(VideoUpladRequest $request)
    {
        $responseData = [];
        foreach ($request->validated()['videos'] as $file) {
            $title = $request->validated()['title'] ?? 'aaa';
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('videos', $filename, 'public');
            /** @var User $user */
            $user = User::query()->first();

            Video::query()
                ->create([
                    'title' => $title ?? 'video_1' . ($user->videos()->count() + 1),
                    'user_id' => $user->id,
                    'size' => $file->getSize(),
                    'src' => asset('storage/' . $path),
                ]);

            $responseData[] = [
                'path' => asset('storage/' . $path),
                'filename' => $filename,
            ];
        }

        return response()->json(['data' => $responseData]);
    }

    public function list(Request $request)
    {
        $response = Http::get('https://www.instagram.com/reel/DR9WX-BEUQ4/');
//        dd($response->body());

        /** @var User $user */
        $user = User::query()->first();
//        $videos = auth()->user()->videos;
        $videos = [];
        return Inertia::render('Videos', [
            'message' => 'Videos',
            'videos' => $videos,
        ]);
    }
}
