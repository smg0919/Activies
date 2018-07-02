<?php 
session_start();
require_once ("db.php");
require_once "./../../Wx.JsApi.php";
require_once "./../../jssdk.php";
$id=$_GET['id']; //商品id
$name=$_GET['name'];
$num=$_GET['num'];
$phone=$_GET['phone'];
//$date=$_GET['date'];
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
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width,minimum-scale=1,maximum-scale=1">
<script src="js/jquery.min.js"></script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
function sub()
{
	if(confirm("是否确认提交订单"))
	{
		var num='<?php echo $num?>';
		if(num<=0)
		{
			alert('购票数量不能为0！');
		}
		else
		{
			
		$.ajax({
		    type: "post",  //数据提交方式（post/get）
		    url: "subselproduct.class.php",  //提交到的url
		    data: {id:'<?php echo $id?>',name:'<?php echo $name?>',phone:<?php echo $phone?>,date:'<?php echo $play_date?>',num:<?php echo $num?>},//提交的数据
		    dataType: "json",//返回的数据类型格式
		    success: function(msg){
			    if(msg)
				{
					if(msg['status'])
					{
						//调用微信
						var obj = eval('(' + msg['api'] + ')');
						callpay(obj);
					}
					else
					{
						alert('活动已结束。');
						//alert('提交失败,请重新识别分销商二维码，下订单');
					}
				}
				else
				{
					alert('提交失败,请检查信息无误后请再次提交');
					
				}
		 		
		    },
		    error:function(msg){
		      alert("网络错误");
		    }
			});
		}
	}
}

//调用微信JS api 支付
function jsApiCall(param)
{
	WeixinJSBridge.invoke(
		'getBrandWCPayRequest',
		param,
		function(res){
		
			if(res.err_msg == 'get_brand_wcpay_request:ok')
			{
				alert("支付成功！");
				location.href="zzgp.php";
			}
			else if(res.err_msg == 'get_brand_wcpay_request:cancel')
			{
				alert("支付失败！");
				location.href="zzgp.php";
			}
			else if(res.err_msg == 'get_brand_wcpay_request:fail')
			{				
				alert("支付失败！");	
				location.href="zzgp.php";
			}
			//WeixinJSBridge.log(res.err_msg);
			//alert(res.err_code+res.err_desc+res.err_msg);
		}
	);
}

function callpay(param)
{
	if (typeof WeixinJSBridge == "undefined"){
		if( document.addEventListener ){
			document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
		}else if (document.attachEvent){
			document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
			document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
		}
	}else{
		jsApiCall(param);
	}
}
</script>
<title>订单确认</title>
<style type="text/css">
body {
	margin: 0px;
	background-color: #fff;
}
.font1{ font-family:"微软雅黑"; font-size:16px; color:#282828}
.font2{ font-family:"微软雅黑"; font-size:16px; color:#cecece}
.font3{ font-family:"微软雅黑"; font-size:16px; color:#ff5a00}
.font4{ font-family:"微软雅黑"; font-size:16px; color:#fff}
.font4 a{ font-family:"微软雅黑"; font-size:16px; color:#fff}
</style>
</head>

<body>
<div style="width:100%; background-color:#FFF; ">
<div style="width:90%; margin:auto;">
<div class="font1" style="float:left; width:100%; height:50px; line-height:50px; "><?php echo $product_name?></div>
<div style="clear:both"></div>
</div>
</div>

<div style="width:100%; background-color:#FFF; ">

<div style="width:90%; margin:auto;">
<div class="font1" style="float:left; width:50%; height:50px; line-height:50px; border-bottom:solid 1px #d9d9d9;">单价</div>
<div class="font2" style="float:left; width:50%; height:50px; line-height:50px; border-bottom:solid 1px #d9d9d9; text-align:right;">￥<?php echo $wx_price?></div>
<div style="clear:both"></div>
</div>

<div style="width:90%; margin:auto;">
<div class="font1" style="float:left; width:50%; height:50px; line-height:50px; border-bottom:solid 1px #d9d9d9;">购买数量</div>
<div class="font2" style="float:left; width:50%; height:50px; line-height:50px; border-bottom:solid 1px #d9d9d9; text-align:right;"><?php echo $num?></div>
<div style="clear:both"></div>
</div>


<div style="width:90%; margin:auto;">
<div class="font1" style="float:left; width:50%; height:50px; line-height:50px; border-bottom:solid 1px #d9d9d9;">总价</div>
<div class="font2" style="float:left; width:50%; height:50px; line-height:50px; border-bottom:solid 1px #d9d9d9; text-align:right;">￥<?php echo $num*$wx_price?></div>
<div style="clear:both"></div>
</div>

<div style="width:90%; margin:auto;">
<div class="font1" style="float:left; width:50%; height:50px; line-height:50px; border-bottom:solid 1px #d9d9d9;">联系人</div>
<div class="font2" style="float:left; width:50%; height:50px; line-height:50px; border-bottom:solid 1px #d9d9d9; text-align:right;"><?php echo $name?></div>
<div style="clear:both"></div>
</div>

<div style="width:90%; margin:auto;">
<div class="font1" style="float:left; width:50%; height:50px; line-height:50px; border-bottom:solid 1px #d9d9d9;">手机号</div>
<div class="font2" style="float:left; width:50%; height:50px; line-height:50px; border-bottom:solid 1px #d9d9d9; text-align:right;"><?php echo $phone?></div>
<div style="clear:both"></div>
</div>

</div>
<div style="width:100%; height:30px;"></div>
<div style="width:100%; background-color:#FFF; ">
<a href="#">
<div style="width:90%; margin:auto;">
<div class="font1" style="float:left; width:100%; height:50px; line-height:50px; text-align:center; background-color:#F00; " onclick="sub()"><span class="font4">确认订单</span></div>
<div style="clear:both"></div>
</div>
</a>
</div>

</body>
</html>
