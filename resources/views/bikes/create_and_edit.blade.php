@extends('layouts.app')

@section('content')

<div class="container">
    <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">
            
            <div class="panel-heading">
                <h1>
                    <i class="glyphicon glyphicon-edit"></i> Bike /
                    @if($bike->id)
                        Edit #{{$bike->id}}
                    @else
                        Create
                    @endif
                </h1>
            </div>

            @include('common.error')

            <div class="panel-body">
                @if($bike->id)
                    <form action="{{ route('bikes.update', $bike->id) }}" method="POST" accept-charset="UTF-8">
                        <input type="hidden" name="_method" value="PUT">
                @else
                    <form action="{{ route('bikes.store') }}" method="POST" accept-charset="UTF-8">
                @endif

                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    
                <div class="form-group">
                    <label for="lng-field">Lng</label>
                    <input class="form-control" type="text" name="lng" id="lng-field" value="{{ old('lng', $bike->lng ) }}" />
                </div> 
                <div class="form-group">
                    <label for="lat-field">Lat</label>
                    <input class="form-control" type="text" name="lat" id="lat-field" value="{{ old('lat', $bike->lat ) }}" />
                </div> 
                <div class="form-group">
                    <label for="is_riding-field">Is_riding</label>
                    <input class="form-control" type="text" name="is_riding" id="is_riding-field" value="{{ old('is_riding', $bike->is_riding ) }}" />
                </div>

                    <div class="well well-sm">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <a class="btn btn-link pull-right" href="{{ route('bikes.index') }}"><i class="glyphicon glyphicon-backward"></i>  Back</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection