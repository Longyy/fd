<?php
include("httpConfig.php");
require_once "jssdk.php";
$jssdk = new JSSDK();
$signPackage = $jssdk->GetSignPackage();

$accessToken = array
(
    'appid'      => $signPackage["appId"],
    'secret'     => $signPackage['appSecret'],
    'code'       => $_REQUEST["code"],
    'grant_type' => 'authorization_code'
);
//获取openid
$accessTokenHttp = "https://api.weixin.qq.com/sns/oauth2/access_token?" . http_build_query($accessToken);
include("httpGet.php");
$result = json_decode(httpGet($accessTokenHttp), true);
?>
<!DOCTYPE html>
<html>
<head>
    <title>转诊医生注册</title>
    <meta name="viewport"
          content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no"/>
    <script language="JavaScript" src="js/jquery-1.11.0.min.js" type="text/javascript"></script>
    <script language="JavaScript" src="js/bootstrap.min.js" type="text/javascript"></script>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js" type="text/javascript" charset="utf-8"></script>
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
</head>
<body style="padding-top: 10px">
<center>欢迎使用“红胎记咨询微信平台”服务</center>
<form>
    <div class="form-group">
        <label for="exampleInputPassword1">用户名</label>
        <input type="text" class="form-control" id="txtName" placeholder="请输入用户名">
    </div>
    <div class="form-group">
        <label for="exampleInputPassword1">密码</label>
        <input type="password" class="form-control" id="txtPassword" placeholder="请输入密码">
    </div>
    <div class="form-group">
        <input id="ckService" type="checkbox">&nbsp;同意“红胎记咨询”的服务条款
    </div>


</form>
<center>
    <button type="button" id="btnSubmit" style="width: 200px;" class="btn btn-primary">确定</button>
        <br/>
        <center>
            本服务由“红胎记咨询微信平台”提供
        </center>
</center>
</body>
</html>
<script>
    var openId = '<?php echo $result["openid"];?>'
    $("#btnSubmit").bind("click", function () {
        if ($.trim($("#txtName").val()) == "") {
            alert("请输入用户名");
            return;
        }
        if ($.trim($("#txtPassword").val()) == "") {
            alert("请输入密码");
            return;
        }
        if($("input[type='checkbox']").is(':checked')==false){
            alert("请同意服务条款");
            return;
        }
        $.ajax({
            type: "POST",
            url: '<?php echo( $postUrl) ?>',
            data: {userName: $("#txtName").val(), openId: openId,
                password: $("#txtPassword").val(), postType: "login",
                roleType:'3'},
            success: function (data) {
                if(data.st!=1){
                    alert(data.rt);
                }
                else{
                    alert("注册成功")
                    WeixinJSBridge.invoke('closeWindow', {}, function (res) {

                    });
                }


            }
        })
    })
</script>