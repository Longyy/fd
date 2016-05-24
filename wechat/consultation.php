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



?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>咨询表</title>
    <link rel="stylesheet" href="css/style2.css">
    <script language="JavaScript" src="js/jquery-1.11.0.min.js" type="text/javascript"></script>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
</head>
<body>
<div class="con">
    <div class="ect-bg">
        <header class="ect-header ect-margin-tb ect-margin-lr text-center icon-write">
            <a href="javascript:history.go(-1)" class="pull-left ect-icon ect-icon1 ect-icon-history">&nbsp;</a> <span>咨询表</span>
            <a href="javascript:;" class="pull-right ect-icon ect-icon1 ect-icon-mune"></a></header>
    </div>
    <section class="na-zixun zx-consignee">
        <!-- <span class="zx-title text-center">欢迎使用红胎记咨询</span> -->
        <div class="na-zixun-content">
            <ul>
                <li>
                    <div class="input-text">
                        <strong><img src="images/name.png"/></strong><b>姓名</b><span>
                            <input id="txtName" type="text" datatype="*" class="inputBg" nullmsg="请填写信息！" placeholder="请输入姓名"></span>
                    </div>
                </li>
                <li>
                    <div class="input-text">
                        <strong><img src="images/gender.png"/></strong><b>性别</b><span>
                            <input id="rdSex" checked name="sex" type="radio"/>男 &nbsp;&nbsp;
                            <input type="radio" name="sex"/>女</span>
                    </div>
                </li>
                <li>
                    <div class="input-text"><strong><img src="images/map.gif"/></strong><b>所在地区</b>
                        &nbsp;
                        <select id="province" onchange="selectProvince(this)" style="height: 30px; width: 30%">
                            <option value="">--省--</option>
                            <?php
                            while ($row = $provinceResult->fetch_assoc()) {
                                ?>
                                <option value="<?php echo($row['provinceID']) ?>"><?php echo($row['province']) ?></option>
                            <?php } ?>
                        </select>
                        <select id="city" style="height: 30px; width: 30%">
                            <option value="">--市--</option>
                        </select>
                    </div>
                </li>
                <li>
                    <div class="input-text"><strong><img src="images/age.png"/></strong><b>出生日期</b>
                        &nbsp;
                        <select id="year" style="height: 30px; width: 70px;">
                            <option>年</option>
                            <?php
                            for ($i=1949; $i <2017; $i++) {
                                echo "<option value='$i'>$i</option>";
                            }
                            ?>
                        </select>
                        <select id="month" style="height: 30px; width: 60px;">
                            <option>月</option>
                            <?php
                            for ($i=1; $i <13; $i++) {
                                echo "<option value='$i'>$i</option>";
                            }
                            ?>
                        </select>
                        <select id="day" style="height: 30px; width: 60px;">
                            <option>日</option>
                            <?php
                            for ($i=1; $i <32; $i++) {
                                echo "<option value='$i'>$i</option>";
                            }
                            ?>
                        </select>

                    </div>
                </li>
               <!--  <li>
                    <div class="input-text">
                        <strong><img src="images/age.png"/></strong><b>年龄</b><span>
                            <input id="txtAge" onkeyup="this.value=this.value.replace(/\D/g,'')"
                                   onafterpaste="this.value=this.value.replace(/\D/g,'')" type="text" datatype="*" class="inputBg" nullmsg="请填写信息！"></span>
                    </div>
                </li> -->
                <li>
                    <div class="input-text">
                        <strong><img src="images/phone.png"/></strong><b>手机号码</b><span>
                            <input id="txtPhone" onkeyup="this.value=this.value.replace(/\D/g,'')"
                                   onafterpaste="this.value=this.value.replace(/\D/g,'')" type="text" datatype="*" class="inputBg" nullmsg="请填写信息！" placeholder="请输入手机号"></span>
                    </div>
                </li>
                <!-- <li>
                    <div class="input-text">
                        <strong><img src="images/idcard.png"/></strong><b>身份证号</b><span>
                            <input id="txtIdCard" type="text" datatype="*" class="inputBg" nullmsg="请填写信息！"></span>
                    </div>
                </li> -->
                <!-- <li class="multiple" style="height: 8em">
                    <div class="input-text">
                        <strong><img src="images/message.png" width="23"/></strong><b class="text-center">信息来源<br/><font size="1">(可多选)</font></b><span>
			<div class="check-item">
                <input type="checkbox" id="ckRes1" value="QQ群/QQ"/>
                QQ
            </div>
			<div class="check-item">
                <input type="checkbox" id="ckRes2" value="微信"/>
                微信
            </div>
			<div class="check-item">
                <input type="checkbox" id="ckRes3" value="媒体"/>
                媒体
            </div>
			<div class="check-item">
                <input type="checkbox" id="ckRes4" value="患友介绍"/>
                患友介绍
            </div>
			<div class="check-item">
                <input type="checkbox" id="ckRes5" value="医生推荐"/>
                医生推荐
            </div>
			<div class="check-item">
                <input type="checkbox" id="ckRes6" value="其他"/>
                其他
            </div>
			</span></div>
                </li> -->
                <!-- <li>
                    <div class="input-text"><strong><img src="images/zzd.png"/></strong><b>求诊类型</b>
                        <input type="radio" id="rdType" onchange="ckType(this)" checked name="conType"> 预约
                        <input type="radio" name="conType" onchange="ckType(this)"> 转诊
                    </div>
                </li> -->
               <!--  <div id="divOrderCode" style="display: none">
                    <li>
                        <label for="exampleInputPassword1">转诊号</label>
                        <input type="text" class="form-control-ext" id="txtOrderCode" placeholder="请输入预约号">
                    </li>
                </div> -->
               <!--  <li>
                    <div class="input-text"><strong><img src="images/lczd.png"/></strong><b>临床诊断</b><span>

				 <select id="ddlDiagnose" style="height: 30px;">
                     <option value="血管瘤">血管瘤</option>
                     <option value="鲜红斑痣">鲜红斑痣</option>
                     <option value="其他">其他</option>
                 </select>
			</span></div>
                </li> -->
            <li class="multiple" style="height: 7em">
                    <div class="input-text">
                        <strong><img src="images/name.png" width="23"/></strong><b class="text-center">患病部位<br/><font size="1">(可多选)</font></b><span>
			<div class="check-item">
                <input type="checkbox" id="ckDes1" value="面部"/>
                面部
            </div>
			<div class="check-item">
                <input type="checkbox" id="ckDes2" value="颈部"/>
                颈部
            </div>
            <div class="check-item">
                <input type="checkbox" id="ckDes3" value="下肢"/>
                上下肢
            </div>
			<div class="check-item">
                <input type="checkbox" id="ckDes4" value="胸"/>
                胸
            </div>
			<div class="check-item">
                <input type="checkbox" id="ckDes5" value="背"/>
                背
            </div>
			<div class="check-item">
                <input type="checkbox" id="ckDes6" value="其他部位"/>
                其他部位
            </div>
			</span></div>
                </li>





            <li style="height: 6em">
                 <div class="input-text"><strong><img src="images/zls.png"/></strong>
                        <b>图片上传</b>
                        <span>
                            <div class="check-item">
                                <a href="javascript:void" onclick="chooseImage()" >上传照片</a>
                            </div>

                        </span>
                </div>
                <div id="divUploadImg">
