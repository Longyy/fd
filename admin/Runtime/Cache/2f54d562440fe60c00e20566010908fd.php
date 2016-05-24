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

<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=fs0k1wtH2zNYqEjwKSfllKpG"></script>
<div class="pad_10">
	<form action="<?php echo u('MedicalCenter/edit');?>" method="post" name="myform" id="myform">
		<table width="100%" cellpadding="2" cellspacing="1" class="table_form">
			<tr>
				<th width="60">名称 :</th>
				<td><input type="text" name="name" id="name" class="input-text" value="<?php echo ($medicalCenter["name"]); ?>" size="25"></td>
			</tr>

			<tr>
				<td>地址:</td>
				<td><textarea name="address" id="address" cols="40" rows="3"><?php echo ($medicalCenter["address"]); ?></textarea></td>
				<td><input type="button" style="height: 50px;cursor:pointer; width: 100px;" onclick="search()" value="搜索地理位置"></td>
			</tr>
			<tr>
				<td colspan="2" style="padding-left: 60px;">
					<div id="allmap" style="height: 200px;width: 300px; border:1px solid gray"></div>
				</td>

			</tr>
			<tr>
				<td>
					经纬度
				</td>
				<td>
					<input value="<?php echo ($medicalCenter["longitude"]); ?>,<?php echo ($medicalCenter["latitude"]); ?>"  readonly type="text" id="txtLongAndLati" name="LongAndLati">
					<input  type="hidden" name="longitude" id="longitude" class="input-text" value="<?php echo ($medicalCenter["longitude"]); ?>" size="25" >
					<input  type="hidden" name="latitude" id="latitude" value="<?php echo ($medicalCenter["latitude"]); ?>" >
				</td>
				<td></td>
			</tr>
			<tr>
				<th width="70">医生总数量 :</th>
				<td><input type="text" name="doctor_count" id="doctor_count" class="input-text"  onkeyup="value=value.replace(/[^\d]/g,'')"onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))" value="<?php echo ($medicalCenter["doctor_count"]); ?>" size="25"></td>
			</tr>
			<tr>
				<th>成立时间:</th>
				<td>
					<link rel="stylesheet" type="text/css" href="__ROOT__/statics/js/calendar/calendar-blue.css"/>
			<script type="text/javascript" src="__ROOT__/statics/js/calendar/calendar.js"></script>
		<input class="date input-text" type="text" name="setup_date" id="setup_date" size="18" value="" />	
					<script language="javascript" type="text/javascript">
	                    Calendar.setup({
	                        inputField     :    "setup_date",
	                        ifFormat       :    "%Y-%m-%d %H:%M:00",
	                        showsTime      :    "true",
	                        timeFormat     :    "24"
	                    });
	     </script>
				</td>
			</tr>
			<tr>
				<th>联系电话:</th>
				<td>
					<input type="text" name="phone" id="phone" class="input-text" value="<?php echo ($medicalCenter["phone"]); ?>" size="25">
				</td>
			</tr>
			<tr>
				<th>省份:</th>
				<td>
					<select name="province_id" id="province_id" style="width:200px;">
						<option value="">--请选择--</option>
						<?php if(is_array($provinceData)): $i = 0; $__LIST__ = $provinceData;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><option value="<?php echo ($val["provinceID"]); ?>" <?php if(($val['provinceID']) == $medicalCenter['province_id']): ?>selected="selected"<?php endif; ?>><?php echo ($val["province"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
					</select>
				</td>
			</tr>
            <tr>
                <th>是否推荐（当GPS匹配不到时使用） :</th>
                <td>
                    <select id="is_recommend" name="is_recommend">
                        <option value="0"<?php if(($medicalCenter['is_recommend']) == "0"): ?>selected="selected"<?php endif; ?>>否</option>
                        <option value="1"<?php if(($medicalCenter['is_recommend']) == "1"): ?>selected="selected"<?php endif; ?>>是</option>
                    </select>
                </td>
            </tr>
			<tr>
				<th>简介信息:</th>
				<td>
					<script type="text/javascript" src="__ROOT__/statics/js/kindeditor/kindeditor.js"></script><script type="text/javascript" src="__ROOT__/statics/js/includes/kindeditor/lang/zh_CN.js"></script><script> var editor; KindEditor.ready(function(K) { editor = K.create('#info');});</script><textarea id="info" style="width:200%;height:350px;" name="info" ><?php echo ($medicalCenter["info"]); ?></textarea>
				</td>
			</tr>
		</table>
		<input type="hidden" name="id" value="<?php echo ($medicalCenter["id"]); ?>" />
		<input type="submit" name="dosubmit" id="dosubmit" class="dialog" value=" ">
	</form>
	<script type="text/javascript">
		$(function(){
			$.formValidator.initConfig({formid:"myform",autotip:true,onerror:function(msg,obj){window.top.art.dialog({content:msg,lock:true,width:'250',height:'50'}, function(){this.close();$(obj).focus();})}});

			$("#name").formValidator({onshow:"不能为空",onfocus:"不能为空"}).inputValidator({min:1,onerror:"请填写名称"});
			$("#address").formValidator({onshow:"不能为空",onfocus:"不能为空"}).inputValidator({min:1,onerror:"请填写地址"});
			$("#longitude").formValidator({onshow:"不能为空",onfocus:"不能为空"}).inputValidator({min:1,onerror:"请填写经度"});
			$("#doctor_count").formValidator({onshow:"不能为空",onfocus:"不能为空"}).inputValidator({min:1,onerror:"请填写医生总数量"});
		})


//		Calendar.setup({
//			inputField     :    "setup_date",
//			ifFormat       :    "Y-m-d",
//			showsTime      :    "true",
//			timeFormat     :    "24",
//			date:'2011-11-11 11:11:11'
//		});

		// 百度地图API功能
		var map = new BMap.Map("allmap");
		map.enableScrollWheelZoom();   //启用滚轮放大缩小，默认禁用
		map.enableContinuousZoom();    //启用地图惯性拖拽，默认禁用
		var point = new BMap.Point(<?php echo ($lonAndLat); ?>);
		map.centerAndZoom(point,15);
		map.enableScrollWheelZoom();
		//	map.centerAndZoom("上海", 15);
		var data_info = <?php echo ($info); ?>;
		var opts = {
			width : 250,     // 信息窗口宽度
			height: 80,     // 信息窗口高度
			title : "信息窗口" , // 信息窗口标题
			enableMessage:true//设置允许信息窗发送短息
		};
		for(var i=0;i<data_info.length;i++){
			var marker = new BMap.Marker(new BMap.Point(data_info[i][0],data_info[i][1]));  // 创建标注
			var content = data_info[i][2];
			map.addOverlay(marker);               // 将标注添加到地图中
			addClickHandler(content,marker);
		}
		function addClickHandler(content,marker){
//		marker.addEventListener("onmouseover",function(e){
//					openInfo(content,e)}
//		);
			marker.addEventListener("click",function(e){
						openInfo(content,e)}
			);
		}
		function openInfo(content,e){
			var p = e.target;
			var point = new BMap.Point(p.getPosition().lng, p.getPosition().lat);
			var infoWindow = new BMap.InfoWindow(content,opts);  // 创建信息窗口对象
			map.openInfoWindow(infoWindow,point); //开启信息窗口
		}

//		var map = new BMap.Map("allmap");
//		map.centerAndZoom("上海", 15);

		var localSearch = new BMap.LocalSearch(map);
		localSearch.enableAutoViewport(); //允许自动调节窗体大小
		function search(){
			var map = new BMap.Map("allmap");
　　            map.clearOverlays();//清空原来的标注
			localSearch.setSearchCompleteCallback(function (searchResult) {
				var poi = searchResult.getPoi(0);
				$("#txtLongAndLati").val(poi.point.lng + "," + poi.point.lat);
				$("#longitude").val(poi.point.lng);
				$("#latitude").val(poi.point.lat);
				map.centerAndZoom(poi.point, 13);
				var marker = new BMap.Marker(new BMap.Point(poi.point.lng, poi.point.lat));  // 创建标注，为要查询的地址对应的经纬度
				map.addOverlay(marker);
			});
			localSearch.search($("#address").val());
		}
	</script>
</div>
</body>
</html>