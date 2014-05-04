<?php
include_once ("CommonUtil.php");

if(!isset($_GET['r']) || empty($_GET['r']))
{
	header( 'Location:../default/index.php' );
	exit;
}

session_start();
 
$image_file	= "../image/common/reg_check". CommonUtil::genRandomNumString(1) .".png";
$font_file 	= "../common/font/font". mt_rand(1, 2) .".ttf";

$font_size  = 12; // font size in pts
$font_color = 0x00000000 ;

$x_finalpos = 5;
$x_delta = 14;
$y_finalpos = 18;

$c1 = CommonUtil::genRandomNumString(1);
$c2 = CommonUtil::genRandomNumString(1);
$c3 = CommonUtil::genRandomNumString(1);
$c4 = CommonUtil::genRandomNumString(1);

$a1 = ((mt_rand(0, 2) == 0) ? (-1) : 1) * CommonUtil::genRandomNumString(2) / 4;
$a2 = ((mt_rand(0, 2) == 0) ? (-1) : 1) * CommonUtil::genRandomNumString(2) / 4;
$a3 = ((mt_rand(0, 2) == 0) ? (-1) : 1) * CommonUtil::genRandomNumString(2) / 4;
$a4 = ((mt_rand(0, 2) == 0) ? (-1) : 1) * CommonUtil::genRandomNumString(2) / 4;

$image =  imagecreatefrompng($image_file);
imagettftext($image, $font_size, $a1, $x_finalpos, $y_finalpos, $font_color, $font_file, $c1);
imagettftext($image, $font_size, $a2, $x_finalpos+$x_delta, $y_finalpos, $font_color, $font_file, $c2);
imagettftext($image, $font_size, $a3, $x_finalpos+2*$x_delta, $y_finalpos, $font_color, $font_file, $c3);
imagettftext($image, $font_size, $a4, $x_finalpos+3*$x_delta, $y_finalpos, $font_color, $font_file, $c4);

$image_width = imagesx($image);
$image_height = imagesy($image);

if ($a1<0) 
	$color1 = 0x00FFFFFF;
else $color1 = 0x00999999;

if ($a2<0) 
	$color2 = 0x00FFFFFF;
else $color2 = 0x00999999;

imageline ( $image , $a1 , $image_height/3 , $image_width - 1 , $image_height/3 , $color1 );
imageline ( $image , 0 , 2*$image_height/3 , $image_width - $a2 , 2*$image_height/3 , $color2 );

header('Content-type: image/png') ;
imagepng($image);
imagedestroy($image);

unset($_SESSION['_register']);
$_SESSION['_register'] = array();
$_SESSION['_register'][$_GET['r']] = $c1.$c2.$c3.$c4;
?>