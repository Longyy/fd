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
//是否登录注册过，没有进行跳转
$openId=$result["openid"];
$sql = "SELECT * FROM `cms_wechat_user` WHERE role_id=5 and open_id='" . $openId."'";
$result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
$rowCount=$result->num_rows;
if($rowCount==0){
    $url=$pageUrl."login.php?roleType=5&openId=".$openId;
    echo "<script>window.location.href='".$url."'</script>";
}

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title>治疗技师</title>
<link rel="stylesheet" href="style.css">
</head>
<script type="text/javascript" language="javascript">
   var ha1;
   var ha2;
   var ha3;
   var ha4;
   var ha5;
   var ha6;
   var ha7;
   var ha8;
   var ha9;
   function he1(){
	     ha1=document.getElementById("l1");
         ha2=document.getElementById("l2");
     
		 ha4=document.getElementById("hq1");
		 ha5=document.getElementById("hq2");
		
	   ha1.style.display="block";
	   ha2.style.display="none";

	   ha4.style.color="#fff";
	   ha5.style.color="#555";
	
	   ha4.style.background="#50c1e9";
	   ha5.style.background="#fff";

	   }
   function he2(){
	      ha1=document.getElementById("l1");
          ha2=document.getElementById("l2");
     
		  ha4=document.getElementById("hq1");
		  ha5=document.getElementById("hq2");
		;
	   ha1.style.display="none";
	   ha2.style.display="block";
	
	   ha4.style.color="#555";
	   ha5.style.color="#fff";
	
	   ha5.style.background="#50c1e9";
	   ha4.style.background="#fff";
	
	   }
       
</script>
<style>
#table1{
	background-color:#ffffff;
	border-top:1px solid #CCC;
	}
#table{
	border:none;
	line-height:300%;
	
	}
</style>
<body>
<div class="con">
	<div class="ect-bg">
		<header class="ect-header ect-margin-tb ect-margin-lr text-center icon-write"> <a href="javascript:history.go(-1)" class="pull-left ect-icon ect-icon1 ect-icon-history">&nbsp;</a> <span>治疗技师</span> <a href="javascript:;" class="pull-right ect-icon ect-icon1 ect-icon-mune"></a></header>
	</div>
	
	<div class="ect-padding-tb zz-doctor doctor-intro ect-padding-lr ect-margin-lr">
		<ul>
			<li>
				<div class="zz-doctor-img pull-left" style="border-radius:100%;">&nbsp;</div>
				<dl>
				  <dd>张晓晓<a class="pull-right pub-grey" href="">刷新</a></dd>
				  <dd>主任医师</dd>
				  <dd>上海光动力技术医院<a class="pull-right pub-blue ect-colorf" href="">退出</a></dd>
				</dl>
			</li>
		</ul>
		
	</div>
    <div style="margin-bottom:20px" align="center"><li><input type="search" style="width:35%;" placeholder="请输入姓名或手机号">&nbsp;<input type="button" value="查询" style="width:10%">&nbsp;<input type="button" value="扫一扫" style="width:25%; margin-left:10%"></li></div>
	<section class="pub-tab" id="tab">
		<ul class="nav nav-tabs text-center">
			<li class="col-xs-4" style="width:50%; border:none;"><a data-toggle="tab" role="tab" href="#one" onClick="he1()" id="hq1" style="background:#50c1e9; color:#fff">转诊成功</a></li>
			<li class="col-xs-4" style="width:50%; border:none;"><a data-toggle="tab" role="tab" href="#two" onClick="he2()" id="hq2">提交列表</a></li>
		 </ul>
		 <div class="tab_content">
             <div align="center">
                 <p style="margin-bottom:20px;"><span style="font-size: 0.7rem">选择时间段:&nbsp;</span><span><input type="date" style="width: 25%"></span><span>--</span><span><input type="date" style="width: 25%"></span><span>&nbsp;<input type="button" value="查询" style="width:12%"></span></p>
             </div>
		      <ul id="table1" class="nav nav-tabs text-center">
                <li id="table" style="width:25%">患者姓名</li>
                <li id="table" style="width:25%">心电图</li>
                <li id="table" style="width:25%">肝功能</li>
                <li id="table" style="width:25%">处理</li>
              </ul>
              <ul id="table1" class="nav nav-tabs text-center">
                <li id="table" style="width:25%">张三</li>
                <li id="table" style="width:25%">正常</li>
                <li id="table" style="width:25%">不正常</li>
                <li id="table" style="width:25%"><select name=""><option>适合治疗</option><option>不适合治疗</option></select></li>
                
              </ul>
             <ul id="table1" class="nav nav-tabs text-center">
                <li id="table" style="width:25%">张三</li>
                <li id="table" style="width:25%">正常</li>
                <li id="table" style="width:25%">不正常</li>
                <li id="table" style="width:25%"><select name=""><option>适合治疗</option><option>不适合治疗</option></select></li>
                
              </ul>
              <ul id="table1" class="nav nav-tabs text-center">
                <li id="table" style="width:25%">张三</li>
                <li id="table" style="width:25%">正常</li>
                <li id="table" style="width:25%">不正常</li>
                <li id="table" style="width:25%"><select name=""><option>适合治疗</option><option>不适合治疗</option></select></li>
                
              </ul>
              <ul id="table1" class="nav nav-tabs text-center"  style="border-bottom:1px solid #CCC; ">
                <li id="table" style="width:25%">张三</li>
                <li id="table" style="width:25%">正常</li>
                <li id="table" style="width:25%">不正常</li>
                <li id="table" style="width:25%"><select name=""><option>适合治疗</option><option>不适合治疗</option></select></li>
                
              </ul>
              
			</div>
            <div class="rl-txt" id="l2" style="display:none;  padding:0 0;">
             <div align="center">
            <p style="margin-bottom:20px;"><span>选择时间段:&nbsp;</span><span><input type="date"></span><span>--</span><span><input type="date"></span><span>&nbsp;<input type="button" value="查询" style="width:12%"></span></p>
            </div>
			 <ul id="table1" class="nav nav-tabs text-center">
                <li id="table" style="width:25%">患者姓名</li>
                <li id="table" style="width:25%">心电图</li>
                <li id="table" style="width:25%">肝功能</li>
                <li id="table" style="width:25%">处理</li>
              </ul>
              <ul id="table1" class="nav nav-tabs text-center">
                <li id="table" style="width:25%">张三</li>
                <li id="table" style="width:25%">正常</li>
                <li id="table" style="width:25%">不正常</li>
                <li id="table" style="width:25%">无法治疗</li>
                
              </ul>
             <ul id="table1" class="nav nav-tabs text-center">
                <li id="table" style="width:25%">张三</li>
                <li id="table" style="width:25%">正常</li>
                <li id="table" style="width:25%">不正常</li>
                <li id="table" style="width:25%">无法治疗</li>
                
              </ul>
              <ul id="table1" class="nav nav-tabs text-center">
                <li id="table" style="width:25%">张三</li>
                <li id="table" style="width:25%">正常</li>
                <li id="table" style="width:25%">不正常</li>
                <li id="table" style="width:25%">无法治疗</li>
                
              </ul>
              <ul id="table1" class="nav nav-tabs text-center" style="border-bottom:1px solid #CCC; ">
                <li id="table" style="width:25%">张三</li>
                <li id="table" style="width:25%">正常</li>
                <li id="table" style="width:25%">不正常</li>
                <li id="table" style="width:25%">无法治疗</li>
                
              </ul>
			</div>
		 </div>
	</section>
</div>

</body>
</html>

 