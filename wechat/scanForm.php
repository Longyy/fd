<?php
include("httpConfig.php");
require_once "jssdk.php";
//$jssdk = new JSSDK("wx1a3f4816d206f7cc", "27f6a58f927d79a71ef647d84f6af001");
$jssdk = new JSSDK();
$signPackage = $jssdk->GetSignPackage();

$accessToken = array
(
    'appid'      => $signPackage["appId"],
    'secret'     => $signPackage['appSecret'],
    'code'       => $_REQUEST["code"],
    'grant_type' => 'authorization_code'
);
//获取openid
$accessTokenHttp = "https://api.weixin.qq.com/sns/oauth2/access_token?" . http_build_query($accessToken);

include("httpGet.php");
$result = json_decode(httpGet($accessTokenHttp), true);
$openId=$result["openid"];
//echo($openId);
//获取转诊医生信息
include("connection.php");
$sql="select * from cms_wechat_user t1 left join cms_transfer_doctor t2 on t1.identity_id=t2.id
where t1.role_id=3 and t1.open_id='$openId'";
//echo($sql);
$doctorResult = mysqli_query($connection, $sql) or die(mysqli_error($connection));
$doctorName=$doctorResult->fetch_assoc();


//获取医疗中心省份
$sql = "select t1.province_id,t2.province as province_name from cms_medical_center t1 left join cms_province t2 on t1.province_id=t2.provinceID group by t1.province_id,t2.province having province_name is not null";
$centerProvinceResult = mysqli_query($connection, $sql) or die(mysqli_error($connection));

//判断转诊医生是否登录
$openId=$result["openid"];
$sql = "SELECT * FROM `cms_wechat_user` WHERE role_id=3 and open_id='" . $openId."'";
$result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
$rowCount=$result->num_rows;
if($rowCount==0){
    $url=$pageUrl."login.php?roleType=1&openId=".$openId;
    echo "<script>window.location.href='".$url."'</script>";
}
$result = $result->fetch_assoc();
$doctorId=$result["identity_id"];

//查询自疗医院
$sql = "SELECT * FROM `cms_medical_center`";
$centerResult = mysqli_query($connection, $sql) or die(mysqli_error($connection));

//查询医生信息
$sql = "SELECT * FROM `cms_doctor` ";
$centerdoctorResult = mysqli_query($connection, $sql) or die(mysqli_error($connection));
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>转诊单</title>
    <link rel="stylesheet" href="css/style2.css">
    <script language="JavaScript" src="js/jquery-1.11.0.min.js" type="text/javascript"></script>
</head>
<body>
<div class="con">
    <div class="ect-bg">
        <header class="ect-header ect-margin-tb ect-margin-lr text-center icon-write"> <a href="javascript:history.go(-1)" class="pull-left ect-icon ect-icon1 ect-icon-history">&nbsp;</a> <span>转诊单</span> <a href="javascript:;" class="pull-right ect-icon ect-icon1 ect-icon-mune"></a></header>
    </div>
    <section class="nav-c text-center">
        <span class="ect-colorb">欢迎使用红胎记咨询</span>
        <table class="referral-list" width="90%" cellspacing="10">
            <tr>
                <td colspan="2"><input id="txtCode" class="refer-input-txt zzd" type="text" placeholder="请输入转诊单号"/></td>
            </tr>
            <tr>
            <tr>
                <td colspan="2"><input id="txtName" class="refer-input-txt zzd" type="text" placeholder="请输入患者姓名"/></td>
            </tr>
                   <!--  <div class="name-age pull-left z-name">
                        <div class="name-left  refer-font">姓名</div>
                        <div class="na-input pull-left"><input id="txtName" placeholder="请输入患者姓名" type="text" class="inputBg"/></div> -->
                       <!--  <div class="name-left z-age refer-font">年龄</div> -->
<!--                        <div class="na-input pull-left">-->
                           <!--  <input id="txtAge" type="text" class="inputBg"/> -->
