<!--<script language="JavaScript" src="js/bootstrap.min.js" type="text/javascript"></script>-->
<!--<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">-->
<!--<link rel="stylesheet" href="css/style.css">-->
<style>
    .pagination {
        display: inline-block !important;
        padding-left: 0 !important;
        margin: 20px 0 !important;
        border-radius: 4px !important
    }

    .pagination > li {
        display: inline !important
    }

    .pagination > li > a, .pagination > li > span {
        position: relative !important;
        float: left !important;
        padding: 6px 12px !important;
        margin-left: -1px !important;
        line-height: 1.42857143 !important;
        color: #428bca !important;
        text-decoration: none !important;
        background-color: #fff !important;
        border: 1px solid #ddd !important
    }

    .pagination > li:first-child > a, .pagination > li:first-child > span {
        margin-left: 0 !important;
        border-top-left-radius: 4px !important;
        border-bottom-left-radius: 4px !important
    }

    .pagination > li:last-child > a, .pagination > li:last-child > span {
        border-top-right-radius: 4px !important;
        border-bottom-right-radius: 4px !important
    }

    .pagination > li > a:hover, .pagination > li > span:hover, .pagination > li > a:focus, .pagination > li > span:focus {
        color: #2a6496 !important;
        background-color: #eee !important;
        border-color: #ddd !important
    }

    .pagination > .active > a, .pagination > .active > span, .pagination > .active > a:hover, .pagination > .active > span:hover, .pagination > .active > a:focus, .pagination > .active > span:focus {
        z-index: 2 !important;
        color: #fff !important;
        cursor: default !important;
        background-color: #428bca !important;
        border-color: #428bca !important
    }

    .pagination > .disabled > span, .pagination > .disabled > span:hover, .pagination > .disabled > span:focus, .pagination > .disabled > a, .pagination > .disabled > a:hover, .pagination > .disabled > a:focus {
        color: #777 ;
        cursor: not-allowed !important;
        background-color: #fff !important;
        border-color: #ddd !important
    }

    .pagination-lg > li > a, .pagination-lg > li > span {
        padding: 10px 16px !important;
        font-size: 18px !important
    }

    .pagination-lg > li:first-child > a, .pagination-lg > li:first-child > span {
        border-top-left-radius: 6px !important;
        border-bottom-left-radius: 6px !important
    }

    .pagination-lg > li:last-child > a, .pagination-lg > li:last-child > span {
        border-top-right-radius: 6px !important;
        border-bottom-right-radius: 6px !important
    }

    .pagination-sm > li > a, .pagination-sm > li > span {
        padding: 5px 10px !important;
        font-size: 12px !important
    }

    .pagination-sm > li:first-child > a, .pagination-sm > li:first-child > span {
        border-top-left-radius: 3px !important;
        border-bottom-left-radius: 3px !important
    }

    .pagination-sm > li:last-child > a, .pagination-sm > li:last-child > span {
        border-top-right-radius: 3px !important;
        border-bottom-right-radius: 3px !important
    }
</style>
<?php

//include('bootstrapPage.php');
//echo(bootstrap_page(7, 3, 0, "ajax", 'consultationList'));
//exit();
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
$sql = "SELECT * FROM `cms_wechat_user` WHERE role_id=1 and open_id='" . $openId."'";
$result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
$rowCount=$result->num_rows;
if($rowCount==0){
    $url=$pageUrl."login.php?roleType=1&openId=".$openId;
    echo "<script>window.location.href='".$url."'</script>";
}
$result = $result->fetch_assoc();
$doctorId=$result["identity_id"];

//查询医生信息
$sql = "SELECT t1.*,t3.name as center_name FROM cms_doctor t1 left join cms_medical_center_doctor t2 on t1.id=t2.doctor_id left join cms_medical_center t3 on t2.medical_center_id=t3.id where t1.id=$doctorId";
$doctorResult = mysqli_query($connection, $sql) or die(mysqli_error($connection));
$doctorInfo=$doctorResult->fetch_assoc();


