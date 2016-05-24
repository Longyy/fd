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

function write_log($log_content){
    if(isset($_SERVER['HTTP_APPNAME'])){   //SAE
        sae_set_display_errors(false);
        sae_debug($log_content);
        sae_set_display_errors(true);
    }else{ //LOCAL
        $max_size = 500000;
        $log_filename = "log.xml";
        if(file_exists($log_filename) and (abs(filesize($log_filename)) > $max_size)){unlink($log_filename);}
        file_put_contents($log_filename, date('Y-m-d H:i:s').$log_content."\r\n", FILE_APPEND);
    }

}

function get_wechat_url($url){
    $param = array
    (
        'appid'         => 'wx3e395640ed0da430',
        'redirect_uri'  => $url,
        'response_type' => 'code',
        'scope'         => 'snsapi_base',
        'state'         => '#wechat_redirect'
    );
    $wxHttps = 'https://open.weixin.qq.com/connect/oauth2/authorize?'.http_build_query($param);
    return $wxHttps;
}


?>