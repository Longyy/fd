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

// 打开AIRKISS界面
class WIFIAction extends Action
{
    public function index()
    {

        $SDKModel=D('SDK');
        $this->signPackage = $SDKModel->getSignPackage();
//        print_r($this->signPackage);
        $this->display();
    }
}
