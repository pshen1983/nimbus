<?php
include_once ("../utils/CommonUtil.php");
include_once ("../utils/DatabaseUtil.php");

session_start();
if(isset($_SESSION['_userId']))
{
	if(isset($_GET['id']) && !empty($_GET['id']) && $_GET['id']!=$_SESSION['_city'])
	{
		$_SESSION['_city'] = $_GET['id'];
		$dists = DatabaseUtil::getDistList($_SESSION['_city']);

		unset($_SESSION['_dist']);
		unset($_SESSION['_distL']);

		$_SESSION['_distL'] = array();

		while($row = mysql_fetch_array($dists, MYSQL_ASSOC))
		{
			$_SESSION['_distL'][$row['id']] = $row['disname'];
		}

		echo '<option value="0" >--</option>';
		CommonUtil::printOptions($_SESSION['_distL'], $_SESSION['_dist'], false);
	}
	else {
		echo 0;
	}
}
else {
	echo 1;
}
?>