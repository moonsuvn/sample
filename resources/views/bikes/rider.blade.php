@extends('layouts.default')
@section('title', '立即用车')

@section('content')
<div class="col-md-offset-2 col-md-8">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h5>请输入单车编码</h5>
    </div>
      <div class="panel-body">

        @include('shared._errors')

        <div class="gravatar_edit">
          <a href="http://gravatar.com/emails" target="_blank">
            <img src="{{ $user->gravatar('200') }}" alt="{{ $user->name }}" class="gravatar"/>
          </a>
        </div>

        <form method="POST" action="{{ route('users.riding', $user->id ) }}">
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
              <label for="code">单车编码：</label>
              <input type="text" name="code" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary">开始用车</button>
        </form>
        <form method="POST" action="{{ route('users.used',$user->id) }}">
          {{ method_field('PATCH') }}
            {{ csrf_field() }}

        
        </form>
    </div>
  </div>
</div>
@stop