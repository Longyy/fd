<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>复旦张江后台管理</title>
<link href="__ROOT__/statics/admin/css/style.css" rel="stylesheet" type="text/css"/>
<link href="__ROOT__/statics/admin/css/login.css" rel="stylesheet" type="text/css"/>
</head>
<body>
<div id="login">
	<form action="<?php echo u('Public/login');?>" method="post" id="myform">
		<input type="hidden" name="ajaxlogin" id="ajaxlogin" />
		<input type="hidden" name="ajaxcode" id="ajaxcode" />
		<dl>
			<dd>管理员姓名：<input class="text" type="text" name="username" id="username" /></dd>
			<dd>管理员密码：<input class="text" type="password" name="password" id="password" /></dd>
			<?php if($set["check_code"] == '1'): ?><dd>验　证　码：<input id="verify" type="text" name="verify">
					<img id="verify_img" src="<?php echo U('Public/verify',array('rand'=>time()));?>" title="看不清？点击刷新" onclick="javascript:this.src='<?php echo U('Public/verify');?>&mt='+Math.random()" />
				</dd><?php endif; ?>
			<dd><input type="submit" class="submit" name="dosubmit" value="进入管理中心" /></dd>			
		</dl>
	</form>
</div>
</body>
</html>