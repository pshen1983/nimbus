<?php        
include_once ("../_obj/Schedule.php");

session_start();

if( isset($_SESSION['_userId']) && !empty($_SESSION['_userId']) )
{
	if( isset($_POST['id']) && !empty($_POST['id']) )
	{		
		if( Schedule::delete($_POST['id'], $_SESSION['_userId']) )
		{
			echo 0; //success
		}
		else
		{
			echo 2; //delete fail
		}
	}
	else echo 3; //no sid
}
else
{      
	echo 1; //session expire
}
?>  