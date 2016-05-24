<?php
// +----------------------------------------------------------------------
// | APPCMS 移动应用软件后台管理系统
// +----------------------------------------------------------------------
// | provide by ：shiraz-soft.com
// 
// +----------------------------------------------------------------------
// | Author: Shirazsoft APP Team
// +----------------------------------------------------------------------

class QrcodeConsulationAction extends BaseAction
{
    function index()
    {
        $qrcode_consulation = D('qrcode_consulation');
       import("ORG.Util.Page");
        $count = $qrcode_consulation->count();
        $p     = new Page($count, 30);
        if ($_POST) {
    
            
            $end_date = str_replace('-', '', $_POST['end_date']);
            // 如查询的条件是电话号码
            if ($phone = $_POST['phone']) {
                $sql = "select * from cms_qrcode_consultation where cms_qrcode_consultation.phone='".$phone."'";
            }else if ($_POST['start_date'] ) {
                // 如果提交的时间开始时间
                $start_date = str_replace('-', '', $_POST['start_date']);
                $sql = "select * from cms_qrcode_consultation where cms_qrcode_consultation.create_time>'".$start_date."'";
                /*$qrcodeConsulationData = $qrcode_consulation->query($sql);
                var_dump($qrcode_consulation->getlastSql());exit("dfgdsfg");*/
            }
            
            

        }
        else{
           /* $sql = 'select t1.*,t3.name as transfer_doctor_name,t4.province as province_name,t5.name as center_name,t6.name as doctor_name from cms_qrcode_consultation t1
               inner join cms_wechat_user t2 on t1.doctor_open_id =t2.open_id
               inner join cms_transfer_doctor t3 on t2.identity_id=t3.id
                inner join cms_province t4 on t1.province_id=t4.provinceID
                inner join cms_medical_center t5 on t1.center_id=t5.id
                inner join cms_doctor t6 on t1.doctor_id=t6.id limit '.$p->firstRow.','.$p->listRows;*/
                $sql = "select a.id, a.create_time, a.name, a.sex, a.code, a.phone, a.zztime, b.province, c.name as `center_name`, e.name as `doctor_name`, g.name as `transfer_name`
                from cms_qrcode_consultation a 
                left join cms_province b on a.province_id=b.provinceID
                left join cms_medical_center c on c.id=a.center_id
                left join cms_medical_center_doctor d on a.doctor_id=d.doctor_id
                left join cms_doctor e on d.doctor_id=e.id
                left join cms_wechat_user f on a.doctor_open_id=f.open_id
                left join cms_transfer_doctor g on f.user_name=g.phone";
        }

        $qrcodeConsulationData = $qrcode_consulation->query($sql);
//        print_r($qrcodeConsulationData);exit;
//        echo '<pre>';
//        print_r($qrcodeConsulationData);
//        echo '</pre>';
        foreach($qrcodeConsulationData as &$row) {
            $row['create_time'] = date("Y年m月d日H点i分", $row['create_time']);
        }
        $page   = $p->show();
        $this->assign('page', $page);
        $this->assign('consulationList', $qrcodeConsulationData);
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
        $doctor = D('qrcode_consultation');
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
}

?>