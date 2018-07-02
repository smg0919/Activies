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
$sql = "select  w.headurl as headurl,o.phone as phone,o.pay_time as pay_time,s.product_num as num from wq_order as o left join wq_subOrder as s on o.id=s.order_id left join wx_users as w on o.wx_userid=w.id where user_id='".$uid."' and o.status in(1,4,5)";
$res = mysql_query($sql);
$orderList = array();
if($res)
{
	while($row = mysql_fetch_array($res))
	{
		$orderObj['headurl'] = substr_replace($row['headurl'],'s',4,0);  //将头像http转为https		
		$orderObj['phone'] = $row['phone'];
		$orderObj['pay_time'] = $row['pay_time'];
		$orderObj['num']=$row['num'];
		$orderList[] = $orderObj;
	}
}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width,minimum-scale=1,maximum-scale=1">
<style type="text/css">
table tr td {
	border-bottom: 1px solid #d9d9d9;
}
body {
	background-color: #fff;
	margin: 0px;
}
.font3 {
	font-family: "微软雅黑";
	font-size: 18px;
	line-height: 28px;
	color: #818181
}
.font4 {
	font-family: "微软雅黑";
	font-size: 14px;
	line-height: 24px;
	color: #282828
}
</style>
<script>
function backs(uid,database,proportion)
{
	location.href="yj.php?uid="+uid+"&databasename="+database+"&proportion="+proportion;
}
</script>
<title>康溪成绩单</title>
</head>
<body>

<table width="100%">
  <tr>
    <td class="font3" align="center">头像</td>
    <td class="font3" align="center">手机号</td>
	<td class="font3" align="center">数量</td>
    <td class="font3" align="center">兑换时间</td>
  </tr>
  <?php
for($i=0; $i<count($orderList); $i++)
{
	$orderObj = $orderList[$i];
?>
  <tr>
    <td width="15%"><img src="<?php echo $orderObj['headurl']?>" width="50" height="50"/ ></td>
    <td width="35%" class="font4" align="center"><?php echo $orderObj['phone']?></td>
	<td width="20%" class="font4" align="center"><?php echo $orderObj['num']?></td>
    <td width="30%" class="font4" align="center"><?php echo $orderObj['pay_time']?></td>
  </tr>
<?php 
}
?> 
</table>

<div style="width:15%;  position: fixed;  bottom: 100px; z-index: 100;"><img src="images/mx_fh.png" width="100%"  onClick="backs('<?php echo $uid?>','<?php echo $databasename?>','<?php echo $proportion?>')"/></div>

</body>
</html>
