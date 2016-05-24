<?php
/**
 * Created by PhpStorm.
 * User: Kevin
 * Date: 15-4-1
 * Time: 下午5:17
 */
require_once "jssdk.php";
$jssdk       = new JSSDK();
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
var_dump($result["openid"]);
var_dump($signPackage["accessToken"]);

$param  = array("touser" => array($result['openid']), "msgtype" => "text", "text" => array("content" => "你好
"));
$url    = "https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token=" . $signPackage["accessToken"];
$result = httpGet($url, $param, "post");
var_dump($result);
?>