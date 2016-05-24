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
//是否登录注册过，没有进行跳转到求诊页
$openId = $result["openid"];
$sql = "SELECT * FROM `cms_wechat_user` WHERE role_id=4 and open_id='" . $openId . "'";
$result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
$rowCount = $result->num_rows;
if ($rowCount == 0) {
    $url = $pageUrl . "login.php?roleType=4&openId=" . $openId;
    echo "<script>window.location.href='" . $url . "'</script>";
}
$result = $result->fetch_assoc();


$sql = "SELECT t1.id,t1.order_code,t1.handle_type, t2.name as doctor_name,t1.name,t1.sex FROM cms_consultation t1 left join cms_doctor t2 on t1.doctor_id=t2.id
WHERE t1.open_id='$openId'";
//echo($sql);
$consultationResult = mysqli_query($connection, $sql) or die(mysqli_error($connection));


$sql = "SELECT t1.id,t1.order_code,t1.handle_type, t2.name as doctor_name,t1.name,t1.sex FROM cms_consultation t1 left join cms_doctor t2 on t1.doctor_id=t2.id
WHERE t1.open_id='$openId'";
$consultationNameResult = mysqli_query($connection, $sql) or die(mysqli_error($connection));
$nameResult = $consultationNameResult->fetch_assoc();

//查询医生信息
//$accessToken = array
//(
//    'appid'      => $signPackage["appId"],
//    'secret'     => $signPackage['appSecret'],
//    'code'       => $_REQUEST["code"],
//    'grant_type' => 'authorization_code'
//);
////获取openid
//$accessTokenHttp = "https://api.weixin.qq.com/sns/oauth2/access_token?" . http_build_query($accessToken);
//include("httpGet.php");
//$result = json_decode(httpGet($accessTokenHttp), true);
//
////换取省份数据
//$sql = "select * from cms_province";
//$provinceResult = mysqli_query($connection, $sql) or die(mysqli_error($connection));
//
////获取医疗中心省份
//$sql = "select t1.province_id,t2.province as province_name from cms_medical_center t1 left join cms_province t2 on t1.province_id=t2.provinceID group by t1.province_id,t2.province having province_name is not null";
//$centerProvinceResult = mysqli_query($connection, $sql) or die(mysqli_error($connection));
//获取医疗中心
//获取药品
$sql = "SELECT * FROM `cms_app` where id=26";
$app = mysqli_query($connection, $sql) or die(mysqli_error($connection));
//获取药店
$sql = "SELECT * FROM `cms_shop`";
$shop = mysqli_query($connection, $sql) or die(mysqli_error($connection));

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title>药店下单</title>
<link rel="stylesheet" href="css/styleme.css">
 <script language="JavaScript" src="js/jquery-1.11.0.min.js" type="text/javascript"></script>
<script>
var shuliang;
var ha1;
var he1;
function jia(){
	shuliang=document.getElementById("count");
	shuliang.value=parseInt(shuliang.value)+1;
	}
function jian(){
	shuliang=document.getElementById("count");
	shuliang.value=parseInt(shuliang.value)-1;
	if(shuliang.value<0){
		 shuliang.value=0;
		}
	}
function ha(){
	ha1=document.getElementById("ha");
	he1=document.getElementById("he");
	ha1.style.display="block";
	he1.style.display="none";
	}
function he(){
	ha1=document.getElementById("ha");
	he1=document.getElementById("he");
	ha1.style.display="none";
	he1.style.display="block";
	}				
</script>
<script type="text/javascript">
  $(function(){
        //运送方式
        $('input:radio[name="devi"]').change(function(){
            if($(this).val()=="1"){
                //alert("11");
                $(".dev2").css("display","none")
                $(".dev1").css("display","block")
                $("#dname").html("取 &nbsp;件&nbsp;人:&nbsp;")

            }else{
                //alert("22");
                $(".dev1").css("display","none")
                $(".dev2").css("display","block")
                $("#dname").html("收 &nbsp;件&nbsp;人:&nbsp;")

            }

        });
        $('#shop').change(function(){
            var id=$(this).val();
            var ref=$('#shop option:selected').attr('id');
            if(ref!=""&&ref!=undefined){
                var info=ref.split('_');
                $('#tel').html(info[0]);
                $('#saddress').html(info[1]);
            }else{
                $('#tel').html('');
                $('#saddress').html('');
            }


        });

    });
    function submit(){
        var data={
            cname:$("#cname").val(),
            c_id:$("#c_id").val(),
            ctype:$("#ctype").val(),
            count:$("#count").val(),
            devi:$('input:radio[name="devi"]:checked').val(),
            shop:$(".shop").find("option:selected").text(),
            address:$("#address").val(),
            receive:$("#receive").val(),
            phone:$("#phone").val(),
            doct:$("#doct").val(),
            d_id:$("#d_id").val(),
            type:2,


        }
        if(data.count<=0){
            alert("请选择药品数量!");
            return false;
        }
        if(data.receive==""){
            alert("姓名不能为空!");
            return false
        }
        if(data.phone==""){
            alert("联系电话不能为空!");
            return false;
        }
        if(data.doct==""){
            alert("医生不能为空!");
            return false;
        }
        if(data.devi=="1"){
            if(data.shop==""||data.shop=="请选择药店"){
                alert("药店不能为空!");
                return false;
            }
        }

        $.ajax({
            type: "POST",
            url: '../admin.php?m=Wx&a=orderdoct',
            data: data,
            success: function (data) {
                $("#spanEnter").html("提交");
                if (data == "1") {
                    alert("下单成功!");
                    WeixinJSBridge.invoke('closeWindow', {}, function (res) {

                    });
                }
            }
        })

    }

    function exit(){
        WeixinJSBridge.invoke('closeWindow',{},function(res){

            //alert(res.err_msg);

        });
    }

