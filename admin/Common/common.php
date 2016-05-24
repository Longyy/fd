<?php
/*
// | APPCMS 移动应用软件后台管理系统
// +----------------------------------------------------------------------
// | provide by ：shiraz-soft.com
// 
// +----------------------------------------------------------------------
// | Author: Shirazsoft APP Team
// +----------------------------------------------------------------------
*/
//清除所有缓存新方法
function deleteCacheData($dir){
		$fileArr	=	file_list($dir);		
	 	foreach($fileArr as $file)
	 	{
	 		if(strstr($file,"Logs")==false)
	 		{	 			
	 			@unlink($file);	 			
	 		}
	 	} 
	 	
}
//删除商品图片和目录可以是数组或者文件
function file_list($path)
{
 	global $fileList;
 	if ($handle = opendir($path)) 
 	{
 		while (false !== ($file = readdir($handle))) 
 		{
 			if ($file != "." && $file != "..") 
 			{
 				if (is_dir($path."/".$file)) 
 				{ 					
 						
 					file_list($path."/".$file);
 				} 
 				else 
 				{
 						//echo $path."/".$file."<br>";
 					$fileList[]	=	$path."/".$file;
 				}
 			}
 		}
 	}
 	return $fileList;
}


function url_parse($url){    	
    $rs = preg_match("/^(http:\/\/|https:\/\/)/", $url, $match);
	if (intval($rs)==0) {
		$url = "http://".$url;
	}		
	return $url;
}

//转换时间
function gmt_time()
{	
	return date('YmdHis');
}
//如果不是二维数组返回true
function is_two_array($array){
	  return count($array)==count($array, 1);
}


/*关键词替换*/

function replace_keywords($content)
{
	if (empty($content) )
	{
		return($content);
	}
	//获取屏蔽词语
	if(file_exists('./data/word.txt')){
		$str=file_get_contents('./data/word.txt');
		$arrKeywords=explode(',', $str);
		$array_keywords=array();
		foreach ($arrKeywords as $key=>$value){
			$array_keywords[]=explode('|', $value);
		}			
		foreach($array_keywords as $arr)//遍历关键字
		{
			if (strpos($content, $arr[0]) > -1 )
			{
				$content = preg_replace("/" . $arr[0] . "/i", $arr[1], $content);
				$arrTemp[] = $arr;				
			}
		}
		return $content;
	}
	else{
		return $content;
	}
	
}
//表单过滤函数 防止sql注入
function addslashes_set($_string) {
		if (!get_magic_quotes_gpc()) {
			if (is_array($_string)) {
				foreach ($_string as $_key=>$_value) {
					$_string[$_key] = addslashes_set($_value);	//迭代调用
				}
			} else {
				return addslashes($_string); //mysql_real_escape_string($_string, $_link);不支持就用代替addslashes();
			}
		}
		return $_string;
}	
//对象表单选项转换
function set_obj_form_item($_data, $_key, $_value) {
	$_items = array();
	if (is_array($_data)) {
		foreach ($_data as $_v) {
			$_items[$_v->$_key] = $_v->$_value;
		}
	}
	return $_items;
}
//数组表单转换
function set_array_form_item($_data, $_key, $_value) {
		$_items = array();
		if (is_array($_data)) {
			foreach ($_data as $_v) {
				$_items[$_v[$_key]] = $_v[$_value];
			}
		}
	return $_items;
}

//屏蔽ip
function banip($value1,$value2){
	$ban_range_low=ip2long($value1);
	$ban_range_up=ip2long($value2);
	$ip=ip2long($_SERVER["REMOTE_ADDR"]);			
	if ($ip>=$ban_range_low && $ip<=$ban_range_up)
	{
		echo "对不起,您的IP在被禁止的IP段之中，禁止访问！";
		exit();
	}
}
function get_banip(){
	if(file_exists('./data/banip_config_inc.php')){
		$banip=@file_get_contents('./data/banip_config_inc.php');
		$banip=unserialize($banip);
		return $banip;
	}
	else{
		return false;
	}
}
//把对象数组转换为关联数组的方法
function get_object_vars_final($obj){
	if(is_object($obj)){
		$obj=get_object_vars($obj);
	}
	if(is_array($obj)){
		foreach ($obj as $key=>$value){
			$obj[$key]=get_object_vars_final($value);
		}
	}
	return $obj;
}
function curl($url, $postFields = null)
{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FAILONERROR, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		if (is_array($postFields) && 0 < count($postFields))
		{
			$postBodyString = "";
			foreach ($postFields as $k => $v)
			{
				$postBodyString .= "$k=" . urlencode($v) . "&"; 
			}
			unset($k, $v);
			curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);  
 			curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0); 
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, substr($postBodyString,0,-1));
		}
		$reponse = curl_exec($ch);
		curl_close($ch);
		return $reponse;
}
//根据url获取id的方法
function get_id($url) {
	$id = 0;
	$parse = parse_url($url);
	if (isset($parse['query'])) {
		parse_str($parse['query'], $params);
		if (isset($params['id'])) {
			$id = $params['id'];
		} elseif (isset($params['item_id'])) {
			$id = $params['item_id'];
		} elseif (isset($params['default_item_id'])) {
			$id = $params['default_item_id'];
		}elseif(isset($params['mallstItemId'])){
			$id = $params['mallstItemId'];
		}else if(isset($params['num_iid '])){
			$id = $params['num_iid'];
		}
	}
	return $id;
}

