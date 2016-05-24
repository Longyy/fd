<?php
include("httpConfig.php");
include("connection.php");

// 自动登录
$sOpenID = $_REQUEST['openId'];
if(!empty($sOpenID)) {
    // 查询是否注册过
    $sql = "SELECT * FROM `cms_wechat_user` WHERE open_id='{$sOpenID}' limit 1";
    $result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
    $result = $result->fetch_assoc();
    if(!empty($result)) {
        $sMobile = $result['user_name'];
        // 查询是否是转诊医生
        $sql = "select * from cms_transfer_doctor where phone = '{$sMobile}' limit 1";
        $result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
        $result = $result->fetch_assoc();
        if(!empty($result)) {
            header('Location: transferConsultation.php?doctor_id='.$result['id']);
            exit;
        }
    }
} else {

}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>登录</title>
    <link rel="stylesheet" href="css/style.css">
    <script language="JavaScript" src="js/jquery-1.11.0.min.js" type="text/javascript"></script>
</head>
<body>
<div class="con">
    <div class="login-top">
        <p class="text-center">欢迎使用红胎记咨询</p>
        <p class="text-right">Welcome</p>
    </div>
    <div class="ect-padding ect-margin-top">
        <table width="100%">
            <tr>
                <td>
                    <input type="text" class="usertxt" placeholder="请输入手机号码" id="userName" name="userName">

                </td>
            </tr>
            <tr>
                <td>
                    <input type="password" class="passtxt ect-margin-top" placeholder="请输入密码" id="password" name="password">
                </td>
            </tr>
            <tr>
                <td>
                    <p class="ect-padding-tb"><input id="ckService" type="checkbox" checked="checked">同意红胎记咨询服务条款内容</p>
                </td>
            </tr>
            <tr>
                <td>
                    <input type="button" id="btnSubmit" class="ect-bg btn ect-btn-info btn-info" value="登录">
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

        var userName = $.trim($("#userName").val());
        var password = $.trim($("#password").val())

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
                    alert("登陆成功")
                    if (data.roleId == 1) {
                        window.location.href = '<?php echo( $zixunchuli) ?>';
                    }
                    else if (data.roleId == 4) {
                        window.location.href = '<?php echo( $messUrl) ?>';
                    }
                    else if (data.roleId == 3) {
                        window.location.href = '<?php echo( $scanForm) ?>';
                    }
                    else if (data.roleId == 5) {
                        window.location.href = '<?php echo( $zhiliaojishi) ?>';
                    }
//                    WeixinJSBridge.invoke('closeWindow', {}, function (res) {
//
//                    });
                }


            }
        })
    })
</script>