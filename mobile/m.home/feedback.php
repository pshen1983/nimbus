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
	$l_feedback = "Feedback";
	$l_submit = "Submit";
	$l_feed_label = "Please give us your feedback:";
	$l_mess_title = "User feedback";
	$l_res_f1 = "System busy, please try again later.";
}
else if($_SESSION['_language'] == 'zh') {
	$l_title = "会云网";
	$l_feedback = "意见反馈";
	$l_submit = "发送";
	$l_feed_label = "请提出您的宝贵意见和建议：";
	$l_mess_title = "用户意见反馈";
	$l_res_f1 = "系统忙，请稍候再试。";
}

//=========================================================================================================

if( isset($_POST['feed_h']) && !empty($_POST['feed_h']) )
{
	$result = Message::create($l_mess_title, 1, $_SESSION['_userId'], $_POST['feed']);
	if($result != -1)
	{
		header( 'Location: ../m.home/option.php' );
		exit;
	}
}

$pageTitle = $l_feedback;
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
<?php if (isset($_POST['feed_h']) && !empty($_POST['feed_h']) && isset($result)) {
	echo '<div style="margin:auto;margin-bottom:10px;font-size:.8em;font-weight:bold;padding:10px 10px 10px 10px;background-color:'.( ($result==0 || $result==2) ? '#DDFFDD;' : '#FFDDDD;color:red;' ).'"><center>';

	switch ($result) {
    case -1:
        echo $l_res_f1;
        break;
	}

	echo '</center></div>';
}
?>
<form id="feedback_form" data-ajax="false" method="post" enctype="multipart/form-data" action="feedback.php" name="join_form" id="feedback_form" accept-charset="UTF-8">
<div><label><?php echo $l_feed_label?></label>
<textarea style="margin-bottom:20px;height:150px;" id="feed" name="feed"></textarea></div>
<input type="submit" value="<?php echo $l_submit?>" />
<input type="hidden" id="feed_h" name="feed_h" value="1" />
</form>
</div>
<?php include_once '../m.common/footer1.inc.php';?>
</div>
</body>
</html>