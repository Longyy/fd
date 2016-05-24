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
    //预约咨询
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
            exit(json_encode(array('st' => 0, 'rt' => '您的微信号已绑定，无法提交')));
        }
        //如果是转诊患者需要验证之前信息是否一致
        if ($_POST['orderType'] == 0) {
            $sql = "SELECT * FROM `cms_qrcode_consultation` WHERE code='" . $_POST['order_code'] . "'";
            $result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
            $rowCount = $result->num_rows;
           /* if ($rowCount == 0) {
                header('Content-Type:application/json; charset=utf-8');
                exit(json_encode(array('st' => 0, 'rt' => '您输入的转诊单无效')));
            }*/
            $result = $result->fetch_assoc();
           /* if ($result['name'] != $_POST['name']) {
                header('Content-Type:application/json; charset=utf-8');
                exit(json_encode(array('st' => 0, 'rt' => '您输入的姓名不符')));
            }*/
            /*if ($result['sex'] != $_POST['sex']) {
                header('Content-Type:application/json; charset=utf-8');
                exit(json_encode(array('st' => 0, 'rt' => '您输入的性别不符')));
            }*/
           /* if ($result['age'] != $_POST['age']) {
                header('Content-Type:application/json; charset=utf-8');
                exit(json_encode(array('st' => 0, 'rt' => '您输入的年龄不符')));
            }*/
            /*if ($result['phone'] != $_POST['phone']) {
                header('Content-Type:application/json; charset=utf-8');
                exit(json_encode(array('st' => 0, 'rt' => '您输入的手机号不符')));
            }*/
          /*  if ($result['id_card'] != $_POST['id_card']) {
                header('Content-Type:application/json; charset=utf-8');
                exit(json_encode(array('st' => 0, 'rt' => '您输入的身份证不符')));
            }*/
        }
        $creteTime = time();
        $sql       = "INSERT INTO cms_consultation (birthday,name,sex,phone,order_code,diagnose,description,before_treat,demand,doctor_id,open_id,create_time,resource,handle_type,city) VALUE('$_POST[birthday]', '$_POST[name]','$_POST[sex]','$_POST[phone]','$_POST[order_code]','$_POST[diagnose]','$_POST[description]','$_POST[before_treat]','$_POST[demand]','$_POST[doctorId]','$_POST[openId]','$creteTime','$_POST[resource]',0,'$_POST[city]')";

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

        $msg = "尊敬的" . $_POST["name"] . "您提交的咨询信息已经收到！医生将尽快处理或联系您。";
        //获取最新一条记录
        $sql = "select max(id) as id from cms_consultation";
        $result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
        $result = $result->fetch_assoc();
        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode(array('st' => 1, 'rt' => $msg,'conId'=>$result['id'])));
        break;
    // 咨询建议
    case "zixunjianyi":
        include("httpGet.php");
        $sql = "INSERT INTO cms_proposal (con_id, suggestion, other, recommend, recommendother) VALUES ('$_POST[con_id]','$_POST[suggestion]','$_POST[other]','$_POST[recommend]','$_POST[recommendother]')";
        mysqli_query($connection, $sql) or die(mysqli_error($connection));
        $sql1 = "update cms_consultation set status_id=2 where id =" . $_POST["con_id"];
        mysqli_query($connection, $sql1) or die(mysqli_error($connection));
        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode(array('st' => 1, 'rt' => "信息已提交成功！")));


        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode(array('st' => 1, 'msg' => $sql)));
