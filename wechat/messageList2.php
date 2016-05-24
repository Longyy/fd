<?php
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
$result = httpGet($accessTokenHttp);
$result = json_decode($result, true);
include("connection.php");
//是否登录注册过，没有进行跳转到咨询页
$openId = $result["openid"];
$sql = "SELECT * FROM `cms_wechat_user` WHERE role_id=4 and open_id='" . $openId . "'";
$result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
$rowCount = $result->num_rows;
if ($rowCount == 0) {
    echo "<script>window.location.href='" . $consultation . "'</script>";
}
//获取患者未读消息列表
$sql = "SELECT t1.create_time, t1.id,t1.order_code,t1.handle_type, t2.name as doctor_name,t1.name,t1.sex,t1.status_id FROM cms_consultation t1 left join cms_doctor t2 on t1.doctor_id=t2.id
WHERE status_id in(1,2,5,6) and t1.open_id='$openId'";
$consultationUnReader = mysqli_query($connection, $sql) or die(mysqli_error($connection));

//查询随访记录医生已处理患者未查看信息
$sql = "select * from cms_follow  where answer is not null and status_id =0 and patient_openId= '$openId'";
$suifangUnReader = mysqli_query($connection, $sql) or die(mysqli_error($connection));

// 是否预约咨询过，没预约咨询则跳转到咨询页面
$sql = "SELECT * FROM `cms_consultation` WHERE  open_id='" . $openId . "'";
$resultq = mysqli_query($connection, $sql) or die(mysqli_error($connection));
$rowCountq = $resultq->num_rows;
if ($rowCountq == 0) {
    echo "<script>window.location.href='" . $consultation . "'</script>";
}

//获取患者已读消息列表
$sql = "SELECT t1.create_time, t1.id,t1.order_code,t1.handle_type, t2.name as doctor_name,t1.name,t1.sex,t1.status_id FROM cms_consultation t1 left join cms_doctor t2 on t1.doctor_id=t2.id
WHERE status_id in (4) and t1.open_id='$openId'";
$consultationReaded= mysqli_query($connection, $sql) or die(mysqli_error($connection));

//查询随访记录医生已处理患者已查看信息
$sql = "select * from cms_follow  where answer is not null and status_id =1 and patient_openId= '$openId'";
$suifangReaded = mysqli_query($connection, $sql) or die(mysqli_error($connection));


$sql = "SELECT t1.id,t1.order_code,t1.handle_type, t2.name as doctor_name,t1.name,t1.sex,t1.city FROM cms_consultation t1 left join cms_doctor t2 on t1.doctor_id=t2.id
WHERE t1.open_id='$openId'";
$consultationNameResult = mysqli_query($connection, $sql) or die(mysqli_error($connection));
$nameResult = $consultationNameResult->fetch_assoc();
//print_r($nameResult);
// 根据咨询者查询咨询者城市
$a = $nameResult['city'];
$sql = "SELECT * FROM cms_city where cityID='$a'";
$cityResult = mysqli_query($connection, $sql) or die(mysqli_error($connection));
$cityName = $cityResult->fetch_assoc();
//根据城市信息查询省份
$b = $cityName['father'];
$sql = "SELECT * FROM cms_province WHERE provinceID='$b'";
$provinceResult = mysqli_query($connection, $sql) or die(mysqli_error($connection));
$provinceName = $provinceResult->fetch_assoc();