<!--                    <img src="../Uploads/User_cert/pic/201603180040008930.jpg" height="50px"  width="50px;" style="float: left; margin-top: 2em">-->
<!--                    <img src="../Uploads/User_cert/pic/201603180040008930.jpg" height="50px"  width="50px;" style="float: left; margin-top: 2em">-->
<!--                    <img src="../Uploads/User_cert/pic/201603180040008930.jpg" height="50px"  width="50px;" style="float: left; margin-top: 2em">-->
                </div>


            </li>





                <li style="height: 2em">
                    <div class="input-text"><strong><img src="images/zls.png"/></strong>
                        <b>治疗史</b><span>
			<div class="check-item">
                <input type="checkbox" id="ckBef1" value="脉冲染料激光"/>
                脉冲染料激光
            </div>
			<!-- <div class="check-item">
                <input type="checkbox" id="ckBef2" value="光动力"/>
                光动力
            </div> -->
			<div class="check-item">
                <input type="checkbox" id="ckBef3" value="其他"/>
                其他
            </div>
			</span></div>
                </li>
                <li class="multiple" style="height: 9em">
                    <div class="input-text"><strong><img src="images/zlyq.png"/></strong><b>找医生</b>
			<span>
				 <!-- <select id="ddlRequire" style="width: 45%;height: 30px;">
                     <option value="">治疗要求</option>
                     <option value="就近治疗">就近治疗</option>
                     <option value="异地治疗">异地治疗</option>
                 </select> -->
				 <select id="ddlProvince" style="width: 100%;height: 30px; ">
                     <option value="">省份</option>
                     <?php
                     while ($row = $centerProvinceResult->fetch_assoc()) {
                         ?>
                         <option value="<?php echo($row['province_id']) ?>"><?php echo($row['province_name']) ?></option>
                     <?php } ?>
                 </select>
                 <br/>
				 <select id="ddlCenter" style="width: 100%;height: 30px; margin-top:10px; ">
                    <option value="">治疗中心</option>
                    <?php
                     while ($row = $centerResult->fetch_assoc()) {
                         ?>
                         <option value="<?php echo($row['id']) ?>"><?php echo($row['name']) ?></option>
                     <?php } ?>
                 </select>
                  <br/>
				 <select id="ddlDoctor" style="width: 100%;height: 30px; margin-top:10px;">
                     <option value="">医生名字</option>
                     <option value="">医生名字</option>
                     <option value="">医生名字</option>
                     <option value="">医生名字</option>
                 </select>
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


    wx.config({
        debug: false,
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


//                $(obj).attr('src', localIds);
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
                            $("#divUploadImg").append("<img data-imgid=\""+data+"\"  src=\""+localIds+"\" height=\"50px\"  width=\"50px;\" style=\"float: left; margin-top: 2em\">");
                        });
                        $(obj).next().val(serverId); // 把上传成功后获取的值附上
                    }
                });
            }
        });
    }

    function submit() {
//        $("#divUploadImg").append("<img src=\"../Uploads/User_cert/pic/201603180040008930.jpg\" height=\"50px\"  width=\"50px;\" style=\"float: left; margin-top: 2em\">");
        var param = {
            name: $("#txtName").val(),
            sex: document.getElementById("rdSex").checked == true ? 1 : 0,
            city:$("#city").val(),
            //age: $("#txtAge").val(),
//            city:$("#dlCity").val(),
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
        //出生年份
        var year = $("#year").val();
        var month = $("#month").val();
        var day = $("#day").val();
        param.birthday = year+month+day;
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
//        var res = ''
//        if ($("#ckRes1").is(':checked')) {
//            res += $("#ckRes1").val() + " "
//        }
//        if ($("#ckRes2").is(':checked')) {
//            res += $("#ckRes2").val() + " "
//        }
//        if ($("#ckRes3").is(':checked')) {
//            res += $("#ckRes3").val() + " "
//        }
//        if ($("#ckRes4").is(':checked')) {
//            res += $("#ckRes4").val() + " "
//        }
//        if ($("#ckRes5").is(':checked')) {
//            res += $("#ckRes5").val() + " "
//        }
//        if ($("#ckRes6").is(':checked')) {
//            res += $("#ckRes6").val() + " "
//        }
//        if ($("#ckRes7").is(':checked')) {
//            res += $("#ckRes7").val() + " "
//        }
//        if (res == '') {
//            alert("请选择信息来源");
//            return;
//        }
//        param.resource = res.substr(0, res.length - 1);

        //治疗史
        var bef = ''
        if ($("#ckBef1").is(':checked')) {
            bef += $("#ckBef1").val() + " "
        }
      /*  if ($("#ckBef2").is(':checked')) {
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
       /* if (param.age == "") {
            alert("请输入年龄");
            return;
        }*/
       /* if (!(/(^[0-9]*$)/).test(param.age)) {
            alert("年龄输入不正确")
            return false;
        }*/

       /* if (param.age > 120) {
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
       /* if (param.id_card == "") {
            alert("请输入身份证号码");
            return;
        }*/
      /*  if (!(/(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/).test(param.id_card)) {
            alert("身份证号码输入有误");
            return;
        }
        if (document.getElementById("rdType").checked != true && $.trim($("#txtOrderCode").val()) == "") {
            alert("请输入转诊号");
            return;
        }*/
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

        var doctorId=$("#ddlDoctor").val();
        $.ajax({
            type: "POST",
            url: '<?php echo( $postUrl) ?>',
            data: param,
            success: function (data) {
                $("#spanEnter").html("提交");
                alert(data.rt);
                if (data.st == 1) {
                   // window.location.href = '<?php echo( $pageUrl)?>doctorTime.php?doctorId='+doctorId+'&id='+data.conId;
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

</script>