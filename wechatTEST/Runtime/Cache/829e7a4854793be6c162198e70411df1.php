<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport"
	      content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no"/>
	<script language="javascript" type="text/javascript" src="__ROOT__/statics/js/jquery/jquery-1.11.0.min.js"></script>
	<script language="javascript" type="text/javascript" src="__ROOT__/statics/js/bootstrap.min.js"></script>
	<link href="__ROOT__/statics/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
	<title><?php echo ($title); ?></title>
</head>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js" type="text/javascript" charset="utf-8"></script>
<body>

</body>
</html>
<script type="text/javascript">

wx.config({
	debug: false,
	appId: '<?php echo ($signPackage["appId"]); ?>',
	timestamp: '<?php echo ($signPackage["timestamp"]); ?>',
	nonceStr: '<?php echo ($signPackage["nonceStr"]); ?>',
	signature: '<?php echo ($signPackage["signature"]); ?>',
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
	wx.getLocation({
		success: function (res) {
			alert(JSON.stringify(res));
			longitude = res.longitude;
			latitude = res.latitude;
		},
		cancel: function (res) {
			alert('用户拒绝授权获取地理位置');
		}
	});
});








</script>