//        exit(json_encode(array('st' => 1, 'roleId' => $userResult['role_id'])));
        break;
    // 预约信息
    case "appoint":
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
       /* $sql = "SELECT * FROM `cms_wechat_user` WHERE (role_id=1 or role_id=3) and open_id='" . $_POST['openId'] . "'";
        $result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
        $rowCount = $result->num_rows;
        if ($rowCount != 0) {
            header('Content-Type:application/json; charset=utf-8');
            exit(json_encode(array('st' => 0, 'rt' => '您的微信号已绑定无法提交咨询单')));
        }*/
        //如果是转诊患者需要验证之前信息是否一致
        if ($_POST['orderType'] == 0) {
           /* $sql = "SELECT * FROM `cms_qrcode_consultation` WHERE code='" . $_POST['order_code'] . "'";
            $result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
            $rowCount = $result->num_rows;*/
           /* if ($rowCount == 0) {
                header('Content-Type:application/json; charset=utf-8');
                exit(json_encode(array('st' => 0, 'rt' => '您输入的转诊单无效')));
            }*/
            $result = $result->fetch_assoc();
           /* if ($result['name'] != $_POST['name']) {
                header('Content-Type:application/json; charset=utf-8');
                exit(json_encode(array('st' => 0, 'rt' => '您输入的姓名不符')));
            }*/
            /*if ($result['sex'] != $_POST['sex']) {
                header('Content-Type:application/json; charset=utf-8');
                exit(json_encode(array('st' => 0, 'rt' => '您输入的性别不符')));
            }*/
           /* if ($result['age'] != $_POST['age']) {
                header('Content-Type:application/json; charset=utf-8');
                exit(json_encode(array('st' => 0, 'rt' => '您输入的年龄不符')));
            }*/
            /*if ($result['phone'] != $_POST['phone']) {
                header('Content-Type:application/json; charset=utf-8');
                exit(json_encode(array('st' => 0, 'rt' => '您输入的手机号不符')));
            }*/
          /*  if ($result['id_card'] != $_POST['id_card']) {
                header('Content-Type:application/json; charset=utf-8');
                exit(json_encode(array('st' => 0, 'rt' => '您输入的身份证不符')));
            }*/
        }
        $creteTime = time();
        $sql       = "INSERT INTO cms_appoint (pic,birthday,name,sex,phone,order_code,diagnose,description,before_treat,demand,doctor_id,open_id,create_time,resource,handle_type,city) VALUE('$_POST[pic]', '$_POST[birthday]', '$_POST[name]','$_POST[sex]','$_POST[phone]','$_POST[order_code]','$_POST[diagnose]','$_POST[description]','$_POST[before_treat]','$_POST[demand]','$_POST[doctorId]','$_POST[openId]','$creteTime','$_POST[resource]',0,'$_POST[city]')";

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

        $msg = "尊敬的" . $_POST["name"] . "您提交的预约信息已经收到！医生将尽快处理或联系您。";
        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode(array('st' => 1, 'rt' => $msg)));
        break;

    //治疗中心医生/转诊医生/患者登录
    case "login":

//        $sql = "SELECT * FROM`cms_wechat_user` WHERE user_name='" . $_POST["userName"] . "'and password='" . $_POST["password"] . "'and role_id =" . $_POST["roleType"];
        $sql = "SELECT * FROM`cms_wechat_user` WHERE user_name='" . $_POST["userName"] . "'and password='" . $_POST["password"] . "'";

        $result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
        $rowCount = $result->num_rows;
        if ($rowCount == 0) {
            header('Content-Type:application/json; charset=utf-8');
            exit(json_encode(array('st' => 0, 'rt' => "用户名或密码错误")));
        }
        $userResult = $result->fetch_assoc();
        if ($userResult["open_id"] != "") {
            header('Content-Type:application/json; charset=utf-8');
            exit(json_encode(array('st' => 0, 'rt' => "该用户名和密码已使用，请重新输入")));
        }

        $sql = "UPDATE `cms_wechat_user` SET open_id='" . $_POST["openId"] . "'  WHERE user_name='" . $_POST["userName"] . "'and password='" . $_POST["password"] . "'";

        mysqli_query($connection, $sql) or die(mysqli_error($connection));

        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode(array('st' => 1, 'roleId' => $userResult['role_id'])));
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
        $sql = "INSERT INTO cms_qrcode_consultation (name,sex,phone,zztime,doctor_open_id,code,create_time,status_id,province_id,center_id,doctor_id) VALUE('$_POST[name]','$_POST[sex]','$_POST[phone]','$_POST[zztime]','$openId','$_POST[code]',$creteTime,0,'$_POST[province_id]','$_POST[center_id]','$_POST[doctor_id]')";
        $result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode(array('st' => 1, 'rt' => "转诊信息已提交")));
        break;
    // 提交治疗确认表信息
    case 'zhiliaoquerenbiao':
        $sql = "INSERT INTO `cms_treatment`( name, tel, hospitalname, doctorname, heartpic, liver, openid) VALUES ('$_POST[name]','$_POST[tel]','$_POST[hospitalname]', '$_POST[doctorname]','$_POST[heartpic]','$_POST[liver]','$_POST[openid]')";
        $result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode(array('st' => 1, 'rt' => "治疗确认表信息已提交！")));
        break;
    //通过治疗中心，加载医生
    case 'loadDoctor':
        $sql = "SELECT t.doctor_id,t1.name as doctor_name FROM `cms_medical_center_doctor` t left join `cms_doctor` t1 on t.doctor_id=t1.id  WHERE  t.medical_center_id=" . $_POST['centerId'];
        $result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
        while ($row = $result->fetch_assoc()) {
            $doctorData[] = array('id' => $row['doctor_id'], 'name' => $row['doctor_name']);
        }
        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode($doctorData));
    //通过省份加载治疗中心
    case 'loadCenter':
        $sql = "select * from cms_medical_center where province_id=" . $_POST['provinceId'];
        $result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
        while ($row = $result->fetch_assoc()) {
            $centerData[] = array('id' => $row['id'], 'name' => $row['name']);
        }
        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode($centerData));
    //邀请患者来院治疗
    case "invite":
        $sql = "update cms_consultation set handle_type =1,status_id=1 where id =" . $_POST["id"];
        $result = mysqli_query($connection, $sql) or die(mysqli_error($connection));

        $sql = "select t1.name,t1.open_id,t4.name as doctor_name,t2.medical_center_id from cms_consultation t1 left join cms_medical_center_doctor t2 on t1.doctor_id=t2.doctor_id left join  cms_medical_center t3 on t2.medical_center_id= t3.id left join cms_doctor t4 on t2.doctor_id=t4.id
