<?php
// +----------------------------------------------------------------------
// | APPCMS 移动应用软件后台管理系统
// +----------------------------------------------------------------------
// | provide by ：shiraz-soft.com
// 
// +----------------------------------------------------------------------
// | Author: Shirazsoft APP Team
// +----------------------------------------------------------------------

class PublicAction extends BaseAction
{
    // 菜单页面
    public function menu()
    {
        //显示菜单项
        $id           = intval($_REQUEST['tag']) == 0 ? 6 : intval($_REQUEST['tag']);
        $menu         = array();
        $role_id      = D('admin')->where('id=' . $_SESSION['admin_info']['id'])->getField('role_id');
        $node_ids_res = D("access")->where("role_id=" . $role_id)->field("node_id")->select();

        $node_ids = array();
        foreach ($node_ids_res as $row) {
            array_push($node_ids, $row['node_id']);
        }
        //读取数据库模块列表生成菜单项
        $node  = M("node");
        $where = "auth_type<>2 AND status=1 AND is_show=0 AND group_id=" . $id;
        $list  = $node->where($where)->field('id,action,action_name,module,module_name,data')->order('sort DESC')->select();
        foreach ($list as $key => $action) {
            $data_arg = array();
            if ($action['data']) {
                $data_arr = explode('&', $action['data']);
                foreach ($data_arr as $data_one) {
                    $data_one_arr               = explode('=', $data_one);
                    $data_arg[$data_one_arr[0]] = $data_one_arr[1];
                }
            }
            $action['url'] = U($action['module'] . '/' . $action['action'], $data_arg);
            if ($action['action']) {
                $menu[$action['module']]['navs'][] = $action;
            }
            $menu[$action['module']]['name'] = $action['module_name'];
            $menu[$action['module']]['id']   = $action['id'];
        }
        $this->assign('menu', $menu);
        $this->display('left');
    }

    /**
     * 后台主页
     */
    public function main()
    {
        $security_info = array();
        if (is_dir(ROOT_PATH . "/install")) {
            $security_info[] = "强烈建议删除安装文件夹,点击<a href='" . u('Public/delete_install') . "'>【删除】</a>";
        }
        if (APP_DEBUG == true) {
            $security_info[] = "强烈建议您网站上线后，建议关闭 DEBUG （前台错误提示）";
        }
        if (count($security_info) <= 0) {
            $this->assign('no_security_info', 0);
        } else {
            $this->assign('no_security_info', 1);
        }
        $this->assign('security_info', $security_info);
        $disk_space  = @disk_free_space(".") / pow(1024, 2);
        $server_info = array(
            '程序版本'     => NOW_VERSION . '[<a href="http://bbs.shiraz-soft.com/forum.php?mod=forumdisplay&fid=62" target="_blank">查看最新版本</a>]',
            '操作系统'     => PHP_OS,
            '运行环境'     => $_SERVER["SERVER_SOFTWARE"],
            '上传附件限制'   => ini_get('upload_max_filesize'),
            '执行时间限制'   => ini_get('max_execution_time') . '秒',
            '服务器域名/IP' => $_SERVER['SERVER_NAME'] . ' [ ' . gethostbyname($_SERVER['SERVER_NAME']) . ' ]',
            '剩余空间'     => round($disk_space < 1024 ? $disk_space : $disk_space / 1024, 2) . ($disk_space < 1024 ? 'M' : 'G'),
        );
        $this->assign('set', $this->setting);
        $this->assign('server_info', $server_info);
        $this->display();
    }

