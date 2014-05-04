<?php
include_once ("../utils/CommonUtil.php");
include_once ("../utils/SecurityUtil.php");

session_start();
SecurityUtil::mCheckLogin();

//============================================= Language ==================================================

if($_SESSION['_language'] == 'en') {
	$l_title = "ConfOne";
	$l_near = "Events Near Me";
	$l_search = "Search Events";
	$l_search_all = "All Events";
	$l_add = "Add Events";
	$l_profile = "Profile";
	$l_logout = "Logout";
	$l_ptitle = "More";
	$l_about = "About ConfOne";
	$l_feedback = "Feedback";
}
else if($_SESSION['_language'] == 'zh') {
	$l_title = "会云网";
	$l_near = "附近活动";
	$l_search = "搜索活动";
	$l_search_all = "全部活动";
	$l_add = "添加活动";
	$l_profile = "个人信息";
	$l_logout = "退出登录";
	$l_ptitle = "更多";
	$l_about = "关于会云";
	$l_feedback = "意见反馈";
}

//=========================================================================================================
$pageTitle = $l_ptitle;
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
<div class="edge top_6 bot_6">
<a href="javascript:nEvents();" class="link top_6" style="color:#333;"><?php echo $l_near?></a>
<div style="border-top:1px solid #BBB;"></div>
<a href="../m.search/index.php" class="link" style="color:#333;"><?php echo $l_search?></a>
<div style="border-top:1px solid #BBB;"></div>
<a href="../m.search/index.php?hid=0" class="link" style="color:#333;"><?php echo $l_search_all?></a>
<div style="border-top:1px solid #BBB;"></div>
<a href="../m.home/join.php" class="link bot_6" style="color:#333;"><?php echo $l_add?></a>
</div>
<div style="margin-top:15px;" class="edge top_6 bot_6">
<a href="../m.home/profile.php" class="link top_6 bot_6" style="color:#333;"><?php echo $l_profile?></a>
</div>
<div style="margin-top:15px;" class="edge top_6 bot_6">
<a href="../m.default/about.php" class="link top_6" style="color:#333;"><?php echo $l_about?></a>
<div style="border-top:1px solid #BBB;"></div>
<a href="../m.home/feedback.php" class="link bot_6" style="color:#333;"><?php echo $l_feedback?></a>
</div>
<div style="margin-top:15px;text-align:center;" class="edge top_6 bot_6">
<a href="../m.default/logout.php" class="link top_6 bot_6" data-transition="fade" style="color:#333;"><?php echo $l_logout?></a>
</div>
</div>
<?php include_once '../m.common/footer1.inc.php';?>
</div>
</body>
</html>