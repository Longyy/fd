<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
<link href="__ROOT__/statics/admin/css/main.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript">
	$(function () {
		$(".main_con").hover(
				function () {
					$(this).css("background", "#ffffcc");
				},
				function () {
					$(this).css("background", "#ffffff");
				}
		);
	})
</script>
<div style="text-align: left;margin-top: 5px;">
	快速查询：
	<select id="ddlCondition" style="width: 200px;height: 35px;">
		<option value="1">转诊单</option>
		<option value="2">患者</option>
		<option value="3">转诊医生</option>
		<option value="4">治疗医生</option>
		<option value="5">转诊医院</option>
	</select>
	内容：<input type="text" style="width: 300px;height: 30px;" id="txtContent" onkeydown="search()" >
	<input type="button" onclick="search(this)" value="查询" style="width: 55px; height: 34px;cursor: pointer">
</div>
<div id="divContent">

</div>
</body>
</html>
<script>
	function search(obj){
		if (event.keyCode == 13||$(obj).val()=="查询"){
			$.post('<?php echo u('Public/search');?>', {condition:$("#ddlCondition").val(),content:$("#txtContent").val()}, function(data) {
				$("#divContent").html(data);
			});
		}
	}
</script>