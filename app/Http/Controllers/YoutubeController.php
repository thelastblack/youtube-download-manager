<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Video;
use App\Jobs\DownloadYoutubeVideo;

class YoutubeController extends Controller
{
    public function index()
    {
      return view('home')->with('videos', Video::orderBy('created_at', 'desc')->get());
    }

    public function download(Request $request)
    {
      $video_id = $this->parse_url($request->input('video-url'));
      $youtube = new YoutubeDownloader($video_id);
      $info = $youtube->getVideoInfo();
      return view('downloadoptions')->with('info', $info);
    }

    public function createDownloadJob(Request $request)
    {
      $video_id = $this->parse_url($request->input('video-url'));
      $video = new Video();
      $video->video_id = $video_id;
      $video->status = 'waiting';
      $video->save();
      $this->dispatch(new DownloadYoutubeVideo($video));
      return redirect()->back();
    }

    private function parse_url($url)
    {
      $query = parse_url($url, PHP_URL_QUERY);
      parse_str($query, $query_params);
      if (isset($query_params['v'])) {
        return $query_params['v'];
      } else {
        return null;
      }
    }

    public function delete(Video $video)
    {
      if (file_exists($video->download_file)) {
        unlink($video->download_file);
      }
      $video->delete();
      return redirect()->back();
    }
}
