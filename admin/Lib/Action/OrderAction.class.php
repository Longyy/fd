<?php
// +----------------------------------------------------------------------
// | APPCMS 移动应用软件后台管理系统
// +----------------------------------------------------------------------
// | provide by ：shiraz-soft.com
// 
// +----------------------------------------------------------------------
// | Author: Shirazsoft APP Team
// +----------------------------------------------------------------------

class OrderAction extends BaseAction
{
    function index()
    {
        $consultation = D('consultation');
        import("ORG.Util.Page");
        $result=$consultation->execute("SELECT t1.*,t2.name AS doctor_name FROM `cms_consultation` t1 LEFT JOIN `cms_doctor` t2 ON t1.doctor_id=t2.id");
        $p     = new Page($result, 30);
        $sql = 'SELECT t1.*,t2.name AS doctor_name FROM `cms_consultation` t1 LEFT JOIN `cms_doctor` t2 ON t1.doctor_id=t2.id ORDER BY create_time DESC  limit '.$p->firstRow.','.$p->listRows;
        $order = $consultation->query($sql);
        $page = $p->show();
        $this->assign('page', $page);
        $this->assign('orderList', $order);
        $this->display("index");
    }
    //代下单
    function dorder()
    {
        $Order = D('Order');
        import("ORG.Util.Page");
        //$where['type']=1;
        $count = $Order->where($where)->count();
        $p     = new Page($count, 30);
        $order = $Order->where($where)->order('time')->limit($p->firstRow.",".$p->listRows)->select();
        $page   = $p->show();
        $this->assign('page', $page);
        $this->assign('page', $page);
        $this->assign('orderList', $order);
        $this->display();
    }



}

?>