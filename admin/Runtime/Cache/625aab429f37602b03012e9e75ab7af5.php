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
<div class="pad-lr-10">
    <form id="myform" name="myform" action="<?php echo u('Group/delete');?>" method="post" onsubmit="return check();">
    <div class="table-list">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
                <th width=60>ID</th>
                <th width=30><input type="checkbox" value="" id="check_box" onclick="selectall('id[]');"></th>               
                <th width=100>菜单分类名称</th>
                <th>分类标识</th>     
				<th width=60>排序</th> 			
                <th width=60>状态</th>				
                <th width=60>操作</th>
            </tr>
        </thead>
    	<tbody>
        <?php if(is_array($group_list)): $i = 0; $__LIST__ = $group_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><tr>
        	 <td align="center"><?php echo ($val["id"]); ?></td>
            <td align="center"><input type="checkbox" value="<?php echo ($val["id"]); ?>" name="id[]" <?php if($val['user_name'] == 'group'): ?>disabled="disabled"<?php endif; ?>></td>           
            <td align="center"><?php echo ($val["title"]); ?></td>
            <td align="center"><?php echo ($val["name"]); ?></td>     		
			<td align="center"><input type="text" class="input-text-c input-text" onblur="sort(<?php echo ($val["id"]); ?>,'sort',this.value)" id="sort_<?php echo ($val["id"]); ?>" value="<?php echo ($val["sort"]); ?>" size="4" name="listorders[<?php echo ($val["id"]); ?>]"></td>
            <td align="center" onclick="status(<?php echo ($val["id"]); ?>,'status')" id="status_<?php echo ($val["id"]); ?>"><img src="__ROOT__/statics/images/status_<?php echo ($val["status"]); ?>.gif" /></td>
            <td align="center"><a href="javascript:edit(<?php echo ($val["id"]); ?>,'<?php echo ($val["title"]); ?>')">编辑</a></td>
        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
    	</tbody>
    </table>
    <div class="btn">
		<label for="check_box" style="float:left;"><?php echo (L("select_all")); ?>/<?php echo (L("cancel")); ?></label>
		<input type="submit" class="button" name="dosubmit" value="<?php echo (L("delete")); ?>" onclick="return confirm('<?php echo (L("sure_delete")); ?>')" style="float:left;margin-left:10px;"/>
		<div id="pages"><?php echo ($page); ?></div>
    </div>
    </div>
    </form>
</div>
<script language="javascript">
function check(){
	var ids='';
	$("input[name='id[]']:checked").each(function(i, n){
		ids += $(n).val() + ',';
	});
	if(ids=='') {
		window.top.art.dialog({content:lang_please_select+'菜单分类名称	',lock:true,width:'200',height:'50',time:1.5},function(){});
		return false;
	}
	return true;
}
function edit(id, name) {
	var lang_edit = "<?php echo (L("edit")); ?>";
	window.top.art.dialog({id:'edit'}).close();
	window.top.art.dialog({
		title:lang_edit+'--'+name,
		id:'edit',
		iframe:'?m=Group&a=edit&id='+id,width:'480',height:'170'
		}, 
		function()
		{
			var d = window.top.art.dialog({id:'edit'}).data.iframe;
			d.document.getElementById('dosubmit').click();
			return false;
		}, 
		function()
		{
			window.top.art.dialog({id:'edit'}).close()
		});
}
function status(id,type){
    $.get("<?php echo u('Group/status');?>", { id: id, type: type }, function(jsondata){
		var return_data  = eval("("+jsondata+")");
		$("#"+type+"_"+id+" img").attr('src', '__ROOT__/statics/images/status_'+return_data+'.gif');
	}); 
}
//排序方法
function sort(id,type,num){
    $.get("<?php echo u('Group/sort');?>", { id: id, type: type,num:num }, function(jsondata){
		var return_data  = eval("("+jsondata+")");
		$("#"+type+"_"+id+" ").attr('value', return_data);
	}); 
}
</script>
</body>
</html>