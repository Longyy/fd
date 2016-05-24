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

//换取省份数据
$sql = "select * from cms_province";
$provinceResult = mysqli_query($connection, $sql) or die(mysqli_error($connection));

//获取医疗中心省份
$sql = "select t1.province_id,t2.province as province_name from cms_medical_center t1 left join cms_province t2 on t1.province_id=t2.provinceID group by t1.province_id,t2.province having province_name is not null";
$centerProvinceResult = mysqli_query($connection, $sql) or die(mysqli_error($connection));
//获取医疗中心
$sql = "SELECT * FROM`cms_medical_center`";
$centerResult = mysqli_query($connection, $sql) or die(mysqli_error($connection));
//查询医生信息
$sql = "select * from cms_doctor";
$doctorInfo = mysqli_query($connection, $sql) or die(mysqli_error($connection));
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title>咨询表</title>
<link rel="stylesheet" href="css/stylea.css">
<script src="js/prefixfree.min.js"></script>
<script src='js/jquery.js'></script>
</head>
<body>
<div class="con">
	<div class="ect-bg">
		<header class="ect-header ect-margin-tb ect-margin-lr text-center icon-write"> <a href="javascript:history.go(-1)" class="pull-left ect-icon ect-icon1 ect-icon-history">&nbsp;</a> <span>咨询表</span> <a href="javascript:;" class="pull-right ect-icon ect-icon1 ect-icon-mune"></a></header>
	</div>
	<section class="na-zixun zx-consignee">
		<span class="zx-title text-center">欢迎使用红胎记咨询</span>
		<div class="na-zixun-content">
		<ul>
   		<li>
    		<div class="input-text"><strong><img src="images/name.png"/></strong><b>姓名</b><span><input type="text" datatype="*" id="txtName" class="inputBg" nullmsg="请填写信息！"></span></div>
        </li>
        <li>
    		<div class="input-text"><strong><img src="images/gender.png"/></strong><b>性别</b><span><input type="radio" name="sex" checked="checked" id="rdSex" />男&nbsp;<input type="radio" name="sex" />女</span></div>
        </li>
		<li>
    		<div class="input-text"><strong><img src="images/map.gif"/></strong><b>所在地区</b>
			<span>
				<div id="province" class="wrapper-dropdown" style="width:20%">省
					<ul class="dropdown">
					<?php
                    	while ($row = $provinceResult->fetch_assoc()) {
                    ?>
						<li value="<?php echo($row['provinceID']) ?>"><i class="icon-user"><?php echo($row['province']) ?></i></li>
					<?php } ?>
					</ul>
				</div>
				<div id="city" class="wrapper-dropdown" >市
					<ul class="dropdown">
						<?php
                            for ($i=2000; $i <5000 ; $i++) { 
                                echo "<li class='cityval'>太原市</li>";
                            }
                        ?>
					</ul>
				</div>
			</span>
			</div>
        </li>
		<li>
    		<div class="input-text"><strong><img src="images/age.png"/></strong><b>出生</b>
            <span>
				<div id="dd1" class="wrapper-dropdown" style="width:20%">年份
					<ul class="dropdown">
                        <?php
                            for ($i=2000; $i <5000 ; $i++) { 
                                echo "<li class='birthyear'>$i</li>";
                            }
                        ?>
					</ul>
				</div>
				<div id="lczd" class="wrapper-dropdown" style="width:10%">1月
					<ul class="dropdown" style="width:100%;">
						<?php
                            for ($i=1; $i <=12 ; $i++) { 
                                echo "<li class='birthmonth'>$i</li>";
                            }
                        ?>
					</ul>
				</div>
                <div id="yuyue" class="wrapper-dropdown" style="width:10%">1日
					<ul class="dropdown">
						<?php
                            for ($i=1; $i <=31 ; $i++) { 
                                echo "<li class='birthday'>$i</li>";
                            }
                        ?>
					</ul>
				</div>
			</span>
            </div>
        </li>
		<li>
    		<div class="input-text"><strong><img src="images/phone.png"/></strong><b>手机号码</b><span><input type="text" id="txtPhone" datatype="*" class="inputBg" nullmsg="请填写信息！"></span></div>
        </li>
		<li class="multiple" style="height:6em;">
    		<div class="input-text"><strong><img src="images/message.png" width="23"/></strong><b class="text-center">信息来源<br/><font size="1">(可多选)</font></b><span>
			<div class="check-item"><div class="check-box checkedBox"><i><input type="checkbox" id="ckRes1" /></i></div>QQ群/QQ </div>
			<div class="check-item"><div class="check-box "><i><input type="checkbox" id="ckRes2" /></i></div>微信</div>
			<div class="check-item"><div class="check-box "><i><input type="checkbox" id="ckRes3"/></i></div>媒体报</div>
			<div class="check-item"><div class="check-box checkedBox"><i><input type="checkbox" id="ckRes4"/></i></div>患友介绍</div>
			<div class="check-item"><div class="check-box "><i><input type="checkbox" id="ckRes5"/></i></div>医生推荐</div>
			<div class="check-item"><div class="check-box "><i><input type="checkbox" id="ckRes6"/></i></div>其他</div>
			</span></div>
        </li>
		
		<li class="multiple">
    		<div class="input-text"><strong><img src="images/name.png"/></strong><b>病情描述</b><span>
			<div class="check-item"><div class="check-box checkedBox"><i><input type="checkbox" id="ckDes1" /></i></div>面部 </div>
			<div class="check-item"><div class="check-box "><i><input type="checkbox" id="ckDes2"/></i></div>颈脖</div>
			<div class="check-item"><div class="check-box "><i><input type="checkbox" id="ckDes3"/></i></div>上肢</div>
			<div class="check-item"><div class="check-box checkedBox"><i><input type="checkbox" id="ckDes4"/></i></div>下肢</div>
			<div class="check-item"><div class="check-box "><i><input type="checkbox" id="ckDes5"/></i></div>胸背</div>
			<div class="check-item"><div class="check-box "><i><input type="checkbox" id="ckDes6"/></i></div>其他</div>
			</span></div>
        </li>
		<li>
    		<div class="input-text"><strong><img src="images/zls.png"/></strong>
			<b>治疗史</b><span>
			<div class="check-item"><div class="check-box checkedBox"><i><input type="checkbox" id="ckBef1"/></i></div>脉冲染料激光</div> 
			<div class="check-item"><div class="check-box "><i><input type="checkbox" id="ckBef3" /></i></div>其他</div>
			</span></div>
        </li>
		<li class="multiple" style="height:7em">
            <div class="input-text"><strong><img src="images/zlyq.png"/></strong><b>治疗要求</b>
            <span>
                <div id="dd2" class="wrapper-dropdown" style="width:60%">省份
                    <ul class="dropdown">
                        
                       <?php
                     while ($row = $centerProvinceResult->fetch_assoc()) {
                         ?>
                         <li value="<?php echo($row['province_id']) ?>"><i class="icon-user"></i><?php echo($row['province_name']) ?></li>
                     <?php } ?>
                    </ul>
                </div>
                <div id="dd3" class="wrapper-dropdown" style="width:60%">治疗中心名称
                    <ul class="dropdown">
                         <?php
                            while ($row = $centerResult->fetch_assoc()) {
                         ?>
                         <li value="<?php echo($row['id']) ?>"><i class="icon-user"></i><?php echo($row['name']) ?></li>
                     <?php } ?>
                    </ul>
                </div>
                <div id="dd4" class="wrapper-dropdown" style="width:60%">医生姓名
                    <ul class="dropdown">
                        <?php
                            while ($row = $doctorInfo->fetch_assoc()) {
                         ?>
                         <li value="<?php echo($row['id']) ?>"><i class="icon-user"></i><?php echo($row['name']) ?></li>
                     <?php } ?>
                    </ul>
                </div>
            </span>
            </div>
        </li>
        </ul>
		<p class="ect-padding-lr ect-padding-tb refer-font"><input checked="checked" type="radio">同意红胎记咨询服务条款内容</p>
		<p class="ect-padding text-center">
		<input type="button" class="btn_blue treat-btn ect-margin-lr" onclick="submit()" value="提交">
		<input type="button" class="btn_grey treat-btn ect-margin-lr" onclick="exit()" value="取消">
		</p>
		</div>
		<br/>
		<br/>
		
	</section>
	
