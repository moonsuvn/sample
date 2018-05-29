<header class="navbar navbar-fixed-top navbar-inverse" role="navigation">
  <div class="container" style="padding-right: 0px">
    <div class="col-md-offset-1 col-md-10 col-xs-10">
      <div class="navbar-header" >
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-menu" style="padding-right: 0px">
        <span class="sr-only">切换导航</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      </button>
      <a href="/" id="logo" class="navbar-brand nav-title" style="padding-top:15px ">App</a>
     </div>
      <nav>
        <div class="collapse navbar-collapse" id="navbar-menu">
        <ul class="nav navbar-nav navbar-right">

          @if (Auth::check())
            <li><a href="{{ route('bikes.index') }}">单车列表</a></li>
            <li><a href="{{ route('users.index') }}">用户列表</a></li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                {{ Auth::user()->name }} <b class="caret"></b>
              </a>
              <ul class="dropdown-menu">
                <li><a href="{{ route('users.rider', Auth::user()->id ) }}">立即用车</a></li>
                <li><a href="{{ route('users.show', Auth::user()->id) }}">个人中心</a></li>
                <li><a href="{{ route('users.edit', Auth::user()->id) }}">编辑资料</a></li>
                <li><a href="{{ route('users.payCenter', Auth::user()->id) }}">充值中心</li>
                <li><a href="{{ route('users.riders', Auth::user()->id) }}">行车记录</a></li>
                <li class="divider"></li>
                <li>
                  <a id="logout" href="#">
                    <form action="{{ route('logout') }}" method="POST">
                      {{ csrf_field() }}
                      {{ method_field('DELETE') }}
                      <button class="btn btn-block btn-danger" type="submit" name="button">退出</button>
                    </form>
                  </a>
                </li>
              </ul>
            </li>
          @else
            <li><a href="{{ route('help') }}">帮助</a></li>
            <li><a href="{{ route('login') }}">登录</a></li>
            <li><a href="{{ route('signup') }}">注册</a></li>
          @endif
        </ul>
      </div>
      </nav>
    </div>
  </div>
</header>