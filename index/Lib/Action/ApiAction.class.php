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
class ApiAction extends BaseAction {
	private $page_size=40;	 //总分页数
	private $update_time='';
	public function index() {	
		$page_size=$this->_get('pageSize');
		$this->update_time=$this->_get('targetTime');  //获取更新的时间
		//update_time		
		if(!empty($page_size)&&is_numeric($page_size)){		
			$this->page_size=$page_size;
		}
		$this->check();	
		$method=$this->_get('method');
		//执行请求的方法
		$this->$method();
		exit();
	}
	//检测请求是否合法
	private function check(){
		$method_array=array('articleCateGet','articleListGet','articleContentGet','appCateGet','appListGet','appContentGet','documentCateGet','videoCateGet','documentListGet','videoListGet','ImageCateGet','imageListGet','TeacherList','TeacherInfo','HotCourseList','CourseContentGet','CourseContentGet','pictureListGet','imagesGet','CourseVideoUrlList','CourseVideoUrlContent','CourseDocumentUrlList','CourseDocumentUrlContent','CourseList');
		//签名方式	
		$method=$this->_get('method');     //method		
		$key='123456';	//双方约定的一个key		
		$sign=$this->_get('sign');  //签名				
		$my_sign=md5(strtolower($method.$key));
		//echo $my_sign;		
		if(!in_array($method, $method_array)){
			exit('请求的方法不存在');
		}
		if($my_sign!=$sign){
			exit('签名不正确');
		}	
		return true;	
	}
	/*
	 * cid=0 获取全部分类 
	 * cid=123  获取123分类下面的二级分类
	 * */
	private function articleCateGet(){
		$cid=$this->_get('cid');
		if(!$cid){
			$cid=0;
		}
		$cate_rel=get_cate_list($model='article_cate',$id=$cid,$level=0);
        $cate_lists=json_encode($cate_rel);
        echo $cate_lists; 
        exit;		
	}	
	/*
	 * cid=0 获取全部分类 
	 * cid=123  获取123分类下面的二级分类
	 * */
	private function appCateGet(){	
		$cid=$this->_get('cid');
		if(!$cid){
			$cid=0;
		}
		$cate_rel=get_cate_list($model='app_cate',$id=$cid,$level=0);
        $cate_lists=json_encode($cate_rel); 
        echo $cate_lists; 
        exit;			
        	
	}
	
	//获取指定分类下面的文章
	private function appListGet(){		
		$cid=$this->_get('cid');
		if(empty($cid)){  //cid为空 去查找code
			$alias=$this->_get('alias');				
			$app_cate_mod = D('app_cate');
			$cate_rel=$app_cate_mod->where("alias='{$alias}'")->find();
			$cid=$cate_rel['cid'];
		}
		$page=$this->_get('p');
		$order=$this->_get('order');
		$page=isset($page)?$page:1;  //当前的页数			
		
		$app_mod=D('app');
		$sql_where="status=1";
		if(!empty($cid)&&is_numeric($cid))
		{
			$sql_where.=" AND cate_id='{$cid}'";	
		}
		//显示update_time之后的数据			
		if(!empty($this->update_time))
		{
			$sql_where.=" AND add_time>'$this->update_time'";	
		}
		
		import("ORG.Util.Page");
		$count = $app_mod->where($sql_where)->count();	
		$totalPage=ceil($count/$this->page_size);
		$p = new Page($count,$this->page_size);	
		if(empty($order)){
			$order='ordid ASC,id DESC';
		}else{
			$order=str_replace('_', ' ', $order);
		}
		$app_list = $app_mod->where($sql_where)
			->limit($p->firstRow . ',' . $p->listRows)
			->order($order)->select();
		
		$results=array();		
		foreach ($app_list as $key=>$val){
			$results[$key]['id']=$val['id'];
			$results[$key]['name']=$val['title'];
			$results[$key]['packagename']=$val['packagename'];
			$results[$key]['version']=$val['version'];
			$results[$key]['size']=$val['size'];
			if(!empty($val['img'])){
				$results[$key]['icon']=$this->setting['site_domain'].'/'.$val['img'];				
			}else{
				$results[$key]['icon']='';
			}
			if(!empty($val['url'])){
				$results[$key]['url']=$this->setting['site_domain'].'/'.$val['url'];	
			}else{
				$results[$key]['url']='';				
			}			
			$results[$key]['description']=$val['abst'];
			//$results[$key]['content']=$val['info'];			
			$results[$key]['isUpdate']=$val['isupdate'];
		}
		$app_list=array(
			'results'=>$results,
			'page'=>$page,
			'totalPage'=>$totalPage
		);					
		echo (json_encode($app_list));
		exit;
	}
	
