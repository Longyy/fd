<?php
/**
 * Created by PhpStorm.
 * User: Kevin
 * Date: 15-4-10
 * Time: 下午2:50
 */
include("httpConfig.php");
include("connection.php");
$sql = "SELECT * FROM `cms_app_cate`";
$result = mysqli_query($connection, $sql) or die(mysqli_error($connection));

?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no"/>
    <title>我的处方</title>
    <script language="JavaScript" src="js/jquery-1.11.0.min.js" type="text/javascript"></script>
    <script language="JavaScript" src="js/bootstrap.min.js" type="text/javascript"></script>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js" type="text/javascript" charset="utf-8"></script>
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
</head>
<body>
<form>
    <div class="form-group">
        <label for="exampleInputPassword1">药品</label>
        <select id="ddlCate">
            <?php
            while ($row = $result->fetch_assoc()) {
            ?>
                <option value="<?php echo($row["id"]) ?>"><?php echo($row["name"]) ?></option>

            <?php
            }
            ?>
        </select>
    </div>

    <div class="form-group">
        <label for="exampleInputPassword1">数量</label>
        <select id="ddlNumber">
            <option value="1">1</option>
            <option value="1">2</option>
            <option value="1">3</option>
            <option value="1">4</option>
            <option value="1">5</option>
            <option value="1">6</option>
            <option value="1">7</option>
            <option value="1">8</option>
            <option value="1">9</option>
            <option value="1">10</option>
        </select>
    </div>
</form>
<center>
    <button type="button" id="btnSubmit" onclick="submit()" style="width: 200px;" class="btn btn-primary">提交
        <!--        <button type="button" id="fstep2" style="width: 200px;" class="btn btn-primary">提交2</button>-->
        <br/>
        <center>
            本服务由“红胎记咨询微信平台”提供
        </center>
</center>
</body style="padding-top: 10px;">
</html>
<script>
    function submit(){
        $.ajax({
            type: "POST",
            url: '<?php echo( $postUrl) ?>',
            data: {consultation_id:<?php echo( $_GET["id"]) ?>,
                cate_id:$("#ddlCate").val(),
                number:$("#ddlNumber").val(),
                postType:"prescription"
            },
            success: function (data) {
                alert("操作成功");
//                if(data.st=true){
//                    alert("操作成功");
////                    WeixinJSBridge.invoke('closeWindow', {}, function (res) {
////
////                    });
//                }
            }
        })
    }
</script>