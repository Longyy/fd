<?php
// 本类由系统自动生成，仅供测试用途
class IndexAction extends Action {
    public function index(){
        $this->check();
        $method=$this->_get('method');
        //执行请求的方法
        $this->$method();
        exit();
    }

    public function  receivePatientData(){
        $interface=M('interface');
        $data['name']=$this->_get('name');
        $data['id_card']=$this->_get('idcard');
        $data['phone']=$this->_get('phone');
        $result = $interface->add($data);
        //更新患者信息
        $qrcodeConsultation=M('qrcode_consultation');
        $qrcodeConsultation->where(array('name'=>$this->_get('name'),'id_card'=>$this->_get('idcard')))->setField('status_id',1);
//        dump($qrcodeConsultation->getLastSql());
        if($result){
            $this->ajaxReturn(array('result'=>1,'msg'=>'操作成功'));
        }
        else{
            $this->ajaxReturn(array('result'=>0,'msg'=>'操作失败'));
        }

    }



    //检测请求是否合法
    private function check(){
        $method_array=array('receivePatientData');
        //签名方式
        $method=$this->_get('method');     //method
        $key='fdzj';	//双方约定的一个key
        $sign=$this->_get('sign');  //签名
        $my_sign=md5(strtolower($method.$key));
        //echo $my_sign;
        if(!in_array($method, $method_array)){
            exit('请求的方法不存在');
        }
        if($my_sign!=$sign){
            exit('签名不正确');
        }
        return true;
    }
}