<!--                        </div>-->
                    <!-- </div> -->
              <!--   </td> -->
            </tr>
            <tr>
                <td width="21%"><img width="10" src="images/gender.png"/>&nbsp;&nbsp;<font class="refer-font">性&nbsp;&nbsp;别</font></td>
                <td align="left"><input id="rdSex" checked type="radio" name="sex"  name="sex"/>男&nbsp;&nbsp;<input name="sex"  type="radio"/>女</td>
            </tr>
            <tr>
                <td colspan="2"><input class="refer-input-txt z-phone" type="text" placeholder="请输入手机号码" onkeyup="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')" id="txtPhone"/></td>
            </tr>
             <tr style="border:1px solid ">
                <td colspan="2" style="height:50px; font-size:12px; color:#a9a9a9">
                出生日期: <input id="zztime" name="zztime" class="refer-input-txt z-phone" type="date"  style="width:74%"/>
                </td>
            </tr>
            <tr>
                <td ><!--&nbsp;<img width="10" src="images/map.gif"/>&nbsp;<font class="refer-font" >-->所在地区<!--</font>--></td>
                <td align="left">
                    <select id="ddlProvince" style="width: 100%;height: 30px; ">
                        <option value="">省份</option>
                        <?php
                        while ($row = $centerProvinceResult->fetch_assoc()) {
                            ?>
                            <option value="<?php echo($row['province_id']) ?>"><?php echo($row['province_name']) ?></option>
                        <?php } ?>
                    </select>
                    <!-- <select id="city" style="width:45%;height:37px;border:1px solid #72CDEC">
                        <option value="">--市--</option>
                        <option></option>
                    </select> -->
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <select id="ddlCenter" name="center_id" style="width:100%;height:37px;border:1px solid #72CDEC">
<!--                        <option>--推荐医院--</option>-->
<!--                        --><?php //while($row = $centerResult->fetch_assoc())
//                        {?>
<!--                        <option value="--><?php //echo($row['id'])?><!--">--><?php //echo($row['name'])?><!--</option>-->
<!--                        --><?php
//                        }?>

                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <select id="ddlDoctor" name="doctor_id" style="width:100%;height:37px;border:1px solid #72CDEC">
                        <option>--推荐医生--</option>
                        <?php while($row = $centerdoctorResult->fetch_assoc())
                        {?>
                            <option value="<?php echo($row['id'])?>"><?php echo($row['name'])?></option>
                        <?php
                        }?>
                    </select>
                </td>
            </tr>


            <tr>
                <td colspan="2" align="left">
                    <div style="padding-left:1em;" class="refer-font">
                        <input type="radio" checked="checked"/>同意红胎记咨询服务条款内容
                    </div></td>
            </tr>
            <tr>
                <td colspan="2"></td>
            </tr>

        </table>
        <p class="ect-padding text-center">
            <input type="button" onclick="submit()" class="btn_blue treat-btn ect-margin-lr" value="提交">
            <input type="button" class="btn_grey treat-btn ect-margin-lr" value="取消">
        </p>
        <br/>
        <br/>
        <br/>
    </section>

</div>

</body>
</html>

