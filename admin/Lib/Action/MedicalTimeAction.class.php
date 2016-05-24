<?php
// +----------------------------------------------------------------------
// | APPCMS 移动应用软件后台管理系统
// +----------------------------------------------------------------------
// | provide by ：shiraz-soft.com
// 
// +----------------------------------------------------------------------
// | Author: Shirazsoft APP Team
// +----------------------------------------------------------------------

class MedicalTimeAction extends BaseAction
{
    function index()
    {
        $cms_medical_time      = M('medical_time');
        $result                = $cms_medical_time->select();
        $forenoon_begin        = explode(',', $result[0]['begin_time']);
        $forenoon_end          = explode(',', $result[0]['end_time']);
        $afternoon_begin       = explode(',', $result[1]['begin_time']);
        $afternoon_end         = explode(',', $result[1]['end_time']);
        $night_begin           = explode(',', $result[2]['begin_time']);
        $night_end             = explode(',', $result[2]['end_time']);
        $this->forenoon_begin  = $forenoon_begin[0];
        $this->forenoon_end    = $forenoon_end[0];
        $this->afternoon_begin = $afternoon_begin[0];
        $this->afternoon_end   = $afternoon_end[0];
        $this->night_begin     = $night_begin[0];
        $this->night_end       = $night_end[0];

        $this->forenoon_begin_min  = $forenoon_begin[1];
        $this->forenoon_end_min    = $forenoon_end[1];
        $this->afternoon_begin_min = $afternoon_begin[1];
        $this->afternoon_end_min   = $afternoon_end[1];
        $this->night_begin_min     = $night_begin[1];
        $this->night_end_min       = $night_end[1];

        $this->display();
    }


    public function edit()
    {
        $cms_medical_time = D('medical_time');
        $sql              = "update cms_medical_time set begin_time ='$_POST[forenoon_begin],$_POST[forenoon_begin_min]',end_time ='$_POST[forenoon_end],$_POST[forenoon_end_min]' where id=1";
        $cms_medical_time->execute($sql);

        $sql = "update cms_medical_time set begin_time ='$_POST[afternoon_begin],$_POST[afternoon_begin_min]',end_time ='$_POST[afternoon_end],$_POST[afternoon_end_min]' where id=2";
        $cms_medical_time->execute($sql);

        $sql = "update cms_medical_time set begin_time ='$_POST[night_begin],$_POST[night_begin_min]',end_time ='$_POST[night_end],$_POST[night_end_min]' where id=3";
        $cms_medical_time->execute($sql);

        $this->success(L('operation_success'));
    }


}

?>