<?php
include("connection.php");
/**
 * Created by PhpStorm.
 * User: Kevin
 * Date: 15-3-31
 * Time: 上午10:18
 */
if (isset($_POST['postType'])) {
    $post_type = $_POST['postType'];
}
switch ($post_type) {
    //预约求诊
    case "consulation":
 include("httpGet.php");
        //如果是转诊患者需要验证之前信息是否一致
        if($_POST['orderType']==0){
            $sql = "SELECT * FROM `cms_qrcode_consulation` WHERE code='".$_POST['order_code']."'";
            $result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
            $rowCount=$result->num_rows;
            if($rowCount==0){
                header('Content-Type:application/json; charset=utf-8');
                exit(json_encode(array('st' => 0,'rt'=>'您输入的转诊单无效')));
            }
            $result = $result->fetch_assoc();
            if($result['name']!=$_POST['name']){
                header('Content-Type:application/json; charset=utf-8');
                exit(json_encode(array('st' => 0,'rt'=>'您输入的姓名不符')));
            }
            if($result['sex']!=$_POST['sex']){
                header('Content-Type:application/json; charset=utf-8');
                exit(json_encode(array('st' => 0,'rt'=>'您输入的性别不符')));
            }
            if($result['age']!=$_POST['age']){
                header('Content-Type:application/json; charset=utf-8');
                exit(json_encode(array('st' => 0,'rt'=>'您输入的年龄不符')));
            }
            if($result['phone']!=$_POST['phone']){
                header('Content-Type:application/json; charset=utf-8');
                exit(json_encode(array('st' => 0,'rt'=>'您输入的手机号不符')));
            }
            if($result['id_card']!=$_POST['id_card']){
                header('Content-Type:application/json; charset=utf-8');
                exit(json_encode(array('st' => 0,'rt'=>'您输入的身份证不符')));
            }
        }
        $creteTime=time();
        $sql = "INSERT INTO cms_consultation (name,sex,age,phone,id_card,order_code,diagnose,description,before_treat,demand,doctor_id,open_id,order_type,create_time) VALUE( '$_POST[name]','$_POST[sex]','$_POST[age]','$_POST[phone]','$_POST[id_card]','$_POST[order_code]','$_POST[diagnose]','$_POST[description]','$_POST[before_treat]','$_POST[demand]','$_POST[doctorId]','$_POST[openId]','$_POST[orderType]','$creteTime')";
        $result = mysqli_query($connection, $sql) or die(mysqli_error($connection));

        //给患者添加微信用户
        $pwd=substr($_POST["phone"], -4);
        $sql="insert into cms_wechat_user (user_name,password,role_id,open_id) value ('$_POST[name]','$pwd',4,'$_POST[openId]')";
        $result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
//        //转诊
//        if ($_POST["orderType"] == 0) {
//            //通过转诊号获取销售代表
//            $sql = "SELECT * FROM `cms_saler` WHERE order_begin <=" . $_POST["order_code"] . " AND order_end>=" . $_POST["order_code"];
//
//            $result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
//            $result = $result->fetch_assoc();
//            $saleId = $result["id"];
//
//            $sql    = "INSERT INTO `cms_consultation_saler`(order_code,saler_id) VALUES(" . $_POST["order_code"] . "," . $saleId . ")";
//            $result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
//
//
//        }

        $msg ="尊敬的".$_POST["name"]."您提交的求诊信息已经收到！医生将会在2个工作日内处理或联系您。为更好服务您，系统自动为您服务";
        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode(array('st' => 1,'rt'=>$msg)));
        break;

    //治疗中心医生/转诊医生/患者登录
    case "login":
        $sql = "SELECT * FROM`cms_wechat_user` WHERE user_name='".$_POST["userName"]."'and password='".$_POST["password"]."'and role_id =".$_POST["roleType"];
        $result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
        $rowCount=$result->num_rows;
        if($rowCount==0){
            header('Content-Type:application/json; charset=utf-8');
            exit(json_encode(array('st' => 0,'rt'=>"用户名或密码错误")));
        }
        $result = $result->fetch_assoc();
        if($result["open_id"]!=""){
            header('Content-Type:application/json; charset=utf-8');
            exit(json_encode(array('st' => 0,'rt'=>"该用户名和密码已使用，请重新输入")));
        }

        $sql = "UPDATE `cms_wechat_user` SET open_id='".$_POST["openId"]."'  WHERE user_name='".$_POST["userName"]."'and password='".$_POST["password"]."'";
        $result = mysqli_query($connection, $sql) or die(mysqli_error($connection));

        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode(array('st' => 1)));
        break;
    //处方
    case "prescription":
        //更新订单状态
        $sql = "update cms_consultation set handle_type =2,status_id=1 where id =".$_POST["consultation_id"];
        mysqli_query($connection, $sql) or die(mysqli_error($connection));

        $sql    = "INSERT INTO `cms_consultation_prescription` (consultation_id,cate_id,number,create_time) VALUES(" . $_POST["consultation_id"] . "," . $_POST["cate_id"] . "," . $_POST["number"] . "," . time() . ")";
        $result = mysqli_query($connection, $sql) or die(mysqli_error($connection));

        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode(array('st' => $result)));
        break;

    case "scanForm":
        $openId=$_POST['openId'];
        //判断转诊医生是否有注册
        $sql = "SELECT * FROM`cms_wechat_user` WHERE role_id=3 and open_id='".$openId."'";
        $result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
        $rowCount=$result->num_rows;
        if($rowCount==0){
            header('Content-Type:application/json; charset=utf-8');
            exit(json_encode(array('st' => 0,'rt'=>"您的微信号尚未绑定，请先绑定后再进行该操作！")));
        }
        //插入求诊信息
        $sql = "INSERT INTO cms_qrcode_consulation (name,sex,age,phone,id_card,doctor_open_id,code) VALUE( '$_POST[name]','$_POST[sex]','$_POST[age]','$_POST[phone]','$_POST[id_card]','$openId','$_POST[code]')";
        $result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode(array('st' => 1,'rt'=>"诊疗信息已提交！")));
        break;
    case 'loadDoctor':
        $sql = "SELECT t.doctor_id,t1.name as doctor_name FROM `cms_medical_center_doctor` t left join `cms_doctor` t1 on t.doctor_id=t1.id  WHERE  t.medical_center_id=" . $_POST['centerId'];
        $result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
        while ($row = $result->fetch_assoc()) {
            $doctorData[]=array('id'=> $row['doctor_id'],'name'=> $row['doctor_name']);
            header('Content-Type:application/json; charset=utf-8');
            exit(json_encode($doctorData));
        }
    //邀请患者来院治疗
    case "invite":
        $sql = "update cms_consultation set handle_type =1,status_id=1 where id =".$_POST["id"];
        $result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode(array('st' => 1,'rt'=>"信息已提交成功！")));
    //医生作废订单
    case "cancle":
        $sql = "update cms_consultation set handle_type =3,status_id=1 where id =".$_POST["id"];
        $result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode(array('st' => 1,'rt'=>"信息已提交成功！")));
    case 'test':
        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode(array('st' => 0,'rt'=>"诊疗信息已提交！")));
    default:
        require("text.php");
        break;

}

