<?php
require_once "./db.php";
require_once "./../../Wx.JsApi.php";


$successMsg="
<xml>
  <return_code><![CDATA[SUCCESS]]></return_code>
  <return_msg><![CDATA[OK]]></return_msg>
</xml>";

$errorMsg="
<xml>
  <return_code><![CDATA[FAIL]]></return_code>
  <return_msg><![CDATA[签名错误]]></return_msg>
</xml>";

$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
/*
$postStr = "<xml><appid><![CDATA[wx8fb7d83511a8110c]]></appid>
<attach><![CDATA[车圈儿保证]]></attach>
<bank_type><![CDATA[CMB_CREDIT]]></bank_type>
<cash_fee><![CDATA[1]]></cash_fee>
<device_info><![CDATA[WEB]]></device_info>
<fee_type><![CDATA[CNY]]></fee_type>
<is_subscribe><![CDATA[N]]></is_subscribe>
<mch_id><![CDATA[1321101601]]></mch_id>
<nonce_str><![CDATA[d4a68c5adcfcf9b3dcb5124e14ca3b74]]></nonce_str>
<openid><![CDATA[ogtaqt0NBz4JeiqnW_eMjjYKv1AY]]></openid>
<out_trade_no><![CDATA[2016031810111410627600]]></out_trade_no>
<result_code><![CDATA[SUCCESS]]></result_code>
<return_code><![CDATA[SUCCESS]]></return_code>
<sign><![CDATA[030F577E4C98EEDBAFD511B0F78FB86D]]></sign>
<time_end><![CDATA[20160318101128]]></time_end>
<total_fee>1</total_fee>
<trade_type><![CDATA[APP]]></trade_type>
<transaction_id><![CDATA[1006270826201603184071743168]]></transaction_id>
</xml>";
*/
$xml = simplexml_load_string($postStr);
$array = json_decode(json_encode((array)$xml), true);

$data = array();
foreach($array as $key=>$value)
{
	if($key != "sign")
	{
		$data[$key]=$xml->$key;
	}
}

$s = getSign($data, false);

if($s==$xml->sign)
{
	echo $successMsg;
	$sql = "select `main_order_id` from `wq_wx_order` where order_id='".$xml->out_trade_no."'";
	$res = mysql_query($sql);
	$order_id = "";
	if($res)
	{
		$row = mysql_fetch_array($res);
		if($row)
		{
			$order_id = $row['main_order_id'];
		}
	}

	$sql = "select * from `wq_order` where id='".$order_id."'";
	$res = mysql_query($sql);
	$order_tid = "";
	$user_id = "";
	$fee = "";
	$company_id = "";
	$name = "";
	$phone = "";
	if($res)
	{
		$row = mysql_fetch_array($res);
		if($row)
		{
			$order_tid = $row['order_tid'];
			$user_id = $row['user_id'];
			$name = $row['name'];
			$phone = $row['phone'];
			$fee = intval(100 * floatval($row['real_price']));
			$company_id = $row['comp_id'];
		}
	}

	$sql = "update `wq_order` set `pay_time`=NOW(), type=2, status=1 where id='".$order_id."'";
	mysql_query($sql);
	$cnt = 0;
	// 插入到智游宝表里面
	$sql = "select count(1) as cnt from wq_zyb_order where order_id='".$order_tid."'";
	$res = mysql_query($sql);
	if($res)
	{
		$row = mysql_fetch_array($res);
		if($row)
		{
			$cnt = $row['cnt'];
		}
		if(intval($cnt) > 0)
		{
		}
		else
		{
			$ret = insert_zyb($order_id, $order_tid);
		}
	}

	$sql_debug = "insert into debug (info) values('wxpay_result=".$ret."')";
	mysql_query($sql_debug);

	$sql = "select * from wq_subOrder where order_id='".$order_id."'";
	$res = mysql_query($sql);
	if($res)
	{
		$subInfo = mysql_fetch_array($res);	
		if($subInfo)
		{
			$num = $subInfo["product_num"];
			$product_name = $subInfo["product_name"];
		}
	}

	$sql = "update `wx_users` set `name`='".$name."', phone='".$phone."' where id='".$user_id."'";
	mysql_query($sql);
	$sql = "update `wq_wx_order` set `status`=1 where order_id='".$xml->out_trade_no."'";
	/*
	if($ret != 0)
	{
		$retreatBatchNo = date("YmdHis").$msec.mt_rand(100,999);
		refundByWx($order_id, $retreatBatchNo, $fee);
		$sql="insert into wq_account(happen_time,order_id,main_order_id,user_id,fee,status,account_type,company_id) values(now(),'".$orderCode."','".$order_id."','".$company_id."','".$fee."','3','2','".$company_id."')";
		$res=mysql_query($sql);
		$sql = "update `wq_wx_order` set `status`=3 where order_id='".$xml->out_trade_no."'";
	}
	*/
}
else 
{
	echo $errorMsg;
	$sql = "update `wq_wx_order` set `status`=2 where order_id='".$xml->out_trade_no."'";
}

