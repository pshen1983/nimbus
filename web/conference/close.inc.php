<?php
include_once ("../_obj/Conference.php");
include_once ("../utils/SecurityUtil.php");

session_start();
if( isset($_SESSION['_userId']) && !empty($_SESSION['_userId']) )
{
	if( isset($_GET['c']) && !empty($_GET['c']) && isset($_GET['url']) && !empty($_GET['url']) )
	{
		if(Conference::isUserConfById($_SESSION['_userId'], $_GET['c']))
		{
			if( Conference::closeConf($_GET['c']) )
			{
				unset($_SESSION['_confsEdit'][$_GET['url']]);
				echo 0;
			}
			else {
				echo 4;
			}
		}
		else {
			echo 3;
		}
	}
	else {
		echo 2;
	}
}
else {
	echo 1;
}
?>