</script>
</head>
<body>
<div class="con">
	<div class="drug-bg">
		<header class="ect-header ect-margin-tb ect-margin-lr text-center icon-write"> <a href="javascript:history.go(-1)" class="pull-left ect-icon ect-icon1 ect-icon-history">&nbsp;</a> <span>代下单</span> <a href="javascript:;" class="pull-right ect-icon ect-icon1 ect-icon-mune"></a></header>
	</div>
	<div class="drug-main">
		<p class="text-center"><img src="images/drug_img.jpg" width="150"/></p>
		<div class="drug-img text-center ect-margin-tb"><img />药盒图片一张</div>
        <?php $row = $app->fetch_assoc()?>
		<dl class="drug-info">
            <input type="hidden" name="cname" id="cname" value="<?php echo $row['title']?>">
			<dt>药品名：复美达(注射用海姆泊芬)</dt>
            <dt>国药准字：123</dt>
            <input type="hidden" name="c_id" id="c_id" value="<?php echo $row['id']?>">
            <input type="hidden" name="ctype" id="ctype" value="<?php echo $row['info']?>">
			<dt>规格：100mg/瓶</dt>
			<dt><span class="pull-left">数量：</span><div class="input-group pull-left">
				<span class="input-group-addon pull-left plus text-center" onClick="jia()">+</span>
				<input type="text" class="form-contro pull-left" name="count" value="1" id="count" style="width: 1.1em">
				<span class="input-group-addon sub pull-left text-center" onClick="jian()">-</span>
				</div>&nbsp;盒</dt>
		</dl>
		<div class="clear shipping">
			<div class="pull-left">配送方式：</div>
			<div class="pull-left">
           
			<label><input checked="checked" name="devi"  type="radio" value="" onClick="ha()"/>去药店自取<br/></label>
			<label><input name="devi"  type="radio" value="" onClick="he()"/>快递到付</label>
           
			</div>
		</div>
		<ul class="drug-txt ect-margin-tb" id="ha">
			<li><label>自取店名：</label>
            <select style="width:83%; height:30px;" name="shop" id="shop">
                <option>请选自取地址</option>
                <?php
                $shoparr=array();
                while ($row = $shop->fetch_assoc()) {
                    $shoparr[$row['id']]=$row;
                    ?>
                    <option value="<?php echo $row['id'];?>" id="<?php echo $row['tel'].'_'.$row['address']?>"><?php echo $row['name'];?></option>

                <?php
                };
                ?>
            </select></li>
            <li><label>药店电话：</label><input type="text" id="tel" class="drug-btn" placeholder="药店电话"/></li>
            <li><label>取件人：</label><input type="text" class="drug-btn" name="receive" id="receive" placeholder="请填写患者姓名"/></li>
            <li><label>电话：</label><input type="text" name="phone" id="phone" class="drug-btn" placeholder="请填写11位手机号码"/></li>
            <li><label>医生：</label><input type="text" name="doct" id="doct" class="drug-btn" placeholder="医生名字"/></li>
            <input type="hidden" name="d_id" id="d_id" value="0">
        </ul>
        <ul class="drug-txt ect-margin-tb" id="he" style="display:none"> 
			<li><label>收件人：</label><input type="text" name="receive" id="receive" class="drug-btn" placeholder="请填写患者姓名"/></li>
			<li><label>电话：</label><input type="text" name="phone" id="phone" class="drug-btn" placeholder="请填写11位手机号码"/></li>
            <li><label>收件地址：</label><input type="text" name="address" id="address" class="drug-btn" placeholder="请填写取件地址"/></li>
           <li><label>医生：</label><input type="text" class="drug-btn" name="doct" id="doct" placeholder="医生名字"/></li>
		</ul>
		<p class="ect-padding text-center">
	        <input type="button" class="btn_blue treat-btn ect-margin-lr" onclick="submit()" value="提交">
            <input type="button" class="btn_grey treat-btn ect-margin-lr" onclick="exit()" value="取消">
		</p>
	</div>
	
	
</div>

</body>
</html>

