<?php
/**
 * Created by PhpStorm.
 * User: bear
 * Date: 16/3/17
 * Time: 下午8:01
 */
class ShopAction extends BaseAction{
    function index()
    {
        $Shop = D('Shop');
        import("ORG.Util.Page");
        $count = $Shop->count();
        $p     = new Page($count, 30);

        $shop = $Shop->order('time')->limit($p->firstRow.",".$p->listRows)->select();
        $page   = $p->show();
        $this->assign('page', $page);
        $big_menu       = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=Shop&a=add\', title:\'添加药店\', width:\'600\', height:\'350\', lock:true}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);', '添加药店');
        $this->big_menu = $big_menu;

        $this->assign('doctorList', $shop);
        $this->display();
    }
    function add()
    {
        if (isset($_POST['dosubmit'])) {
            $shop = D('shop');
            $result = $shop->where("name='" . $_POST['name'] . "'")->count();
            if ($result) {
                $this->error('该药店已存在');
            }else{
                $data=$_POST;
                $data['time']=time();
                if($shop->add($data)){
                    $this->success("操作成功!");
                }else{
                    $this->error("操作失败!");
                }
            }

        }
        $this->display();
    }
}