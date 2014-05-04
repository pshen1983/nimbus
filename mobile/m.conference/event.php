<?php
include_once ("../_obj/Conference.php");
include_once ("../_obj/Schedule.php");
include_once ("../utils/CommonUtil.php");
include_once ("../utils/SecurityUtil.php");

session_start();
if(!isset($_SESSION['_language'])) CommonUtil::setSessionLanguage();
SecurityUtil::mCheckLogin();

//============================================= Language ==================================================

if($_SESSION['_language'] == 'en') {
	$l_title = "ConfOne";
	$l_time = "Time: ";
	$l_loca = "Location: ";
	$l_note = "Note:";
	$l_sponser = "Sponsers";
}
else if($_SESSION['_language'] == 'zh') {
	$l_title = "会云网";
	$l_sponser = "赞助商";
	$l_time = "时间：";
	$l_loca = "地点：";
	$l_note = "备注：";
}

//=========================================================================================================

if( !isset($_GET['s']) || !is_numeric($_GET['s']) || 
	!isset($_GET['c']) || !is_numeric($_GET['c']) )
{
	header( 'Location: ../m.home/index.php' );
	exit;
}

$event = Schedule::mGetEvent($_GET['c'], $_GET['s']);

if( !isset($event) )
{
	header( 'Location: ../m.home/index.php' );
	exit;
}

$pageTitle = $event->summ;

$stime = substr($event->stime, 0, 5);
$etime = substr($event->etime, 0, 5);
$time = $stime.' ~ '.$etime;
?>
<!DOCTYPE html>
<html manifest="../m.common/m.confone.manifest">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; minimum-scale=1.0; user-scalable=0;" />
<meta name="format-detection" content="telephone=no" />
<title><?php echo $l_title?></title>
<link rel="apple-touch-icon" href="../m.image/cbutton.png" />
<link rel="apple-touch-icon-precomposed" href="../m.image/cbutton.png" />
<link rel="stylesheet" href="../m.css/jquery.mobile-1.0b3.css" /> 
<link rel="stylesheet" href="../m.css/generic.css" />
<script src="../m.js/jquery.min.js"></script>
<script src="../m.js/common.js"></script>
<script src="../m.js/jquery.mobile-1.0b3.js"></script> 
</head> 
<body>
<div data-role="page" data-theme="b">
<?php include_once '../m.common/header.inc.php';?>
<div data-role="content" data-theme="c">
<label style="font-weight:bold"><?php echo $event->summ;?></label>
<span style="font-weight:normal;margin-top:10px;font-size:.8em;display:block;">
<span style="font-weight:bold"><?php echo $l_time;?></span><?php echo $l_time.$event->date.' ('.$time.')';?><br />
<span style="font-weight:bold"><?php echo $l_loca;?></span><?php echo $event->addr?><br /><br />
<?php echo $event->note?>
</span>
</div>
<?php include_once '../m.common/footer1.inc.php';?>
</div>
</body>
</html>