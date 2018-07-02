<?php
session_start();
require_once ("db.php");
$phone = $_POST['phone'];
$num=$_POST['num'];
$sql="select sum(s.product_num) as totalnum from wq_order as o left join wq_subOrder as s on o.id=s.order_id where o.phone='".$phone."' and o.status in(1,4,5)";
$res = mysql_query($sql);
if($res)
{
	$rows = mysql_fetch_array($res);
	if($rows)
	{
		$totalnum = intval($rows['totalnum']);
		if(($totalnum+$num)>10)
		{
			$data=false;
			$date['sumnum']=$totalnum;
			$date['kynum']=10-$totalnum;
			$json_arr = array("data"=>$data,"sumnum"=>$totalnum,"kynum"=>10-$totalnum);
		}
		else 
		{
			$data=true;
			$json_arr = array("data"=>$data);
		}
	}
	else 
	{
		$data=true;
		$json_arr = array("data"=>$data);
	}
	
}
else
{
	$data=true;
	$json_arr = array("data"=>$data);
}
echo json_encode($json_arr);
?>