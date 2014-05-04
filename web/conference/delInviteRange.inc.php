<?php        
include_once ("../_obj/Conference.php");
include_once ("../_obj/Invitation.php");

session_start();

if( isset($_SESSION['_userId']) && !empty($_SESSION['_userId']) )
{
	if( isset($_POST['ids']) && !empty($_POST['ids']) &&
		Conference::isUserConfById($_SESSION['_userId'], $_POST['cid']))
	{		
		if( Invitation::deleteRange($_POST['ids']) )
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