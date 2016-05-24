<?php
session_start();
$id=$_GET['id'];
var_dump($id);
exit();
$_SESSION['tid']=$id;
include("httpConfig.php");
require_once "jssdk.php";
$jssdk = new JSSDK();
$signPackage = $jssdk->GetSignPackage();
include("httpGet.php");
$accessToken = array
(
    'appid'      => $signPackage["appId"],
    'secret'     => $signPackage['appSecret'],
    'code'       => $_REQUEST['code'],
    'grant_type' => 'authorization_code'
);
//请求获取授权码access_token
$accessTokenHttp = "https://api.weixin.qq.com/sns/oauth2/access_token?" . http_build_query($accessToken);

//var_dump($accessTokenHttp);
$result = httpGet($accessTokenHttp);

$result = json_decode($result, true);
//var_dump($result);
//exit();



include("connection.php");
//是否登录注册过，没有进行跳转
$openId = $result["openid"];
$sql = "SELECT * FROM `cms_wechat_user` WHERE role_id=4 and open_id='" . $openId . "'";
$result = mysqli_query($connection, $sql) or die(mysqli_error($connection));

$rowCount = $result->num_rows;
if ($rowCount == 0) {
    $url = $pageUrl . "login.php?roleType=4&openId=" . $openId;
    echo "<script>window.location.href='" . $url . "'</script>";
}
$result = $result->fetch_assoc();


$sql = "SELECT t1.id,t1.order_code,t1.handle_type, t2.name as doctor_name,t1.name,t1.sex FROM cms_consultation t1 left join cms_doctor t2 on t1.doctor_id=t2.id
WHERE t1.open_id='$openId'";
//echo($sql);
$consultationResult = mysqli_query($connection, $sql) or die(mysqli_error($connection));


$sql = "SELECT t1.id,t1.order_code,t1.handle_type, t2.name as doctor_name,t1.name,t1.sex FROM cms_consultation t1 left join cms_doctor t2 on t1.doctor_id=t2.id
WHERE t1.open_id='$openId'";
$consultationNameResult = mysqli_query($connection, $sql) or die(mysqli_error($connection));
$nameResult = $consultationNameResult->fetch_assoc();

$sql = "SELECT p.*,c.name,c.phone FROM `cms_pic` as p left join `cms_consultation` as c on p.uid=c.id where p.id=".$_SESSION['tid']." and p.status=0 limit 0,20";
$picResult = mysqli_query($connection, $sql) or die(mysqli_error($connection));
//var_dump($doctorId);


?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>消息管理</title>
    <link rel="stylesheet" href="css/style.css">
    <script language="JavaScript" src="js/jquery-1.11.0.min.js" type="text/javascript"></script>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
</head>
<body>
<script>
    wx.config({
        debug: true,
        appId: '<?php echo $signPackage["appId"];?>',
        timestamp: '<?php echo $signPackage["timestamp"];?>',
        nonceStr: '<?php echo $signPackage["nonceStr"];?>',
        signature: '<?php echo $signPackage["signature"];?>',
        jsApiList: [
            'chooseImage',
            'uploadImage'
        ]
    });
    function chooseImage(obj){
        // 选择张片
        wx.chooseImage({
            count: 1, // 默认9
            sizeType: ['original', 'compressed'], // 可以指定是原图还是压缩图，默认二者都有
            sourceType: ['album', 'camera'], // 可以指定来源是相册还是相机，默认二者都有
            success: function(res) {
                var localIds = res.localIds; // 返回选定照片的本地ID列表，localId可以作为img标签的src属性显示图片
                $(obj).attr('src', localIds);
                // 上传照片
                wx.uploadImage({
                    localId: '' + localIds,
                    isShowProgressTips: 1,
                    success: function(res) {
                        serverId = res.serverId;
                        var url='../admin.php?m=Wx&a=savePic&mid='+serverId+'&acc=<?php echo $jssdk->getAccessToken()?>&uid=<?php echo $nameResult["id"]?>&uname=<?php echo $nameResult["name"]?>';
                        // alert(url);
                        $.get(url,function(data){
                            alert(data);
                        });
                        $(obj).next().val(serverId); // 把上传成功后获取的值附上
                    }
                });
            }
        });
    }
</script>
<div class="con">
    <!--    <div class="ect-bg">-->
    <!--        <header class="ect-header ect-margin-tb ect-margin-lr text-center icon-write"> <a href="javascript:history.go(-1)" class="pull-left ect-icon ect-icon1 ect-icon-history">&nbsp;</a> <span>患者</span> <a href="javascript:;" class="pull-right ect-icon ect-icon1 ect-icon-mune"></a></header>-->
    <!--    </div>-->

    <div class="patient-info ect-padding">
        <ul>
            <li><?php echo($nameResult['name'])?><a class="pull-right pub-grey" onclick=" refresh()">刷新</a></li>
            <li><?php echo($nameResult['sex']==0?"女":'男')?></li>
            <li>浙江宁波 <a class="pull-right pub-blue ect-colorf" onclick="exit()">退出</a></li>
        </ul>
    </div>
    <div class="treat-info ect-padding">
        <?php
        while ($data = $picResult->fetch_assoc()) {
            ?>
            <div class="treat-title ect-margin-tb ">
                <a class="qiye_file" href="javascript:;">医生反馈
                    <img src="../<?php echo(substr($data['pic'],2));?>" width="100%">                </a>
                <p>时间：<?php echo(date("Y-m-d",$data['time']));?><p>
            </div>
            <div class="treat-b"></div>
            <!--医生意见 -->
            <div class="treat-title ect-margin-tb ">
                <p><?php echo($data['comment']);?></p>

                <p>时间：<?php echo(date("Y-m-d",$data['retime']));?><p>
            </div>
            <div class="treat-b"  style="height:100px;"></div>
            <?php
        }
        ?>

        <div class="treat-b" style="height:100px;"></div>
        <!---->
    </div>




</div>

</body>

<script>

    function detail(id,type) {
        switch (type) {
            case 1:
                window.location.href = '<?php echo( $pageUrl) ?>' + "inviteDetail.php?id=" + id;
                break;
            case 2:
                window.location.href = '<?php echo( $pageUrl) ?>' + "buyMedicine.php?id=" + id;
                break;

        }
        <!--        window.location.href = '--><?php //echo( $pageUrl) ?><!--' + "consultationDetail.php?id=" + id;-->
    }

    function refresh(){
        window.location.href='<?php echo($messUrl) ?>';
    }

    function exit(){
        WeixinJSBridge.invoke('closeWindow',{},function(res){

            //alert(res.err_msg);

        });
    }
</script>