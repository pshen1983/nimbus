<?php
include_once ("../_obj/User.php");
include_once ("../_obj/UserT.php");
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
	$l_title = "Speakers | ";
	$l_company = "Company: ";
	$l_comp_title = "Title: ";
	$l_name = "Username or Email";
	$l_pass = "Password";
	$l_auto = "Remember me";
	$l_bu_login = "Sign in";
	$l_new_to = "New to ConfOne?";
	$l_reg_now = "Join in Now!";
	$l_join = "Join Conference";
	$l_apply = "The event organizer need to manually put you in this event, ConfOne has informed the organizer. Please wait and check your email or ConfOne message box for acceptance.";
}
else if($_SESSION['_language'] == 'zh') {
	$l_title = "演讲嘉宾 | ";
	$l_company = "公司：";
	$l_comp_title = "职务：";
	$l_name = "帐号";
	$l_pass = "密码";
	$l_auto = "自动登录";
	$l_bu_login = "登录";
	$l_new_to = "刚到会云网？";
	$l_reg_now = "立刻注册！";
	$l_join = "参加会议活动";
	$l_apply = "参加该活动需要活动举办方同意，会云已经通知举办方您有意参与他们的活动。请关注您的邮箱和站内信，等候回复。";
}

//=========================================================================================================

$ids='';
$speaIds = Conference::getConfSpeakerIds($conf->getCid());

$speakers = array();
while($row = mysql_fetch_array($speaIds, MYSQL_ASSOC))
{
	$speakers[$row['uid']] = null;
	$ids = $ids.$row['uid'].', ';
}
$ids = $ids.'0';

$speakerT = UserT::getRangeConfUsers($conf->getCid(), $ids);
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

$speakerUser = User::getRangeUsers($ids);
foreach($speakerUser as $speaker)
{
	$speakers[$speaker->getUid()] = $speaker;
}

$page = 3;
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo $l_title.$conf->name?></title><link rel="shortcut icon" href="../image/favicon.ico" />
<link type="text/css" rel="stylesheet" href="../css/generic.css" />
<link type="text/css" rel="stylesheet" href="../css/conference.css" />
<script type="text/JavaScript" src="../js/common.js"></script>
<script type="text/JavaScript" src="../js/conference.js"></script>
<style type="text/css">
html,body{margin:0;padding:0;}
</style>
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
<div id="h_cbody">
<?php if( isset($_SESSION['_userId']) ) { ?>
<table id="speaker" style="width:100%;">
<?php foreach($speakers as $spea) {?>
<tr>
<td style="width:130px;"><div class="pic round_border_6" style="background:url('<?php echo $spea->pic?>') no-repeat center"></div></td>
<td><div>
<div class="fname"><label><?php echo $spea->fullname?></label></div>
<div style="font-size:.8em;"><label>
<?php echo '<b style="line-height:2em;">'.$l_company.'</b>'.$spea->company?><br />
<?php echo '<b style="line-height:2em;">'.$l_comp_title.'</b>'.$spea->title?></label>
</div></div></td>
</tr>
<tr><td></td>
<td><div><label><?php echo $spea->description;?></label>
<div class="comu">
<div style="margin-bottom:3px;"><img src="../image/email-icon.gif" /> : <?php echo $spea->getEmail();?></div>
<div style="margin-bottom:3px;"><img style="width:12px;" src="../image/phone-icon.png" /> : <?php echo $spea->cell;?></div>
<div style="margin-bottom:3px;"><img style="width:12px;" src="../image/weibo-icon.png" /> : <?php echo $spea->weibo;?></div>
</div></div>
</td>
</tr>
<tr><td colspan="2"><div class="bord"></div></td></tr>
<?php }?>
</table>
<?php } else { //Not Logged in ?>
<center>
<form id="log_form" method="post" enctype="multipart/form-data" action="../default/login.php" name="login_form" accept-charset="UTF-8">
<table>
<tr>
<td colspan="2"><div class="log_in_div"><label class="log_label"><?php echo $l_name?></label><br /><input type="text" name="log_name" class="log_in round_border_4" tabindex='1' /></div></td>
</tr>
<tr>
<td colspan="2"><div class="log_in_div"><label class="log_label"><?php echo $l_pass?></label><br /><input type="password" name="log_pass" class="log_in round_border_4" tabindex='2' /></div></td>
</tr>
<tr>
<td><div class="offset"><input type="checkbox" name="log_re" tabindex='3' /><label id="check"><?php echo $l_auto?></label></div></td>
<td style="text-align:right;">
<input type="hidden" name="log" value="log" />
<input type="hidden" value="../conference/info_speaker.php?url=<?php echo $_GET['url']?>" name="url" />
<div class="offset"><button type="submit" class="log round_border_4" tabindex='4' ><span class="input"><?php echo $l_bu_login?></span></button></div></td>
</tr>
<tr>
<td colspan="2"><div id="go_reg"><a class="go_reg" href="../default/register.php"><?php echo $l_new_to?> <span style="font-weight:bold;"><?php echo $l_reg_now?></span></a></div></td>
</tr>
</table>
</form>
</center>
<?php }?>
</div>
</div>
</div>
<div style="width:100%;border-bottom:1px solid #DDD;margin-bottom:10px;"></div>
</div>
</div>
<?php include_once '../common/footer.inc.php';?>
</body>
</html>