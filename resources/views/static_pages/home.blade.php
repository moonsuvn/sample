@extends('layouts.default')

@section('content')
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
        resizeEnable: true,
        center: [117.14509,34.214571],//地图中心点
        zoom:17
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


    addCloudLayer();  //叠加云数据图层
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
                    content: "<font class='title'>" + clouddata._name + "</font><hr/>" + photo.join("") +  "<br />" +"单车状态：" + clouddata.status + "<br />"+ "创建时间：" + clouddata._createtime + "<br />" + "更新时间：" + clouddata._updatetime,
                    size: new AMap.Size(0, 0),
                    autoMove: true,
                    offset: new AMap.Pixel(0, -25)
                });
                infoWindow.open(map, clouddata._location);
            });
        });
    } 


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
    function onComplete(data){
        var str=['定位成功'];
    }
    //为keyListener方法注册按键事件  
    document.onkeydown=keyListener;   
  
    function keyListener(e){   
  
        //  当按下回车键，执行代码  
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
@stop