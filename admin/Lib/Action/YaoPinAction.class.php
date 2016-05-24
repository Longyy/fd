<?php
// +----------------------------------------------------------------------
// | APPCMS 移动应用软件后台管理系统
// +----------------------------------------------------------------------
// | provide by ：shiraz-soft.com
// 
// +----------------------------------------------------------------------
// | Author: Shirazsoft APP Team
// +----------------------------------------------------------------------

class YaoPinAction extends BaseAction
{
	public function index()
	{
		$app_mod = D('app');
		$app_cate_mod = D('app_cate');

		//搜索
		$where = '1=1';
		if (isset($_GET['keyword']) && trim($_GET['keyword'])) {
		    $where .= " AND title LIKE '%".$_GET['keyword']."%'";
		    $this->assign('keyword', $_GET['keyword']);
		}	
		if (isset($_GET['cate_id']) && intval($_GET['cate_id'])) {
		    $where .= " AND cate_id=".$_GET['cate_id'];
		    $this->assign('cate_id', $_GET['cate_id']);
		}
		import("ORG.Util.Page");
		$count = $app_mod->where($where)->count();
		$p = new Page($count,20);
		$app_list = $app_mod->where($where)->limit($p->firstRow.','.$p->listRows)->order('add_time DESC,ordid ASC')->select();
		
		$key = 1;
		foreach($app_list as $k=>$val){
			$app_list[$k]['key'] = ++$p->firstRow;
			$app_list[$k]['cate_name'] = $app_cate_mod->field('name')->where('id='.$val['cate_id'])->find();
		}
		$result = $app_cate_mod->order('sort_order ASC')->select();
    	$cate_list = array();
    	foreach ($result as $val) {
    	    if ($val['pid']==0) {
    	        $cate_list['parent'][$val['id']] = $val;
    	    } else {
    	        $cate_list['sub'][$val['pid']][] = $val;
    	    }
    	}
		//网站信息/应用资讯
		$page = $p->show();
		$this->assign('page',$page);
    	$this->assign('cate_list', $cate_list);
		$this->assign('app_list',$app_list);
		$this->display();
	}

	function edit()
	{
		if(isset($_POST['dosubmit'])){
			$app_mod = D('app');
			$data = $app_mod->create();
			if($data['cate_id']==0){
				$this->error('请选择资讯分类');
			}
			if ($_FILES['img']['name']!=''&&$_FILES['url']['name']=='') {
			    $upload_list = $this->_upload();
			 	$data['img'] = $upload_list['0']['savepath'].$upload_list['0']['savename'];
			}else if ($_FILES['url']['name']!=''&&$_FILES['img']['name']=='') {
			    $upload_list = $this->_upload();
			 	$data['url'] = $upload_list['0']['savepath'].$upload_list['0']['savename'];
			 	$data['size']=round($upload_list['0']['size']/1024/1024, 2);;
			}else if($_FILES['img']['name']!=''&&$_FILES['url']['name']!=''){
				$upload_list = $this->_upload();
			    $data['img'] = $upload_list['0']['savepath'].$upload_list['0']['savename'];	
			    $data['url'] = $upload_list['1']['savepath'].$upload_list['1']['savename'];
			    $data['size']=round($upload_list['1']['size']/1024/1024, 2);;
			}
			$result = $app_mod->save($data);
			if(false !== $result){
				$this->success(L('operation_success'),U('YaoPin/index'));
			}else{
				$this->error(L('operation_failure'));
			}
		}else{
			$app_mod = D('app');
			if( isset($_GET['id']) ){
				$app_id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->error(L('please_select'));
			}
			$app_cate_mod = D('app_cate');
		    $result = $app_cate_mod->order('sort_order ASC')->select();
		    $cate_list = array();
		    foreach ($result as $val) {
		    	if ($val['pid']==0) {
		    	    $cate_list['parent'][$val['id']] = $val;
		    	} else {
		    	    $cate_list['sub'][$val['pid']][] = $val;
		    	}
		    }
			$app_info = $app_mod->where('id='.$app_id)->find();	
			$this->assign('show_header', false);
	    	$this->assign('cate_list',$cate_list);
			$this->assign('app',$app_info);
			$this->display();
		}


	}

