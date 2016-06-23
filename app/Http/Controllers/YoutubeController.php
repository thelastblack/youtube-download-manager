<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Video;
use App\VideoVersion;
use App\Jobs\DownloadYoutubeVideo;
use Masih\YoutubeDownloader\YoutubeDownloader;

class YoutubeController extends Controller
{
    public function index()
    {
        return view('home')->with('videos', Video::orderBy('created_at', 'desc')->get());
    }

    public function versions(Request $request)
    {
        $video_id = $this->parse_url($request->input('video-url'));
        $youtube = new YoutubeDownloader($video_id);
        $info = $youtube->getVideoInfo();
        $video = new Video();
        $video->video_id = $video_id;
        $video->status = 'waiting';
        $video->save();
        return view('downloadoptions')->with('info', $info)->with('video', $video);
    }

    public function download(Request $request)
    {
        $video = Video::find($request->input('video-id'));
        $videoVersion = new VideoVersion();
        $videoVersion->itag = $request->input('itag');
        $video->version()->save($videoVersion);
        $this->dispatch(new DownloadYoutubeVideo($videoVersion));
        return redirect()->route('list');
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
	if (is_file($video->download_file)) {
            unlink($video->download_file);
        }
        $video->delete();
        return redirect()->route('list');
    }
}
