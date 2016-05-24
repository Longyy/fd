<?php
// +----------------------------------------------------------------------
// | Shirazsoft APPCMS
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://shiraz-soft.com All rights reserved.
// +----------------------------------------------------------------------
// | 未经许可，不得随意复制传播，否则追究责任
// +----------------------------------------------------------------------
// | Author: Shirazsoft APP Team
// +----------------------------------------------------------------------

defined('THINK_PATH') or exit();
/**
 * 机器人检测
 * @category   Extend
 * @package  Extend
 * @subpackage  Behavior
 * @author   Shirazsoft APP Team
 */
class RobotCheckBehavior extends Behavior {
    protected $options   =  array(
            'LIMIT_ROBOT_VISIT' =>  true, // 禁止机器人访问
        );
    public function run(&$params) {
        // 机器人访问检测
        if(C('LIMIT_ROBOT_VISIT') && self::isRobot()) {
            // 禁止机器人访问
            exit('Access Denied');
        }
    }

    static private function isRobot() {
        static $_robot = null;
        if(is_null($_robot)) {
            $spiders = 'Bot|Crawl|Spider|slurp|sohu-search|lycos|robozilla';
            $browsers = 'MSIE|Netscape|Opera|Konqueror|Mozilla';
            if(preg_match("/($browsers)/", $_SERVER['HTTP_USER_AGENT'])) {
                $_robot	 =	  false ;
            } elseif(preg_match("/($spiders)/", $_SERVER['HTTP_USER_AGENT'])) {
                $_robot	 =	  true;
            } else {
                $_robot	 =	  false;
            }
        }
        return $_robot;
    }
}