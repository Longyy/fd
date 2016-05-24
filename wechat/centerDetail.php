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
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no"/>
    <title>治疗中心简介</title>
    <script language="JavaScript" src="js/jquery-1.11.0.min.js" type="text/javascript"></script>
    <script language="JavaScript" src="js/bootstrap.min.js" type="text/javascript"></script>
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
</head>
<body style="padding-top: 10px; padding-left: 10px; padding-right: 10px;">
<div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading"><?php echo($result['name'])?>简介</div>
    <div class="panel-body">
        <?php echo($result['info'])?>
    </div>


</div>
<center>
    本服务由“光动力咨询微信平台”提供
</center>
</body>