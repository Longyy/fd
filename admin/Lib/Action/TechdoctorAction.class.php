<?php
// +----------------------------------------------------------------------
// | APPCMS 移动应用软件后台管理系统
// +----------------------------------------------------------------------
// | provide by ：shiraz-soft.com
// 
// +----------------------------------------------------------------------
// | Author: Shirazsoft APP Team
// +----------------------------------------------------------------------

class TechdoctorAction extends BaseAction
{
    function index()
    {
        $doctor = D('technician_doctor');
        import("ORG.Util.Page");
        $count = $doctor->count();
        $p     = new Page($count, 30);

        /*$sql = 'SELECT t1.*,t3.`name` AS center_name,t4.count_num FROM `technician_doctor` t1 LEFT JOIN `cms_medical_center_doctor` t2 ON t1.`id`=t2.`doctor_id`
LEFT JOIN `cms_medical_center` t3 ON t2.`medical_center_id`=t3.`id`
left join (select doctor_id,count(*) as count_num from cms_consultation group by doctor_id) t4 on t1.id=t4.doctor_id limit ' . $p->firstRow . ',' . $p->listRows;
        $doctor = $doctor->query($sql);*/
        $doctor = $doctor->select();
//        $doctor = $doctor->limit($p->firstRow . ',' . $p->listRows)->select();
        $page   = $p->show();
        $this->assign('page', $page);
        $big_menu       = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=Techdoctor&a=add\', title:\'添加技师\', width:\'600\', height:\'350\', lock:true}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);', '添加技师');
        $this->big_menu = $big_menu;

