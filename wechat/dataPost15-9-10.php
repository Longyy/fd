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
        //验证手机号码是否用过
        $sql = "SELECT * FROM `cms_wechat_user` WHERE role_id<>4 and user_name='" . $_POST['phone'] . "'";
        $result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
        $rowCount = $result->num_rows;
        if ($rowCount != 0) {
            header('Content-Type:application/json; charset=utf-8');
            exit(json_encode(array('st' => 0, 'rt' => '您输入的手机号已被使用')));
        }
        //验证是否是其他角色（医生，转诊医生）进行的操作
        $sql = "SELECT * FROM `cms_wechat_user` WHERE (role_id=1 or role_id=3) and open_id='" . $_POST['openId'] . "'";
        $result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
        $rowCount = $result->num_rows;
        if ($rowCount != 0) {
            header('Content-Type:application/json; charset=utf-8');
            exit(json_encode(array('st' => 0, 'rt' => '您的微信号已绑定无法提交求诊单')));
        }
        //如果是转诊患者需要验证之前信息是否一致
        if ($_POST['orderType'] == 0) {
            $sql = "SELECT * FROM `cms_qrcode_consultation` WHERE code='" . $_POST['order_code'] . "'";
            $result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
            $rowCount = $result->num_rows;
            if ($rowCount == 0) {
                header('Content-Type:application/json; charset=utf-8');
                exit(json_encode(array('st' => 0, 'rt' => '您输入的转诊单无效')));
            }
            $result = $result->fetch_assoc();
            if ($result['name'] != $_POST['name']) {
                header('Content-Type:application/json; charset=utf-8');
                exit(json_encode(array('st' => 0, 'rt' => '您输入的姓名不符')));
            }
            if ($result['sex'] != $_POST['sex']) {
                header('Content-Type:application/json; charset=utf-8');
                exit(json_encode(array('st' => 0, 'rt' => '您输入的性别不符')));
            }
            if ($result['age'] != $_POST['age']) {
                header('Content-Type:application/json; charset=utf-8');
                exit(json_encode(array('st' => 0, 'rt' => '您输入的年龄不符')));
            }
            if ($result['phone'] != $_POST['phone']) {
                header('Content-Type:application/json; charset=utf-8');
                exit(json_encode(array('st' => 0, 'rt' => '您输入的手机号不符')));
            }
            if ($result['id_card'] != $_POST['id_card']) {
                header('Content-Type:application/json; charset=utf-8');
                exit(json_encode(array('st' => 0, 'rt' => '您输入的身份证不符')));
            }
        }
        $creteTime = time();
        $sql       = "INSERT INTO cms_consultation (name,sex,age,phone,id_card,order_code,diagnose,description,before_treat,demand,doctor_id,open_id,order_type,create_time,resource,handle_type) VALUE( '$_POST[name]','$_POST[sex]','$_POST[age]','$_POST[phone]','$_POST[id_card]','$_POST[order_code]','$_POST[diagnose]','$_POST[description]','$_POST[before_treat]','$_POST[demand]','$_POST[doctorId]','$_POST[openId]','$_POST[orderType]','$creteTime','$_POST[resource]',0)";
        $result = mysqli_query($connection, $sql) or die(mysqli_error($connection));

        //给患者添加微信用户
        $pwd = substr($_POST["phone"], -4);
        $sql = "insert into cms_wechat_user (user_name,password,role_id,open_id) value ('$_POST[phone]','$pwd',4,'$_POST[openId]')";
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

        $msg = "尊敬的" . $_POST["name"] . "您提交的求诊信息已经收到！医生将会在2个工作日内处理或联系您。为更好服务您，系统自动为您服务";
        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode(array('st' => 1, 'rt' => $msg)));
        break;

    //治疗中心医生/转诊医生/患者登录
    case "login":
        $sql = "SELECT * FROM`cms_wechat_user` WHERE user_name='" . $_POST["userName"] . "'and password='" . $_POST["password"] . "'and role_id =" . $_POST["roleType"];
        $result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
        $rowCount = $result->num_rows;
        if ($rowCount == 0) {
            header('Content-Type:application/json; charset=utf-8');
            exit(json_encode(array('st' => 0, 'rt' => "用户名或密码错误")));
        }
        $result = $result->fetch_assoc();
        if ($result["open_id"] != "") {
            header('Content-Type:application/json; charset=utf-8');
            exit(json_encode(array('st' => 0, 'rt' => "该用户名和密码已使用，请重新输入")));
        }

        $sql = "UPDATE `cms_wechat_user` SET open_id='" . $_POST["openId"] . "'  WHERE user_name='" . $_POST["userName"] . "'and password='" . $_POST["password"] . "'";
        $result = mysqli_query($connection, $sql) or die(mysqli_error($connection));

        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode(array('st' => 1)));
        break;
    //处方
    case "prescription":
        //更新订单状态
        $sql = "update cms_consultation set handle_type =2,status_id=1 where id =" . $_POST["consultation_id"];
        mysqli_query($connection, $sql) or die(mysqli_error($connection));

        $sql = "INSERT INTO `cms_consultation_prescription` (consultation_id,cate_id,number,create_time) VALUES(" . $_POST["consultation_id"] . "," . $_POST["cate_id"] . "," . $_POST["number"] . "," . time() . ")";
        $result = mysqli_query($connection, $sql) or die(mysqli_error($connection));

        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode(array('st' => $result)));
        break;

    case "scanForm":
        $openId = $_POST['openId'];
        //判断转诊医生是否有注册
        $sql = "SELECT * FROM`cms_wechat_user` WHERE role_id=3 and open_id='" . $openId . "'";
        $result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
        $rowCount = $result->num_rows;
        if ($rowCount == 0) {
            header('Content-Type:application/json; charset=utf-8');
            exit(json_encode(array('st' => 0, 'rt' => "您的微信号尚未绑定，请先绑定后再进行该操作！")));
        }
        $creteTime = time();
        //插入求诊信息
        $sql = "INSERT INTO cms_qrcode_consultation (name,sex,age,phone,id_card,doctor_open_id,code,create_time) VALUE( '$_POST[name]','$_POST[sex]','$_POST[age]','$_POST[phone]','$_POST[id_card]','$openId','$_POST[code]','$creteTime')";
        $result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode(array('st' => 1, 'rt' => "诊疗信息已提交！")));
        break;
    case 'loadDoctor':
        $sql = "SELECT t.doctor_id,t1.name as doctor_name FROM `cms_medical_center_doctor` t left join `cms_doctor` t1 on t.doctor_id=t1.id  WHERE  t.medical_center_id=" . $_POST['centerId'];
        $result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
        while ($row = $result->fetch_assoc()) {
            $doctorData[] = array('id' => $row['doctor_id'], 'name' => $row['doctor_name']);
            header('Content-Type:application/json; charset=utf-8');
            exit(json_encode($doctorData));
        }
    //邀请患者来院治疗
    case "invite":
        $sql = "update cms_consultation set handle_type =1,status_id=1 where id =" . $_POST["id"];
        $result = mysqli_query($connection, $sql) or die(mysqli_error($connection));

        $sql="select t1.name,t1.open_id,t4.name as doctor_name,t2.medical_center_id from cms_consultation t1 left join cms_medical_center_doctor t2 on t1.doctor_id=t2.doctor_id left join  cms_medical_center t3 on t2.medical_center_id= t3.id left join cms_doctor t4 on t2.doctor_id=t4.id
