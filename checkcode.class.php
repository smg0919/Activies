<?php
session_start();
require_once ("db.php");
$code=$_POST['code'];
$phone = $_POST['phone'];
if(($code== $_SESSION['return'])&&($phone==$_SESSION['phone']))
{
	$data=true;
}
else 
{
	$data=false;
}
echo json_encode($data);//输出json数据
?>