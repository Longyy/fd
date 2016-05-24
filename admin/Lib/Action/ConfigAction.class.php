<?php
// +----------------------------------------------------------------------
// | APPCMS 移动应用软件后台管理系统
// +----------------------------------------------------------------------
// | provide by ：shiraz-soft.com
// 
// +----------------------------------------------------------------------
// | Author: Shirazsoft APP Team
// +----------------------------------------------------------------------

class ConfigAction extends BaseAction
{
    function index()
    {
        $config          = M('config');
        $this->infoModel = $config->where('type=\'service\'')->find();
        $this->display();
    }

    function editService()
    {
        $config          = M('config');
        $config->where('type=\'service\'')->save(array('info' => $_POST['info']));
        $this->success(L('operation_success'));
    }

    function faq()
    {
        $config          = M('config');
        $this->infoModel = $config->where('type=\'FAQ\'')->find();
        $this->display();
    }

    function editFAQ()
    {
        $config          = M('config');
        $config->where('type=\'FAQ\'')->save(array('info' => $_POST['info']));
        $this->success(L('operation_success'));
    }

}

?>