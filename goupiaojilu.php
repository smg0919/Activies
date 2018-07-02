<?php 
session_start();
require_once ("db.php");
$wx_userid=$_SESSION['wx_userid'];
$sql="select o.id as id,s.product_num as product_num,s.product_name as product_name,o.real_price as real_price,o.name as name,o.play_date as play_date from wq_order as o left join wq_subOrder as s on o.id=s.order_id where status>0 and status < 7 and o.wx_userid='".$wx_userid."'";
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
		$List[] = $productObj;
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
function detail(id)
{
	location.href='goupiaojiludetail.php?id='+id;
}
</script>
<title>购买记录</title>
<style type="text/css">
body {
	margin: 0px;
	background-color: #f5f5f5;
}
.font1{ font-family:"微软雅黑"; font-size:16px; color:#282828}
.font2{ font-family:"微软雅黑"; font-size:16px; color:#cecece}
.font3{ font-family:"微软雅黑"; font-size:16px; color:#ff5a00}

</style>
</head>

<body>
<?php 
	if(count($List)>0)
	{
		
	for($i=0;$i<count($List);$i++)
	{
		$ListObj=$List[$i];
?>
<div style="width:100%; background-color:#FFF; ">
<div style="width:90%; margin:auto;">
<div class="font1" style="float:left; width:50%; height:50px; line-height:50px; border-bottom:solid 1px #d9d9d9;"><?php echo $ListObj['product_name'].$ListObj['product_num']?>张</div>
<div class="font2" style="float:left; width:50%; height:50px; line-height:50px; border-bottom:solid 1px #d9d9d9; text-align:right;"><?php echo $ListObj['play_date']?></div>
<div class="font3" style="float:left; width:30%; height:50px; line-height:50px;"><?php echo $ListObj['name']?></div>
<div class="font3" style="float:left; width:30%; height:50px; line-height:50px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;￥<?php echo $ListObj['real_price']?></div>
<div class="font2" style="float:left; width:40%; height:50px; line-height:50px; text-align:right;">
  <input type="button" name="button" id="button" value="详情" onclick="detail(<?php echo $ListObj['id']?>)" style=" margin-top:10px;" />
</div>
<div style="clear:both"></div>
</div>
<div style="width:100%; height:10px;"></div>
</div>
<?php 
	}
	}
	else {
		
		
?>
	<img src="images/zw.png" width="100%" />
<?php 
	}?>
</body>
</html>
