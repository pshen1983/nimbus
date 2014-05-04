<?php
include_once ("../_obj/Conference.php");
include_once ("../utils/CommonUtil.php");
include_once ("../utils/SecurityUtil.php");

session_start();
SecurityUtil::mCheckLogin();

if( isset($_POST['j_url']) && !empty($_POST['j_url']) )
{
	$conf = Conference::getConfIdStatus($_POST['j_url']);

	if( isset($conf['cid']) && !empty($conf['cid']) && $conf['status']=='P' )
	{
		if( !Conference::isJoined($conf['cid'], $_SESSION['_userId']) )
		{
			if( Conference::joinConf($conf['cid'], $_SESSION['_userId']) )
			{
				$result = 0;
			}
			else {
				$result = 3;
			}
		}
		else {
			$result = 2;
		}
	}
	else {
		$result = 1;
	}
}

//============================================= Language ==================================================

if($_SESSION['_language'] == 'en') {
	$l_title = "ConfOne";
	$l_add = "Join Event";
	$l_back = "Back";
	$l_home = "Home";
	$l_mess = '<b style="color:#000">Link Code</b> is used for an Event Home Page<br />Page: "http://www.confome.con/demo2011",<br />Then: "demo2011" is the Link Code.<br /><br />Please ask the event organizer for the Code.';
	$l_res_0 = 'Event successfully added, <a href="../m.home/index.php" data-icon="home" data-transition="pop" style="font-size:1.2em;">Home Page</a>';
	$l_res_1 = 'Cannot find event with Link Code: "'.(isset($_POST['j_url']) ? $_POST['j_url']: '').'"';
	$l_res_2 = 'Already added this event, <a href="../m.home/index.php" data-icon="home" data-transition="pop" style="font-size:1.2em;">Home Page</a>';
	$l_res_3 = 'System busy! Please try again later.';
	$l_link = "Enter an Event Link Code: ";
}
else if($_SESSION['_language'] == 'zh') {
	$l_title = "会云网";
	$l_add = "添加活动";
	$l_back = "返回";
	$l_home = "主页";
	$l_mess = '<b style="color:#000">链接码</b>用于访问会议活动的网络主页<br />若："http://www.confome.con/demo2011"，<br />则："demo2011"为链接码。<br /><br />请向会议活动主办方获得链接码。';
	$l_res_0 = '会议活动添加成功，<a href="../m.home/index.php" data-icon="home" data-transition="pop" style="font-size:1.2em;">转回主页</a>';
	$l_res_1 = '找不到连接码为"'.(isset($_POST['j_url']) ? $_POST['j_url']: '').'"的会议活动';
	$l_res_2 = '您已经添加过该活动，<a href="../m.home/index.php" data-icon="home" data-transition="pop" style="font-size:1.2em;">转回主页</a>';
	$l_res_3 = '系统忙碌，无法添加该活动，请稍候再试';
	$l_link = "输入会议活动链接码：";
}

//=========================================================================================================

$pageTitle = $l_add;
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
<div style="margin:0 0 10px 0;padding:5px 10px 5px 10px;background-color:#FFFFDD;font-size:.8em;word-wrap:break-word">
<label style="color:#666;line-height:1.3em;"><?php echo $l_mess?></label>
</div>
<?php if (isset($_POST['j_url']) && !empty($_POST['j_url']) && isset($result)) {
	echo '<div style="margin:auto;margin-bottom:10px;font-size:.8em;font-weight:bold;padding:10px 10px 10px 10px;background-color:'.( ($result==0 || $result==2) ? '#DDFFDD;' : '#FFDDDD;color:red;' ).'"><center>';

	switch ($result) {
    case 0:
        echo $l_res_0;
        break;
    case 1:
        echo $l_res_1;
        break;
    case 2:
        echo $l_res_2;
        break;
    case 3:
        echo $l_res_3;
        break;
	}

	echo '</center></div>';
}
?>
<form id="m_join_form" data-ajax="false" method="post" enctype="multipart/form-data" action="join.php" name="join_form" id="join_form" accept-charset="UTF-8">
<label style="font-size:.9em;font-weight:bold;"><?php echo $l_link;?></label>
<input type="text" id="j_url" name="j_url" autocorrect="off" autocapitalize="off" />
<input type="submit" value="<?php echo $l_add?>" />
<input type="hidden" id="j_hid" name="j_hid" value="1" />
</form>
</div>
<?php include_once '../m.common/footer1.inc.php';?>
</div>
</body>
</html>