<?php 
session_start();
require_once "./../../Wx.JsApi.php";
require_once ("db.php");
$tools = new JsApi();
$openid = $tools->GetOpenid();    //购票人微信ID
$user_info = $tools->GetUserInfo($openid);
$nick_name = $user_info['nickname'];
$img_url = $user_info['headimgurl'];
$province = $user_info['province'];
$city = $user_info['city'];
$sql="select * from wx_users where wechat='".$openid."'";
$res = mysql_query($sql);
$notFetch = true;
if($res)
{
	$rows = mysql_fetch_array($res);
	if($rows)
	{
		$wxuserId = $rows['id'];
		$_SESSION['name']=$rows['name'];
		$_SESSION['phone']=$rows['phone'];
		$notFetch = false;	
		
	}
}
if($notFetch)
{
	$sql="insert into wx_users(nick_name,wechat,headurl,city,reg_time) values('".urlencode($nick_name)."','".$openid."','".$img_url."','".$province."-".$city."',NOW())";
	$res = mysql_query($sql);
	$wxuserId = mysql_insert_id();
	
}
else 
{
	$sql = "update `wx_users` set `city`='".$province."-".$city."', `nick_name`='".urlencode($nick_name)."', `headurl`='".$img_url."' where `wechat`='".$openid."'";
	mysql_query($sql);
	
}
$_SESSION['wx_userid']=$wxuserId;            
$reg_from_admin=$_GET['reg_from_admin'];				//所属旅行社
$user_id=$_GET['user_id']; 
$buy_type=$_GET['buy_type'];
$_SESSION['wx_openid']=$openid;
$_SESSION['user_id']=$user_id;
$_SESSION['reg_from_admin']=$reg_from_admin;
$_SESSION['buy_type']=$buy_type;  
$_SESSION['wx_userid']=$wxuserId;
/* $sql="select * from wq_product where buy_type='".$buy_type."'";
$res = mysql_query($sql);
$productList = array();
if($res)
{
	while($row = mysql_fetch_array($res))
	{
		$productObj['id']=$row['id'];
		$productObj['product_name']=$row['product_name'];
		$productObj['play_date']=$row['play_date'];
		$productObj['can_sel_date']=$row['can_sel_date'];
		$productObj['wx_price']=$row['wx_price'];
		$productObj['price']=$row['price'];
		$productObj['fandianprice']=$row['wx_price']-$row['price'];       //返点价格
		$productObj['zyb_product_id']=$row['zyb_product_id'];		
		$productList[] = $productObj;
	}
} */
?>