<?php
/**
 * Created by PhpStorm.
 * User: Kevin
 * Date: 15-4-13
 * Time: 下午4:25
 */
$domain='http://hongtaiji.vvtek.cn/wechat/';
$pageUrl="http://".$_SERVER['SERVER_NAME']."/wechat/";
$postUrl="http://".$_SERVER['SERVER_NAME']."/wechat/dataPost.php";

//患者登录之后直接跳转到地址
$messUrl=get_wechat_url($domain.'messageList2.php');
//医生登录之后直接跳转到地址
$zixunchuli=get_wechat_url($domain.'zixunchuli.php');

//转诊医生列表
$qrcodeConsultation=get_wechat_url($domain.'transConsultationList.php');

//转诊医生首页
$tran_consultation=get_wechat_url($domain.'transferConsultation.php');

//治疗中心医生已处理列表
$tran_consultation_before=get_wechat_url($domain.'consultationListBefore.php');
$tran_consultation_other=get_wechat_url($domain.'consultationListOther.php');
$picUrl=get_wechat_url($domain.'picDetail.php');

// 预约医生成功页面
$ordersuccess = get_wechat_url($domain.'ordersuccess.php');

//扫描页面
$scanForm=get_wechat_url($domain.'scanForm.php');

//求诊页面
$consultation=get_wechat_url($domain.'consultation.php');

//去药店
$orderPay=get_wechat_url($domain.'order_pay.php');

//技师登录跳转页面
$zhiliaojishi=get_wechat_url($domain.'zhiliaojishi.php');



function get_wechat_url($url){
    $param = array
    (
        'appid'         => 'wx3e395640ed0da430',
        'redirect_uri'  => $url,
        'response_type' => 'code',
        'scope'         => 'snsapi_base',
        'state'         => '#wechat_redirect'
    );
    $wxHttps = 'https://open.weixin.qq.com/connect/oauth2/authorize?'.http_build_query($param);
    return $wxHttps;
}

function dd($msg) {
    echo '<pre>';
    var_dump($msg);exit;
    echo '</pre>';
}