function addslashes_deep($value) {
    $value = is_array($value) ? array_map('addslashes_deep', $value) : addslashes($value);
    return $value;
}
function miao_client($appkey,$appsecret)
{
	define('API_CACHETIME','0');  //缓存时间默认为小时   0表示不缓存
	define('API_CACHEPATH','Runtime/Api59miao_cache'); //缓存目录
	define('CHARSET','UTF-8');  //编码
	define('APIURL','http://api.59miao.com/Router/Rest?');  //请求地址		
	define('API_CLEARCACHE','1 23 * *');   //自动清除缓存时间
	vendor('api59miao.init');				
	//引入59秒api文件	
	$AppKeySecret=Api59miao_Toos::GetAppkeySecret($appkey,$appsecret);   //获取appkey appsecret
	$_api59miao=new Api59miao($AppKeySecret);			
	return $_api59miao;
}



function get_cate_list($model='article_cate',$id=0,$level=0){
	$cate_mod=D($model);
	$list=array();	
	//获取一级分类
	$res=$cate_mod->where("pid={$id} AND status=1 ")			
			->order("sort_order ASC")->select();  
	foreach($res as $key=>$val){
		$val['level']=$level;
		$list[$val['id']]=$val;
		//二级分类
		 //选出不热门推荐的分类	
		$arr=$cate_mod->where("pid='{$val['id']}' AND status=1 ")
			->order("sort_order ASC")->select();	
		
		//三级分类
		foreach($arr as $k2=>$v2){
			$v2['level']=$level+1;
			$v2['cls']="sub_".$val['id'];
			$list[$v2['id']]=$v2;
			//判断是否选出所有分类			
			$res3=$arr[$k2]['items']=$cate_mod->where('pid='.$v2['id'])
			->order("sort_order ASC")->select();		
			foreach($res3 as $k3=>$v3){
				$v3['level']=$level+2;
				$v3['cls']="sub_".$val['id']." sub_".$v2['id'];
				$list[$v3['id']]=$v3;
			}
		}
		$res[$key]['items']=$arr;
	}
	return array('list'=>$res,'sort_list'=>$list);
}

function uploadImg(&$msg,&$name){
    if (!empty($_FILES["img"]["name"])) { //提取文件域内容名称，并判断
        $path = "upload/"; //上传路径
        if (!file_exists($path)) {
            //检查是否有该文件夹，如果没有就创建，并给予最高权限
            mkdir("$path", 0700);
        }
        //END IF
        //允许上传的文件格式
        $tp = array("image/gif", "image/pjpeg", "image/jpeg","image/png");
        //检查上传文件是否在允许上传的类型
        if (!in_array($_FILES["img"]["type"], $tp)) {
            $msg="图片格式不正确";
            return false;
        }
        //END IF
        $filetype = $_FILES['img']['type'];
        if ($filetype == "image/jpeg") {
            $type = ".jpg";
        }
        if ($filetype == "image/jpg") {
            $type = ".jpg";
        }
        if ($filetype == "image/pjpeg") {
            $type = ".jpg";
        }
        if ($filetype == "image/gif") {
            $type = ".gif";
        }
        if ($_FILES["img"]["name"]) {
            $today = date("YmdHis"); //获取时间并赋值给变量
            $file2 = $path . $today . $type; //图片的完整路径
            $name   = $today . $type; //图片名称
            $flag  = 1;
        }
        //END IF
        if ($flag) $result = move_uploaded_file($_FILES["img"]["tmp_name"], $file2);
        if(!$result) $msg="上传图片失败";
        //特别注意这里传递给move_uploaded_file的第一个参数为上传到服务器上的临时文件
        return true;
    }
}

function uploadTxt(&$msg,$uploadName,&$name){
    if (!empty($_FILES[$uploadName]["name"])) { //提取文件域内容名称，并判断
        $path = "upload/barcode/"; //上传路径
        if (!file_exists($path)) {
            //检查是否有该文件夹，如果没有就创建，并给予最高权限
            mkdir("$path", 0700);
        }
        //END IF
        //允许上传的文件格式
        $tp = array("text/plain");
        //检查上传文件是否在允许上传的类型
//        if (!in_array($_FILES[$uploadName]["type"], $tp)) {
//            $msg="上传格式不正确";
//            return false;
//        }

        if ($_FILES[$uploadName]["name"]) {
            $today = date("YmdHis"); //获取时间并赋值给变量
            $file2 = $path . $today . '.txt';
            $name   = $today . '.txt';
            $flag  = 1;
        }
        //END IF
        if ($flag) $result = move_uploaded_file($_FILES[$uploadName]["tmp_name"], $file2);
        if(!$result) {$msg="上传失败"; return false;};
        //特别注意这里传递给move_uploaded_file的第一个参数为上传到服务器上的临时文件
        return true;
    }
}







?>