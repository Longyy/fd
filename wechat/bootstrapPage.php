<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-8-28
 * Time: 下午3:08
 */
//bootstrap page
function bootstrap_page($totalRows,$listRows,$current,$method='html',$url='')
{
    include('page.php');
    if(!$url)
    {
        $_ = '';
        if($_GET)
        {
            foreach($_GET AS $k=>$v)
            {
                if(!$v) continue;
                $_ .= $k.'-'.$v.'-';
            }
        }
        $url = U(__CONTROLLER__.'/'.ACTION_NAME).'-'.$_.'p-?';
    }

    $params = array
    (
        'total_rows'=> $totalRows, #(必须)
        'method'    => $method, #(必须)
        'parameter' => $url,  #(必须)
        'now_page'  => $current,  #(必须)
        'list_rows' => $listRows, #(可选) 默认为15
    );
    $page = new Core_Lib_Page($params);
    return  $page->show_2();
}