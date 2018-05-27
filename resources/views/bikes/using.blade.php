<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no, width=device-width">
    <title>正在计时</title>
    <link rel="stylesheet" href="https://cache.amap.com/lbs/static/main1119.css"/>
    <script type="text/javascript" src="https://webapi.amap.com/maps?v=1.4.6&key=ee399e130ab26d2bf52bbff59b82eff3&plugin=AMap.Geolocation"></script>
    <script type="text/javascript" src="https://cache.amap.com/lbs/static/addToolbar.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<body>
<button onclick="s();stopCount()" id="ajax">立即还车</button>
<button onclick="timedCount()">开始计时</button>
<input type="text" id="txt" >
<div id='container' style="margin-top:60px"></div>
<div id="tip"></div>

<script type="text/javascript">
    
    var c=0;
    var t;
    var map, geolocation;
    //加载地图，调用浏览器定位服务
    map = new AMap.Map('container', {
        resizeEnable: true
    });
    function timeCount(){
        document.getElementById('txt').value=c;
    map.plugin('AMap.Geolocation',function(){
        geolocation = new AMap.Geolocation({
            enableHighAccuracy: true,//是否使用高精度定位，默认:true
            timeout: 10000,          //超过10秒后停止定位，默认：无穷大
            buttonOffset: new AMap.Pixel(10, 20),//定位按钮与设置的停靠位置的偏移量，默认：Pixel(10, 20)
            zoomToAccuracy: true,      //定位成功后调整地图视野范围使定位位置及精度范围视野内可见，默认：false
            buttonPosition:'RB'
        });
        map.addControl(geolocation);
        geolocation.getCurrentPosition();
        //watchPosition();//持续定位；
        AMap.event.addListener(geolocation, 'complete', onComplete);//返回定位信息
        AMap.event.addListener(geolocation, 'error', onError);      //返回定位出错信息
    });
    //解析定位结果
    function onComplete(data) {
        longitude=data.position.getLng();
        latitude=data.position.getLat();
        longitude=float(longitude);
        latitude=float(latitude);
    }
    //解析定位错误信息
    function onError(data) {
        document.getElementById('tip').innerHTML = '定位失败';
    }

    $.post({
            url:"{{ route('users.track',$user->id)}}",
            data:{
                longitude:longitude,
                latitude:latitude,
                _token:"{{ csrf_token() }}"
            },
            success:function(){

            }
        });
      

    function s()
    {
        //alert(latitude);
        $.post({
        url:"{{ route('users.used',$user->id)}}",
        data:{
            longitude:longitude,
            latitude:latitude,
            _token: "{{ csrf_token() }}"
        },
        success:function(res){
            alert(res.message);
            //跳转
            window.location.href="{{ route('users.rider',$user) }}";
        }
        });
    }

    c+=1;
    t=setTimeout("timedCount()",1000);
    }
    
    /*function timedCount()
    {
        document.getElementById('txt').value=c;
        $.post({
            url:"{{ route('users.track',$user->id)}}",
            data:{
                longitude:longitude,
                latitude:latitude,
                _token:"{{ csrf_token() }}"
            },
            success:function(){

            }
        });
        c+=1;
        t=setTimeout("timedCount()",1000);
    }*/

    function stopCount()
    {

        clearTimeout(t);

    }
    
</script>
</body>
</html>