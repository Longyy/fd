<?php
include("httpConfig.php");
include("connection.php");
$sql = "SELECT t1.*,t2.name as doctor_name,t4.name as center_name FROM cms_consultation t1 left join cms_doctor t2 on t1.doctor_id=t2.id
left join cms_medical_center_doctor t3 on t2.id=t3.doctor_id
left join cms_medical_center t4 on t3.medical_center_id=t4.id where t1.id=".$_GET["id"];
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
<body style="margin: 20px;">
<ul class="list-group">
    <li class="list-group-item">姓名：<?php echo($data['name']) ?></li>
    <!-- <li class="list-group-item">年龄：</li> -->
    <li class="list-group-item">性别：<?php echo($data['sex']==1?"男":"女") ?></li>

    <li class="list-group-item">出生日期：<?php echo($data['birthday']) ?></li>
    <li class="list-group-item">手机号：<?php echo($data['phone']) ?></li>
<!--    <li class="list-group-item">信息来源：--><?php //echo($data['resource']) ?><!--</li>-->
    <!-- <li class="list-group-item">求诊类型：</li> -->
    <?php if($data['order_type']!=1){?>
<!--    <li class="list-group-item">所在省市：--><?php //echo($data['city']) ?><!--</li>-->
<!--    <li class="list-group-item">转诊号：--><?php //echo($data['order_code']==""?"无":$data['order_code']) ?><!-- </li>-->
    <?php }?>
    <!-- <li class="list-group-item">临床诊断：-->
        <!-- <?php /*if($data['diagnose'])*//*{ echo($data['diagnose']);}else{ echo "无";}*/ ?> -->
    <!-- </li>  -->
    <li class="list-group-item">患病部位：
        <?php if($data['description']){ echo($data['description']);}else{ echo "无";} ?>
    </li>
    <li class="list-group-item">照片：
        <div><img src="../Uploads/User_cert/pic/201603180040008930.jpg" height="100px" width="100px" ></div>
        <div><img src="../Uploads/User_cert/pic/201603180040008930.jpg" height="100px" width="100px"></div>
        <div><img src="../Uploads/User_cert/pic/201603180040008930.jpg" height="100px" width="100px"></div>
    </li>
    <li class="list-group-item">治疗史：
        <?php if($data['before_treat']){ echo($data['before_treat']);}else{ echo "无";} ?>
    </li>
    <!-- <li class="list-group-item">求诊要求： -->
        <!-- <?php /*if($data['demand'])*//*{ echo($data['demand']);}else{ echo "无";}*/ ?> -->
   <!--  </li> -->
    <li class="list-group-item">医疗中心：
        <?php if($data['center_name']){ echo($data['center_name']);}else{ echo "无";} ?>
    </li>
    <li class="list-group-item">医生姓名：
        <?php if($data['doctor_name']){ echo($data['doctor_name']);}else{ echo "无";} ?>
    </li>
</ul>
<center>
<button type="button" onclick="invite()" class="btn btn-primary">邀请预约</button>
<button type="button" onclick="suggestion()" class="btn btn-primary">咨询建议</button>
<button type="button" onclick="cancle()" class="btn btn-primary">作废</button>
</center>
</body>
</html>

<script>
    /// 邀请来源
    function invite(){
        $.ajax({
            type: "POST",
            url: '<?php echo( $postUrl) ?>',
            data: {id: <?php echo($_GET['id']) ?>,
                openId:'<?php echo($data['open_id'])?>',
                postType:"invite"
            },
            success: function (data) {
                    alert("您已经邀请患者<?php echo($data['name']) ?>来院治疗中心就诊,请及时跟进患者来院的情况");
//                    WeixinJSBridge.invoke('closeWindow', {}, function (res) {
//
//                    });

            }
        })
    }

    // 咨询建议
    function suggestion(){
        window.location.href="zixunjianyi.php?id="+<?php echo($_GET['id'])?>;
       /* $.ajax({
            type: "POST",
            url: '<?php echo( $postUrl) ?>',
            data: {id: <?php echo($_GET['id']) ?>,
                openId:'<?php echo($data['open_id'])?>',
                postType:"suggestion"
            },
            success: function (data) {
               if(data.st==1){
                   window.location.href="zixunjianyi.php?id="+<?php echo($_GET['id'])?>;
                   alert("dfsdf");
               }

            }
        })*/
    }


 // 信息作废
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