@extends('base')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="jumbotron">
                    <h1>Select the version to download</h1>

                    <form action="{{ route('download') }}" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="video-id" value="{{ $video->id }}">
                        <div class="form-group">
                            <label for="itag">
                                Version
                            </label>
                            <select name="itag" id="itag" class="form-control">
                                <option disabled>Full formats</option>
                                @foreach($info->full_formats as $format)
                                    <option value="{{ $format->itag }}">{{ $format->type }} - {{ $format->quality }}</option>
                                @endforeach
                                <option disabled>Partials</option>
                                @if(isset($info->adaptive_formats))
                                @foreach($info->adaptive_formats as $format)
                                    @if(isset($info->quality_label))
                                        <option value="{{ $format->itag }}">{{ $format->type }} - {{ $format->quality_label }}</option>
                                    @else
                                        <option value="{{ $format->itag }}">{{ $format->type }}</option>
                                    @endif
                                @endforeach
                                @endif
                            </select>
                        </div>
                        <button type="submit" class="btn btn-default">Download</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
