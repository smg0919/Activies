<?php
include "../../../Tools/phpqrcode/phpqrcode.php";
Header( "Content-type: image/jpeg");

define("LEFT", 1);
define("CENTER", 2);
define("RIGHT", 3);
define("FONT_MSYH", '/data/web/fonts/MSYH.TTF');
define("FONT_FZDH", '/data/web/fonts/FZDHTJW.ttf');
define(DB_HOST, 'localhost');  
define(DB_USER, 'root');  
define(DB_PASS, 'symiao');  
define(DB_DATABASENAME, 'jj_wq_app'); 

$uid = $_GET["uid"];

$rspImage = new Imagick();
$rspImage->newImage(485, 860, new ImagickPixel('white'));
$rspImage->setImageFormat('jpg'); 

$imgbk = new Imagick('/data/kxhotspring/api/Wechat/actives/A001/hd_bk.jpg');
$rspImage->compositeImage($imgbk, Imagick::COMPOSITE_DEFAULT, 0, 0);
$imgbk->destroy();

$conn = mysql_connect(DB_HOST, DB_USER, DB_PASS);
mysql_select_db(DB_DATABASENAME, $conn);
mysql_query("SET NAMES 'utf8'");  
$sql = "select reg_from_admin from `wq_user` where `id`='".$uid."'";
$res = mysql_query($sql);
$compid = 0;
if($res)
{
	$row = mysql_fetch_array($res);
	if ($row)
	{
		$compid = intval($row['reg_from_admin']);
	}
}
/*
$conn = mysql_connect(DB_HOST, DB_USER, DB_PASS);
mysql_select_db(DB_DATABASENAME, $conn);
mysql_query("SET NAMES 'utf8'");  
$sqlpic = "select `username`, `logo` from `wq_user` where `id`='".$uid."'";
$res = mysql_query($sqlpic);
if($res)
{
	$row = mysql_fetch_array($res);
	if ($row)
	{
		$addr = '/data/kxhotspring/api/upload/'.$row['logo'];
		if($row['logo'] == '')
		{
		}
		$username = $row['username'];
	}
}
if(!file_exists($addr) || is_dir($addr))
{
	$addr = '/data/kxhotspring/api/Tools/logo.png';
}

$img = new Imagick($addr);
$img->cropthumbnailimage(85, 85);
$rspImage->compositeImage($img, Imagick::COMPOSITE_DEFAULT, 35, 670);
$img->destroy();

$img = new Imagick();
$img->newImage(200, 100, new ImagickPixel('white'));
$img->setImageFormat('jpg');

$draw = new ImagickDraw();
$draw->setFont(FONT_MSYH);
$draw->setFontSize(20);
$draw->setFillColor('black');
$draw->setTextAlignment(\Imagick::ALIGN_CENTER);
$draw->annotation(100, 50, $username);
$img->drawImage($draw);

$rspImage->compositeImage($img, Imagick::COMPOSITE_DEFAULT, 140, 670);
$draw->destroy();
$img->destroy();
*/
$value="http://www.kxhotspring.com/api/Wechat/actives/A001/zzgp.php?user_id=".$uid."&reg_from_admin=".$compid."&buy_type=3";
$errorCorrectionLevel = "Q";
$matrixPointSize = "22";
$qr_file="QRCode/".$uid.'.png';
QRcode::png($value, $qr_file, $errorCorrectionLevel, $matrixPointSize, "2");

$img = new Imagick($qr_file);
$img->cropthumbnailimage(120, 120);
$rspImage->compositeImage($img, Imagick::COMPOSITE_DEFAULT, 330, 719);
$img->destroy();


$rspImage->setImageCompression(Imagick::COMPRESSION_JPEG);
$rspImage->setImageCompressionQuality(50);
$rspImage->setCompression(Imagick::COMPRESSION_JPEG);
$rspImage->setCompressionQuality(50);

echo $rspImage;
$rspImage->destroy();

?>