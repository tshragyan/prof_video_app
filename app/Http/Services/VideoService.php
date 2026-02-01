<?php


namespace App\Http\Services;


use Illuminate\Support\Facades\File;

class VideoService
{
    public function downloadVideosToFiles($videoId, $videoUrl)
    {
        $path = storage_path("app/public/instagram/reels");

        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }

        file_put_contents(
            "$path/{$videoId}.mp4",
            file_get_contents($videoUrl)
        );

        $bytes = filesize($path);

        return ['path' => "$path/{$videoId}.mp4", 'size' => $bytes];

    }
}
