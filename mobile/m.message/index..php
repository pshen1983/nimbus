<?php
include_once ("../_obj/Message.php");
include_once ("../utils/CommonUtil.php");
include_once ("../utils/SecurityUtil.php");

session_start();
if(!isset($_SESSION['_language'])) CommonUtil::setSessionLanguage();
SecurityUtil::mCheckLogin();

//============================================= Language ==================================================

if($_SESSION['_language'] == 'en') {
	$l_title = "ConfOne";
	$l_p_title = "Messages";
	$l_time = "Time: ";
	$l_sender = "Sender: ";
}
else if($_SESSION['_language'] == 'zh') {
	$l_title = "会云网";
	$l_p_title = "站内信";
	$l_time = "时间：";
	$l_sender = "发信人：";
}

//=========================================================================================================

$pageTitle = $l_p_title;

$messages = Message.getMessageList($_SESSION['_userId']);

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
<div data-role="content" data-theme="d">
<ul data-role="listview" data-theme="c">
<?php foreach ($messages as $message) {?>
<li>
<a href="../m.message/message.php?id=<?php echo $message->getId();?>" data-transition="slide">
<label <?php if(!$message->isRead()) echo 'style="font-weight:bold;"'?>><?php echo $message->getTitle();?></label>
<span style="margin-top:10px;font-size:.8em;display:block;">
<span style="font-weight:bold"><?php echo $l_sender?></span><br />
<span style="font-weight:bold"><?php echo $l_time?></span>
</span></a>
</li>
<?php }?>
</ul>
</div>
<?php include_once '../m.common/footer.inc.php';?>
</div>
</body>
</html>