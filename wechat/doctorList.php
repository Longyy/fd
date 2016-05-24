<?php
include("connection.php");
//include("httpConfig.php");
//require_once "jssdk.php";
//$jssdk = new JSSDK();
//$signPackage = $jssdk->GetSignPackage();
//
//$accessToken = array
//(
//    'appid'      => $signPackage["appId"],
//    'secret'     => $signPackage['appSecret'],
//    'code'       => $_REQUEST["code"],
//    'grant_type' => 'authorization_code'
//);
////获取openid
//$accessTokenHttp = "https://api.weixin.qq.com/sns/oauth2/access_token?" . http_build_query($accessToken);
//include("httpGet.php");
//$result = json_decode(httpGet($accessTokenHttp), true);

//获取医疗中心
$sql = "SELECT * FROM`cms_medical_center` where id=".$_GET['id'];
$centerResult = mysqli_query($connection, $sql) or die(mysqli_error($connection));
$centerResult = $centerResult->fetch_assoc();
//获取医生列表
$sql = "SELECT t.doctor_id,t1.name as doctor_name,t1.level_name,img FROM `cms_medical_center_doctor` t left join `cms_doctor` t1 on t.doctor_id=t1.id  WHERE  t.medical_center_id=" . $_GET['id'];
$doctorResult = mysqli_query($connection, $sql) or die(mysqli_error($connection));
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>医生列表</title>
    <link rel="stylesheet" href="css/style.css">
    <script language="JavaScript" src="js/jquery-1.11.0.min.js" type="text/javascript"></script>
</head>
<body>
<div class="con">
    <div class="ect-bg">
        <header class="ect-header ect-margin-tb ect-margin-lr text-center icon-write"> <a href="javascript:history.go(-1)" class="pull-left ect-icon ect-icon1 ect-icon-history">&nbsp;</a> <span>医生列表</span> <a href="javascript:;" class="pull-right ect-icon ect-icon1 ect-icon-mune"></a></header>
    </div>

    <div class="ect-padding-tb doctor-detail doctor-intro ect-padding-lr ect-margin-lr">
        <ul>
            <li>
                <div class="hospital-map pull-left">&nbsp;</div>
                <dl>
                    <dt>
                    <h4 class="title"><?php echo($centerResult['name'])?></h4>
                    </dt>
<!--                    <dd><font class="yy-color">医院等级：</font>三级甲等</dd>-->
<!--                    <dd><font class="yy-color">医院定点：</font>是</dd>-->
                    <dd><font class="yy-color">联系电话：</font><?php echo($centerResult['phone'])?></dd>
                    <dd><font class="yy-color">医院地址：</font><?php echo($centerResult['address'])?></dd>
                </dl>
            </li>
        </ul>
        <p class="clear">
            <a href="map.php?id=<?php echo($_GET['id'])?>">
                <font class="ect-colorb">查看地图</font>
            </a>
        </p>
    </div>

    <div class="ect-wrapper text-center">
        <div>
<!--            <a href="">时间<img src="images/icon_03.png"/></a>-->
<!--            <a href="">职称<img src="images/icon_03.png"/></a>-->
<!--            <a href="">xx<img src="images/icon_03.png"/></a>-->
        </div>
    </div>
    <div class="ect-pro-list doctor-intro">
        <ul id="doctor-list">
            <?php
            while ($row = $doctorResult->fetch_assoc()) {
            ?>
            <li>
                <i class="pull-left user-img"><img src="<?php if($row['img']){ echo('../upload/'.$row['img']);}else{echo 'images/1.jpg';}?>"></i>
                <dl>
                    <dt>
                    <h4 class="title">
                        <?php echo($row['doctor_name'])?><span class="zrys"><?php echo($row['titleName'])?></span>
                        <a href="order.php" class="pull-right pub-blue appoint ect-colorf">预约</a>
                    </h4>

                    </dt>
                    <dd>主任医师，原上海皮肤病医院主任，上海大学人类学研究机构...</dd>
                </dl>
            </li>
            <?php
            }
            ?>

        </ul>
    </div>

</div>

</body>
</html>