	function add()
	{
		if(isset($_POST['dosubmit'])){
			$app_mod = D('app');
			
			if($_POST['title']==''){
				$this->error(L('input').L('app_title'));
			}
			if(false === $data = $app_mod->create()){
				$this->error($app_mod->error());
			}			
			//img和app的url 必须都存在
			if ($_FILES['img']['name']!=''&&$_FILES['url']['name']!=''){			  
			    $upload_list = $this->_upload();
			    $data['img'] = $upload_list['0']['savepath'].$upload_list['0']['savename'];	
			    $data['url'] = $upload_list['1']['savepath'].$upload_list['1']['savename'];
			    $data['size']=round($upload_list['1']['size']/1024/1024,2);
			}
			$data['add_time']=time();
			$result = $app_mod->add($data);
			if($result){
				$cate = M('app_cate')->field('id,pid')->where("id=".$data['cate_id'])->find();
				if( $cate['pid']!=0 ){
					M('app_cate')->where("id=".$cate['pid'])->setInc('app_nums');
					M('app_cate')->where("id=".$data['cate_id'])->setInc('app_nums');
				}else{
					M('app_cate')->where("id=".$data['cate_id'])->setInc('app_nums');
				}
				$this->success('添加成功');
			}else{
				$this->error('添加失败');
			}
		}else{
			$app_cate_mod = D('app_cate');
	    	$result = $app_cate_mod->order('sort_order ASC')->select();
	    	$cate_list = array();
	    	foreach ($result as $val) {
	    	    if ($val['pid']==0) {
	    	        $cate_list['parent'][$val['id']] = $val;
	    	    } else {
	    	        $cate_list['sub'][$val['pid']][] = $val;
	    	    }
	    	}
	    	$this->assign('cate_list',$cate_list);
	    	$this->display();
		}
	}

	function delete_attatch()
    {
    	$attatch_mod = D('attatch');
    	$app_mod = D('app');
    	$app_id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : exit('0');
    	$aid = isset($_GET['aid']) && intval($_GET['aid']) ? intval($_GET['aid']) : exit('0');
		$app_info = $app_mod->where('id='.$app_id)->find();
    	$aid_arr = explode(',', $app_info['aid']);
    	foreach ($aid_arr as $key=>$val) {
    	    if ($val == $aid) {
    	        unset($aid_arr[$key]);
    	    }
    	}
    	$aids = implode(',', $aid_arr);
    	$app_mod->where('id='.$app_id)->save(array('aid'=>$aids));
    	echo '1';
    	exit;
    }

	function delete()
    {
		$app_mod = D('app');
		if((!isset($_GET['id']) || empty($_GET['id'])) && (!isset($_POST['id']) || empty($_POST['id']))) {
            $this->error('请选择要删除的资讯！');
		}
		if( isset($_POST['id'])&&is_array($_POST['id']) ){
			$cate_ids = implode(',',$_POST['id']);
			foreach( $_POST['id'] as $val ){
				$app = $app_mod->field("id,cate_id")->where("id=".$val)->find();
				$cate = M('app_cate')->field('id,pid')->where("id=".$app['cate_id'])->find();
				if( $cate['pid']!=0 ){
					M('app_cate')->where("id=".$cate['pid'])->setDec('app_nums');
					M('app_cate')->where("id=".$app['cate_id'])->setDec('app_nums');
				}else{
					M('app_cate')->where("id=".$app['cate_id'])->setDec('app_nums');
				}

			}
			$app_mod->delete($cate_ids);
		}else{
			$cate_id = intval($_GET['id']);
			$app = $app_mod->field("id,cate_id")->where("id=".$cate_id)->find();
			M('app_cate')->where("id=".$app['cate_id'])->setDec('app_nums');
		    $app_mod->where('id='.$cate_id)->delete();
		}
		$this->success(L('operation_success'));
    }

    public function _upload()
    {
    	import("ORG.Net.UploadFile");
        $upload = new UploadFile();
        //设置上传文件大小
        $upload->maxSize = 32922000;
        //$upload->allowExts = explode(',', 'jpg,gif,png,jpeg');
        $upload->savePath = 'data/app/';

        $upload->saveRule = uniqid;
        if (!$upload->upload()) {
            //捕获上传异常
            $this->error($upload->getErrorMsg());
        } else {
            //取得成功上传的文件信息
            $uploadList = $upload->getUploadFileInfo();                                 
        }        
        return $uploadList;
    }

	function sort_order()
    {
    	$app_mod = D('app');
		if (isset($_POST['listorders'])) {
            foreach ($_POST['listorders'] as $id=>$sort_order) {
            	$data['ordid'] = $sort_order;
                $app_mod->where('id='.$id)->save($data);
            }
            $this->success(L('operation_success'));
        }
        $this->error(L('operation_failure'));
    }

    //修改状态
	function status()
	{
		$app_mod = D('app');
		$id 	= intval($_REQUEST['id']);
		$type 	= trim($_REQUEST['type']);
		$sql 	= "update ".C('DB_PREFIX')."app set $type=($type+1)%2 where id='$id'";
		$res 	= $app_mod->execute($sql);
		$values = $app_mod->field("id,".$type)->where('id='.$id)->find();
		$this->ajaxReturn($values[$type]);
	}

}
?>