<?php
include_once ("DatabaseUtil.php");
include_once ("CommonUtil.php");

class SessionUtil
{
	public static function initSession()
	{
		$_SESSION['_color'] = array();
		$_SESSION['_color'][0] = '#000080';
		$_SESSION['_color'][1] = '#000080';
		$_SESSION['_color'][2] = '#000080';
		$_SESSION['_color'][3] = '#000080';
		$_SESSION['_color'][4] = '#000080';
		$_SESSION['_color'][5] = '#000080';
		$_SESSION['_color'][6] = '#000080';
		$_SESSION['_color'][7] = '#000080';
		$_SESSION['_color'][8] = '#000080';
		$_SESSION['_color'][9] = '#000080';
		$_SESSION['_color'][10] = '#000080';
		$_SESSION['_color'][11] = '#000080';
		$_SESSION['_color'][12] = '#000080';
		$_SESSION['_color'][13] = '#000080';
		$_SESSION['_color'][14] = '#000080';
		$_SESSION['_color'][15] = '#000080';
		$_SESSION['_color'][16] = '#000080';
		$_SESSION['_color'][17] = '#000080';
		$_SESSION['_color'][18] = '#000080';
		$_SESSION['_color'][19] = '#000080';
		$_SESSION['_color'][20] = '#000080';
		$_SESSION['_color'][21] = '#000080';
		$_SESSION['_color'][22] = '#000080';
		$_SESSION['_color'][23] = '#000080';
		$_SESSION['_color'][24] = '#000080';
		$_SESSION['_color'][25] = '#000080';
		$_SESSION['_color'][26] = '#000080';
		$_SESSION['_color'][27] = '#000080';
		$_SESSION['_color'][28] = '#000080';
		$_SESSION['_color'][29] = '#000080';
//		$_SESSION['_color'][0] = '#003366';
//		$_SESSION['_color'][1] = '#996633';
//		$_SESSION['_color'][2] = '#33CC33';
//		$_SESSION['_color'][3] = '#FF5050';
//		$_SESSION['_color'][4] = '#CC0099';
//		$_SESSION['_color'][5] = '#003300';
//		$_SESSION['_color'][6] = '#00FF99';
//		$_SESSION['_color'][7] = '#000066';
//		$_SESSION['_color'][8] = '#993300';
//		$_SESSION['_color'][9] = '#009900';
//		$_SESSION['_color'][10] = '#33CCCC';
//		$_SESSION['_color'][11] = '#FF3399';
//		$_SESSION['_color'][12] = '#99FF33';
//		$_SESSION['_color'][13] = '#663300';
//		$_SESSION['_color'][14] = '#CC0000';
//		$_SESSION['_color'][15] = '#6600CC';
//		$_SESSION['_color'][16] = '#FFFF00';
//		$_SESSION['_color'][17] = '#006699';
//		$_SESSION['_color'][18] = '#666633';
//		$_SESSION['_color'][19] = '#FF00FF';
//		$_SESSION['_color'][20] = '#0099FF';
//		$_SESSION['_color'][21] = '#993333';
//		$_SESSION['_color'][22] = '#800000';
//		$_SESSION['_color'][23] = '#660066';
//		$_SESSION['_color'][24] = '#FF9933';
//		$_SESSION['_color'][25] = '#3366FF';
//		$_SESSION['_color'][26] = '#339966';
//		$_SESSION['_color'][27] = '#9966FF';
//		$_SESSION['_color'][28] = '#003399';
//		$_SESSION['_color'][29] = '#993366';
	}

	public static function initLocation()
	{
		if(!isset($_SESSION['_provL']))
		{
			$provs = DatabaseUtil::getProvList();

			$_SESSION['_provL'] = array();
			while($row = mysql_fetch_array($provs, MYSQL_ASSOC))
			{
				$_SESSION['_provL'][$row['provid']] = $row['proname'];
			}
		}

		if(!isset($_SESSION['_prov']))
		{
			$prov = null;
			$ip = CommonUtil::get_real_ip();
			$addr = CommonUtil::ipCity($ip);
			$addr = trim($addr);
			$addr = split(' ', $addr);

			foreach($_SESSION['_provL'] as $k=>$v)
			{
				$pos = strpos($addr[0], $v);
				if($pos !== false)
				{
					$prov = array();
					$prov['pid'] = $k;
					$prov['name'] = $v;
					break;
				}
			}

			if($prov != null) 
			{
				if(!isset($_SESSION['_prov'])) $_SESSION['_prov'] = $prov['pid'];
				if(!isset($_SESSION['_geoprov'])) $_SESSION['_geoprov'] = $prov['pid'];

				if(!isset($_SESSION['_cityL']))
				{
					$cities = DatabaseUtil::getCityList($_SESSION['_prov']);

					$_SESSION['_cityL'] = array();
					while($row = mysql_fetch_array($cities, MYSQL_ASSOC))
					{
						$_SESSION['_cityL'][$row['cityid']] = $row['cityname'];
					}
				}

				$city = null;
				foreach($_SESSION['_cityL'] as $k=>$v)
				{
					$pos = strpos($addr[0].$addr[1], $v);
					if($pos !== false)
					{
						$city = array();
						$city['cid'] = $k;
						$city['name'] = $v;
						break;
					}
				}

				if($city != null)
				{
					if(!isset($_SESSION['_city'])) $_SESSION['_city'] = $city['cid'];
					if(!isset($_SESSION['_geocity'])) $_SESSION['_geocity'] = $city['cid'];

					if(!isset($_SESSION['_distL']))
					{
						$dists = DatabaseUtil::getDistList($_SESSION['_city']);
	
						$_SESSION['_distL'] = array();
						while($row = mysql_fetch_array($dists, MYSQL_ASSOC))
						{
							$_SESSION['_distL'][$row['id']] = $row['disname'];
						}
					}
				}
			}
		}
	}

	public static function isLoggedIn()
	{
		return ( isset($_SESSION['_userId']) && $_SESSION['_userId']>0 );
	}
}
?>