?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>患者</title>
    <link rel="stylesheet" href="style.css">
    <script language="JavaScript" src="js/jquery-1.11.0.min.js" type="text/javascript"></script>
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
<body style="font-family:微软雅黑;">
<div class="con">
    <div class="ect-bg">
        <header class="ect-header ect-margin-tb ect-margin-lr text-center icon-write"> <a href="javascript:history.go(-1)" class="pull-left ect-icon ect-icon1 ect-icon-history">&nbsp;</a> <span>患者</span> <a href="javascript:;" class="pull-right ect-icon ect-icon1 ect-icon-mune"></a></header>
    </div>

    <div class="ect-padding-tb zz-doctor doctor-intro ect-padding-lr ect-margin-lr">
        <ul>
            <li>
                <div class="zz-doctor-img pull-left" style="border-radius:100%;">&nbsp;</div>
                <dl>
                    <dd><?php echo($nameResult['name'])?><a class="pull-right pub-grey" onclick=" refresh()">刷新</a></dd>
                    <dd><?php echo($nameResult['sex']==0?"女":'男')?></dd>
                    <dd><?php echo ($provinceName['province'])?><?php echo($cityName['city'])?><a class="pull-right pub-blue ect-colorf" href="">退出</a></dd>
                </dl>
            </li>
        </ul>

    </div>
    <section class="pub-tab" id="tab">
        <ul class="nav nav-tabs text-center">
            <li class="col-xs-4" style="width:50%; border:none;"><a data-toggle="tab" role="tab" href="#one" onClick="he1()" id="hq1" style="background:#50c1e9; color:#fff">新消息</a></li>
            <li class="col-xs-4" style="width:50%; border:none;"><a data-toggle="tab" role="tab" href="#two" onClick="he2()" id="hq2">已读消息</a></li>
        </ul>
        <div class="tab_content">
            <div class="rl-txt" id="l1" style="display:black; padding:0 0;">
                <div align="center">
                    <p style="margin-bottom:20px;"><span style="font-size: 0.7rem">选择时间段:&nbsp;</span><span><input type="date" style="width: 25%"></span><span>--</span><span><input type="date" style="width: 25%"></span><span>&nbsp;<input type="button" value="查询" style="width:12%"></span></p>
                </div>
                <ul id="table1" class="nav nav-tabs text-center">
                    <li id="table" style="width:33.3%">医生姓名</li>
                    <li id="table" style="width:33.3%">信息类型</li>
                    <li id="table" style="width:33.3%">回复时间</li>
                </ul>
                <?php
                while ($row = $consultationUnReader->fetch_assoc()) {
                ?>
                        <ul onclick="detail(<?php echo($row["id"])?>,<?php echo($row["status_id"])?>)" id="table1" class="nav nav-tabs text-center">
                            <li id="table" style="width:33.3%"><?php echo($row['name'])?></li>
                            <li id="table" style="width:33.3%">
                                <?php
                                    switch($row['status_id']){
                                        case '1':
                                            echo('邀请预约');
                                            break;
                                        case '2':
                                            echo('咨询建议');
                                            break;
                                        case '6':
                                            echo('预约失败');
                                            break;
                                    }

                                ?>
                            </li>
                            <li id="table" style="width:33.3%"><?php echo(date('Y-m-d',$row['create_time']))?></li>
                        </ul>
                <?php
                }
                ?>
                <?php
                while ($row = $suifangUnReader->fetch_assoc()) {
                    $nameRes='';
//                    print_r($row);
//                    echo($row['doctor_openId']);
                    //获取医生或者技师姓名
                    $sql="select * from cms_wechat_user where open_id ='$row[doctor_openId]'";
                    $result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
                    $result = $result->fetch_assoc();
                    $id=$result['identity_id'];
                    if($result['role_id']==1){
                        $sql="select *from cms_doctor where id =$id";
                        $result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
                        $result = $result->fetch_assoc();
                        $nameRes=$result['name'];
                    }
                    elseif($result['role_id']==5){
                        $sql="select *from cms_technician_doctor where id =$id";
                        $result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
                        $result = $result->fetch_assoc();
                        $nameRes=$result['name'];
                    }
                    ?>
                    <ul onclick="read(<?php echo($row['id'])?>)" id="table1" class="nav nav-tabs text-center">
                        <li id="table" style="width:33.3%"><?php echo($nameRes)?></li>
                        <li id="table" style="width:33.3%">
                           随访
                        </li>
                        <li id="table" style="width:33.3%"><?php echo(date('Y-m-d',$row['answer_time']))?></li>
                    </ul>
                <?php
                }
                ?>
