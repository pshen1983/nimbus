<?php        
include_once ("../_obj/Conference.php");
include_once ("../_obj/Invitation.php");

session_start();

if( isset($_SESSION['_userId']) && !empty($_SESSION['_userId']) )
{
	if( isset($_POST['id']) && !empty($_POST['id']) &&
		Conference::isUserConfById($_SESSION['_userId'], $_POST['cid']))
	{		
		if( Invitation::delete($_POST['id']) )
		{
			echo 0; //success
		}
		else
		{
			echo 2; //delete fail
		}
	}
	else echo 3; //no input
}
else
{      
	echo 1; //session expire
}
?>  