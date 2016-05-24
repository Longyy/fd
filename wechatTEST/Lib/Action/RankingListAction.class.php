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

// 排行榜
class RankingListAction extends Action
{
    public function index()
    {
//        dump(strtotime(date("Y-m-d",strtotime("-1 day"))));
//        dump(strtotime('2015-9-23'));
        //获取上一天的时间戳
        $pre_day = strtotime(date("Y-m-d", strtotime("-1 day")));
        //使用时间计算
        $fans_time        = M('fans_time');
        $user_time_sql    = "select round(sum(end_time-begin_time)/60) as times from cms_fans_time t1 where mark_date=1442937600 and open_id='123'";
        $user_time_result = $fans_time->query($user_time_sql);
        $this->user_time  = $user_time_result[0]['times'];
        //打败分数计算
        //昨天使用硬件总的粉丝数
        $fans_count_sql    = "select count(*) as count_num from ( select open_id from cms_fans_time where mark_date=1442937600 group by open_id)t";
        $fans_count_result = $fans_time->query($fans_count_sql);
        $fans_count        = $fans_count_result [0]['count_num'];
        //昨天打到粉丝数
        $fans_low_count_sql    = "select count(*) as count_num from ( select open_id,sum(end_time-begin_time) as times from cms_fans_time t1 where mark_date=1442937600 group by open_id having times<(select sum(end_time-begin_time) from cms_fans_time where open_id='12'))t";
        $fans_low_count_result = $fans_time->query($fans_low_count_sql);
        $fans_low_count        = $fans_low_count_result [0]['count_num'];
        $this->fans_perc       = round(($fans_low_count / $fans_count) * 100);

        //计算昨日排行榜
        //计算使用时间最多分钟数
        $max_min_sql    = "select  round(sum(end_time-begin_time)/60) as count_time from  cms_fans_time where mark_date='1442937600' group by open_id order by count_time desc limit 1";
        $max_min_result = $fans_time->query($max_min_sql);
        $this->max_min  = $max_min_result [0]['count_time'];
        //计算使用时间最少分钟数
        $min_min_sql    = "select  round(sum(end_time-begin_time)/60) as count_time from  cms_fans_time where mark_date='1442937600' group by open_id order by count_time limit 1";
        $min_min_result = $fans_time->query($min_min_sql);
        $this->min_min  = $min_min_result [0]['count_time'];
//        dump($this->max_min);
//        dump($this->min_min);
        //计算昨日使用时间排序
        $rank_list_sql          = "select round(sum(end_time-begin_time)/60) as times,t1.open_id,t2.nick_name,t2.head_img_url from cms_fans_time t1  left join cms_fans t2 on t1.open_id=t2.open_id
where mark_date=1442937600 group by t1.open_id,t2.nick_name,t2.head_img_url order by times desc";
        $this->rank_list_result = $fans_time->query($rank_list_sql);
//        dump($this->rank_list_result );
        $this->display();
    }

    public function week()
    {
        //获取x轴数据
        for ($i = 0; $i < 7; $i++) {
            $date[] = date('m-d', strtotime("-$i day"));
        }
        $this->x_date = json_encode($date);
        $fans_time = M('fans_time');

        //查询一周的使用记录
        $sql = '';
        for ($i = 0; $i < 7; $i++) {
            $current_day = strtotime(date("Y-m-d", strtotime("-$i day")));
            $sql .= "select round(sum(end_time-begin_time)/60) as count_min from cms_fans_time where open_id='123' and mark_date='$current_day'" . " union all ";
        }
        $result = $fans_time->query(substr($sql, 0, -10));
        $i=0;
        foreach ($result as $val) {
            $current_day = date("m-d", strtotime("-$i day"));
            $min = $val['count_min'] == null ? "0" : $val['count_min'];
            $min_data[] =$min;
            $list_data[]=array($min,$current_day);
            $i--;
        }
        $this->min_data=json_encode($min_data);
        $this->list_data=$list_data;
        $this->title = '阅读报告';
        $this->display();
    }
}
