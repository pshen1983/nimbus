<?php
include_once ("../_obj/Conference.php");
include_once ("SecurityUtil.php");

session_start();
SecurityUtil::checkLogin($_SERVER['REQUEST_URI']);

if( isset($_POST['x1']) && is_numeric($_POST['x1']) && isset($_POST['x2']) && is_numeric($_POST['x2']) &&
	isset($_POST['y1']) && is_numeric($_POST['y1']) && isset($_POST['y2']) && is_numeric($_POST['y2']) &&
	isset($_POST['fname']) && !empty($_POST['fname']) && isset($_POST['url']) && !empty($_POST['url']) &&
	file_exists($_POST['fname']) && Conference::isUserConfById($_SESSION['_userId'], $_POST['simg_cid']) )
{
	$src = $_POST['fname'];
	$src_size = getimagesize($src);
	$src_w = $src_size[0];
	$src_h = $src_size[1];
	$src_type = $src_size[2]; // image type, 1 gif, 2 jpg, 3 png
	$targ_w = 130;
	$targ_h = 80;	
	
	$x1 = $_POST['x1'];
	$x2 = $_POST['x2'];
	$y1 = $_POST['y1'];
	$y2 = $_POST['y2'];	

	$dst_r = ImageCreateTrueColor( $targ_w, $targ_h );

	$sqlPic = "../tmp/temp_conf_img_crop130x80.".$_POST['simg_cid'];

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
	
	if (!Conference::updateImg($_POST['simg_cid'], $image, false))
	{
		echo 2;	
	}
	else
	{
		$newPic = "../tmp/temp_conf_img_crop130x80.".$_POST['simg_cid']."?t=".time();
		if( isset($_SESSION['_confsEdit'][$_POST['url']]) && 
			$_SESSION['_confsEdit'][$_POST['url']]->getCid() == $_POST['simg_cid'])
		{
			$_SESSION['_confsEdit'][$_POST['url']]->img = $newPic;
		}
		echo $newPic;
	}

  	unlink($_POST['fname']);
}
else {
	echo 1;
}
?>