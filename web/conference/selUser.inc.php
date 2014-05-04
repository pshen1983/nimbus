<?php
include_once ("../_obj/User.php");
include_once ("../_obj/UserT.php");

session_start();
$data = new stdClass();

if( isset($_SESSION['_userId']) && !empty($_SESSION['_userId']) )
{
	if( isset($_POST['ins_cid']) && !empty($_POST['ins_cid']) && isset($_POST['ins_email']) && !empty($_POST['ins_email']) )
	{
		$UserT = UserT::getConfUserByEmail($_POST['ins_cid'], $_POST['ins_email']);
		if ($UserT == null)
		{
			$User = User::getUserByEmail($_POST['ins_email']);
			if ($User != null)
			{
				$pic = explode("?", $User->pic);
				if ($pic[0] != "../image/default/default_pro_pic.png")
				{
					copy ( $pic[0], '../tmp/temp_atte_img.'.$_POST['ins_cid'] );			
				}

				$data->error = 0;
				$data->pic = $User->pic;
				$data->cell = addslashes($User->cell);
				$data->fname = addslashes($User->fullname);
				$data->comp = addslashes($User->company);
				$data->title = addslashes($User->title);
				if ($User->description == null)
				{
					$User->description = '';
				}
				$data->desc = ($User->description);
			}
			else
			{
				$data->error = -3; //not in user table
			}
		}
		else
		{
			$pic = explode("?", $UserT->pic);
			if ($pic[0] != "../image/default/default_pro_pic.png")
			{
				copy ( $pic[0], '../tmp/temp_atte_img.'.$_POST['ins_cid'] );			
			}
			
			$data->error = 0;
			$data->pic = $UserT->pic;
			$data->cell = addslashes($UserT->cell);
			$data->fname = addslashes($UserT->fullname);
			$data->comp = addslashes($UserT->company);
			$data->title = addslashes($UserT->title);
			if ($UserT->description == null)
			{
				$UserT->description = '';
			}
			$data->desc = ($UserT->description);					
		}
	}
	else
	{
		$data->error = -4; //no email entered
	}
}
else
{   
	$data->error = -5; //session expire
}
echo json_encode($data);
?>