<?php
include("connection.php");
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

//获取医疗中心
$sql = "SELECT * FROM`cms_medical_center`";
$centerResult = mysqli_query($connection, $sql) or die(mysqli_error($connection));
//echo( $_SERVER['SERVER_NAME']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no"/>
    <title>我要求诊</title>
    <script language="JavaScript" src="js/jquery-1.11.0.min.js" type="text/javascript"></script>
    <script language="JavaScript" src="js/bootstrap.min.js" type="text/javascript"></script>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js" type="text/javascript" charset="utf-8"></script>
    <script language="JavaScript" src="js/bootstrap-multiselect.js" type="text/javascript"></script>
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="css/bootstrap-multiselect.css" rel="stylesheet" type="text/css">
</head>
<body style="padding-top: 10px">
<form style="padding-right: 10px; padding-left: 10px;">
    <div class="form-group">
        <label for="exampleInputPassword1">姓名</label>
        <input type="text" class="form-control" id="txtName" placeholder="请输入姓名">
    </div>

    <div class="form-group">
        <label for="exampleInputPassword1">性别</label>
        <label> <input type="radio" id="rdSex" checked name="sex"> 男</label>
        <label> <input type="radio" name="sex"> 女</label>
    </div>
    <div class="form-group">
        <label for="exampleInputPassword1">年龄</label>
        <input type="text" class="form-control" id="txtAge" onkeyup="this.value=this.value.replace(/\D/g,'')"
               onafterpaste="this.value=this.value.replace(/\D/g,'')" placeholder="请输入您的年龄">
    </div>
    <div class="form-group">
        <label for="exampleInputPassword1">手机号码</label>
        <input type="text" onkeyup="this.value=this.value.replace(/\D/g,'')"
               onafterpaste="this.value=this.value.replace(/\D/g,'')" id="txtPhone" class="form-control" placeholder="请输入手机号码">
    </div>
    <div class="form-group">
        <label for="exampleInputPassword1">身份证号码</label>
        <input type="text" class="form-control" id="txtIdCard" placeholder="请输入身份证号码">
    </div>
    <div class="form-group">
        <label for="exampleInputPassword1">信息来源</label>
        <select id="ddlResource">
            <option value="QQ">Q</option>
            <option value="微信">微信</option>
            <option value="媒体报道">媒体报道</option>
            <option value="患友介绍">患友介绍</option>
            <option value="医生推荐">医生推荐</option>
            <option value="其他">其他</option>
        </select>
    </div>
    <div class="form-group">
        <label for="exampleInputPassword1">求诊类型</label>
        <label> <input type="radio" id="rdType" onchange="ckType(this)" checked name="conType"> 预约</label>
        <label><input type="radio" name="conType" onchange="ckType(this)"> 转诊</label>
    </div>
    <div class="form-group" id="divOrderCode" style="display: none">
        <label for="exampleInputPassword1">转诊号/预约号</label>
        <input type="text" class="form-control" id="txtOrderCode" placeholder="请输入预约号">
    </div>
    <div class="form-group">
        <label for="exampleInputPassword1">临床诊断</label>
        <!--        <input type="text" class="form-control" id="txtDiagnose" placeholder="请输入临床诊断">-->
<!--        <textarea class="form-control" maxlength="70" id="txtDiagnose" placeholder="请输入临床诊断,最多70字" rows="3"></textarea>-->
        <select id="ddlDiagnose">
            <option value="血管瘤">血管瘤</option>
            <option value="鲜红斑痣">鲜红斑痣</option>
            <option value="其他">其他</option>
        </select>
    </div>
    <div class="form-group">
        <label for="exampleInputPassword1">病情描述</label>
<!--        <textarea class="form-control" maxlength="70" id="txtDescription" placeholder="请输入病情描述,最多70字" rows="3"></textarea>-->
<!--        <select id="ddlDescription" style="height: 125px;" class="multiselect" multiple="multiple">-->
<!--            <option value="面部">面部</option>-->
<!--            <option value="颈部">颈部</option>-->
<!--            <option value="上肢">上肢</option>-->
<!--            <option value="下肢">下肢</option>-->
<!--            <option value="胸">胸</option>-->
<!--            <option value="背">背</option>-->
<!--            <option value="其他部位">其他部位</option>-->
<!--        </select>-->
        <input type="checkbox" id="ckDes1" value="面部">面部
        <input type="checkbox" id="ckDes2" value="颈部">颈部
        <input type="checkbox" id="ckDes3" value="上肢">上肢
        <input type="checkbox" id="ckDes4" value="下肢">下肢
        <input type="checkbox" id="ckDes5" value="胸">胸
        <input type="checkbox" id="ckDes6" value="背">背
        <input type="checkbox" id="ckDes7" value="其他部位">其他部位
    </div>
    <div class="form-group">
        <label for="exampleInputPassword1">过往治疗史</label>
<!--        <textarea class="form-control" maxlength="70" id="txtBeforeTreat" placeholder="请输入曾经过往治疗史,最多70字" rows="3"></textarea>-->
        <select id="ddlBeforeTreat">
            <option value="脉冲染料激光">脉冲染料激光</option>
            <option value="光动力">光动力</option>
            <option value="其他">其他</option>
        </select>
    </div>
    <div class="form-group">
        <label for="exampleInputPassword1">治疗要求</label>
