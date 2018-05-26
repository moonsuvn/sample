<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no, width=device-width">
    <title>行车轨迹</title>
    <link rel="stylesheet" href="http://cache.amap.com/lbs/static/main1119.css"/>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <style type="text/css">
        .title {
            font: 13px 'Microsoft Yahei';
            color: #09f
        }
        .amap-info-content {
            font-size: 12px
        }
    </style>
    <script type="text/javascript" src="http://webapi.amap.com/maps?v=1.4.6&key=ee399e130ab26d2bf52bbff59b82eff3"></script>
    <script src="//webapi.amap.com/ui/1.0/main.js"></script>
    <script type="text/javascript" src="http://cache.amap.com/lbs/static/addToolbar.js"></script>
</head>
<body>
<div id="container"></div>
<script type="text/javascript">

    //加载PathSimplifier，loadUI的路径参数为模块名中 'ui/' 之后的部分 
    AMapUI.load(['ui/misc/PathSimplifier'], function(PathSimplifier) {

    if (!PathSimplifier.supportCanvas) {
        alert('当前环境不支持 Canvas！');
        return;
    }

    //启动页面
    initPage(PathSimplifier);
    });
    //初始化地图对象，加载地图
    var map = new AMap.Map("container", {
        resizeEnable: true,
        center: [117.28345670000009,34.28345669999997],//地图中心点
        zoom: 15 //地图显示的缩放级别
    });   

	addCloudLayer();  //叠加云数据图层
    function addCloudLayer() {
        //加载云图层插件
        map.plugin('AMap.CloudDataLayer', function() {
            var layerOptions = {
                clickable: true
            };
            var cloudDataLayer = new AMap.CloudDataLayer('5b08ca9c7bbf1916a5a95851', layerOptions); //实例化云图层类
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
    }    

    /*$.post({
        url:"{{ route('users.setcloudtrack',$rider_id)}}",
        data:{
            _token: "{{ csrf_token() }}"
        },
        success:function(res){
            data=res.message;
            //alert($data);
        }
        });*/

    function initPage(PathSimplifier) {

    //创建组件实例
    var pathSimplifierIns = new PathSimplifier({
        zIndex: 100,
        map: map, //所属的地图实例
        getPath: function(pathData, pathIndex) {
            //返回轨迹数据中的节点坐标信息，[AMap.LngLat, AMap.LngLat...] 或者 [[lng|number,lat|number],...]
            return pathData.path;
        },
        getHoverTitle: function(pathData, pathIndex, pointIndex) {
            //返回鼠标悬停时显示的信息
            if (pointIndex >= 0) {
                //鼠标悬停在某个轨迹节点上
                return pathData.name + '，点:' + pointIndex + '/' + pathData.path.length;
            }
            //鼠标悬停在节点之间的连线上
            return pathData.name + '，点数量' + pathData.path.length;
        },
        renderOptions: {
            //轨迹线的样式
            pathLineStyle: {
                strokeStyle: 'red',
                lineWidth: 6,
                dirArrowStyle: true
            }
        }
    });

    //这里构建轨迹，
    /*pathSimplifierIns.setData([{
        name: '骑行路线',
        path: [
            [117.1234567,34.1234567],
            [116.1234567,33.1234567]
        ]
    }]);*/
    //构造轨迹
    $.post({
        url:"{{ route('users.setcloudtrack',$rider_id)}}",
        data:{
            _token: "{{ csrf_token() }}"
        },
        success:function(res){
            data=res.message;
            var scatter=[];
            for (var i=0;i<data.length;i++){
                scatter[i]=data[i].split(",");
            }           
            pathSimplifierIns.setData([{
            name: '骑行路线',
            path: scatter
    }]);
            //alert(data);
        }
        });

    //创建一个巡航器
    var navg0 = pathSimplifierIns.createPathNavigator(0, //关联第1条轨迹
        {
            loop: true, //循环播放
            speed: 1000000
        });

    navg0.start();
}
</script>
</body>
</html>