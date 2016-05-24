<?php
// +----------------------------------------------------------------------
// | APPCMS 移动应用软件后台管理系统
// +----------------------------------------------------------------------
// | provide by ：shiraz-soft.com
// 
// +----------------------------------------------------------------------
// | Author: Shirazsoft APP Team
// +----------------------------------------------------------------------

class MapAction extends Action
{
    function index()
    {

        $this->display();
    }

    function index2()
    {

        $this->display();
    }


    function index3()
    {

        $this->display();
    }


    function index4()
    {

        $this->display();
    }

    function index5()
    {
        $this->firstPoint = '121.42767, 31.184755';
        $this->display();
    }

    function getPoint($i)
    {
        $points = array('121.431187,31.185307', '121.436146,31.180179', "121.437188,31.174463", "121.434241,31.174216", "121.425151,31.172856","121.404202,31.176224","121.391195,31.17196","121.393279,31.162814");
        $this->ajaxReturn($points[$i]);
    }


}

?>