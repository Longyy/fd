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
	<form name="searchform" action="" method="get" >
		<table width="100%" cellspacing="0" class="search-form">
			<tbody>
			<tr>
				<td>

						电子监管码 :
						<input name="keyword" type="text" class="input-text" size="25" value="<?php echo ($code); ?>" />





						状态 :
						<select name="status_id">
							<option value="-1" <?php if($status_id == -1): ?>selected="selected"<?php endif; ?>>　--请选择--</option>
							<option value="0" <?php if($status_id == 0): ?>selected="selected"<?php endif; ?>>　出货</option>
							<option value="1" <?php if($status_id == 1): ?>selected="selected"<?php endif; ?>>　使用</option>
						</select>
						<input type="hidden" name="m" value="Barcode" />
						<input type="submit" name="search" class="button" value="搜索" />

				</td>
			</tr>
			</tbody>
		</table>
	</form>
	<form id="myform" name="myform"  method="post" onsubmit="return check();">
		<div class="table-list">
			<table width="100%" cellspacing="0">
				<thead>
				<tr>
					<th width="50">ID</th>
					<th width="50">药品名称</th>
					<th width="50">电子监管码</th>
					<th width="50">扫描医生</th>
					<th width="50">扫码时间</th>
					<th width="50">状态</th>
					<th width="50">操作</th>
				</tr>
				</thead>
				<tbody>
				<?php if(is_array($barcode_list)): $i = 0; $__LIST__ = $barcode_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><tr>
						<td align="center"><?php echo ($val["id"]); ?></td>
						<td align="center"><?php echo ($val["name"]); ?></td>
						<td align="center"><?php echo ($val["code"]); ?></td>
						<td align="center"><?php echo ($doctorName['name']); ?></td>
						<td align="center"><?php echo date('Y-m-d H:i:s',$val['scan_time']);?></td>
						<td align="center"><?php echo $val['status_id']==0?'出货':'使用';?></td>
						<td align="center"></td>
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





</script>
</body>
</html>