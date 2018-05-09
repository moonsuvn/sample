@extends('layouts.app')

@section('content')

<div class="container">
    <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h1>Bike / Show #{{ $bike->id }}</h1>
            </div>

            <div class="panel-body">
                <div class="well well-sm">
                    <div class="row">
                        <div class="col-md-6">
                            <a class="btn btn-link" href="{{ route('bikes.index') }}"><i class="glyphicon glyphicon-backward"></i> Back</a>
                        </div>
                        <div class="col-md-6">
                             <a class="btn btn-sm btn-warning pull-right" href="{{ route('bikes.edit', $bike->id) }}">
                                <i class="glyphicon glyphicon-edit"></i> Edit
                            </a>
                        </div>
                    </div>
                </div>

                <label>Lng</label>
<p>
	{{ $bike->lng }}
</p> <label>Lat</label>
<p>
	{{ $bike->lat }}
</p> <label>Is_riding</label>
<p>
	{{ $bike->is_riding }}
</p>
            </div>
        </div>
    </div>
</div>

@endsection
