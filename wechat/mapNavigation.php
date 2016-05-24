<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/4/27
 * Time: 13:25
 * 百度地图导航(有步行  自驾 公交)
 */
define("MAPAK","rbwqbtGKVBKo1WwPlkpfGxCc");



?>



<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <style type="text/css">
        body, html,#l-map {width: 100%;height: 100%;overflow: hidden;margin:0;}
    </style>
    <script type="text/javascript" src="http://api.map.baidu.com/api?type=quick&ak=rbwqbtGKVBKo1WwPlkpfGxCc&v=1.0"></script>
</head>
<body>

<div id="l-map"></div>

</body>
</html>

<script type="text/javascript">
    var map = new BMap.Map("l-map");


    wx.getLocation({
        success: function (res) {
            var latitude = res.latitude; // 纬度，浮点数，范围为90 ~ -90
            var longitude = res.longitude; // 经度，浮点数，范围为180 ~ -180。
            var speed = res.speed; // 速度，以米/每秒计
            var accuracy = res.accuracy; // 位置精度
            map.centerAndZoom(new BMap.Point(latitude, longitude), 14);
        }
    });
</script>