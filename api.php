<?php
defined('ROOT_PATH') or define('ROOT_PATH', dirname(__FILE__));
if (!is_file(ROOT_PATH . '/data/install.lock')) {
    header('Location: ./install.php');
    exit;
}

//header('Location: ./admin.php');

//1.确定应用名称 Home
define('APP_NAME', 'api');
//2.确定应用路径
define('APP_PATH', './api/');
/* 数据目录*/
define('DATA_PATH', './api/');
/* HTML静态文件目录*/
define('HTML_PATH', DATA_PATH . 'html/');
//3.开启调试模式
define('APP_DEBUG', true);
//4.应用核心文件
require(ROOT_PATH . '/core/core.php');

?>
