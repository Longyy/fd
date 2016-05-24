<?php
// +----------------------------------------------------------------------
// | Shirazsoft APPCMS
// +----------------------------------------------------------------------
// | Copyright (c) 2012-2014 http://shiraz-soft.com All rights reserved.
// +----------------------------------------------------------------------
// | 未经许可，不得随意复制传播，否则追究责任
// +----------------------------------------------------------------------
// | Author: Shirazsoft APP Team
// +----------------------------------------------------------------------
// $Id: Cookie.class.php 2702 2012-02-02 12:35:01Z liu21st $

/**
 +------------------------------------------------------------------------------
 * Cookie管理类
 +------------------------------------------------------------------------------
 * @category   Think
 * @package  Think
 * @subpackage  Util
 * @author    Shirazsoft APP Team
 * @version   $Id: Cookie.class.php 2702 2012-02-02 12:35:01Z liu21st $
 +------------------------------------------------------------------------------
 */
class Cookie {
    // 判断Cookie是否存在
    static function is_set($name) {
        return isset($_COOKIE[C('COOKIE_PREFIX').$name]);
    }

    // 获取某个Cookie值
    static function get($name) {
        $value   = $_COOKIE[C('COOKIE_PREFIX').$name];
        $value   =  unserialize(base64_decode($value));
        return $value;
    }

    // 设置某个Cookie值
    static function set($name,$value,$expire='',$path='',$domain='') {
        if($expire=='') {
            $expire =   C('COOKIE_EXPIRE');
        }
        if(empty($path)) {
            $path = C('COOKIE_PATH');
        }
        if(empty($domain)) {
            $domain =   C('COOKIE_DOMAIN');
        }
        $expire =   !empty($expire)?    time()+$expire   :  0;
        $value   =  base64_encode(serialize($value));
        setcookie(C('COOKIE_PREFIX').$name, $value,$expire,$path,$domain);
        $_COOKIE[C('COOKIE_PREFIX').$name]  =   $value;
    }

    // 删除某个Cookie值
    static function delete($name) {
        Cookie::set($name,'',-3600);
        unset($_COOKIE[C('COOKIE_PREFIX').$name]);
    }

    // 清空Cookie值
    static function clear() {
        unset($_COOKIE);
    }
}