</div>

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
            city:$("#city").val(),
            //age: $("#txtAge").val(),
			//city:$("#dlCity").val(),
            phone: $("#txtPhone").val(),
            //id_card: $("#txtIdCard").val(),
            //orderType: document.getElementById("rdType").checked == true ? 1 : 0,
            //order_code: $("#txtOrderCode").val(),
            //diagnose: $("#ddlDiagnose").val(),
//            description: $("#ddlDescription").val(),
//            before_treat: $("#ddlBeforeTreat").val(),
            demand: $("#ddlRequire").val(),
            latitude: latitude,
            longitude: longitude,
            openId: openId,
            postType: "consulation"
//            resource: $("#ddlResource").val()
        }
        //出生数据
        /*var birth  = ''
        $(".birthyear").click(function(){
            birthyear = $(this).val();
        });
        $(".birthmonth").click(function(){
            birthmonth = $(this).val();
        });
        $(".birthday").click(function(){
            birthday = $(this).val();
        });
        param.date =birthyear.concat(birthmonth, birthday);*/
        //病情描述
        var des = ''
        if ($("#ckDes1").is(':checked')) {
            des += $("#ckDes1").val() + " "
        }
        if ($("#ckDes2").is(':checked')) {
            des += $("#ckDes2").val() + " "
        }
        if ($("#ckDes3").is(':checked')) {
            des += $("#ckDes3").val() + " "
        }
        if ($("#ckDes4").is(':checked')) {
            des += $("#ckDes4").val() + " "
        }
        if ($("#ckDes5").is(':checked')) {
            des += $("#ckDes5").val() + " "
        }
        if ($("#ckDes6").is(':checked')) {
            des += $("#ckDes6").val() + " "
        }
        if ($("#ckDes7").is(':checked')) {
            des += $("#ckDes7").val() + " "
        }
        if (des == '') {
            alert("请选择病情描述");
            return;
        }
        param.description = des.substr(0, des.length - 1);

        //信息来源
        var res = ''
        if ($("#ckRes1").is(':checked')) {
            res += $("#ckRes1").val() + " "
        }
        if ($("#ckRes2").is(':checked')) {
            res += $("#ckRes2").val() + " "
        }
        if ($("#ckRes3").is(':checked')) {
            res += $("#ckRes3").val() + " "
        }
        if ($("#ckRes4").is(':checked')) {
            res += $("#ckRes4").val() + " "
        }
        if ($("#ckRes5").is(':checked')) {
            res += $("#ckRes5").val() + " "
        }
        if ($("#ckRes6").is(':checked')) {
            res += $("#ckRes6").val() + " "
        }
        if ($("#ckRes7").is(':checked')) {
            res += $("#ckRes7").val() + " "
        }
        if (res == '') {
            alert("请选择信息来源");
            return;
        }
        param.resource = res.substr(0, res.length - 1);

        //治疗史
        var bef = ''
        if ($("#ckBef1").is(':checked')) {
            bef += $("#ckBef1").val() + " "
        }
       /* if ($("#ckBef2").is(':checked')) {
            bef += $("#ckBef2").val() + " "
        }*/
        if ($("#ckBef3").is(':checked')) {
            bef += $("#ckBef3").val() + " "
        }

        if (bef == '') {
            alert("请选择治疗史");
            return;
        }
        param.before_treat = bef.substr(0, bef.length - 1);
