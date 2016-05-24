<?php
defined('ROOT_PATH') or define('ROOT_PATH', dirname(__FILE__));


//header('Location: ./admin.php');

//1.确定应用名称 Home
define('APP_NAME', 'wechatTEST');
//2.确定应用路径
define('APP_PATH', './wechatTEST/');

//3.开启调试模式
define('APP_DEBUG', true);
//4.应用核心文件
require(ROOT_PATH . '/core/core.php');

?>
