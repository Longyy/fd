<?php
// +----------------------------------------------------------------------
// | APPCMS 移动应用软件后台管理系统
// +----------------------------------------------------------------------
// | provide by ：shiraz-soft.com
// 
// +----------------------------------------------------------------------
// | Author: Shirazsoft APP Team
// +----------------------------------------------------------------------

class TransferDoctorAction extends BaseAction
{
    function index()
    {
        $doctor = D('transfer_doctor');
        import("ORG.Util.Page");
        $count = $doctor->count();
        $p     = new Page($count, 30);

//        $doctor = $doctor->limit($p->firstRow . ',' . $p->listRows)->select();
        $sql    = 'select t.*,t4.province as province_name,t5.name as hospital from cms_transfer_doctor t
left join ( select identity_id,count(*) as count_num from cms_qrcode_consultation t1
left join cms_wechat_user t2 on t1.doctor_open_id =t2.open_id where t2.role_id=3 group by t2.identity_id )t3
 on t.id=t3.identity_id left join cms_province t4 on t.province_id=t4.provinceID
 left join cms_transfer_hospital t5 on t.hospital_id=t5.id limit ' . $p->firstRow . ',' . $p->listRows;
        $doctor = $doctor->query($sql);
        $page   = $p->show();
        $this->assign('page', $page);
        $big_menu       = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=TransferDoctor&a=add\', title:\'添加医生\', width:\'600\', height:\'350\', lock:true}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);', '添加医生');
        $this->big_menu = $big_menu;

        $this->assign('doctorList', $doctor);
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
            // 医生职业证书
            if (empty($_FILES["zyzs"]["name"])) {
                $data['zyzs'] = "";
            } else {
                //上传图片
                if (uploadImg($msg, $zs) == false) {
                    $this->error($msg);
                }
            }
            $data['zyzs']        = $zs;
            $data['img']         = $name;
            $data['name']        = $_POST["name"];
            $data['sex']         = $_POST["sex"];
            $data['titleName']  = $_POST["titleName"];
            $data['deName']     = $_POST['deName'];
            $data['phone']       = $_POST["phone"];
            $data['email']       = $_POST["email"];
            $data['hospital_id']    = $_POST["hospital_id"];
            $data['province_id'] = $_POST["province_id"];
//            $data["medical_center_id"] = $_POST["medical_center_id"];

//            $doctor->img = $name;
//            $doctor->create();
            
            $doctorId = $doctor->data($data)->add();
//            //添加关系
//            $medical_center_doctor      = D('medical_center_doctor');
//            $data2["medical_center_id"] = $_POST["medical_center_id"];
//            $data2["doctor_id"]         = $result;
//            $medical_center_doctor->data($data2)->add();

            //给医生添加用户名密码和角色  微信登录时需要用到
            $wechat_user          = D('wechat_user');
            $data3['user_name']   = $_POST["phone"];
            $data3['password']    = substr($_POST["phone"], -4);
            $data3['role_id']     = 3;
            $data3['identity_id'] = $doctorId;
            $result               = $wechat_user->data($data3)->add();
            if ($result) {
                $this->success(L('operation_success'), '', '', 'add');
            } else {
                $this->error(L('operation_failure'));
            }
        } else {
            $hospital           = D('transfer_hospital');
            $this->hospitalData = $hospital->select();
//            medicalCenterData
            $provinceModel      = D('province');
            $this->provinceData = $provinceModel->select();
            // 医生职称
            $title = D("doctor_title");
            $this->titleName    = $title->select();
            // 医生科室
            $deName             = D("hospital_department");
            $this->hospitalDepartment = $deName->select();      
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
            $wechat_user = D('wechat_user');
            $result      = $wechat_user->where("user_name='" . $_POST['phone'] . "'and identity_id<>" . $_POST["id"])->count();
            if ($result) {
                $this->error('手机号码已存在，请重新输入');
            }
            // 医生图片上传
            if (!empty($_FILES["img"]["name"])) {
                if (uploadImg($msg, $name) == false) {
                    $this->error($msg);
                } else {
                    $doctor->img = $name;
                }
            } else {
                $doctor->img = $_POST["txtImg"];
            }
            // 医生职业证书上传
            if (!empty($_FILES["zyzs"]["name"])) {
                if (uploadImg($msg, $name) == false) {
                    $this->error($msg);
                } else {
                    $doctor->zyzs = $name;
                }
            } else {
                $doctor->zyzs = $_POST["txtImg"];
            }
            $doctor->name              = $_POST["name"];
            $doctor->sex               = $_POST["sex"];
            $doctor->titleName        = $_POST["titleName"];
            $doctor->deName           = $_POST['deName'];
            $doctor->phone             = $_POST["phone"];
            $doctor->email             = $_POST["email"];
            $doctor->medical_center_id = $_POST["medical_center_id"];
            $doctor->hospital_id          = $_POST["hospital_id"];
            $doctor->province_id       = $_POST["province_id"];

            $result = $doctor->where('id=' . $_POST['id'])->save();

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

            $provinceModel       = D('province');
            $this->provinceData = $provinceModel->select();

            $hospital           = D('transfer_hospital');
            $this->hospitalData = $hospital->select();

            // 医生职称
            $title = D("doctor_title");
            $this->titleName = $title->select();
            // 医生科室
            $deName             = D("hospital_department");
            $this->hospitalDepartment = $deName->select();
            $this->display();
        }
    }

    function delete()
    {
        if ((!isset($_GET['id']) || empty($_GET['id'])) && (!isset($_POST['id']) || empty($_POST['id']))) {
            $this->error('请选择要删除的数据！');
        }
        $doctor = D('transfer_doctor');
        $wechatUser=D('wechat_user');
        if (isset($_POST['id']) && is_array($_POST['id'])) {
            $ids = implode(',', $_POST['id']);
            $doctor->delete($ids);
            //同时删除微信用户数据
            foreach ($_POST['id'] as $val) {
                $wechatUser->where(array('role_id'=>3,'identity_id'=>$val))->delete();
            }


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

    function listHospital()
    {
        $result = [];
        if(isset($_POST['provinceId'])) {
            $province     = M('transfer_hospital');
            $result       = $province->where('province_id=' . $_POST['provinceId'])->select();
        }
        $this->ajaxReturn ($result,'JSON');
    }
}

?>