        $this->assign('doctorList', $doctor);
        $this->display();
    }


    function add()
    {
        if (isset($_POST['dosubmit'])) {
            $doctor = D('technician_doctor');
            $result = $doctor->where("name='" . $_POST['name'] . "'")->count();
            if ($result) {
                $this->error('该医生已存在');
            }
            $wechat_user = D('wechat_user');
            $result      = $wechat_user->where("user_name='" . $_POST['phone'] . "'")->count();
            if ($result) {
                $this->error('手机号码已存在，请重新输入');
            }
            // 医生图片
            if (empty($_FILES["img"]["name"])) {
                $data['img'] = "";
            } else {
                //上传图片
                if (uploadImg($msg, $name) == false) {
                    $this->error($msg);
                }
            }
            // 医生执照证书图片
            if (empty($_FILES["certificate"]["name"])) {
                $data['certificate'] = "";
            }else{
                if (uploadImg($msg, $zhengshu) == false) {
                    $this->error($msg);
                }
            }
            $data['certificate'] = $zhengshu;
            $data['img']        = $name;
            $data['name']       = $_POST["name"];
            $data['sex']        = $_POST["sex"];
            $data['titleName']  = $_POST["titleName"];
            $data['deName']     = $_POST["deName"];
            $data['phone']      = $_POST["phone"];
            $data['email']      = $_POST["email"];
//            $data["medical_center_id"] = $_POST["medical_center_id"];

//            $doctor->img = $name;
//            $doctor->create();
           // 技师id编号
            $doctorId = $doctor->data($data)->add();

            //添加关系
            $medical_center_doctor      = D('medical_center_doctor');
            $data2["medical_center_id"] = $_POST["medical_center_id"];
            $data2["technician_id"]         = $doctorId;
            /*var_dump($data2['technician_id']);var_dump($data2['medical_center_id']);exit();*/
            $medical_center_doctor->data($data2)->add();
            //给医生添加用户名密码和角色  微信登录时需要用到
            $wechat_user          = D('wechat_user');
            $data3['user_name']   = $_POST["phone"];
            $data3['password']    = substr($_POST["phone"], -4);
            $data3['role_id']     = 5;
            $data3['identity_id'] = $doctorId;
            $wechat_user->data($data3)->add();

            if ($wechat_user) {
                $this->success(L('operation_success'), '', '', 'add');
            } else {
                $this->error(L('operation_failure'));
            }
        } else {
            $medicalCenter           = D('medical_center');
            $this->medicalCenterData = $medicalCenter->select();
            // 医院职称
            $title = D("doctor_title");
            $this->titleName    = $title->select();
//            medicalCenterData
            // 医院科室
            $department = D('hospital_department');
            $name = $department->select();
            /*var_dump($name);exit();*/
            $this->assign('name',$name);
            $this->assign('show_header', false);
            $this->display();
        }
    }

    public function edit()
    {
        if (isset($_POST['dosubmit'])) {
            $doctor = M('technician_doctor');
            $result = $doctor->where("name='" . $_POST['name'] . "' and id<>" . $_POST["id"])->count();
            if ($result) {
                $this->error('该医生已存在');
            }
            $wechat_user = D('wechat_user');
            $result      = $wechat_user->where("user_name='" . $_POST['phone'] . "'and identity_id<>" . $_POST["id"])->count();
            if ($result) {
                $this->error('手机号码已存在，请重新输入');
            }
            // 医生图片
            if (!empty($_FILES["img"]["name"])) {
                if (uploadImg($msg, $name) == false) {
                    $this->error($msg);
                } else {
                    $doctor->img = $name;
                }
            } else {
                $doctor->img = $_POST["txtImg"];
            }
            // 医生职业证书
            if (!empty($_FILES["certificate"]["name"])) {
                if (uploadImg($msg, $name) == false) {
                    $this->error($msg);
                } else {
                    $doctor->certificate = $name;
                }
            } else {
                $doctor->certificate = $_POST["certificate"];
            }
            
            $doctor->name              = $_POST["name"];
            $doctor->sex               = $_POST["sex"];
            $doctor->deName            = $_POST["deName"];
            $doctor->titleName         = $_POST['titleName'];
            $doctor->phone             = $_POST["phone"];
            $doctor->email             = $_POST["email"];
            $doctor->medical_center_id = $_POST["medical_center_id"];
            $result                    = $doctor->where('id=' . $_POST['id'])->save();

            //更新关联表数据
            $cms_medical_center_doctor = D('cms_medical_center_doctor');
            $cms_medical_center_doctor->where("doctor_id=" . $_POST["id"])->delete();
            $data1["medical_center_id"] = $_POST["medical_center_id"];
            $data1["doctor_id"]         = $_POST["id"];
            $cms_medical_center_doctor->data($data1)->add();

            if (false !== $result) {
                $this->success(L('operation_success'), '', '', 'edit');
            } else {
                $this->error(L('operation_failure'));
            }
        } else {
            if (isset($_GET['id'])) {
                $id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->error('参数错误');
            }
            $doctor = D('technician_doctor');
            $doctor = $doctor->where('id=' . $id)->find();

            $medical_center_doctor = D('medical_center_doctor');
            //查询该医生所在医疗中心
            $result          = $medical_center_doctor->where('doctor_id=' . $id)->select();
            $this->medicalId = $result[0]['medical_center_id'];

            $medicalCenter           = D('medical_center');
            $this->medicalCenterData = $medicalCenter->select();
            // 医院职称
            $title = D("doctor_title");
            $this->titleName    = $title->select();
//            medicalCenterData
            // 医院科室
            $department = D('hospital_department');
            $name = $department->select();
            /*var_dump($name);exit();*/
            $this->assign('name',$name);
            $this->assign('doctor', $doctor);
            $this->assign('show_header', false);
            $this->display();
        }
    }

    function delete()
    {
        if ((!isset($_GET['id']) || empty($_GET['id'])) && (!isset($_POST['id']) || empty($_POST['id']))) {
            $this->error('请选择要删除的医疗中心！');
        }
        $doctor     = D('technician_doctor');
        $wechatUser = D('wechat_user');
        if (isset($_POST['id']) && is_array($_POST['id'])) {
            $ids = implode(',', $_POST['id']);
            $doctor->delete($ids);
            //同时删除微信用户数据
            foreach ($_POST['id'] as $val) {
                $wechatUser->where(array('role_id' => 1, 'identity_id' => $val))->delete();
            }
        } else {
            $id = intval($_GET['id']);
            $doctor->delete($id);
        }
        $this->success(L('operation_success'));
    }

    function  medicalTime($id)
    {
        $medical_center_time = D('medical_center_time');
        $result              = $medical_center_time->query("SELECT t1.*, t2.begin_time,t2.end_time FROM cms_medical_center_time t1 LEFT JOIN cms_medical_time t2
 ON t1.medical_time_id=t2.`id`
WHERE t1.`medical_center_id`=(
	SELECT medical_center_id FROM `cms_medical_center_doctor` WHERE doctor_id=" . $id . ")");
        if ($result == null) {
            $medical_time = D('medical_time');
            $result       = $medical_time->query("SELECT t1.*,t1.id AS medical_time_id FROM cms_medical_time t1");
        }
        $this->data = $result;
        $this->display("medicalTime");
    }

    function detail($id)
    {
        $doctor       = M('technician_doctor');
        $result       = $doctor->where('id=' . $id)->select();
        $this->doctor = $result[0];
//        dump($result);
        $this->display();
    }

    //设置医生工作时间
    function workTimeList()
    {
        $doctor_work = D('doctor_work_time');
        import("ORG.Util.Page");
        $code = $_GET['keyword'];
        if ($code) {
            $where['code'] = array('like', "%$code%");;
        }
        $count = $doctor_work->where($where)->count();
        $p     = new Page($count, 30);

        $sql = 'select t1.*,t2.name as doctor_name,t3.name as center_name from cms_doctor_work_time t1 left join cms_doctor t2 on t1.doctor_id=t2.id left join cms_medical_center t3 on t1.center_id=t3.id limit ' . $p->firstRow . ',' . $p->listRows;
//        $doctor = $doctor->limit($p->firstRow . ',' . $p->listRows)->select();
        $doctorWork = $doctor_work->query($sql);
        $page       = $p->show();
        $this->assign('page', $page);
        $big_menu       = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=Doctor&a=addDoctorWork\', title:\'添加技师门诊时间\', width:\'600\', height:\'350\', lock:true}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);', '添加');
        $this->big_menu = $big_menu;
//dump($doctorWork);
        $this->assign('doctorWorkList', $doctorWork);
        $this->display();
    }

    function  addDoctorWork()
    {
        if (isset($_POST['dosubmit'])) {
            if (!$_POST['doctor_id']) {
                $this->error(L('请选择医生'));
                return;
            }

            if (!$_POST['work_date']) {
                $this->error(L('请填写上班时间'));
                return;
            }

            if (!$_POST['forenoon'] && !$_POST['afternoon']) {
                $this->error(L('请选择区间'));
                return;
            }
            $doctorWork = M('doctor_work_time');
            if (!$doctorWork->where(array('doctor_id' => $_POST['doctor_id'], 'work_date' => strtotime($_POST['work_date'])))->find()) {
                $result = $doctorWork->data(
                    array('doctor_id'        => $_POST['doctor_id'],
                          'center_id'        => $_POST['medical_center_id'],
                          'work_date'        => strtotime($_POST['work_date']),
                          'forenoon'         => $_POST['forenoon'] ? 1 : 0,
                          'forenoon_number'  => $_POST['forenoon_number'],
                          'afternoon'        => $_POST['afternoon'] ? 1 : 0,
                          'afternoon_number' => $_POST['afternoon_number']
                    ))->add();
                if ($result) {
                    $this->success(L('operation_success'), '', '', 'add');
                } else {
                    $this->error(L('operation_failure'));
                }

            } else {
                $this->error(L('该医生该日期已添加不能重复添加'));
            }
        } else {
            $medicalCenter           = D('medical_center');
            $this->medicalCenterData = $medicalCenter->select();

            $this->display();
        }
    }

    function  editDoctorWork($id)
    {
        if (isset($_POST['dosubmit'])) {
            if (!$_POST['doctor_id']) {
                $this->error(L('请选择医生'));
                return;
            }

            if (!$_POST['work_date']) {
                $this->error(L('请填写上班时间'));
                return;
            }

            if (!$_POST['forenoon'] && !$_POST['afternoon']) {
                $this->error(L('请选择区间'));
                return;
            }
            $doctorWork = M('doctor_work_time');
            if (!$doctorWork->where(array('doctor_id' => $_POST['doctor_id'], 'work_date' => strtotime($_POST['work_date']), 'id' => array('neq', $id)))->find()) {
//                $sql    = $doctorWork->getLastSql();
                $data   = array(
                    'id'               => $id,
                    'doctor_id'        => $_POST['doctor_id'],
                    'work_date'        => strtotime($_POST['work_date']),
                    'forenoon'         => $_POST['forenoon'] ? 1 : 0,
                    'forenoon_number'  => $_POST['forenoon_number'],
                    'afternoon'        => $_POST['afternoon'] ? 1 : 0,
                    'afternoon_number' => $_POST['afternoon_number']
                );
                $result = $doctorWork->save($data);
//                $sql    = $doctorWork->getLastSql();
//                if ($result) {
                    $this->success(L('operation_success'), '', '', 'edit');
//                } else {
//                    $this->error(L('operation_failure'));
//                }

            } else {
                $this->error(L('该医生该日期已添加不能重复添加'));
            }
        } else {
            $doctorWork             = M('doctor_work_time');
            $doctorWorkModel        = $doctorWork->where("id=$id")->find();
            $this->doctor_work_time = $doctorWorkModel;
//            dump($this->doctor_work_time);
            $medicalCenter           = D('medical_center');
            $this->medicalCenterData = $medicalCenter->select();

            //查询医生
            $Model            = new Model();
            $sql              = "SELECT t.doctor_id,t1.name as doctor_name FROM `cms_medical_center_doctor` t left join `cms_doctor` t1 on t.doctor_id=t1.id  WHERE  t.medical_center_id=" . $doctorWorkModel ['center_id'];
            $doctorData       = $Model->query($sql);
            $this->doctorData = $doctorData;


            $this->display();
        }
    }

    function getDoctorById($id)
    {
        $Model      = new Model();
        $sql        = "SELECT t.doctor_id,t1.name as doctor_name FROM `cms_medical_center_doctor` t left join `cms_doctor` t1 on t.doctor_id=t1.id  WHERE  t.medical_center_id=" . $id;
        $doctorData = $Model->query($sql);
        return $this->ajaxReturn($doctorData);
    }

    function deleteDoctorWork()
    {
        if ((!isset($_GET['id']) || empty($_GET['id'])) && (!isset($_POST['id']) || empty($_POST['id']))) {
            $this->error('请选择要删除的数据！');
        }
        $doctor = D('doctor_work_time');

        if (isset($_POST['id']) && is_array($_POST['id'])) {
            $ids = implode(',', $_POST['id']);
            $doctor->delete($ids);
        }
        $this->success(L('operation_success'));
    }
}

?>