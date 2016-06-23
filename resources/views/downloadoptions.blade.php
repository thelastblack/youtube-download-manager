@extends('base')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="jumbotron">
                    <h1>Select the version to download</h1>

                    <form>
                        <div class="form-group">
                            <label for="itag">
                                Version
                            </label>
                            <select name="itag" id="itag">
                                @foreach($info->full_formats as $format)
                                    <option value="{{ $format->itag }}">{{ $format->filename }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-default">Download</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
