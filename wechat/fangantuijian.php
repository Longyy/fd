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
$result = httpGet($accessTokenHttp);
$result = json_decode($result, true);
include("connection.php");
//是否登录注册过，没有进行跳转到咨询页
$openId = $result["openid"];

// 根据患者的id查询反馈的信息
$id = $_GET['id'];
$sql = "SELECT * FROM cms_proposal WHERE con_id=".$id;
$paitenResult = mysqli_query($connection,$sql) or die(mysqli_error($connection));
$paitenResult = $paitenResult->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title>方案推荐</title>
<link rel="stylesheet" href="style.css">
<script>
window.onload =function(){
	var o=document.getElementById("o").value;
	var yao=document.getElementById("yaodian");
	if(o=="光动力"){
		yao.style.display="block";
		}else{
		yao.style.display="none";	
			}
	
	}
</script>
</head>

<body>
<div class="con">
	<div class="ect-bg">
    
		<header class="ect-header ect-margin-tb ect-margin-lr text-center icon-write"> <a href="javascript:history.go(-1)" class="pull-left ect-icon ect-icon1 ect-icon-history">&nbsp;</a> <span>方案推荐</span> <a href="javascript:;" class="pull-right ect-icon ect-icon1 ect-icon-mune"></a></header>
	</div>
      <p style="font-size:26px; color:#50c1e9; height:60px; margin-top:20px;">消息提示</p>
      <p><span style="margin-left:20%"><span style="color:#0000c6">xx医院</span>的<span style="color:#0000c6">xx</span>医生于<span style="color:#0000c6">x年x月x日</span>给出咨询建议</span><p>
      <p style="font-size:26px; color:#50c1e9; height:60px; margin-top:20px;"></p>
      <p><textarea name="" cols="36" rows="5" style="margin-left:20%" placeholder="随便写的" disabled><?php echo($paitenResult["suggestion"])?></textarea></p>
      <p style="font-size:26px; color:#50c1e9; height:60px; margin-top:20px;"</p>
      <p><input type="text" id="o" value="<?php echo($paitenResult['recommend'])?>" disabled  style="margin-left:20%"/></p>
      <p style="font-size:18px; margin-left:10%; margin-top:5px;">其他</p> 
      <p><textarea name="" cols="36" rows="5" style="margin-left:20%; margin-top:5px;" placeholder="随便写的" disabled><?php echo($paitenResult["recommendother"])?></textarea></p>
      <p>
                      <ul style="width:100%; margin-bottom:20px; margin-top:20px;">
<li style="width:20%; height:30px; background:#50c1e9;float:left; margin-left:20%; margin-bottom:20px; color:#FFF; text-align:center; position:absolute; line-height: 200%" id="yaodian"><a href="<?php echo($orderPay)?>" style="color:#fff; line-height:150%">去药店</a></li>
<li style="width:20%; height:30px; background:#50c1e9;float:left; margin-left:56%; margin-bottom:20px; color:#FFF; text-align:center; line-height: 200%"><a href="#" style="color:#fff; line-height:150%">取消</a></li>
</ul>
    </p> 
</div>

</body>
</html>

 