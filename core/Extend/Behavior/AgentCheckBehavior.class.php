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
 * 行为扩展：代理检测
 * @category   Extend
 * @package  Extend
 * @subpackage  Behavior
 * @author   Shirazsoft APP Team
 */
class AgentCheckBehavior extends Behavior {
    protected $options   =  array(
            'LIMIT_PROXY_VISIT'=>true,
        );
    public function run(&$params) {
        // 代理访问检测
        if(C('LIMIT_PROXY_VISIT') && ($_SERVER['HTTP_X_FORWARDED_FOR'] || $_SERVER['HTTP_VIA'] || $_SERVER['HTTP_PROXY_CONNECTION'] || $_SERVER['HTTP_USER_AGENT_VIA'])) {
            // 禁止代理访问
            exit('Access Denied');
        }
    }
}
?>