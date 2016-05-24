<?php
// +----------------------------------------------------------------------
// | APPCMS 移动应用软件后台管理系统
// +----------------------------------------------------------------------
// | provide by ：shiraz-soft.com
// 
// +----------------------------------------------------------------------
// | Author: Shirazsoft APP Team
// +----------------------------------------------------------------------

class SalerAction extends BaseAction
{
    function index()
    {
        $prex = C('DB_PREFIX');
        $saler = D('saler');
        $count = $saler->count();
        import("ORG.Util.Page");
        $p     = new Page($count, 30);
        $saler_list =  $saler->field($prex.'saler.*,t2.name as department_name')->join('left join '.$prex.'department t2 on '.$prex.'saler.department_id=t2.id')->limit($p->firstRow . ',' . $p->listRows)->order('is_job desc')->select();

//        $doctor = $doctor->limit($p->firstRow . ',' . $p->listRows)->select();

        $page = $p->show();
        $this->assign('page', $page);
        $big_menu       = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=Saler&a=add\', title:\'添加销售代表\', width:\'600\', height:\'350\', lock:true}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);', '添加销售代表');
        $this->big_menu = $big_menu;

        $this->assign('salerList', $saler_list);
        $this->display();
    }


    function add()
    {
        if (isset($_POST['dosubmit'])) {
            $saler  = D('saler');
            $result = $saler->where("name='" . $_POST['name'] . "'")->count();
            if ($result) {
                $this->error('该销售代表已存在');
            }
            if ($_POST["order_end"] < $_POST["order_begin"]) {
                $this->error('结束号码必须大于起始号码');
            }
//            $wechat_user        = D('wechat_user');
//            $result = $wechat_user->where("user_name='" . $_POST['phone'] . "'")->count();
//            if ($result) {
//                $this->error('手机号码已存在，请重新输入');
//            }
            $result = $saler->query("SELECT * FROM `cms_saler` WHERE (order_begin<=" . $_POST["order_begin"] . " AND order_end>=" . $_POST["order_begin"] . ") OR(order_begin<=" . $_POST["order_end"] . " AND order_end>=" . $_POST["order_end"] . ")");
            if(count($result)!=0){
                $this->error('范围有误，请检查后重新填写');
            }
//            SELECT * FROM `cms_saler` WHERE (order_begin<=80 AND order_end>=80) OR(order_begin<=100 AND order_end>=100)

//            //上次图片
//            if (uploadImg($msg, $name) == false) {
//                $this->error($msg);
//            }
//            $doctor->name = $name;

            $saler->create();
            $saler->province_id = $_POST['province'];
            $result = $saler->add();
            if($result) {
                $insertId = $result;
                if(!empty($_POST['choiced_transfer_doctor'])) {
                    $aDoctor = explode('|', $_POST['choiced_transfer_doctor']);
                    foreach($aDoctor as $sDoctor) {
                        if(!empty($sDoctor)) {
                            $obj = D('saler_doctor');
                            $data = array(
                                'saler_id'  => $insertId,
                                'doctor_id' => intval($sDoctor),
                            );
                            $obj->data($data)->add();
                        }
                    }
                }
            }


            //给医生添加用户名密码和角色  微信登录时需要用到
//            $wechat_user        = D('wechat_user');
//            $data3['user_name'] = $_POST["phone"];
//            $data3['password']  = substr($_POST["phone"], -4);
//            $data3['role_id']   = 2;
//            $data3['identity_id'] = $result;
//            $wechat_user->data($data3)->add();
            if ($result) {
                $this->success(L('operation_success'), '', '', 'add');
            } else {
                $this->error(L('operation_failure'));
            }
        } else {
            $department           = D('department');
            $this->departmentData = $department->select();
            $province = D('province');
            $this->province = $province->select();
            $transferHospital = D('transfer_hospital');
            $this->transferHospital = $transferHospital->select();
            $this->assign('show_header', false);
            $this->display();
        }
    }

    public function edit()
    {
        if (isset($_POST['dosubmit'])) {
            $saler  = D('saler');
            $result = $saler->where("name='" . $_POST['name'] . "' and id<>" . $_POST["id"])->count();
            if ($result) {
                $this->error('该销售代表已存在');
            }
            if (false === $saler->create()) {
                $this->error($saler->getError());
            }
            $result = $saler->save();
            if(false !== $result){
                $this->success(L('operation_success'), '', '', 'edit');
            }else{
                $this->error(L('operation_failure'));
            }
//            $saler->name       = $_POST["name"];
//            $saler->sex        = $_POST["sex"];
//            $saler->level_name = $_POST["level_name"];
//            $saler->phone      = $_POST["phone"];
//            $saler->email      = $_POST["email"];
//
//            $result = $saler->where('id=' . $_POST['id'])->save();
//            if (false !== $result) {
//                $this->success(L('operation_success'), '', '', 'edit');
//            } else {
//                $this->error(L('operation_failure'));
//            }
        } else {
            if (isset($_GET['id'])) {
                $id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->error('参数错误');
            }
            $saler = D('saler');
            $saler = $saler->where('id=' . $id)->find();
            $department           = D('department');
            $this->departmentData = $department->select();

            $this->assign('saler', $saler);
            $this->assign('show_header', false);
            $this->display();
        }
    }

    function delete()
    {
        if ((!isset($_GET['id']) || empty($_GET['id'])) && (!isset($_POST['id']) || empty($_POST['id']))) {
            $this->error('请选择要删除的医疗中心！');
        }
        $saler = D('saler');
        if (isset($_POST['id']) && is_array($_POST['id'])) {
            $ids = implode(',', $_POST['id']);
            $saler->delete($ids);
        } else {
            $id = intval($_GET['id']);
            $saler->delete($id);
        }
        $this->success(L('operation_success'));
    }

    function listDoctor()
    {
        $result = [];
        if(isset($_POST['transfer_hospital_id'])) {
            $doctor     = M('transfer_doctor');
            $result       = $doctor->where('hospital_id=' . $_POST['transfer_hospital_id'])->select();
        }
        $this->ajaxReturn ($result,'JSON');
    }


}

?>