	//文章详情调用
	private function appContentGet(){		
		$id=$this->_get('id');
		
		$app_mod=D('app');
		$app_cate_mod=D('app_cate');
				
		$app_rel = $app_mod->where("id='{$id}'")->find();
		
		
		$results['id']=$app_rel['id'];
		$results['name']=$app_rel['title'];
		$results['packagename']=$app_rel['packagename'];
		$results['version']=$app_rel['version'];
		$results['size']=$app_rel['size'];
		if(!empty($app_rel['img'])){
			$results['icon']=$this->setting['site_domain'].'/'.$app_rel['img'];	
		}else{
			$results['icon']='';
		}
		if(!empty($app_rel['url'])){
			$results['url']=$this->setting['site_domain'].'/'.$app_rel['url'];	
		}else{
			$results['url']='';
		}
		
		$results['description']=$app_rel['abst'];
		//$results[$key]['content']=$val['info'];			
		$results['isUpdate']=$app_rel['isupdate'];
		
		echo (json_encode($results));
		exit;
	}
	
	
	
	
	//获取指定分类下面的文章
	private function articleListGet(){		
		$cid=$this->_get('cid');
		
		if(!$cid){  //cid为空 去查找code
			$alias=$this->_get('alias');				
			$article_cate_mod = D('article_cate');
			$cate_rel=$article_cate_mod->where("alias='{$alias}'")->find();			
			$cid=$cate_rel['id'];
		}		
		$page=$this->_get('p');
		$order=$this->_get('order');
		$page=isset($page)?$page:1;  //当前的页数			
		$article_mod=D('article');
		$article_cate_mod=D('article_cate');					
		$sql_where = "1=1 AND status=1";
		if(!empty($cid)&&is_numeric($cid)){
			$sql_where.=" AND cate_id='{$cid}'";
		}
		//显示update_time之后的数据			
		if(!empty($this->update_time))
		{
			$sql_where.=" AND add_time>'$this->update_time'";	
		}
		import("ORG.Util.Page");
		$count = $article_mod->where($sql_where)->count();	
		$totalPage=ceil($count/$this->page_size);
		$p = new Page($count,$this->page_size);	
		if(empty($order)){
			$order='publish_time DESC,id DESC';
		}else{
			$order=str_replace('_', ' ', $order);
		}
		$article_list = $article_mod->where($sql_where)
			->limit($p->firstRow . ',' . $p->listRows)
			->order($order)->select();		
		$article_results=array();
		foreach ($article_list as $key=>$val){
			$article_results[$key]['id']=$val['id'];
			$article_cate_rel=$article_cate_mod->where("id='{$val['cate_id']}'")->find();
			$article_results[$key]['category']=$article_cate_rel['name'];
			$article_results[$key]['title']=$val['title'];
			$article_results[$key]['description']=$val['abst'];
		//	$article_results[$key]['content']=$val['info'];
			$article_results[$key]['date']=date('Y-m-d H:i:s',$val['add_time']);

			if(!empty($val['publish_time'])){
				$time = strtotime($val['publish_time']);
				$article_results[$key]['publish_time']=date('Y-m-d H:i:s',$time);		
			}else{
				$article_results[$key]['publish_time']=date('Y-m-d H:i:s',time());
			}
			
		}
		//print_r($article_results);
		//echo $article_mod->getLastSql();
		$article_list=array(
			'results'=>$article_results,
			'page'=>$page,
			'totalPage'=>$totalPage
		);					
		echo (json_encode($article_list));
		exit;
	}
	//文章详情调用
	private function articleContentGet(){		
		$id=$this->_get('id');
		
		$article_mod=D('article');
		$article_cate_mod=D('article_cate');
		$article_rel = $article_mod->where("id='{$id}'")->find();
		$article_results['id']=$article_rel['id'];
		$article_cate_rel=$article_cate_mod->where("id='{$article_rel['cate_id']}'")->find();
		$article_results['category']=$article_cate_rel['name'];
		$article_results['title']=$article_rel['title'];
		$article_results['description']=$article_rel['abst'];
		$article_results['publish_time']=$article_rel['publish_time'];
		
		//正则替换文章图片
		$pattern="/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg]))[\'|\"].*?[\/]?>/";
		preg_match_all($pattern,$article_rel['info'],$match);			
			
		$num=count($match[1]);		
		for($i=0;$i<$num;$i++){
			if(!strpos($match[1][$i],"ttp:")){				
				$article_rel['info'] = str_replace($match[1][$i], $this->setting['site_domain'].$match[1][$i], $article_rel['info']);	
			}
		}
		$article_results['content']=$article_rel['info'];
		$article_results['date']=date('Y-m-d H:i:s',$article_rel['add_time']);
		//print_r($article_results);
		//exit;
		echo (json_encode($article_results));
		exit;
	}	
	//文档分类调用	
	/*
	 * cid=0 获取全部分类 
	 * cid=123  获取123分类下面的二级分类
	 * */
	private function documentCateGet(){		
		$cid=$this->_get('cid');
		if(!$cid){
			$cid=0;
		}
		$cate_rel=get_cate_list($model='document_cate',$id=$cid,$level=0);
		
        $cate_lists=json_encode($cate_rel); 
        echo $cate_lists; 
        exit;	
	}
	//视频分类调用	
	/*
	 * cid=0 获取全部分类 
	 * cid=123  获取123分类下面的二级分类
	 * */
	private function videoCateGet(){		
		$cid=$this->_get('cid');
		if(!$cid){
			$cid=0;
		}
		$cate_rel=get_cate_list($model='video_cate',$id=$cid,$level=0);
        $cate_lists=json_encode($cate_rel); 
        echo $cate_lists; 
        exit;
	}
	
