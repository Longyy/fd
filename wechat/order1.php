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



include("connection.php");
//是否登录注册过，没有进行跳转
$openId=$_GET["openid"];
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
$sql = "SELECT t1.*,t3.name as center_name FROM cms_doctor t1 left join cms_medical_center_doctor t2 on t1.id=t2.doctor_id left join cms_medical_center t3 on t2.medical_center_id=t3.id where t1.id=$doctorId";
$doctorResult = mysqli_query($connection, $sql) or die(mysqli_error($connection));
$doctorInfo=$doctorResult->fetch_assoc();
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
    <title>代下单</title>
    <link rel="stylesheet" href="css/style2.css">
    <script language="JavaScript" src="js/jquery-1.11.0.min.js" type="text/javascript"></script>
</head>
<body>
<script>
    $(function(){
        $(".add").click(function() {
            // $(this).prev() 就是当前元素的前一个元素，即 text_box
            $(this).prev().val(parseInt($(this).prev().val()) + 1);
        });

        $(".min").click(function() {
            // $(this).next() 就是当前元素的下一个元素，即 text_box
            if(parseInt($(this).next().val())>0){
                $(this).next().val(parseInt($(this).next().val()) - 1);

            }
        });
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
            type:1,


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
<div class="con">
    <div class="ect-bg">
        <header class="ect-header ect-margin-tb ect-margin-lr text-center icon-write">
            <a href="javascript:history.go(-1)" class="pull-left ect-icon ect-icon1 ect-icon-history">&nbsp;</a> <span>代下单</span>
            <a href="javascript:;" class="pull-right ect-icon ect-icon1 ect-icon-mune"></a></header>
    </div>
    <section class="na-zixun zx-consignee">
        <div>
            <div style="float:left;padding: 2em">
                <img src="images/gy.jpeg" style="height: 5em">
            </div>
            <div style="float: left;padding: 1em; padding-top: 3em">
                <h2>国药关爱健康12323</h2>
            </div>
        </div>
        <div style="clear: both"></div>
        <div style="padding: 2em;padding-top: 0em;font-size: 13px;font-weight: bold">
            <?php $row = $app->fetch_assoc()?>
            <ul>
                <li> <img src="../<?php echo $row['img']?>" style="width: 100%"></li>
                <input type="hidden" name="cname" id="cname" value="<?php echo $row['title']?>">
                <li>药品名:&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $row['title']?></li>
                <input type="hidden" name="c_id" id="c_id" value="<?php echo $row['id']?>">
                <input type="hidden" name="ctype" id="ctype" value="<?php echo $row['info']?>">

                <li>规格:&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $row['info']?></li>
                <li>数量:&nbsp;&nbsp;&nbsp;&nbsp;
                    <input class="min" name="" type="button" value="-" style="height: 3em;width: 3em" />
                    <input class="text_box" name="count" id="count" type="text" value="0" style="width:25px; height: 2em;background: #ffffff;text-align: center" />
                    <input class="add" name="" type="button" value="+" style="height: 2.8em;width: 2.8em" />
                    盒
                </li>
                <li><div style="float: left">配送方式:</div><div style="float: left;margin-left: 1em">
                        <label><input type="radio" name="devi" value="1" checked>药店自取</label>
                        <br><br>
                        <label><input type="radio" name="devi" value="2">快递到付</label>
                    </div>
                </li>
                <li class="dev1">
                    自取店址:
                    <select name="shop" id="shop" style="width: 15em;" class="shop">
                        <option value="">请选择药店</option>
                        <?php
                        $shoparr=array();
                        while ($row = $shop->fetch_assoc()) {
                            $shoparr[$row['id']]=$row;
                            ?>
                            <option value="<?php echo $row['id'];?>" id="<?php echo $row['tel'].'_'.$row['address']?>"><?php echo $row['name'];?></option>

                        <?php
                        };
                        ?>
                    </select>

                </li>
                <li class="dev1">
                    药店电话:&nbsp;&nbsp;&nbsp;&nbsp;<span id="tel"></span>
                </li>
                <li class="dev1">
                    取件地址:&nbsp;&nbsp;&nbsp;&nbsp;<span id="saddress"></span>
                </li>

                <li class="dev2" style="display: none">
                    收货地址:&nbsp;<input type="text" name="address" id="address" style="height: 2.5em; width: 15em;border: none;border-bottom: 1px #000000 solid">
                </li>
                <li >
                    <span id="dname">取 &nbsp;件&nbsp;人:&nbsp;</span><input type="text" name="receive" id="receive" style="height: 2.5em; width: 15em;border: none;border-bottom: 1px #000000 solid">
                </li>
                <li>
                   联系电话:&nbsp;<input type="text" name="phone" id="phone" style="height: 2.5em; width: 15em;border: none;border-bottom: 1px #000000 solid">
                </li>
                <li>
                    医生姓名:&nbsp;<input type="text" name="doct" id="doct" value="<?php echo $doctorInfo['name']?>" style="height: 2.5em; width: 15em;border: none;border-bottom: 1px #000000 solid">
                </li>
                <input type="hidden" name="d_id" id="d_id" value="<?php echo $doctorInfo['id']?>">

            </ul>

        <div style="clear: both"></div>
        </div>


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
