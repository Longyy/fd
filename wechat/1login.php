<?php
include("httpConfig.php");
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title>登录</title>
 <link rel="stylesheet" href="css/styleme.css">
<script language="JavaScript" src="js/jquery-1.11.0.min.js" type="text/javascript"></script>
</head>
<body>
<div class="con">
	<div class="login-top">
		<p class="text-center">欢迎使用红胎记咨询</p>
		<p class="text-right">Welcome</p>
	</div>
	<div class="ect-padding ect-margin-top">
	<table  width="100%">
		<tr>
			<td>
			<input type="text" class="usertxt" placeholder="请输入手机号码" id="txtName" name="txtName">
		   
			</td>
		</tr>
		<tr>
			<td>
			<input type="password" class="passtxt ect-margin-top" placeholder="请输入密码" id="txtPassword" name="txtPassword">
			</td>
		</tr>
		<tr>
			<td>
            <p>&nbsp;</p>
			<p class="ect-padding-tb"><input type="radio" checked>同意红胎记咨询服务条款内容</p>
            <p>&nbsp;</p>
			</td>
		</tr>
		<tr>
			<td>
			<input type="submit" class="ect-bg btn ect-btn-info btn-info" value="登录">
			</td>
		</tr>
	</table>
	<p style="clear:both" class="ect-padding-lr ect-margin-tb text-right">
		<a href="">找回密码</a>  
	</p>
	</div>
</div>

</body>
<script>
    $("#btnSubmit").bind("click", function () {

        var userName = $.trim($("#txtName").val());
        var password = $.trim($("#txtPassword").val())

        if (userName == "") {
            alert("请输入用户名");
            return;
        }
        if (password == "") {
            alert("请输入密码");
            return;
        }
        if ($("input[type='checkbox']").is(':checked') == false) {
            alert("请同意服务条款");
            return;
        }
        var param = {
            userName: userName,
            openId: '<?php echo($_GET['openId'])?>',
            password: password,
            postType: "login"

        }
//        alert(JSON.stringify(param));
//        return;
        $.ajax({
            type: "POST",
            url: '<?php echo( $postUrl) ?>',
            data: param,
            success: function (data) {
                if (data.st != 1) {
                    alert(data.rt);
                }
                else {
                    alert("注册成功")
                    if (data.roleId == 1) {
                        window.location.href = '<?php echo( $consultationListUrl) ?>';
                    }
                    else if (data.roleId == 4) {
                        window.location.href = '<?php echo( $messUrl) ?>';
                    }
                    else if (data.roleId == 3) {
                        window.location.href = '<?php echo( $tran_consultation) ?>';
                    }
//                    WeixinJSBridge.invoke('closeWindow', {}, function (res) {
//
//                    });
                }


            }
        })
    })
</script>
</html>