	//文档列表
	private function documentListGet(){		
		$cid=$this->_get('cid');	
		if(empty($cid)){  //cid为空 去查找code
			$alias=$this->_get('alias');				
			$document_cate_mod = D('document_cate');
			$cate_rel=$document_cate_mod->where("alias='{$alias}'")->find();
			$cid=$cate_rel['id'];
		}				
		$page=$this->_get('p');
		$order=$this->_get('order');
		$page=isset($page)?$page:1;  //当前的页数			
		$document_mod=D('document');
		$document_cate_mod=D('document_cate');					
		$sql_where = "1=1 AND status=1";
		if(!empty($cid)&&is_numeric($cid)){
			$sql_where.=" AND cate_id='{$cid}'";
		}
		//显示update_time之后的数据			
		if(!empty($this->update_time))
		{
			$sql_where.=" AND add_time>'$this->update_time'";	
		}
		import("ORG.Util.Page");
		$count = $document_mod->where($sql_where)->count();	
		$totalPage=ceil($count/$this->page_size);
		$p = new Page($count,$this->page_size);	
		if(empty($order)){
			$order='ordid ASC,id DESC';
		}else{
			$order=str_replace('_', ' ', $order);
		}
		$document_list = $document_mod->where($sql_where)
			->limit($p->firstRow . ',' . $p->listRows)
			->order($order)->select();		
		$document_results=array();
		foreach ($document_list as $key=>$val){
			$document_results[$key]['id']=$val['id'];
			$document_cate_rel=$document_cate_mod->where("id='{$val['cate_id']}'")->find();
			$document_results[$key]['category']=$document_cate_rel['name'];
			$document_results[$key]['title']=$val['title'];
			$document_results[$key]['url']=$val['url'];			
			$document_results[$key]['filesize']=$val['filesize'];	
			$document_results[$key]['info']=$val['abst'];
			
			if(!empty($val['img'])){
				$document_results[$key]['icon']=$this->setting['site_domain'].'/'.$val['img'];	
			}else{
				$document_results[$key]['icon']='';
			}
			
			$document_results[$key]['date']=date('Y-m-d H:i:s',$val['add_time']);
		}
		//print_r($document_results);
		//echo $document_mod->getLastSql();
		$document_list=array(
			'results'=>$document_results,
			'page'=>$page,
			'totalPage'=>$totalPage
		);					
		echo (json_encode($document_list));
		exit;
	}
	
