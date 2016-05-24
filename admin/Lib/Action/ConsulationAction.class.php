<?php
// +----------------------------------------------------------------------
// | APPCMS 移动应用软件后台管理系统
// +----------------------------------------------------------------------
// | provide by ：shiraz-soft.com
// 
// +----------------------------------------------------------------------
// | Author: Shirazsoft APP Team
// +----------------------------------------------------------------------

class ConsulationAction extends BaseAction
{
    function index()
    {
        $consultation = D('consultation');
        import("ORG.Util.Page");
        $where='where 1=1';
        if($_POST['begin_date']){
            $where=$where.' and t1.create_time>'.strtotime($_POST['begin_date']);
        }
        if($_POST['end_date']){
            $where=$where.' and t1.create_time<'.strtotime($_POST['end_date']);
        }
        if($_POST['status']){
            $where=$where.' and t1.handle_type='.$_POST['status'];
        }
        if($_POST['phone']){
            $where=$where.' and t1.phone='.$_POST['phone'];
        }
        $sql = 'select t1.*,t2.name as doctor_name,t4.name as center_name from cms_consultation t1 left join cms_doctor t2 on t1.doctor_id=t2.id
left join cms_medical_center_doctor t3 on t2.id=t3.doctor_id
left join cms_medical_center t4 on t3.medical_center_id=t4.id '.$where;
        $count_sql="select count(1) as count from (".$sql.")t";
        $count = $consultation->query($count_sql);
        $p     = new Page($count[0]["count"], 20);
        $consulationData = $consultation->query($sql.' limit '.$p->firstRow.','.$p->listRows);
        $page   = $p->show();
        $this->assign('page', $page);
        $this->calendar=1;

        $this->assign('consulationList', $consulationData);
        $this->begin=$_POST['begin_date'];
        $this->end=$_POST['end_date'];
        $this->display();
    }


    function add()
    {
        if (isset($_POST['dosubmit'])) {
            $doctor = D('transfer_doctor');
            $result = $doctor->where("name='" . $_POST['name'] . "'")->count();
            if ($result) {
                $this->error('该医生已存在');
            }
            if (empty($_FILES["img"]["name"])) {
                $data['img'] = "";
            } else {
                //上传图片
                if (uploadImg($msg, $name) == false) {
                    $this->error($msg);
                }
            }
            $data['img']        = $name;
            $data['name']       = $_POST["name"];
            $data['sex']       = $_POST["sex"];
            $data['level_name'] = $_POST["level_name"];
            $data['phone']      = $_POST["phone"];
            $data['email']      = $_POST["email"];
//            $data["medical_center_id"] = $_POST["medical_center_id"];

//            $doctor->img = $name;
//            $doctor->create();
            $result = $doctor->data($data)->add();

//            //添加关系
//            $medical_center_doctor      = D('medical_center_doctor');
//            $data2["medical_center_id"] = $_POST["medical_center_id"];
//            $data2["doctor_id"]         = $result;
//            $medical_center_doctor->data($data2)->add();

            //给医生添加用户名密码和角色  微信登录时需要用到
            $wechat_user        = D('wechat_user');
            $data3['user_name'] = $_POST["phone"];
            $data3['password']  = substr($_POST["phone"], -4);
            $data3['role_id']   = 3;
            $wechat_user->data($data3)->add();

            if ($result) {
                $this->success(L('operation_success'), '', '', 'add');
            } else {
                $this->error(L('operation_failure'));
            }
        } else {
//            $medicalCenter           = D('medical_center');
//            $this->medicalCenterData = $medicalCenter->select();
//            medicalCenterData
            $this->assign('show_header', false);
            $this->display();
        }
    }

