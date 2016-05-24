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

//var_dump($accessTokenHttp);
$result = httpGet($accessTokenHttp);

$result = json_decode($result, true);


include("connection.php");
//是否登录注册过，没有进行跳转
$openId = $result["openid"];
$sql = "SELECT * FROM `cms_wechat_user` WHERE role_id=3 and open_id='" . $openId . "'";
$result = mysqli_query($connection, $sql) or die(mysqli_error($connection));

$rowCount = $result->num_rows;
if ($rowCount == 0) {
    $url = $pageUrl . "login.php?roleType=3&openId=" . $openId;
    echo "<script>window.location.href='" . $url . "'</script>";
}
$result = $result->fetch_assoc();
$doctorId = $result["identity_id"];

$sql = "SELECT * FROM `cms_transfer_doctor` WHERE id='" . $doctorId . "'";
$doctorResult = mysqli_query($connection, $sql) or die(mysqli_error($connection));
$doctorName = $doctorResult->fetch_assoc();


//查询当前医生所拥有的患者求诊列表(未分页)
$sql = "SELECT * FROM `cms_qrcode_consultation` WHERE doctor_open_id='" . $openId . "' order by create_time desc ";
$patientResult = mysqli_query($connection, $sql) or die(mysqli_error($connection));
$rowCount = $patientResult->num_rows;
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no"/>
    <title><?php echo($doctorName["name"]) ?></title>
    <script language="JavaScript" src="js/jquery-1.11.0.min.js" type="text/javascript"></script>
    <script language="JavaScript" src="js/bootstrap.min.js" type="text/javascript"></script>
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">


    <script language="JavaScript" src="js/jquery.datetimepicker.js" type="text/javascript"></script>
    <link href="css/jquery.datetimepicker.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="alert alert-info" style="height: 180px; padding-left: 30px;" role="alert">
    <div class="row">
        转诊单号/手机号：<input type="text" id="txtCode">
    </div><br/>
    <div class="row">
        患者状态：
        <select id="ddlStatus">
            <option value=" ">--请选择--</option>
            <option value="1">转诊成功</option>
            <option value="0">未转诊</option>
        </select>
    </div><br/>
    <div class="row">
        查询时间：
        <input class="datetimepicker" id="timestart" size="10" style="color:#07d">
        <input type="hidden" id="hdtimestart">
        至
        <input type="email" class="datetimepicker" id="timeend" size="10" style="color:#07d">
        <input type="hidden" id="hdtimeend">
    </div>
    <div class="pull-right">
        <button type="button" id="btnseach" class="btn btn-warning" onclick="search()">
            <span class="icon-filter ezp-btnicon-left"></span>筛选
        </button>
<!--        <button type="button" id="btnseach" class="btn btn-primary" onclick="search()">-->
<!--            <span class="icon-filter ezp-btnicon-left"></span>扫一扫-->
<!--        </button>-->
    </div>

</div>
<table class="table table-striped">
    <tr>
        <th>患者姓名</th>
        <th>联系方式</th>
        <th>性别</th>
        <th>年龄</th>
<!--        <th>身份证号</th>-->
        <th>转诊时间</th>
    </tr>
    <tbody id="tbody">
    <?php
    while ($row = $patientResult->fetch_assoc()) {
        ?>
        <tr>
            <td><?php echo($row["name"]) ?></td>
            <td><?php echo($row["phone"]) ?></td>
            <td><?php echo($row["sex"] == 1 ? "男" : "女") ?></td>
            <td><?php echo($row["age"]) ?></td>
<!--            <td>--><?php //echo($row["id_card"]) ?><!--</td>-->
            <td><?php echo(date('Y-m-d', $row["create_time"])) ?></td>

        </tr>
    <?php
    }
    ?>
    </tbody>
</table>
<center>
    <div id="divPage">
        <?php include('bootstrapPage.php');
        //        echo(bootstrap_page(20, 3, 3, "ajax", 'DishesMagic-index'));
        if ($rowCount != 0)
            echo(bootstrap_page($rowCount, 3, 0, "ajax", 'transConsultationList'));
        ?>
    </div>
<br/>
    本服务由“红胎记咨询微信平台”提供
</center>

</body>
</html>

<script>
    $('.datetimepicker').datetimepicker({
        lang: 'cn',
        i18n: {
            cn: {
                months: [
                    '一月', '二月', '三月', '四月',
                    '五月', '六月', '七月', '八月',
                    '九月', '十月', '十一月', '十二月',
                ],
                dayOfWeek: [
                    "日.", "一", "二", "三",
                    "四", "五", "六.",
                ]
            }
        },
        timepicker: false,
        format: 'Y-m-d',
        closeOnDateSelect: true
    });
    var pageData = {'postType': 'transConsultationList'};
    function search(obj, page){
        pageData.pageIndex = 1;
        if (page == 'ajax') {
            var param = $(obj).data("page");
            pageData.pageIndex = param.p;
        }
        if(pageData.pageIndex==0)return;
        pageData.code = $("#txtCode").val();
        pageData.startTime = $("#timestart").val();
        pageData.endTime = $("#timeend").val();
        pageData.openId ='<?php echo($openId)?>';

        $.ajax({
            type: "POST",
            url: '<?php echo( $postUrl) ?>',
            data: pageData,
            success: function (data) {
//                alert(data.sql);
                $("#tbody").html("");
                $.each(data.rt, function (index, value) {
                    var sex = value.sex == 0 ? "女" : "男";

                    var tr = '<tr></tr>'
                    var td = '<td>' + value.name + '</td>' + '<td>' + value.phone + '</td>' + '<td>' + sex + '</td>' + '<td>' + value.age + '</td>' + '<td>' + value.createTime + '</td>';
                    $($(tr).append(td)).appendTo($("#tbody"));


                })

                $("#divPage").html(data.page);

                $(".jspage").click(function () {
                    search(this, 'ajax');
                })
            }
        })
    }
    $(".jspage").click(function () {
        search(this, 'ajax');
    })
</script>