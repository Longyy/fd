<?php
include("httpConfig.php");
include("connection.php");
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
// 获取用户的openid
$openId=$result["openid"];


?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title>咨询建议</title>
<link rel="stylesheet" href="style.css">
    <script language="JavaScript" src="js/jquery-1.11.0.min.js" type="text/javascript"></script>
<script>
function woqu(){
   var he=document.getElementById("hehehe");
   if(he.style.display=="none"){
	   he.style.display="block";
	   }else{
		   he.style.display="none";
		   }
}
function woqu1(){
   var he1=document.getElementById("hehehe1");
   if(he1.style.display=="none"){
	   he1.style.display="block";
	   }else{
		   he1.style.display="none";
		   }
}
function woqu2(){
   var he2=document.getElementById("hehehe2");
   if(he2.style.display=="none"){
	   he2.style.display="block";
	   }else{
		   he2.style.display="none";
		   }
}
function woqu3(){
   var he3=document.getElementById("hehehe3");
   if(he3.style.display=="none"){
	   he3.style.display="block";
	   }else{
		   he3.style.display="none";
		   }
}
function woqu4(){
   var he4=document.getElementById("hehehe4");
   if(he4.style.display=="none"){
	   he4.style.display="block";
	   }else{
		   he4.style.display="none";
		   }
}

</script>
</head>

<body>
<div class="con">
	<div class="ect-bg">
    
		<header class="ect-header ect-margin-tb ect-margin-lr text-center icon-write"> <a href="javascript:history.go(-1)" class="pull-left ect-icon ect-icon1 ect-icon-history">&nbsp;</a> <span>咨询建议</span> <a href="javascript:;" class="pull-right ect-icon ect-icon1 ect-icon-mune"></a></header>
	</div>
<div>
  <p style="font-size:26px; color:#50c1e9; height:60px; margin-top:20px;">咨询建议</p>
    <P style="width:80%; margin-left:20%; margin-top:20px;"><SPAN><input name="" type="checkbox" class="sugestion" value="预设文字一：你今天吃饭了吗"></SPAN>&nbsp;<span>
          <input type="button" value="预设文字一：你今天吃饭了吗"   style="width:70%" onClick="woqu2()"></span></P>
  <p><textarea name="" cols="24" rows="3" style=" display:none;  margin-left:26%;" id="hehehe2" placeholder="请输入推荐方案" disabled></textarea></p>
   <P style="width:80%; margin-left:20%; margin-top:20px;"><SPAN><input name="" type="checkbox" class="sugestion" value=" 预设文字二：你今天吃饭了吗"></SPAN>&nbsp;<span>
           <input type="button" value="预设文字二：你今天吃饭了吗"   style="width:70%" onClick="woqu3()"></span></P>
  <p><textarea name="" cols="24" rows="3" style=" display:none;  margin-left:26%;" id="hehehe3" placeholder="请输入推荐方案" disabled></textarea></p>
   <P style="width:80%; margin-left:20%; margin-top:20px;"><SPAN><input name="" type="checkbox" value="预设文字三：你今天吃饭了吗 "></SPAN>&nbsp;<span>
           <input type="button" value="预设文字三：你今天吃饭了吗" class="sugestion"  style="width:70%" onClick="woqu4()"></span></P>
  <p><textarea name="" cols="24" rows="3" style=" display:none;  margin-left:26%;" id="hehehe4" placeholder="请输入推荐方案" disabled></textarea></p>
   <P style="width:80%; margin-left:20%; margin-top:20px;"><SPAN style="color:#060;font-weight:bold" ><input name="" type="checkbox" value="" onClick="woqu()">其他：</SPAN>&nbsp;<span>
           <textarea name="" cols="24" rows="3" style=" display:none;  margin-left:7.5%; margin-top:5px;" id="hehehe" placeholder="请输入推荐方案" class="other"></textarea></span></P>
   <p style="font-size:26px; color:#50c1e9; height:60px; margin-top:40px;">推荐方案</p>   
   <P style="width:80%; margin-left:20%; margin-top:20px;"><SPAN><input name="" type="checkbox" value="" checked></SPAN>&nbsp;<span>
           <select name="recommend" style="width:70%">
            <option class="fangfan">光动力</option>
            <option class="fangfan">脉冲染料激光</option>
            </select></span></P>
   <P style="width:80%; margin-left:20%; margin-top:20px;"><SPAN style="color:#060;font-weight:bold"><input name="" type="checkbox" value="" onClick="woqu1()">其他：</SPAN>&nbsp;<span>
           <textarea name="" cols="24" rows="3" style=" display:none; margin-left:7.5%; margin-top:5px;" id="hehehe1" class="otherfan" placeholder="请输入推荐方案"></textarea></span></P>
    <p class="ect-padding text-center">
      <input type="button" class="btn_blue treat-btn ect-margin-lr" onclick="submit()" value="提交">
      <input type="button" class="btn_grey treat-btn ect-margin-lr" value="取消">
    </p>                                                                                                                                   
</div>              
</div>

</body>
</html>
<script>
    var openId = '<?php echo $result["openid"];?>'

    function submit(){
        var param = {
            suggestion: $(".sugestion").attr("checked","checked").val(),
            con_id:<?php echo($_GET['id'])?>,
            other: $(".other").val(),
            recommend:$("select").attr("value","value").val(),
            recommendother: $(".otherfan").val(),
            openId: openId,
            postType: "zixunjianyi"

        }


        $.ajax({
            type: "POST",
            url: '<?php echo( $postUrl) ?>',
            data: param,
            success: function (data) {
            alert("咨询意见提交成功");
                if(data.st==1){
                    WeixinJSBridge.invoke('closeWindow', {}, function (res) {


                    });
                }
    //                    WeixinJSBridge.invoke('closeWindow', {}, function (res) {
    //
    //                    });

            }
        })
    }
</script>

 