<?php

$appId = "wx7f07390e9f3e50b7";
$appSecret = "83c1b920dd1457d88950ac13415c3675";
$accessToken=getAccessToken($appId,$appSecret);
define("ACCESS_TOKEN", $accessToken);
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-8-11
 * Time: 下午4:13
 */

//创建登录菜单
function create_login_url($url)
{

    $param = array
    (
        'appid'         => 'wx7f07390e9f3e50b7',
        'redirect_uri'  => $url,
        'response_type' => 'code',
        'scope'         => 'snsapi_base',
        'state'         => '#wechat_redirect'
    );
    $wxHttps = 'https://open.weixin.qq.com/connect/oauth2/authorize?'.http_build_query($param);
    return $wxHttps;
}

//获取accessToken
function getAccessToken($appId,$appSecret)
{
    // access_token 应该全局存储与更新，以下代码以写入到文件中做示例
    $data = json_decode(file_get_contents("access_token.json"));
    if ($data->expire_time < time()) {
        // 如果是企业号用以下URL获取access_token
        // $url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=$this->appId&corpsecret=$this->appSecret";
        $url          = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appId&secret=$appSecret";
        $res          = json_decode(httpGet($url));
        $access_token = $res->access_token;
        if ($access_token) {
            $data->expire_time  = time() + 7000;
            $data->access_token = $access_token;
            $fp                 = fopen("access_token.json", "w");
            fwrite($fp, json_encode($data));
            fclose($fp);
        }
    } else {
        $access_token = $data->access_token;
    }
    return $access_token;
}

//创建菜单
function createMenu($data){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".ACCESS_TOKEN);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $tmpInfo = curl_exec($ch);
    if (curl_errno($ch)) {
        return curl_error($ch);
    }

    curl_close($ch);
    return $tmpInfo;

}





function send_text_msg($openid,$msg){
    $param=array("touser"=>$openid,"msgtype"=>"text","text"=>array("content"=>$msg));
    $url="https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=".ACCESS_TOKEN;

   return httpPost($url,$param,"post");
}

function send_temp_msg($arrayMsg){
//    $param=array("touser"=>$openid,"msgtype"=>"text","text"=>array("content"=>$msg));
    $url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".ACCESS_TOKEN;

    return httpPost($url,$arrayMsg,"post");
}


 function httpGet($url)
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_TIMEOUT, 500);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_URL, $url);

    $res = curl_exec($curl);
    curl_close($curl);

    return $res;
}

//function httpPost($url,$post_data)
//{
//    $ch = curl_init();
//    curl_setopt($ch, CURLOPT_URL, $url);
//    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//    curl_setopt($ch, CURLOPT_POST, 1);
//    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
//    $output = curl_exec($ch);
//    curl_close($ch);
//    return $output;
//}

//请求方法
function httpPost($url, $params = '', $method = 'get')
{
    $curlInt = curl_init();
    switch (strtolower($method)) {
        case 'get':
            $url .= $params ? '?' . http_build_query($params) : '';
            break;
        case 'post':
            curl_setopt($curlInt, CURLOPT_POST, 1);
            curl_setopt($curlInt, CURLOPT_POSTFIELDS, json_encode($params,JSON_UNESCAPED_UNICODE));
            break;
        case 'delete':
            curl_setopt($curlInt, CURLOPT_POST, 1);
            curl_setopt($curlInt, CURLOPT_POSTFIELDS, http_build_query($params));
            break;
        case 'route':
            $url .= '/' . http_build_route($params);
            break;
        case 'wx':
            curl_setopt($curlInt, CURLOPT_POST, 1);
            curl_setopt($curlInt, CURLOPT_POSTFIELDS, $params);
            break;
    }
    curl_setopt($curlInt, CURLOPT_URL, $url);
    curl_setopt($curlInt, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curlInt, CURLOPT_BINARYTRANSFER, 1);
    $httpHeader = array('Content-Type:application/json;charset=utf-8');
    curl_setopt($curlInt, CURLOPT_HTTPHEADER, $httpHeader);
    $getInfo = curl_getinfo($curlInt);
    $content = curl_exec($curlInt);
    return $content;
}
?>