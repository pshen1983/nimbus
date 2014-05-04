<?php        
include_once ("../_obj/Conference.php");
include_once ("../_obj/Sponsor.php");

session_start();

if( isset($_SESSION['_userId']) && !empty($_SESSION['_userId']) )
{
	if( isset($_POST['sid']) && !empty($_POST['sid']) &&
		isset($_POST['cid']) && !empty($_POST['cid']) &&
		Conference::isUserConfById($_SESSION['_userId'], $_POST['cid']))
	{		
		if( Sponsor::delete($_POST['sid']) )
		{	
			unlink(Sponsor::$picPath.$_POST['sid']);
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