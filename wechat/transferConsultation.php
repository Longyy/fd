<?php
error_reporting(E_ALL);
include("httpConfig.php");
require_once "jssdk.php";
$jssdk = new JSSDK();
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
/*$openId = $result["openid"];
$sql = "SELECT * FROM `cms_wechat_user` WHERE role_id=3 and open_id='" . $openId . "'";
$result = mysqli_query($connection, $sql) or die(mysqli_error($connection));*/

/*$rowCount = $result->num_rows;
if ($rowCount == 0) {
    $url = $pageUrl . "login.php?roleType=3&openId=" . $openId;
    echo "<script>window.location.href='" . $url . "'</script>";
}*/
/*$result = $result->fetch_assoc();
$doctorId=$result["identity_id"];
//转诊医生信息
$sql = "SELECT t1.*,t2.name as hospital FROM cms_transfer_doctor t1 left join cms_transfer_hospital t2 on t1.hospital_id=t2.id where t1.id=$doctorId";
$doctorResult = mysqli_query($connection, $sql) or die(mysqli_error($connection));
$doctorInfo=$doctorResult->fetch_assoc();*/
//患者信息
/*$sql = "select t1.*,t3.name as doctor_name from cms_qrcode_consultation t1 left join cms_wechat_user t2 on t1.doctor_open_id=t2.open_id left join cms_transfer_doctor t3 on t3.id=t2.identity_id WHERE t2.open_id='" . $openId . "' order by t1.create_time desc";
$patientResult = mysqli_query($connection, $sql) or die(mysqli_error($connection));
$conResult = $patientResult->fetch_assoc();
$rowCount = $patientResult->num_rows;*/
// 提交转正医生的id
$doctor_id = $_GET["doctor_id"];
$sql = "select a.*, b.open_id from cms_transfer_doctor a left join cms_wechat_user b on a.phone = b.user_name where a.id=".$doctor_id;
$doctorInfo = mysqli_query($connection,$sql) or (mysqli_error($connection));
$doctorInfo=$doctorInfo->fetch_assoc();
// 查询转诊信息
$sql="SELECT * FROM cms_qrcode_consultation where doctor_open_id = '{$doctorInfo['open_id']}' order by id desc";
$transResult = mysqli_query($connection, $sql) or die(mysqli_error($connection));
$transUserResult = [];
while($row=mysqli_fetch_assoc($transResult)){
    $transUserResult[] = $row;
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>转诊信息管理</title>
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
        #table1{
            background-color:#ffffff;
            border-top:1px solid #CCC;
        }
        #table{
            border:none;
            line-height:300%;

    </style>
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

            ha1.style.display="none";
            ha2.style.display="block";

            ha4.style.color="#555";
            ha5.style.color="#fff";

            ha5.style.background="#50c1e9";
            ha4.style.background="#fff";

        }

    </script>
    <link rel="stylesheet" href="css/style.css">
    <script language="JavaScript" src="js/jquery-1.11.0.min.js" type="text/javascript"></script>

    <script language="JavaScript" src="js/bootstrap.min.js" type="text/javascript"></script>
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
            <li> <?php echo($doctorInfo['hospital'])?> <a class="pull-right pub-blue ect-colorf" onclick="exit()">退出</a></li>
        </ul>
    </div>

    <div class="ect-padding text-center">
        <a href="" class="btn btn-zlys ect-padding-lr ect-margin-lr">查询姓名/单号</a>
        <a href="<?php echo( $scanForm) ?>" class="btn btn-info ect-colorg ect-bg-grey ect-padding-lr ect-margin-lr">转诊单</a>
    </div>

    <section class="pub-tab" id="tab">
        <ul class="nav nav-tabs text-center">
            <li class="col-xs-4" style="width:50%; border:none;"><a data-toggle="tab" role="tab" href="#one" onClick="he1()" id="hq1" style="background:#50c1e9; color:#fff">转诊成功</a></li>
            <li class="col-xs-4" style="width:50%; border:none;"><a data-toggle="tab" role="tab" href="#two" onClick="he2()" id="hq2">转诊列表</a></li>
        </ul>
        <div class="tab_content">
            <div class="rl-txt" id="l1" style="display:black; padding:0 0;">
                <div align="center">
                    <p style="margin-bottom:20px;"><span style="font-size:0.7em">选择时间段:&nbsp;</span><span><input type="date" style="width:25%"></span><span>--</span><span><input type="date" style="width:25%"></span><span>&nbsp;<input type="button" value="查询" style="width:12%"></span></p>
                </div>
                <ul id="table1" class="nav nav-tabs text-center">
                    <li id="table" style="width:33.3%">患者姓名</li>
                    <li id="table" style="width:33.3%">性别</li>
                    <li id="table" style="width:33.3%">转诊时间</li>
                </ul>
               <?php
                  foreach($transUserResult as $key => $row) {
                      if($row['status_id'] == 1) {
                          ?>
                          <ul id="table1" class="nav nav-tabs text-center">
                              <li id="table" style="width:33.3%"><?php echo($row["name"]) ?></li>
                              <li id="table" style="width:33.3%"><a
                                      href="#"><?php echo ($row["sex"]) == 1 ? '男' : '女' ?></a></li>
                              <li id="table" style="width:33.3%"><?php echo($row["zztime"]) ?></li>
                          </ul>
                          <?php
                      }}?>
               <!-- <ul id="table1" class="nav nav-tabs text-center">
                    <li id="table" style="width:33.3%">王五</li>
                    <li id="table" style="width:33.3%"><a href="#">男</a></li>
                    <li id="table" style="width:33.3%">2015.06.12</li>
                </ul>
                <ul id="table1" class="nav nav-tabs text-center">
                    <li id="table" style="width:33.3%">赵六</li>
                    <li id="table" style="width:33.3%"><a href="#">男</a></li>
                    <li id="table" style="width:33.3%">2015.06.12</li>
                </ul>
                <ul id="table1" class="nav nav-tabs text-center" style="border-bottom:1px solid #CCC;">
                    <li id="table" style="width:33.3%">李达</li>
                    <li id="table" style="width:33.3%"><a href="#">男</a></li>
                    <li id="table" style="width:33.3%">2015.06.12</li>
                </ul>-->

            </div>
            <div class="rl-txt" id="l2" style="display:none;  padding:0 0;">
                <div align="center">
                    <p style="margin-bottom:20px;"><span style="font-size:0.7em">选择时间段:&nbsp;</span><span><input type="date" style="width:25%"></span><span>--</span><span><input type="date" style="width:25%"></span><span>&nbsp;<input type="button" value="查询" style="width:12%"></span></p>
                </div>
                <ul id="table1" class="nav nav-tabs text-center">
                    <li id="table" style="width:33.3%">患者姓名</li>
                    <li id="table" style="width:33.3%">性别</li>
                    <li id="table" style="width:33.3%">提交时间</li>
                </ul>
                <?php
                foreach($transUserResult as $key => $row) {
                    if($row['status_id'] == 0) {
                        ?>
                        <ul id="table1" class="nav nav-tabs text-center">
                            <li id="table" style="width:33.3%"><?php echo($row["name"]) ?></li>
                            <li id="table" style="width:33.3%"><a href="#"><?php echo($row["sex"]) ?></a></li>
                            <li id="table" style="width:33.3%"><?php echo($row["zztime"]) ?></li>
                        </ul>
                        <?php
                    }}?>
            </div>
        </div>
    </section>
