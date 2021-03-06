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

/**
 * Crypt 加密实现类
 * @category   ORG
 * @package  ORG
 * @subpackage  Crypt
 * @author    Shirazsoft APP Team
 */
class Crypt {

    /**
     * 加密字符串
     * @access static
     * @param string $str 字符串
     * @param string $key 加密key
     * @return string
     */
    function encrypt($str,$key,$toBase64=false){
        $r = md5($key);
        $c=0;
        $v = "";
		$len = strlen($str);
		$l = strlen($r);
        for ($i=0;$i<$len;$i++){
         if ($c== $l) $c=0;
         $v.= substr($r,$c,1) .
             (substr($str,$i,1) ^ substr($r,$c,1));
         $c++;
        }
        if($toBase64) {
            return base64_encode(self::ed($v,$key));
        }else {
            return self::ed($v,$key);
        }

    }

    /**
     * 解密字符串
     * @access static
     * @param string $str 字符串
     * @param string $key 加密key
     * @return string
     */
    function decrypt($str,$key,$toBase64=false) {
        if($toBase64) {
            $str = self::ed(base64_decode($str),$key);
        }else {
            $str = self::ed($str,$key);
        }
        $v = "";
		$len = strlen($str);
        for ($i=0;$i<$len;$i++){
         $md5 = substr($str,$i,1);
         $i++;
         $v.= (substr($str,$i,1) ^ $md5);
        }
        return $v;
    }


   function ed($str,$key) {
      $r = md5($key);
      $c=0;
      $v = "";
	  $len = strlen($str);
	  $l = strlen($r);
      for ($i=0;$i<$len;$i++) {
         if ($c==$l) $c=0;
         $v.= substr($str,$i,1) ^ substr($r,$c,1);
         $c++;
      }
      return $v;
   }
}