	private function videoListGet(){		
		$cid=$this->_get('cid');
		if(empty($cid)){  //cid为空 去查找code
			$alias=$this->_get('alias');				
			$video_cate_mod = D('video_cate');
			$cate_rel=$video_cate_mod->where("alias='{$alias}'")->find();
			$cid=$cate_rel['id'];
		}	
		$page=$this->_get('p');
		$order=$this->_get('order');
		$page=isset($page)?$page:1;  //当前的页数			
		$video_mod=D('video');
		$video_cate_mod=D('video_cate');					
		$sql_where = "1=1 AND status=1";
		if(!empty($cid)&&is_numeric($cid)){
			$sql_where.=" AND cate_id='{$cid}'";
		}
		//显示update_time之后的数据			
		if(!empty($this->update_time))
		{
			$sql_where.=" AND add_time>'$this->update_time'";	
		}
		import("ORG.Util.Page");
		$count = $video_mod->where($sql_where)->count();	
		$totalPage=ceil($count/$this->page_size);
		$p = new Page($count,$this->page_size);	
		if(empty($order)){
			$order='ordid ASC,id DESC';
		}else{
			$order=str_replace('_', ' ', $order);
		}
		$video_list = $video_mod->where($sql_where)
			->limit($p->firstRow . ',' . $p->listRows)
			->order($order)->select();		
		$video_results=array();
		foreach ($video_list as $key=>$val){
			$video_results[$key]['id']=$val['id'];
			$video_cate_rel=$video_cate_mod->where("id='{$val['cate_id']}'")->find();
			$video_results[$key]['category']=$video_cate_rel['name'];
			$video_results[$key]['title']=$val['title'];
			$video_results[$key]['url']=$val['url'];			
			$video_results[$key]['info']=$val['abst'];
			$video_results[$key]['date']=date('Y-m-d H:i:s',$val['add_time']);
			if(!empty($val['img'])){
				$video_results[$key]['icon']=$this->setting['site_domain'].'/'.$val['img'];	
			}else{
				$video_results[$key]['icon']='';
			}
								
		}
		//print_r($video_results);
		//echo $video_mod->getLastSql();
		$video_list=array(
			'results'=>$video_results,
			'page'=>$page,
			'totalPage'=>$totalPage
		);					
		echo (json_encode($video_list));
		exit;
	}
	//相册分类	
	private function ImageCateGet(){		
		$cid=$this->_get('cid');
		if(!$cid){
			$cid=0;
		}
		$cate_rel=get_cate_list($model='image_cate',$id=$cid,$level=0);
        $cate_lists=json_encode($cate_rel); 
        echo $cate_lists; 
        exit;
	}
	//相册列表
	private function imageListGet(){		
		$cid=$this->_get('cid');	
		if(empty($cid)){  //cid为空 去查找code
			$alias=$this->_get('alias');				
			$image_cate_mod = D('image_cate');
			$cate_rel=$image_cate_mod->where("alias='{$alias}'")->find();
			$cid=$cate_rel['id'];
		}		
		$page=$this->_get('p');
		$order=$this->_get('order');
		$page=isset($page)?$page:1;  //当前的页数			
		$image_mod=D('image');
		$image_cate_mod=D('image_cate');					
		$image_url_mod=D('image_url');
		$sql_where = "1=1 AND status=1";
		if(!empty($cid)&&is_numeric($cid)){
			$sql_where.=" AND cate_id='{$cid}'";
		}
		//显示update_time之后的数据			
		if(!empty($this->update_time))
		{
			$sql_where.=" AND add_time>'$this->update_time'";	
		}
		import("ORG.Util.Page");
		$count = $image_mod->where($sql_where)->count();	
		$totalPage=ceil($count/$this->page_size);
		$p = new Page($count,$this->page_size);	
		if(empty($order)){
			$order='ordid ASC,id DESC';
		}else{
			$order=str_replace('_', ' ', $order);
		}
		$image_list = $image_mod->where($sql_where)
			->limit($p->firstRow . ',' . $p->listRows)
			->order($order)->select();	
		//echo $image_mod->getLastSql();	
		$image_results=array();
		foreach ($image_list as $key=>$val){
			$image_results[$key]['id']=$val['id'];
			$image_cate_rel=$image_cate_mod->where("id='{$val['cate_id']}'")->find();
			$image_results[$key]['category']=$image_cate_rel['name'];
			$image_results[$key]['title']=$val['title'];					
			$image_results[$key]['info']=$val['abst'];
			$image_results[$key]['date']=date('Y-m-d H:i:s',$val['add_time']);			
			$image_url_rel=$image_url_mod->field('url,surl')->where("image_id='{$val['id']}'")->order("ordid ASC")->limit("0,1")->select();
			//count
			$image_results[$key]['count'] = $image_url_mod->where("image_id='{$val['id']}'")->count();
			if(!empty($image_url_rel[0]['surl'])){
				$image_results[$key]['icon']=$this->setting['site_domain'].'/'.$image_url_rel[0]['surl'];	
			}else{
				$image_results[$key]['icon']='';
			}
			if(!empty($image_url_rel[0]['url'])){
				$image_results[$key]['img']=$this->setting['site_domain'].'/'.$image_url_rel[0]['url'];	
			}else{
				$image_results[$key]['img']='';
			}
				
					
		}		
		$image_list=array(
			'results'=>$image_results,
			'page'=>$page,
			'totalPage'=>$totalPage		
		);					
		echo (json_encode($image_list));
		exit;
	}
	//图库列表	
	private function pictureListGet(){
		$id=$this->_get('id');	  //相册id			
		$page=$this->_get('p');
		$ids='';  //用于判断是不是alias传过来的值
		$alias=$this->_get('alias');	
		
		if(empty($id)){  //如果相册的id为空 则直接获取 分类下面的相册下面的图片	
			$image_cate_mod = D('image_cate');
			$cate_rel=$image_cate_mod->where("alias='{$alias}'")->find();
			$cid=$cate_rel['id'];
			
			$image_mod=D('image');
			$image_list = $image_mod->where("cate_id='{$cid}'")->select();
			
			foreach ($image_list as $key=>$val){
				$ids.=$val['id'].',';
			}
			$ids=substr($ids, 0,-1);			
		}		
		
		
				
		$page=isset($page)?$page:1;  //当前的页数			
					
		$image_url_mod=D('image_url');
		
		$sql_where = "1=1 AND status=1";
		
		if(!empty($id)){
			$sql_where.=" AND image_id='{$id}'";
		}
		//alias不为空 也就是ids不为空的时候执行
		if(!empty($alias)){			
			$sql_where.=" AND image_id in ({$ids})";
		}	
	//	echo $sql_where;	
		//显示update_time之后的数据			
		 if($this->update_time)
		{
			$sql_where.=" AND add_time>'$this->update_time'";	
		}
		 
		import("ORG.Util.Page");
		$count = $image_url_mod->where($sql_where)->count();	
		$totalPage=ceil($count/$this->page_size);
		$p = new Page($count,$this->page_size);		
		$image_list = $image_url_mod->where($sql_where)->limit($p->firstRow . ',' . $p->listRows)->order('ordid ASC')->select();	
		//echo $image_url_mod->getLastSql(); 
		$image_list_rel=array();
		foreach ($image_list as $k=>$v){
			$image_list_rel[$k]['id']=$v['id'];
			if(!empty($v['surl'])){
				$image_list_rel[$k]['surl']=$this->setting['site_domain'].'/'.$v['surl'];	
			}else{
				$image_list_rel[$k]['surl']='';
			}
			
			if(!empty($v['url'])){
				$image_list_rel[$k]['url']=$this->setting['site_domain'].'/'.$v['url'];	
			}else{
				$image_list_rel[$k]['url']='';
			}
			
		}		
		//print_r($image_results);
		//echo $image_mod->getLastSql();
		$image_list=array(
			'results'=>$image_list_rel,
			'page'=>$page,
			'totalPage'=>$totalPage,
			'count'=>$count
		);					
		echo (json_encode($image_list));
		exit;
		
	}
	//获取图片列表	
	private function imagesGet(){
		$id=$this->_get('id');	  //图库 id	
		$image_url_mod=D('image_url');
		$sql_where = "status=1 AND id='{$id}'";
		$image_url_mod=D('image_url');
		$image_list = $image_url_mod->field('url,surl')->where($sql_where)->find();
		if(!empty($image_list['url'])){
			$image_list['url']=$this->setting['site_domain'].'/'.$image_list['url'];	
		}else{
			$image_list['url']='';
		}
		if(!empty($image_list['surl'])){
			$image_list['surl']=$this->setting['site_domain'].'/'.$image_list['surl'];	
		}else{
			$image_list['surl']='';
		}
		
		$image_list=array(
			'results'=>$image_list,		
		);					
		echo (json_encode($image_list));
		exit;
	}	
	//讲师列表	
	private function TeacherList(){				
		$teacher=D('teacher');
		$page=$this->_get('p');		
		$page=isset($page)?$page:1;  //当前的页数		
		$sql_where = "1=1 AND status=1";
		if($this->update_time)
		{
			$sql_where.=" AND add_time>'$this->update_time'";	
		}
		import("ORG.Util.Page");
		$count = $teacher->where($sql_where)->count();	
		$totalPage=ceil($count/$this->page_size);
		$p = new Page($count,$this->page_size);	
		
		$teacher_rel = $teacher->where($sql_where)->limit($p->firstRow . ',' . $p->listRows)
			->order('sort_order asc')->select();
		
		$teacher_list=array();   
		foreach ($teacher_rel as $key=>$val){
			$teacher_list[$key]['id']=$val['id'];
			$teacher_list[$key]['name']=$val['name'];
			$teacher_list[$key]['level']=$val['level'];
			$teacher_list[$key]['video']=$val['video'];
			$teacher_list[$key]['position']=$val['position'];
			$teacher_list[$key]['date']=date('Y-m-d H:i:s',$val['add_time']);		
			if(!empty($val['img'])){
				$teacher_list[$key]['img']=$this->setting['site_domain'].'/'.$val['img'];	
			}else{
				$teacher_list[$key]['img']='';
			}	
			if(!empty($val['video_img'])){
				$teacher_list[$key]['video_img']=$this->setting['site_domain'].'/'.$val['video_img'];
			}else{
				$teacher_list[$key]['video_img']='';
			}			
			$teacher_list[$key]['info']=$val['abst'];	
		}
        
		$teacher_list=array(
			'results'=>$teacher_list,
			'page'=>$page,
			'totalPage'=>$totalPage
		);					
		echo (json_encode($teacher_list));
		exit;		



	}
	//获取讲师详情
	private function TeacherInfo(){
		$id=$this->_get('id');
		$teacher=D('teacher');
		$course=D('course');
        $teacher_rel=$teacher->where("id='{$id}'")->find();
        $teacher_info['id']=$teacher_rel['id'];
        $teacher_info['name']=$teacher_rel['name'];
		$teacher_info['level']=$teacher_rel['level'];
		$teacher_info['position']=$teacher_rel['position'];
		$teacher_info['date']=date('Y-m-d H:i:s',$teacher_rel['add_time']);		
		if(!empty($teacher_rel['img'])){			
			$teacher_info['img']=$this->setting['site_domain'].'/'.$teacher_rel['img'];	
		}else{
			$teacher_info['img']='';
		}	
		
        $teacher_info['info']=$teacher_rel['abst'];	
        $teacher_info['course']=$course->where("teacher_id='{$teacher_rel['id']}'")->select();       
        echo (json_encode($teacher_info));
        exit;
	}	
	/*
	 * cid=0 获取全部分类 
	 * cid=123  获取123分类下面的二级分类
	 * */
	private function CourseCateGet(){	
		$cid=$this->_get('cid');
		if(!$cid){
			$cid=0;
		}
		$cate_rel=get_cate_list($model='course_cate',$id=$cid,$level=0);
        $cate_lists=json_encode($cate_rel); 
        echo $cate_lists; 
        exit;			
        	
	}	
	
