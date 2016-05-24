<?php
/*
// | APPCMS 移动应用软件后台管理系统
// +----------------------------------------------------------------------
// | provide by ：shiraz-soft.com
// 
// +----------------------------------------------------------------------
// | Author: Shirazsoft APP Team
// +----------------------------------------------------------------------
*/

// 微信开放接口处理
class PublicAction extends Action
{
    public function index()
    {
        $fp                 = fopen("log.txt", "w");
        fwrite($fp, "test");
        fclose($fp);
        return;
//        echo $_GET['echostr'];
//        exit;
        $weixin = D('Weixin');
        // 获取数据
        $data = $weixin->getData();
        if ($data ['MsgType'] == 'event') {
            $event = strtolower($data ['Event']);

            $fan_info = $weixin->getFansInfo($data['FromUserName']);
//            $fp                 = fopen("log.txt", "w");
//            fwrite($fp, json_encode($fan_info));
//            fclose($fp);
            //关注/取消关注
            if ($event == 'subscribe') {
                $fans                = M('fans');
                $data['weixin_code'] = $data['ToUserName'];
                $data['open_id']     = $data['FromUserName'];
                $data['fan_status']  = 1;
                $data['nick_name'] = $fan_info['nickname'];
                $data['sex'] = $fan_info['sex'];
                $data['head_img_url'] = $fan_info['headimgurl'];
                $data['province'] = $fan_info['province'];
                $data['country'] = $fan_info['country'];
                $data['city'] = $fan_info['city'];
                $data['create_time'] = time();
                $fans->add($data);
            } elseif ($event == 'unsubscribe') {
                $fans                 = M('fans');
                $data['fan_status']   = 0;
                $condition['open_id'] = $data['FromUserName'];
                $fans->where($condition)->data($data)->save();
            }
        }


//        $this->data = $data;
//        $fp                 = fopen("log.txt", "w");
//        fwrite($fp, $data ['ToUserName'].'|'.$data ['FromUserName'].'|'.$data ['MsgType']);
//        fclose($fp);
    }
}