<!--        <textarea class="form-control" id="txtRequire" maxlength="70" placeholder="请输入治疗要求,最多70字" rows="3"></textarea>-->
        <select id="ddlRequire">
            <option value="就近治疗">就近治疗</option>
            <option value="异地治疗">异地治疗</option>
        </select>
    </div>
    <div class="form-group">
        <label for="exampleInputPassword1">医疗中心</label>
        <select id="ddlCenter">
            <option value="">--请选择--</option>
            <?php
            while ($row = $centerResult->fetch_assoc()) {
                ?>
                <option value="<?php echo($row['id']) ?>"><?php echo($row['name']) ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="form-group">
        <label for="exampleInputPassword1">医生</label>
        <select id="ddlDoctor">

        </select>
    </div>
    <div class="form-group">
        <input id="ckService" type="checkbox">&nbsp;同意“光动力咨询”的服务条款
    </div>
</form>
<center>
    <button type="button" id="btnSubmit" onclick="submit()" style="width: 200px;" class="btn btn-primary">
        <span id="spanEnter">提交</span>
</center>
<br/>
<br/>
<br/>
</body>
</html>
<script>
    var openId = '<?php echo $result["openid"];?>'
    var longitude;
    var latitude;

    function submit() {

        var param = {
            name: $("#txtName").val(),
            sex: document.getElementById("rdSex").checked == true ? 1 : 0,
            age: $("#txtAge").val(),
//            city:$("#dlCity").val(),
            phone: $("#txtPhone").val(),
            id_card: $("#txtIdCard").val(),
            orderType: document.getElementById("rdType").checked == true ? 1 : 0,
            order_code: $("#txtOrderCode").val(),
            diagnose: $("#ddlDiagnose").val(),
//            description: $("#ddlDescription").val(),
            before_treat: $("#ddlBeforeTreat").val(),
            demand: $("#ddlRequire").val(),
            latitude: latitude,
            longitude: longitude,
            openId: openId,
            postType: "consulation",
            resource:$("#ddlResource").val()
        }
//        alert(param.diagnose)
//        alert(param.description)
//        alert(param.before_treat)
//        alert(param.demand)
//        if (param.description != null) {
//            var desStr = '';
//            $.each(param.description, function (index, value) {
//                desStr += value + " ";
//            })
//            param.description=desStr;
//        }
//       alert(desStr);

        var des=''
        if($("#ckDes1").is(':checked')){
            des+=$("#ckDes1").val()+" "
        }
        if($("#ckDes2").is(':checked')){
            des+=$("#ckDes2").val()+" "
        }
        if($("#ckDes3").is(':checked')){
            des+=$("#ckDes3").val()+" "
        }
        if($("#ckDes4").is(':checked')){
            des+=$("#ckDes4").val()+" "
        }
        if($("#ckDes5").is(':checked')){
            des+=$("#ckDes5").val()+" "
        }
        if($("#ckDes6").is(':checked')){
            des+=$("#ckDes6").val()+" "
        }
        if($("#ckDes7").is(':checked')){
            des+=$("#ckDes7").val()+" "
        }
        if(des==''){
            alert("请选择病情描述");
            return;
        }
        param.description = des.substr(0,des.length-1);
//        alert(param.description);
        if (param.name == "") {
            alert("请输入姓名");
            return;
        }
        if (param.age == "") {
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
        }
        if (param.phone == "") {
            alert("请输入手机号码");
            return;
        }
        if (!(/^(13|14|15|16|18|19)\d{9}$/).test(param.phone)) {
            alert("手机号码格式不正确")
            return false;
        }
        if (param.id_card == "") {
            alert("请输入身份证号码");
            return;
        }
        if (!(/(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/).test(param.id_card)) {
            alert("身份证号码输入有误");
            return;
        }
        if (document.getElementById("rdType").checked != true && $.trim($("#txtOrderCode").val()) == "") {
            alert("请输入转诊号");
            return;
        }
        if ($("#ddlCenter").val() == "") {
            alert("请选择治疗中心");
            return;
        }
        if ($("#ddlDoctor").val() == "") {
            alert("请选择医生");
            return;
        }
        if($("input[type='checkbox']").is(':checked')==false){
            alert("请同意服务条款");
            return;
        }
        param.doctorId=$("#ddlDoctor").val();
        $("#spanEnter").html("正在提交请求...");

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

    function ckType(m) {
        if (document.getElementById("rdType").checked == true) {
            $("#divOrderCode").hide();
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

    function loadDoctor() {
        alert('asdf');
    }

    $("#ddlCenter").bind("change", function (evt) {
        if ($(this).val() == "")return;
        $("#ddlDoctor").empty();
        $.ajax({
            type: "POST",
            url: '<?php echo( $postUrl) ?>',
            data: {'postType': 'loadDoctor', 'centerId': $(this).val()},
            success: function (data) {
                $.each(data, function (index, row) {
                    $("<option></option>").text(row.name).val(row.id).appendTo("#ddlDoctor")
                })
            }
        })
    })


</script>