	//获取课程列表
	private function CourseList(){
		$cid=$this->_get('cid');
		if(empty($cid)){  //cid为空 去查找code
			$alias=$this->_get('alias');				
			$course_cate_mod = D('course_cate');
			$cate_rel=$course_cate_mod->where("alias='{$alias}'")->find();
			$cid=$cate_rel['id'];
		}	
		$page=$this->_get('p');
		$order=$this->_get('order');
		$page=isset($page)?$page:1;  //当前的页数			
		$course_mod=D('course');
		$course_cate_mod=D('course_cate');	
		$course_video_url_mod=D('course_video_url');    //视屏
		$course_url_mod=D('course_url');    //文档
		
		$sql_where = "1=1 AND status=1";
		if(!empty($cid)&&is_numeric($cid)){
			$sql_where.=" AND cate_id='{$cid}'";
		}
		//显示update_time之后的数据			
		if(!empty($this->update_time))
		{
			$sql_where.=" AND add_time>'$this->update_time'";	
		}
		import("ORG.Util.Page");
		$count = $course_mod->where($sql_where)->count();	
		$totalPage=ceil($count/$this->page_size);
		$p = new Page($count,$this->page_size);	
		if(empty($order)){
			$order='ordid ASC,id DESC';
		}else{
			$order=str_replace('_', ' ', $order);
		}
		$course_list = $course_mod->where($sql_where)
			->limit($p->firstRow . ',' . $p->listRows)
			->order($order)->select();		
		$course_results=array();
		foreach ($course_list as $key=>$val){
			$course_results[$key]['id']=$val['id'];
			$course_cate_rel=$course_cate_mod->where("id='{$val['cate_id']}'")->find();
		    $course_results[$key]['category']=$course_cate_rel['name'];
			$course_results[$key]['title']=$val['title'];			
			$course_results[$key]['info']=$val['abst'];
			$course_results[$key]['date']=date('Y-m-d H:i:s',$val['add_time']);
			if(!empty($val['img'])){
				$course_results[$key]['icon']=$this->setting['site_domain'].'/'.$val['img'];					
			}else{
				$course_results[$key]['icon']='';				
			}
			
			$course_results[$key]['video_count']=$course_video_url_mod->where("course_id='{$val['id']}'")->count();
			$course_results[$key]['document_count']=$course_url_mod->where("course_id='{$val['id']}'")->count();					
		}
		//print_r($video_results);
		//echo $video_mod->getLastSql();
		$course_list=array(
			'results'=>$course_results,
			'page'=>$page,
			'totalPage'=>$totalPage
		);					
		echo (json_encode($course_list));
		exit;		
	}
	//获取精选课程
	private function HotCourseList(){
		
		$page=$this->_get('p');
		$order=$this->_get('order');
		$page=isset($page)?$page:1;  //当前的页数			
		$course_mod=D('course');
		$course_cate_mod=D('course_cate');
		$course_video_url_mod=D('course_video_url');    //视屏
		$course_url_mod=D('course_url');    //文档
		
		$sql_where = "status=1 AND hot=1";		
		//显示update_time之后的数据			
		if(!empty($this->update_time))
		{
			$sql_where.=" AND add_time>'$this->update_time'";	
		}		
		
		import("ORG.Util.Page");
		$count = $course_mod->where($sql_where)->count();	
		$totalPage=ceil($count/$this->page_size);
		$p = new Page($count,$this->page_size);	
		if(empty($order)){
			$order='ordid ASC,id DESC';
		}else{
			$order=str_replace('_', ' ', $order);
		}
		$course_list = $course_mod->where($sql_where)
			->limit($p->firstRow . ',' . $p->listRows)
			->order($order)->select();		
		$course_results=array();
		foreach ($course_list as $key=>$val){
			$course_results[$key]['id']=$val['id'];
			$course_cate_rel=$course_cate_mod->where("id='{$val['cate_id']}'")->find();
		    $course_results[$key]['category']=$course_cate_rel['name'];
			$course_results[$key]['title']=$val['title'];			
			$course_results[$key]['info']=$val['abst'];
			$course_results[$key]['date']=date('Y-m-d H:i:s',$val['add_time']);
			if(!empty($val['img'])){
				$course_results[$key]['icon']=$this->setting['site_domain'].'/'.$val['img'];	
			}else{
				$course_results[$key]['icon']='';
			}
			
			$course_results[$key]['video_count']=$course_video_url_mod->where("course_id='{$val['id']}'")->count();
			$course_results[$key]['document_count']=$course_url_mod->where("course_id='{$val['id']}'")->count();
			
		}
		//print_r($video_results);
		//echo $video_mod->getLastSql();
		$course_list=array(
			'results'=>$course_results,
			'page'=>$page,
			'totalPage'=>$totalPage,
			'count'=>$count
		);					
		echo (json_encode($course_list));
		exit;		
	}
	
