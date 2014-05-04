<?php
include_once ("../utils/CommonUtil.php");

session_start();
if(!isset($_SESSION['_language'])) CommonUtil::setSessionLanguage();

//============================================= Language ==================================================

if($_SESSION['_language'] == 'en') {
	$l_title = "ConfOne";
	$l_pinfo = "Introduction";
}
else if($_SESSION['_language'] == 'zh') {
	$l_title = "会云网";
	$l_pinfo = "简介";
}

//=========================================================================================================

if(htmlspecialchars($_SERVER['HTTP_REFERER']) != CommonUtil::curPageURL())
	$_SESSION['lpage'] = htmlspecialchars($_SERVER['HTTP_REFERER']);
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; minimum-scale=1.0; user-scalable=0;" />
<meta name="format-detection" content="telephone=no" />
<title><?php echo $l_title?></title>
<link rel="apple-touch-icon" href="../image/cbutton.png" />
<link rel="apple-touch-icon-precomposed" href="../image/cbutton.png" />
<link type="text/css" rel="stylesheet" href="../css/generic.css" />
<link type="text/css" rel="stylesheet" href="../css/conference.css" />
<script type="text/JavaScript" src="../js/common.js"></script>
<script type="text/JavaScript" src="../js/conference.js"></script>
<script type="text/javascript">function linkTo(link){window.location = link}</script>
<style>html{background-color:#589ED0;}</style>
</head>
<body><?php 
$_GET['m_b']='index.php'; $_GET['m_h']="../home/index.php"; 
include_once '../common/header.inc.php';
unset($_GET['m_b']); unset($_GET['m_h']);?>
<div id="m_main_div">

</div>
<?php include_once '../common/footer.inc.php';?>
</body>
</html>