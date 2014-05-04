<?php        
include_once ("../_obj/Broadcast.php");
include_once ("../_obj/Conference.php");

session_start();

if( isset($_SESSION['_userId']) && !empty($_SESSION['_userId']) )
{
	if( isset($_POST['id']) && !empty($_POST['id']) &&
		isset($_POST['cid']) && !empty($_POST['cid']) &&
		Conference::isUserConfById($_SESSION['_userId'], $_POST['cid']))
	{		
		if( Broadcast::delete($_POST['id']) )
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