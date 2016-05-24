<?php
// +----------------------------------------------------------------------
// | APPCMS 移动应用软件后台管理系统
// +----------------------------------------------------------------------
// | provide by ：shiraz-soft.com
// 
// +----------------------------------------------------------------------
// | Author: Shirazsoft APP Team
// +----------------------------------------------------------------------

class MedicalCenterAction extends BaseAction
{
    function index()
    {
        $prex = C('DB_PREFIX');
        $medicalCenter = D('medical_center');
        import("ORG.Util.Page");
        $count = $medicalCenter->count();
        $p     = new Page($count, 30);

        $medicalCenterList = $medicalCenter->field($prex.'medical_center.*,'.$prex.'province.province as province_name')->join('LEFT JOIN '.$prex.'province ON '.$prex.'medical_center.province_id = '.$prex.'province.provinceID ')-> limit($p->firstRow . ',' . $p->listRows)->select();
//        echo $medicalCenter->getLastSql();

        $page = $p->show();
        $this->assign('page', $page);
        $big_menu       = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=MedicalCenter&a=add\', title:\'添加医疗中心\', width:\'800\', height:\'500\', lock:true}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);', '添加医疗中心');
        $this->big_menu = $big_menu;

        $this->assign('medicalCenterList', $medicalCenterList);
        $this->display();
    }

    function map()
    {
        $medicalCenter     = D('medical_center');
        $medicalCenterList = $medicalCenter->select();
        $info              = array();
        foreach ($medicalCenterList as $val) {
            $sql    = "select count(*)as count_num from cms_consultation where doctor_id in (select doctor_id from cms_medical_center_doctor  where medical_center_id=" . $val['id'] . ") and create_time>" . strtotime(date('Y-m-01', strtotime('-1 month'))) . " and create_time<" . strtotime(date('Y-m-t', strtotime('-1 month')));
            $result = $medicalCenter->query($sql);
            array_push($info, array($val['longitude'], $val['latitude'], $val['name'], $result[0]['count_num']));
        }
        $this->info = json_encode($info);
        //以第一个地理位置为中心点定位坐标
        $this->lonAndLat = $medicalCenterList[0]['longitude'] . ',' . $medicalCenterList[0]['latitude'];


        $this->display();
    }

    function add()
    {
        if (isset($_POST['dosubmit'])) {
            mysql_query("SET name UTF8");
            $medicalCenter = D('medical_center');
            $result        = $medicalCenter->where("name='" . $_POST['name'] . "'")->count();
            if ($result) {
                $this->error('该医疗中心已存在');
            }
            $medicalCenter->create();
            $medicalCenter->setup_date = strtotime($_POST["setup_date"]);
            $result                    = $medicalCenter->add();
            if ($result) {
                $this->success(L('operation_success'), '', '', 'add');
            } else {
                $this->error(L('operation_failure'));
            }
        } else {
            $provinceModel       = D('province');
            $this->provinceData = $provinceModel->select();
            $this->assign('show_header', false);
            $this->display();
        }
    }

    public function edit()
    {
        if (isset($_POST['dosubmit'])) {
            $medicalCenter               = M('medical_center');
            $medicalCenter->name         = $_POST["name"];
            $medicalCenter->address      = $_POST["address"];
            $medicalCenter->longitude    = $_POST["longitude"];
            $medicalCenter->latitude     = $_POST["latitude"];
            $medicalCenter->doctor_count = $_POST["doctor_count"];
            $medicalCenter->setup_date   = $_POST["setup_date"];
            $medicalCenter->info         = $_POST["info"];
            $medicalCenter->is_recommend = $_POST["is_recommend"];
            $medicalCenter->phone = $_POST["phone"];
            $medicalCenter->province_id = $_POST["province_id"];
//            $result = $medicalCenter->where("name='".$_POST['name']."' and id<>".$_POST["id"])->count();
//            if($result){
//                $this->error('该医疗中心已存在');
//            }
            $result = $medicalCenter->where('id=' . $_POST['id'])->save();
            if (false !== $result) {
                $this->success(L('operation_success'), '', '', 'edit');
            } else {
                $this->error(L('operation_failure'));
            }
        } else {
            if (isset($_GET['id'])) {
                $id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->error('参数错误');
            }
            $medicalCenter = D('medical_center');
            $medicalCenter = $medicalCenter->where('id=' . $id)->find();

            $this->info = json_encode(array(array($medicalCenter['longitude'], $medicalCenter['latitude'], $medicalCenter['name'])));
            //以第一个地理位置为中心点定位坐标
            $this->lonAndLat = $medicalCenter['longitude'] . ',' . $medicalCenter['latitude'];
            $this->assign('medicalCenter', $medicalCenter);
            $this->assign('show_header', false);

            $provinceModel       = D('province');
            $this->provinceData = $provinceModel->select();
            $this->display();
        }
    }

    function delete()
    {
        if ((!isset($_GET['id']) || empty($_GET['id'])) && (!isset($_POST['id']) || empty($_POST['id']))) {
            $this->error('请选择要删除的医疗中心！');
        }
        $medicalCenter = D('medical_center');
        if (isset($_POST['id']) && is_array($_POST['id'])) {
            $ids = implode(',', $_POST['id']);
            $medicalCenter->delete($ids);
        } else {
            $id = intval($_GET['id']);
            $medicalCenter->delete($id);
        }
        $this->success(L('operation_success'));
    }

    function addDoctor($id)
    {
        $Model                 = new Model();
        $doctor                = D('doctor');
        $this->doctorData      = $doctor->select();
        $medical_center_doctor = D('medical_center_doctor');
        $doctors               = array();
        //查询该医疗中心已经配置好的医生
//        $result              = $medical_center_doctor->query("SELECT t1.doctor_id,t2.name FROM  cms_medical_center_doctor t1 LEFT JOIN `cms_doctor` t2 ON t1.doctor_id=t2.id WHERE t1.medical_center_id=" . $id);
//        array_push($doctors, $result);
////        $this->selectDoctors = $result;
//        //查询尚未配置过医疗中心的医生
//        $result                 = $Model->query("SELECT t1.id as doctor_id,t1.name FROM  `cms_doctor` t1 WHERE t1.id NOT IN(SELECT doctor_id FROM `cms_medical_center_doctor` )");
//        array_push($doctors, $result);
        $doctors = $Model->query("SELECT t1.doctor_id,t2.name,1 FROM  cms_medical_center_doctor t1 LEFT JOIN `cms_doctor` t2 ON t1.doctor_id=t2.id
WHERE t1.medical_center_id=" . $id . "
UNION
SELECT t1.id AS doctor_id,t1.name,0 FROM  `cms_doctor` t1 WHERE t1.id NOT IN(SELECT doctor_id FROM `cms_medical_center_doctor` )");

        $this->doctorData = $doctors;
//        dump($doctors);
//        $this->notSelectDoctors = $result;
//        dump($result);
        $this->id = $id;
        $this->display("addDoctor");
    }

    function updateDoctor()
    {
        $Model = new Model();
        $Model->execute("delete from cms_medical_center_doctor where medical_center_id=" . $_POST["medical_center_id"]);
        foreach ($_POST["data"] as $index => $val) {
            $Model->execute("insert into cms_medical_center_doctor (medical_center_id,doctor_id) values (" . $_POST["medical_center_id"] . "," . $val . ")");
        }

        $this->ajaxReturn(true, "JSON2");
    }

    function setTimes($id)
    {
        $medical_center_time = D('medical_center_time');
        $result              = $medical_center_time->query("SELECT t1.*, t2.begin_time,t2.end_time FROM cms_medical_center_time t1 LEFT JOIN cms_medical_time t2 ON t1.medical_time_id=t2.`id`
WHERE t1.`medical_center_id`=" . $id);
        if ($result == null) {
            $medical_time = D('medical_time');
            $result       = $medical_time->query("SELECT t1.*,t1.id AS medical_time_id FROM cms_medical_time t1");
        }
        $this->data = $result;
        $this->id   = $id;
        $this->display("setTimes");
    }

    //更改上班时间
    /*
    $timesIds  时间段
    $day 所有时间段的上班状态数据
    */
    function addTimes($timesIds, $day, $medical_center_id)
    {
        $medical_center_time = D('medical_center_time');
        $medical_center_time->execute("delete from cms_medical_center_time where medical_center_id=" . $medical_center_id);
        $start  = 0;
        $end    = 6;
        $i      = 0;
        $status = array();
        foreach ($timesIds as $val) {
            $medical_time_id = $val;
            for ($start; $start <= $end; $start++) {
                array_push($status, array($day[$start][$i]));
//                array_push($start, array($day[$start][$start]));
                $i++;
            }
            $start = $end + 1;
            $end   = $start + 6;
            $i     = 0;

            $data["medical_center_id"] = $medical_center_id;
            $data["medical_time_id"]   = $medical_time_id;
            $data["monday"]            = $status[0][0];
            $data["tuesday"]           = $status[1][0];
            $data["wednesday"]         = $status[2][0];
            $data["thursday"]          = $status[3][0];
            $data["friday"]            = $status[4][0];
            $data["saturday"]          = $status[5][0];
            $data["sunday"]            = $status[6][0];
            $medical_center_time->data($data)->add();
            $status = array();
        }

        return $this->ajaxReturn(true, "JSON2");
    }

    function  doctorList($id)
    {
        $medical_center_doctor = D('medical_center_doctor');
        import("ORG.Util.Page");
        $result = $medical_center_doctor->execute("SELECT * FROM `cms_medical_center_doctor` t1 LEFT JOIN  `cms_doctor` t2 ON t1.doctor_id=t2.id  WHERE medical_center_id=" . $id);
        $p      = new Page($result, 30);
        $sql    = 'SELECT * FROM `cms_medical_center_doctor` t1 LEFT JOIN  `cms_doctor` t2 ON t1.doctor_id=t2.id  WHERE medical_center_id=' . $id . ' limit ' . $p->firstRow . ',' . $p->listRows; //sql语句
//        $result=$medical_center_doctor->query("SELECT * FROM `cms_medical_center_doctor` t1 LEFT JOIN  `cms_doctor` t2 ON t1.doctor_id=t2.id  WHERE medical_center_id=".$id);
        $doctor = $medical_center_doctor->query($sql);
        $page   = $p->show();
        $this->assign('page', $page);
        $this->assign('doctorList', $doctor);
        $this->display("doctorList");
    }


}

?>