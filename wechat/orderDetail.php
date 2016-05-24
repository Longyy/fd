<?php
include("httpConfig.php");
include("connection.php");
$sql = "SELECT * FROM `cms_consultation` where id=".$_GET["id"];
$result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
$data=$result->fetch_assoc();
//var_dump($result->fetch_assoc());
//while($row = $result->fetch_assoc()) {
//    echo "<br> id: ". $row["id"]. " - Name: ". $row["name"]. " " . $row["age"];
//}
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no"/>
    <title>详情</title>
    <script language="JavaScript" src="js/jquery-1.11.0.min.js" type="text/javascript"></script>
    <script language="JavaScript" src="js/bootstrap.min.js" type="text/javascript"></script>
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
</head>
<body>
<ul class="list-group">
    <li class="list-group-item">姓名：<?php echo($data['name']) ?></li>
    <li class="list-group-item">年龄：<?php echo($data['birthday']) ?></li>
    <li class="list-group-item">来院时间：<?php echo(date('Y-m-d',$data['reserve_time'])); echo('&nbsp;&nbsp;&nbsp;'); echo($data['reserve_reg']==1?'上午':'下午') ?></li>
<!--    <li class="list-group-item">所在省市：--><?php //echo($data['city']) ?><!--</li>-->
<!--    <li class="list-group-item">预约号/转诊号：--><?php //echo($data['order_code']==""?"无":$data['order_code']) ?><!-- </li>-->
<!--    <li class="list-group-item">过往治疗史：-->
<!--        --><?php //if($data['before_treat']){ echo($data['before_treat']);}else{ echo "无";} ?>
<!--    </li>-->
<!--    <li class="list-group-item">求诊要求：-->
<!--        --><?php //if($data['demand']){ echo($data['demand']);}else{ echo "无";} ?>
<!--    </li>-->
</ul>
<center>
<button type="button" onclick="handle(5)" class="btn btn-primary">接受</button>
    <button type="button" onclick="handle(6)" class="btn btn-primary">拒绝</button>
</center>
</body>
</html>

<script>
    function handle(type){
        $.ajax({
            type: "POST",
            url: '<?php echo( $postUrl) ?>',
            data: {id: <?php echo($_GET['id']) ?>,
                postType:"doctorHandle",
                type:type
            },
            success: function (data) {
                    alert("操作成功");
//                    WeixinJSBridge.invoke('closeWindow', {}, function (res) {
//
//                    });

            }
        })
    }

    function cancle(){
        $.ajax({
            type: "POST",
            url: '<?php echo( $postUrl) ?>',
            data: {id: <?php echo($_GET['id']) ?>,

                postType:"cancle"
            },
            success: function (data) {
                if(data.st=true){
                    alert("操作成功");
//                    WeixinJSBridge.invoke('closeWindow', {}, function (res) {
//
//                    });
                }
            }
        })
    }


</script>