<?php
include_once ("../_obj/Conference.php");
include_once ("../utils/SecurityUtil.php");
include_once ("../utils/CommonUtil.php");

session_start();
if(!isset($_SESSION['_language'])) CommonUtil::setSessionLanguage();
SecurityUtil::mCheckLogin();

//============================================= Language ==================================================

if($_SESSION['_language'] == 'en') {
	$l_title = "ConfOne";
}
else if($_SESSION['_language'] == 'zh') {
	$l_title = "会云网";
}

//=========================================================================================================

if( !isset($_GET['c']) || !is_numeric($_GET['c']) )
{
	header( 'Location: ../m.home/index.php' );
	exit;
}

$pageTitle = $_SESSION['_mConf']->name;

$sftime = strtotime($_SESSION['_mConf']->stime);
$sdate = date('Y.m.d', $sftime);
$stime = date('H:i', $sftime);
$tprint = $sdate.(($stime!='00:00') ? ' '.$stime : '');

if( isset($_SESSION['_mConf']->etime) && !empty($_SESSION['_mConf']->etime) )
{
	$eftime = strtotime($_SESSION['_mConf']->etime);
	$edate = date('Y.m.d', $eftime);
	$etime = date('H:i', $eftime);
	$tprint = $tprint.' ~ '.$edate.(($etime!='00:00') ? ' '.$etime : '');
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
<div data-role="content" data-theme="c">
<div>
<img align="top" style="float:left;height:54px;margin-right:10px;-webkit-box-shadow:0 0 5px #333;-moz-box-shadow:0 0 5px #333;box-shadow:0 0 5px #333;" src="<?php echo $_SESSION['_mConf']->img?>">
<div>
<label style="font-size:.8em;font-weight:bold;"><?php echo $_SESSION['_mConf']->name;?></label><br />
<p style="font-size:.6em;line-height:.6em;"><?php echo $tprint?></p>
<p style="font-size:.6em;line-height:.6em;"><?php echo $_SESSION['_mConf']->addr;?></p>
</div></div>
<div style="margin-top:20px;">
<p style="word-wrap:break-word;white-space:normal;font-size:.6em;"><?php echo $_SESSION['_mConf']->description;?></p>
</div>
</div>
<?php include_once '../m.common/footer1.inc.php';?>
</div>
</body>
</html>