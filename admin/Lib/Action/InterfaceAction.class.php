<?php
// +----------------------------------------------------------------------
// | APPCMS 移动应用软件后台管理系统
// +----------------------------------------------------------------------
// | provide by ：shiraz-soft.com
// 
// +----------------------------------------------------------------------
// | Author: Shirazsoft APP Team
// +----------------------------------------------------------------------

class InterfaceAction extends BaseAction
{
    function index()
    {
        $interface = M('interface');
        import("ORG.Util.Page");
        if ($_POST) {
            $number = $_POST['number'];
            $sql = "select * from cms_interface where cms_interface.number=".$number;
            $interfaceList = $interface->query($sql);
             $p     = new Page(count($interfaceList), 10);

        }else{
                $count = $interface->count();
                $p     = new Page($count, 10);
                $interfaceList = $interface->limit($p->firstRow . ',' . $p->listRows)->select();
        }
        $page = $p->show();
        $this->assign('page', $page);
        $this->assign('interface_list', $interfaceList);
        $this->display();
    }



}

?>