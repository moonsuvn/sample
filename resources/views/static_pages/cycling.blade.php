<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no, width=device-width">
    <title>骑车路线</title>
    <link rel="stylesheet" href="http://cache.amap.com/lbs/static/main1119.css"/>
    <style type="text/css">
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
            top: 10px;
            left: 60px;
            width: 170px;
        }
        #end {
            position: fixed;
            background-color: white;
            max-height: 90%;
            overflow-y: auto;
            top: 10px;
            left: 230px;
            width: 170px;
        }
        #buton1 {
            position: fixed;
            background-color: white;
            max-height: 90%;
            overflow-y: auto;
            top: 10px;
            right: 300px;
            width: 50px;
        }
    </style>
    <script type="text/javascript" src="http://webapi.amap.com/maps?v=1.4.6&key=ee399e130ab26d2bf52bbff59b82eff3&plugin=AMap.Riding&plugin=AMap.Autocomplete"></script>
    <script type="text/javascript" src="http://cache.amap.com/lbs/static/addToolbar.js"></script>
</head>
<body>
<div id="container"></div>
<div id="panel"></div>
<!--<button id="buton1" onclick=search()>submit</button>-->
<div id="start">
    <table>
        <tr>
            <td>
                <label>请输入起点：</label>
            </td>
        </tr>
        <tr>
            <td>
                <input id="starttext" type="text" />
            </td>
        </tr>
    </table>
</div>
<div id="end">
    <table>
        <tr>
            <td>
                <label>请输入终点：</label>
            </td>
        </tr>
        <tr>
            <td>
                <input id="endtext" type="text"/>
            </td>
        </tr>
    </table>
</div>
<script type="text/javascript">
    var map = new AMap.Map("container", {
        resizeEnable: true
    });
    //自动搜索
    var autostart = new AMap.Autocomplete({
        city: "徐州",
        input: "starttext"
    });
    var autoend = new AMap.Autocomplete({
        city: "徐州",
        input: "endtext"
    })
    //骑行导航
    var riding = new AMap.Riding({
        map: map,
        panel: "panel"
    }); 
    map.plugin('AMap.Geolocation', function() {
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
    });
    /*function search()
    {
    var inputstart=document.getElementById('starttext');
    var inputend=document.getElementById('endtext');
    var startvalue=inputstart.value;
    var endvalue=inputend.value;
    riding.search([
                     {keyword: startvalue,city:'徐州'},
                     //第一个元素city缺省时取transOptions的city属性
                     {keyword: endvalue,city:'徐州'}
                     //第二个元素city缺省时取transOptions的cityd属性,
                     //没有cityd属性时取city属性
                    ]);
    }*/
    //为keyListener方法注册按键事件  
    document.onkeydown=keyListener;   
  
    function keyListener(e){   
  
        //  当按下回车键，执行我们的代码  
        if(e.keyCode == 13){   
  
        var inputstart=document.getElementById('starttext');
        var inputend=document.getElementById('endtext');
        var startvalue=inputstart.value;
        var endvalue=inputend.value;
        riding.search([
                     {keyword: startvalue,city:'徐州'},
                     //第一个元素city缺省时取transOptions的city属性
                     {keyword: endvalue,city:'徐州'}
                     //第二个元素city缺省时取transOptions的cityd属性,
                     //没有cityd属性时取city属性
                ]);  
  
            }   
  
        }
</script>
</body>
</html>