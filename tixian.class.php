<?php
define(DB_HOST, 'localhost');  
define(DB_USER, 'root');  
define(DB_PASS, 'symiao');
define(DB_DATABASENAME,'jj_wq_app');   
$conn = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die("connect failed" . mysql_error());
mysql_select_db(DB_DATABASENAME, $conn);
mysql_query("SET NAMES 'utf8'");
$direct_type = $_POST['direct_type'];
$reg_from_admin = $_POST['reg_from_admin'];
$uid=$_POST['uid'];
$fanmoney=$_POST['fanmoney'];
$databasename=$_POST['databasename'];
$proportion=$_POST['proportion'];
define(DB_DATABASENAMEs,$databasename);   
$conn = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die("connect failed" . mysql_error());
mysql_select_db(DB_DATABASENAMEs, $conn);
mysql_query("SET NAMES 'utf8'");
$sql="select sum(tixian_money) as tixian_money from tixian where user_id=".$uid."";
$res = mysql_query($sql);
if($res)
{
	
	$rows = mysql_fetch_array($res);
	if($rows)
	{
		$tixian_money = intval($rows['tixian_money']);
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
if(($total_num*$proportion)>$tixian_money)
{
		if($direct_type==1)
		{
			
			define(DB_DATABASENAME,'jj_wq_app');   
			$conn = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die("connect failed" . mysql_error());
			mysql_select_db(DB_DATABASENAME, $conn);
			mysql_query("SET NAMES 'utf8'");
			$sql="select * from wq_admin where id='".$reg_from_admin."'";
			$res = mysql_query($sql);
			if($res)
			{
				$row = mysql_fetch_array($res);
				if($row)
				{
					$chubeijin=$row['chubeijin'];
					$sql1="update wq_admin set chubeijin=chubeijin+'".$fanmoney."' where id='".$reg_from_admin."'";
					$ress1 = mysql_query($sql1);
					$order_tid=time().rand(1000,9999);
					$fees=$fanmoney*100;
					$account=($chubeijin+$fanmoney)*100;
					$sql4="insert into wq_account(order_id,main_order_id,user_id,fee,status,happen_time,account_type,company_id,remark,balance) values('".$order_tid."','0','".$uid."','".$fees."','1',now(),'3','".$reg_from_admin."','活动提现储备金成功".$fanmoney."元','".$account."')";
					$ress4 = mysql_query($sql4);
					define(DB_DATABASENAMEs,$databasename);   
					$conn = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die("connect failed" . mysql_error());
					mysql_select_db(DB_DATABASENAMEs, $conn);
					mysql_query("SET NAMES 'utf8'");
					$sql2="insert into tixian(user_id,tixian_time,tixian_money) values('".$uid."',now(),'".$fanmoney."')";
					$ress2 = mysql_query($sql2);
					if($ress1&&$ress2&&$ress4)
					{
						$data=true;//组合成json格式数据
					}
					else
					{
						$data=false;//组合成json格式数据
					}
					echo json_encode($data);//输出json数据
				}
			}
			
		}
		else
		{
			define(DB_DATABASENAME,'jj_wq_app');   
			$conn = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die("connect failed" . mysql_error());
			mysql_select_db(DB_DATABASENAME, $conn);
			mysql_query("SET NAMES 'utf8'");
			$sql="select * from wq_user where id='".$uid."'";
			$res = mysql_query($sql);
			if($res)
			{
				$row = mysql_fetch_array($res);
				if($row)
				{
					$account=$row['account'];
					$sql1="update wq_user set account=account+'".$fanmoney."' where id='".$uid."'";
					$ress1 = mysql_query($sql1);
					$order_tid=time().rand(1000,9999);
					$fees=$fanmoney*100;
					$account=($account+$fanmoney)*100;
					$sql4="insert into wq_account(order_id,main_order_id,user_id,fee,status,happen_time,account_type,company_id,remark,balance) values('".$order_tid."','0','".$uid."','".$fees."','1',now(),'3','".$reg_from_admin."','活动提现钱包成功".$fanmoney."元','".$account."')";
					$ress4 = mysql_query($sql4);
					define(DB_DATABASENAMEs,$databasename);   
					$conns = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die("connect failed" . mysql_error());
					mysql_select_db(DB_DATABASENAMEs, $conns);
					mysql_query("SET NAMES 'utf8'");
					$sql2="insert into tixian(user_id,tixian_time,tixian_money) values('".$uid."',now(),'".$fanmoney."')";
					$ress2 = mysql_query($sql2);
					if($ress1&&$ress2&&$ress4)
					{
						$data=true;//组合成json格式数据
					}
					else
					{
						$data=false;//组合成json格式数据
					}
					echo json_encode($data);//输出json数据
				}
			}
			
		}
}
else 
{
	$data=false;//组合成json格式数据
	echo json_encode($data);//输出json数据
}
?>
	
			
			