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
}
else if($_SESSION['_language'] == 'zh') {
	$l_title = "会云网";
	$l_company = "公司: ";
	$l_position = "职位: ";
}

//=========================================================================================================

if( !isset($_GET['u']) || !is_numeric($_GET['u']) || 
	!isset($_GET['c']) || !is_numeric($_GET['c']) )
{
	header( 'Location: ../m.home/index.php' );
	exit;
}

$user = User::mGetUser($_GET['u']);
$userT = UserT::mGetConfUser($_GET['c'], $_GET['u']);

if ($userT)
{
	if( isset($userT->pic) && !empty($userT->pic)) $user->pic = $userT->pic;
	if( isset($userT->fullname) && !empty($userT->fullname)) $user->fullname = $userT->fullname;
	if( isset($userT->company) && !empty($userT->company)) $user->company = $userT->company;
	if( isset($userT->title) && !empty($userT->title)) $user->title = $userT->title;
	if( isset($userT->description) && !empty($userT->description)) $user->description = $userT->description;
	if( isset($userT->cell) && !empty($userT->cell)) $user->cell = $userT->cell;
	if( isset($userT->weibo) && !empty($userT->weibo)) $user->weibo = $userT->weibo;
}

$pageTitle = $user->fullname;
?>
<!DOCTYPE html>
<html manifest="../m.common/m.confone.manifest">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; minimum-scale=1.0; user-scalable=0;" />
<meta name="format-detection" content="telephone=yes" />
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
<img style="margin:0 15px 0 5px;float:left;-webkit-box-shadow:0 0 3px #333;-moz-box-shadow:0 0 3px #333;box-shadow:0 0 3px #333;width:60px;" src="<?php echo $user->pic?>" />
<label style="font-weight:bold;"><?php echo $user->fullname;?></label><br />
<span style="font-size:.7em;font-weight:bold;">
<?php echo $l_company?><span style="font-weight:normal;"><?php echo $user->company?></span><br />
<?php echo $l_position?><span style="font-weight:normal;"><?php echo $user->title;?></span>
</span>
<div style="margin:20px 5px 0px 5px;font-size:.8em;"><p style="line-height:1.3em;"><?php echo $user->description;?></p></div>
<div style="margin:20px 5px 10px 5px;font-size:.8em;font-weight:bold;">
<div style="margin-bottom:3px;"><img src="../m.image/email-icon.gif" /> : <?php echo $user->getEmail();?></div>
<div style="margin-bottom:3px;"><img style="width:12px;" src="../m.image/phone-icon.png" /> : <?php echo $user->cell;?></div>
<div style="margin-bottom:3px;"><img style="width:12px;" src="../m.image/weibo-icon.png" /> : <?php echo $user->weibo;?></div>
</div>
</div>
<?php include_once '../m.common/footer1.inc.php';?>
</div>
</body>
</html>