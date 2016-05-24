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
    <title>美国哈佛ESL课程</title>
    <script language="JavaScript" src="js/jquery-1.11.0.min.js" type="text/javascript"></script>
    <script language="JavaScript" src="js/bootstrap.min.js" type="text/javascript"></script>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js" type="text/javascript" charset="utf-8"></script>
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">

    <style type="text/css">
        h2{color: #000000;font-size: 20px; font: '微软雅黑', '黑体'}
        h4{color: #000000;font-size: 17px; font: '微软雅黑', '宋体'}
        p{color: #000000;font-size: 14px;font: '微软雅黑', '宋体'}
        font{color: red}
        img{height:140px;margin-top: 5px;width:95%;algin:center}
    </style>
</head>
<body  bgcolor="#CCDDCC">

<h1 align="center">2015美国哈佛大学 ESL 课程夏令营</h1>
<h2>哈佛大学</h2>

<p>哈佛大学（Harvard University），简称哈佛，坐落于美国马萨诸塞州剑桥市，是一所享誉世界的私立研究型大学，是著名的常春藤盟校成员；这里走出了8位美利坚合众国总统，上百位诺贝尔获得者曾在此工作、学习，其在文学、医学、法学、商学等多个领域拥有崇高的学术地位及广泛的影响力，被公认为是当今世界最顶尖的高等教育机构之一。</p>

<h2>夏令营</h2>
<p>我们希望所有辛辛学子们有一个更好的练习英文，此团为全英文交流团。参加夏令营的学生，透过上课，培训，和游学了解到行万里路胜过读万卷书的意义。在学习的过程中，我们安排学生住宿在环境优美的大学校附近、参加不同的活动，体验文化上的差异，提升学生们的独立生活的训练，让学生在英文学科上，生活上以及国际观上有一个丰富以及难忘的旅程。美国游学将美国旅游和语言学习结合在一起，带您游遍美国各大城市和参观顶级高等学府，近距离接触美国文化和了解美国教育体制。</p>
<h2>行程特色:</h2>
<ol>
    <li><p>哈佛大学<font >ELS Language Centers全程授课</font>，质量保证</p></li>
    <li><p>与美国当地管理者一同工作，体验难忘的<font >志愿者经历</font></p></li>
    <li><p>授课期间在<font>ＵＮＯ餐厅，哈佛FIRE+ICE餐厅</font>享受地道美国餐体验美式生活</p></li>
    <li><p>走访<font>哈佛大学丶麻省理工大学丶耶鲁大学丶宾夕法尼亚大学丶西点军校</font>感受美国高校氛围，为未来埋下梦想的种子。各大名校学生面对面<font>分享经验</font></p></li>
    <li><p>游历美国首都<font>【华盛顿】</font>，参观地标性建筑【白宫】丶【国会山庄】</p></li>
    <li><p>游览美国的第一大中心城市<font>【纽约】</font></p></li>
</ol>

<?php

$day;$address;$them;$schoolTime1;$schoolTime2;$schoolTime3;$imagepah;

for($i=0; $i<14 ; $i++){
    switch($i){

        case 0:
            echo "<h4 align=\"center\">第一天</h4>
                    <p>机场集合，乘豪华客机飞往美国波士顿，晚餐过后，入住酒店休息</p>
                    <h2>哈佛ESL讲座课程</h2>
                    <p>自 1961 年以来，已有来自超过 175 个国家和地区，数以百万计的学生选择就读 ELS Language Centers，将其视为快速学习英语的最佳方式。在美国 ELS Language Centers 学习英语将是您一生中最精彩丶最令人难忘的经验之一。精通英语将为您提供通往教育丶商业及个人发展之无限机会的重要通行证。</p>
                    <p><u>ELS Language Centers 由其各运作地点所在国的主管机关所认可</u>></p>
                    <ul >
                        <li><p>在美国的 ELS Language Centers 已通过进修教育与训练认证协会（Accrediting Council forContinuing Education & Training, ACCET）的认证，该委员会是美国教育部指定的全国认证机构</p></li>
                        <li><p>ELS 已获得批准，可提供下列课程：密集课程丶半密集课程丶TOEFL ® iBT完全应试准备课程丶美国游学课程丶商务英语课程丶CELTA 及特别课程。本校由联邦法律授权，可招收非移民外国学生</p></li>
                    </ul>
                    <p style=\"text-align: center\">
                    <img src=\"image/onetwoday1.png\"  /></p>";

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
            $them = "英语学习方法";
            $schoolTime1 = "上午ESL：常规交际英语口语与正式英语用语";
            $schoolTime2 = "下午讲座：提高沟通技巧与能力";
            $schoolTime3 = "教室：哈佛教室";
            $imagepah = "image/onetwoday3.png";
            break;
        case 3:
            $day = "第四天";
            $address = "地点:波士顿";
            $them = "英语和文化";
            $schoolTime1 = "上午ESL：如何培养英语听力";
            $schoolTime2 = "下午讲座：中美文化差异与世界文化大融合";
            $schoolTime3 = "教室：哈佛教室";
            $imagepah = "image/onetwoday4.png";
            break;
        case 4:
            $day = "第五天";
            $address = "地点:波士顿";
            $them = "志愿活动和了解美国";
            $schoolTime1 = "美国有56%人是志愿者，参与志愿者活动了解社会责任心并从中可以学习到课本以外的知识。我们的志愿者活动有到波士顿动物园，波士顿公共花园，食品配发工坊，学校...等等";
            $schoolTime2 = "下午：参观世界顶级理工大学没有之一的【麻省理工学院】和参观波士顿市区著名景点随着著名的波士顿自由之路了解美国独立的故事";
            $schoolTime3 = "";
            $imagepah = "image/onetwoday5.png";
            break;
        case 5:
            $day = "第六天";
            $address = "地点:波士顿";
            $them = "如何培养学习和全球经济化";
            $schoolTime1 = "上午ESL：如何培养良好的英语阅读习惯";
            $schoolTime2 = "下午讲座：经济全球化要求人才国际化";
            $schoolTime3 = "教室：哈佛教室";
            $imagepah = "image/onetwoday6.png";
            break;
        case 6:
            $day = "第七天";
            $address = "地点:中国→波士顿";
            $them = "结业典礼、毕营仪式";
            $schoolTime1 = "上午讲座：招生老师讲解学校如何选择学生，学生如何申请美国学校";
            $schoolTime2 = "下午：结业典礼、毕营仪式";
            $schoolTime3 = "教室：哈佛教室";
            $imagepah = "image/onetwoday7.png";
            break;
        case 7:
            $day = "第八天";
            $address = "地点:波士顿→耶鲁→纽约（车程共4小时，每站相隔2小时）";
            $them = "参观各大名校";
            $schoolTime1 = "早餐后從波士顿前往纽约途径康涅狄格州纽黑文市，参观常春藤盟校之一与哈佛不相上下的美国总统大学【耶鲁大学】，后乘车前往纽约。抵达后入住酒店休息。";
            $schoolTime2 = "";
            $schoolTime3 = "";
            $imagepah = "image/onetwoday8.png";
            break;
        case 8:
            $day = "第九天";
            $address = "地点:纽约";
            $them = "游玩各大旅游景点";
            $schoolTime1 = "早餐后，乘船参观美国精神的象征—【自由女神像】（1小时乘船游览）丶世界的金融中心【华尔街】（下车参观半小时）丶【时代广场】（下车参观约2小时）丶【联合国总部大厦】（外观）丶【洛克菲勒中心】（外观约30分钟）丶【第五大道】（下车游览约2小时）";
            $schoolTime2 = "";
            $schoolTime3 = "";
            $imagepah = "image/onetwoday9.png";
            break;
        case 9:
            $day = "第十天";
            $address = "地点:纽约→西点军校→ Woodbury Outlets奥特莱斯（约2小时车程）";
            $them = "最悠久的军事学院和最大的奥特莱斯";
            $schoolTime1 = "早起迎接全新的早晨，参观世界闻名的西点军校，该校是美国历史最悠久的军事学院之一。下午抵达全美国最大的奥特莱斯, Woodbury Outlets 疯狂shopping!";
            $schoolTime2 = "";
            $schoolTime3 = "";
            $imagepah = "image/onetwoday10.png";
            break;
        case 10:
            $day = "第十一天";
            $address = "地点:纽约→普林斯顿→宾州大学→华盛顿（每站相隔1.5小时）";
            $them = "美国德奖学金得主的高校联盟";
            $schoolTime1 = "参观美国常春藤8大名校其中的【宾夕法尼亚大学】和【普林斯顿大学】（每所学校参观交流1.5小时）。常春藤盟校（Ivy League）指的是由美国东北部地区的八所大学组成的美国一流名校、也是美国产生最多罗德奖学金得主的高校联盟。此外，建校时间长，八所学校中的七所是在英国殖民时期建立的。这八所院校包括：哈佛大学、耶鲁大学、普林斯顿大学、哥伦比亚大学、宾夕法尼亚大学、达特茅斯学院、布朗大学及康奈尔大学。";
            $schoolTime2 = "";
            $schoolTime3 = "";
            $imagepah = "image/onetwoday11.png";
            break;
        case 11:
            $day = "第十二天";
            $address = "地点:华盛顿";
            $them = "参观华盛顿各大景点";
            $schoolTime1 = "上午游览华盛顿巍然屹立的【华盛顿纪念碑】(外观)丶【白宫】（外观）丶【国会山庄】(外观)丶【林肯纪念堂】丶【越战军人纪念碑】丶【韩战纪念碑】（林肯+越韩战纪念碑共约2小时）丶【杰弗逊纪念堂】（约半小时）丶感受美国的历史与闻名。午后前往着名的【航天航空博物馆】（约2小时）进行参观,它是世界上航空和航天科学技术方面收藏品最丰富的博物馆。";
            $schoolTime2 = "";
            $schoolTime3 = "";
            $imagepah = "image/onetwoday12.png";
            break;
        case 12:
            $day = "第十三天";
            $address = "地点:华盛顿→中国";
            $them = "恋恋不舍";
            $schoolTime1 = "早上前往华盛顿机场离开返回中国。 ";
            $schoolTime2 = "";
            $schoolTime3 = "";
            $imagepah = "image/onetwoday13.png";
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
<h2><font >费用：￥41800.00</font></h2>
<h2>联系方式</h2>
<p>地址：上海市徐汇区虹桥路808号A6-205</p>
<p> 电话：<a href="tel:021-6111 9800">021-6111 9800</a> </p>
</br></br>
</br></br>
</body>
</html>