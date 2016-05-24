<?php
/**
 * Created by PhpStorm.
 * User: Kevin
 * Date: 15-4-10
 * Time: 下午4:04
 */
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

//var_dump($accessTokenHttp);
$result = httpGet($accessTokenHttp);
$result = json_decode($result, true);
include("connection.php");
//var_dump($result);
//是否登录注册过，没有进行跳转
$openId = $result["openid"];

//$sql = "SELECT t2.* FROM `cms_consultation_prescription` t1 INNER JOIN `cms_consultation` t2 ON t1.consultation_id=t2.id WHERE t2.open_id='".$openId."'ORDER BY t1.create_time DESC";

$sql = "SELECT t1.*, t1.create_time AS prescription_time,t2.name AS doctor_name FROM `cms_consultation` t1 LEFT JOIN `cms_doctor` t2 ON t1.`doctor_id`=t2.`id`
WHERE t1.`open_id`='" . $openId . "' ORDER BY t1.create_time DESC";
$result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no"/>
    <title>处方列表</title>
    <script language="JavaScript" src="js/jquery-1.11.0.min.js" type="text/javascript"></script>
    <script language="JavaScript" src="js/bootstrap.min.js" type="text/javascript"></script>
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
</head>
<body>
<table class="table table-striped">
    <tr onclick="detail(<?php echo($row["id"]) ?>)">
        <th>患者姓名</th>
        <th>性别</th>
        <th>预约时间</th>
        <th>预约医生</th>
        <th>订单状态</th>
    </tr>
    <tbody>
    <?php
    while ($row = $result->fetch_assoc()) {
        ?>

        <tr onclick="detail(<?php echo($row["id"]) ?>)">
            <td><?php echo($row["name"]) ?></td>
            <td><?php echo($row["sex"] == 1 ? "男" : "女") ?></td>
            <td><?php echo(date('Y-m-d', $row["prescription_time"])) ?></td>
            <td><?php if($row["doctor_name"]==""){echo("无");} else{ echo($row["doctor_name"]);} ?></td>
            <td><?php switch($row["status_id"]){
                    case -1:
                        echo("未处理");
                        break;
                    case 0:
                        echo("已处理");
                        break;
                    case -1:
                        echo("已完成");
                        break;
                }?></td>
        </tr>

    <?php
    }
    ?>
    </tbody>
</table>
</body>
</html>

<script>
    function detail(id) {
        window.location.href = '<?php echo( $pageUrl) ?>'+"prescriptionDetail.php?id=" + id;
    }
</script>