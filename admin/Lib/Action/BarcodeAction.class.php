<?php
// +----------------------------------------------------------------------
// | APPCMS 移动应用软件后台管理系统
// +----------------------------------------------------------------------
// | provide by ：shiraz-soft.com
// 
// +----------------------------------------------------------------------
// | Author: Shirazsoft APP Team
// +----------------------------------------------------------------------
use \PHPExcel_IOFactory;
class BarcodeAction extends BaseAction
{
     function index()
    {
//        $barcode = D('barcode');
//        import("ORG.Util.Page");
//        $count=$barcode->count();
//        $p     = new Page($count, 30);
//        $barcodeList=$barcode->limit($p->firstRow . ',' . $p->listRows)->select();
//        dump($barcodeList);
//        $page = $p->show();
//        $this->assign('page', $page);
//        $this->assign('orderList', $barcodeList);
//        $this->display("index");
        $barcode =  M('barcode');
       /* import("ORG.Util.Page");
        $code = $_GET['keyword'];
        $status_id=$_GET['status_id'];
        if ($code) {
            $where['code'] = array('like',"%$code%");;
        }
        if ($status_id!=-1) {
            $where['status_id'] = array('eq',$status_id);;
        }
        $count = $barcode->where($where)->count();
        $p     = new Page($count, 30);

        $barcodeList = $barcode->where($where)->limit($p->firstRow . ',' . $p->listRows)->select();
//        dump($barcodeList );
        $page = $p->show();*/



         $Model = new Model();
         $barcodeList = $Model->query("select a.id, a.name, a.code, a.scan_time, a.status_id, c.name
              from __PREFIX__barcode a
              left join __PREFIX__wechat_user b on a.open_id=b.open_id
              left join __PREFIX__technician_doctor c on b.user_name=c.phone");

//        // 显示条形码信息
//        $barcodeList = $barcode
//                ->field("*")
//                ->table("cms_barcode")
//                //->where("(cms_barcode.open_id=cms_wechat_user.open_id) or (cms_barcode.open_id='')")->select();
//                ->select();
//        $openid = '';
//        for ($i=0; $i <count($barcodeList); $i++) {
//            $openid = $barcodeList[$i]['open_id'];
//            //var_dump($openid);
//        }
//        $id = '';
//        for ($i=0; $i <count($barcodeList) ; $i++) {
//              $id = $barcodeList[$i]['identity_id'];
//          }
//
//        $role = M("doctor");
//        $doctorName = $role->where("cms_doctor.id=".$id)->select();
//        $doctorList = '';
//        for ($i=0; $i < count($doctorName); $i++) {
//                $doctorList = $doctorName[$i];
//        }
        $this->assign('page', $page);
        $this->assign('barcode_list', $barcodeList);




        $big_menu = array(u('Barcode/downloadTmp'), '下载模版', 'javascript:window.top.art.dialog({id:\'export\',iframe:\'?m=Barcode&a=export\', title:\'导入电子监管码\', width:\'480\', height:\'100\', lock:true}, function(){ var d = window.top.art.dialog({id:\'export\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click(); d.document.getElementById(\'divPro\').style.display= \'block\';  return false;}, function(){ window.top.art.dialog({id:\'export\'}).close()});void(0);', '导入电子监管码');
        $this->assign('big_menu', $big_menu);
        $this->code=$code;
        $this->status_id=$status_id;
        $this->display();
    }

    function export()
    {
        if (isset($_POST['dosubmit'])) {
//            $this->success(L('operation_success'), '', '', 'export');
//            $this->error('导入失败');
//            if ($_FILES['fileField1']['type'] !== 'application/vnd.ms-excel') {
//                $this->ajaxReturn(array('result' => '-1', 'msg' => '文件类型不对'));
//            }
//            if (!$_FILES['fileField1']['tmp_name']) {
//                $this->ajaxReturn(array('result' => '-1', 'msg' => '上传文件错误,请重新上传'));
//            }
            vendor("PHPExcel.PHPExcel");
            $tmp_file = $_FILES ['barcode']['tmp_name'];
//            $columnIndex = \PHPExcel_IOFactory::load($tmp_file)->getSheet(0)->getHighestDataColumn();
//            if ($columnIndex != "B") {
//                return $this->ajaxReturn(array("result" => -1, "msg" => "模版格式不正确"));
//            }
            $PHPExcel   = PHPExcel_IOFactory::load($tmp_file);
            $sheet      = $PHPExcel->getSheet(0); // 读取第一個工作表(编号从 0 开始)
            $highestRow = $sheet->getHighestRow(); // 取得总行数

            for ($row = 2; $row <= $highestRow; $row++) {
                $name    = $sheet->getCellByColumnAndRow(0, $row)->getValue();
                $code    = $sheet->getCellByColumnAndRow(1, $row)->getValue();
                $barcode = M('barcode');
                if (!$barcode->where("code='$code'")->find()) {
                    $barcode->add(array('name' => $name, 'code' => $code, 'status_id' => 0));
                }
            }
            $this->success(L('operation_success'), '', '', 'export');


        } else {
            $this->display();
        }
    }

    public function downloadTmp()
    {
        vendor("PHPExcel.PHPExcel");
        $objPHPExcel = new \PHPExcel();
// Add some data
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', "药品名")
            ->setCellValue('B1', "电子监管码");



// Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('电子监管码');
        $objPHPExcel->getDefaultStyle()->getFont()->setName('微软雅黑');
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
// Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="电子监管码模版.xls"');
        header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
// If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }

    public function  exportTxt()
    {
        if (!uploadTxt($msg, 'barcode', $name)) {
            $this->error($msg);
        }
        $fileName = $_SERVER['DOCUMENT_ROOT'] . '/fd/upload/barcode/' . $name;
        $fileContent =trim( file_get_contents($fileName));
        ini_set('memory_limit', '-1');//不要限制Mem大小，否则会报错
        $lines = array_unique(explode('|', str_replace(
            array("\r\n","\n"),
            "|",
            $fileContent
        )));

        foreach ($lines as $line) {
            $barcode = M('barcode');
            if (!$barcode->where("code='{$line}'")->find()) {
                $barcode->data(array( 'code' => $line, 'status_id' => 0))->add();
            }
        }

        $this->success(L('operation_success'), '', '', 'export');
    }


}

?>