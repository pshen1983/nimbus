<?php
include_once ("../_obj/User.php");
include_once ("../_obj/UserT.php");
include_once ("../_obj/Broadcast.php");
include_once ("../utils/CommonUtil.php");
include_once ("../utils/SecurityUtil.php");

session_start();
if(!isset($_SESSION['_language'])) CommonUtil::setSessionLanguage();
SecurityUtil::mCheckLogin();

if( !isset($_GET['c']) || !is_numeric($_GET['c']) )
{
	header( 'Location: ../m.home/index.php' );
	exit;
}

//============================================= Language ==================================================

if($_SESSION['_language'] == 'en') {
	$l_title = "ConfOne";
	$l_company = "Company: ";
	$l_position = "Position: ";
	$l_bcast = "Event Broadcast";
	$l_mtime = "Modified Time: ";
}
else if($_SESSION['_language'] == 'zh') {
	$l_title = "会云网";
	$l_company = "公司: ";
	$l_position = "职位: ";
	$l_bcast = "活动附加信息";
	$l_mtime = "更新时间：";
}

//=========================================================================================================

$pageTitle = $l_bcast;

$bcasts = Broadcast::mGetConfBcasts( $_GET['c'] );

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
<div data-role="content">
<ul data-role="listview" data-theme="c">
<?php foreach ($bcasts as $cast) {
$utime = substr($cast->getUtime(), 0, 16);?>
<li>
<a href="../m.conference/bcast.php?b=<?php echo $cast->getId();?>&c=<?php echo $cast->getCid();?>" data-transition="slide">
<label><?php echo $cast->btitle; ?></label>
<span style="font-weight:normal;margin-top:10px;font-size:.8em;display:block;">
<span style="font-weight:bold"><?php echo $l_mtime;?></span><?php echo $utime?>
</span></a>
</li>
<?php }?>
</ul>
</div>
<?php include_once '../m.common/footer1.inc.php';?>
</div>
</body>
</html>