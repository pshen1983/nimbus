<?php        
include_once ("../_obj/User.php");
include_once ("../_obj/UserT.php");
include_once ("../_obj/Conference.php");

session_start();

if($_SESSION['_language'] == 'en') {
	$l_title = "ConfOne";
	$l_error1 = "Please enter all required information";
	$l_error2 = "Invalid cellphone number";
	$l_error3 = "Please enter all required information";
	$l_error7 = "Please do not enter special character in all fields";
	$l_error8 = "Invalid email address";
	$l_return = "Return to previous page";
}
else if($_SESSION['_language'] == 'zh') {
	$l_title = "会云网";
	$l_error1 = "请填写所有必填信息";
	$l_error2 = "手机号码格式有误";
	$l_error3 = "请填写所有必填信息";
	$l_error7 = "请不要在所填信息中使用特殊符号";
	$l_error8 = "邮箱格式有误";
	$l_return = "返回上一页";
}
if( isset($_SESSION['_userId']) && !empty($_SESSION['_userId']) )
{
	if( isset($_POST['ins']) && !empty($_POST['ins']) )
	{
		if( isset($_POST['ins_email']) && !empty($_POST['ins_email']) &&
			isset($_POST['ins_fullname']) && !empty($_POST['ins_fullname']) &&
			isset($_POST['ins_cid']) && !empty($_POST['ins_cid']) &&
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
				$targ_w = 100;
				$targ_h = 100;	
				
				$x1 = $_POST['ins_x1'];
				$x2 = $_POST['ins_x2'];
				$y1 = $_POST['ins_y1'];
				$y2 = $_POST['ins_y2'];	
					
				$dst_r = ImageCreateTrueColor( $targ_w, $targ_h );
			
				$sqlPic = "../tmp/temp_atte_img_crop100x100.".$_POST['ins_cid'];
			
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
			
			$UserUID=User::registeredEmail($_POST['ins_email']);			
			if($UserUID) // if user exist
			{
				$upd_ins_userT_success = 0; // if insert/update userT success, set to 1
				//insert into user temp table if information is changed
				$UserT_insert = UserT::createUserT($_POST['ins_cid'], $UserUID['uid'], trim($_POST['ins_email']), $_POST['ins_fullname'], $image, $_POST['ins_desc'], $_POST['ins_comp'], $_POST['ins_title'], $_POST['ins_cell'], null);
				if ($UserT_insert == 2) // user in user temp table, update user temp
				{
					$UserT_update=UserT::updateUserT($_POST['ins_cid'], $UserUID['uid'], $_POST['ins_fullname'], $image, $_POST['ins_desc'], $_POST['ins_comp'], $_POST['ins_title'], trim($_POST['ins_email']), $_POST['ins_cell'], null);
					// 0 success
					if ($UserT_update == 2) // 2 update fail
					{
				   		$output = $l_error7.'  <a href="'.$_POST['ins_page'].'">'.$l_return.'</a>';
				   		exit;
					}
					else if ($UserT_update == 1) // 1 missing  cid, uid, fullname
					{
				   		$output = $l_error3.'  <a href="'.$_POST['ins_page'].'">'.$l_return.'</a>';
				   		exit;
					}
					else if ($UserT_update == 3) // 3 invalid email format
					{
				   		$output = $l_error8.'  <a href="'.$_POST['ins_page'].'">'.$l_return.'</a>';
				   		exit;
					}
					else //if ($UserT_update == 0) // 0 success
					{
						$upd_ins_userT_success = 1;
					}									
				}
				else if ($UserT_insert == 3) // insert fail
				{
				   	$output = $l_error7.'  <a href="'.$_POST['ins_page'].'">'.$l_return.'</a>';
				   	exit;		
				}
				else if ($UserT_insert == 1) // missing cid, uid, fullname
				{
				   	$output = $l_error3.'  <a href="'.$_POST['ins_page'].'">'.$l_return.'</a>';
				   	exit;					
				}
				else if ($UserT_insert == 4) // invalid email format
				{
				   	$output = $l_error8.'  <a href="'.$_POST['ins_page'].'">'.$l_return.'</a>';
				   	exit;					
				}
				else // if ($UserT_insert == 0) // success
				{
					$upd_ins_userT_success = 1;						
				}	
				if ($upd_ins_userT_success == 1)
				{
					// insert into relation table
					Conference::addAttendee($_POST['ins_cid'], $UserUID['uid']);
					if( isset($image) )
					{
						rename ( $sqlPic, '../tmp/temp_user_t_img.'.$_POST['ins_cid'].'.'.$UserUID['uid'] );																
					}					
					header( 'Location: '.$_POST['ins_page'] ) ;
					exit;
				}
			}
			else // user not exist
			{
				//add user
				$resultU = User::addUser($_POST['ins_cell'], trim($_POST['ins_email']), $_POST['ins_fullname'], $image, $_POST['ins_comp'], $_POST['ins_title'], $_POST['ins_desc']);
				if ($resultU == 0)
				{
					// get uid, insert into relation table
					$UserUID2=User::registeredEmail($_POST['ins_email']);
					if($UserUID2)
					{
						// insert into relation table
						Conference::addAttendee($_POST['ins_cid'], $UserUID2['uid']);
						if( isset($image) )
						{
							rename ( $sqlPic, '../tmp/temp_profile_img_crop100.'.$UserUID2['uid'] );												
						}
						header( 'Location: '.$_POST['ins_page'] ) ;
						exit;					
					}
				}
				else if ($resultU == -1)
				{
				   	$output = $l_error1.'  <a href="'.$_POST['ins_page'].'">'.$l_return.'</a>';
				   	exit;
				}
				else if ($resultU == -2)
				{
				   	$output = $l_error2.'  <a href="'.$_POST['ins_page'].'">'.$l_return.'</a>';
				   	exit;
				}
				else if ($resultU == -7)
				{
				   	$output = $l_error7.'  <a href="'.$_POST['ins_page'].'">'.$l_return.'</a>';
				   	exit;
				}
				else if ($resultU == -8)
				{
				   	$output = $l_error8.'  <a href="'.$_POST['ins_page'].'">'.$l_return.'</a>';
				   	exit;
				}			
			}
		}
		else
		{
			//$data->error = -3; //missing page input
	   		$output = $l_error3.'  <a href="'.$_POST['ins_page'].'">'.$l_return.'</a>';
	   		exit;
		}
	}
	else
	{
		//$data->error = -4; //not post
	    header( 'Location: '.$_POST['ins_page'] );
		exit;
	}
}
else
{      
	//$data->error = -5; //session expire
	header( 'Location: ../default/login.php' );
	exit;
}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo $l_title?></title><link rel="shortcut icon" href="../image/favicon.ico" />
</head>
<body>
<?php
echo $output;
?>
</body>
</html>