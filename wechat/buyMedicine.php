<?php
//include("httpConfig.php");
//require_once "jssdk.php";
//$jssdk = new JSSDK();
//$signPackage = $jssdk->GetSignPackage();
//include("httpGet.php");
//$accessToken = array
//(
//    'appid'      => $signPackage["appId"],
//    'secret'     => $signPackage['appSecret'],
//    'code'       => $_REQUEST["code"],
//    'grant_type' => 'authorization_code'
//);
////请求获取授权码access_token
//$accessTokenHttp = "https://api.weixin.qq.com/sns/oauth2/access_token?" . http_build_query($accessToken);
//
////var_dump($accessTokenHttp);
//$result = httpGet($accessTokenHttp);
//
//$result = json_decode($result, true);


//include("connection.php");
////是否登录注册过，没有进行跳转
//$openId = $result["openid"];
//$sql = "SELECT * FROM `cms_wechat_user` WHERE role_id=4 and open_id='" . $openId . "'";
//$result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
//
//$rowCount = $result->num_rows;
//if ($rowCount == 0) {
//    $url = $pageUrl . "login.php?roleType=4&openId=" . $openId;
//    echo "<script>window.location.href='" . $url . "'</script>";
//}
//$result = $result->fetch_assoc();
//
//
//$sql = "SELECT t1.id,t1.order_code,t1.handle_type, t2.name as doctor_name FROM cms_consultation t1 left join cms_doctor t2 on t1.doctor_id=t2.id
// WHERE t1.open_id='" . $openId . "'";
//$consultationResult = mysqli_query($connection, $sql) or die(mysqli_error($connection));

?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no"/>
    <title>你进入的是购药页面</title>
    <script language="JavaScript" src="js/jquery-1.11.0.min.js" type="text/javascript"></script>
    <script language="JavaScript" src="js/bootstrap.min.js" type="text/javascript"></script>
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
</head>
<body style="padding-top: 10px;">
购药
</body>
</html>

