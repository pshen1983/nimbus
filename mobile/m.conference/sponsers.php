<?php
include_once ("../_obj/Sponsor.php");
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
	$l_sponser = "Sponsers";
}
else if($_SESSION['_language'] == 'zh') {
	$l_title = "会云网";
	$l_sponser = "赞助商";
}

//=========================================================================================================

$pageTitle = $l_sponser;

$sponsors = Sponsor::getSponsorListByCid($_GET['c']);
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
<?php foreach ($sponsors as $spon) { ?>
<li>
<a href="<?php echo $spon->url?>" target="_blank" data-transition="slide">
<img style="margin:12px 0 0 15px;float:left;-webkit-box-shadow:0 0 3px #333;-moz-box-shadow:0 0 3px #333;box-shadow:0 0 3px #333;width:80px;" src="<?php echo $spon->img?>" />
<label style="position:relative;top:20px;left:20px;"><?php echo $spon->cname;?></label>
</a>
</li>
<?php }?>
</ul>
</div>
<?php include_once '../m.common/footer1.inc.php';?>
</div>
</body>
</html>