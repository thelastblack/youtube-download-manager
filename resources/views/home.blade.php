@extends('base')

@section('content')
@parent
  <div class="container">
    <div class="row">
      <div class="col-xs-12">
        <div class="jumbotron">
          <h1>Download and manage links</h1>

          <form class="form-inline" action="{{ route('download') }}" method="post">
            {{ csrf_field() }}
            <div class="form-group">
              <label for="exampleInputEmail1">Video URL</label>
              <input type="text" class="form-control" name="video-url" placeholder="URL">
            </div>
            <button type="submit" class="btn btn-default">Download</button>
          </form>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-xs-12">
        <h3>Downloads <span class="badge">{{ $videos->count() }}</span></h3>
        <table class="table table-bordered table-hover table-striped">
          <thead>
            <tr>
              <th>Video Id</th>
              <th>Name</th>
              <th>Progress</th>
              <th>Actions</th>
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
                    <span>60% Complete</span>
                  </div>
                </div>
                @else
                  {{ ucfirst($video->status) }}
                @endif
              </td>
              <td>
                <div class="btn-group btn-group-xs" role="group">
                  <a type="button" class="btn btn-success">Download</a>
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
