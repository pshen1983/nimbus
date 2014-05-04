<?php
include_once ("../_obj/User.php");
include_once ("SecurityUtil.php");

session_start();
SecurityUtil::checkLogin($_SERVER['REQUEST_URI']);

unset($_SESSION['_imgSaProErr']);

$filename_array = explode("?", $_POST['filename']);
$filename = $filename_array[0];

if( isset($_POST['x1']) && is_numeric($_POST['x1']) && isset($_POST['x2']) && is_numeric($_POST['x2']) &&
	isset($_POST['y1']) && is_numeric($_POST['y1']) && isset($_POST['y2']) && is_numeric($_POST['y2']) &&
	$_POST['x1'] != $_POST['x2'] && $_POST['y1'] != $_POST['y2'] &&
	isset($filename) && !empty($filename) && file_exists($filename) && isset($_POST['fpage']) && !empty($_POST['fpage']) )
{
	$src = $filename;
	$src_size = getimagesize($src);
	$src_w = $src_size[0];
	$src_h = $src_size[1];
	$src_type = $src_size[2]; // image type, 1 gif, 2 jpg, 3 png
	$targ_w = 100;
	$targ_h = 100;	
	
	$x1 = $_POST['x1'];
	$x2 = $_POST['x2'];
	$y1 = $_POST['y1'];
	$y2 = $_POST['y2'];	

	$dst_r = ImageCreateTrueColor( $targ_w, $targ_h );

	$sqlPic = User::$picPath.$_SESSION['_userId'];

	if ($src_type == 1) //gif
	{
		$img_r = imagecreatefromgif($src);	
		imagecopyresampled($dst_r,$img_r,0,0,$x1,$y1,$targ_w,$targ_h,$x2-$x1,$y2-$y1);	
		imagegif($dst_r, $sqlPic);				
	}
	else if ($src_type == 2) //jpg
	{
		$img_r = imagecreatefromjpeg($src);	
		imagecopyresampled($dst_r,$img_r,0,0,$x1,$y1,$targ_w,$targ_h,$x2-$x1,$y2-$y1);
		imagejpeg($dst_r, $sqlPic, 90);	
	}
	else if ($src_type == 3)
	{
		$img_r = imagecreatefrompng($src);	
		imagecopyresampled($dst_r,$img_r,0,0,$x1,$y1,$targ_w,$targ_h,$x2-$x1,$y2-$y1);	
		imagepng($dst_r, $sqlPic, 9);				
	}

	$fp = fopen($sqlPic, 'r');
	$image = fread($fp, filesize($sqlPic));
	$image = addslashes($image);
	fclose($fp);

	if( $_SESSION['_loginUser']->updatePic($image) )
		$_SESSION['_loginUser']->pic = $sqlPic.'?t='.time();
	else $_SESSION['_imgSaProErr'] = 2;

  	unlink($filename);
  	imagedestroy($dst_r);
}
else {
	$_SESSION['_imgSaProErr'] = 1;
}

header( 'Location: '.$_POST['fpage'] ) ;
?>