mysql_query($sql);
mysql_close($conn);


function getSign($Obj)
{
	foreach ($Obj as $k => $v)
	{
		$Parameters[strtolower($k)] = $v;
	}
	//签名步骤一：按字典序排序参数
	ksort($Parameters);
	$String = formatBizQueryParaMap($Parameters, false);
	//echo "【string】 =".$String."</br>";
	//签名步骤二：在string后加入KEY
	$String = $String."&key=D7F972FDE29D690DD42C5F1AB4CE38F0";
	//签名步骤三：MD5加密
	$result_ = strtoupper(md5($String));
	return $result_;
}

function formatBizQueryParaMap($paraMap, $urlencode)
{
	$buff = "";
	ksort($paraMap);
	foreach ($paraMap as $k => $v)
	{
		if($urlencode)
		{
		   $v = urlencode($v);
		}
		$buff .= strtolower($k) . "=" . $v . "&";
	}
	$reqPar;
	if (strlen($buff) > 0) 
	{
		$reqPar = substr($buff, 0, strlen($buff)-1);
	}
	return $reqPar;
}

function sendBuyInfo($phone,$order_id,$name,$ass_code,$ticketName,$num)
{
	require_once "../../../protected/apps/default/controller/smsLib/SmsSender.php";
	$appid = '1400036004';
	$appkey = "ba3e7fd87a3d19918759936a83c65cd0";
	$retVal = 1;
	try
	{
		$singleSender = new SmsSingleSender($appid, $appkey);

		$templId = 47427;
		$params = array("".$name, "".$ticketName, "".$num, "".$order_id, "".$ass_code);
		$result = $singleSender->sendWithParam("86", "".$phone, $templId, $params, "", "", "");
		$rsp = json_decode($result);
		if($rsp && isset($rsp->result))
		{
			$retVal = $rsp->result;
		}
	}
	catch (\Exception $e) 
	{
	}
	
	return $retVal;
}

