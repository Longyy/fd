<?php
// +----------------------------------------------------------------------
// | APPCMS 移动应用软件后台管理系统
// +----------------------------------------------------------------------
// | provide by ：shiraz-soft.com
// 
// +----------------------------------------------------------------------
// | Author: Shirazsoft APP Team
// +----------------------------------------------------------------------

class AdminModel extends RelationModel
{
	protected $_link=array(
	   'Role'=>array(
	       'mapping_type'  => BELONGS_TO,
	       'class_name'    => 'Role',
	 	   'mapping_name'=>'role',	
           'foreign_key'   => 'role_id',
	   ),
	);
    public function checkUsername($userName,$id='')
    {
        $where = "user_name='$userName'";
        if ($id) {
            $where .= " AND id<>'$id'";
        }
        $id = $this->where($where)->getField('id');
        if ($id) {
        	//存在
            return false;
        } else {
        	//不存在
            return true;
        }
    }
}