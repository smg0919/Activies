<?php
$data['status']=false;

echo json_encode($data);//输出json数据
die("");

session_start();
require_once ("db.php");
include 'lib/guolvzifu.php';
require_once "./../../Wx.JsApi.php";
$phone = $_POST['phone'];
$name=strFilter($_POST['name']);
$date=$_POST['date'];
$id=$_POST['id'];
$num=$_POST['num'];
$reg_from_admin=$_SESSION['reg_from_admin'];
$wxuser=$_SESSION['wx_userid'];
$openid=$_SESSION['wx_openid'];
$buy_type=$_SESSION['reg_from_admin'];
$user_id=$_SESSION['user_id'];
if($num<=0 || intval($user_id) == 0)
{
	$data['status']=false;//组合成json格式数据
	
	echo json_encode($data);//输出json数据
	exit;
}
$sql="select * from wq_product where id='".$id."'";
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
	$totalprice=$wx_price*$num;
	mysql_query("BEGIN");
	$order_tid=time().rand(1000,9999);
	$sql1="insert into wq_order(order_tid,order_type,wx_userid,user_id,real_price,status,order_time,phone,name,play_date,type,comp_id) values('".$order_tid."','1','".$wxuser."','".$user_id."','".$totalprice."','0',now(),'".$phone."','".$name."','".$date."','2','".$reg_from_admin."')";
	$ress1 = mysql_query($sql1);
	$order_id=mysql_insert_id();
	$sql2="insert into wq_subOrder(order_id,product_id,product_name,product_per_price,product_num,product_price,zyb_product_id) values('".$order_id."','".$id."','".$product_name."','".$wx_price."','".$num."','".$totalprice."','".$zyb_product_id."')";
	$ress2 = mysql_query($sql2);
	if($ress1 && $ress2)
	{
		mysql_query("COMMIT");
		$data['status']=true;
	}
	else
	{
		mysql_query("ROLLBACK");
		$data['status']=false;	
	}
}
else
{
	$data['status']=false;
}

$mtime=explode(' ',microtime());
$msec = substr($mtime[0],2);
$tradeNo = date("YmdHis").$msec.mt_rand(1000,9999);

$fee = 100 * $totalprice;
$input = new WxPayUnifiedOrder();
$input->SetBody($product_name);
$input->SetAttach($product_name);
$input->SetOut_trade_no("".$tradeNo);
$input->SetTotal_fee("".$fee);
$input->SetTime_start(date("YmdHis"));
$input->SetTime_expire(date("YmdHis", time() + 600));
$input->SetGoods_tag($product_name);
$input->SetNotify_url("http://www.kxhotspring.com/api/Wechat/actives/A001/payCallback.php");
$input->SetTrade_type("JSAPI");
$input->SetOpenid($openid);
$order = WxPayApi::unifiedOrder($input);

$result = $order['return_code'];

if($result == 'SUCCESS')
{
	$prepayid = $order['prepay_id'];
	$sql = "insert into wq_wx_order (`order_id`, `prepayid`, `main_order_id`, `user_id`, `fee`, `status`, `usage`) values ('".$tradeNo."', '".$prepayid."', '".$order_id."', '".$wxuser."', '".$fee."', 0, 3)";
	mysql_query($sql);
}
$tools = new JsApi();
$jsApiParameters = $tools->GetJsApiParameters($order);
$data['api'] = $jsApiParameters;
echo json_encode($data);//输出json数据
?>