where t1.id=".$_POST["id"];
        $result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
        $result = $result->fetch_assoc();
        include("httpConfig.php");
        include("wechatClass.php");
        //发送消息模版
        $tempMsg = array(
            "touser"      => $result["open_id"],
            "template_id" => "To2lktM7BwzgtzXJRW8sLKYNPJK3YlKtsz9G09qzkwc",
            "url"         => $pageUrl."map.php?id=".$result["medical_center_id"],
            "data"        => array(
                "first" => array(
                    "value" => "恭喜您，已成功收到邀请！",
                    "color" => "#173177"
                ),
                "keyword1"=>array(
                    "value" => $result["name"]
                ),
                "keyword2"=>array(
                    "value" =>  date('Y-m-d',time()),
                ),
                "keyword3"=>array(
                    "value" => $result["doctor_name"]."医生邀请您来院就诊"
                ),
                "remark"=>array(
                    "value"=>"光动力咨询微信平台为您提供最好的服务",
                    "color" => "#173177"
                )
            )
        );
        send_temp_msg($tempMsg);

        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode(array('st' => 1, 'rt' => "信息已提交成功！")));
    //医生作废订单
    case "cancle":
        $sql = "update cms_consultation set handle_type =3,status_id=1 where id =" . $_POST["id"];
        $result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode(array('st' => 1, 'rt' => "信息已提交成功！")));
    case 'consultationList':

        $where = 'where doctor_id=' . $_POST['doctorId'];
        if ($_POST['status'] != " ") {
            $where = $where . ' and handle_type=' . $_POST['status'];
        }
        if ($_POST['startTime']) {
            $where = $where . ' and create_time>' . strtotime($_POST['begin_date']);
        }
        if ($_POST['endTime']) {
            $where = $where . ' and create_time>' . strtotime($_POST['endTime']);
        }
        $sql = "SELECT * FROM `cms_consultation`" . $where;


        $result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
        $rowCount = $result->num_rows;

        $pageIndex = $_POST["pageIndex"] - 1;
        $pageStart = $pageIndex * 3;
        $sql       = $sql . ' limit ' . $pageStart . ',' . '3';

        $consultationResult = mysqli_query($connection, $sql) or die(mysqli_error($connection));
        $consulationData=array();
        $pageHtml='';
        while ($row = $consultationResult->fetch_assoc()) {
            $consulationData[] = array('id'          => $row['id'],
                                       'name'        => $row['name'],
                                       'phone'       => $row['phone'],
                                       'sex'         => $row['sex'],
                                       'createTime'  => date('Y-m-d', $row["create_time"]),
                                       'handle_type' => $row['handle_type']);
        }
        include('bootstrapPage.php');
        if($rowCount!=0)
        $pageHtml = bootstrap_page($rowCount, 3, $_POST["pageIndex"], "ajax", 'consultationList');

        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode(array('st' => 1, 'rt' => $consulationData, 'page' => $pageHtml, 'sql' => $sql)));
    case 'transConsultationList':

        $where = "where doctor_open_id='".$_POST['openId']."'";

        if ($_POST['code'] != "") {

            $where = $where . " and (phone like '%".$_POST['code']."%' or id_card like '%".$_POST['code']."%')" ;
//            header('Content-Type:application/json; charset=utf-8');
//            exit(json_encode(array('sql' => $where)));
        }
        if ($_POST['startTime']) {
            $where = $where . ' and create_time>' . strtotime($_POST['begin_date']);
        }
        if ($_POST['endTime']) {
            $where = $where . ' and create_time>' . strtotime($_POST['endTime']);
        }

        $sql = "SELECT * FROM `cms_qrcode_consultation`" . $where.' order by create_time desc';


        $result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
        $rowCount = $result->num_rows;

        $pageIndex = $_POST["pageIndex"] - 1;
        $pageStart = $pageIndex * 3;
        $sql       = $sql . ' limit ' . $pageStart . ',' . '3';
        $consulationData=array();
        $pageHtml='';
        $consultationResult = mysqli_query($connection, $sql) or die(mysqli_error($connection));
        while ($row = $consultationResult->fetch_assoc()) {
            $consulationData[] = array('id'         => $row['id'],
                                       'name'       => $row['name'],
                                       'phone'      => $row['phone'],
                                       'sex'        => $row['sex'],
                                       'age'        => $row['age'],
                                       'createTime' => date('Y-m-d', $row["create_time"]),
                                       'id_card'    => $row['id_card']);
        }

        include('bootstrapPage.php');
        if($rowCount!=0)
        $pageHtml = bootstrap_page($rowCount, 3, $_POST["pageIndex"], "ajax", 'transConsultationList');

        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode(array('st' => 1, 'rt' => $consulationData, 'page' => $pageHtml, 'sql' => $sql)));
    case 'test':
        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode(array('st' => 0, 'rt' => "诊疗信息已提交！")));
    default:
        require("text.php");
        break;

}