//        alert(param.description);
        if (param.name == "") {
            alert("请输入姓名");
            return;
        }
        if (param.phone == "") {
            alert("请输入手机号码");
            return;
        }
        if (!(/^(13|14|15|16|18|19)\d{9}$/).test(param.phone)) {
            alert("手机号码格式不正确")
            return false;
        }
        if ($("#ddlCenter").val() == "") {
            alert("请选择治疗中心");
            return;
        }
        if (param.demand == "") {
            alert("请选择治疗要求");
            return;
        }
        if ($("#ddlDoctor").val() == "") {
            alert("请选择医生");
            return;
        }
        if ($("input[type='checkbox']").is(':checked') == false) {
            alert("请同意服务条款");
            return;
        }
        param.doctorId = $("#ddlDoctor").val();
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
        $("<option></option>").text("--请选择--").val("").appendTo("#ddlCenter")
        $("#ddlDoctor").empty();
        $("<option></option>").text("--请选择--").val("").appendTo("#ddlDoctor")
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

    function exit(){
        WeixinJSBridge.invoke('closeWindow',{},function(res){

            //alert(res.err_msg);

        });
    }














$.fn.toggleCheckbox = function () {
    this.attr('checked', !this.attr('checked'));
}
$('.check-box').on('click', function () {
    $(this).find(':checkbox').toggleCheckbox();
    $(this).toggleClass('checkedBox');
});

		
		function DropDown(el) {
				this.dd = el;
				this.initEvents();
		}
		DropDown.prototype = {
			initEvents : function() {
					var obj = this;

					obj.dd.on('click', function(event){
						$(this).toggleClass('active');
						event.stopPropagation();
					});	
			}
		}

			$(function() {

				var dd1 = new DropDown( $('#dd1') );
				var dd2 = new DropDown( $('#dd2') );
				var dd3 = new DropDown( $('#dd3') );
				var dd4 = new DropDown( $('#dd4') );
				var province = new DropDown( $('#province') );
				var city = new DropDown( $('#city') );
				var yuyue = new DropDown( $('#yuyue') );
				var lczd = new DropDown( $('#lczd') );
				
				$(document).click(function() {
					// all dropdowns
					$('#dd1').removeClass('active');
					$('#dd2').removeClass('active');
					$('#dd3').removeClass('active');
					$('#dd4').removeClass('active');
					$('#province').removeClass('active');
					$('#city').removeClass('active');
					$('#yuyue').removeClass('yuyue');
					$('#lczd').removeClass('lczd');
				});

			});
</script>
