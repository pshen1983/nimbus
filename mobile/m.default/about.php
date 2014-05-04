<?php
include_once ("../utils/CommonUtil.php");
include_once ("../utils/SecurityUtil.php");

session_start();
if(!isset($_SESSION['_language'])) CommonUtil::setSessionLanguage();
SecurityUtil::mCheckLogin();

//============================================= Language ==================================================

if($_SESSION['_language'] == 'en') {
	$l_title = "ConfOne";
	$l_about = "About";
}
else if($_SESSION['_language'] == 'zh') {
	$l_title = "会云网";
	$l_about = "关于";
}

//=========================================================================================================

$pageTitle = $l_about;
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
<p>会云是一个工具，一个助手和一个平台。</p>
<p><b>工具</b>：为会议活动组织者提供一个会议活动管理工具，包括会议日程管理，参会者人员管理等。（会云为会议活动组织者提供一个全过程的会议管理平台。会议活动组织者通过会云能够独立的创建，发布，以及即时的更新线上会议信息给参会者）。</p>
<p><b>助手</b>：为参会者提供一个移动助手，在会议活动期间推送会议信息、会议文档、会议通讯录、会议微博。手机体验为主。用户通过会云在会议活动期间掌控所在会议信息，与其他与会者通过邮件，短信，微博的方式进行多媒体互动。</p>
<p><b>平台</b>：根据用户参会历史数据、喜好，推荐新的同类会议活动。为会议活动提供一个推广平台包括接受发送活动邀请，注册以及会后内容材料的推送。会云的注册用户通过会云能够随时随地的了解感兴趣的会议信息、参会咨询以及预订会议。</p>
</div>
<?php include_once '../m.common/footer1.inc.php';?>
</div>
</body>
</html>