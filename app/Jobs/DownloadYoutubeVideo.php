<?php

namespace App\Jobs;

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
      $youtube = new YoutubeDownloader($this->video->video_id);
      $youtube->setPath($this->video->download_directory);
      $info  = $youtube->getVideoInfo();
      $url = $info->full_formats[0]->url;
      $filename = $info->full_formats[0]->filename;
      $this->video->filename = $filename;
      $this->video->status = 'downloading';
      $this->video->save();
      $youtube->onProgress = function ($downloadedBytes, $fileSize) {
          $this->video->size = $fileSize;
          $this->video->progress = $downloadedBytes;
          $this->video->save();
          print("Downloaded {$downloadedBytes} of {$fileSize}".PHP_EOL);
      };
      $youtube->downloadFull($url, $filename);
      $this->video->status = 'finished';
      $this->video->save();
    }
}
