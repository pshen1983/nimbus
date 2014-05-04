<?php
include_once ("../utils/CommonUtil.php");
include_once ("../utils/DatabaseUtil.php");

session_start();
if(isset($_SESSION['_userId']))
{
	if(isset($_GET['id']) && !empty($_GET['id']) && $_GET['id']!=$_SESSION['_dist'])
	{
		$_SESSION['_dist'] = $_GET['id'];
	}
	else {
		echo 0;
	}
}
else {
	echo 1;
}
?>