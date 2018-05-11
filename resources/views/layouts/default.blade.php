<!DOCTYPE html>
<html>
  <head>
    <title>@yield('title', 'OfO App')</title>
    <link rel="stylesheet" href="/css/app.css">
    <script type="text/javascript" src="http://webapi.amap.com/maps?v=1.4.6&key=ee399e130ab26d2bf52bbff59b82eff3&plugin=AMap.Riding&plugin=AMap.Autocomplete"></script>
    <script type="text/javascript" src="http://cache.amap.com/lbs/static/addToolbar.js"></script>
    <style type="text/css">
        #container {
            position: absolute;
            top: 37px;
            left: 0;
            right: 0;
            bottom: 0;
            width: 100%;
            height: 100%;
          }
        #panel {
            position: fixed;
            background-color: white;
            max-height: 90%;
            overflow-y: auto;
            top: 10px;
            right: 10px;
            width: 280px;
        }
        #start {
            position: fixed;
            background-color: white;
            max-height: 90%;
            overflow-y: auto;
            top: 57px;
            left: 60px;
            width: 170px;
        }
        #end {
            position: fixed;
            background-color: white;
            max-height: 90%;
            overflow-y: auto;
            top: 57px;
            left: 230px;
            width: 170px;
        }
    </style>
  </head>
  <body>
    @include('layouts._header')

    <div class="container">
      <!--<div class="col-md-offset-1 col-md-10">-->
        @include('shared._messages')
        @yield('content')
       <!-- @include('layouts._footer')
      </div>-->
    </div>

    <script src="/js/app.js"></script>
  </body>
</html>