where t1.id=" . $_POST["id"];
        $result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
        $result = $result->fetch_assoc();
        include("httpConfig.php");
        include("wechatClass.php");
        //发送消息模版
        $tempMsg = array(
            "touser"      => $result["open_id"],
            "template_id" => "To2lktM7BwzgtzXJRW8sLKYNPJK3YlKtsz9G09qzkwc",
            "url"         => $pageUrl . "map.php?id=" . $result["medical_center_id"],
            "data"        => array(
                "first"    => array(
                    "value" => "恭喜您，已成功收到邀请！",
                    "color" => "#173177"
                ),
                "keyword1" => array(
                    "value" => $result["name"]
                ),
                "keyword2" => array(
                    "value" => date('Y-m-d', time()),
                ),
                "keyword3" => array(
                    "value" => $result["doctor_name"] . "医生邀请您来院就诊"
                ),
                "remark"   => array(
                    "value" => "光动力咨询微信平台为您提供最好的服务",
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
//        $where = 'where doctor_id=' . $_POST['doctorId'];
//        if ($_POST['status'] != " ") {
//            $where = $where . ' and handle_type=' . $_POST['status'];
//        }
//        if ($_POST['startTime']) {
//            $where = $where . ' and create_time>' . strtotime($_POST['begin_date']);
//        }
//        if ($_POST['endTime']) {
//            $where = $where . ' and create_time>' . strtotime($_POST['endTime']);
//        }
        $sql = "SELECT * FROM `cms_consultation` WHERE doctor_id=$doctorId and (handle_type=0 or handle_type=4)";


        $result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
        $rowCount = $result->num_rows;

        $pageIndex = $_POST["pageIndex"] - 1;
        $pageStart = $pageIndex * 20;
        $sql       = $sql . ' limit ' . $pageStart . ',' . '20';

        $consultationResult = mysqli_query($connection, $sql) or die(mysqli_error($connection));
        $consulationData = array();
        $pageHtml        = '';
        while ($row = $consultationResult->fetch_assoc()) {
            $consulationData[] = array('id'          => $row['id'],
                                       'name'        => $row['name'],
                                       'phone'       => $row['phone'],
                                       'sex'         => $row['sex'],
                                       'createTime'  => date('Y-m-d', $row["create_time"]),
                                       'handle_type' => $row['handle_type']);
        }
        include('bootstrapPage.php');
        if ($rowCount != 0)
            $pageHtml = bootstrap_page($rowCount, 20, $_POST["pageIndex"], "ajax", 'consultationList');

        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode(array('st' => 1, 'rt' => $consulationData, 'page' => $pageHtml, 'sql' => $sql)));
    case 'consultationListBefore':
//        $where = 'where doctor_id=' . $_POST['doctorId'];
//        if ($_POST['status'] != " ") {
//            $where = $where . ' and handle_type=' . $_POST['status'];
//        }
//        if ($_POST['startTime']) {
//            $where = $where . ' and create_time>' . strtotime($_POST['begin_date']);
//        }
//        if ($_POST['endTime']) {
//            $where = $where . ' and create_time>' . strtotime($_POST['endTime']);
//        }
        $sql = "SELECT t1.*,t2.number,t3.name as med_name FROM `cms_consultation` t1 left join cms_consultation_prescription t2 on t1.id= t2.consultation_id
left join cms_app_cate t3 on t2.cate_id=t3.id
where t1.handle_type in (1,2,3,5,6) and t1.doctor_id=$doctorId";


        $result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
        $rowCount = $result->num_rows;

        $pageIndex = $_POST["pageIndex"] - 1;
        $pageStart = $pageIndex * 20;
        $sql       = $sql . ' limit ' . $pageStart . ',' . '20';

        $consultationResult = mysqli_query($connection, $sql) or die(mysqli_error($connection));
        $consulationData = array();
        $pageHtml        = '';
        while ($row = $consultationResult->fetch_assoc()) {
            $consulationData[] = array('id'          => $row['id'],
                                       'name'        => $row['name'],
                                       'phone'       => $row['phone'],
                                       'sex'         => $row['sex'],
                                       'med_name'         => $row['med_name'],
                                       'number'         => $row['number'],
                                       'createTime'  => date('Y-m-d', $row["create_time"]),
                                       'handle_type' => $row['handle_type']);
        }
        include('bootstrapPage.php');
        if ($rowCount != 0)
            $pageHtml = bootstrap_page($rowCount, 20, $_POST["pageIndex"], "ajax", 'consultationList');

        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode(array('st' => 1, 'rt' => $consulationData, 'page' => $pageHtml, 'sql' => $sql)));
    case 'transConsultationList':

        $where = "where doctor_open_id='" . $_POST['openId'] . "'";

//        if ($_POST['code'] != "") {
//
//            $where = $where . " and (phone like '%" . $_POST['code'] . "%' or id_card like '%" . $_POST['code'] . "%')";
////            header('Content-Type:application/json; charset=utf-8');
////            exit(json_encode(array('sql' => $where)));
//        }
//        if ($_POST['startTime']) {
//            $where = $where . ' and create_time>' . strtotime($_POST['begin_date']);
//        }
//        if ($_POST['endTime']) {
//            $where = $where . ' and create_time>' . strtotime($_POST['endTime']);
//        }

        $sql = "SELECT * FROM `cms_qrcode_consultation`" . $where . ' order by create_time desc';


        $result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
        $rowCount = $result->num_rows;

        $pageIndex       = $_POST["pageIndex"] - 1;
        $pageStart       = $pageIndex * 20;
        $sql             = $sql . ' limit ' . $pageStart . ',' . '20';
        $consulationData = array();
        $pageHtml        = '';
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
        if ($rowCount != 0)
            $pageHtml = bootstrap_page($rowCount, 20, $_POST["pageIndex"], "ajax", 'transConsultationList');

        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode(array('st' => 1, 'rt' => $consulationData, 'page' => $pageHtml, 'sql' => $sql)));
    case 'test':
        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode(array('st' => 0, 'rt' => "诊疗信息已提交！")));
    case 'scanBarcode':

        $sql = "SELECT * FROM `cms_barcode` WHERE code='" . $_POST['code'] . "'";
        $result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
        $barcode  = $result->fetch_assoc();
        $rowCount = $result->num_rows;
        if ($rowCount == 0) {
            header('Content-Type:application/json; charset=utf-8');
            exit(json_encode(array('st' => 0, 'rt' => '无法识别真假')));
        } elseif ($rowCount != 0) {
            if ($barcode['status_id'] == 1) {
                header('Content-Type:application/json; charset=utf-8');
                exit(json_encode(array('st' => 0, 'rt' => '此电子监管码已失效')));
            } else {
                //更新条形码状态
                $now = time();
                $sql = "update cms_barcode set status_id=1, scan_time={$now}, open_id='$_POST[openId]' WHERE code='" . $_POST['code'] . "'";
                mysqli_query($connection, $sql) or die(mysqli_error($connection));
                //医生销量加一
                $sql = "update cms_doctor set sale_count =sale_count+1 where id =(select  identity_id from cms_wechat_user where open_id='$_POST[openId]' limit 1)";
                mysqli_query($connection, $sql) or die(mysqli_error($connection));
                header('Content-Type:application/json; charset=utf-8');
                exit(json_encode(array('st' => 0, 'rt' => $barcode['name'] . '此盒为合格产品，请放心使用！')));
            }
        }


        $sql = "insert into cms_barcode (code) value ('$_POST[code]')";
        $result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode(array('st' => $result, 'rt' => '扫描成功')));
    case 'loadCity':
        $sql = "select * from cms_city where father=$_POST[provinceId]";
        $cityResult = mysqli_query($connection, $sql) or die(mysqli_error($connection));
        while ($row = $cityResult->fetch_assoc()) {
            $cityData[] = array('id'   => $row['cityID'],
                                'name' => $row['city']
            );
        }
        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode($cityData));

    case 'loadDate': //加载日历

        $sql = "select * from cms_doctor_work_time where '$_POST[cDate]'=FROM_UNIXTIME(work_date,'%Y-%m') and doctor_id=$_POST[doctorId]";
