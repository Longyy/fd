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
<script type="text/javascript">
	$(function () {
		$("#add_attatch").click(function () {
			$("#attatch_tr").clone().prependTo($("#attatch_tr").parent());
		});
	})
</script>
<form action="<?php echo u('Saler/add');?>" method="post" name="myform" id="myform" enctype="multipart/form-data"
      style="margin-top:10px;">
	<div class="pad-10">
		<div class="col-tab">
			<ul class="tabBut cu-li">
				<li id="tab_setting_1" class="on" onclick="SwapTab('setting','on','',2,1);"><?php echo (L("general_info")); ?>
				</li>
			</ul>
			<div id="div_setting_1" class="contentList pad-10">
				<table width="100%" cellpadding="2" cellspacing="1" class="table_form">
					<tr>
						<th width="100">姓名:</th>
						<td><input type="text" name="name" id="name" class="input-text"></td>
					</tr>
					<tr>
						<th>性别 :</th>
						<td>
							<select id="sex" name="sex">
								<option value="1">男</option>
								<option value="0">女</option>
							</select>
						</td>
					</tr>
					<tr>
						<th>是否在职 :</th>
						<td>
							<select id="is_job" name="is_job">
								<option value="1">在职</option>
								<option value="0">离职</option>
							</select>
						</td>
					</tr>
					<tr>
						<th width="100">区域部门:</th>
						<td>
							<select name="department_id" id="department_id" style="width:200px;">
								<?php if(is_array($departmentData)): $i = 0; $__LIST__ = $departmentData;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><option value="<?php echo ($val["id"]); ?>"><?php echo ($val["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
							</select>
						</td>
					</tr>
					<!--<tr>-->
						<!--<th width="100">职称:</th>-->
						<!--<td><input type="text" name="level_name" id="level_name" class="input-text"></td>-->
					<!--</tr>-->
					<!--<tr>-->
					<!--<th>照片 :</th>-->
					<!--<td><input type="file" name="img" id="img" class="input-text" style="width:200px;"/></td>-->
					<!--</tr>-->
					<tr>
						<th width="100">手机:</th>
						<td><input type="text" name="phone" id="phone" class="input-text"></td>
					</tr>
					<tr>
						<th width="100">邮箱:</th>
						<td><input type="text" name="email" id="email" class="input-text"></td>
					</tr>
					<tr>
						<th width="100">分配号:</th>
						<td>
							<input type="text" name="order_begin" id="order_begin" class="input-text"  onkeyup="value=value.replace(/[^\d]/g,'')"
							       onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))" >—>
							<input type="text" name="order_end" id="order_end" class="input-text"  onkeyup="value=value.replace(/[^\d]/g,'')"
							       onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))">
						</td>
					</tr>

				</table>
			</div>

			<div class="bk15"></div>
			<div class="btn" style="display: none"><input type="submit" value="<?php echo (L("submit")); ?>"
			                                              onclick="return submitFrom();"
			                                              name="dosubmit" class="button" id="dosubmit"></div>
		</div>
	</div>
</form>
<script type="text/javascript">
	$(function () {
		$.formValidator.initConfig({formid: "myform", autotip: true, onerror: function (msg, obj) {
			window.top.art.dialog({content: msg, lock: true, width: '250', height: '50'}, function () {
				this.close();
				$(obj).focus();
			})
		}});

		$("#name").formValidator({onshow: "不能为空", onfocus: "不能为空"}).inputValidator({min: 1, onerror: "请填写名称"});
		$("#level_name").formValidator({onshow: "不能为空", onfocus: "不能为空"}).inputValidator({min: 1, onerror: "请填写职称"});
		$("#phone").formValidator({ empty: false, onshow: "请输入你的手机号码，可以为空哦", onfocus: "你要是输入了，必须输入正确", oncorrect: "输入正确", onempty: "你真的不想留手机号码啊？" }).inputValidator({ min: 11, max: 11, onerror: "手机号码必须是11位的,请确认" }).regexValidator({ regexp: "mobile", datatype: "enum", onerror: "你输入的手机号码格式不正确" });
		$("#email").formValidator({empty: false, onshow: "请输入邮箱", onfocus: "邮箱6-100个字符,输入正确了才能离开焦点", oncorrect: "恭喜你,你输对了", defaultvalue: ""}).inputValidator({ min: 6, max: 100, onerror: "你输入的邮箱长度非法,请确认" }).regexValidator({ regexp: "email", datatype: "enum", onerror: "你输入的邮箱格式不正确" });
	})
</script>
</body></html>