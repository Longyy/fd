<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-8-11
 * Time: 下午5:00
 */

include("wechatClass.php");
//发送文字消息
//$msg="<a href='https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx7f07390e9f3e50b7&redirect_uri=http%3A%2F%2Fwww.taoappcode.com%2Ftest%2Ffdzj%2Ffd%2Fwechat%2FscanForm.php%3Fnum%3D37&response_type=code&scope=snsapi_base&state=111'>治疗意见</a>";
//echo(send_text_msg('ohLp2tyaV07tYqKt_NWm2s9o7yZA',$msg));
//发送模版消息
$tempMsg = array(
    "touser"      => "ohLp2tyaV07tYqKt_NWm2s9o7yZA",
    "template_id" => "To2lktM7BwzgtzXJRW8sLKYNPJK3YlKtsz9G09qzkwc",
    "url"         => "www.baidu.com",
    "data"        => array(
        "first" => array(
            "value" => "恭喜你购买成功！",
            "color" => "#173177"
        ),
        "keyword1"=>array(
            "value" => "张三",
            "color"=>"#173177"
        ),
        "keyword2"=>array(
            "value" => "2015-8-31",
            "color"=>"#173177"
        ),
        "keyword3"=>array(
            "value" => "邀请来院就诊",
            "color"=>"#173177"
        ),
        "remark"=>array(
            "value"=>"为您提供最好的服务",
            "color" => "#173177"
        )
    )
);
echo(send_temp_msg($tempMsg));