    public function login()
    {
        //unset($_SESSION);
        $admin_mod = M('admin');
        if ($_POST) {
            $username = $_POST['username'] && trim($_POST['username']) ? trim($_POST['username']) : '';
            $password = $_POST['password'] && trim($_POST['password']) ? trim($_POST['password']) : '';
            if (!$username || !$password) {
                redirect(u('Public/login'));
            }
            if ($this->setting['check_code'] == 1) {
                if ($_SESSION['verify'] != md5($_POST['verify'])) {
                    $this->error(L('verify_error'));
                }
            }
            //生成认证条件
            $map = array();
            // 支持使用绑定帐号登录
            $map['user_name'] = $username;
            $map["status"]    = array('gt', 0);
            $admin_info       = $admin_mod->where("user_name='$username'")->find();

            //使用用户名、密码和状态的方式进行认证
            if (false === $admin_info) {
                $this->error('帐号不存在或已禁用！');
            } else {
                if ($admin_info['password'] != md5($password)) {
                    $this->error('密码错误！');
                }

                $_SESSION['admin_info'] = $admin_info;
//				if($authInfo['user_name']=='admin') {
//					$_SESSION['administrator'] = true;
//				}
                $this->success('登录成功！', u('Index/index'));
                exit;
            }
        }
        $this->assign('set', $this->setting);
        $this->display();
    }

    function sendMessage()
    {
        if ($_POST) {
            $title   = $this->_post('title');
            $content = $this->_post('content');
            $cate_id = $this->_post('cate_id');
            vendor('jpush.jpush_api_php_client');

            //获取对应分类下面的appkey appsecret
            $MessageCateMod = D('message_cate');
            $MessageCateRel = $MessageCateMod->where("id='{$cate_id}'")->find();
            $master_secret  = trim($MessageCateRel['MasterSecret']);
            $app_key        = trim($MessageCateRel['AppKey']);

            $sendno = rand(1, 198888);

            //echo phpinfo();
            $client = new JpushClient($app_key, $master_secret, 0);


            //s
            $str = $client->sendNotificationByAppkey($sendno, 'des', $title, $content, 'android', array('id' => '123'));

            $rel = json_decode($str);
            if ($rel->errcode == 0) {
                $this->success('推送成功！', u('Public/sendMessage'));
            } else {
                $this->error('推送失败，失败原因:' . $rel->errcode . '---' . $rel->errmsg, u('Public/sendMessage'));
            }


            //{"sendno":"175088","msg_id":"1928523460","errcode":0,"errmsg":"Succeed"}

            //{"errcode":1002,"errmsg":"app_key format is error"}
            //  echo $str."\n";
            exit;

        }
        $MessageCateMod = D('message_cate');
        $MessageCateRel = $MessageCateMod->select();
        $this->assign('MessageCateList', $MessageCateRel);
        $this->display();
    }

    /*发送消息分类配置*/
    function sendMessageCate()
    {
        $MessageCateMod = D('message_cate');
        $MessageCateRel = $MessageCateMod->select();
        $this->assign('MessageCateList', $MessageCateRel);
        $big_menu = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=Public&a=sendMessageCateAdd\', title:\'' . L('add_cate') . '\', width:\'500\', height:\'260\', lock:true}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);', L('add_cate'));

