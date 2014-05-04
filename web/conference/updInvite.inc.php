<?php
include_once ("../_obj/Invitation.php");
include_once ("../_obj/Conference.php");

session_start();
$data = new stdClass();

if( isset($_SESSION['_userId']) && !empty($_SESSION['_userId']) )
{
	if( isset($_POST['upd']) && !empty($_POST['upd']) )
	{
		if( isset($_POST['upd_id']) && !empty($_POST['upd_id']) &&
			isset($_POST['upd_cell']) && !empty($_POST['upd_cell']) &&
			isset($_POST['upd_fullname']) && !empty($_POST['upd_fullname']) &&
			Conference::isUserConfById($_SESSION['_userId'], $_POST['upd_cid']) )
		{
			$atReturn = Invitation::update($_POST['upd_id'], $_POST['upd_fullname'], $_POST['upd_comp'], $_POST['upd_title'], $_POST['upd_email']);
		
			if ($atReturn == 1) // miss input
			{
				$data->error = -3;
			}
			else if ($atReturn == 2) // update fail
			{
				$data->error = -7;				
			}
			else if ($atReturn == 3) // invalid email format
			{
				$data->error = -8;
			}
			else
			{
				$data->error = $_POST['upd_id'];
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