//查询总数
$sql = "SELECT * FROM `cms_consultation` WHERE doctor_id=$doctorId";
$patientResult = mysqli_query($connection, $sql) or die(mysqli_error($connection));
$rowCount = $patientResult->num_rows;

//查询当前医生所拥有的患者求诊列表
$sql = "SELECT * FROM `cms_consultation` WHERE doctor_id=$doctorId and (handle_type=0 or handle_type=4) limit 0,20";
$patientResult = mysqli_query($connection, $sql) or die(mysqli_error($connection));

//查询当前医生所拥有的患者求诊列表
$sql = "SELECT * FROM `cms_order` WHERE d_id=$doctorId and status=0  and type=1 limit 0,20";
$orderResult = mysqli_query($connection, $sql) or die(mysqli_error($connection));
//查询当前医生所拥有的患者求诊列表
$sql = "SELECT * FROM `cms_order` WHERE d_id=$doctorId and status=0  and type=1 limit 0,20";
$orderResult = mysqli_query($connection, $sql) or die(mysqli_error($connection));
//查询当前医生所拥有的患者求诊列表
$sql = "SELECT p.*,c.name,c.phone FROM `cms_pic` as p left join `cms_consultation` as c on p.uid=c.id where c.doctor_id=$doctorId and p.status=0 limit 0,20";
$picResult = mysqli_query($connection, $sql) or die(mysqli_error($connection));
//var_dump($doctorId);

////查询总销量
//$sql = "SELECT count(*) as count_sale FROM `cms_consultation` WHERE doctor_id='" . $doctorId ."'";
//$saleResult = mysqli_query($connection, $sql) or die(mysqli_error($connection));
//$saleResult = $saleResult->fetch_assoc();
//$countSale=$saleResult["count_sale"];
//
////查询上月销量
//$sql = "SELECT count(*) as count_sale FROM `cms_consultation` WHERE doctor_id='" . $doctorId ."' and create_time>".strtotime( date('Y-m-01', strtotime('-1 month')))." and create_time<".strtotime( date('Y-m-t', strtotime('-1 month')));
//$saleResult = mysqli_query($connection, $sql) or die(mysqli_error($connection));
//$saleResult = $saleResult->fetch_assoc();
//$lastMonthCountSale=$saleResult["count_sale"];
//
////查询本月销量
//$BeginDate=date('Y-m-01', strtotime(date("Y-m-d")));
//$sql = "SELECT count(*) as count_sale FROM `cms_consultation` WHERE doctor_id='" . $doctorId ."' and create_time>".strtotime($BeginDate)." and create_time<".strtotime( date('Y-m-d', strtotime("$BeginDate +1 month -1 day")) );
//$saleResult = mysqli_query($connection, $sql) or die(mysqli_error($connection));
//$saleResult = $saleResult->fetch_assoc();
//$currentMonthCountSale=$saleResult["count_sale"];
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>治疗医生</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="style1.css">

    <script language="JavaScript" src="js/jquery-1.11.0.min.js" type="text/javascript"></script>

    <script language="JavaScript" src="js/bootstrap.min.js" type="text/javascript"></script>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js" type="text/javascript" charset="utf-8"></script>
<!--    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">-->
</head>
<body>
<div class="con">
<!--    <div class="ect-bg">-->
<!--        <header class="ect-header ect-margin-tb ect-margin-lr text-center icon-write"> <a href="javascript:history.go(-1)" class="pull-left ect-icon ect-icon1 ect-icon-history">&nbsp;</a> <span>治疗医生</span> <a href="javascript:;" class="pull-right ect-icon ect-icon1 ect-icon-mune"></a></header>-->
<!--    </div>-->

    <div class="patient-info ect-padding">
        <ul>
            <li><?php echo($doctorInfo['name'])?><a class="pull-right pub-grey" onclick=" refresh()">刷新</a></li>
            <li><?php echo($doctorInfo['sex']==0?"女":"男")?></li>
            <li> <?php echo($doctorInfo['center_name'])?> <a class="pull-right pub-blue ect-colorf" onclick="exit()">退出</a></li>
        </ul>
    </div>

    <div class="ect-padding text-center">
        <a href="" class="btn btn-zlys ect-padding-lr ect-margin-lr">查询姓名/单号</a>
        <a id="scanQRCode1"  class="btn btn-info ect-colorg ect-bg-grey ect-padding-lr ect-margin-lr">扫一扫</a>
        <a href="order.php?openid=<?php echo $openId?>" class="btn btn-zlys ect-padding-lr ect-margin-lr">代下单</a>

    </div>

    <section class="pub-tab">
        <ul class="nav nav-tabs text-center">