        $this->assign('big_menu', $big_menu);
        $this->display();
    }

    function sendMessageCateAdd()
    {
        $MessageCateMod = D('message_cate');
        if (isset($_POST['dosubmit'])) {

            if (false === $vo = $MessageCateMod->create()) {
                $this->error($MessageCateMod->error());
            }

            //保存当前数据
            $article_cate_id = $MessageCateMod->add();
            $this->success(L('operation_success'), '', '', 'add');
        } else {
            $this->display();
        }
    }

    function sendMessageCateEdit()
    {
        $MessageCateMod = D('message_cate');

        if (isset($_POST['dosubmit'])) {

            if (false === $vo = $MessageCateMod->create()) {
                $this->error($MessageCateMod->error());
            }

            $result = $MessageCateMod->save();
            if (false !== $result) {
                $this->success(L('operation_success'), '', '', 'edit');
            } else {
                $this->error(L('operation_failure'));
            }
        } else {
            if (isset($_GET['id'])) {
                $cate_id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->error(L('please_select') . L('article_name'));
            }
            $MessageCateInfo = $MessageCateMod->where('id=' . $cate_id)->find();
            $this->assign('MessageCateInfo', $MessageCateInfo);
            $this->assign('show_header', false);
            $this->display();
        }
    }


    //退出登录
    public function logout()
    {
        if (isset($_SESSION['admin_info'])) {
            unset($_SESSION['admin_info']);
            $this->success('退出登录成功！', u('Public/login'));
        } else {
            $this->error('已经退出登录！');
        }
    }

    //验证码
    public function verify()
    {
        import("ORG.Util.Image");
        Image::buildImageVerify(4, 1, 'gif', '50', '24');
    }

    /*
         * 清除缓存
         * */
    function clearCache()
    {
        import("ORG.Io.Dir");
        $dir = new Dir;

        if (is_dir(CACHE_PATH)) {
            $dir->del(CACHE_PATH);
        }
        if (is_dir(TEMP_PATH)) {
            $dir->del(TEMP_PATH);
        }
        if (is_dir(LOG_PATH)) {
            $dir->del(LOG_PATH);
        }
        if (is_dir(DATA_PATH . '_fields/')) {
            $dir->del(DATA_PATH . '_fields/');
        }

        if (is_dir("./index/Runtime/Cache/")) {
            $dir->del("./index/Runtime/Cache/");
        }

        if (is_dir("./index/Runtime/Temp/")) {
            $dir->del("./index/Runtime/Temp/");
        }

        if (is_dir("./index/Runtime/Logs/")) {
            $dir->del("./index/Runtime/Logs/");
        }

        if (is_dir("./index/Runtime/Data/_fields/")) {
            $dir->del("./index/Runtime/Data/_fields/");
        }
        $this->display('index');
    }

    public function deleteInstall()
    {
        import("ORG.Io.Dir");
        $dir = new Dir;
        $dir->delDir(ROOT_PATH . "/install");
        @unlink(ROOT_PATH . '/install.php');
        if (!is_dir(ROOT_PATH . "/install")) {
            $this->success(L('operation_success'));
        }
    }

    public function search()
    {
        $type    = $_POST["condition"];
        $content = $_POST['content'];
        switch ($type) {
            //转诊单
            case 1:
                $sql   = "select *,t2.role_id,t3.name as doctor_name from cms_qrcode_consultation t1 left join cms_wechat_user t2 on t1.doctor_open_id=t2.open_id left join cms_transfer_doctor t3 on t2.identity_id=t3.id where t2.role_id=3 and code=" . $content;
                $Model = new Model();
                $data  = $Model->query($sql);
                if (!$data) {
                    echo('暂无数据');
                    return;
                } else {
                    $this->model = $data[0];
                }
                $this->display('transfer');
                break;
            //患者
            case 2:
                $sql   = "select t1.*,t2.name as doctor_name,t4.name as center_name from cms_consultation t1
left join cms_doctor t2 on t1.doctor_id=t2.id
left join cms_medical_center_doctor t3 on t2.id=t3.doctor_id
left join cms_medical_center t4 on t3.medical_center_id=t4.id
where t1.name like '%$content%' or t1.phone like '%$content%'";
                $Model = new Model();
                $data  = $Model->query($sql);
                if (!$data) {
                    echo('暂无数据');
                    return;
                } else {
                    $this->model = $data[0];
//                    dump($this->model );
                }
                $this->display('patient');
                break;
            //转诊医生
            case 3:
                $sql   = "select t1.*,t2.province from cms_transfer_doctor t1
left join cms_province t2 on t1.province_id=t2.id
where name like '%$content%' or phone like '%$content%'";
                $Model = new Model();
                $data  = $Model->query($sql);
                if (!$data) {
                    echo('暂无数据');
                    return;
                } else {
                    $this->model = $data[0];
//                    dump($this->model );
                }
                $this->display('transferDoctor');
                break;
            //治疗中心医生
            case 4:
                $sql   = "select * from cms_doctor where name like '%$content%' or phone like '%$content%'";
                $Model = new Model();
                $data  = $Model->query($sql);
                if (!$data) {
                    echo('暂无数据');
                    return;
                } else {
                    $this->model = $data[0];
//                    dump($this->model );
                }
                $this->display('doctor');
                break;
            //转诊医院
            case 5:
                $sql   = "select t1.*,t2.province from cms_medical_center t1 left join cms_province t2 on t1.province_id=t2.id where name like '%$content%'";
                $Model = new Model();
                $data  = $Model->query($sql);
                if (!$data) {
                    echo('暂无数据');
                    return;
                } else {
                    $this->model = $data[0];
//                    dump($this->model );
                }
                $this->display('hospital');
                break;
        }
    }
}

?>