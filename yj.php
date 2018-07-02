<?php
define(DB_HOST, 'localhost');  
define(DB_USER, 'root');  
define(DB_PASS, 'symiao');
$databasename=$_GET['databasename'];  
define(DB_DATABASENAME,$databasename);   
$conn = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die("connect failed" . mysql_error());
mysql_select_db(DB_DATABASENAME, $conn);
mysql_query("SET NAMES 'utf8'");
$uid=$_GET['uid'];
$activityid=$_GET['activityid'];
$proportion=$_GET['proportion'];
$sql = "select sum(tixian_money) as tixian_money from tixian where user_id=".$uid."";
$res = mysql_query($sql);
if($res)
{
	$row =mysql_fetch_array($res);
	if($row)
	{
		
		$tixian_money = $row['tixian_money'];
		if(is_null($tixian_money))
		{
			$tixian_money=0;
		}
		
	}
}
$sql = "select count(*) as cnt_num,sum(s.product_num) as sum_num,sum(o.real_price) as total_price from wq_order as o left join wq_subOrder as s on o.id=s.order_id where  o.status
IN (1,4,5) and user_id='".$uid."'";
$res = mysql_query($sql);
if($res)
{
	$row =mysql_fetch_array($res);
	if($row)
	{
		$cnt_num = $row['cnt_num'];
		$sum_num = $row['sum_num'];
		$total_price=$row['total_price'];
	}
}
$sql1 = "select sum(s.product_num) as total_num from wq_order as o left join wq_subOrder as s on o.id=s.order_id where user_id='".$uid."' and status in(1,4,5)";
$ress = mysql_query($sql1);
if($ress)
{
	$rows =mysql_fetch_array($ress);
	if($rows)
    {
		$total_num = $rows['total_num'];
	}
} 
define(DB_DATABASENAMEs,'jj_wq_app');   
$conns = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die("connect failed" . mysql_error());
mysql_select_db(DB_DATABASENAMEs, $conns);
mysql_query("SET NAMES 'utf8'");
$sql = "select * from wq_user where id='".$uid."'";
$res = mysql_query($sql);
if($res)
{
	$row =mysql_fetch_array($res);
	if($row)
	{
		$direct_type=$row['direct_type'];
		$reg_from_admin=$row['reg_from_admin'];
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width,minimum-scale=1,maximum-scale=1">
<script type="text/javascript" src="./js/jquery.min.js"></script>
<title>交易记录</title>
</head>

<style type="text/css">
body {
	margin: 0px;
	background-color: #f5f5f5;
}
.font3 {
	font-family: "微软雅黑";
	font-size: 14px;
	line-height: 28px;
	color: #818181
}
.font9 {
	font-family: "微软雅黑";
	font-size: 18px;
	color: #ff0000
}
.font10 {
	font-family: "微软雅黑";
	font-size: 14px;
	line-height: 24px;
	color: #fff;
}
.font10 a {
	font-family: "微软雅黑";
	font-size: 14px;
	line-height: 24px;
	color: #fff;
}
.font11 {
	font-family: "微软雅黑";
	font-size: 24px;
	line-height: 24px;
	color: #000;
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
<script>
 function tixian(direct_type,reg_from_admin,fanmoney,uid,databasename,proportion)
 {
 	if(fanmoney<=0)
	{
		 document.getElementById('text').innerHTML="提现失败";
		 document.getElementById('detail').innerHTML="可提金额小于0";
		 locking();
		
	}
	else
	{
		$.ajax({
		type: "post",  //数据提交方式（post/get）
		url: "tixian.class.php",  //提交到的url
		data: {direct_type:direct_type,reg_from_admin:reg_from_admin,fanmoney:fanmoney,uid:uid,databasename:databasename,proportion:proportion},//提交的数据
		dataType: "json",//返回的数据类型格式
		success: function(msg){
			if(msg)
			{
				 document.getElementById('text').innerHTML="提现成功";
				 $("#tip_detail").hide();
				 locking();
				 
			}
			else
			{	
				 document.getElementById('text').innerHTML="提现失败";
				 document.getElementById('detail').innerHTML="可提金额小于0";
				 $("#tip_detail").show();		
				 locking();
		 		 
			}
			
		},
		error:function(msg){
		  alert("网络错误");
		}
		});	
	}
 	
 }
</script>

<script>   
  function    locking(){   
   document.all.ly.style.display="block";   
   document.all.ly.style.width=document.body.clientWidth + 'px';   
   document.all.ly.style.height=document.body.clientHeight +'px';   
   document.all.Layer2.style.display='block';  
   }   
  function    Lock_CheckForm(theForm){   
   document.all.ly.style.display='none';document.all.Layer2.style.display='none';
  return   false;   
   }   
</script>
<body>
<div id="ly" style="position: absolute; top: 0px; filter: alpha(opacity=60); background-color: rgba(36, 36, 36,0.9); z-index: 2; left: 0px; display: none;"></div>   
<div style="width:100%; height:20px;"></div>
<div style="width:100%; "><img src="images/yj_banner.png" width="100%" /></div>
<div style="width:100%; height:20px;"></div>
<div style=" width:100%;  background-color:#FFF">
  <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0"  style="border-bottom:solid 1px #d9d9d9; background-color:#FFF;">
    <tr>
      <td width="25%" height="60" class="font3">成交人数</td>
      <td align="left" class="font9"><?php if($cnt_num!=0):?><?php echo $cnt_num?><?php else:?>0<?php endif;?>人</td>
    </tr>
  </table>
  <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0"  style="border-bottom:solid 1px #d9d9d9; background-color:#FFF;">
    <tr>
      <td width="25%" height="60" class="font3">成交票数</td>
      <td align="left" class="font9"><?php if($sum_num!=0):?><?php echo $sum_num?><?php else:?>0<?php endif;?>人</td>
    </tr>
  </table>
  <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0"  style="border-bottom:solid 1px #d9d9d9; background-color:#FFF;">
    <tr>
      <td width="25%" height="60" class="font3">成交金额</td>
      <td align="left" class="font9">￥<?php if($total_price!=0):?><?php echo $total_price?><?php else:?>0.00<?php endif;?></td>
    </tr>
  </table>
   <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0"  style="border-bottom:solid 1px #d9d9d9; background-color:#FFF;">
    <tr>
      <td width="25%" height="60" class="font3">返点金额</td>
      <td align="left" class="font9">￥<?php if($total_num!=0):?><?php echo $total_num*$proportion?><?php else:?>0.00<?php endif;?></td>
    </tr>
  </table>
  <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0"  style="border-bottom:solid 1px #d9d9d9; background-color:#FFF;">
    <tr>
      <td width="25%" height="60" class="font3">已提金额</td>
      <td align="left" class="font9">￥<?php if($tixian_money!=0):?><?php echo $tixian_money?><?php else:?>0.00<?php endif;?></td>
    </tr>
  </table>
</div>
<div style="width:100%; height:20px;"></div>
<div style="width:100%; height:100px;"></div>
<!--<div style="width:100%; height:90px; position: fixed;bottom: 0px;font-size: 0;line-height: 0;z-index: 100;""></div>-->
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="position: fixed; bottom: 0; z-index:100;" >
  <tr>
    <td width="50%"  class="font51"><a href="https://www.kxhotspring.com/api/Wechat/actives/A001/mx.php?uid=<?php echo $uid?>&activityid=<?php echo $activityid?>&databasename=<?php echo $databasename?>&proportion=<?php echo $proportion?>"><img src="images/yj_btn1.png"  width="100%" /></a></td>
    <td width="50%"  class="font51"><?php if($direct_type==1):?><img src="images/yj_btn2.png" width="100%" onclick="tixian('<?php echo $direct_type?>','<?php echo $reg_from_admin?>','<?php echo $total_num*$proportion-$tixian_money?>','<?php echo $uid?>','<?php echo $databasename?>','<?php echo $proportion?>')" /><?php else:?><img src="images/yj_btn3.png" width="100%" onclick="tixian('<?php echo $direct_type?>','<?php echo $reg_from_admin?>','<?php echo $total_num*$proportion-$tixian_money?>','<?php echo $uid?>','<?php echo $databasename?>','<?php echo $proportion?>')"/><?php endif;?></td>
  </tr>
</table>
<!--          浮层框架开始         -->
<div id="Layer2" align="center" style=" z-index: 3; display: none;" >
<div class="font4" style=" position:absolute;top:50%; left:20%;  width:60%; z-index:999;top:0; margin-top:10px; text-align:center; background-color:#FFF; ">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" bgcolor="#6db547" height="30px;">
    <div align="right" style="padding-right:10px;"><a href=JavaScript:; class="font10" onClick="Lock_CheckForm(this);">[关闭]</a></div>    
    </td>
  </tr>
  <tr>
    <td align="center"><div style="width:45%"><img src="images/tx_btn.png" width="100%" ></div></td>
  </tr>
  
  <tr>
    <td height="30px;" align="center" valign="top" class="font11" id="text" style=" padding-left:10px; padding-right:10px;">提现失败!</td>
  </tr>
    <tr>
    <td height="20px;" align="center" valign="top" class="font4" id="tip_detail" style=" padding-left:10px; padding-right:10px;">提示：<span id="detail">提现金额不能为零</span></td>
  </tr>
  <tr>
    <td align="center" height="20px;"></td>
  </tr>
</table>
</div></div>

</body>