</div>

<script>

    function detail(id){
        window.location.href='<?php echo( $pageUrl) ?>'+"consultationDetail.php?id="+id;
    }
    var pageData = {'postType': 'transConsultationList'};
    function search(obj,page){
        pageData.pageIndex=1;

//        pageData.status=0; //未处理
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

        pageData.openId='<?php echo($openId)?>';

//        alert(pageData.startTime);
        alert(JSON.stringify(pageData));
        $.ajax({
            type: "POST",
            url: '<?php echo( $postUrl) ?>',
            data: pageData,
            success: function (data) {
//                alert(JSON.stringify(data));
                $("#tbody").html("");
                $.each(data.rt,function(index,value){
                    var sex = value.sex == 0 ? "女" : "男";
                    var tr = '<tr></tr>'
                    var td = '<td>' + value.name + '</td>' +
                        '<td>' + value.phone + '</td>' +
                        '<td>' + sex + '</td>' +
                        '<td>' + value.age + '</td>' +
                        '<td>' + value.createTime + '</td>';
                    $($(tr).append(td)).appendTo($("#tbody"));
                });

                $("#divPage").html(data.page);

                $(".jspage").click(function(){
                    search(this,'ajax');
                })
            }
        })
    }

    $(".jspage").click(function () {
        alert('asdf');
        search(this, 'ajax');
    });



    function refresh(){
        window.location.href='<?php echo($tran_consultation) ?>';
    }

    function exit(){
        WeixinJSBridge.invoke('closeWindow',{},function(res){

            //alert(res.err_msg);

        });
    }
</script>
</body>

</html>