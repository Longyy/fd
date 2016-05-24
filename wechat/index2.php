<?php
require_once "jssdk.php";
$jssdk = new JSSDK("wx7f07390e9f3e50b7", "83c1b920dd1457d88950ac13415c3675");
//$jssdk = new JSSDK("wx1a3f4816d206f7cc", "27f6a58f927d79a71ef647d84f6af001");
$signPackage = $jssdk->GetSignPackage();
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>复旦张江</title>
    <link href="css/blue.css" rel="stylesheet" type="text/css">
    <script language="JavaScript" src="js/jquery-1.11.0.min.js" type="text/javascript"></script>
    <script language="JavaScript" src="js/multi-step-form.js" type="text/javascript"></script>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <script language="javascript">
        $(function(){
            multi_step_form({
                container: '.sections',
                section: '.section'
            });
        });
        wx.config({
            debug: true,
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
            wx.getLocation({
                success: function (res) {
                    alert(JSON.stringify(res));
                    var latitude = res.latitude; // 纬度，浮点数，范围为90 ~ -90
                    var longitude = res.longitude; // 经度，浮点数，范围为180 ~ -180。
                    var speed = res.speed; // 速度，以米/每秒计
                    var accuracy = res.accuracy; // 位置精度
                }
            });

        });
    </script>
    <script src="js/placeholders.min.js"></script>

    <!-- /********************/  -->

</head>

<body>
<div id="wrapper">
    <div class="container">
        <!-- BEGIN FORM -->
        <div class="sections">
            <form>
                <div class="section-holder">
                    <h2 class="form-title">我要求诊</h2>
                    <h2 class="section-title"></h2>
                    <div class="section current" section="个人信息">
                        <input name="post_type" class="post-type" type="hidden" value="Email" />
                        <label><input type="text" id="txtName" placeholder="姓名" data="required" /></label>
                        <label>
                            <select placeholder="性别" id="txtSex" data="required">
                                <option value="">--性别--</option>
                                <option value="1">男</option>
                                <option value="0">女</option>
                            </select>
                        </label>
                        <label><input type="text" placeholder="年龄" id="txtAge" data="required" /></label>
                        <label>
                            <select placeholder="城市" id="dlCity" data="required">
                                <option value="">--城市--</option>
                                <option value="BJ">北京</option>
                                <option value="SH">上海</option>
                            </select>
                        </label>
                        <label><input type="text" id="txtPhone" placeholder="手机号码" data="required" /></label>
                        <label><input type="text" id="txtIdCard" placeholder="身份证号码" data="required" /></label>
                    </div><!-- close section -->
                    <div class="section" section="病情">
                        <label><input id="txtOrderCode" type="text" placeholder="转诊号/预约号" data="required" /></label>
                        <label><textarea id="txtDiagnose" placeholder="临床诊断" data="required" cols="20" rows="3" ></textarea></label>
                        <label><textarea id="txtDescription" placeholder="病情描述" data="required" cols="40" rows="5" ></textarea></label>
                        <label><textarea id="txtBeforeTreat" placeholder="曾经过往治疗史" data="required" cols="40" rows="5" ></textarea></label>
                        <label><textarea id="txtRequire" placeholder="就诊要求" data="required" cols="40" rows="5" ></textarea></label>
                        <label><input id="cbIsAccept" type="checkbox" value="Yes" placeholder="接受《复旦张江天使之吻》服务条款" data="required" /> 接受《复旦张江天使之吻》服务条款</label>
                    </div><!-- close section -->
                    <div class="section final" section="求诊信息">
                    </div><!-- close section -->
                    <div id="preloader" class="none"><img src="images/15.gif" /></div>
                    <div class="clear"></div>
                </div>
                <!-- BEGIN NAVIGATION -->
                <div class="section-holder">
                    <div class="back">上一步</div>
                    <div class="next">下一步</div>
                    <div class="clear"></div>
                </div>
                <!-- END NAVIGATION -->
            </form>
        </div>
        <!-- BEGIN PROGRESS BAR -->
        <div class="progress-bar">
            <div class="progress"></div>
            <div class="progress-text">0%</div>
        </div>
        <!-- END PROGRESS BAR -->
        <!-- END FORM -->
    </div>
</div>
</body>
</html>

