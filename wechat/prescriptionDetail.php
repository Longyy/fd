<?php
/**
 * Created by PhpStorm.
 * User: Kevin
 * Date: 15-4-10
 * Time: 下午2:50
 */

include("connection.php");
$sql = "SELECT *,t2.name as doctor_name FROM `cms_consultation` t1 LEFT JOIN `cms_doctor` t2  ON  t1.doctor_id=t2.id  WHERE t1.id=".$_GET["id"];
$result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
$data=$result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no"/>
    <title>处方详情</title>
    <script language="JavaScript" src="js/jquery-1.11.0.min.js" type="text/javascript"></script>
    <script language="JavaScript" src="js/bootstrap.min.js" type="text/javascript"></script>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js" type="text/javascript" charset="utf-8"></script>
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
</head>
<body>
<form>
    <div class="form-group">
        <label for="exampleInputPassword1">预约号</label>
        <?php echo($data['order_code']) ?>
    </div>
    <div class="form-group">
        <label for="exampleInputPassword1">姓名</label>
        <?php echo($data['name']) ?>
    </div>
    <div class="form-group">
        <label for="exampleInputPassword1">性别</label>
        <?php echo($data["sex"] == 1 ? "男" : "女") ?>
    </div>
    <div class="form-group">
        <label for="exampleInputPassword1">电话</label>
        <?php echo($data['phone']) ?>
    </div>
    <div class="form-group">
        <label for="exampleInputPassword1">临床诊断</label>
        <?php if($data['diagnose']){ echo($data['diagnose']);}else{ echo "无";} ?>
    </div>
    <div class="form-group">
        <label for="exampleInputPassword1">医生姓名</label>
        <?php echo($data['doctor_name']) ?>
    </div>
</form>
<center>
    <button type="button" id="btnSubmit"  style="width: 200px;" class="btn btn-primary">支付
        <!--        <button type="button" id="fstep2" style="width: 200px;" class="btn btn-primary">提交2</button>-->
</center>
</body>
</html>
<script>
    function submit(){
        $.ajax({
            type: "POST",
            url: "http://www.taoappcode.com/test/fdzj/fd/wechat/dataPost.php",
            data: {consultation_id:<?php echo( $_GET["id"]) ?>,
                cate_id:$("#ddlCate").val(),
                number:$("#ddlNumber").val(),
                postType:"prescription"
            },
            success: function (data) {
                if(data.st=true){
                    alert("操作成功");
                    WeixinJSBridge.invoke('closeWindow', {}, function (res) {

                    });
                }
            }
        })
    }
</script>