	//获取课程详情
	private function CourseContentGet(){
		$id=$this->_get('id');	
			
		$course_mod=D('course');
		
		
		$course_rel = $course_mod->where("id='{$id}'")->find();
			
		if(!empty($course_rel['img'])){
			$course_rel['img']=$this->setting['site_domain'].'/'.$course_rel['img'];	
		}else{
			$course_rel['img']='';
		}
		
		$course_rel=array(
			'results'=>$course_rel			
		);	
		
		echo (json_encode($course_rel));
		exit;
				
	}
	//课程相关视频url
	private function CourseVideoUrlList(){
		$id=$this->_get('id');	
		$course_video_url_mod=D('course_video_url');		
		$course_video_url_rel = $course_video_url_mod->field('id,url,img,name')->where("course_id='{$id}'")->select();

		foreach($course_video_url_rel as $key=>$val){
			$course_video_url_rel[$key]['name']=$val['name'];
			if(!empty($val['img'])){
				$course_video_url_rel[$key]['img']=$this->setting['site_domain'].'/'.$val['img'];	
			}else{
				$course_video_url_rel[$key]['img']='';
			}
		}
		$course_video_url_rel=array(
			'results'=>$course_video_url_rel			
		);	
		
		echo (json_encode($course_video_url_rel));
		exit;
				
	}
	//课程相关视频url内容详情
	private function CourseVideoUrlContent(){
		$id=$this->_get('id');	
			
		$course_video_url_mod=D('course_video_url');
		
		$course_video_url_rel = $course_video_url_mod->field('id,url,img,name')->where("id='{$id}'")->find();
		
		$course_video_url_rel=array(
			'results'=>$course_video_url_rel			
		);	
		echo (json_encode($course_video_url_rel));
		exit;
				
	}
	
	//文档相关视频url
	private function CourseDocumentUrlList(){
		$id=$this->_get('id');	
			
		$course_url_mod=D('course_url');		
		
		$course_url_rel = $course_url_mod->field('id,url,name,img')->where("course_id='{$id}'")->select();
		foreach($course_url_rel as $key=>$val){
			$course_url_rel[$key]['name']=$val['name'];
			
			if(!empty($val['img'])){
				$course_url_rel[$key]['img']=$this->setting['site_domain'].'/'.$val['img'];	
			}else{
				$course_url_rel[$key]['img']='';
			}
			
		
		}
		$course_url_rel=array(
			'results'=>$course_url_rel			
		);		
		echo (json_encode($course_url_rel));
		exit;
				
	}
	//课程相关视频url内容详情
	private function CourseDocumentUrlContent(){
		$id=$this->_get('id');	
		$course_url_mod=D('course_url');
		$course_url_rel = $course_url_mod->field('id,url,name,img')->where("id='{$id}'")->find();
		$course_url_rel=array(
			'results'=>$course_url_rel			
		);
		echo (json_encode($course_url_rel));
		exit;
				
	}
	
	
	
	
	
}

?>