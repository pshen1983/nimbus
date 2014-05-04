<?php
include_once ("../utils/CommonUtil.php");
include_once ("../utils/SecurityUtil.php");

session_start();
if(!isset($_SESSION['_language'])) CommonUtil::setSessionLanguage();
if(!isset($_SESSION['_userId'])) SecurityUtil::cookieLogin();

//============================================= Language ==================================================

if($_SESSION['_language'] == 'en') {
	$l_title="Follow us | ConfOne";
	$l_search="You Search For";
	$l_contact = "Contact us";
	$l_web = "Web: ";
	$l_email = "Email: ";
	$l_qq = "QQ: ";
}
else if($_SESSION['_language'] == 'zh') {
	$l_title="关注我们 | 会云网";
	$l_search="您搜索的词条";
	$l_contact = "联系我们";
	$l_web = "网址：";
	$l_email = "邮箱：";
	$l_qq = "Q Q ：";
}

//=========================================================================================================
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo $l_title?></title><link rel="shortcut icon" href="../image/favicon.ico" />
<link type="text/css" rel="stylesheet" href="../css/generic.css" />
<link type="text/css" rel="stylesheet" href="../css/search.css" />
<script type="text/JavaScript" src="../js/common.js"></script>
<style type="text/css">
html,body{margin:0;padding:0;}
div#contactus ul li{padding-bottom:10px;color:#333;}</style>
</head>
<body>
<div style="width:100%;">
<?php include_once isset($_SESSION['_userId']) ? '../common/header.inc.php' : '../common/header1.inc.php';?>
<div class="stand_width">
<div id="hmain">
<div style="padding:50px;">
<div style="padding:15px;font-weight:bold;"><label><?php echo $l_contact;?></label></div>
<div style="width:98%;margin:auto;border-bottom:1px solid #AAA;"></div>
<div id="contactus">
<ul>
<li><label><?php echo $l_web?><a href="..">www.confone.com</a></label></li>
<li><label><?php echo $l_email?>pshen1983@gmail.com</label></li>
<li><label><?php echo $l_qq?>83974886</label></li>
</ul>
</div>
</div>
</div>
</div>
</div>
<?php include_once '../common/footer.inc.php';?>
</body>
</html>