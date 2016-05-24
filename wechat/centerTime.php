<?php
include("httpConfig.php");
include("connection.php");
//是否登录注册过，没有进行跳转
$sql = "SELECT t1.*, t2.begin_time,t2.end_time FROM cms_medical_center_time t1 LEFT JOIN cms_medical_time t2 ON t1.medical_time_id=t2.`id`
WHERE t1.`medical_center_id`=" . $_GET['id'];
$result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
$rowCount = $result->num_rows;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-8-24
 * Time: 下午12:04
 */
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no"/>
    <title>医疗中心上班时间配置</title>
    <script language="JavaScript" src="js/jquery-1.11.0.min.js" type="text/javascript"></script>
    <script language="JavaScript" src="js/bootstrap.min.js" type="text/javascript"></script>
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <style type="text/css">
        .classYes {
            background-color: red;
        }

        th {
            width: 100px;
        }

        table.imagetable {
            font-family: verdana, arial, sans-serif;
            font-size: 11px;
            color: #333333;
            border-width: 1px;
            border-color: #999999;
            border-collapse: collapse;
        }

        table.imagetable th {
            background: #b5cfd2 url('cell-blue.jpg');
            border-width: 1px;
            padding: 8px;
            border-style: solid;
            border-color: #999999;
        }

        .tdClass {
            background: #dcddc0;
            border-width: 1px;
            padding: 8px;
            height: 30px;
            border-style: solid;
            border-color: #999999;
        }

        .tdClass2 {
            background-color: green;
            border-width: 1px;
            padding: 8px;
            height: 30px;
            border-style: solid;
            border-color: #999999;
        }
    </style>
</head>
<body style="padding-top: 10px; padding-left: 10px; padding-right: 10px;">
<div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading" style="height: 40px;">
        <div style="float: left;font-weight:bold;">上班时间表&nbsp;&nbsp;&nbsp;</div>
        <div style="float: left; width: 20px; height: 15px; background-color: green; margin-top: 3px; "></div style="float: left">
        <div style="float: left">正常上班&nbsp;&nbsp;</div>
        <div style="float: left; width: 20px; background-color:#dcddc0;  height: 15px; margin-top: 3px;"></div>
        <div style="float: left">休息</div>
    </div>
    <?php if ($rowCount == 0) { ?><br/><br/>该医疗中心尚未设置上班时间<br/><br/><br/><?php return;
    } ?>
    <div class="panel-body">
        <table class="imagetable">
            <tr>
                <th></th>
                <th>周一</th>
                <th>周二</th>
                <th>周三</th>
                <th>周四</th>
                <th>周五</th>
                <th>周六</th>
                <th>周日</th>
            </tr>
            <?php
            while ($val = $result->fetch_assoc()) {
                ?>
                <tr>
                    <td class="tdClass" style="width: 200px;">
                        <?
                        $dateBegin = explode(',', $val['begin_time']);
                        $dateEnd = explode(',', $val['end_time']);
                        ?>
                        <? echo($dateBegin[0]) ?>
                        :
                        <? echo($dateBegin[1] == 0 ? "00" : "30") ?><br/> --><br/>
                        <? echo($dateEnd[0]) ?>
                        :<? echo($dateEnd[1] == 0 ? "00" : "30") ?>
                        <input type="hidden" name="timesIds[]" value="{$val['medical_time_id']}">
                    </td>
                    <td <?php if ($val['monday'] == 1){ ?>class="tdClass2"<?php } else{ ?> class="tdClass"<?php } ?>
                    </td>
                    <td <?php if ($val['tuesday'] == 1){ ?>class="tdClass2"<?php } else{ ?> class="tdClass"<?php } ?>
                    </td>
                    <td <?php if ($val['wednesday'] == 1){ ?>class="tdClass2"<?php } else{ ?> class="tdClass"<?php } ?>
                    </td>
                    <td <?php if ($val['thursday'] == 1){ ?>class="tdClass2"<?php } else{ ?> class="tdClass"<?php } ?>
                    </td>
                    <td <?php if ($val['friday'] == 1){ ?>class="tdClass2"<?php } else{ ?> class="tdClass"<?php } ?>
                    </td>
                    <td <?php if ($val['saturday'] == 1){ ?>class="tdClass2"<?php } else{ ?> class="tdClass"<?php } ?>
                    </td>
                    <td <?php if ($val['sunday'] == 1){ ?>class="tdClass2"<?php } else{ ?> class="tdClass"<?php } ?>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>


</div>
<center>
    本服务由“光动力咨询微信平台”提供
</center>
</body>
