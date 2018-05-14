<html>
<head>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript">
    
    //经纬度计算
    var totalDistance = 0.0;
    var lastLat;
    var lastLong;

    function toRadians(degree) {
      return this * Math.PI / 180;
  }


  function distance(latitude1, longitude1, latitude2, longitude2) {
      // R是地球半径（KM）
      var R = 6371;

      var deltaLatitude = toRadians(latitude2-latitude1);
      var deltaLongitude = toRadians(longitude2-longitude1);
      latitude1 = toRadians(latitude1);
      latitude2 = toRadians(latitude2);

      var a = Math.sin(deltaLatitude/2) *
      Math.sin(deltaLatitude/2) +
      Math.cos(latitude1) *
      Math.cos(latitude2) *
      Math.sin(deltaLongitude/2) *
      Math.sin(deltaLongitude/2);

      var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
      var d = R * c;
      return d;
  }


  function updateStatus(message) {
    document.getElementById("status").innerHTML = message;
}

function loadDemo() {
    if(navigator.geolocation) {
        updateStatus("浏览器支持HTML5 Geolocation。");
        navigator.geolocation.watchPosition(updateLocation, handleLocationError, {maximumAge:20000});
    }
}


function updateLocation(position) {
    window.latitude = position.coords.latitude;//获取纬度值
    window.longitude = position.coords.longitude;//获取经度值
    window.accuracy = position.coords.accuracy;

    document.getElementById("latitude").innerHTML = latitude;
    document.getElementById("longitude").innerHTML = longitude;
    document.getElementById("accuracy").innerHTML = accuracy;

       // 如果accuracy的值太大，我们认为它不准确，不用它计算距离
        if (accuracy >= 500) {
            updateStatus("这个数据太不靠谱，需要更准确的数据来计算本次移动距离。");
            return};

        // 计算移动距离

        if ((lastLat != null) && (lastLong != null)) {
            var currentDistance = distance(latitude, longitude, lastLat, lastLong);
            document.getElementById("currDist").innerHTML =
            "本次移动距离：" + currentDistance.toFixed(4) + " 千米";

            totalDistance += currentDistance;

            document.getElementById("totalDist").innerHTML =
            "总计移动距离：" + currentDistance.toFixed(4) + " 千米";
        }

        lastLat = latitude;
        lastLong = longitude;

        updateStatus("计算移动距离成功。");
        
    }

function s()
{
    alert(latitude);
}

/*
    function latitude(position) {

        var latitude = position.coords.latitude;

        return latitude;
      }
    function longitude(position) {

        var longitude = position.coords.longitude;

        return longitude;
      }
    
    window.lng=latitude(position);
    window.lat=longitude(position);
    //window.lng=123.1234567;
    //window.lat=123.1234567;
    $(document).ready(function(){
    $("#ajax").click(function(){
        $.post({
            url:"{{ route('users.used',$user->id)}}",
            data:{
                longitude:lng,
                latitude:lat,
                _token: "{{ csrf_token() }}"
            },
            success:function(res){
                alert(res.message);
                //跳转
                window.location.href="{{ route('users.rider',$user) }}";
            }
        }
        );
    });
});
*/
    function handleLocationError(error) {
        switch(error.code)
        {
            case 0:
            updateStatus("尝试获取您的位置信息时发生错误：" + error.message);
            break;
            case 1:
            updateStatus("用户拒绝了获取位置信息请求。");
            break;
            case 2:
            updateStatus("浏览器无法获取您的位置信息：" + error.message);
            break;
            case 3:
            updateStatus("获取您位置信息超时。");
            break;
        }
    }
</script>
</head>

<body>
    <button onclick="s()">123</button>
    <!--<form method="POST" action="{{ route('users.used',$user->id)}}">
        {{ method_field('PATCH') }}
        {{ csrf_field() }}
        <input type="text" id="txt">
        <div class="form-group">
            <label for="code">单车编码：</label>
            <input type="text" name="code" class="form-control">
        </div>
        <input type="submit" value="结束用车" onClick="stopCount()">
    </form>-->

    <h1>HTML5 Geolocation距离跟踪器</h1>

    <p id="status">该浏览器不支持HTML5 Geolocation</p>

    <h2>当前位置：</h2>
    <table border="1">
        <tr>
            <td width="40" scope="col">纬度</th>
                <td width="114" id="latitude" for="latitude">?</td>
            </tr>
            <tr>
                <td>经度</td>
                <td id="longitude" for="longitude">?</td>
            </tr>
            <tr>
                <td>准确度</td>
                <td id="accuracy">?</td>
            </tr>
        </table>

        <h4 id="currDist">本次移动距离：0 千米</h4>
        <h4 id="totalDist">总计移动距离：0 千米</h4>
        <button type="button" id="ajax" onclick="getlocation()">这是ajax的测试按钮</button>
    </body>

    </html>