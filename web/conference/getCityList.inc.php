<?php
include_once ("../utils/DatabaseUtil.php");

session_start();
if(isset($_SESSION['_userId']))
{
	if(isset($_GET['id']) && !empty($_GET['id']) && $_GET['id']!=$_SESSION['_prov'])
	{
		$_SESSION['_prov'] = $_GET['id'];
		$cities = DatabaseUtil::getCityList($_SESSION['_prov']);

		unset($_SESSION['_city']);
		unset($_SESSION['_dist']);
		unset($_SESSION['_cityL']);
		unset($_SESSION['_distL']);

		$_SESSION['_cityL'] = array();

		while($row = mysql_fetch_array($cities, MYSQL_ASSOC))
		{
			$_SESSION['_cityL'][$row['cityid']] = $row['cityname'];
		}

		echo '<option value="0" >--</option>';
		CommonUtil::printOptions($_SESSION['_cityL'], $_SESSION['_city'], false);
	}
	else {
		echo 0;
	}
}
else {
	echo 1;
}
?>