function insert_zyb($order_id, $order_tid)
{
	$url = "http://boss.zhiyoubao.com/boss/service/code.htm"; //正式地址
	$zyb_id="lnwljkfx"; // 用户名
	$zyb_qy="sdzfxlnwljkfx"; // 企业码
	$zyb_ps="FA0352431DC5EE2D06C9DBAAD4E67B22"; // 私钥
	$today = date("Y-m-d");
	$expiredDay = date("Y-m-d", strtotime("+7 day"));

	$sql = "select * from wq_order where id='".$order_id."'";
	$res = mysql_query($sql);
	if($res)
	{
		$orderInfo = mysql_fetch_array($res);		
		if($orderInfo)
		{
			if(intval($orderInfo["id"]) == 0)
			{
				return 1001; // 1001 代表错误：自己订单里不存在这个订单号
			}
			$contact = $orderInfo["name"];
			$phone = $orderInfo["phone"];
			$order_price = $orderInfo["real_price"];
			if($orderInfo["play_date"])
			{
				$expiredDay = $orderInfo["play_date"];
			}
			// 目前一个订单只有一个子订单，所以用find，不是select，不循环，这样下面的子订单也只有一个。
			$sql = "select * from wq_subOrder where order_id='".$order_id."'";
			$res = mysql_query($sql);
			if($res)
			{
				$subInfo = mysql_fetch_array($res);	
				if($subInfo)
				{
					if(intval($subInfo["id"]) == 0)
					{
						return 1002; // 1001 代表错误：自己订单里不存在这个订单号
					}
					$suborder_id = $order_tid.$subInfo["id"];
					$suborder_perPrice = $subInfo["product_per_price"];
					$suborder_num = $subInfo["product_num"];
					$suborder_totalPrice = $subInfo["product_price"];
					$suborder_expired = $expiredDay;
					$suborder_goodsid = $subInfo["zyb_product_id"];
					$suborder_goodsname = $subInfo["product_name"];
					$suborder_remark =  $subInfo["product_name"];
				}
			}
			else
			{
				return 1002; // 1002 代表错误：自己子订单表里面不存在这个订单号
			}
		}
	}
	else
	{
		return 1001; // 1001 代表错误：自己订单里不存在这个订单号
	}
	$xmlmsg = "
	<PWBRequest>
		<transactionName>SEND_CODE_REQ</transactionName>
		<header>
			<application>SendCode</application>
			<requestTime>".date("Y-m-d")."</requestTime>
		</header>
		<identityInfo>
			<corpCode>".$zyb_qy."</corpCode>
			<userName>".$zyb_id."</userName>
		</identityInfo>
		<orderRequest>
			<order>
				<certificateNo></certificateNo>
				<linkName>".$contact."</linkName>
				<linkMobile>".$phone."</linkMobile>
				<orderCode>".$order_tid."</orderCode>
				<orderPrice>".$order_price."</orderPrice>
				<groupNo></groupNo>
				<payMethod>vm</payMethod>
				<ticketOrders>
					<ticketOrder>
						<orderCode>".$suborder_id."</orderCode>
						<price>".$suborder_perPrice."</price>
						<quantity>".$suborder_num."</quantity>
						<totalPrice>".$suborder_totalPrice."</totalPrice>
						<occDate>".$suborder_expired."</occDate>
						<goodsCode>".$suborder_goodsid."</goodsCode>
						<goodsName>".$suborder_goodsname."</goodsName>
						<remark>".$suborder_remark."</remark> 
					</ticketOrder>
				</ticketOrders>
			</order>
		</orderRequest>
	</PWBRequest>";

	$sign=MD5("xmlMsg=".$xmlmsg.$zyb_ps);

	$sql_debug = "insert into debug (info) values('zyb_req=".$xmlmsg."')";
	mysql_query($sql_debug);

	$postData["xmlMsg"] = $xmlmsg;
	$postData["sign"] = $sign;

	$postData = json_encode($postData);

	$postData = 'xmlMsg='.$xmlmsg.'&sign='.$sign;

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HEADER, false); 
	curl_setopt($ch, CURLOPT_POST, count($postData));
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

	$output=curl_exec($ch);
	curl_close($ch);
	
	$sql_debug = "insert into debug (info) values('zyb_result=".$output."')";
	mysql_query($sql_debug);
	$xml = simplexml_load_string($output);
	$zyb_order_id = $xml->orderResponse->order->orderCode;
	$assistCheckNo = $xml->orderResponse->order->assistCheckNo;
	
	$order_return = $xml->code;
	if(intval($xml->code) == 0)
	{
		$sql = "insert into wq_zyb_order (`order_id`, `zyb_orderId`, `status`, `assistCheckNo`) values ('".$order_tid."', '".$zyb_order_id."', 0, '".$assistCheckNo."')";
		mysql_query($sql);
		if(mysql_affected_rows() != 1)
		{
			return 1003; // 插入智游宝失败
		}
		$retSm = sendBuyInfo($phone,$zyb_order_id,$contact,$assistCheckNo,$suborder_goodsname,$suborder_num);
		$sql = "update wq_order set ass_No='".$assistCheckNo."' where id='".$order_id."'";
		mysql_query($sql);
	}
	
	return $order_return; // 每个code对应一个错误码， 0是成功
}

function refundByWx($id, $refundNo, $money)
{
	$sql = "select `order_id`, `fee` from `wq_wx_order`  where `main_order_id`='".$id."'";
	$res = mysql_query($sql);
	if($res)
	{
		$info = mysql_fetch_array($res);
		if($info)
		{
			$orderid = $info['order_id'];
			$totalFee = $info['fee'];
			$input = new WxPayRefund();
			$input->SetOut_trade_no($orderid);
			$input->SetTotal_fee($totalFee);
			$input->SetOut_refund_no($refundNo);
			$input->SetRefund_fee($money);
			$input->SetOp_user_id(WxPayConfig::MCHID);
			$order = WxPayApi::refund($input);
		}
	}
}
?>