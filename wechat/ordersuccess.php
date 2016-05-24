<?php
include("httpConfig.php");
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
include("connection.php");
$openId=$result["openid"];
// 获取医生信息
$doctorId = $_GET['doctor_id'];
$conId = $_GET['con_id'];
$sql = "select a.name, d.name as `center_name`, d.address as `center_name`, b.name as `doctor_name` 
from cms_consultation a
left join cms_doctor b on a.doctor_id = b.id
left join cms_medical_center_doctor c on b.id = c.doctor_id
left join cms_medical_center d on c.medical_center_id=d.id
where a.id ={$doctorId}";

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title>患者预约成功</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="con">
	<div class="ect-bg">
		<header class="ect-header ect-margin-tb ect-margin-lr text-center icon-write"> <a href="javascript:history.go(-1)" class="pull-left ect-icon ect-icon1 ect-icon-history">&nbsp;</a> <span>患者</span> <a href="javascript:;" class="pull-right ect-icon ect-icon1 ect-icon-mune"></a></header>
	</div>
	<div class="invited-hospital ect-padding-lr">
	
		<div class="invited-txt">
			<p>尊敬的张三：</p>
			<p style="text-indent:2em;">您已预约<font color="#4fc1e9">上海黄山医院张三医生于2015年11月7日上午</font></p>
			<p>（上午11点钟前或下午4点前）到院挂号就诊。</p>
		</div>
		<div class="invited-note">
			<p>温馨提醒：</p>
			<p>1.地址：<a href=""><font class="color-green">上海市中山南路969号第一医院皮肤科</font></a>&nbsp;<img src="images/map.gif"/></p>
			
			<p>2.<font class="color-green">电话咨询</font></p>
		</div>
		
		<p class="ect-padding text-center ect-margin-top">
			<input type="button" value="确定" class="btn_blue treat-btn ect-margin-lr">
			<input type="button" value="取消" class="btn_grey treat-btn ect-margin-lr">
		</p>
	</div>
	<br/>
	<br/>
	<br/>

	
	
</div>
</body>
</html>


