<?php
include("httpConfig.php");
include("connection.php");
//是否登录注册过，没有进行跳转
$sql = "select * from cms_medical_center where id=".$_GET['id'];
$result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
$result = $result->fetch_assoc();
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-8-24
 * Time: 下午12:04
 */
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <style type="text/css">
        body, html,#allmap {width: 100%;height: 100%;overflow: hidden;margin:0;font-family:"微软雅黑";}
    </style>
    <script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=fs0k1wtH2zNYqEjwKSfllKpG"></script>
    <title>地图展示</title>
</head>
<body>
<div id="allmap"></div>
</body>
</html>
<script type="text/javascript">

    // 百度地图API功能
    var map = new BMap.Map("allmap");  // 创建Map实例
    map.addControl(new BMap.MapTypeControl());   //添加地图类型控件
    map.centerAndZoom('<?php echo($result['city'])?>',15);      // 初始化地图,用城市名设置地图中心点
</script>