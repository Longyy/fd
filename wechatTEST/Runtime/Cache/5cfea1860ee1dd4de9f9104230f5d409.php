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
<script language="javascript" type="text/javascript" src="__ROOT__/statics/js/echarts-all.js"></script>

<body style=" margin: 20px;">
<div class="" style="text-align: center;">
	<a href="<?php echo get_wechat_url('http://www.taoappcode.com/test/diandubi-wechat/ddb/wechat.php?m=RankingList');?>">
		<button type="button" style="width: 110px;" class="btn btn-default">昨日</button>
	</a>
	<a href="<?php echo get_wechat_url('http://www.taoappcode.com/test/diandubi-wechat/ddb/wechat.php?m=RankingList&a=week');?>">	<button type="button" style="width: 100px; margin-left: -15px;" class="btn btn-info">一周</button>
	</a>
</div>
<div id="main" style="height:300px;width:500px;margin-left:-50px; text-align:center">loading....</div>

<table class="table">
	<?php if(is_array($list_data)): $i = 0; $__LIST__ = $list_data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$data): $mod = ($i % 2 );++$i;?><tr>
		<td><?php echo ($data[0]); ?></td>
		<td style="text-align: right"><?php echo ($data[1]); ?></td>
	</tr><?php endforeach; endif; else: echo "" ;endif; ?>
</table>
</body>

	<script type="text/javascript">

	var myChart = echarts.init(document.getElementById('main'));
	myChart.setOption({
		title : {
			text: '',
			subtext: '             单位分钟'
		},
		tooltip : {
			trigger: 'axis'
		},
		legend: {
			data:['阅读时间报表展示']
		},
		calculable : true,
		xAxis : [
			{
				type : 'category',
				boundaryGap : false,
				data : <?php echo ($x_date); ?>
			}
		],
		yAxis : [
			{
				type : 'value'
			}
		],
		series : [
			{
				name:'阅读时间报表展示',
				type:'line',
				stack: '总量',
				data:<?php echo ($min_data); ?>
			}
		]
	});


</script>