<!--                <ul id="table1" class="nav nav-tabs text-center">-->
<!--                    <li id="table" style="width:33.3%">李四</li>-->
<!--                    <li id="table" style="width:33.3%"><a href="#">方案推荐</a></li>-->
<!--                    <li id="table" style="width:33.3%">2015.06.12</li>-->
<!--                </ul>-->
<!--                <ul id="table1" class="nav nav-tabs text-center">-->
<!--                    <li id="table" style="width:33.3%">王五</li>-->
<!--                    <li id="table" style="width:33.3%"><a href="#">反馈回复</a></li>-->
<!--                    <li id="table" style="width:33.3%">2015.06.12</li>-->
<!--                </ul>-->
<!--                <ul id="table1" class="nav nav-tabs text-center">-->
<!--                    <li id="table" style="width:33.3%">赵六</li>-->
<!--                    <li id="table" style="width:33.3%"><a href="#">预约</a></li>-->
<!--                    <li id="table" style="width:33.3%">2015.06.12</li>-->
<!--                </ul>-->
<!--                <ul id="table1" class="nav nav-tabs text-center" style="border-bottom:1px solid #CCC;">-->
<!--                    <li id="table" style="width:33.3%">李达</li>-->
<!--                    <li id="table" style="width:33.3%"><a href="#">预约状态变更</a></li>-->
<!--                    <li id="table" style="width:33.3%">2015.06.12</li>-->
<!--                </ul>-->

            </div>
            <div class="rl-txt" id="l2" style="display:none;  padding:0 0;">
                <div align="center">
                    <p style="margin-bottom:20px;"><span style="font-size: 0.7rem">选择时间段:&nbsp;</span><span><input type="date" style="width: 25%"></span><span>--</span><span><input type="date" style="width: 25%"></span><span>&nbsp;<input type="button" value="查询" style="width:12%"></span></p>
                </div>
                <ul id="table1" class="nav nav-tabs text-center">
                    <li id="table" style="width:33.3%">医生姓名</li>
                    <li id="table" style="width:33.3%">信息类型</li>
                    <li id="table" style="width:33.3%">回复时间</li>
                </ul>
                <?php
                while ($row = $consultationReaded->fetch_assoc()) {
                    ?>
                    <ul onclick="detail(<?php echo($row["id"])?>,<?php echo($row["status_id"])?>)" id="table1" class="nav nav-tabs text-center">
                        <li id="table" style="width:33.3%"><?php echo($row['name'])?></li>
                        <li id="table" style="width:33.3%">
                            <?php
                            switch($row['status_id']){
                                case '4':
                                    echo('已预约');
                            }

                            ?>
                        </li>
                        <li id="table" style="width:33.3%"><?php echo(date('Y-m-d',$row['create_time']))?></li>
                    </ul>
                <?php
                }
                ?>

                <?php
                while ($row = $suifangReaded->fetch_assoc()) {
                    $nameRes='';
//                    print_r($row);
//                    echo($row['doctor_openId']);
                    //获取医生或者技师姓名
                    $sql="select * from cms_wechat_user where open_id ='$row[doctor_openId]'";
                    $result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
                    $result = $result->fetch_assoc();
                    $id=$result['identity_id'];
                    if($result['role_id']==1){
                        $sql="select *from cms_doctor where id =$id";
                        $result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
                        $result = $result->fetch_assoc();
                        $nameRes=$result['name'];
                    }
                    elseif($result['role_id']==5){
                        $sql="select *from cms_technician_doctor where id =$id";
                        $result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
                        $result = $result->fetch_assoc();
                        $nameRes=$result['name'];
                    }
                    ?>
                    <ul onclick="read(<?php echo($row['id'])?>)" id="table1" class="nav nav-tabs text-center">
                        <li id="table" style="width:33.3%"><?php echo($nameRes)?></li>
                        <li id="table" style="width:33.3%">
                            随访
                        </li>
                        <li id="table" style="width:33.3%"><?php echo(date('Y-m-d',$row['answer_time']))?></li>
                    </ul>
                <?php
                }
                ?>
<!--                <ul id="table1" class="nav nav-tabs text-center">-->
<!--                    <li id="table" style="width:33.3%">李四</li>-->
<!--                    <li id="table" style="width:33.3%"><a href="#">方案推荐</a></li>-->
<!--                    <li id="table" style="width:33.3%">2015.06.12</li>-->
<!--                </ul>-->
<!--                <ul id="table1" class="nav nav-tabs text-center">-->
<!--                    <li id="table" style="width:33.3%">王五</li>-->
<!--                    <li id="table" style="width:33.3%"><a href="#">反馈回复</a></li>-->
<!--                    <li id="table" style="width:33.3%">2015.06.12</li>-->
<!--                </ul>-->
<!--                <ul id="table1" class="nav nav-tabs text-center">-->
<!--                    <li id="table" style="width:33.3%">赵六</li>-->
<!--                    <li id="table" style="width:33.3%"><a href="#">预约</a></li>-->
<!--                    <li id="table" style="width:33.3%">2015.06.12</li>-->
<!--                </ul>-->
<!--                <ul id="table1" class="nav nav-tabs text-center" style="border-bottom:1px solid #CCC;">-->
<!--                    <li id="table" style="width:33.3%">李达</li>-->
<!--                    <li id="table" style="width:33.3%"><a href="#">预约状态变更</a></li>-->
<!--                    <li id="table" style="width:33.3%">2015.06.12</li>-->
<!--                </ul>-->
            </div>
        </div>
    </section>
</div>

</body>
</html>

<script>
    function refresh(){
        window.location.href='<?php echo($messUrl) ?>';
    }

    function detail(id,type) {
        switch (type) {
            case 1:
                window.location.href = '<?php echo( $pageUrl) ?>' + "inviteDetail.php?id=" + id;
                break;
            case 2:
                window.location.href = '<?php echo( $pageUrl) ?>' + "fangantuijian.php?id=" + id;
                break;
            case 6:
                window.location.href = '<?php echo( $pageUrl) ?>' + "yuyueshibai.php?id=" + id;
                break;


        }
        <!--        window.location.href = '--><?php //echo( $pageUrl) ?><!--' + "consultationDetail.php?id=" + id;-->
    }

    function read($id){
//        alert($id);
        $.ajax({
            type: "POST",
            url: '<?php echo( $postUrl) ?>',
            data: {postType:"readFollow",id:$id},
            success: function (data) {
                window.location.href = '<?php echo( get_wechat_url($domain.'shuhoufanhui.php')) ?>';

            }
        })
    }
</script>