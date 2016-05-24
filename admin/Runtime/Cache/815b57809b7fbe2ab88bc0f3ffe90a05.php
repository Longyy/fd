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
<script language="javascript" type="text/javascript" src="__ROOT__/statics/js/jquery/jquery.role.js"></script>
<div class="pad-lr-10">
	<form id="myform" name="myform" action="<?php echo u('Doctor/delete');?>" method="post" onsubmit="return check();">
		<div class="table-list">
			<table width="100%" cellspacing="0">
				<thead>
				<tr>
					<th width="50">ID</th>
					<!--<th width="20"><input type="checkbox" value="" id="check_box" onclick="selectall('id[]');"></th>-->
					<th width="50">患者姓名</th>
					<th>性别</th>
					<th width="60">年龄</th>
					<th width="120">手机</th>
					<th width="120">身份证号</th>
					<th width="120">预约类型</th>
					<th width="120">预约/转诊号</th>
					<th width="120">医生</th>
					<th width="120">临床诊断</th>
					<th width="120">病情描述</th>
					<th width="120">曾经过往治疗史</th>
					<th width="120">治疗要求</th>
					<th width="120">订单状态</th>
					<!--<th width="120">操作</th>-->
				</tr>
				</thead>
				<tbody>
				<?php if(is_array($orderList)): $i = 0; $__LIST__ = $orderList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><tr>
						<td align="center"><?php echo ($val["id"]); ?></td>
						<!--<td align="center"><input type="checkbox" value="<?php echo ($val["id"]); ?>" name="id[]"></td>-->
						<td align="center"><?php echo ($val["name"]); ?></td>
						<td align="center">
							<?php if(($val['sex']) == "1"): ?>男
								<?php else: ?>
								女<?php endif; ?>
						</td>
						<td align="center"><?php echo ($val["age"]); ?></td>
						<td align="center"><?php echo ($val["phone"]); ?></td>
						<td align="center"><?php echo ($val["id_card"]); ?></td>
						<td align="center">
							<?php if(($val['order_type']) == "1"): ?>预约
								<?php else: ?>
								转诊<?php endif; ?>
						</td>
						<td align="center"><?php echo ($val["order_code"]); ?></td>
						<td align="center"><?php echo ($val["doctor_name"]); ?></td>
						<td align="center"><?php echo ($val["diagnose"]); ?></td>
						<td align="center"><?php echo ($val["description"]); ?></td>
						<td align="center"><?php echo ($val["before_treat"]); ?></td>
						<td align="center"><?php echo ($val["demand"]); ?></td>
						<td align="center">
							<?php switch($val["status_id"]){ case -1: echo("未处理"); break; case 0: echo("已处理"); break; case -1: echo("已完成"); break; }?>
						</td>
					</tr><?php endforeach; endif; else: echo "" ;endif; ?>
				</tbody>
			</table>

			<div class="btn">
				<!--<label for="check_box" style="float:left;">全选/取消</label>-->
				<!--<input type="submit" class="button" name="dosubmit" value="删除" onclick="return confirm('确定删除')"-->
				<!--style="float:left;margin-left:10px;"/>-->
				<div id="pages"><?php echo ($page); ?></div>
			</div>


		</div>
	</form>
</div>
<script language="javascript">


	function check() {
		var ids = '';
		$("input[name='id[]']:checked").each(function (i, n) {
			ids += $(n).val() + ',';
		});

		if (ids == '') {
			window.top.art.dialog({content: '请选择你需要操作的医生', lock: true, width: '200', height: '50', time: 1.5}, function () {
			});
			return false;
		}
		return true;
	}


</script>
</body>
</html>