@extends('base')

@section('content')
@parent
  <div class="container">
    <div class="row">
      <div class="col-xs-12">
        <div class="jumbotron">
          <h1>Download and manage links</h1>

          <form action="{{ route('versions') }}" method="post">
            {{ csrf_field() }}
            <div class="form-group">
              <label for="video-url">Video URL</label>
              <input type="text" id="video-url" class="form-control" name="video-url" placeholder="URL">
            </div>
            <button type="submit" class="btn btn-default">Download</button>
          </form>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-xs-12">
        <h3>Downloads <span class="badge">{{ $videos->count() }}</span></h3>
        <table class="table table-responsive table-bordered table-hover table-striped">
          <thead>
            <tr>
              <th class="col-xs-2">Video Id</th>
              <th class="col-xs-3">Name</th>
              <th class="col-xs-5">Progress</th>
              <th class="col-xs-2">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($videos as $video)
            <tr class="{{ $video->bootstrap_class }}">
              <td>{{ $video->video_id }}</td>
              <td>{{ $video->filename }}</td>
              <td>
                @if($video->status == 'downloading')
                <div class="progress">
                  <div class="progress-bar" role="progressbar" style="width: {{ $video->percent }}%;">
                    {{ $video->percent }}% Complete
                  </div>
                </div>
                @else
                  {{ ucfirst($video->status) }}
                @endif
              </td>
              <td>
                <div class="btn-group btn-group-xs" role="group">
                  @if($video->status == 'finished')
                  <a type="button" class="btn btn-success" href="{{ $video->download_link }}">Download</a>
                  @endif
                  @if($video->status != 'downloading')
                  <a type="button" class="btn btn-danger" href="{{ route('delete', ['video' => $video->id]) }}">Delete</a>
                  @endif
                </div>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endsection
