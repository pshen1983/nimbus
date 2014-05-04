<?php
include_once ("../_obj/User.php");
include_once ("../_obj/UserT.php");

session_start();
$data = new stdClass();

if( isset($_SESSION['_userId']) && !empty($_SESSION['_userId']) )
{
	if( isset($_POST['ins_cid']) && !empty($_POST['ins_cid']) && isset($_POST['ins_cell']) && !empty($_POST['ins_cell']) )
	{
		$UserT = UserT::getConfUserByEmail($_POST['ins_cid'], $_POST['ins_cell']);
		if ($UserT == null)
		{
			$User = User::getUserByEmail($_POST['ins_cell']);
			if ($User != null)
			{
				$data->error = 0;
				$data->email = addslashes($User->email);
				$data->fname = addslashes($User->fullname);
				$data->comp = addslashes($User->company);
				$data->title = addslashes($User->title);
			}
			else
			{
				$data->error = -3; //not in user table
			}
		}
		else
		{			
			$data->error = 0;
			$data->email = addslashes($UserT->email);
			$data->fname = addslashes($UserT->fullname);
			$data->comp = addslashes($UserT->company);
			$data->title = addslashes($UserT->title);			
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