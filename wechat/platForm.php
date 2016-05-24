<?php
include("httpConfig.php");
require_once "jssdk.php";
$jssdk = new JSSDK();
$signPackage = $jssdk->GetSignPackage();
include("httpGet.php");
$accessToken = array
(
    'appid'      => $signPackage["appId"],
    'secret'     => $signPackage['appSecret'],
    'code'       => $_REQUEST["code"],
    'grant_type' => 'authorization_code'
);
//请求获取授权码access_token
$accessTokenHttp = "https://api.weixin.qq.com/sns/oauth2/access_token?" . http_build_query($accessToken);

//var_dump($accessTokenHttp);
$result = httpGet($accessTokenHttp);

$result = json_decode($result, true);


include("connection.php");
//是否登录注册过，没有进行跳转
$openId = $result["openid"];
$sql = "SELECT * FROM `cms_wechat_user` WHERE open_id='" . $openId . "'";

$result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
$userResult = $result->fetch_assoc();
$rowCount = $result->num_rows;
//注册页面
if ($rowCount == 0) {
    $url = $pageUrl . "login.php?openId=" . $openId;
    echo "<script>window.location.href='" . $url . "'</script>";
}
elseif($userResult["role_id"]==1){
//    echo("医生后台");
    echo "<script>window.location.href='" . $consultationListUrl . "'</script>";
}
elseif($userResult["role_id"]==3){
//    echo("转诊医生后台");
    echo "<script>window.location.href='" . $tran_consultation . "'</script>";
}
elseif($userResult["role_id"]==4){
//    echo("患者后台");
    echo "<script>window.location.href='" . $messUrl . "'</script>";
}