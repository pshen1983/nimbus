<?php
include_once ("../utils/CommonUtil.php");
include_once ("../utils/SessionUtil.php");

if(!isset($_SESSION)) session_start();

if( isset($_GET['l']) && !empty($_GET['l']) && strlen($_GET['l'])==2 )
{
	if( CommonUtil::isSupportLanguage($_GET['l']) )
	{
		$_SESSION['_language'] = $_GET['l'];
		if (isset($_SESSION['_userId'])) SessionUtil::initSession();
		CommonUtil::setLanguageCookie($_GET['l']);
		echo 0;
	}
	else echo 1;
}
else echo 2
?>