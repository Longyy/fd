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
$(function(){
	$("#add_attatch").click(function(){
	$("#attatch_tr").clone().prependTo($("#attatch_tr").parent());
	});
})
</script>
<form action="<?php echo u('YaoPin/add');?>" method="post" name="myform" id="myform"  enctype="multipart/form-data" style="margin-top:10px;">
  <div class="pad-10">
    <div class="col-tab">
      <ul class="tabBut cu-li">
        <li id="tab_setting_1" class="on" onclick="SwapTab('setting','on','',2,1);"><?php echo (L("general_info")); ?></li>       
      </ul>
      <div id="div_setting_1" class="contentList pad-10">
        <table width="100%" cellpadding="2" cellspacing="1" class="table_form">
        <tr>
            <th><?php echo L('cate_id');?>:</th>
            <td><select name="cate_id" id="cate_id" style="width:200px;">
            	<option value="0">--请选择分类--</option>
                <?php if(is_array($cate_list['parent'])): $i = 0; $__LIST__ = $cate_list['parent'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><option value="<?php echo ($val["id"]); ?>"><?php echo ($val["name"]); ?></option>
                  <?php if(!empty($cate_list['sub'][$val['id']])): if(is_array($cate_list['sub'][$val['id']])): $i = 0; $__LIST__ = $cate_list['sub'][$val['id']];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sval): $mod = ($i % 2 );++$i;?><option value="<?php echo ($sval["id"]); ?>">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo ($sval["name"]); ?></option>
                      <?php if(!empty($cate_list['sub'][$sval['id']])): if(is_array($cate_list['sub'][$sval['id']])): $i = 0; $__LIST__ = $cate_list['sub'][$sval['id']];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ssval): $mod = ($i % 2 );++$i;?><option value="<?php echo ($ssval["id"]); ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo ($ssval["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; endif; endforeach; endif; else: echo "" ;endif; endif; endforeach; endif; else: echo "" ;endif; ?>
              </select></td>
          </tr>		  
          <tr>
            <th width="100"><?php echo L('title');?>:</th>
            <td><input type="text" name="title" id="title" class="input-text" size="60"></td>
          </tr> 
          <tr>
          	<th>电子监管码 :</th>
            <td><input type="text" name="version" id="version" class="input-text" size="30"></td>
          </tr>			  
          <tr>
          	<th>药品图片 :</th>
            <td><input type="file" name="img" id="img" class="input-text"  style="width:200px;" /></td>
          </tr>		    	  
          <tr>
            <th><?php echo L('abst');?> :</th>
            <td><textarea name="abst" id="abst" style="width:68%;height:50px;"></textarea></td>
          </tr>
           <tr>
            <th><?php echo L('info');?> :</th>
            <td>            	
				<script type="text/javascript" src="__ROOT__/statics/js/kindeditor/kindeditor.js"></script><script type="text/javascript" src="__ROOT__/statics/js/includes/kindeditor/lang/zh_CN.js"></script><script> var editor; KindEditor.ready(function(K) { editor = K.create('#info');});</script><textarea id="info" style="width:70%;height:350px;" name="info" ></textarea>
			</td>
          </tr>
          <tr>
            <th><?php echo L('ordid');?> :</th>
            <td><input type="text" name="ordid" id="ordid" class="input-text" size="8"></td>
          </tr>		  	  
         <tr>
            <th><?php echo L('status');?> :</th>
            <td><input type="radio" name="status" class="radio_style" value="1" checked="checked">
              &nbsp;已审核&nbsp;&nbsp;&nbsp;
              <input type="radio" name="status" class="radio_style" value="0">
              &nbsp;待审核
            </td>
          </tr>
        </table>
      </div>
   
      <div class="bk15"></div>
      <div class="btn"><input type="submit" value="<?php echo (L("submit")); ?>" onclick="return submitFrom();" name="dosubmit" class="button" id="dosubmit"></div>
    </div>
  </div>
</form>
<script type="text/javascript">
function SwapTab(name,cls_show,cls_hide,cnt,cur){
    for(i=1;i<=cnt;i++){
		if(i==cur){
			 $('#div_'+name+'_'+i).show();
			 $('#tab_'+name+'_'+i).attr('class',cls_show);
		}else{
			 $('#div_'+name+'_'+i).hide();
			 $('#tab_'+name+'_'+i).attr('class',cls_hide);
		}
	}
}
/**
function submitFrom(){
	if($("#cate_id").val()==0)
	{
	   alert('请选择文章分类');
	   return false;
	}
	if($("#title").val()==''){
		alert('文章标题不能为空');
	    return false;
	}
	if($("#img").val()==''){
		alert('药品图标不能为空');
	    return false;
	}
	if($("#url").val()==''){
		alert('应用地址不能为空');
	    return false;
	}
	

}
**/
</script>
</body></html>