<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport"
	      content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no"/>
	<script language="javascript" type="text/javascript" src="__ROOT__/statics/js/jquery/jquery-1.11.0.min.js"></script>
	<script language="javascript" type="text/javascript" src="__ROOT__/statics/js/bootstrap.min.js"></script>
	<link href="__ROOT__/statics/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
	<title><?php echo ($title); ?></title>
</head>
<body style=" margin: 20px;">
<div class="" style="text-align: center;">
	<a href="<?php echo get_wechat_url('http://www.taoappcode.com/test/diandubi-wechat/ddb/wechat.php?m=RankingList');?>">
		<button type="button" style="width: 110px;" class="btn btn-info">昨日</button>
	</a>
	<a href="<?php echo get_wechat_url('http://www.taoappcode.com/test/diandubi-wechat/ddb/wechat.php?m=RankingList&a=week');?>">	<button type="button" style="width: 100px; margin-left: -15px;" class="btn btn-default">一周</button>
	</a>
</div>
<br/>
<div style="text-align: center">
	使用时间<?php echo ($user_time); ?>分钟<br/><br/>
	打到<?php echo ($fans_perc); ?>%
</div>
<table style="width: 100%">
	<?php if(is_array($rank_list_result)): $i = 0; $__LIST__ = $rank_list_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$data): $mod = ($i % 2 );++$i; $width=round(($data['times']/$max_min)*100); ?>
		<tr>
			<td rowspan="2" style="width:70px;">
				<?php echo ($i); ?>
				<img style="width: 50px;height: 50px;" src="<?php echo ($data['head_img_url']); ?>">
			</td>
			<td>
				<?php echo ($data['nick_name']); ?>
			</td>
		</tr>
		<tr>
			<td>
				<div class="progress">
					<div class="progress-bar" role="progressbar" aria-valuenow="<?php echo ($data['times']); ?>" aria-valuemin="<?php echo ($min_min); ?>" aria-valuemax="<?php echo ($max_min); ?>" style="width:<?php echo ($width); ?>%;">
						<?php echo ($width); ?>%
					</div>
				</div>
			</td>
		</tr><?php endforeach; endif; else: echo "" ;endif; ?>



</table>


</body>


<script>

</script>