    public function edit()
    {
        if (isset($_POST['dosubmit'])) {
            $doctor = M('transfer_doctor');
            $result = $doctor->where("name='" . $_POST['name'] . "' and id<>" . $_POST["id"])->count();
            if ($result) {
                $this->error('该医生已存在');
            }
            if (!empty($_FILES["img"]["name"])) {
                if (uploadImg($msg, $name) == false) {
                    $this->error($msg);
                } else {
                    $doctor->img = $name;
                }
            } else {
                $doctor->img = $_POST["txtImg"];
            }
            $doctor->name              = $_POST["name"];
            $doctor->sex               = $_POST["sex"];
            $doctor->level_name        = $_POST["level_name"];
            $doctor->phone             = $_POST["phone"];
            $doctor->email             = $_POST["email"];
            $doctor->medical_center_id = $_POST["medical_center_id"];
            $result                    = $doctor->where('id=' . $_POST['id'])->save();

//            //更新关联表数据
//            $cms_medical_center_doctor = D('cms_medical_center_doctor');
//            $cms_medical_center_doctor->where("doctor_id=" . $_POST["id"])->delete();
//            $data1["medical_center_id"] = $_POST["medical_center_id"];
//            $data1["doctor_id"]         = $_POST["id"];
//            $cms_medical_center_doctor->data($data1)->add();

            if (false !== $result) {
                $this->success(L('operation_success'), '', '', 'edit');
            } else {
                $this->error(L('operation_failure'));
            }
        } else {
            if (isset($_GET['id'])) {
                $id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->error('参数错误');
            }
            $doctor = D('transfer_doctor');
            $doctor = $doctor->where('id=' . $id)->find();

//            $medical_center_doctor = D('medical_center_doctor');
//            //查询该医生所在医疗中心
//            $result          = $medical_center_doctor->where('doctor_id=' . $id)->select();
//            $this->medicalId = $result[0]['medical_center_id'];
//
//            $medicalCenter           = D('medical_center');
//            $this->medicalCenterData = $medicalCenter->select();
//              dump($doctor);
            $this->assign('doctor', $doctor);
            $this->assign('show_header', false);
            $this->display();
        }
    }

    function delete()
    {
        if ((!isset($_GET['id']) || empty($_GET['id'])) && (!isset($_POST['id']) || empty($_POST['id']))) {
            $this->error('请选择要删除的数据！');
        }
        $doctor = D('consultation');
        if (isset($_POST['id']) && is_array($_POST['id'])) {
            $ids = implode(',', $_POST['id']);
            $doctor->delete($ids);
        } else {
            $id = intval($_GET['id']);
            $doctor->delete($id);
        }
        $this->success(L('operation_success'));
    }

//    function  medicalTime($id)
//    {
//        $medical_center_time = D('medical_center_time');
//        $result              = $medical_center_time->query("SELECT t1.*, t2.begin_time,t2.end_time FROM cms_medical_center_time t1 LEFT JOIN cms_medical_time t2
// ON t1.medical_time_id=t2.`id`
//WHERE t1.`medical_center_id`=(
//	SELECT medical_center_id FROM `cms_medical_center_doctor` WHERE doctor_id=".$id.")");
//        if ($result == null) {
//            $medical_time = D('medical_time');
//            $result=$medical_time->query("SELECT t1.*,t1.id AS medical_time_id FROM cms_medical_time t1");
//        }
//        $this->data          = $result;
//        $this->display("medicalTime");
//    }

    function detail($id)
    {
        $doctor       = M('transfer_doctor');
        $result       = $doctor->where('id=' . $id)->select();
        $this->doctor = $result[0];
//        dump($result);
        $this->display();
    }
    function piclist()
    {
        $Pic = D('Pic');
        import("ORG.Util.Page");
        //$where['type']=1;
        $count = $Pic->where($where)->count();
        $p     = new Page($count, 30);
        $order = $Pic->where($where)->order('time')->limit($p->firstRow.",".$p->listRows)->select();
        $page   = $p->show();
        $this->assign('page', $page);
        $this->assign('page', $page);
        $this->assign('orderList', $order);
        $this->display();
    }
    // 治疗确认
    public function ziliaook()
    {
        $consultation = D('treatment');
        $where='';
        if ($_POST) {
            if($_POST['tel']){
             //$where=$where.' and cms_treatment.tel='.$_POST['tel'];
            $sql ="select * from cms_treatment where cms_treatment.tel=".$_POST['tel'];
            $consulationData = $consultation->query($sql);
            }
        }else{
            $sql ="select * from cms_treatment";
            $consulationData = $consultation->query($sql);
        }
        
      /*  $sql ="select * from cms_treatment".$where;
        $consulationData = $consultation->query($sql);*/
        //$consulationData = $consultation->->select();
        /*import("ORG.Util.Page");
        $sql = 'SELECT * FROM `cms_treatment';
        $count_sql="select count(1) as count from (".$sql.")t";
        $count = $consultation->query($count_sql);
        $p     = new Page($count[0]["count"], 20);
        $consulationData = $consultation->query($sql.' limit '.$p->firstRow.','.$p->listRows);
        $page   = $p->show();
        $this->assign('page', $page);
        $this->calendar=1;*/
        //var_dump($consulationData);exit();
        //var_dump($consultation->getlastSql());exit();
        $this->assign('consulationList', $consulationData);
       /* $count = count($consulationData);
        for ($i=0; $i <$count ; $i++) { 
            $openid = $consulationData[$i]['openid'];
        }*/
        /*$user = D("wechat_user");
        $data['open_id'] = $openid;
        $useList = $user->where('open_id='.$data)->select();
        var_dump($openid);exit();
        $this->assign("openid", $useList[0]['open_id']);*/
        $this->display();
    }
}

?>