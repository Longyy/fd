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
class BaseAction extends Action {
	public $setting;
	public function _initialize() {
		$setting_mod=D('setting');
		$setting_rel=$setting_mod->select();		
		foreach ($setting_rel as $val) {
				$this->setting[$val['name']] = $val['data'];
		}	  
				
	}
	
}

?>