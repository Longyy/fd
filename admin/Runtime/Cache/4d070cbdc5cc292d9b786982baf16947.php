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
<div class="pad_10">
<form action="<?php echo u('Group/edit');?>" method="post" name="myform" id="myform">
<table width="100%" cellpadding="2" cellspacing="1" class="table_form">	
    <tr> 
      <th width="80"><?php echo L('group_title');?> :</th>
      <td><input type="text" name="title" id="title" class="input-text" value="<?php echo ($group_info["title"]); ?>"></td>
    </tr>
    <tr> 
      <th><?php echo L('group_name');?> :</th>
      <td><input type="text" name="name" id="name" class="input-text" value="<?php echo ($group_info["name"]); ?>"></td>
    </tr>
    <tr>
      <th><?php echo L('sort');?>  :</th>
      <td><input type="text" name="sort" id="sort" class="input-text" value="<?php echo ($group_info["sort"]); ?>"></td>
    </tr>
	<tr>
		<th><?php echo L('state');?> :</th>
		<td>
	  		<input type="radio" name="status" class="radio_style" value="1" <?php if($group_info["status"] == 1): ?>checked<?php endif; ?>> &nbsp;有效&nbsp;&nbsp;&nbsp;
	  	  <input type="radio" name="status" class="radio_style" value="0" <?php if($group_info["status"] == 0): ?>checked<?php endif; ?>> &nbsp;禁用
		</td>
    </tr>
</table>
<input type="hidden" name="id" value="<?php echo ($group_info["id"]); ?>" />
<input type="submit" name="dosubmit" id="dosubmit" class="dialog" value=" ">
</form>
<script type="text/javascript">
var group_id = "<?php echo ($group_info["id"]); ?>";
var group_name="<?php echo ($group_info["user_name"]); ?>";
$(function(){
	$.formValidator.initConfig({formid:"myform",
		autotip:true,
		onerror:function(msg,obj){
			window.top.art.dialog({content:msg,lock:true,width:'200',height:'50'}, 
								  function(){
									  this.close();
									  $(obj).focus();
									})
		}
	});	
	$("#title").formValidator({
		onshow:"不能为空",onfocus:"不能为空"
	}).inputValidator({
		min:1,onerror:"请填写菜单分类名称"
	});
})
</script>
</div>
</body>