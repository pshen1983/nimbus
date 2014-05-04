<?php
include_once ("../_obj/User.php");
include_once ("../_obj/UserT.php");
include_once ("../_obj/Conference.php");
include_once ("../utils/CommonUtil.php");
include_once ("../utils/SecurityUtil.php");

session_start();
if(!isset($_SESSION['_language'])) CommonUtil::setSessionLanguage();
SecurityUtil::mCheckLogin();

//============================================= Language ==================================================

if($_SESSION['_language'] == 'en') {
	$l_title = "ConfOne";
	$l_company = "Company: ";
	$l_position = "Position: ";
	$l_speaker = "Speaker";
}
else if($_SESSION['_language'] == 'zh') {
	$l_title = "会云网";
	$l_company = "公司: ";
	$l_position = "职位: ";
	$l_speaker = "演讲嘉宾";
}

//=========================================================================================================

if( !isset($_GET['c']) || !is_numeric($_GET['c']) )
{
	header( 'Location: ../m.home/index.php' );
	exit;
}

$pageTitle = $l_speaker;

$ids='';
$speaIds = Conference::getConfSpeakerIds($_GET['c']);

$speakers = array();
while($row = mysql_fetch_array($speaIds, MYSQL_ASSOC))
{
	$speakers[$row['uid']] = null;
	$ids = $ids.$row['uid'].', ';
}
$ids = $ids.'0';

$speakerT = UserT::mGetRangeConfUsers($_GET['c'], $ids);
foreach($speakerT as $speaker)
{
	$speakers[$speaker->getUid()] = $speaker;
}

$ids = '';
foreach ($speakers as $k=>$v)
{
	if( !isset($speakers[$k]) )
		$ids = $ids.$k.', ';
}
$ids = $ids.'0';

$speakerT = User::mGetRangeUsers($ids);
foreach($speakerT as $speaker)
{
	$speakers[$speaker->getUid()] = $speaker;
}
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
<div data-role="content" data-theme="d">
<ul data-role="listview" data-theme="c">
<?php foreach ($speakers as $spea) {
$isUserT = (get_class($spea)=='UserT');?>
<li>
<a href="../m.conference/user.php?c=<?php echo $_GET['c']?>&u=<?php echo $spea->getUid()?>" data-transition="slide">
<img style="margin:12px 0 0 15px;float:left;-webkit-box-shadow:0 0 3px #333;-moz-box-shadow:0 0 3px #333;box-shadow:0 0 3px #333;width:60px;" src="<?php echo $spea->pic?>" />
<label style="font-weight:bold;"><?php echo $spea->fullname;?></label><br />
<span style="font-size:.7em;">
<?php echo $l_company?><span style="font-weight:normal;"><?php echo $spea->company?></span><br />
<?php echo $l_position?><span style="font-weight:normal;"><?php echo $spea->title;?></span>
</span>
</a>
</li>
<?php }?>
</ul>
</div>
<?php include_once '../m.common/footer1.inc.php';?>
</div>
</body>
</html>