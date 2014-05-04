<?php
include_once ("../_obj/User.php");
include_once ("../_obj/Conference.php");
include_once ("../utils/CommonUtil.php");
include_once ("../utils/SecurityUtil.php");

session_start();
if(!isset($_SESSION['_language'])) CommonUtil::setSessionLanguage();
SecurityUtil::mCheckLogin();

//============================================= Language ==================================================

if($_SESSION['_language'] == 'en') {
	$l_title = "ConfOne";
	$l_search = "Search";
	$l_ptitle = "Search ConfOne";
}
else if($_SESSION['_language'] == 'zh') {
	$l_title = "会云网";
	$l_search = "搜索";
	$l_ptitle = "会云搜索";
}

//=========================================================================================================

$pageTitle = $l_ptitle;

$confs = array();
if( isset($_POST['hid']) || isset($_GET['hid'])) {
$confs = Conference::searchConfAll( (isset($_POST['search']) ? $_POST['search'] : ""),
									0, 
									20 );
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
<form id="m_join_form" data-ajax="false" method="post" enctype="multipart/form-data" action="../m.search/index.php" name="join_form" id="join_form" accept-charset="UTF-8">
<input value="<?php if (isset($_POST['search'])) echo $_POST['search']?>" type="text" id="search" name="search" autocorrect="off" autocapitalize="off" />
<input type="hidden" name="hid" id="hid" value="1" />
<input type="submit" value="<?php echo $l_search?>" />
</form>
</div>
<div style="margin-top:30px;">
<?php echo Conference::printConfListM($confs); ?>
</div>
</div>
<?php include_once '../m.common/footer1.inc.php';?>
</div>
</body>
</html>