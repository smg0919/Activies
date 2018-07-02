
<?php
session_start();
$phone=$_POST['phone'];
$code=rand(000000,999999);
$res=getSmsCode($phone,$code,2);
$_SESSION['phone']=$phone;
$_SESSION['return']=$code;
	function getSmsCode($phone,$code,$type)
	{
		require_once "smsLib/SmsSender.php";
		$appid = '1400036004';
		$appkey = "ba3e7fd87a3d19918759936a83c65cd0";
		$retVal = 1;
		try
		{
			$singleSender = new SmsSingleSender($appid, $appkey);
			if($type==1)
			{
				$templId = 29940;
			}
			else if($type==2)
			{
				$templId = 32994;
			}
			// 指定模板群发，模板参数沿用上文的模板 id 和 $params
			$params = array($code);
			$result = $singleSender->sendWithParam("86", $phone, $templId, $params, "", "", "");
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
	$data='{username:"123",password:"123"}';//组合成json格式数据
    echo json_encode($data);//输出json数据
?>