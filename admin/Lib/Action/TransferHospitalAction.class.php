<?php
// +----------------------------------------------------------------------
// | APPCMS 移动应用软件后台管理系统
// +----------------------------------------------------------------------
// | provide by ：shiraz-soft.com
// 
// +----------------------------------------------------------------------
// | Author: Shirazsoft APP Team
// +----------------------------------------------------------------------

class TransferHospitalAction extends BaseAction
{
    function index()
    {
        $prex = C('DB_PREFIX');
        $transferHospital = D('transfer_hospital');
        import("ORG.Util.Page");
        $count = $transferHospital->count();
        $p     = new Page($count, 30);
        if ($_POST) {
            $provice_name = $_POST["province_name"];
            $province = M("province");
            $provincelist = $province->where("cms_province.province like '%".$provice_name."%'")->select();
            $province_id = $provincelist[0]['provinceID'];
            $transferHospital = D('transfer_hospital');
            $transferHospitalList = $transferHospital->where("cms_transfer_hospital.province_id='".$province_id."'")->select();

        }else{
            $transferHospitalList = $transferHospital
                    ->field($prex.'transfer_hospital.*,'.$prex.'province.province as province_name')
                    ->join('LEFT JOIN '.$prex.'province ON '.$prex.'transfer_hospital.province_id = '.$prex.'province.provinceID ')
                    -> limit($p->firstRow . ',' . $p->listRows)
                    ->select();    
        }
        
        $page = $p->show();
        $this->assign('page', $page);

        $this->assign('transferHospitalList', $transferHospitalList);
        $big_menu       = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=TransferHospital&a=add\', title:\'添加转诊医院\', width:\'800\', height:\'400\', lock:true}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);', '添加转诊医院');
        $this->big_menu = $big_menu;
        $this->display();
    }



    function add()
    {
        if (isset($_POST['dosubmit'])) {
            mysql_query("SET name UTF8");
            $transferHospital = D('transfer_hospital');
            $result        = $transferHospital->where("name='" . $_POST['name'] . "'")->count();
            if ($result) {
                $this->error('该医疗中心已存在');
            }
            $transferHospital->create();
            $transferHospital->setup_date = strtotime($_POST["setup_date"]);
            $result                    = $transferHospital->add();
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
            $transferHospital               = M('transfer_hospital');
            $transferHospital->name         = $_POST["name"];
            $transferHospital->address      = $_POST["address"];
            $transferHospital->longitude    = $_POST["longitude"];
            $transferHospital->latitude     = $_POST["latitude"];
            $transferHospital->doctor_count = $_POST["doctor_count"];
            $transferHospital->setup_date   = $_POST["setup_date"];
            $transferHospital->info         = $_POST["info"];
            $transferHospital->is_recommend = $_POST["is_recommend"];
            $transferHospital->province_id = $_POST["province_id"];
//            $result = $transferHospital->where("name='".$_POST['name']."' and id<>".$_POST["id"])->count();
//            if($result){
//                $this->error('该医疗中心已存在');
//            }
            $result = $transferHospital->where('id=' . $_POST['id'])->save();
            if (false !== $result) {
                $this->success(L('operation_success'), '', '', 'edit');
            } else {
                $this->error(L('operation_failure'));
            }
        } else {
            if (isset($_GET['id'])) {
                $id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->error('参数错误');
            }
            $transferHospital = D('transfer_hospital');
            $transferHospital = $transferHospital->where('id=' . $id)->find();

            $this->info = json_encode(array(array($transferHospital['longitude'], $transferHospital['latitude'], $transferHospital['name'])));
            //以第一个地理位置为中心点定位坐标
            $this->lonAndLat = $transferHospital['longitude'] . ',' . $transferHospital['latitude'];
            $this->assign('medicalCenter', $transferHospital);
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
        $transferHospital = D('transfer_hospital');
        if (isset($_POST['id']) && is_array($_POST['id'])) {
            $ids = implode(',', $_POST['id']);
            $transferHospital->delete($ids);
        } else {
            $id = intval($_GET['id']);
            $transferHospital->delete($id);
        }
        $this->success(L('operation_success'));
    }
}

?>