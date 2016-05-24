<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/4/29
 * Time: 14:47
 */
?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no"/>
    <title>高端夏令营</title>
    <script language="JavaScript" src="js/jquery-1.11.0.min.js" type="text/javascript"></script>
    <script language="JavaScript" src="js/bootstrap.min.js" type="text/javascript"></script>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js" type="text/javascript" charset="utf-8"></script>
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">

    <script language="javascript" type="text/javascript">
        /*     width_screen=screen.width;
         height_screen=screen.height;
         availWidth_screen=screen.availWidth;
         availHeight_screen=screen.availHeight;
         colorDepth_screen=screen.colorDepth;
         document.write("你的屏幕宽为："+width_screen+"<br />你的屏幕高为："+height_screen+"<br />你的屏幕可用宽为："+availWidth_screen+"<br />你的屏幕可用高为："+availHeight_screen+"<br />你的颜色设置所有为数为："+colorDepth_screen);
         */
    </script>
    <style type="text/css">
        h2{color: #000000;font-size: 20px; font: '微软雅黑', '黑体'}
        h4{color: #000000;font-size: 17px; font: '微软雅黑', '宋体'}
        p{color: #000000;font-size: 14px;font: '微软雅黑', '宋体'}
        font{color: red}
        img{height:140px;margin-top: 5px;width:95%;algin:center}
    </style>
</head>
<body bgcolor="#CCDDCC">

<h1 align="center">2015美国哈佛大学领导力高端夏令营14天</h1>
<h2>哈佛大学</h2>
<p>哈佛大学（Harvard University），简称哈佛，坐落于美国马萨诸塞州剑桥市，是一所享誉世界的私立研究型大学，是著名的常春藤盟校成员；这里走出了8位美利坚合众国总统，上百位诺贝尔获得者曾在此工作、学习，其亦培养了62名富豪企业家及335位罗德学者，人数均为全美最多。其在文学、医学、法学、商学等多个领域拥有崇高的学术地位及广泛的影响力，被公认为是当今世界最顶尖的高等教育机构之一。</p>
<h2>行程特色:</h2>
<ol>
    <li><p>美国<font >哈佛大学专业教授全程授课</font>，质量保证</p></li>
    <li><p>各大常春藤盟校学生面对面<font >分享经验</font></p></li>
    <li><p>与美国当地管理者一同工作，体验难忘的<font>志愿者经历</font></p></li>
    <li><p>授课期间在哈佛广场的<font>UNO餐厅和FIRE+ICE餐厅</font>宾夕法尼亚大学丶西点军校感受美国高校氛围，为未来埋下梦想的种子</p></li>
    <li><p>游历美国首都<font>【华盛顿】</font>，参观地标性建筑【白宫】丶【国会山庄】</p></li>
    <li><p>游览美国的第一大中心城市<font>【纽约】</font></p></li>
</ol>
<h2>行程介绍</h2>
<?php

$day;$address;$them;$schoolTime1;$schoolTime2;$schoolTime3;$imagepah;

for($i=0; $i<14 ; $i++){
    switch($i){

        case 0:
            echo "<h4 align=\"center\">第一天</h4>
                    <p>机场集合，乘直航豪华客机飞往美国纽约，晚餐过后，抵达入后住酒店休息。</p>
                    <h2>哈佛讲座课程</h2>
                    <p>通过四个系列讲座，学生会在理论和实践的基础训练中，更加了解叙述方式对我们生活的重要影响，以及如何更有效的表达自己从而提升领导能力。讲故事是我们理解这个世界最主要的论证工具，是我们联系因果关系的基本工具，以及我们揭露掩藏的人类行为动机的最有力工具。在更加深远的意义上，讲故事的艺术让我们练习如何将自身行为以及对于他人的认知感进行换位思考。整个讲座会贯穿一系列各种各样的小故事。故事涵盖从口头传说到书面资料，从亚里士多德的哲学到弗洛伊德的哲学，从荣格的心理学到威廉·詹姆士的心理学，从乔治·莱考夫的思维理论到本尼迪克·安德森的思维理论。 学生需要准备好纸笔来做书面及口语练习。课堂内禁止录音或使用笔记本电脑。</p>
                   <h3><p>讲座一：领导力问题</p></h3>
                   <p>在这个讲座中我们会看到为何简单的领导模式会失败，以及对于时间和文化的叙述是如何启发和塑造人类个体和集体成果的。</p>
                   <h3><p>讲座二：如何让故事更精彩</p></h3>
                   <p>在这个和教授互动的课程中，我们会一起学习如何让一个故事成为一个生动的好故事，给人留下深刻的印象。</p>
                   <h3><p>讲座三：人物与创作</p></h3>
                   <p>本讲座我们会探讨虚构小说和纪实文学之间的区别和联系，人类创造出的事物和自然界本真的事物之间的区别和联系，以及语言是如何帮助跨越这些分界线的。</p>
                   <h3><p>讲座四：风格，自我表达，和改变</p></h3>
                   <p>本讲座帮助重温领导力主题，让我们认识到那些在叙述风格、声音、措辞和比喻上貌似很小的决定是如何对我们与他人的沟通产生深远的影响的，以致改变整个世界。</p>

                    <p style=\"text-align: center\">
                    <img src=\"image/twooneday1.png\"  /></p>";

            break;
        case 1:
            $day = "第二天";
            $address = "地点:波士顿";
            $them = "开营仪式和哈佛定向越野";
            $schoolTime1 = "上午：开营仪式、新生欢迎会";
            $schoolTime2 = "下午：“Scavenger Hunt” 哈佛定向越野";
            $schoolTime3 = "教室：哈佛教室";
            $imagepah = "image/onetwoday2.png";
            break;
        case 2:
            $day = "第三天";
            $address = "地点:波士顿";
            $them = "领导和创新一";
            $schoolTime1 = "教授：哈佛专业教授";
            $schoolTime2 = "演讲题目：领导力,创新与创造力第一部分";
            $schoolTime3 = "教室：哈佛教室";
            $imagepah = "image/twooneday3.png";
            break;
        case 3:
            $day = "第四天";
            $address = "地点:波士顿";
            $them = "领导和创新二";
            $schoolTime1 = "教授：哈佛专业教授";
            $schoolTime2 = "演讲题目：领导力,创新与创造力第二部分";
            $schoolTime3 = "教室：哈佛教室";
            $imagepah = "image/twooneday4.png";
            break;
        case 4:
            $day = "第五天";
            $address = "地点:波士顿";
            $them = "领导和创新三";
            $schoolTime1 = "教授：哈佛专业教授";
            $schoolTime2 = "演讲题目：领导力,创新与创造力第三部分";
            $schoolTime3 = "教室：哈佛教室";
            $imagepah = "image/twooneday5.png";
            break;
        case 5:
            $day = "第六天";
            $address = "地点:波士顿";
            $them = "志愿活动和美国独立故事";
            $schoolTime1 = "上午：美国有56%人是志愿者，参与志愿者活动了解社会责任心并从中可以学习到课本以外的知识。我们的志愿者活动有到波士顿动物园，波士顿公共花园，食品配发工坊，学校...等等";
            $schoolTime2 = "下午：参观世界顶级理工大学没有之一的【麻省理工学院】和参观波士顿市区著名景点随着著名的波士顿自由之路了解美国独立的故事";
            $schoolTime3 = "";
            $imagepah = "image/twooneday6.png";
            break;
        case 6:
            $day = "第七天";
            $address = "地点:中国→波士顿";
            $them = "领导和创新四";
            $schoolTime1 = "教授：哈佛专业教授";
            $schoolTime2 = "演讲题目：领导力.创新力与创造力第四部分";
            $schoolTime3 = "教室：哈佛教室";
            $imagepah = "image/twooneday7.png";
            break;
        case 7:
            $day = "第八天";
            $address = "地点:波士顿→耶鲁→纽约（车程共4小时，每站相隔2小时）";
            $them = "参观耶鲁大学";
            $schoolTime1 = "早餐后從波士顿前往纽约途径康涅狄格州纽黑文市，参观【耶鲁大学】，后乘车前往纽约。抵达后入住酒店休息。";
            $schoolTime2 = "";
            $schoolTime3 = "";
            $imagepah = "image/twooneday8.png";
            break;
        case 8:
            $day = "第九天";
            $address = "地点:纽约";
            $them = "游玩各大旅游景点";
            $schoolTime1 = "早餐后,乘船参观美国精神的象征—【自由女神像】(1 小时乘船游览)丶世界的金融中心【华尔街】";
            $schoolTime2 = "(下车参观半小时)丶【时代广场】(下车参观约 2 小时)丶【联合国总部大厦】(外观)丶【洛克菲勒中心】(外观约 30 分钟)丶【第五大道】(下车游览约 2 小时)";
            $schoolTime3 = "";
            $imagepah = "image/twooneday9.png";
            break;
        case 9:
            $day = "第十天";
            $address = "地点:纽约→西点军校→ Woodbury Outlets奥特莱斯（约2小时车程）";
            $them = "最悠久的军事学院和最大的奥特莱斯";
            $schoolTime1 = "早起迎接全新的早晨，参观世界闻名的西点军校，该校是美国历史最悠久的军事学院之一。";
            $schoolTime2 = "下午抵达美东最大的Outlet, Woodbury Outlet 疯狂shopping! Day11：";
            $schoolTime3 = "";
            $imagepah = "image/twooneday10.png";
            break;
        case 10:
            $day = "第十一天";
            $address = "地点:纽约→普林斯顿→宾州大学→华盛顿（每站相隔1.5小时）";
            $them = "参观美国各大名校";
            $schoolTime1 = "参观美国常春藤8大名校其中的【宾夕法尼亚大学】和【普林斯顿大学】（每所学校参观交流1.5小时）。常春藤盟校（Ivy League）指的是由美国东北部地区的八所大学组成的美国一流名校、也是美国产生最多罗德奖学金得主的高校联盟。此外，建校时间长，八所学校中的七所是在英国殖民时期建立的。这八所院校包括：哈佛大学、耶鲁大学、普林斯顿大学、哥伦比亚大学、宾夕法尼亚大学、达特茅斯学院、布朗大学及康奈尔大学。";
            $schoolTime2 = "";
            $schoolTime3 = "";
            $imagepah = "image/twooneday11.png";
            break;
        case 11:
            $day = "第十二天";
            $address = "地点:华盛顿";
            $them = "参观华盛顿各大景点";
            $schoolTime1 = "上午游览华盛顿巍然屹立的【华盛顿纪念碑】(外观)丶【白宫】（外观）丶【国会山庄】(外观)丶【林肯纪念堂】丶【越战军人纪念碑】丶【韩战纪念碑】丶【杰弗逊纪念堂】丶感受美国的历史与闻名。";
            $schoolTime2 = "午后前往着名的【航天航空博物馆】进行参观,它是世界上航空和航天科学技术方面收藏品最丰富的博物馆，";
            $schoolTime3 = "";
            $imagepah = "image/twooneday12.png";
            break;
        case 12:
            $day = "第十三天";
            $address = "地点:华盛顿→中国";
            $them = "恋恋不舍";
            $schoolTime1 = "早上前往波士顿机场离开，搭乘航空返回中国。";
            $schoolTime2 = "";
            $schoolTime3 = "";
            $imagepah = "image/twooneday13.png";
            break;
        case 13:
            $day = "第十四天";
            $address = "地点:中国";
            $them = "回家";
            $schoolTime1 = "抵达中国，结束愉快的美国之旅 ";
            $schoolTime2 = "";
            $schoolTime3 = "";
            $imagepah = "";
            break;
    }
    if($i!=0){
        setView($day,$address,$them,$schoolTime1,$schoolTime2,$schoolTime3,$imagepah);
    }
}

function setView($day,$address,$theme,$schoolTime1,$schoolTime2,$schoolTime3,$imagepah){
    $data="<h4 align=\"center\">$day</h4>
            <p>$address</p>";

    if(!empty($theme)){
        $data = $data."<h2>$theme</h2><ul>";
    }else{
        $data = $data."<ul>";
    }
    if(!empty($schoolTime1)){
        $data = $data."<li><p>$schoolTime1</p></li>";
    }
    if(!empty($schoolTime2)){
        $data = $data."<li><p>$schoolTime2</p></li>";
    }
    if(!empty($schoolTime3)){
        $data = $data."<li><p>$schoolTime3</p></li>";
    }
    if(!empty($imagepah)){
        $data = $data." </ul><p style=\"text-align: center\"> <img src=$imagepah /></p>";
    }else{
        $data = $data." </ul> ";
    }
    echo $data;
}
?>
<h2 align="center">费用包含</h2>
<ol >
    <li><p>机票、签证费用</p></li>
    <li><p>境外游览巴士</p></li>
    <li><p>境外人身保险</p></li>
    <li><p>境外营队费用</p></li>
    <li><p>学习费用</p></li>
    <li><p>行程中景点门票</p></li>
    <li><p>住宿、餐饮费用 （赠送一顿龙虾餐）</p></li>
    <li><p>导游服务费</p></li>
    <li><p>全程司机导游小费</p></li>
    <li><p>夏令营结业证书</p></li>
</ol>
<p><font>备注：所有用餐及行程中列出景点门票都已包含，不参加者不另行退费。</font></p>

<h2 align="center">费用不包含</h2>
<ol>
    <li><p>行程列明以外的景点或活动所引起的任何费用</p></li>
    <li><p>护照办理费用、行李托运或超重费用</p></li>
    <li><p>司导超时工作费用</p></li>
    <li><p>一切私人费用，如洗衣、电话、传真、上网、收费电视节目、游戏、宵夜、机场和酒店行李搬运服务、购物等费用</p></li>
</ol>
<h2 align="left">招生对象：初中及高中、大学在校学生</h2>
<p><font>备注：</font></p>
<ol>
    <li><p>我公司保留在不减少景点的情况下调整行程顺序的权利</p></li>
    <li><p>一日三餐时间如在飞机或者机场，用餐自理</p></li>
    <li><p>因不可抗力的客观原因和非我公司原因（如天灾、战争、罢工等）或航空公司
            航班延误、取消、使领馆签证延误等情况，我公司有权变更行程，一切超出费用
            （如：在境外延期的签证费、住宿、餐食、交通等费用），均由客人自理</p></li>
    <li><p>团组分房为同性，如遇自然单间由住宿单间的团员自行承担单间差</p></li>
</ol>
<h2><font >费用：￥45800.00</font></h2>
<h2>联系方式</h2>
<p>地址：上海市徐汇区虹桥路808号A6-205</p>
<p> 电话：<a href="tel:021-6111 9800">021-6111 9800</a> </p>
</br></br>
</body>
</html>