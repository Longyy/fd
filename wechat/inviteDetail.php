<?php
include("httpConfig.php");
include("connection.php");
//是否登录注册过，没有进行跳转
$sql = "select  t1.name,t2.name as doctor_name,t4.name as center_name,t4.address,t4.id as center_id,t1.doctor_id from cms_consultation t1 left join cms_doctor t2 on t1.doctor_id=t2.id left join cms_medical_center_doctor t3 on t2.id=t3.doctor_id left join cms_medical_center t4 on t3.medical_center_id=t4.id where t1.id=".$_GET['id'];
//var_dump($sql);
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
    <title>邀请预约</title>
    <script language="JavaScript" src="js/jquery-1.11.0.min.js" type="text/javascript"></script>
    <script language="JavaScript" src="js/bootstrap.min.js" type="text/javascript"></script>
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
</head>
<body style="padding-top: 10px; padding-left: 10px; padding-right: 10px;">
<div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading">邀请预约</div>
    <div class="panel-body">
        尊敬的<?php echo($result['name'])?>:<br/>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?echo($result['center_name'])?>治疗中心的<?php echo($result['doctor_name'])?>医生邀请您尽快预约<br/>
       <a href="<?php echo( $pageUrl)?>centerDetail.php?id=<?php echo($result['center_id'])?>"> 1.了解医疗中心</a><br/>
        <a href="<?php echo( $pageUrl)?>doctorTime.php?doctorId=<?php echo($result['doctor_id'])?>&id=<?php echo($_GET['id'])?>"> 2.门诊时间（预约）</a><br>
        <a href="<?php echo( $pageUrl)?>map.php?id=<?php echo($result['center_id'])?>">3.导航</a>
    </div>


</div>
<center>
    本服务由“光动力咨询微信平台”提供
</center>
</body>