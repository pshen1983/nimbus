<?php        
include_once ("../_obj/Conference.php");
include_once ("../_obj/Sponsor.php");

session_start();
$data = new stdClass();

if( isset($_SESSION['_userId']) && !empty($_SESSION['_userId']) )
{
	if( isset($_POST['ins']) && !empty($_POST['ins']) )
	{
		if( isset($_POST['ins_cname']) && !empty($_POST['ins_cname']) && isset($_POST['ins_cid']) && !empty($_POST['ins_cid']) &&
			Conference::isUserConfById($_SESSION['_userId'], $_POST['ins_cid']) )
		{	
			$ins_filename_array = explode("?", $_POST['ins_filename']);
			$ins_filename = $ins_filename_array[0];
			//create cropped image
			if( isset($_POST['ins_x1']) && is_numeric($_POST['ins_x1']) && isset($_POST['ins_x2']) && is_numeric($_POST['ins_x2']) &&
				isset($_POST['ins_y1']) && is_numeric($_POST['ins_y1']) && isset($_POST['ins_y2']) && is_numeric($_POST['ins_y2']) &&
				$_POST['ins_x1'] != $_POST['ins_x2'] && $_POST['ins_y1'] != $_POST['ins_y2'] &&
				isset($_POST['ins_filename']) && !empty($_POST['ins_filename']) && file_exists($ins_filename) )
			{
				$src = $ins_filename;
				$src_size = getimagesize($src);
				$src_w = $src_size[0];
				$src_h = $src_size[1];
				$src_type = $src_size[2]; // image type, 1 gif, 2 jpg, 3 png
				
				$x1 = $_POST['ins_x1'];
				$x2 = $_POST['ins_x2'];
				$y1 = $_POST['ins_y1'];
				$y2 = $_POST['ins_y2'];	
					
				$targ_w = $x2-$x1;
				$targ_h = $y2-$y1;	
				
				$dst_r = ImageCreateTrueColor( $targ_w, $targ_h );
			
				$sqlPic = "../tmp/temp_spon_img_croptemp.".$_POST['ins_cid'];
			
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
			  	//unlink($ins_filename);
			}
			else 
			{
				$image = null;
			}
			
			$result=Sponsor::create($_POST['ins_cid'], $_POST['ins_cname'], $_POST['ins_url'], $image);
			$data->error = $result;
			if ($result > 0)
			{	
				if( isset($image) )
				{
					if ( rename ( $sqlPic, '../tmp/temp_spon_img_crop.'.$result ) )
					{
						$data->img = '../tmp/temp_spon_img_crop.'.$result.'?t='.time();
					}
					else
					{
						$data->img = '../image/default/default_evn_pic.png';
					}											
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
