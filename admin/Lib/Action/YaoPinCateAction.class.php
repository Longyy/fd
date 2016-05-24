<?php
// +----------------------------------------------------------------------
// | APPCMS 移动应用软件后台管理系统
// +----------------------------------------------------------------------
// | provide by ：shiraz-soft.com
// 
// +----------------------------------------------------------------------
// | Author: Shirazsoft APP Team
// +----------------------------------------------------------------------

class YaoPinCateAction extends BaseAction
{
	public $cate_list;
	function _initialize(){
		parent::_initialize();
		//每次显示的时候清除缓存		
		$this->cate_list=get_cate_list($model='app_cate',$id=0,$level=0);
	}
	
	//分类列表
    function index()
    {    	    	    	
   		$this->assign('app_cate_list',$this->cate_list['sort_list']);    	
		$big_menu = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=YaoPinCate&a=add\', title:\''.L('add_cate').'\', width:\'500\', height:\'260\', lock:true}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);', L('add_cate'));
		$this->assign('big_menu',$big_menu);
		$this->display();
    }

    //添加分类数据
    function add()
    {
    	if(isset($_POST['dosubmit'])){
			$app_cate_mod = M('app_cate');
	        if( false === $vo = $app_cate_mod->create() ){
		        $this->error( $app_cate_mod->error() );
		    }
		    if($vo['name']==''){
		    	$this->error('分类名称不能为空');exit;
		    }
		    $result = $app_cate_mod->where("name='".$vo['name']."' AND pid='".$vo['pid']."'")->count();
		    if($result != 0){
		        $this->error('该分类已经存在');
		    }
			//保存当前数据
		    $app_cate_id = $app_cate_mod->add();
		    $this->success(L('operation_success'), '', '', 'add');
    	}else{
    		$app_cate_mod = D('app_cate');
	    	$result = $app_cate_mod->order('sort_order ASC')->select();
	    	$app_cate_list = array();
	    	foreach ($result as $val) {
	    	    if ($val['pid']==0) {
	    	        $app_cate_list['parent'][$val['id']] = $val;
	    	    } else {
	    	        $app_cate_list['sub'][$val['pid']][] = $val;
	    	    }
	    	}
	    	$this->assign('app_cate_list',$app_cate_list);
	    	$this->assign('show_header', false);
	        $this->display();
    	}

    }

    function delete()
    {
        if((!isset($_GET['id']) || empty($_GET['id'])) && (!isset($_POST['id']) || empty($_POST['id']))) {
            $this->error('请选择要删除的分类！');
		}
		$app_cate_mod = M('app_cate');
		if (isset($_POST['id']) && is_array($_POST['id'])) {
		    $cate_ids = implode(',', $_POST['id']);
		    $app_cate_mod->delete($cate_ids);
		} else {

		    $cate_id = intval($_GET['id']);
		    $app_cate_mod->delete($cate_id);
		}
		$this->success(L('operation_success'));
    }

    function edit()
    {
    	if(isset($_POST['dosubmit'])){
    		$app_cate_mod = M('app_cate');

	    	$old_cate = $app_cate_mod->where('id='.$_POST['id'])->find();
	        //名称不能重复
	        if ($_POST['name']!=$old_cate['name']) {
	            if ($this->_cate_exists($_POST['name'], $_POST['pid'], $_POST['id'])) {
	            	$this->error('分类名称重复！');exit;
	            }
	        }

	        //获取此分类和他的所有下级分类id
	        $vids = array();
	        $children[] = $old_cate['id'];
	        $vr = $app_cate_mod->where('pid='.$old_cate['id'])->select();
	        foreach ($vr as $val) {
	            $children[] = $val['id'];
	        }
	        if (in_array($_POST['pid'], $children)) {
	            $this->error('所选择的上级分类不能是当前分类或者当前分类的下级分类！');
	        }

	    	$vo = $app_cate_mod->create();
			$result = $app_cate_mod->save();
			if(false !== $result){
				$this->success(L('operation_success'), '', '', 'edit');
			}else{
				$this->error(L('operation_failure'));
			}
    	}else{
    		$app_cate_mod = M('app_cate');
			if( isset($_GET['id']) ){
				$cate_id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->error(L('please_select').L('app_name'));
			}
			$app_cate_info = $app_cate_mod->where('id='.$cate_id)->find();

			$result = $app_cate_mod->order('sort_order ASC')->select();
	    	$cate_list = array();
	    	foreach ($result as $val) {
	    	    if ($val['pid']==0) {
	    	        $cate_list['parent'][$val['id']] = $val;
	    	    } else {
	    	        $cate_list['sub'][$val['pid']][] = $val;
	    	    }
	    	}
		    $this->assign('cate_list', $cate_list);
			$this->assign('app_cate_info',$app_cate_info);
			$this->assign('show_header', false);
			$this->display();
    	}

    }


    private function _cate_exists($name, $pid, $id=0)
    {
    	$where = "name='".$name."' AND pid='".$pid."'";
    	if( $id&&intval($id) ){
    		$where .= " AND id<>'".$id."'";
    	}
        $result = M('app_cate')->where($where)->count();
        //echo(M('app_cate')->getLastSql());exit;
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    function sort_order()
    {
    	$app_cate_mod = M('app_cate');
		if (isset($_POST['listorders'])) {
            foreach ($_POST['listorders'] as $id=>$sort_order) {
            	$data['sort_order'] = $sort_order;
                $app_cate_mod->where('id='.$id)->save($data);
            }
            $this->success(L('operation_success'));
        }
        $this->error(L('operation_failure'));
    }
    //修改状态
	function status()
	{
		$app_cate_mod = D('app_cate');
		$flink_mod = D('flink');
		$id 	= intval($_REQUEST['id']);
		$type 	= trim($_REQUEST['type']);
		$sql 	= "update ".C('DB_PREFIX')."app_cate set $type=($type+1)%2 where id='$id'";
		$res 	= $app_cate_mod->execute($sql);
		$values = $app_cate_mod->where('id='.$id)->find();
		$this->ajaxReturn($values[$type]);
	}
}
?>