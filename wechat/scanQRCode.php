<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no"/>
    <title></title>
    <script language="JavaScript" src="js/jquery-1.11.0.min.js" type="text/javascript"></script>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js" type="text/javascript" charset="utf-8"></script>
</head>
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
// 扫一扫权限验证
$sql = "SELECT * FROM `cms_wechat_user` WHERE (role_id =5 or role_id=1) and open_id ='".$openId."'";
$result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
$row = $result->fetch_all();
if (count($row) == 0) {
   echo('<div style="font-size: 50px;">您无法使用此功能</div>');
    exit();
}

?>

<body>
<!--<input type="button" id="scanQRCode1" value="测试">-->
正在打开扫描功能，请稍后。。。。。。
</body>
</html>
<script>
    wx.config({
        debug: false,
        appId: '<?php echo $signPackage["appId"];?>',
        timestamp: <?php echo $signPackage["timestamp"];?>,
        nonceStr: '<?php echo $signPackage["nonceStr"];?>',
        signature: '<?php echo $signPackage["signature"];?>',
        jsApiList: [
            // 所有要调用的 API 都要加到这个列表中
            'checkJsApi',
            'onMenuShareTimeline',
            'onMenuShareAppMessage',
            'onMenuShareQQ',
            'onMenuShareWeibo',
            'hideMenuItems',
            'showMenuItems',
            'hideAllNonBaseMenuItem',
            'showAllNonBaseMenuItem',
            'translateVoice',
            'startRecord',
            'stopRecord',
            'onRecordEnd',
            'playVoice',
            'pauseVoice',
            'stopVoice',
            'uploadVoice',
            'downloadVoice',
            'chooseImage',
            'previewImage',
            'uploadImage',
            'downloadImage',
            'getNetworkType',
            'openLocation',
            'getLocation',
            'hideOptionMenu',
            'showOptionMenu',
            'closeWindow',
            'scanQRCode',
            'chooseWXPay',
            'openProductSpecificView',
            'addCard',
            'chooseCard',
            'openCard'
        ]
    });
    wx.ready(function () {
        // 在这里调用 API
        wx.scanQRCode({
            needResult: 1,
            desc: 'scanQRCode desc',
            success: function (result) {
                var  code =result.resultStr.split(',')[1];
//                alert(code);
<!--    alert('--><?php //echo( $openId) ?><!--');-->
//                alert(result.resultStr.split(',')[1])
                $.ajax({
                    type: "POST",
                    url: '<?php echo( $postUrl) ?>',
                    data: {'postType': 'scanBarcode', 'code': code,'openId':'<?php echo( $openId) ?>'},
                    success: function (result) {
                        alert(result.rt);
                        WeixinJSBridge.invoke('closeWindow',{},function(res){

                            //alert(res.err_msg);

                        });
                    }
                })
//                alert(JSON.stringify(res));
//                window.location.href="http://www.baidu.com";
            }
        });

//        document.querySelector('#scanQRCode1').onclick = function () {
//            wx.scanQRCode({
//                needResult: 0,
//                desc: 'scanQRCode desc',
//                success: function (res) {
//                }
//            });
//        };

    });


</script>