<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadFromInstagram;
use App\Http\Requests\VideoUpladRequest;
use App\Http\Services\InstagramService;
use App\Http\Services\TelegramService;
use App\Models\User;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
            $user = auth()->user();

            Video::query()
                ->create([
                    'title' => $title ?? 'video_1' . ($user->videos()->count() + 1),
                    'user_id' => $user->id,
                    'size' => $file->getSize(),
                    'src' => asset('storage/' . $path),
                    'from' => Video::PC_KEYWORD,
                    'stored' => Video::STORED_LOCAL,
                    'path' => asset('storage/' . $path),
                ]);

            $responseData[] = [
                'path' => asset('storage/' . $path),
                'filename' => $filename,
            ];
        }

        return response()->json(['data' => $responseData]);
    }

    public function importFromInstagram(Request $request, InstagramService $instagramService)
    {
        $url = $request->get('url');

        if (!str_starts_with($url, Video::INSTAGRAM_VIDEOS_PREFIX)) {
            return response(['error' => 'Invalid video url'], 400);
        }

        $shortCode = getIgShortCodeFromUrl($url);
        $videoId = igShortcodeToId($shortCode);
        $video = $instagramService->importReels($videoId);

        if (isset($video['path'])) {
            /** @var User $user */
            $user = auth()->user();
            $video = Video::query()
                ->create([
                    'title' => 'video_' . ($user->videos()->count() + 1),
                    'user_id' => $user->id,
                    'size' => $video['size'],
                    'src' => env('APP_URL') . '/storage' . explode('app', $video['path'])[1],
                    'from' => Video::PC_KEYWORD,
                    'stored' => Video::STORED_LOCAL,
                    'path' => $video['path']
                ]);
        }

        return response(['url' => $video->src, 'id' => $video], 200);
    }

//    public function importFromTelegram(UploadFromInstagram $request, TelegramService $telegramService)
//    {
//        $url = $request->validated()['url'];
//
//        if (!str_starts_with($url, Video::INSTAGRAM_VIDEOS_PREFIX)) {
//            return response(['error' => 'Invalid video url'], 400);
//        }
//
//        $message = $telegramService->sendMessage($url);
//        $uploadedVideo = $telegramService->getBotMessageVideoById($message['bot'], $message['id']);
//
//        /** @var User $user */
//        $user = auth()->user();
//
//        $video = Video::query()
//            ->create([
//                'title' => $title ?? 'video_1' . ($user->videos()->count() + 1),
//                'user_id' => $user->id,
//                'size' => $uploadedVideo['size'],
//                'src' => env('APP_URL') . '/storage' .explode('app', $uploadedVideo['path'])[1],
//                'from' => Video::PC_KEYWORD,
//                'stored' => Video::STORED_LOCAL,
//                'path' => $uploadedVideo['path']
//            ]);
//
//        return response(['url' => $video->src, 'id' => $video], 200);
//    }

    public function saveVideo()
    {

    }

    public function removeVideos(Request $request)
    {
        $videos = $request->input('videos');

        if (is_null($videos) || !is_array($videos)) {
            return;
        }

        foreach ($videos as $videoItem) {
            /** @var Video $video */
            $video = Video::query()
                ->where('id', '=', $videoItem)
                ->first();

            if (auth()->user()->id != $video->user_id) {
                return;
            }

            Storage::disk('public')->delete('telegram_bot/videos/video.mp4');
            $video->delete();
        }
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
