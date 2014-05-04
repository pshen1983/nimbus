<?php
include_once ("../utils/CommonUtil.php");
include_once ("../utils/SecurityUtil.php");
include_once ("../_obj/Conference.php");

session_start();
SecurityUtil::mCheckLogin();

//============================================= Language ==================================================

if($_SESSION['_language'] == 'en') {
	$l_title = "ConfOne";
	$l_confone = "ConfOne";
	$l_back = "Back";
	$l_home = "Home";
	$l_time = "Time: ";
	$l_loca = "Location: ";
	$l_page = "Events Near You";
	$l_no_event = "There is no event near you";
	$l_ptitle = "Near You";
}
else if($_SESSION['_language'] == 'zh') {
	$l_title = "会云网";
	$l_confone = "会云网";
	$l_back = "返回";
	$l_home = "主页";
	$l_time = "时间：";
	$l_loca = "地点：";
	$l_page = "附近的活动";
	$l_no_event = "您附近没有会议活动";
	$l_ptitle = "附近";
}

//=========================================================================================================
$pageTitle = $l_ptitle;
?>
<!DOCTYPE html>
<html manifest="../m.common/m.confone.manifest">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; minimum-scale=1.0; user-scalable=0;" />
<meta name="format-detection" content="telephone=no" />
<title><?php echo $l_title;?></title>
<link rel="apple-touch-icon" href="../m.image/cbutton.png" />
<link rel="apple-touch-icon-precomposed" href="../m.image/cbutton.png" />
<link rel="stylesheet" href="../m.css/jquery.mobile-1.0b3.css" /> 
<link rel="stylesheet" href="../m.css/generic.css" />
<script type="text/javascript" src="../m.js/jquery.min.js"></script>
<script type="text/javascript" src="../m.js/common.js"></script>
<script type="text/javascript" src="../m.js/conference.js"></script>
<script type="text/javascript" src="../m.js/jquery.mobile-1.0b3.js"></script>
</head> 
<body>
<div data-role="page" data-theme="b">
<div data-role="header" data-theme="b">
<?php include_once '../m.common/header.inc.php';?>
</div>
<div id="cList" data-role="content" data-theme="d">
<?php
$confs = array();
if( isset($_GET['lat']) && !empty($_GET['lat']) && isset($_GET['lng']) && !empty($_GET['lng']) )
{
	echo '<ul data-role="listview" data-theme="c">';

	$range = CommonUtil::getLatLongRange($_GET['lat'], $_GET['lng'], 3);
	$lat1 = $_GET['lat']-$range[0];
	$lat2 = $_GET['lat']+$range[0];
	$lng1 = $_GET['lng']-$range[1];
	$lng2 = $_GET['lng']+$range[1];

	//neighbor
	$confs = Conference::getNeighborConfInfoWithin($lat1, $lng1, $lat2, $lng2, 'P');

	$ind = 0;
	foreach ($confs as $conf) 
	{
		$sftime = strtotime($conf->stime);
		$sdate = date('Y.m.d', $sftime);
		$stime = date('H:i', $sftime);
		$tprint = $sdate.(($stime!='00:00') ? ' '.$stime : '');
	
		if( isset($conf->etime) && !empty($conf->etime) )
		{
			$eftime = strtotime($conf->etime);
			$edate = date('Y.m.d', $eftime);
			$etime = date('H:i', $eftime);
			$tprint = $tprint.' ~ '.$edate.(($etime!='00:00') ? ' '.$etime : '');
		}
?>
<li>
<a href="../m.conference/index.php?c=<?php echo $conf->getCid();?>" data-transition="slide">
<label><?php echo $conf->name; ?></label>
<span style="margin-top:10px;font-size:.8em;display:block;">
<span style="font-weight:bold"><?php echo $l_time;?></span><?php echo $tprint?><br />
<span style="font-weight:bold"><?php echo $l_loca;?></span><?php echo $conf->addr;?>
</span></a>
</li><?php
		$ind++;
	}

	echo '</ul>';
}
else {
	echo '<div style="color:#589ED0">'.$l_no_event.'</div>';
}?>
</div>
<?php include_once '../m.common/footer1.inc.php';?>
</div>
</body>
</html>