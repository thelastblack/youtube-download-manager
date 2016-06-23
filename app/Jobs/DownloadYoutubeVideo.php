<?php

namespace App\Jobs;

use Exception;
use App\Jobs\Job;
use App\Video;
use App\VideoVersion;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Masih\YoutubeDownloader\YoutubeDownloader;


class DownloadYoutubeVideo extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    private $videoVersion;

    /**
     * Create a new job instance.
     *
     * @param VideoVersion $videoVersion
     * @return void
     */
    public function __construct(VideoVersion $videoVersion)
    {
        $this->videoVersion = $videoVersion;
    }

    private function findItag($formats, $itag)
    {
        foreach($formats as $format) {
            if ($format->itag == $itag) {
                return $format;
            }
        }
        return false;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $video = $this->videoVersion->video;
        echo 'Fetched '.$video->id.PHP_EOL;
        try {
            $youtube = new YoutubeDownloader($video->video_id);
            $youtube->setPath($video->download_directory);
            $info  = $youtube->getVideoInfo();
            $format = $this->findItag($info->full_formats, $this->videoVersion->itag);
            if ($format === false) {
                $format = $this->findItag($info->adaptive_formats, $this->videoVersion->itag);
                if ($format === false) {
                    return $this->safeFailed($video);
                }
            }
            $video->filename = $format->filename;
            $video->status = 'downloading';
            $video->save();
            $start = time();
            $youtube->onProgress = function ($downloadedBytes, $fileSize) use(&$start, $video) {
                if (time() - $start > 5) {
                    if ($fileSize > 0) {
                        $video->size = $fileSize;
                        $video->progress = $downloadedBytes;
                        $videop->save();
                        $start = time();
                    }
                }
            };
            $youtube->downloadFull($format->url, $format->filename);
            $video->status = 'finished';
            $video->save();
            echo 'Finished '.$video->id.PHP_EOL;
        } catch (\Exception $e) {
            echo 'Failed '.$video->id.PHP_EOL;
            \Log::error($e->getMessage(), ['error' => $e]);
            $this->safeFailed($video);
        }
    }

    private function safeFailed($video)
    {
        try {
            $video->status = 'failed';
            $video->save();
        } catch (\Exception $e2) {

        }
    }
}