//        header('Content-Type:application/json; charset=utf-8');
//        exit(json_encode($sql));
        $result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
        while ($row = $result->fetch_assoc()) {
            $time[] = date('d', $row['work_date']) + 1;
        }
        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode($time));
    case 'getRegion': //获取区间（上午，下午）
        //判断是否在今天之前预定
        $crData = $_POST['cuDate'];
        if (strtotime("$crData   +1   day") < time()) {
            header('Content-Type:application/json; charset=utf-8');
            exit(json_encode(array('st' => 0, 'rt' => '选择时间不能小于当前时间')));
        }
        $sql = "select * from cms_doctor_work_time where doctor_id=$_POST[doctorId] and FROM_UNIXTIME(work_date,'%Y-%m-%d')='$_POST[cuDate]'";
        $result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
        $time = $result->fetch_assoc();

        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode(array('st' => 1, 'rt' => array(
            'forenoon' => $time['forenoon'],
            'forenoon_number' => $time['forenoon_number'],
            'afternoon' => $time['afternoon'],
            'afternoon_number' => $time['afternoon_number']))));



    case 'orderDoctor': //预定医生
        //判断当前患者是否之前有预约相同时间段
        $sql="select * from cms_consultation where id=$_POST[id]";
        $result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
        $conModel= $result->fetch_assoc();
        $openId=$conModel['open_id'];

        $sql="select * from cms_consultation where doctor_id=$_POST[doctorId] and FROM_UNIXTIME(reserve_time,'%Y-%m-%d')='$_POST[cuDate]' and reserve_reg=$_POST[reg] and open_id='$openId'";
        $result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
        $rowCount = $result->num_rows;

        if ($rowCount != 0) {
            header('Content-Type:application/json; charset=utf-8');
            exit(json_encode(array('st' => 0, 'rt' => '您已预约过，无法重复预约')));
        }
        //判断当前超过医生预定限制
        $sql="select * from cms_consultation where doctor_id=$_POST[doctorId] and FROM_UNIXTIME(reserve_time,'%Y-%m-%d')='$_POST[cuDate]' and reserve_reg=$_POST[reg]";
        $result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
        $conCount = $result->num_rows;

        //后台设置限制
        $sql="select * from cms_doctor_work_time where doctor_id=$_POST[doctorId] and FROM_UNIXTIME(work_date,'%Y-%m-%d')='$_POST[cuDate]'";
        $result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
        $doctorTime = $result->fetch_assoc();


        if($_POST['reg']==1&&$doctorTime['forenoon_number']<=$conCount){
            header('Content-Type:application/json; charset=utf-8');
            exit(json_encode(array('st' => 0, 'rt' => '预约人数已满')));
        }
        if($_POST['reg']==2&&$doctorTime['afternoon_number']<=$conCount){
            header('Content-Type:application/json; charset=utf-8');
            exit(json_encode(array('st' => 0, 'rt' => '预约人数已满')));
        }

        $reserve_time = strtotime($_POST['cuDate']);
        $sql          = "update cms_consultation set status_id=4,reserve_time=$reserve_time ,reserve_reg=$_POST[reg] where id=$_POST[id]";
        $result = mysqli_query($connection, $sql) or die(mysqli_error($connection));

        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode(array('st'=>1)));

    case 'doctorHandle': //医生处理订单（接受，拒绝）
        $sql = "update cms_consultation set status_id=$_POST[type] where id=$_POST[id]";
        $result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode(true));

    case 'shuhoufanhui': //术后反馈
        $nowTime=time();
        $sql = "insert into cms_follow (question,pic_id,question_time,patient_openId) values('$_POST[question]',$_POST[pic_id],$nowTime,'$_POST[openId]')";
        $result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode($sql));
    case 'suifang': //随访
        $nowTime=time();
        $sql = "update cms_follow set answer='$_POST[answer]',answer_time=$nowTime,doctor_openId='$_POST[doctor_openId]' where id=$_POST[id]";
        $result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode($sql));


    case 'readFollow': //随访读取

        $sql = "update cms_follow set status_id=1 where id=$_POST[id]";
        $result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode(true));
    default:
        require("text.php");
        break;

}

