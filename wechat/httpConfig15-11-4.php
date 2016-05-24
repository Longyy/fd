<?php
/**
 * Created by PhpStorm.
 * User: Kevin
 * Date: 15-4-13
 * Time: 下午4:25
 */
$pageUrl="http://".$_SERVER['SERVER_NAME']."/test/fdzj/fd/wechat/";
$postUrl="http://".$_SERVER['SERVER_NAME']."/test/fdzj/fd/wechat/dataPost.php";

//患者登录之后直接跳转到地址
$messUrl="https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx7f07390e9f3e50b7&redirect_uri=http%3A%2F%2Fwww.taoappcode.com%2Ftest%2Ffdzj%2Ffd%2Fwechat%2FmessageList.php&response_type=code&scope=snsapi_base&state=%23wechat_redirect";
//医生登录之后直接跳转到地址
$consultationListUrl="https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx7f07390e9f3e50b7&redirect_uri=http%3A%2F%2Fwww.taoappcode.com%2Ftest%2Ffdzj%2Ffd%2Fwechat%2FconsultationList.php&response_type=code&scope=snsapi_base&state=%23wechat_redirect";
//转诊医生列表
$qrcodeConsultation="https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx7f07390e9f3e50b7&redirect_uri=http%3A%2F%2Fwww.taoappcode.com%2Ftest%2Ffdzj%2Ffd%2Fwechat%2FtransConsultationList.php&response_type=code&scope=snsapi_base&state=%23wechat_redirect";
//转诊医生首页
$tran_consultation="https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx7f07390e9f3e50b7&redirect_uri=http%3A%2F%2Fwww.taoappcode.com%2Ftest%2Ffdzj%2Ffd%2Fwechat%2FtransferConsultation.php&response_type=code&scope=snsapi_base&state=%23wechat_redirect";