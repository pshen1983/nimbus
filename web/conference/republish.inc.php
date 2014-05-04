<?php
include_once ("../_obj/Conference.php");
include_once ("../utils/SecurityUtil.php");

session_start();
SecurityUtil::checkLogin($_SERVER['REQUEST_URI']);

if( isset($_SESSION['_userId']) && !empty($_SESSION['_userId']) )
{
	if( isset($_POST['cid']) && !empty($_POST['cid']) && isset($_POST['curl']) && !empty($_POST['curl']))
	{		
		if(Conference::isUserConfById($_SESSION['_userId'], $_POST['cid']))
		{	
			if( Conference::publishConf($_POST['cid']) )
			{
				$url = explode("php?url=", $_POST['curl']);
				if( isset($_SESSION['_confsEdit']) &&  isset($_SESSION['_confsEdit'][$url[1]]) )
				{
					$_SESSION['_confsEdit'][$url[1]]->status='P';
				}	
				header( 'Location: '.$_POST['curl'] ) ;
			}
			else 
			{
				header( 'Location: ../home/index.php' ) ;
			}
		}
		else 
		{
			header( 'Location: ../home/index.php' ) ;
		}
	}
	else 
	{
		header( 'Location: ../home/index.php' ) ;
	}
}
else 
{	
	header( 'Location: ../default/login.php' ) ;
}
?>