<!--            <li class="col-xs-4 cur" onclick="jump('after')"><a data-toggle="tab" role="tab" href="#one">待处理</a></li>-->
<!--            <li class="col-xs-4 " onclick="jump('before')"><a data-toggle="tab" role="tab" href="#two">已处理</a></li>-->
<!--            <li class="col-xs-4 " onclick="jump('other')"><a data-toggle="tab" role="tab" href="#two">其他</a></li>-->
            <li class="col-xs-4 cur" onclick="jump('after')"><a data-toggle="tab" role="tab" href="#one">待处理</a></li>
            <li class="col-xs-4 " onclick="jump('before')"><a data-toggle="tab" role="tab" href="#two">已处理</a></li>
            <li class="col-xs-4" onclick="jump('other')" style="border:solid 0px red;width:100px;"><a data-toggle="tab" role="tab" href="#three">其他</a></li>
        </ul>
        <div class="tab_content">
            <div class="rl-txt" id="l3" style="display:block">
                <li id="daixiadan" onClick="ta()">咨询表</li></br>
                <table id="daixiadan1" width="100%" style="display:table">
                    <tr id="shengming" align="center">
                        <td>姓名</td>
                        <td>电话</td>
                        <td>性别</td>
                        <td>预约时间</td>
                        <td>订单状态</td>
                    </tr>
                    <?php
                    while ($row = $patientResult->fetch_assoc()) {
                        ?>
                        <tr onclick="detail(<?php echo($row["id"])?>,<?php echo($row["handle_type"])?>)">
                            <td bgcolor="#ffffff"><?php echo($row["name"]) ?></td>
                            <td bgcolor="#ffffff"><?php echo($row["phone"]) ?></td>
                            <td bgcolor="#ffffff"><?php echo($row["sex"]==1?"男":"女") ?></td>
                            <td bgcolor="#ffffff"><?php echo(date('Y-m-d',$row["create_time"])) ?></td>
                            <!--                    <td>--><?php //echo($row["status_id"]==-1?"未处理":"已处理") ?><!--</td>-->
                            <td bgcolor="#ffffff">
                                <?php switch($row["handle_type"]){
                                    case '0':
                                        echo("未处理");
                                        break;
                                    case '4':
                                        echo("患者已预约<br/>");
                                        echo(date('Y-m-d',$row['reserve_time']));
                                        echo($row['reserve_reg']==1?"上午":"下午");
                                        break;
                                    default:
                                        echo("未处理");
                                }?></td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
                </br>

            </div>

            <div class="rl-txt" id="l3" style="display:block">
                <li id="huanzhe"><a href="patient_upload.html">患者图片列表(点击行回复)</a></li></br>
                <table id="daixiadan1" width="100%" style="display:table">
                    <tr id="shengming" align="center">
                        <td>姓名</td>
                        <td>电话</td>
                        <td>上传时间</td>
                        <td>状态</td>
                    </tr>
                    <?php
                    while ($row = $picResult->fetch_assoc()) {
                        ?>
                        <tr onclick="detailp(<?php echo($row["id"])?>)">
                            <td bgcolor="#ffffff"><?php echo($row["name"]) ?></td>
                            <td bgcolor="#ffffff"><?php echo($row["phone"]) ?></td>
                            <td bgcolor="#ffffff"><?php echo(date('Y-m-d',$row["time"])) ?></td>
                            <!--                    <td>--><?php //echo($row["status_id"]==-1?"未处理":"已处理") ?><!--</td>-->
                            <td bgcolor="#ffffff">
                                <?php switch($row["status"]){
                                    case '0':
                                        echo("未处理");
                                        break;
                                    case '1':
                                        echo("已处理");
                                        break;
                                }?></td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
                </br>

            </div>

            <center>
                <div id="divPage">
                    <?php include('bootstrapPage.php');
                    if($rowCount!=0)
                        echo(bootstrap_page($rowCount, 20, 0, "ajax", 'consultationList'));
                    ?>
                </div>
            </center>
        </div>
    </section>
</div>
</body>
</html>

<script>

    var longitude;
    var latitude;
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
//        wx.getLocation({
//            success: function (res) {
//                alert(JSON.stringify(res));
////                longitude = res.longitude;
////                latitude = res.latitude;
//            },
//            cancel: function (res) {
//                alert('用户拒绝授权获取地理位置');
//            }
//        });
        document.querySelector('#scanQRCode1').onclick = function () {
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
        };
    });



        function detail(id,type){
            //预约
            if(type==4){
    <!--            window.location.href='--><?php //echo( $pageUrl) ?><!--'+"consultationDetail.php?id="+id;-->
                window.location.href='orderDetail.php?id='+id;
            }
            else if(type==0){
                            window.location.href='<?php echo( $pageUrl) ?>'+"consultationDetail.php?id="+id;
            }
        }
    function detailp(id){
        window.location.href='consultationDetailp.php?id='+id;
    }
    var pageData = {'postType': 'consultationList'};
    function search(obj,page){
        pageData.pageIndex=1;
        pageData.status=0; //未处理
        if(page=='ajax'){
            var param = $(obj).data("page");
            pageData.pageIndex=param.p;
//            pageData.pageIndex=(param.p-1);
//            alert(pageData.pageIndex);
        }
        if(pageData.pageIndex==0)return;
//        pageData.status=$("#ddlStatus").val();
//        pageData.startTime = $("#timestart").val();
//        pageData.endTime = $("#timeend").val();
        pageData.doctorId='<?php echo($doctorId)?>';
//        alert(pageData.startTime);
//        alert(JSON.stringify(pageData));
        $.ajax({
            type: "POST",
            url: '<?php echo( $postUrl) ?>',
            data: pageData,
            success: function (data) {
//                alert(JSON.stringify(data));
                $("#tbody").html("");
                $.each(data.rt,function(index,value){
                    var sex=value.sex==0?"女":"男";
                    var handle_type="";
                    switch (value.handle_type){
                        case '0':
                            handle_type="未处理";
                            break;
                        case '4':
                            handle_type="患者已预约";
                            break;
                    }
                    var tr='<tr onclick=\"detail('+value.id+','+value.handle_type+')\"></tr>'
                    var td = '<td bgcolor="#ffffff">'+value.name+'</td>'+
                        '<td bgcolor="#ffffff">'+value.phone+'</td>'+
                        '<td bgcolor="#ffffff">'+sex+'</td>'+
                        '<td bgcolor="#ffffff">'+value.createTime+'</td>';
//                        '<td bgcolor="#ffffff">'+handle_type+'</td>';
                    $($(tr).append(td)).appendTo($("#tbody"));


                })

                $("#divPage").html(data.page);

                $(".jspage").click(function(){
                    search(this,'ajax');
                })
            }
        })
    }

    $(".jspage").click(function () {
        search(this, 'ajax');
    })

    function jump(type){
        if(type=='before'){
            window.location.href = '<?php echo( $tran_consultation_before) ?>';
        }
        else if(type=='after'){
            window.location.href = '<?php echo( $consultationListUrl) ?>';
        }else if(type=="other"){
            window.location.href = '<?php echo( $tran_consultation_other) ?>';
        }
    }

    function refresh(){
        window.location.href='<?php echo($consultationListUrl) ?>';
    }

    function exit(){
        WeixinJSBridge.invoke('closeWindow',{},function(res){

            //alert(res.err_msg);

        });
    }
</script>