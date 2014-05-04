<?php
include_once ("../_obj/Conference.php");
include_once ("../utils/CommonUtil.php");
include_once ("../utils/SecurityUtil.php");

session_start();
SecurityUtil::mCheckLogin();

//============================================= Language ==================================================

if($_SESSION['_language'] == 'en') {
	$l_title = "ConfOne";
	$l_home = "Home";
	$l_sche = "Schedule";
	$l_spea = "Speaker";
	$l_spon = "Sponser";
	$l_inte = "Social";
	$l_atte = "Attendees";
	$l_bcast = "Broadcast";
	$l_plan = "Planners";
	$l_back = "Back";
	$l_c_home = "Home";
	$l_option = "Option";
	$l_join = "Join Event";
	$l_unjoin = "Remove Event";
	$l_home = "Back Home";
	$l_cancel = "Cancel";
	$l_apply = "The event organizer need to manually put you in this event, ConfOne has informed the organizer. Please wait and check your email or ConfOne message box for acceptance.";
}
else if($_SESSION['_language'] == 'zh') {
	$l_title = "会云网";
	$l_home = "简介";
	$l_sche = "日程表";
	$l_spea = "演讲嘉宾";
	$l_spon = "赞助商";
	$l_inte = "互动";
	$l_atte = "参会者";
	$l_bcast = "附加信息";
	$l_plan = "策划团队";
	$l_back = "返回";
	$l_c_home = "主页";
	$l_option = "选项";
	$l_join = "参加活动";
	$l_unjoin = "退出活动";
	$l_home = "返回主页";
	$l_cancel = "取消";
	$l_apply = "参加该活动需要活动举办方同意，会云已经通知举办方您有意参与他们的活动。请关注您的邮箱和站内信，等候回复。";
}

//=========================================================================================================

$isJoined = Conference::isJoined($_GET['c'], $_SESSION['_userId']);
$isOwner = Conference::isUserConfById($_SESSION['_userId'], $_GET['c']);

if( !isset($_GET['c']) || !is_numeric($_GET['c']) )
{
	header( 'Location: ../m.home/index.php' );
	exit;
}

if( isset($_GET['c']) && (!isset($_SESSION['_mConfId']) || $_GET['c']!=$_SESSION['_mConfId']) )
{
	$_SESSION['_mConf'] = Conference::getBasicConfInfoWithDescription($_GET['c'], 'P');
	$_SESSION['_mConfId'] = $_GET['c'];
}

$pageTitle = $_SESSION['_mConf']->name;
$ind = 0;
$icons = array();

$icons[$ind++] = '<a onclick="this.style.color=\'#71AFDB\'" style="text-decoration:none;font-family: Helvetica, Arial, sans-serif;color:#000080;font-size:.8em;display:block;height:100%;width:100%;" href="../m.conference/home.php?c='.$_GET['c'].'" data-transition="slide"><img style="padding:15px 0 2px 0;width:48px;height:48px;display:block;" src="../m.image/home.png">'.$l_c_home.'</a>';

if( Conference::hasSchedule($_GET['c']) )
	$icons[$ind++] = '<a onclick="this.style.color=\'#71AFDB\'" style="text-decoration:none;font-family: Helvetica, Arial, sans-serif;color:#000080;font-size:.8em;display:block;height:100%;width:100%;" href="../m.conference/schedule.php?c='.$_GET['c'].'" data-transition="slide"><img style="padding:15px 0 2px 0;width:48px;height:48px;display:block;" src="../m.image/schedule.png">'.$l_sche.'</a>';

if( Conference::hasSpeaker($_GET['c']) )
	$icons[$ind++] = '<a onclick="this.style.color=\'#71AFDB\'" style="text-decoration:none;font-family: Helvetica, Arial, sans-serif;color:#000080;font-size:.8em;display:block;height:100%;width:100%;" href="../m.conference/speakers.php?c='.$_GET['c'].'" data-transition="slide"><img style="padding:15px 0 2px 0;width:48px;height:48px;display:block;" src="../m.image/speaker.png">'.$l_spea.'</a>';

if( Conference::hasSponser($_GET['c']) )
	$icons[$ind++] = '<a onclick="this.style.color=\'#71AFDB\'" style="text-decoration:none;font-family: Helvetica, Arial, sans-serif;color:#000080;font-size:.8em;display:block;height:100%;width:100%;" href="../m.conference/sponsers.php?c='.$_GET['c'].'" data-transition="slide"><img style="padding:15px 0 2px 0;width:48px;height:48px;display:block;" src="../m.image/sponsor.png">'.$l_spon.'</a>';

if( Conference::hasAttendee($_GET['c']) )
	$icons[$ind++] = '<a onclick="this.style.color=\'#71AFDB\'" style="text-decoration:none;font-family: Helvetica, Arial, sans-serif;color:#000080;font-size:.8em;display:block;height:100%;width:100%;" href="../m.conference/attendees.php?c='.$_GET['c'].'" data-transition="slide"><img style="padding:15px 0 2px 0;width:48px;height:48px;display:block;" src="../m.image/attendee.png">'.$l_atte.'</a>';

if( Conference::hasBcasts($_GET['c']) )
	$icons[$ind++] = '<a onclick="this.style.color=\'#71AFDB\'" style="text-decoration:none;font-family: Helvetica, Arial, sans-serif;color:#000080;font-size:.8em;display:block;height:100%;width:100%;" href="../m.conference/broadcasts.php?c='.$_GET['c'].'" data-transition="slide"><img style="padding:15px 0 2px 0;width:48px;height:48px;display:block;" src="../m.image/broadcast.png">'.$l_bcast.'</a>';
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
<div data-role="header" data-theme="b">
<a data-rel="back" data-icon="arrow-l"  data-direction="reverse"><?php echo $l_back?></a>
<h2><?php echo $pageTitle;?></h2>
<a class="ui-btn-right" data-icon="gear" href="javascript:showAct(200);"><?php echo $l_option?></a>
</div>
<div data-role="content" data-theme="c">
<div class="ui-grid-b">
<?php 
$size = 0;
foreach($icons as $k=>$v) {?>
<div class="ui-block-<?php echo chr(97+$k%3)?>"><div style="width:100%;height:120px;" ><center><?php echo $v?></center></div></div>
<?php
$size++;
}
for($ii=$size; $ii<9; $ii++)
{?>
	<div class="ui-block-<?php echo chr(97+$ii%3)?>"><div style="width:100%;height:100px;" ></div></div>
<?php }?>
</div>
</div>
<?php include_once '../m.common/footer1.inc.php';?>
<div id="screen"></div>
<div id="action">
<div style="padding:20px;">
<?php if(!$isJoined && !$isOwner) { echo '<a data-role="button" href="javascript:joinConf(\''.$_GET['c'].'\')" data-theme="c">'.$l_join.'</a>'; }?>
<?php if($isJoined && !$isOwner) { echo '<a data-role="button" href="javascript:leaveConf(\''.$_GET['c'].'\')" data-theme="c">'.$l_unjoin.'</a>'; }?>
<a data-role='button' href='../m.home/index.php' rel='external' data-transition="pop" data-theme="c"><?php echo $l_home?></a>
<a data-role="button" data-rel="close" href="javascript:hideAct(200)" style="margin-top:20px;" data-theme="a"><?php echo $l_cancel?></a>
</div>
</div>
</div>
</body>
</html>