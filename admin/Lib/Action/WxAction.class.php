<?php
/**
 * Created by PhpStorm.
 * User: bear
 * Date: 16/3/17
 * Time: 上午12:11
 */
class WxAction extends Action{
    //代下单
    function orderdoct(){
        if($_POST){
            $data=$_POST;
            $data['time']=time();
            $Order=M('Order');
            if($Order->add($data)){
                echo "1";
            }else{
                echo "0";
            }
        }

    }
    function index(){
        echo "index";
    }
    public function savePic(){
        $acc=$_GET['acc'];
        $mid=$_GET['mid'];
        $data['uid']=$_GET['uid'];
        $data['uname']=$_GET['uname'];
        $data['pic']=$this->getmedia($acc,$mid,"pic");
        $data['time']=time();
        $Pic=M('Pic');
        if($id= $Pic->add($data)){
            echo $id;
        }else{
            echo "提交失败!";
        }


    }
    private function getmedia($access_token,$media_id,$foldername){
        $url = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=".$access_token."&media_id=".$media_id;
        if (!file_exists("./Uploads/User_cert/".$foldername)) {
            mkdir("./Uploads/User_cert/".$foldername, 0777, true);
        }
        $targetName = './Uploads/User_cert/'.$foldername.'/'.date('YmdHis').rand(1000,9999).'.jpg';
        $ch = curl_init($url); // 初始化
        $fp = fopen($targetName, 'wb'); // 打开写入
        curl_setopt($ch, CURLOPT_FILE, $fp); // 设置输出文件的位置，值是一个资源类型
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);
        return $targetName;
    }


}