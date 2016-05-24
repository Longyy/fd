<?php
include("httpConfig.php");
include("connection.php");
$doctorId = $_GET['doctorId'];
$conId=$_GET['id'];
$sql="select t1.name as doctor_name,t3.name as center_name  from cms_doctor t1 left join cms_medical_center_doctor t2 on t1.id=t2.doctor_id
left join cms_medical_center t3 on t2.medical_center_id=t3.id where t1.id=$doctorId";
$result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
$result = $result->fetch_assoc();
//print_r($result);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link rel="stylesheet" href="style.css">
    <title>预约医生</title>
    <style type="text/css">
        * {
            margin: 0;
            padding: 0;
        }

        .red {
            color: red;
        }

        .date {
            cursor: pointer;
        }

        .today {
            background: #F90;
            font-weight: bold;
            cursor: pointer;
        }

        #calendar {
            width: 260px;
            margin: 50px auto;
        }

        #date {
            text-align: center;
            border: 1px #ccc solid;
            border-bottom: 0;
        }

        #date a {
            display: inline-block;
            width: 18px;
            height: 20px;
            background-position: center -20px;
            vertical-align: middle;
            cursor: pointer;
        }

        #date #selectDate {
            width: 120px;
            display: inline-block;
        }

        #preYear {
            background: url();
        }

        #preMonth {
            background: url();
        }

        #nextMonth {
            background: url();
        }

        #nextYear {
            background: url();
        }

        #calTable {
            width: 100%;
            border-collapse: collapse;
        }

        #calTable th, #calTable td {
            width: 30px;
            height: 20px;
            border: 1px #ccc solid;
            text-align: center;
        }

        #calTable tbody {
            font-family: Georgia, "Times New Roman", Times, serif;
        }

        #cont_bg{display:none;position:fixed;left:0px;top:0px;width:100%;height:100%;z-index:1;background:#000;filter:alpha(Opacity=50);-moz-opacity:0.5;opacity: 0.5;}
        #wd{display:none;position:fixed;width:80%;overflow:hidden;left:50%;margin-left:-40%;background:#fff;top:50%;margin-top:-50px;z-index:2;}
        #wd #wd_top{width:100%;height:20px;background:#9ba5b1;text-align:right;}
        #wd #wd_top img{width:10px;height:10px;margin-right:10px;}
        #wd #frm{width:210px;margin:0px auto;}
        #wd #frm div{float:left;clear:both;width:100%;margin-top:10px;color:#464646;}
        #wd #frm .frm_title{text-align:center;padding-top:30px;}
        #wd #frm p{float:left;}
        #wd #frm .pal{padding-left:15px;}
        #wd #frm .frm_phone input{width:160px;padding:3px 0px;border:1px solid #dedede;margin-left:10px;margin-top:-4px;}
        #wd #frm .frm_btn{text-align:center;padding-bottom:20px;}
        #wd #frm .frm_btn input{width:100px;height:30px;line-height:30px;background:#414141;color:#fff; cursor:pointer;}
    </style>
</head>
<script language="JavaScript" src="js/jquery-1.11.0.min.js" type="text/javascript"></script>
<body style="max-width:640px; text-align:center; margin:0 auto;">

    <div class="invited-hospital">
		<div class="invited-txt calendar_doctor text-center">
			<h3><?php echo($result['doctor_name'])?>医生门诊时间</h3>
			<p><?php echo($result['center_name'])?></p>
		</div>
<div id="calendar" style="width:85%; height:auto">
    <div id="date" style="height:50px; background:#2ba892; color:#FFF">
        <!--<a id="preMonth" title="上一年"><<</a>-->
        <a id="preYear" title="上一月" style="height:50px; line-height:330%; color:#FFF"><<</a>
        <span id="selectDate" style="height:40px;">
            <select id="selectYear">
            </select>
            <select id="selectMonth">
                <option value="1">1月</option>
                <option value="2">2月</option>
                <option value="3">3月</option>
                <option value="4">4月</option>
                <option value="5">5月</option>
                <option value="6">6月</option>
                <option value="7">7月</option>
                <option value="8">8月</option>
                <option value="9">9月</option>
                <option value="10">10月</option>
                <option value="11">11月</option>
                <option value="12">12月</option>
            </select>
        </span>
        <!--<a id="nextYear" title="下一月"></a>-->
        <a id="nextMonth" title="下一年" style="height:50px; line-height:330%;color:#FFF">>></a>
    </div>
    <table id="calTable" style="height:200px">
        <thead style="background:#53c3af">
        <tr>
            <th class="red">日</th>
            <th>一</th>
            <th>二</th>
            <th>三</th>
            <th>四</th>
            <th>五</th>
            <th class="red">六</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        </tbody>
    </table>
</div>

<div id="cont_bg"></div>
<form id="form_choice_date" method="post" action="<?php echo($pageUrl.'ordersuccess.php')?>">
    <input type="hidden" name="doctor_id" value="<?php echo $doctorId;?>"/>
    <input type="hidden" name="con_id" value="<?php echo $conId;?>"/>
    <div id="wd">
        <div id="wd_top"><img src="images/close.png" style="margin-top: 5px;"></div>
        <div id="frm">

                <div class="frm_title">请选择</div>
                <div class="frm_name" style="text-align: center">

                </div>

                <div class="frm_btn"><input type="button" value="确认" onclick="orderDoctor(this)" id="sub"></div>

        </div>
    </div>
        <br/>
            <p class="rl-font rl-txt">温馨提示：橙色为可预约治疗鲜红斑痣时间</p>
            <p class="rl-font rl-txt">门诊当日可预约上午（请务必在11点前到院就诊）</p>
            <p class="rl-font rl-txt">或者下午（请务必在4点前到院就诊）</p>
    <div id="tijiao" style="width:85%; margin-left:7.5%">
    <ul style="100%; margin-bottom:20px;">
    <li style="width:20%; height:30px; background:#50c1e9;float:left; margin-left:18%; margin-bottom:20px; color:#FFF">
        <a href="#" onclick="submit();" style="color:#fff; line-height:250%">确认</a>
    </li>
    <li style="width:20%; height:30px; background:#50c1e9;float:left; margin-left:30%; margin-bottom:20px; color:#FFF"><a href="#" style="color:#fff; line-height:250%">取消</a></li>
    </ul>
    </div>
</form>
</div>
<script type="text/javascript">
    function submit()
    {
        $('#form_choice_date').submit();
    }

var doc = document;
var currentDate = [];
//显示日历
var date = new Date();
showDate(date.getFullYear(),date.getMonth() + 1)
function Calendar() {
    this.init.apply(this, arguments);
}
Calendar.prototype = {
    init: function (tableId, dateId, selectY, selectM, startYear, endYear) {
        var table = doc.getElementById(tableId);
        var dateObj = doc.getElementById(dateId);
        var selectY = doc.getElementById(selectY);
        var selectM = doc.getElementById(selectM);
        this._setSelectYear(selectY, startYear, endYear);
//		this._setTodayDate(table,selectY,selectM);
//		debugger;
        var date = new Date();
        this._showCalendar(table, date.getFullYear(), date.getMonth());
        this._selectChange(table, selectY, selectM);
        this._clickBtn(table, dateObj, selectY, selectM, startYear, endYear);
    },
    //设置年份
    _setSelectYear: function (selectY, startYear, endYear) {
        var html = "";
        var date = new Date();
        if (!endYear) {
            var endYear = date.getFullYear();
        } else {
            var endYear = endYear;
        }
        for (var i = startYear; i <= endYear; i++) {
            var _option = document.createElement('option');
            selectY.appendChild(_option);
            _option.value = i;
            _option.innerHTML = i;
        }
    },
    //鼠标移入移出日期
    _mouseOn: function (obj) {
        obj.onmouseover = function () {
            if (this.innerHTML) {
                this.style.background = "#bbb";
            }
        }
        obj.onmouseout = function () {
            this.style.background = "";
        }
        obj.onclick = function () {
            getRegion($(this).attr("class"),$(this).html())


        }
    },

    //下拉菜单选择日期
    _selectChange: function (table, selectY, selectM) {
        var _this = this;
        selectY.onchange = function () {
            var year = _this._getSelectValue(selectY);
            var month = _this._getSelectValue(selectM) - 1;
            _this._showCalendar(table, year, month);
//            alert('selectYear')
        }
        selectM.onchange = function () {
            var year = _this._getSelectValue(selectY);
            var month = _this._getSelectValue(selectM) - 1;
            ;
            _this._showCalendar(table, year, month);
//            alert('selectMonth')
        }
    },
    //获取下拉菜单的默认值
    _getSelectValue: function (selectObj) {
        var selectList = selectObj.getElementsByTagName('option');
        for (var i = 0, len = selectList.length; i < len; i++) {
            var _option = selectList[i];
            if (_option.selected) {
                return parseInt(_option.value);
            }
        }
    },
    //设置下拉菜单默认值
    _setSelectValue: function (selectObj, value) {
        var selectList = selectObj.getElementsByTagName('option');
        for (var i = 0, len = selectList.length; i < len; i++) {
            var _option = selectList[i];
            if (parseInt(_option.value) == value) {
                _option.selected = true;
                break;
            }
        }
    },
    _clickBtn: function (table, dateObj, selectY, selectM, startYear, endYear) {
//		debugger;
        var _this = this, year = 0;
        var btn = dateObj.getElementsByTagName('a');
//		btn[0].onclick=function(){
//			year=_this._getSelectValue(selectY)-1;
//			var month=_this._getSelectValue(selectM);
//			if(!isYearOver(year)){
//				return;
//			}
//			_this._setSelectValue(selectY,year);
//			_this._setSelectValue(selectM,month);
//			_this._showCalendar(table,year,month-1);
//		}
        btn[0].onclick = function () {
//            debugger;
            year = _this._getSelectValue(selectY);
            var month = _this._getSelectValue(selectM) - 1;
            if (month <= 0) {
                month = 12;
                year--;
            }
//			if(!isYearOver(year)){
//				return;
//			}
            _this._setSelectValue(selectM, month);
            _this._setSelectValue(selectY, year);
            //加载后台设置数据

            showDate($("#selectYear").val(),$("#selectMonth").val());
//            alert($("#selectYear").val());
//            alert($("#selectMonth").val());

            _this._showCalendar(table, year, month - 1);
        }
        btn[1].onclick = function () {
            year = _this._getSelectValue(selectY);
            var month = _this._getSelectValue(selectM) + 1;
            if (month > 12) {
                month = 1;
                year++;
            }
            if (!isYearOver(year)) {
                return;
            }
            _this._setSelectValue(selectM, month);
            _this._setSelectValue(selectY, year);
            //加载后台设置数据
            showDate($("#selectYear").val(),$("#selectMonth").val());
            _this._showCalendar(table, year, month - 1);
        }
//		btn[3].onclick=function(){
//			year=_this._getSelectValue(selectY)+1;
//			var month=_this._getSelectValue(selectM);
//			if(!isYearOver(year)){
//				return;
//			}
//			_this._setSelectValue(selectM,month);
//			_this._setSelectValue(selectY,year);
//			_this._showCalendar(table,year,month-1);
//		}
        function isYearOver(year) {
            var date = new Date();
            var _endYear = endYear ? endYear : date.getFullYear();
            if (year > _endYear || year < startYear) {
                alert("超出日期范围");
                return false;
                ;
            } else {
                return true;
            }
        }
    },
    //显示日历
    _showCalendar: function (table, year, month) {
//        debugger;
        var date = new Date();
        var _year = date.getFullYear();
        var _month = date.getMonth();
        var _date = date.getDate();
        date.setYear(year);
        date.setMonth(month);
        date.setDate(1);
        var day = date.getDay();
        var _this = this;
        var monthDays = this._getMonthDays(year, month);
        var td = table.getElementsByTagName('td');
        for (var k = 0; k < td.length; k++) {
            td[k].innerHTML = "";
            td[k].className = "";
        }
        for (var i = day, len = td.length; i < len; i++) {
            var _td = td[i];
            var j = i - day + 1;
            _td.innerHTML = j;
            _td.className = "date";
//debugger;
            if(j<10){j="0"+ j.toString();}
            else{j= j.toString()}
            if ($.inArray(j, currentDate) != -1) {
                _td.className = "today";
                _this._mouseOn(_td);
            } else {
                _this._mouseOn(_td);
            }
            if (j >= monthDays) {
                break;
            }
        }
    },
    //返回某个月的天数
    _getMonthDays: function (year, month) {
        var monthAry = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
        if (year % 400 == 0) {
            monthAry[1] = 29;
        } else {
            if (year % 4 == 0 && year % 100 != 0) {
                monthAry[1] = 29;
            }
        }
        return monthAry[month];
    }
}
new Calendar("calTable", "date", "selectYear", "selectMonth", 2015, 2030);
var date = new Date();
//alert(date.getMonth())
$("#selectYear").val(date.getFullYear())
$("#selectMonth").val(date.getMonth() + 1)

function showDate(year,month){
    if(month<10){
        month="0"+month.toString();
    }
    var cDate=year+"-"+month;
    $.ajax({
        type: "POST",
        async:false,
        url: '<?php echo( $postUrl) ?>',
        data: {'postType': 'loadDate', 'cDate':cDate,'doctorId':'<?php echo($doctorId)?>'},
        success: function (data) {
            currentDate=[];

            if (data) {
                $.each(data, function () {
                    var day=this;
                    if(day<10){
                        day="0"+day;
                    }
                    else{
                        day=day.toString();
                    }
                    currentDate.push(day);
                })
            } else {
                currentDate = {};
            }


        }
    })
}
var height=$("#wd").height();
$("#wd").css("margin-top",-height/2);

$("#wd_top img").click(function(){
    $("#cont_bg").hide();
    $("#wd").hide();
});
//获取区间范围
function getRegion(type, day) {
    if (type == "today") {
        var month=$("#selectMonth").val();
        if(month<10){
            month="0"+month.toString();
        }

        if(day<10){
            day="0"+day;
        }
        var cuDate=$("#selectYear").val()+"-"+month+"-"+day;
//        alert($("#selectYear").val())
//        alert($("#selectMonth").val())
        $.ajax({
            type: "POST",
            async: false,
            url: '<?php echo( $postUrl) ?>',
            data: {'postType': 'getRegion', 'doctorId': '<?php echo($doctorId)?>','cuDate':cuDate},
            success: function (data) {
                if(data.st==1){
                    var rad='';
                    if(data.rt.forenoon=="1"){
                        rad+="<input type='radio' data-day='"+day+"' name='reg' value='1'>上午";
                    }
                    if(data.rt.afternoon=="1"){
                        rad+="<input type='radio' data-day='"+day+"' name='reg' value='2'>下午";
                    }
                    $(".frm_name").html(rad);
                    $("#cont_bg").show();
                    $("#wd").show();
                }
                else{
                    alert(data.rt);
                }


            }
        })


    }
}

//预定医生
function orderDoctor(){
<!--    var openId = '--><?php //echo $result["openid"];?><!--'-->
    var month=$("#selectMonth").val();
    if(month<10){
        month="0"+month.toString();
    }

    var radReg = $('input:radio[name="reg"]:checked');

    var day=$(radReg).data("day");
    if(day==undefined){
        alert('请选择');
        return;
    }
//    alert($(radReg).data("day"));

    var cuDate=$("#selectYear").val()+"-"+month+"-"+day;
    $.ajax({
        type: "POST",
        url: '<?php echo( $postUrl) ?>',
        data: {'postType': 'orderDoctor', 'id': '<?php echo($conId)?>','cuDate':cuDate,'reg':$(radReg).val(),'doctorId': '<?php echo($doctorId)?>'},
        success: function (data) {
            if (data.st == 1) {
                alert('预约成功');
                $("#cont_bg").hide();
                $("#wd").hide();
            }
            else{
                alert(data.rt);
            }
        }
    })
}
</script>

</body>
</html>