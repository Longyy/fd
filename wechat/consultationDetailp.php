<?php
include("httpConfig.php");
include("connection.php");
$sql = "SELECT p.*,c.name,c.phone,c.age,c.sex FROM `cms_pic` as p left join `cms_consultation` as c on p.uid=c.id where p.id=".$_GET["id"];
$result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
$data=$result->fetch_assoc();
if($_POST){
    $time=time();
    $sql="update cms_pic set comment='".$_POST['comment']."',status=1,retime='$time' where id=".$_POST['id'];
    $result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
    echo "<script> alert('回复成功!');</script>";
}
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
<body style="margin: 20px;">
<ul class="list-group">
    <form action="" method="post" id="sform">
    <li class="list-group-item">姓名：<?php echo($data['name']) ?></li>
    <li class="list-group-item">年龄：<?php echo($data['age']) ?></li>
    <li class="list-group-item">性别：<?php echo($data['sex']==1?"男":"女") ?></li>
    <li class="list-group-item">手机号：<?php echo($data['phone']) ?></li>
    <li class="list-group-item">图片：</li>
    <li class="list-group-item">
        <img src="../<?php echo(substr($data['pic'],2));?>" width="100%">
    </li>
    <li class="list-group-item">建议：
    <textarea cols="30" rows="5" name="comment"><?php echo($data['comment']) ?></textarea>
    </li>
    <input type="hidden" value="<?php echo($data['id']) ?>" name="id">
    </form>
    
</ul>
<center>
<button type="button" onclick="invite()" class="btn btn-primary">回复</button>

</center>
<script>
    function invite(){
        $("#sform").submit();
    }

</script>
</body>
</html>

