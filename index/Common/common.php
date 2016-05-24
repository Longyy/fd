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
function get_cate_list($model='article_cate',$id=0,$level=0){
	$cate_mod=D($model);	
	//获取一级分类
	$res=$cate_mod->field('id,name,pid,alias')->where("pid={$id} AND status=1 ")			
			->order("sort_order ASC")->select();  
	foreach($res as $key=>$val){
		$val['level']=$level;		
		//二级分类
		 //选出不热门推荐的分类	
		$arr=$cate_mod->field('id,name,pid,alias')->where("pid='{$val['id']}' AND status=1 ")
			->order("sort_order ASC")->select();	
		
		//三级分类
		foreach($arr as $k2=>$v2){
			$v2['level']=$level+1;
			$v2['cls']="sub_".$val['id'];			
			//判断是否选出所有分类			
			$arr[$k2]['items']=$cate_mod->field('id,name,pid,alias')->where('pid='.$v2['id'])
			->order("sort_order ASC")->select();	
			
		}
		$res[$key]['items']=$arr;
	}
	return array('results'=>$res);
}




?>