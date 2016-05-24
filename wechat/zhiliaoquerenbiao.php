<?php
  include("connection.php");
include("httpConfig.php");
require_once "jssdk.php";
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
// 获取医疗中心
$sql = "SELECT * FROM`cms_medical_center`";
$centerResult = mysqli_query($connection, $sql) or die(mysqli_error($connection));
// 是否预约咨询过，没预约咨询则跳转到咨询页面
$sql = "SELECT * FROM `cms_consultation` WHERE  open_id='" . $openId . "'";
$resultq = mysqli_query($connection, $sql) or die(mysqli_error($connection));
$rowCountq = $resultq->num_rows;
if ($rowCountq == 0) {
    echo "<script>window.location.href='" . $consultation . "'</script>";
}
// 获取医生信息
$sql = "SELECT * FROM `cms_doctor`";
$doctorResult = mysqli_query($connection, $sql) or die(mysqli_error($connection));

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title></title>
<link rel="stylesheet" href="style.css">
<script language="JavaScript" src="js/jquery-1.11.0.min.js" type="text/javascript"></script>

</head>
<body style="font-family:微软雅黑;">
<div class="con">
	<div class="ect-bg">
		<header class="ect-header ect-margin-tb ect-margin-lr text-center icon-write"> <a href="javascript:history.go(-1)" class="pull-left ect-icon ect-icon1 ect-icon-history">&nbsp;</a> <span>治疗确认表</span> <a href="javascript:;" class="pull-right ect-icon ect-icon1 ect-icon-mune"></a></header>
	</div>
	<div style="margin-top:30px;" >
              <ul id="table1" class="nav nav-tabs text-center" style="height:50px; margin-left: 10%;">
                <li id="table" style="width:33.3%">心 电 图:</li>
                <li id="table" style="width:44.4%"><input name="heartpic" id="heartpic" type="radio" value="1" checked>正常&nbsp;&nbsp;&nbsp;
                <input name="heartpic" id="heartpic" type="radio" value="0">不正常</li>
                
              </ul>
 
    
              <ul id="table1" class="nav nav-tabs text-center" style="height:50px;margin-left: 10%;">
                <li id="table" style="width:33.3%">肝 功 能:</li>
               <li id="table" style="width:44.4%"><input name="liver" type="radio" id="liver" value="1" checked>正常&nbsp;&nbsp;&nbsp;
                <input name="liver" type="radio" id="liver" value="0">不正常</li>
              </ul>
              
              <ul id="table1" class="nav nav-tabs text-center" style="height:50px; text-align:center; margin-left:12%">
                <li id="table" style="width:80%">患者姓名: <input type="text" name="name" id="name" style="width:60%; height: 26px;" placeholder="请输入您的姓名"></li>
                
              </ul>
              <ul id="table1" class="nav nav-tabs text-center" style="height:50px; text-align:center; margin-left:12%">
                <li id="table" style="width:80%">患者手机: <input type="text" name="tel" id="tel" style="width:60%; height: 26px;" placeholder="请输入您的手机号"></li>
                
              </ul>
              
               <ul id="table1" class="nav nav-tabs text-center" style="height:50px; text-align:center; margin-left:12%">
                <li id="table" style="width:80%">医院名称: <select name="hospitalname" id="hospitalname" style="width:60%;height: 32px;">
                                                             <option value="">请选择医院</option>
                                                             <?php
                                                             while ($row = $centerResult->fetch_assoc()) {
                                                                 ?>
                                                                 <option value="<?php echo($row['name']) ?>"><?php echo($row['name']) ?></option>
                                                             <?php } ?>
                                                         </select>
                </li>
                
              </ul>
              <ul id="table1" class="nav nav-tabs text-center" style="height:50px; text-align:center; margin-left:12%">
                <li id="table" style="width:80%">医生姓名: <select name="doctorname" id="doctorname" style="width:60%;height: 32px;">
                                                             <option value="">请选择医生</option>
                                                             <?php
                                                             while ($row = $doctorResult->fetch_assoc()) {
                                                                 ?>
                                                                 <option value="<?php echo($row['name']) ?>"><?php echo($row['name']) ?></option>
                                                             <?php } ?> 
                                                         </select>
                </li>
                
              </ul>
              <ul style="100%; margin-bottom:20px;">
<li style="width:20%; height:30px; background:#50c1e9;float:left; margin-left:18%; margin-bottom:20px; color:#FFF; text-align:center"><p  style="color:#fff; line-height:200%" onclick="submit()">确认</p></li>
<li style="width:20%; height:30px; background:#50c1e9;float:left; margin-left:20%; margin-bottom:20px; color:#FFF; text-align:center"><a href="#" style="color:#fff; line-height:200%">取消</a></li>
</ul>
    </div>
	
	
</div>

</body>
<script type="text/javascript">
    
    function submit() {

        var param = {
            name: $("#name").val(),// 患者姓名
            heartpic: document.getElementById("heartpic").checked == 1 ? '正常' : '不正常',
            liver: document.getElementById("liver").checked == 1 ? '正常' : '不正常',
            //heartpic: $("input:radio:checked").val();
            //liver: $(".liver").val();
            //city:$("#city").val(),
            //age: $("#txtAge").val(),
//            city:$("#dlCity").val(),
            tel: $("#tel").val(),// 患者手机号码
            //id_card: $("#txtIdCard").val(),
            //orderType: document.getElementById("rdType").checked == true ? 1 : 0,
            //order_code: $("#txtOrderCode").val(),
            //diagnose: $("#ddlDiagnose").val(),
//            description: $("#ddlDescription").val(),
            doctorname: $("#doctorname").val(),// 医生名字
            hospitalname: $("#hospitalname").val(),// 治疗中心
            openid: '<?php echo $openId?>',
            postType: "zhiliaoquerenbiao"
//            resource: $("#ddlResource").val()
        }
        
     
        
//        alert(param.description);
        if (param.name == "") {
            alert("请输入患者姓名");
            return;
        }
       
        if (param.tel == "") {
            alert("请输入手机号码");
            return;
        }
        if (!(/^(13|14|15|16|18|19)\d{9}$/).test(param.tel)) {
            alert("手机号码格式不正确")
            return false;
        }
       
        if ($("#hospitalname").val() == "") {
            alert("请选择治疗中心");
            return;
        }
        if ($("#doctorname").val() == "") {
            alert("请选择医生");
            return;
        }
       
        /*param.doctorId = $("#ddlDoctor").val();
        $("#spanEnter").html("正在提交请求...");*/

        $.ajax({
            type: "POST",
            url: '<?php echo( $postUrl) ?>',
            data: param,
            success: function (data) {
                $("#spanEnter").html("提交");
                alert(data.rt);
                if (data.st == 1) {
                    WeixinJSBridge.invoke('closeWindow', {}, function (res) {

                    });
                }
            }
        })
    }

  /*  function ckType(m) {
        if (document.getElementById("rdType").checked == true) {
            $("#divOrderCode").hide();
        }
        else {
            $("#divOrderCode").show();
            $("#txtOrderCode").attr("placeholder", "请输入转诊号");
        }
    }*/

  /*  function checkLength(str) {
//        (str == null) return 0;
        if (typeof str != "string") {
            str += "";
        }
        return str.replace(/[^x00-xff]/g, "01").length;
    }*/
    function exit(){
        WeixinJSBridge.invoke('closeWindow',{},function(res){

            //alert(res.err_msg);

        });
    }

</script>
</html>



 