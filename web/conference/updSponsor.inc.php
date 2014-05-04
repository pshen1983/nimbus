<?php        
include_once ("../_obj/Conference.php");
include_once ("../_obj/Sponsor.php");

session_start();
$data = new stdClass();

if( isset($_SESSION['_userId']) && !empty($_SESSION['_userId']) )
{
	if( isset($_POST['upd']) && !empty($_POST['upd']) )
	{
		if( isset($_POST['upd_cname']) && !empty($_POST['upd_cname']) && 
			isset($_POST['upd_sid']) && !empty($_POST['upd_sid']) &&
			isset($_POST['upd_cid']) && !empty($_POST['upd_cid']) &&
			Conference::isUserConfById($_SESSION['_userId'], $_POST['upd_cid']) )
		{	
			$upd_filename_array = explode("?", $_POST['upd_filename']);
			$upd_filename = $upd_filename_array[0];
			//create cropped image
			if( isset($_POST['upd_x1']) && is_numeric($_POST['upd_x1']) && isset($_POST['upd_x2']) && is_numeric($_POST['upd_x2']) &&
				isset($_POST['upd_y1']) && is_numeric($_POST['upd_y1']) && isset($_POST['upd_y2']) && is_numeric($_POST['upd_y2']) &&
				$_POST['upd_x1'] != $_POST['upd_x2'] && $_POST['upd_y1'] != $_POST['upd_y2'] &&
				isset($_POST['upd_filename']) && !empty($_POST['upd_filename']) && file_exists($upd_filename) )
			{
				$src = $upd_filename;
				$src_size = getimagesize($src);
				$src_w = $src_size[0];
				$src_h = $src_size[1];
				$src_type = $src_size[2]; // image type, 1 gif, 2 jpg, 3 png
				
				$x1 = $_POST['upd_x1'];
				$x2 = $_POST['upd_x2'];
				$y1 = $_POST['upd_y1'];
				$y2 = $_POST['upd_y2'];
				if ($x2 == 9.9 || $y2 == 9.9)
				{					
					$x2 = $src_w;
					$y2 = $src_h;	
				}
					
				$targ_w = $x2-$x1;
				$targ_h = $y2-$y1;
				
				$dst_r = ImageCreateTrueColor( $targ_w, $targ_h );
			
				$sqlPic = "../tmp/temp_spon_img_crop.".$_POST['upd_sid'];
			
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
			  	//unlink($upd_filename);
			}
			else 
			{
				$image = null;
			}
			
			$result=Sponsor::update($_POST['upd_sid'], $_POST['upd_cid'], $_POST['upd_cname'], $_POST['upd_url'], $image);
			$data->error = $result;
			if ($result > 0)
			{	
				if( isset($image) )
				{					
					$data->img = '../tmp/temp_spon_img_crop.'.$result.'?t='.time();														
				}
				else 
				{
					$data->img = '../image/default/default_evn_pic.png';
				}
			}			
		}
		else
		{
			$data->error = -3;//missing page input
		}
	}
	else
	{
		$data->error = -4; //not post
	}
}
else
{      
	$data->error = -5; //session expire
}
echo json_encode($data);
?>  
