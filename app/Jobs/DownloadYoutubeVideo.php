<?php

namespace App\Jobs;

use Exception;
use App\Jobs\Job;
use App\Video;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Masih\YoutubeDownloader\YoutubeDownloader;


class DownloadYoutubeVideo extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    private $video;

    /**
     * Create a new job instance.
     *
     * @param Video $video
     * @return void
     */
    public function __construct(Video $video)
    {
        $this->video = $video;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      try {
        $youtube = new YoutubeDownloader($this->video->video_id);
        $youtube->setPath($this->video->download_directory);
        $info  = $youtube->getVideoInfo();
        $url = $info->full_formats[0]->url;
        $filename = $info->full_formats[0]->filename;
        $this->video->filename = $filename;
        $this->video->status = 'downloading';
        $this->video->save();
        $start = time();
        $youtube->onProgress = function ($downloadedBytes, $fileSize) use(&$start) {
          if (time() - $start > 10) {
            $this->video->size = $fileSize;
            $this->video->progress = $downloadedBytes;
            $this->video->save();
            $start = time();
          }
        };
        $youtube->downloadFull($url, $filename);
        $this->video->status = 'finished';
        $this->video->save();
      } catch (\Exception $e) {
        \Log::error($e->getMessage(), ['error' => $e]);
        try {
          $this->video->status = 'failed';
          $this->video->save();
        } catch (\Exception $e2) {
          
        }
      }
    }
}
