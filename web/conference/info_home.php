<?php
include_once ("../_obj/Conference.php");
include_once ("../utils/CommonUtil.php");
include_once ("../utils/SecurityUtil.php");

session_start();
if(!isset($_SESSION['_language'])) CommonUtil::setSessionLanguage();
SecurityUtil::cookieLogin();

if( !isset($_GET['url']) || empty($_GET['url']) )
{
	header( 'Location: ../conference/index.php' );
	exit;
}

$conf = Conference::getConfInfo($_GET['url']);

if( $conf->search == 'N' && 
	( !isset($_SESSION['_userId']) || 
	  ( !Conference::isJoined($conf->getCid(), $_SESSION['_userId']) && 
	    !Conference::isUserConfById($_SESSION['_userId'], $conf->getCid() ) ) ) )
{
	header( 'Location: ../default/index.php' );
	exit;
}

$sftime = strtotime($conf->stime);
$sdate = date('Y.m.d', $sftime);
$stime = date('H:i', $sftime);
if($stime == '00:00') $stime = '';

$eftime = strtotime($conf->etime);
$edate = date('Y.m.d', $eftime);
$etime = date('H:i', $eftime);
if($etime == '00:00') $etime = '';

//============================================= Language ==================================================

if($_SESSION['_language'] == 'en') {
	$l_title="";
	$l_time = "Time: ";
	$l_loca = "Location: ";
	$l_apply = "The event organizer need to manually put you in this event, ConfOne has informed the organizer. Please wait and check your email or ConfOne message box for acceptance.";
}
else if($_SESSION['_language'] == 'zh') {
	$l_title="";
	$l_time = "时间：";
	$l_loca = "地点：";
	$l_apply = "参加该活动需要活动举办方同意，会云已经通知举办方您有意参与他们的活动。请关注您的邮箱和站内信，等候回复。";
}

//=========================================================================================================

$page = 1;
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo $conf->name?></title><link rel="shortcut icon" href="../image/favicon.ico" />
<link type="text/css" rel="stylesheet" href="../css/generic.css" />
<link type="text/css" rel="stylesheet" href="../css/conference.css" />
<script type="text/JavaScript" src="../js/common.js"></script>
<script type="text/JavaScript" src="../js/conference.js"></script>
<style type="text/css">html,body{margin:0;padding:0;}</style>
</head>
<body <?php if(isset($_SESSION['_applyJoin'])) { unset($_SESSION['_applyJoin']); echo "onload='alert(\"".$l_apply."\")'";}?>>
<div style="width:100%;">
<div class="stand_width">
<?php include_once '../conference/info_header.inc.php';?>
<div id="hmain">
<div id="hright">
<div style="padding: 20px 0 20px 10px;font-size:.8em;"><?php include_once "../common/JiaThisB32.inc.php";?></div>
</div>
<div id="hleft">
<div id="h_ctop">
<table>
<tr><td><div class="confimg round_border_6" style="background: #fff url('<?php echo $conf->img;?>') no-repeat center center;"></div></td>
<td style="padding-left:40px;">
<label style="font-weight:bold"><?php echo $conf->name;?></label>
<div class="tandl">
<span style="font-weight:bold;"><?php echo $l_time?></span><?php echo $sdate.' ~ '.$edate;?><br />
<span style="font-weight:bold;"><?php echo $l_loca?></span><?php echo $conf->getFaddr();?>
</div></td></tr></table>
</div>
<div style="width:80%;border-top:1px solid #AAA;margin:15px 0 0 20px;"></div>
<div id="h_cbody">
<?php echo $conf->description;?>
</div>
</div>
</div>
<div style="width:100%;border-bottom:1px solid #DDD;margin-bottom:10px;"></div>
</div>
</div>
<?php include_once '../common/footer.inc.php';?>
</body>
</html>