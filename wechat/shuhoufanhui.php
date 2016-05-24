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
    echo "<script>window.location.href='" . $consultation . "'</script>";
}
// 查询当前患者历史回复记录
$sql = "SELECT t1.*,t2.pic,t3.name FROM cms_follow t1 left join cms_pic t2 on t1.pic_id=t2.id left join cms_consultation t3 on t1.patient_openId=t3.open_id  where t1.patient_openId='$openId' and t1.answer is not null ";
$historyResult = mysqli_query($connection, $sql) or die(mysqli_error($connection));

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title>术后反馈</title>
<link rel="stylesheet" href="style.css">
<script language="JavaScript" src="js/jquery-1.11.0.min.js" type="text/javascript"></script>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>

</head>

<body style="font-family:微软雅黑;" >
<div class="con">
  <div class="ect-bg">
    <header class="ect-header ect-margin-tb ect-margin-lr text-center icon-write"> <a href="javascript:history.go(-1)" class="pull-left ect-icon ect-icon1 ect-icon-history">&nbsp;</a> <span>术后反馈</span> <a href="javascript:;" class="pull-right ect-icon ect-icon1 ect-icon-mune"></a></header>
  </div>
  <p style="font-size:26px; color:#50c1e9; height:60px; margin-top:20px;">向医生提问</p>
  <p style="margin-top:20px; margin-left:4%">
    <textarea name="question" id="question" cols="45" rows="3" style="" id="hehehe1" placeholder="请输入推荐方案"></textarea>
  </P>
  <p style="margin-top:20px; margin-left:4%">
      <a href="javascript:void" onclick="chooseImage()" >上传照片</a>
      <div id="divUploadImg"></div>
  </p>
  <p style="margin-top:20px; margin-left:4%">
    <input type="button" value="提交" style="width:11%;"  onclick="dopost();">
  </p>
  <p style="font-size:26px; color:#50c1e9; height:60px; margin-top:20px;">历史回复</p>
  <div style="border:1px solid #ccc; height:500px; width:92%; margin-left:4%; overflow-x:hidden;">
    <?php while ($row = $historyResult->fetch_assoc()) {?>
    <div>
      <p style="width:80%; margin-top:20px; margin-left:10%" ><?php echo(date('Y-m-d', $row['question_time'])) ?></p>
      <img style="border:1px solid #ccc; height:200px; width:80%; margin-top:10px; margin-left:10%; " src="<?php echo('.'.$row['pic'])?>">
      <p style="width:80%; margin-top:10px; margin-left:10%"><span style="color:#53c3af;"><?php  echo($row['name'])?>:</span><?php echo($row['question'])  ?></p>
      <p style="width:80%; margin-top:10px; margin-left:10%">
          <?php
          //获取医生或者技师姓名
          $sql="select * from cms_wechat_user where open_id ='$row[doctor_openId]'";
          $result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
          $result = $result->fetch_assoc();
          $id=$result['identity_id'];
          if($result['role_id']==1){
              $sql="select *from cms_doctor where id =$id";
              $result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
              $result = $result->fetch_assoc();
              $nameRes=$result['name'];
          }
          elseif($result['role_id']==5){
              $sql="select *from cms_technician_doctor where id =$id";
              $result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
              $result = $result->fetch_assoc();
              $nameRes=$result['name'];
          }
          ?>
          <span style="color:#53c3af;"><?php echo($nameRes)?></span>
          (<span style="color:#50c1e9;"><?php echo(date('Y-m-d',$row['answer_time']))  ?></span>):<?php echo($row['answer'])  ?></p>
    </div>
    <?php } ?>
    <!-- <div>
      <p style="width:80%; margin-top:20px; margin-left:10%" >2016.5.12</p>
      <div style="border:1px solid #ccc; height:200px; width:80%; margin-top:10px; margin-left:10%; background:#ccc"></div>
      <p style="width:80%; margin-top:10px; margin-left:10%"><span style="color:#53c3af;">xx:</span>我向你提问了同一个问题？</p>
      <p style="width:80%; margin-top:10px; margin-left:10%"><span style="color:#53c3af;">xx医生</span>(<span  style="color:#50c1e9;">2016.5.10</span>):我回答你了同一个问题？</p>
    </div> -->
  </div>
</div>
<script type="text/javascript">
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
        if($("#divUploadImg").find("img").length==1){
            alert('只能上传一张图片');
            return;
        }
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
//                            alert(data);
                            $("#divUploadImg").append("<img data-imgid=\""+data+"\"  src=\""+localIds+"\" height=\"50px\"  width=\"50px;\" style=\"float: left; margin-top: 2em\">");
                        });
                        $(obj).next().val(serverId); // 把上传成功后获取的值附上
                    }
                });
            }
        });
    }
  function dopost() {
  var param = {
        question: $("#question").val(),
        pic_id:$($("#divUploadImg").find("img")[0]).data("imgid"),
        openId: '<?php echo $openId;?>',
        postType: "shuhoufanhui"
    }
//      alert(JSON.stringify(param));
    // 提交问题验证
    if ($("#question").val() == "") {
      alert("请填写反馈信息");
      return false;
    }
    $.ajax({
        type: "POST",
        url: '<?php echo( $postUrl) ?>',
        data: param,
        success: function (data) {
//            alert(data);
            alert('提交成功');
            WeixinJSBridge.invoke('closeWindow', {}, function (res) {

            });

        }
    })
}
</script>
</body>
</html>
