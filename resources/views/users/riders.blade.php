@extends('layouts.default')

@section('content')
<div class="container">
    <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h1>
                    行车记录
                </h1>
            </div>

            <div class="panel-body">
                @if($riders->count())
                    <table class="table table-condensed table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th>开始时间</th> <th>结束时间</th> <th>费用</th>
                                <th class="text-right">OPTIONS</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($riders as $rider)
                                <tr>
                                    <td class="text-center"><strong>{{$rider->id}}</strong></td>

                                    <td>{{$rider->start_at}}</td> <td>{{$rider->end_at}}</td> <td>{{$rider->money}}</td>
                                    
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <h3 class="text-center alert alert-info">Empty!</h3>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection