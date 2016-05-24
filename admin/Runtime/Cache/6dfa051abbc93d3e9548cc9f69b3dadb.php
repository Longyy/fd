<?php if (!defined('THINK_PATH')) exit();?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=7" />
<link href="__ROOT__/statics/admin/css/style.css" rel="stylesheet" type="text/css"/>
<link href="__ROOT__/statics/css/dialog.css" rel="stylesheet" type="text/css" />

<script language="javascript" type="text/javascript" src="__ROOT__/statics/js/jquery/jquery.min.js"></script>
<script language="javascript" type="text/javascript" src="__ROOT__/statics/js/jquery/plugins/formvalidator.js"></script>
<script language="javascript" type="text/javascript" src="__ROOT__/statics/js/jquery/plugins/formvalidatorregex.js"></script>

<script language="javascript" type="text/javascript" src="__ROOT__/statics/admin/js/admin_common.js"></script>
<script language="javascript" type="text/javascript" src="__ROOT__/statics/js/dialog.js"></script>


<script language="javascript" type="text/javascript" src="__ROOT__/statics/js/iColorPicker.js"></script>

<?php if(($calendar) == "1"): ?><script type="text/javascript" src="__ROOT__/statics/js/calendar/calendar.js"></script>
	<link rel="stylesheet" type="text/css" href="__ROOT__/statics/js/calendar/calendar-blue.css"><?php endif; ?>
<script language="javascript">
var URL = '__URL__';
var ROOT_PATH = '__ROOT__';
var APP	 =	 '__APP__';
var lang_please_select = "<?php echo (L("please_select")); ?>";
var def=<?php echo ($def); ?>;
$(function($){
	$("#ajax_loading").ajaxStart(function(){
		$(this).show();
	}).ajaxSuccess(function(){
		$(this).hide();
	});
});

</script>
<title><?php echo (L("website_manage")); ?></title>
</head>
<body>
<div id="ajax_loading">提交请求中，请稍候...</div>
<?php if($show_header != false): if(($sub_menu != '') OR ($big_menu != '')): ?><div class="subnav">
    <div class="content-menu ib-a blue line-x">
    	<?php if(!empty($big_menu)): ?><a class="add fb" href="<?php echo ($big_menu["0"]); ?>"><em><?php echo ($big_menu["1"]); ?></em></a>　
		    <?php if(!empty($big_menu["2"])): ?><a class="add fb" href="<?php echo ($big_menu["2"]); ?>"><em><?php echo ($big_menu["3"]); ?></em></a>　<?php endif; endif; ?>
    </div>
</div><?php endif; endif; ?>

<form action="<?php echo u('Doctor/editDoctorWork');?>" method="post" name="myform" id="myform" enctype="multipart/form-data"
      style="margin-top:10px;">
	<input name="id" value="<?php echo ($doctor_work_time['id']); ?>" type="hidden">
	<div class="pad-10">
		<div class="col-tab">
			<ul class="tabBut cu-li">
				<li id="tab_setting_1" class="on" onclick="SwapTab('setting','on','',2,1);"><?php echo (L("general_info")); ?>
				</li>
			</ul>
			<div id="div_setting_1" class="contentList pad-10">
				<table width="100%" cellpadding="2" cellspacing="1" class="table_form">
					<tr>
						<th width="100">医疗中心:</th>
						<td>
							<select onchange="selectCenter(this)" name="medical_center_id" id="medical_center_id" style="width:200px;">
								<option value="">--请选择--</option>
								<?php if(is_array($medicalCenterData)): $i = 0; $__LIST__ = $medicalCenterData;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><option value="<?php echo ($val["id"]); ?>" <?php if(($val['id']) == $doctor_work_time['center_id']): ?>selected="selected"<?php endif; ?>><?php echo ($val["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
							</select>
						</td>
					</tr>
					<tr>
						<th width="100">医生:</th>
						<td>
							<select name="doctor_id" id="ddlDoctor" style="width:200px;">
								<?php if(is_array($doctorData)): $i = 0; $__LIST__ = $doctorData;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><option value="<?php echo ($val["doctor_id"]); ?>" <?php if(($val['doctor_id']) == $doctor_work_time['doctor_id']): ?>selected="selected"<?php endif; ?>><?php echo ($val["doctor_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
							</select>
						</td>
					</tr>
					<tr>
						<th width="100">上班时间:</th>
						<td>
							<link rel="stylesheet" type="text/css" href="__ROOT__/statics/js/calendar/calendar-blue.css"/>
			<script type="text/javascript" src="__ROOT__/statics/js/calendar/calendar.js"></script>
		<input class="date input-text" type="text" name="work_date" id="work_date" size="18" value="<?php echo date('Y-m-d',$doctor_work_time['work_date']);?>" />	
					<script language="javascript" type="text/javascript">
	                    Calendar.setup({
	                        inputField     :    "work_date",
	                        ifFormat       :    "%Y-%m-%d",
	                        showsTime      :    "true",
	                        timeFormat     :    "24"
	                    });
	     </script>
						</td>
					</tr>
					<tr>
						<th>区间 :</th>
						<td>
							<input name="forenoon"  <?php if(($doctor_work_time['forenoon']) == "1"): ?>checked<?php endif; ?> value="1" type="checkbox">上午
							<input style="width: 50px;" value="<?php echo ($doctor_work_time['forenoon_number']); ?>" name="forenoon_number" onkeyup="this.value=this.value.replace(/\D/g,'')"   onafterpaste="this.value=this.value.replace(/\D/g,'')" type="text" >人
							<br/>
							<input <?php if(($doctor_work_time['afternoon']) == "1"): ?>checked<?php endif; ?> name="afternoon" value="1" type="checkbox">下午
							<input style="width: 50px;" name="afternoon_number" onkeyup="this.value=this.value.replace(/\D/g,'')"    onafterpaste="this.value=this.value.replace(/\D/g,'')" type="text" value="<?php echo ($doctor_work_time['afternoon_number']); ?>">人
						</td>
					</tr>


				</table>
			</div>

			<div class="bk15"></div>
			<div class="btn" style="display: none"><input type="submit" value="<?php echo (L("submit")); ?>" onclick="return submitFrom();"
			                                              name="dosubmit" class="button" id="dosubmit"></div>
		</div>
	</div>
</form>
<script type="text/javascript">
	$(function(){
		$.formValidator.initConfig({formid:"myform",autotip:true,onerror:function(msg,obj){window.top.art.dialog({content:msg,lock:true,width:'250',height:'50'}, function(){this.close();$(obj).focus();})}});
//		$("#ddlDoctor").formValidator({onshow:"不能为空",onfocus:"不能为空"}).inputValidator({min:1,onerror:"请选择医生"});
		$("#setup_date").formValidator({onshow:"不能为空",onfocus:"不能为空"}).inputValidator({min:1,onerror:"请填写职称"});
	})

	function selectCenter(obj){
		debugger;
		if($(obj).val()=="")return;
		$("#ddlDoctor").empty();
		$.ajax({
			type: "POST",
			url: '<?php echo u("Doctor/getDoctorById");?>',
			data: { 'id': $(obj).val()},
			success: function (data) {
				debugger;
				var objSelect = document.getElementById("ddlDoctor");
				$.each(data, function (index, row) {
					debugger;
					var new_opt = new Option(row.doctor_name, row.doctor_id);
					objSelect.options.add(new_opt);
//                    $("<option></option>").text(row.name).val(row.id).appendTo("#ddlDoctor")
				})
			}
		})
	}
</script>
</body></html>