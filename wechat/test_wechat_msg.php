<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-9-6
 * Time: 上午11:58
 */
//提交成功后撤销该代码
echo $_GET['echostr'];
exit;
include("wechatClass.php");
define("TOKEN", "123456");
define('DEBUG', true);
$weixin = new Weixin(TOKEN, DEBUG); //实例化

//$weixin->traceHttp();
if (isset($_GET['echostr'])) {
    $weixin->valid();
} else {
    $weixin->getMsg();
    $type     = $weixin->msgtype; //消息类型
    $username = $weixin->msg['FromUserName']; //哪个用户给你发的消息,这个$username是微信加密之后的，但是每个用户都是一一对应的
    if ($type === 'text') {
        if ($weixin->msg['Content'] == 'Hello2BizUser') { //微信用户第一次关注你的账号的时候，你的公众账号就会受到一条内容为'Hello2BizUser'的消息
            $reply = $weixin->makeText('欢迎你关注哦，屌丝');
        } else { //这里就是用户输入了文本信息
            $keyword = $weixin->msg['Content']; //用户的文本消息内容
            include_once("chaxun.php"); //文本消息 调用查询程序
            $chaxun           = new chaxun(DEBUG, $keyword, $username);
            $results['items'] = $chaxun->search(); //查询的代码

            $reply = $weixin->makeNews($results);
        }
    } elseif ($type === 'location') {
        //用户发送的是位置信息  稍后的文章中会处理
    } elseif ($type === 'image') {
        //用户发送的是图片 稍后的文章中会处理
    } elseif ($type === 'voice') {
        //用户发送的是声音 稍后的文章中会处理
    }
    $weixin->reply($reply);
}
?>