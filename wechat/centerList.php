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
$sql = "SELECT * FROM`cms_medical_center`";
$centerResult = mysqli_query($connection, $sql) or die(mysqli_error($connection));

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>医疗中心</title>
    <link rel="stylesheet" href="css/style.css">
    <script language="JavaScript" src="js/jquery-1.11.0.min.js" type="text/javascript"></script>
</head>
<body>
<div class="con">
    <div class="ect-bg">
        <header class="ect-header ect-margin-tb ect-margin-lr text-center icon-write">
            <a href="javascript:history.go(-1)" class="pull-left ect-icon ect-icon1 ect-icon-history">&nbsp;</a> <span>医疗中心</span>
            <a href="javascript:;" class="pull-right ect-icon ect-icon1 ect-icon-mune"></a></header>
    </div>

    <!--    <div class="input-search ect-padding">-->
    <!--		<span>-->
    <!--        <input type="text" id="keywordBox" placeholder="快速查找医生" name="keywords">-->
    <!--        </span>-->
    <!--    </div>-->
    <!--    <div class="zjjs-tab text-center">-->
    <!--        <ul>-->
    <!--            <li class="cur ect-color">医疗中心</li>-->
    <!--            <li>所有医生</li>-->
    <!--        </ul>-->
    <!--    </div>-->
    <div class="ect-pro-list doctor-intro">
        <ul>
            <?php
            while ($row = $centerResult->fetch_assoc()) {
                ?>
                <a href="doctorList.php?id=<?php echo($row['id']) ?>">
                    <li>

                        <i class="pull-left user-img"><img src="images/1.jpg"/></i>
                        <dl>
                            <dt>
                            <h4 class="title"><?php echo $row['name'] ?></h4>
                            </dt>
                            <dd><?php $row['address'] ?></dd>
                        </dl>
                        <i class="pull-right fa fa-angle-right">&nbsp;</i>

                    </li>
                </a>
            <?php
            }
            ?>
        </ul>
    </div>

</div>

</body>
</html>

