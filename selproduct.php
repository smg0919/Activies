<?php
session_start();
require_once ("db.php");
$product_id=26;
$sql="select * from wq_product where id='".$product_id."'";
$res = mysql_query($sql);
if($res)
{
	$row = mysql_fetch_array($res);
	{
		$id=$row['id'];
		$product_name=$row['product_name'];
		$play_date=$row['play_date'];
		$can_sel_date=$row['can_sel_date'];
		$wx_price=$row['wx_price'];
		$price=$row['price'];
		$fandianprice=$row['wx_price']-$row['price'];       //返点价格
		$zyb_product_id=$row['zyb_product_id'];
	}
}
$wx_userid=$_SESSION['wx_userid'];
$sql="select * from wx_users where id='".$wx_userid."'";
$res = mysql_query($sql);
if($res)
{
	while($row = mysql_fetch_array($res))
	{
		$name=$row['name'];
		$phone=$row['phone'];
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width,minimum-scale=1,maximum-scale=1">
<script src="js/jquery.min.js"></script>
<script>
function checkNum (val,price) {
    document.getElementById('num').value = val >= 1 ? val : 1; 
    if(val<=0)
    {
    	$("#realprice").text("￥"+price);
    }
    else
    {
    	 $("#realprice").text("￥"+val*price);
    }
   
}
function jia(price)
{
	checkNum(parseInt(document.getElementById('num').value)+parseInt(1),price);
}
function jian(price)
{
	checkNum(parseInt(document.getElementById('num').value)-parseInt(1),price);
}
function tj(id)
{
	var name=$("#name").val();
	var phone=$("#phone").val();
	var num=$("#num").val();
	var code=$("#code").val();
	if(num>10)
	{
		alert("每个手机号限购10张惠民票!");
		return;
	}
	if(name=='')
	{
		alert('姓名不能为空');
		return;
	}
	if(phone=='')
	{
		alert('手机号不能为空');
		return;
	}
	var re = /^1\d{10}$/;
	if (re.test(phone)===false) 
	{
		alert("请输入正确的手机号!");
		return;
	}
	if(code=='')
	{
		alert('验证码不能为空!');
		return;
	}
	$.ajax({
	    type: "post",  //数据提交方式（post/get）
	    url: "checkcode.class.php",  //提交到的url
	    data: {phone:phone,code:code},//提交的数据
	    dataType: "json",//返回的数据类型格式
	    success: function(msg){
		    	if(msg)
				{				
		    		$.ajax({
		    		    type: "post",  //数据提交方式（post/get）
		    		    url: "selphonenum.class.php",  //提交到的url
		    		    data: {phone:phone,num:num},//提交的数据
		    		    dataType: "json",//返回的数据类型格式
		    		    success: function(msg){
		    			    	if(msg.data)
		    					{
		    							
		    					    location.href='tjproduct.php?id='+id+'&name='+name+'&phone='+phone+'&num='+num;
		    					}
		    					else
		    					{
		    						alert('此手机号已订'+msg.sumnum+'票,还可订购'+msg.kynum+'票!');				
		    					}	 		
		    		    },
		    		    error:function(msg){
		    		      alert("网络错误");
		    		    }
		    		});
				}
				else
				{
					alert('验证码不正确!');				
				}	 		
	    },
	    error:function(msg){
	      alert("网络错误");
	    }
	});	
}
</script>
<script type="text/javascript">
var clock = '';
var nums =60;
var btn;
function checkcode(thisBtn)
{ 
var phone=document.getElementById('phone').value;
if(phone=='')
{
	alert('手机号不能为空');
	return;
}
var re = /^1\d{10}$/;
if (re.test(phone)===false) 
{
	alert("请输入正确的手机号!");
	return;
}
$.ajax({
	type: "post",  //数据提交方式（post/get）
	url: "sendcode.class.php",  //提交到的url
	data: {phone:document.getElementById('phone').value},//提交的数据
	dataType: "json",//返回的数据类型格式
	success: function(msg){
	btn = thisBtn;
	btn.disabled = true; //将按钮置为不可点击
	btn.value = nums+'秒后获取';
	clock = setInterval(doLoop, 1000); //一秒执行一次
	},
	error:function(msg){
	alert("网络错误")
	}
});		
}
function doLoop()
{
nums--;
if(nums > 0){
 btn.value = nums+'秒后获取';
}else{
 clearInterval(clock); //清除js定时器
 btn.disabled = false;
 btn.value = '获取验证码';
 nums = 10; //重置时间
}
}
</script>
<title>购票详细</title>

</head>
<style type="text/css">
body {
	margin: 0px;
	background-color: #f5f5f5;
}
.font1{ font-family:"微软雅黑"; font-size:14px; color:#cecece}
.font2{ font-family:"微软雅黑"; font-size:14px; color:#282828}
.font3{ font-family:"微软雅黑"; font-size:14px; color:#6db546}
.font3 a{ font-family:"微软雅黑"; font-size:14px; color:#6db546}
.font4{ font-family:"微软雅黑"; font-size:14px; color:#ff5a00}
.font5{ font-family:"微软雅黑"; font-size:26px; color:#ff5a00}

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
<form action="">
<div class="font2" style="width:100%; height:60px; line-height:60px; background-color:#FFF; ">
<div class="font5" style="width:90%; margin:auto;"><?php echo $product_name?></div>
</div>
<div class="font2" style="width:100%; height:30px; line-height:30px; background-color:#FFF; ">
<div class="font1" style="width:90%; margin:auto; line-height:30px;">此票，需游玩前2小时购买</div>
</div>
<div class="font2" style="width:100%; background-color:#FFF; ">
<div style="width:90%; margin:auto;">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">

    	<tr>
      <td height="30" class="font3"><a href="xz.php?id=<?php echo $id?>">购买须知</a></td>
      </tr>

  </table>
</div>
</div>
<div class="font4" style="width:100%; height:20px; line-height:50px;  "></div>
<!--列表-->



<div class="font1" style="width:100%; height:60px; line-height:60px;  border-bottom:solid 1px #d9d9d9; background-color:#FFF; ">
<div style="width:90%; margin:auto;">
<div style="float:left; width:20%">购买数量</div>
<div class="font2" style="float:left; width:80%; text-align:right;">

<div style="height:60px; line-height:60px;">
<button type="button"   style=" margin-top:15px; width:30px; height:30px;" onclick="jian(<?php echo $wx_price?>);">-</button>
<input name="quantity" id="num"  type="tel" style=" margin-top:15px; width:50px; height:30px; border:none; text-align:center;" max="2147483647" min="1" autocomplete="off" value="1" oninput="checkNum(this.value,<?php echo $wx_price?>)">
<button type="button" style=" margin-top:15px; width:30px; height:30px;" onclick="jia(<?php echo $wx_price?>);" >+</button></div>

</div>
</div>
</div>


<div class="font4" style="width:100%; height:20px; line-height:50px;  "></div>


<div class="font1" style="width:100%; height:60px; line-height:60px;  border-bottom:solid 1px #d9d9d9; background-color:#FFF; ">
<div style="width:90%; margin:auto;">
<div style="float:left; width:20%"> 游客信息</div>
<div class="font2" style="float:left; width:80%">需要1位游客信息，入园身份确认</div>
</div>
</div>




<div class="font1" style="width:100%; height:60px; line-height:60px;  border-bottom:solid 1px #d9d9d9; background-color:#FFF; ">
<div style="width:90%; margin:auto;">
<div style="float:left; width:20%">姓名</div>
<div class="font1" style="float:left; width:80%">
  <input name="name" type="text" id="name" value="<?php echo $name?>" placeholder="输入姓名用于确认身份"  class="font1" style="border: 0px;outline:none;cursor: pointer;  height:50px; line-height:40px; "  />
</div>
</div>
</div>




<div class="font1" style="width:100%; height:60px; line-height:60px;  border-bottom:solid 1px #d9d9d9; background-color:#FFF; ">
<div style="width:90%; margin:auto;">
<div style="float:left; width:20%">手机号</div>
<div class="font2" style="float:left; width:80%">
  <input name="phone" type="tel" id="phone" value="<?php echo $phone?>" placeholder="输入手机号"  class="font1" style="border: 0px;outline:none;cursor: pointer; height:50px; line-height:40px; "  />
</div>
</div>
</div>
<div class="font1" style="width:100%; height:60px; line-height:60px;  border-bottom:solid 1px #d9d9d9; background-color:#FFF; ">
<div style="width:90%; margin:auto;">
<div style="float:left; width:20%">验证码</div>
<div class="font2" style="float:left; width:35%">
  <input name="textfield" type="tel" id="code" placeholder="输入验证码"  class="font1" style="border: 0px;outline:none;cursor: pointer; height:50px; line-height:40px; "  />
</div>
<div style="float:left; text-align:right; width:45%;">
<input style="width:80%; height:30px;padding:5px; margin-top:10px; text-align:center;" id="btn" onclick="checkcode(this);" type="button" value="获取短信验证码"></input></div>
</div>
</div>
<div style="width:100%; height:100px;"></div>
<!--底部-->
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="position: fixed; bottom: 0; z-index:100;" >
  <tr>
    <td width="50%" bgcolor="#FFFFFF"  class="font5" id="realprice">￥<?php echo $wx_price?></td>
    <td width="50%"  class="font51"><a href="javascript:;"><img src="images/kfydxx_02.png" width="100%" onclick="tj('<?php echo $id?>')" /></a></td>
  </tr>
</table>
</form>
</body>
</html>
