<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no, width=device-width">
    <title>正在计时</title>
    <link rel="stylesheet" href="http://cache.amap.com/lbs/static/main1119.css"/>
    <script type="text/javascript" src="http://webapi.amap.com/maps?v=1.4.6&key=d0f548836c9d59d72e30767cc82baaf6&plugin=AMap.Geolocation"></script>
    <script type="text/javascript" src="http://cache.amap.com/lbs/static/addToolbar.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<body>
<button onclick="s()" id="ajax">立即还车</button>
<div id='container' style="margin-top:60px"></div>
<div id="tip"></div>

<script type="text/javascript">
/***************************************
由于Chrome、IOS10等已不再支持非安全域的浏览器定位请求，为保证定位成功率和精度，请尽快升级您的站点到HTTPS。
***************************************/
    var map, geolocation;
    //加载地图，调用浏览器定位服务
    map = new AMap.Map('container', {
        resizeEnable: true
    });

    /*addCloudLayer();  //叠加云数据图层
    function addCloudLayer() {
        //加载云图层插件
        map.plugin('AMap.CloudDataLayer', function() {
            var layerOptions = {
                clickable: true
            };
            var cloudDataLayer = new AMap.CloudDataLayer('5b060ee4305a2a668877b2eb', layerOptions); //实例化云图层类
            cloudDataLayer.setMap(map); //叠加云图层到地图
            AMap.event.addListener(cloudDataLayer, 'click', function(result) {
                var clouddata = result.data;
                var photo = [];
                if (clouddata._image[0]) {//如果有上传的图片
                    photo = ['<img width=240 height=100 src="' + clouddata._image[0]._preurl + '"><br>'];
                }
                var infoWindow = new AMap.InfoWindow({
                    content: "<font class='title'>" + clouddata._name + "</font><hr/>" + photo.join("") + "地址：" + clouddata._address + "<br />" + "创建时间：" + clouddata._createtime + "<br />" + "更新时间：" + clouddata._updatetime,
                    size: new AMap.Size(0, 0),
                    autoMove: true,
                    offset: new AMap.Pixel(0, -25)
                });
                infoWindow.open(map, clouddata._location);
            });
        });
    }*/

        geolocation = new AMap.Geolocation({
            enableHighAccuracy: true,//是否使用高精度定位，默认:true
            timeout: 10000,          //超过10秒后停止定位，默认：无穷大
            buttonOffset: new AMap.Pixel(10, 20),//定位按钮与设置的停靠位置的偏移量，默认：Pixel(10, 20)
            zoomToAccuracy: true,      //定位成功后调整地图视野范围使定位位置及精度范围视野内可见，默认：false
            buttonPosition:'RB'
        });
        map.addControl(geolocation);
        geolocation.getCurrentPosition();
        
        AMap.event.addListener(geolocation, 'complete', onComplete);//返回定位信息
        AMap.event.addListener(geolocation, 'error', onError);      //返回定位出错信息
    //解析定位结果
    function onComplete(data) {
        var str=['定位成功'];
        longitude=data.position.getLng();
        latitude=data.position.getLat();
        longitude=float(longitude);
        latitude=float(latitude);
        str.push('经度：' + data.position.getLng());
        str.push('纬度：' + data.position.getLat());
        if(data.accuracy){
             str.push('精度：' + data.accuracy + ' 米');
        }//如为IP精确定位结果则没有精度信息
        str.push('是否经过偏移：' + (data.isConverted ? '是' : '否'));
        document.getElementById('tip').innerHTML = str.join('<br>');
    }
    //解析定位错误信息
    function onError(data) {
        document.getElementById('tip').innerHTML = '定位失败';
    }
      

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

    /*function s()
    {
        //alert(latitude);
        $.post({
        url:"{{ route('users.used',$user->id)}}",
        data:{
            longitude:117.1234567,
            latitude:34.1234567,
            _token: "{{ csrf_token() }}"
        },
        success:function(res){
            alert(res.message);
            //跳转
            window.location.href="{{ route('users.rider',$user) }}";
        }
        });
    }*/
    
</script>
</body>
</html>