<?php
session_start();
require_once("product.class.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<link rel="stylesheet" href="css/calendar.css" />
<script src="js/jquery.min.js"></script>
<script>
alert('活动已结束');
</script>
<title>惠民特价票</title>
</head>
<style type="text/css">
body {
	background-color: #f5f5f5;
}
.font1 {
	font-family: "微软雅黑";
	font-size: 14px;
	color: #cecece
}
.font2 {
	font-family: "微软雅黑";
	font-size: 16px;
	color: #282828
}
.font2 a {
	font-family: "微软雅黑";
	font-size: 16px;
	color: #282828
}
.font3 {
	font-family: "微软雅黑";
	font-size: 16px;
	color: #6db546
}
.font4 {
	font-family: "微软雅黑";
	font-size: 16px;
	color: #ff5a00
}
.font5 {
	font-family: "微软雅黑";
	font-size: 18px;
	color: #ff5a00
}
.font6 {
	font-family: "微软雅黑";
	font-size: 16px;
	color: #a0a0a0
}
a:link {
	text-decoration: none;
}
a:visited {
	text-decoration: none;
}
a:hover {
	text-decoration: none;
}
a:active {
	text-decoration: none;
}
</style>

<body>
<!--选时间--> 
<!--<div class="font1" style="width:100%; height:60px; line-height:60px;  border-bottom:solid 1px #d9d9d9; background-color:#FFF; ">
<div style="float:left;">&nbsp;&nbsp;入住<span class="font2">12月20日</span> 今天 |离店<span class="font2">12月21日</span> 明天</div>
<div style="float:right; width:8%; height:60px; padding-top:15px;"><img src="images/kf_1.png" width="100%"/></div>
</div>-->
<div class="font5" style="width:100%; height:40px; line-height:40px; background-color:#FFF; text-align:center;">康溪温泉惠民特价票</div>
<div style="width:100%; height:150px;"><img src="images/banner.jpg" width="100%" height="160"></div>
<div class="font2" style="width:100%; height:50px; line-height:50px; background-color:#FFF;">&nbsp;<span class="font2" style="width:100%; height:40px; line-height:40px; background-color:#FFF; margin-top:10px;">地址：本溪经济技术开发区孙思邈大街62号</span></div>
<div style="width:100%; height:10px; "></div>

<!--列表-->
<div style="width:100%; background-color:#FFF;  border-bottom:solid 1px #d9d9d9; text-align:center;  padding-top:5px; padding-bottom:5px;">
 
  <table width="95%"  cellpadding="0" cellspacing="0" style="margin:0 auto">
    <tr>
      <td width="8%"  align="left" class="font2"><img src="images/2.png" width="100%" ></td>
      <td  align="left" class="font2"><span style="float:left; padding-left:10px;">门票</span></td>
      <td  align="right" class="font2"><span style="float:right; padding-left:10px;"><a href="goupiaojilu.php">购票记录</a></span></td>
    </tr>
  </table>

</div>
<div style="width:100%; margin:auto; background-color:#FFF;  border-bottom:solid 1px #d9d9d9;  text-align:center;" onClick="sel()">

  <table width="95%"  cellpadding="0" cellspacing="0" style="margin:0 auto">
    <tr>
      <td  align="left" class="font2">
	  <div style="text-align:left">
          <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
             
              <td width="75%"  align="left" class="font2"><div style="text-align:left"><img src="images/1.png" width="12" height="12">惠民特价票</div></td>
              <td width="20%" rowspan="4" align="right"><img src="images/kf_2.png" width="100%" /></td>
            </tr>
            <tr align="left">
              <td  class="font3"><div style="text-align:left">有效期：2018.1.18-2018.2.14</div></td>
            </tr>
            <tr align="left">
              <td><div style="text-align:left"><span class="font4"> 单价</span><span class="font5"><span class="font4">￥</span>99</span></div></td>
            </tr>
          </table>
        </div></td>
    </tr>
  </table>

</div>

<div class="font5" style="width:100%; height:40px; line-height:40px; background-color:#FFF;">&nbsp;&nbsp;购买须知</div>
<div style="width:100%;background-color:#FFF; text-align:center; ">
<div class="font6" style="width:95%; margin:0 auto; text-align:left; ">
1、持沈阳、本溪、鞍山、抚顺、辽阳身份证（原件）；</br>
2、有效电话号码，即使用本人身份证办理的电话号码，身份证与办理电话号码的身份证一致，称之为有效电话号码；</br>
3、在2016年、2017年已经享受过惠民政策的游客不能重复享受惠民政策；</br>
4、享受惠民政策的年龄界限为16周岁-70周岁；</br>
5、身份证地址为康平、法库、辽中、新民，在区域划分上归沈阳管的区域均可；</br>
6、身份证地址为沈阳居民，但是身份证号码为非沈阳身份证号码，视为沈阳居民，为本次活动有效证件；</br>
7、此票为成人自驾门票1张，不含餐，自助餐59元/人，景区自行购买；</br>
8、如果经验证不符合申领条件或者已经申领，需要在景区前台补交40元差价；</br>
9、本度假区在此承诺身份证件及有效电话号码，均用于沈阳市旅游委举办的本次惠民活动；
</div>
</div>
<div style="height:10px; width:100%;"></div>


</body>
</html>
