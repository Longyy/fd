<?php
include("httpConfig.php");
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
//$result = httpGet($accessTokenHttp);
//$result = json_decode($result, true);
//include("connection.php");
////是否登录注册过，没有进行跳转到求诊页
//$openId = $result["openid"];
//$sql = "SELECT * FROM `cms_wechat_user` WHERE role_id in (1,5) and open_id='" . $openId . "'";
//$result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
//$rowCount = $result->num_rows;
//if ($rowCount == 0) {
//    $url = $pageUrl . "login.php?openId=" . $openId;
//    echo "<script>window.location.href='" . $url . "'</script>";
//}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title>预约失败</title>
<link rel="stylesheet" href="style.css">
</head>

<body style="font-family:微软雅黑;">
<div class="con">
	<div class="ect-bg">
		<header class="ect-header ect-margin-tb ect-margin-lr text-center icon-write"> <a href="javascript:history.go(-1)" class="pull-left ect-icon ect-icon1 ect-icon-history">&nbsp;</a> <span>预约反馈</span> <a href="javascript:;" class="pull-right ect-icon ect-icon1 ect-icon-mune"></a></header>
	</div>
<div>
<p style="font-size:26px; color:#50c1e9; height:60px; margin-top:20px;">预约状态</p>
<p style="color:#53c3af">您所预约的:</p>
<p style="margin-top:20px; margin-left:17%">医院:&nbsp; <u>上海市第九人民医院治疗中心</u> </p>
<p style="margin-top:20px; margin-left:17%">医生:&nbsp; <u>张三</u> </p>
<p style="margin-top:20px; margin-left:17%">时间:&nbsp; <u>x年x月x日x时x分</u></p>
<p style="color:#53c3af; margin-top:20px;">预约失败原因:</p>
<p style="margin-top:20px; margin-left:17%">医生因外出学习或临时会议等原因取消预约,请您另行预约就诊</p>
<li style="height:40px; width:16%; background:#50c1e9; margin-top:20px; text-align:center; line-height:200%; margin-left:17%"><a  href="<?php echo( $pageUrl)?>doctorTime.php?doctorId=<?php echo($result['doctor_id'])?>&id=<?php echo($_GET['id'])?>" style="color:#fff">重新预约</a></li>
<li style="height:40px; width:16%; background:#50c1e9; margin-top:20px; text-align:center; line-height:200%; margin-left:57%; margin-top:-40px"><a href="#" style="color:#fff">退出</a></li>
</div>	
</div>

</body>
</html>

 