<script>




    function submit() {

        var param = {
            code:$("#txtCode").val(),
            name: $("#txtName").val(),
            sex: document.getElementById("rdSex").checked == true ? 1 : 0,
            //age: $("#txtAge").val(),
            phone: $("#txtPhone").val(),
            zztime: $("#zztime").val(),
            openId: '<?php echo $openId?>',
//            code: code,
            postType: "scanForm",
            province_id:$("#ddlProvince").val(),
           // city_id:$("#city").val(),
            center_id:$("#ddlCenter").val(),
            doctor_id:$("#ddlDoctor").val()
        };

        if (param.code == "") {
            alert("请输入转诊单号");
            return;
        }
        if (param.name == "") {
            alert("请输入姓名");
            return;
        }
        /*if (param.age == "") {
            alert("请输入年龄");
            return;
        }
        if (!(/(^[0-9]*$)/).test(param.age)) {
            alert("年龄输入不正确")
            return false;
        }

        if (param.age > 120) {
            alert("年龄输入不正确")
            return false;
        }*/
        if (param.phone == "") {
            alert("请输入手机号码");
            return;
        }
        if (!(/^(13|14|15|16|18|19)\d{9}$/).test(param.phone)) {
            alert("手机号码格式不正确")
            return false;
        }

        /*if (param.id_card == "") {
            alert("请输入身份证号码");
            return;
        }
        if (!(/(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/).test(param.id_card)) {
            alert("身份证号码输入有误");
            return;
        }*/
        if (param.province_id == "") {
            alert("请选择省份");
            return;
        }
       /* if (param.city_id == "") {
            alert("请选择城市");
            return;
        }*/
        if (param.center_id== "") {
            alert("请选择医院");
            return;
        }
        if (param.doctor_id== "") {
            alert("请选择医生");
            return;
        }
        $("#spanEnter").html("正在提交请求...");
//        alert(JSON.stringify(param));
        $.ajax({
            type: "POST",
            url: '<?php echo( $postUrl) ?>',
            data: param,
            success: function (data) {
                if (data.st == 0) {
                    alert(data.rt);
                    $("#spanEnter").html("提交");
                }
                else {
                    alert(data.rt);
                    if (data.st == 1) {
                       /* WeixinJSBridge.invoke('closeWindow', {}, function (res) {
                        
                        });*/
                        


                        window.location.href = "<?php echo( $pageUrl)?>transferConsultation.php?doctor_id="+<?php echo $doctorId;?>;

                       
                    }
                }
            }
        })
    }

    function ckType(m) {
        if (document.getElementById("rdType").checked == true) {
            $("#divOrderCode").hide();
            $("#txtOrderCode").attr("placeholder", "请输入预约号");
        }
        else {
            $("#divOrderCode").show();
            $("#txtOrderCode").attr("placeholder", "请输入转诊号");
        }
    }

    function checkLength(str) {
//        (str == null) return 0;
        if (typeof str != "string") {
            str += "";
        }
        return str.replace(/[^x00-xff]/g, "01").length;
    }





    $("#ddlCenter").bind("change", function (evt) {
        if ($(this).val() == "")return;
        $("#ddlDoctor").empty();
        $.ajax({
            type: "POST",
            url: '<?php echo( $postUrl) ?>',
            data: {'postType': 'loadDoctor', 'centerId': $(this).val()},
            success: function (data) {
                var objSelect = document.getElementById("ddlDoctor");
                var new_opt = new Option("--请选择--", "");
                objSelect.options.add(new_opt);
                $.each(data, function (index, row) {

                    var new_opt = new Option(row.name, row.id);
                    objSelect.options.add(new_opt);
//                    $("<option></option>").text(row.name).val(row.id).appendTo("#ddlDoctor")
                })
            }
        })
    })

    function selectProvince(obj){
        if ($(obj).val() == "")return;
        $("#city").empty();
        $.ajax({
            type: "POST",
            url: '<?php echo( $postUrl) ?>',
            data: {'postType': 'loadCity', 'provinceId': $(obj).val()},
            success: function (data) {
//                alert(JSON.stringify(data));
                $.each(data, function (index, row) {
                    $("<option></option>").text(row.name).val(row.id).appendTo("#city")
                })
            }
        })

    }

    $("#ddlProvince").bind("change", function (evt) {
        if ($(this).val() == "")return;
        $("#ddlCenter").empty();
        $("<option></option>").text("--请选择--").val("").appendTo("#ddlCenter");
        $("#ddlDoctor").empty();
        $("<option></option>").text("--请选择--").val("").appendTo("#ddlDoctor");
        $.ajax({
            type: "POST",
            url: '<?php echo( $postUrl) ?>',
            data: {'postType': 'loadCenter', 'provinceId': $(this).val()},
            success: function (data) {
                $.each(data, function (index, row) {
                    $("<option></option>").text(row.name).val(row.id).appendTo("#ddlCenter")
                })
            }
        })
    })


</script>