<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-11-9
 * Time: 下午2:19
 */
class DateAction extends BaseAction
{
    public function index()
    {
//        $config     = M('config');
//        $reportList = $config->select();
//        vendor("PHPExcel.PHPExcel");
//        $objPHPExcel = new \PHPExcel();
//// Add some data
//        $objPHPExcel->setActiveSheetIndex(0)
//            ->setCellValue('A1', "id")
//            ->setCellValue('B1', "info")
//            ->setCellValue('C1', "type");
//
//        foreach ($reportList as $index => $val) {
//            $index = $index + 2;
//            $objPHPExcel->setActiveSheetIndex(0)
//                ->setCellValue('A' . $index, $val["id"])
//                ->setCellValue('B' . $index, $val["info"])
//                ->setCellValue('C' . $index, $val["type"]);
//        }
//
//// Rename worksheet
//        $objPHPExcel->getActiveSheet()->setTitle('导出测试数据');
//// Set active sheet index to the first sheet, so Excel opens this as the first sheet
//        $objPHPExcel->setActiveSheetIndex(0);
//// Redirect output to a client’s web browser (Excel5)
//        header('Content-Type: application/vnd.ms-excel');
//        header('Content-Disposition: attachment;filename="导出测试数据.xls"');
//        header('Cache-Control: max-age=0');
//// If you're serving to IE 9, then the following may be needed
//        header('Cache-Control: max-age=1');
//// If you're serving to IE over SSL, then the following may be needed
//        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
//        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
//        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
//        header('Pragma: public'); // HTTP/1.0
//
//        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
//        $objWriter->save('php://output');
        $this->display();
    }
}