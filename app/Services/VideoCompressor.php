<?php

namespace App\Services;

use FFMpeg\FFMpeg;
use FFMpeg\FFProbe;
use FFMpeg\Format\Video\WebM;
use FFMpeg\Coordinate\Dimension;
use FFMpeg\Filters\Video\ResizeFilter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class VideoCompressor
{
    /**
     * Compress and convert a video to WebM (VP9) 1:1 square crop.
     *
     * @param  string  $sourcePath   Absolute path to the uploaded temp file
     * @param  string  $storagePath  Relative path inside storage/app/public (e.g. banners/abc.webm)
     * @return string  The storage-relative path of the output file
     */
    public function compressToWebM(string $sourcePath, string $storagePath): string
    {
        $ffmpegBin  = config('ffmpeg.ffmpeg.binaries',  env('FFMPEG_BINARIES',  'ffmpeg'));
        $ffprobeBin = config('ffmpeg.ffprobe.binaries', env('FFPROBE_BINARIES', 'ffprobe'));

        $ffmpeg = FFMpeg::create([
            'ffmpeg.binaries'  => $ffmpegBin,
            'ffprobe.binaries' => $ffprobeBin,
            'timeout'          => 3600,
            'ffmpeg.threads'   => 4,
        ]);

        $outputAbsPath = Storage::disk('public')->path($storagePath);

        // Ensure output directory exists
        $dir = dirname($outputAbsPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $video = $ffmpeg->open($sourcePath);

        // Get dimensions for square crop
        $ffprobe = FFProbe::create([
            'ffprobe.binaries' => $ffprobeBin,
        ]);
        $streams   = $ffprobe->streams($sourcePath);
        $videoStream = $streams->videos()->first();
        $width  = $videoStream ? (int) $videoStream->get('width')  : 720;
        $height = $videoStream ? (int) $videoStream->get('height') : 720;
        $side   = min($width, $height);

        // Crop to square then resize to 720x720
        $video->filters()
            ->crop(
                new \FFMpeg\Coordinate\Point(
                    (int)(($width  - $side) / 2),
                    (int)(($height - $side) / 2)
                ),
                new Dimension($side, $side)
            )
            ->resize(new Dimension(720, 720), ResizeFilter::RESIZEMODE_SCALE_WIDTH)
            ->synchronize();

        // WebM VP9 format — good compression, small file
        $format = new WebM();
        $format
            ->setKiloBitrate(800)          // ~800 kbps video
            ->setAudioKiloBitrate(96)      // 96 kbps audio
            ->setAudioChannels(2);

        $video->save($format, $outputAbsPath);

        return $storagePath;
    }

    /**
     * Check if FFmpeg binary is available on this system.
     */
    public static function isAvailable(): bool
    {
        $bin = env('FFMPEG_BINARIES', 'ffmpeg');
        $result = shell_exec(escapeshellcmd($bin) . ' -version 2>&1');
        return $result !== null && str_contains($result, 'ffmpeg version');
    }
}
