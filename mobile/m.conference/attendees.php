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
	$l_company = "Firm: ";
	$l_position = "Title: ";
	$l_attendee = "Attendees";
	$l_join = "Join Conference";
	$l_apply = "The event organizer need to manually put you in this event, ConfOne has informed the organizer. Please wait and check your email or ConfOne message box for acceptance.";
}
else if($_SESSION['_language'] == 'zh') {
	$l_title = "会云网";
	$l_company = "公司: ";
	$l_position = "职位: ";
	$l_attendee = "参会者";
	$l_join = "参加会议活动";
	$l_apply = "参加该活动需要活动举办方同意，会云已经通知举办方您有意参与他们的活动。请关注您的邮箱和站内信，等候回复。";
}

//=========================================================================================================

$isJoined = Conference::isJoined($_GET['c'], $_SESSION['_userId']) ||
			Conference::isUserConfById($_SESSION['_userId'], $_GET['c']);

if( !isset($_GET['c']) || !is_numeric($_GET['c']) )
{
	header( 'Location: ../m.home/index.php' );
	exit;
}

$ids='';
$pageTitle = $l_attendee;

if ($isJoined)
{
	$atteIds = Conference::getConfAttendeeIds($_GET['c']);
	
	$attendees = array();
	while($row = mysql_fetch_array($atteIds, MYSQL_ASSOC))
	{
		$attendees[$row['uid']] = null;
		$ids = $ids.$row['uid'].', ';
	}
	$ids = $ids.'0';
	
	$attendeeT = UserT::mGetRangeConfUsers($_GET['c'], $ids);
	foreach($attendeeT as $attendee)
	{
		$attendees[$attendee->getUid()] = $attendee;
	}
	
	$ids = '';
	foreach ($attendees as $k=>$v)
	{
		if( !isset($attendees[$k]) )
			$ids = $ids.$k.', ';
	}
	$ids = $ids.'0';
	
	$attendeeT = User::mGetRangeUsers($ids);
	foreach($attendeeT as $attendee)
	{
		$attendees[$attendee->getUid()] = $attendee;
	}
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
<body <?php if(isset($_SESSION['_applyJoin'])) { unset($_SESSION['_applyJoin']); echo "onload='alert(\"".$l_apply."\")'";}?>>
<div data-role="page" data-theme="b">
<?php include_once '../m.common/header.inc.php';?>
<div data-role="content" data-theme="d">
<?php if($isJoined) {?>
<ul data-role="listview" data-theme="c">
<?php foreach ($attendees as $atte) {
$isUserT = (get_class($atte)=='UserT');?>
<li>
<a href="../m.conference/user.php?c=<?php echo $_GET['c']?>&u=<?php echo $atte->getUid()?>" data-transition="slide">
<img style="margin:12px 0 0 15px;float:left;-webkit-box-shadow:0 0 3px #333;-moz-box-shadow:0 0 3px #333;box-shadow:0 0 3px #333;width:60px;" src="<?php echo $atte->pic?>" />
<label style="font-weight:bold;"><?php echo $atte->fullname;?></label><br />
<span style="font-size:.7em;">
<?php echo $l_company?><span style="font-weight:normal;"><?php echo $atte->company?></span><br />
<?php echo $l_position?><span style="font-weight:normal;"><?php echo $atte->title;?></span>
</span>
</a>
</li>
<?php }?>
</ul>
<?php } else { echo '<a data-role="button" href="javascript:joinConf(\''.$_GET['c'].'\')" data-theme="c">'.$l_join.'</a>'; }?>
</div>
<?php include_once '../m.common/footer1.inc.php';?>
</div>
</body>
</html>