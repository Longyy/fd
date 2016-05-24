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
$sql = "SELECT t1.*,t3.name as center_name FROM cms_doctor t1 left join cms_medical_center_doctor t2 on t1.id=t2.doctor_id left join cms_medical_center t3 on t2.medical_center_id=t3.id where t1.id=$doctorId order by id desc";
$doctorResult = mysqli_query($connection, $sql) or die(mysqli_error($connection));
$doctorInfo=$doctorResult->fetch_assoc();


//查询已预约未处理患者列表信息
$sql = "SELECT * FROM `cms_consultation` WHERE doctor_id=$doctorId and status_id =4 order by id desc";
$patientUnHandle = mysqli_query($connection, $sql) or die(mysqli_error($connection));

//查询已处理患者列表信息
$sql = "SELECT * FROM `cms_consultation` WHERE doctor_id=$doctorId and status_id in(5,6) order by id desc";
$patientHandled = mysqli_query($connection, $sql) or die(mysqli_error($connection));


// 获取系统的当前时间与患者提交的愉悦处理填写的预约时间进行对比，算出年龄



?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>预约处理</title>
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
        <header class="ect-header ect-margin-tb ect-margin-lr text-center icon-write"> <a href="javascript:history.go(-1)" class="pull-left ect-icon ect-icon1 ect-icon-history">&nbsp;</a> <span>预约处理</span> <a href="javascript:;" class="pull-right ect-icon ect-icon1 ect-icon-mune"></a></header>
    </div>

    <div class="ect-padding-tb zz-doctor doctor-intro ect-padding-lr ect-margin-lr">
        <ul>
            <li>
                <div class="zz-doctor-img pull-left" style="border-radius:100%;">&nbsp;</div>
                <dl>
                    <dd><?php echo($doctorInfo['name'])?><a class="pull-right pub-grey" href="">刷新</a></dd>
                    <dd><?php echo($doctorInfo['titleName'])?></dd>
                    <dd><?php echo($doctorInfo['center_name'])?><a class="pull-right pub-blue ect-colorf" href="">退出</a></dd>
                </dl>
            </li>
        </ul>

    </div>
    <div style="margin-bottom:20px" align="center"><li><input type="search" style="width:35%;" placeholder="请输入姓名或手机号">&nbsp;<input type="button" value="查询" style="width:10%"></li></div>
    <section class="pub-tab" id="tab">
        <ul class="nav nav-tabs text-center">
            <li class="col-xs-4" style="width:50%; border:none;"><a data-toggle="tab" role="tab" href="#one" onClick="he1()" id="hq1" style="background:#50c1e9; color:#fff">未处理</a></li>
            <li class="col-xs-4" style="width:50%; border:none;"><a data-toggle="tab" role="tab" href="#two" onClick="he2()" id="hq2">已处理</a></li>
        </ul>
        <div class="tab_content">
            <div class="rl-txt" id="l1" style="display:black; padding:0 0;">
                <div align="center">
                    <p style="margin-bottom:20px;"><span style="font-size: 0.7rem">选择时间段:&nbsp;</span><span><input type="date" style="width: 25%"></span><span>--</span><span><input type="date" style="width: 25%"></span><span>&nbsp;<input type="button" value="查询" style="width:12%"></span></p>
                </div>
                <ul id="table1" class="nav nav-tabs text-center">
                    <li id="table" style="width:20%">患者姓名</li>
                    <li id="table" style="width:20%">性别</li>
                    <li id="table" style="width:20%">年龄</li>
                    <li id="table" style="width:20%">预约时间</li>
                    <li id="table" style="width:20%">操作</li>
                </ul>
                <?php
                while ($row = $patientUnHandle->fetch_assoc()) {
                    $b = str_replace("-","",date("Y-m-d",time()));//得到系统时间
                    $a = str_replace("-","",date('Y-m-d' ,$row['birthday']+28800));//得到患者预约的时间
                    $c =  substr(($b-$a)/10000,0,2);//将两个时间进行转换为年龄时间
                    ?>
                    <ul id="table1" class="nav nav-tabs text-center" onclick="detail(<?php echo($row['id'])?>,<?php echo($row['status_id'])?>)">
                        <li id="table" style="width:20%"><?php echo($row['name'])?></li>
                        <li id="table" style="width:20%"><?php echo($row['sex']==1?"男":"女") ?></li>
                        <li id="table" style="width:20%"><?php echo($c)?></li>
                        <li id="table" style="width:25%"><?php echo(date('Y-m-d' ,$row['reserve_time']+28800))?></li>
                        <li id="table" style="width:10%"><a href="#">处理</a></li>
                    </ul>
                <?php
                }
                ?>


            </div>
            <div class="rl-txt" id="l2" style="display:none;  padding:0 0;">
                <div align="center">
                    <p style="margin-bottom:20px;"><span style="font-size: 0.7rem">选择时间段:&nbsp;</span><span><input type="date" style="width: 25%"></span><span>--</span><span><input type="date" style="width: 25%"></span><span>&nbsp;<input type="button" value="查询" style="width:12%"></span></p>
                </div>
                <ul id="table1" class="nav nav-tabs text-center">
                    <li id="table" style="width:20%">患者姓名</li>
                    <li id="table" style="width:20%">性别</li>
                    <li id="table" style="width:20%">年龄</li>
                    <li id="table" style="width:20%">预约时间</li>
                    <li id="table" style="width:20%">操作</li>
                </ul>
                <?php
                while ($row = $patientHandled->fetch_assoc()) {
                    $b = str_replace("-","",date("Y-m-d",time()));//得到系统时间
                    $a = str_replace("-","",date('Y-m-d' ,$row['birthday']));//得到患者预约的时间
                    $c =  substr(($b-$a)/10000,0,2);//将两个时间进行转换为年龄时间
                    ?>
                    <ul id="table1" class="nav nav-tabs text-center" onclick="detail(<?php echo($row['id'])?>,<?php echo($row['status_id'])?>)">
                        <li id="table" style="width:20%"><?php echo($row['name'])?></li>
                        <li id="table" style="width:20%"><?php echo($row['sex']==1?"男":"女") ?></li>
                        <li id="table" style="width:20%"><?php echo($c)?></li>
                        <li id="table" style="width:25%"><?php echo(date('Y-m-d' ,$row['reserve_time']))?></li>
                        <li id="table" style="width:10%"><a href="#">已处理</a></li>
                    </ul>
                <?php
                }
                ?>
            </div>
        </div>
    </section>
</div>

</body>
</html>
<script>
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
</script>
 