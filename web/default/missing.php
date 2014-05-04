<?php
$urlElem = split('/', $_SERVER['REQUEST_URI'], 5);

if( (isset($urlElem[1]) && !empty($urlElem[1])) && 
	(empty($urlElem[2]) || !isset($urlElem[2])) )
{
	header( 'Location: /conference/info_home.php?url='.$urlElem[1] ) ;
	exit;
}

include_once ("../utils/CommonUtil.php");

session_start();
if(!isset($_SESSION['_language'])) CommonUtil::setSessionLanguage();

//============================================= Language ==================================================

if($_SESSION['_language'] == 'en') {
	$l_title="ConfOne - Page Cannot Found.";
}
else if($_SESSION['_language'] == 'zh') {
	$l_title="会云 -- 您的访问出错了";
}

//=========================================================================================================

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo $l_title?></title><link rel="shortcut icon" href="../image/favicon.ico" />
<link type="text/css" rel="stylesheet" href="../css/generic.css" />
<script type="text/JavaScript" src="../js/common.js"></script>
<style type="text/css">html,body{margin:0;padding:0;}</style>
</head>
<body>
<div style="width:100%;background-color:#589ED0;">
<div class="stand_width">

</div>
</div>
</body>
</html>