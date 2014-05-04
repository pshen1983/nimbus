<?php
include_once ("../utils/CommonUtil.php");
include_once ("../utils/SecurityUtil.php");

session_start();
if(!isset($_SESSION['_language'])) CommonUtil::setSessionLanguage();
SecurityUtil::mCheckLogin();

//============================================= Language ==================================================

if($_SESSION['_language'] == 'en') {
	$l_title = "ConfOne";
	$l_p_title = "Contact us";
}
else if($_SESSION['_language'] == 'zh') {
	$l_title = "会云网";
	$l_p_title = "联系我们";
}

//=========================================================================================================
$pageTitle = $l_p_title;
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
<div><center><img align="top" style="margin-right:10px;-webkit-box-shadow:0 0 5px #333;-moz-box-shadow:0 0 5px #333;box-shadow:0 0 5px #333;" src="../m.image/logo.png"></center></div>
<div style="margin:20px 5px 10px 5px;font-size:.8em;font-weight:bold;">
<div style="margin-bottom:3px;"><img src="../m.image/email-icon.gif" /> : pshen1983@gmail.com</div>
<div style="margin-bottom:3px;"><img style="width:12px;" src="../m.image/phone-icon.png" /> : 15221696220</div>
<div style="margin-bottom:3px;"><img style="width:12px;" src="../m.image/weibo-icon.png" /> : http://weibo.com/2420020220</div>
</div>
</div>
<?php include_once '../m.common/footer1.inc.php';?>
</div>
</body>
</html>