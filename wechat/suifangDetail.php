<?php
$id = $_GET['id'];
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
$sql = "SELECT * FROM `cms_wechat_user` WHERE role_id in (1,5) and open_id='" . $openId . "'";
$result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
$rowCount = $result->num_rows;
if ($rowCount == 0) {
    $url = $pageUrl . "login.php?openId=" . $openId;
    echo "<script>window.location.href='" . $url . "'</script>";
}
$result = $result->fetch_assoc();
$doctorId=$result["identity_id"];
//查询患者提交的问题
$sql="select t1.*,t2.pic from cms_follow t1 left join cms_pic t2 on t1.pic_id=t2.id where t1.id=$id";
$result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
$result = $result->fetch_assoc();
//print_r($result);
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title>术后反馈 医生</title>
<link rel="stylesheet" href="style.css">
    <script language="JavaScript" src="js/jquery-1.11.0.min.js" type="text/javascript"></script>
</head>

<body style="font-family:微软雅黑;">
<div class="con">
  <div class="ect-bg">
    <header class="ect-header ect-margin-tb ect-margin-lr text-center icon-write"> <a href="javascript:history.go(-1)" class="pull-left ect-icon ect-icon1 ect-icon-history">&nbsp;</a> <span>术后反馈</span> <a href="javascript:;" class="pull-right ect-icon ect-icon1 ect-icon-mune"></a></header>
  </div>
  <p style="font-size:26px; color:#50c1e9; height:60px; margin-top:20px;">回复患者</p>
  <p style="margin-top:20px; margin-left:4%">
    <textarea name="" cols="46" rows="3" style="" id="hehehe1" placeholder="患者提问的问题" disabled>
        <?php echo($result ['question'])?>
    </textarea>
  </P>
  <img style="height: 200px;height: 200px;" src="<?php echo('.'.$result['pic'])?>">
  <p style="margin-top:20px; margin-left:4%">
    <textarea name="" cols="46" rows="3" style="" id="txtAnswer" placeholder="请输入推荐方案"></textarea>
  </P>
  
  <p style="margin-top:20px; margin-left:4%">
    <input type="button" onclick="dopost()" value="提交" style="width:11%;">
  </p>
  <p style="font-size:26px; color:#50c1e9; height:60px; margin-top:20px;">历史回复</p>
  <div style="border:1px solid #ccc; height:500px; width:92%; margin-left:4%; overflow-x:hidden;">
    <div>
      <p style="width:80%; margin-top:20px; margin-left:10%" >2016.5.12</p>
      <div style="border:1px solid #ccc; height:200px; width:80%; margin-top:10px; margin-left:10%; background:#ccc"></div>
      <p style="width:80%; margin-top:10px; margin-left:10%"><span style="color:#53c3af;">xx:</span>我向你提问了同一个问题？</p>
      <p style="width:80%; margin-top:10px; margin-left:10%"><span style="color:#53c3af;">xx医生</span>(<span style="color:#50c1e9;">2016.5.10</span>):我回答你了同一个问题？</p>
    </div>
    <div>
      <p style="width:80%; margin-top:20px; margin-left:10%" >2016.5.12</p>
      <div style="border:1px solid #ccc; height:200px; width:80%; margin-top:10px; margin-left:10%; background:#ccc"></div>
      <p style="width:80%; margin-top:10px; margin-left:10%"><span style="color:#53c3af;">xx:</span>我向你提问了同一个问题？</p>
      <p style="width:80%; margin-top:10px; margin-left:10%"><span style="color:#53c3af;">xx医生</span>(<span  style="color:#50c1e9;">2016.5.10</span>):我回答你了同一个问题？</p>
    </div>
  </div>
</div>
</body>
</html>
<script>
    function dopost() {
        var param = {
            id:'<?php echo $id;?>',
            answer:$("#txtAnswer").val(),
            doctor_openId:'<?php echo $openId?>',
            postType: "suifang"
        }
//      alert(JSON.stringify(param));
        // 提交问题验证
        if ($("#txtAnswer").val() == "") {
            alert("请填写答案");
            return false;
        }
        $.ajax({
            type: "POST",
            url: '<?php echo( $postUrl) ?>',
            data: param,
            success: function (data) {
//                alert(data);
                alert('提交成功')

            }
        })
    }
</script>