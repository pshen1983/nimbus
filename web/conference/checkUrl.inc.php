<?php
include_once ("../_obj/Conference.php");
include_once ("../utils/CommonUtil.php");

session_start();

if( isset($_SESSION['_userId']) )
{
	if( isset($_GET['url']) && !empty($_GET['url']) )
	{
		if( CommonUtil::checkConfUrlFormat($_GET['url']) ) {
			echo Conference::isUrlExist($_GET['url']) ? '4' : '0';
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