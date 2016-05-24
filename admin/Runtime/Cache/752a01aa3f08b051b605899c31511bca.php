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
<div class="pad-lr-10">
	<form name="searchform" action="" method="post" >
		<table width="100%" cellspacing="0" class="search-form">
			<tbody>
			<tr>
				<td>
					<div class="explain-col">
						开始时间：

						<input class="date input-text" type="text" name="begin_date" id="begin_date" size="18" value="<?php echo ($begin); ?>">
						<script language="javascript" type="text/javascript">
							Calendar.setup({
								inputField     :    "begin_date",
								ifFormat       :    "%Y-%m-%d %H:%M:00",
								showsTime      :    "true",
								timeFormat     :    "24"
							});
						</script>

						结束时间：

						<input class="date input-text" type="text" name="end_date" id="end_date" size="18" value="<?php echo ($end); ?>">
						<script language="javascript" type="text/javascript">
							Calendar.setup({
								inputField     :    "end_date",
								ifFormat       :    "%Y-%m-%d %H:%M:00",
								showsTime      :    "true",
								timeFormat     :    "24"
							});
						</script>
						状态：
						<select name="status">
							<option value="">--请选择--</option>
							<option value="1">邀请来院</option>
							<option value="2">治疗意见</option>
							<option value="3">作废</option>
							<option value="0">尚未处理请等待</option>
						</select>
						<input type="hidden" name="m" value="Consulation" />
						<input type="submit" name="search" class="button" value="搜索" />
					</div>
				</td>
				<td>

				</td>
			</tr>
			</tbody>
		</table>
	</form>
	<form id="myform" name="myform" action="<?php echo u('Consulation/delete');?>" method="post" onsubmit="return check();">
		<div class="table-list">
			<table width="100%" cellspacing="0">
				<thead>
				<tr>
					<th width="50">ID</th>
					<th width="20"><input type="checkbox" value="" id="check_box" onclick="selectall('id[]');"></th>
					<th width="200">提交时间</th>
					<th width="50">姓名</th>
					<th>性别</th>
					<!-- <th width="60">年龄</th> -->
					<th width="120">手机</th>
					<th width="120">出生日期</th>
					<th width="120">信息来源</th>
					<th width="120">求诊类型</th>
					<!-- <th width="120">转诊号</th> -->
					<th width="120">临床诊断</th>
					<th width="120">病情描述</th>
					<th width="120">曾经过往治疗史</th>
					<!-- <th width="120">治疗要求</th> -->
					<th width="120">医疗中心</th>
					<th width="120">求诊医生</th>
					<th width="120">处理状态</th>
				</tr>
				</thead>
				<tbody>
				<?php if(is_array($consulationList)): $i = 0; $__LIST__ = $consulationList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><tr>
						<td align="center"><?php echo ($val["id"]); ?></td>
						<td align="center"><input type="checkbox" value="<?php echo ($val["id"]); ?>" name="id[]"></td>
						<td align="center"><?php echo date('Y年m月d日H点i分',$val['create_time']);?></td>
						<td align="center"><?php echo ($val["name"]); ?></td>
						<td align="center">
							<?php if(($val['sex']) == "1"): ?>男<?php else: ?>女<?php endif; ?>
						</td>
						<!-- <td align="center"><?php echo ($val["age"]); ?></td> -->
						<td align="center"><?php echo ($val["phone"]); ?></td>
						<td align="center"><?php echo ($val["birthday"]); ?></td>
						<td align="center"><?php echo ($val["resource"]); ?></td>
						<td align="center">
							<?php if(($val['order_type']) == "1"): ?>预约<?php else: ?>转诊<?php endif; ?>
						</td>
						<!-- <td align="center"><?php echo ($val["order_code"]); ?></td> -->
						<td align="center"><?php echo ($val["diagnose"]); ?></td>
						<td align="center"><?php echo ($val["description"]); ?></td>
						<td align="center"><?php echo ($val["before_treat"]); ?></td>
						<!-- <td align="center"><?php echo ($val["demand"]); ?></td> -->
						<td align="center"><?php echo ($val["center_name"]); ?></td>
						<td align="center"><?php echo ($val["doctor_name"]); ?>|<?php echo ($val["handle_type"]); ?></td>
						<td align="center">
							<?php switch($val["handle_type"]): case "1": ?>邀请来院<?php break;?>
								<?php case "2": ?>治疗意见<?php break;?>
								<?php case "3": ?>作废<?php break;?>
								<?php default: ?>尚未处理请等待<?php endswitch;?>
						</td>
					</tr><?php endforeach; endif; else: echo "" ;endif; ?>
				</tbody>
			</table>

			<div class="btn">
				<label for="check_box" style="float:left;">全选/取消</label>
				<input type="submit" class="button" name="dosubmit" value="删除" onclick="return confirm('确定删除')"
				       style="float:left;margin-left:10px;"/>
				<div id="pages"><?php echo ($page); ?></div>
			</div>


		</div>
	</form>
</div>
<script language="javascript">
	function edit(id, name) {
		var lang_edit = "编辑";
		window.top.art.dialog({id: 'edit'}).close();
		window.top.art.dialog({title: lang_edit + '--' + name, id: 'edit', iframe: '?m=TransferDoctor&a=edit&id=' + id, width: '600', height: '350'}, function () {
			var d = window.top.art.dialog({id: 'edit'}).data.iframe;
			d.document.getElementById('dosubmit').click();
			return false;
		}, function () {
			window.top.art.dialog({id: 'edit'}).close()
		});
	}

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