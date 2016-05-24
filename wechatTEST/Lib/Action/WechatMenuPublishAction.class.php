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
class WechatMenuPublishAction extends Action
{
    public function index()
    {
        $data = '{
     "button":[
      {
           "name":"医生",
           "sub_button":[
            {
               "type":"view",
               "name":"咨询处理",
               "url":"' . get_wechat_url('http://hongtaiji.vvtek.cn/wechat/zixunchuli.php') . '"
            },
             {
               "type":"view",
               "name":"预约处理",
               "url":"' . get_wechat_url('http://hongtaiji.vvtek.cn/wechat/yuyuechuli.php') . '"
            },
            {
               "type":"view",
               "name":"随访",
               "url":"' . get_wechat_url('http://hongtaiji.vvtek.cn/wechat/suifang.php') . '"
            },
            {
               "type":"view",
               "name":"转诊",
               "url":"' . get_wechat_url('http://hongtaiji.vvtek.cn/wechat/scanForm.php') . '"
            },
            {
               "type":"view",
               "name":"防伪识别",
               "url":"' . get_wechat_url('http://hongtaiji.vvtek.cn/wechat/scanQRCode.php') . '"
            }

            ]
       },
        {
           "name":"患者",
           "sub_button":[
              {
               "type":"view",
               "name":"提交咨询表",
               "url":"' . get_wechat_url('http://hongtaiji.vvtek.cn/wechat/consultation.php') . '"
              },
             {
               "type":"view",
               "name":"预约",
               "url":"' . get_wechat_url('http://hongtaiji.vvtek.cn/wechat/appoint.php') . '"
              },
              {
               "type":"view",
               "name":"术后反馈",
               "url":"' . get_wechat_url('http://hongtaiji.vvtek.cn/wechat/shuhoufanhui.php') . '"
              },
              {
               "type":"view",
               "name":"消息管理",
               "url":"' . get_wechat_url('http://hongtaiji.vvtek.cn/wechat/messageList2.php') . '"
              },
              {
               "type":"view",
               "name":"身体状况",
               "url":"' . get_wechat_url('http://hongtaiji.vvtek.cn/wechat/zhiliaoquerenbiao.php') . '"
              }
            ]
       },
       {
           "name":"科普",
           "sub_button":[
                {
                   "type":"click",
                    "name":"患者心路",
                "key":"patient_case"
                },
                {
                 "type":"view",
                 "name":"找医生",
                 "url":"' . get_wechat_url('http://hongtaiji.vvtek.cn/wechat/centerList.php') . '"
              }
            ]
       },
       ]
}';
        $SDKModel=D('SDK');
        echo($SDKModel->createMenu($data));
    }

    public function getWechatUrl(){
        echo(get_wechat_url('http://hongtaiji.vvtek.cn/wechat/zhiliaojishi.php'));
        
    }

}


/*             {
               "type":"view",
               "name":"测试扫描",
               "url":"' . get_wechat_url('http://hongtaiji.vvtek.cn/wechat/scanQRCode.php') . '"
            }*/