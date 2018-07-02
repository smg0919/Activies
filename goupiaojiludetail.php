<?php 
session_start();
require_once ("db.php");
$wx_userid=$_SESSION['wx_userid'];
$id=$_GET['id'];
$sql="select o.ass_No as ass_No,o.order_tid,s.product_per_price as price,o.phone as phone,product_num as product_num,s.product_name as product_name,o.real_price as real_price,o.name as name,o.play_date as play_date from wq_order as o left join wq_subOrder as s on o.id=s.order_id where o.wx_userid='".$wx_userid."' and o.id='".$id."'";
$res = mysql_query($sql);
$List = array();
if($res)
{
	while($row = mysql_fetch_array($res))
	{
		$productObj['id']=$row['id'];
		$productObj['product_name']=$row['product_name'];
		$productObj['product_num']=$row['product_num'];
		$productObj['play_date']=$row['play_date'];
		$productObj['real_price']=$row['real_price'];
		$productObj['name']=$row['name'];
		$productObj['phone']=$row['phone'];
		$productObj['ass_No']=$row['ass_No'];
		$productObj['order_tid']=$row['order_tid'];
		$productObj['price']=$row['price'];
		$List[] = $productObj;
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width,minimum-scale=1,maximum-scale=1">

<title>购买记录详细</title>
<style type="text/css">
body {
	margin: 0px;
	background-color: #fff;
}
.font1{ font-family:"微软雅黑"; font-size:16px; color:#282828}
.font2{ font-family:"微软雅黑"; font-size:16px; color:#cecece}
.font3{ font-family:"微软雅黑"; font-size:16px; color:#ff5a00}

</style>
</head>

<body>
<?php 
	for($i=0;$i<count($List);$i++)
	{
		$ListObj=$List[$i];
?>
<div style="width:100%; background-color:#FFF; ">
<div style="width:90%; margin:auto;">
<div class="font1" style="float:left; width:100%; height:50px; line-height:50px; "><?php echo $ListObj['product_name'].$ListObj['product_num']?>张</div>



<div style="clear:both"></div>
</div>
</div>

<div style="width:100%; background-color:#FFF; ">

<div style="width:90%; margin:auto;">
<div class="font1" style="float:left; width:50%; height:50px; line-height:50px; border-bottom:solid 1px #d9d9d9;">单价</div>
<div class="font2" style="float:left; width:50%; height:50px; line-height:50px; border-bottom:solid 1px #d9d9d9; text-align:right;">￥<?php echo $ListObj['price']?></div>
<div style="clear:both"></div>
</div>

<div style="width:90%; margin:auto;">
<div class="font1" style="float:left; width:50%; height:50px; line-height:50px; border-bottom:solid 1px #d9d9d9;">购买数量</div>
<div class="font2" style="float:left; width:50%; height:50px; line-height:50px; border-bottom:solid 1px #d9d9d9; text-align:right;"><?php echo $ListObj['product_num']?></div>
<div style="clear:both"></div>
</div>

<div style="width:90%; margin:auto;">
<div class="font1" style="float:left; width:50%; height:50px; line-height:50px; border-bottom:solid 1px #d9d9d9;">游玩时间</div>
<div class="font2" style="float:left; width:50%; height:50px; line-height:50px; border-bottom:solid 1px #d9d9d9; text-align:right;"><?php echo $ListObj['play_date']?></div>
<div style="clear:both"></div>
</div>

<div style="width:90%; margin:auto;">
<div class="font1" style="float:left; width:50%; height:50px; line-height:50px; border-bottom:solid 1px #d9d9d9;">总价</div>
<div class="font2" style="float:left; width:50%; height:50px; line-height:50px; border-bottom:solid 1px #d9d9d9; text-align:right;">￥<?php echo $ListObj['real_price']?></div>
<div style="clear:both"></div>
</div>

<div style="width:90%; margin:auto;">
<div class="font1" style="float:left; width:50%; height:50px; line-height:50px; border-bottom:solid 1px #d9d9d9;">联系人</div>
<div class="font2" style="float:left; width:50%; height:50px; line-height:50px; border-bottom:solid 1px #d9d9d9; text-align:right;"><?php echo $ListObj['name']?></div>
<div style="clear:both"></div>
</div>

<div style="width:90%; margin:auto;">
<div class="font1" style="float:left; width:50%; height:50px; line-height:50px; border-bottom:solid 1px #d9d9d9;">手机号</div>
<div class="font2" style="float:left; width:50%; height:50px; line-height:50px; border-bottom:solid 1px #d9d9d9; text-align:right;"><?php echo $ListObj['phone']?></div>
<div style="clear:both"></div>
</div>

<div style="width:90%; margin:auto;">
<div class="font1" style="float:left; width:50%; height:50px; line-height:50px; border-bottom:solid 1px #d9d9d9;">订单号</div>
<div class="font2" style="float:left; width:50%; height:50px; line-height:50px; border-bottom:solid 1px #d9d9d9; text-align:right;"><?php echo $ListObj['order_tid']?></div>
<div style="clear:both"></div>
</div>

<div style="width:90%; margin:auto;">
<div class="font1" style="float:left; width:50%; height:50px; line-height:50px; border-bottom:solid 1px #d9d9d9;">辅助码</div>
<div class="font2" style="float:left; width:50%; height:50px; line-height:50px; border-bottom:solid 1px #d9d9d9; text-align:right;"><?php echo $ListObj['ass_No']?></div>
<div style="clear:both"></div>
</div>
</div>
<div style="width:100%; height:10px;"></div>
<?php 
	}
?>
</body>
</html>
