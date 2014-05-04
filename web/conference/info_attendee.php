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
	$l_title = "Attendees | ";
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
	$l_title = "参会者 | ";
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
$attendIds = Conference::getConfAttendeeIds($conf->getCid());

$attendees = array();
while($row = mysql_fetch_array($attendIds, MYSQL_ASSOC))
{
	$attendees[$row['uid']] = null;
	$ids = $ids.$row['uid'].', ';
}
$ids = $ids.'0';

$attendT = UserT::getRangeConfUsers($conf->getCid(), $ids);
foreach($attendT as $attend)
{
	$attendees[$attend->getUid()] = $attend;
}

$ids = '';
foreach ($attendees as $k=>$v)
{
	if( !isset($attendees[$k]) )
		$ids = $ids.$k.', ';
}
$ids = $ids.'0';

$attendUser = User::getRangeUsers($ids);
foreach($attendUser as $attend)
{
	$attendees[$attend->getUid()] = $attend;
}

$page = 4;
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
<div id="h_cbody">
<?php if( isset($_SESSION['_userId']) ) { 
if( $_SESSION['_userId'] == $conf->getUid() || Conference::isJoined($conf->getCid(), $_SESSION['_userId']) ) {?>
<table id="speaker" style="width:100%;">
<?php foreach($attendees as $attend) {?>
<tr>
<td style="width:130px;"><div class="pic round_border_6" style="background:url('<?php echo $attend->pic?>') no-repeat center"></div></td>
<td><div>
<div class="fname"><label><?php echo $attend->fullname?></label></div>
<div style="font-size:.8em;"><label>
<?php echo '<b style="line-height:2em;">'.$l_company.'</b>'.$attend->company?><br />
<?php echo '<b style="line-height:2em;">'.$l_comp_title.'</b>'.$attend->title?></label>
</div></div></td>
</tr>
<tr><td></td>
<td><div><label><?php echo $attend->description;?></label>
<div class="comu">
<div style="margin-bottom:3px;"><img src="../image/email-icon.gif" /> : <?php echo $attend->getEmail();?></div>
<div style="margin-bottom:3px;"><img style="width:12px;" src="../image/phone-icon.png" /> : <?php echo $attend->cell;?></div>
<div style="margin-bottom:3px;"><img style="width:12px;" src="../image/weibo-icon.png" /> : <?php echo $attend->weibo;?></div>
</div></div>
</td>
</tr>
<tr><td colspan="2"><div class="bord"></div></td></tr>
<?php }?>
</table>
<?php } else if ( $conf->status == 'P' ) { //Not Registerted ?>
<style type="text/css">
html,body{margin:0;padding:0;}
input.jconf {
cursor:pointer;
font-size:1.4em;
font-weight:bold;
color:#fff;
width:200px;
height:50px;
border:0 none;
background: #9dd53a; /* Old browsers */
background: -moz-linear-gradient(top, #9dd53a 0%, #80c217 50%, #7cbc0a 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#9dd53a), color-stop(50%,#80c217), color-stop(100%,#7cbc0a)); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top, #9dd53a 0%,#80c217 50%,#7cbc0a 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top, #9dd53a 0%,#80c217 50%,#7cbc0a 100%); /* Opera11.10+ */
background: -ms-linear-gradient(top, #9dd53a 0%,#80c217 50%,#7cbc0a 100%); /* IE10+ */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#9dd53a', endColorstr='#7cbc0a',GradientType=0 ); /* IE6-9 */
background: linear-gradient(top, #9dd53a 0%,#80c217 50%,#7cbc0a 100%); /* W3C */
}
input.jconf:hover {
background: #9dd53a; /* Old browsers */
background: -moz-linear-gradient(top, #9dd53a 0%, #80c217 100%, #7cbc0a 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#9dd53a), color-stop(100%,#80c217), color-stop(100%,#7cbc0a)); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top, #9dd53a 0%,#80c217 100%,#7cbc0a 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top, #9dd53a 0%,#80c217 100%,#7cbc0a 100%); /* Opera11.10+ */
background: -ms-linear-gradient(top, #9dd53a 0%,#80c217 100%,#7cbc0a 100%); /* IE10+ */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#9dd53a', endColorstr='#7cbc0a',GradientType=0 ); /* IE6-9 */
background: linear-gradient(top, #9dd53a 0%,#80c217 100%,#7cbc0a 100%); /* W3C */
}
input.jconf:active {
background: #9dd53a; /* Old browsers */
background: -moz-linear-gradient(top, #9dd53a 0%, #80c217 0%, #7cbc0a 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#9dd53a), color-stop(0%,#80c217), color-stop(100%,#7cbc0a)); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top, #9dd53a 0%,#80c217 0%,#7cbc0a 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top, #9dd53a 0%,#80c217 0%,#7cbc0a 100%); /* Opera11.10+ */
background: -ms-linear-gradient(top, #9dd53a 0%,#80c217 0%,#7cbc0a 100%); /* IE10+ */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#9dd53a', endColorstr='#7cbc0a',GradientType=0 ); /* IE6-9 */
background: linear-gradient(top, #9dd53a 0%,#80c217 0%,#7cbc0a 100%); /* W3C */
}
</style>
<center>
<form method="post" action="../conference/joinConf.inc.php" enctype="multipart/form-data" name="ceform" id="cdfrom" accept-charset="UTF-8" >
<input type="hidden" id="fjoin" name="fjoin" value="<?php echo $_GET['url']?>" />
<input type="hidden" id="curl" name="curl" value="<?php echo $_SERVER['REQUEST_URI']?>" />
<input class="jconf round_border_6" type="submit" id="join_but" value="<?php echo $l_join?>" />
</form>
</center>
<?php } } else { //Not Logged in ?>
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
<input type="hidden" value="../conference/info_attendee.php?url=<?php echo $_GET['url']?>" name="url" />
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