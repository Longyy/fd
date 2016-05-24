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
if (!defined('THINK_PATH')) exit();

$config = require("config.inc.php");
$array  = array(
    'URL_MODEL'       => 1,
    //缓存配置
    //   'DATA_CACHE_TYPE' => 'file', // 数据缓存方式 文件
//    'DATA_CACHE_TIME' => 0, // 数据缓存时间
    //   'DATA_CACHE_SUBDIR' => true,
    //   'DATA_PATH_LEVEL' => 2,
//	'URL_PATHINFO_DEPR' =>'-',  //参数之间的分割符号    	
    'DEFAULT_LANG'    => 'zh-cn', // 默认语言
    'SHOW_PAGE_TRACE' => true,
    'APPID'           => 'wx3e395640ed0da430',
    'APPSECRET'       => 'fce7c5574bb5105c7b7bc4774ba70282'
);
return array_merge($config, $array);
?>