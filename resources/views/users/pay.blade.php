@extends('layouts.default')
@section('title', '账户充值')

@section('content')
<div class="col-md-offset-2 col-md-8">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h5>充值中心</h5>
    </div>
      <div class="panel-body">

        @include('shared._errors')

        <div class="gravatar_edit">
          <a href="http://gravatar.com/emails" target="_blank">
            <img src="{{ $user->gravatar('200') }}" alt="{{ $user->name }}" class="gravatar"/>
          </a>
        </div>

        <form method="POST" action="{{ route('users.payBalance', $user->id )}}">
            {{ method_field('PATCH') }}
            {{ csrf_field() }}

            <div class="form-group">
              <label for="email">邮箱：</label>
              <input type="text" name="email" class="form-control" value="{{ $user->email }}" disabled>
            </div>

            <div class="form-group">
              <label for="balance">账户余额</label>
              <input type="text" name="balance" class="form-control" value="{{ $user->balance }}" disabled>
            </div>

            <div class="form-group">
              <label for="pay">充值金额(RMB)：</label>
              <input type="text" name="pay" class="form-control" value="5">
            </div>

            <button type="submit" class="btn btn-primary">确认充值</button>
        </form>
    </div>
  </div>
</div>
@stop
