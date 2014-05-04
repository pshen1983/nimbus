<?php
include_once ("../utils/CommonUtil.php");
include_once ("../utils/SecurityUtil.php");

session_start();
if(!isset($_SESSION['_language'])) CommonUtil::setSessionLanguage();
SecurityUtil::mCheckLogin();

if( isset($_POST['sub']) && !empty($_POST['sub']) )
{
	$result = 0;
	if( isset($_POST['name']) && !empty($_POST['name']) )
	{
		if( isset($_POST['cell']) && !empty($_POST['cell']) && 
			CommonUtil::validateCellFormat($_POST['cell']) )
		{
			$_SESSION['_loginUser']->fullname = $_POST['name'];
			$_SESSION['_loginUser']->uname = $_POST['uname'];
			$_SESSION['_loginUser']->cell = $_POST['cell'];
			$_SESSION['_loginUser']->weibo = $_POST['weibo'];
			$_SESSION['_loginUser']->comp = $_POST['comp'];
			$_SESSION['_loginUser']->role = $_POST['role'];

			$result = $_SESSION['_loginUser']->updateUser();
			if($result == 0)
			{
				header( 'Location: ../m.home/option.php' );
				exit;
			}
		}
		else $result = -2;
	}
	else $result = -1;
}

//============================================= Language ==================================================

if($_SESSION['_language'] == 'en') {
	$l_title = "ConfOne";
	$l_back = "Back";
	$l_home = "Home";
	$l_ptitle = "Profile";
	$l_cell = "Cellphone :";
	$l_name = "Full Name :";
	$l_nick = "Nick Name :";
	$l_email = "Account / Email :";
	$l_weibo = "Weibo :";
	$l_company = "Company :";
	$l_role = "Title :";
	$l_submit = "Submit";
	$l_res_f2 = "Invalid email format, please try again.";
	$l_res_f1 = "Please enter your fullname and try again.";
	$l_res_1 = "System busy, please try again later.";
}
else if($_SESSION['_language'] == 'zh') {
	$l_title = "会云网";
	$l_back = "返回";
	$l_home = "主页";
	$l_ptitle = "个人信息";
	$l_cell = "手机号码：";
	$l_name = "真实姓名：";
	$l_nick = "用户昵称：";
	$l_email = "注册邮箱：";
	$l_weibo = "微博：";
	$l_company = "公司：";
	$l_role = "职位：";
	$l_submit = "保存";
	$l_res_f2 = "手机号码格式有误，请重试。";
	$l_res_f1 = "真实姓名不能为空，请重试。";
	$l_res_1 = "系统忙碌，请稍候再试。";
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
<div data-role="content" data-theme="c">
<form id="m_profile_form" data-ajax="false" method="post" enctype="multipart/form-data" action="profile.php" name="profile" id="profile" accept-charset="UTF-8">
<label style="font-size:.8em;color:#666"><?php echo $l_email;?></label>
<label><?php echo $_SESSION['_loginUser']->email?></label><br/>
<div style="margin-top:10px;margin-bottom:20px;">
<?php if ( isset($result) ) {
	echo '<div style="margin:auto;margin-bottom:10px;font-size:.8em;font-weight:bold;padding:10px 10px 10px 10px;background-color:#FFDDDD;color:red;"><center>';

	switch ($result) {
    case -2:
        echo $l_res_f2;
        break;
    case -1:
        echo $l_res_f1;
        break;
    case 1:
        echo $l_res_1;
        break;
	}

	echo '</center></div>';
}
?>
<div><label style="font-size:.9em;color:<?php echo (isset($result) && $result == -1) ? "red" : "#666"?>"><?php echo $l_name;?></label><br/>
<input type="text" id="name" name="name" autocorrect="off" autocapitalize="off" 
<?php echo 'value="'.(isset($_POST['name']) ? $_POST['name']: $_SESSION['_loginUser']->fullname).'"'?>/></div>
<div><label style="font-size:.9em;color:#666"><?php echo $l_nick;?></label><br/>
<input type="text" id="uname" name="uname" autocorrect="off" autocapitalize="off" 
<?php echo 'value="'.(isset($_POST['uname']) ? $_POST['uname']: $_SESSION['_loginUser']->uname).'"'?>/></div>
<div><label style="font-size:.9em;color:<?php echo (isset($result) && $result == -2) ? "red" : "#666"?>"><?php echo $l_cell;?></label><br/>
<input type="tel" id="cell" name="cell" autocorrect="off" autocapitalize="off" 
<?php echo 'value="'.(isset($_POST['cell']) ? $_POST['cell']: $_SESSION['_loginUser']->cell).'"'?>/></div>
<div><label style="font-size:.9em;color:#666"><?php echo $l_weibo;?></label><br/>
<input type="url" id="weibo" name="weibo" autocorrect="off" autocapitalize="off" 
<?php echo 'value="'.(isset($_POST['weibo']) ? $_POST['weibo']: $_SESSION['_loginUser']->weibo).'"'?>/></div>
<div><label style="font-size:.9em;color:#666"><?php echo $l_company;?></label><br/>
<input type="text" id="comp" name="comp" autocorrect="off" autocapitalize="off" 
<?php echo 'value="'.(isset($_POST['comp']) ? $_POST['comp']: $_SESSION['_loginUser']->company).'"'?>/></div>
<div><label style="font-size:.9em;color:#666"><?php echo $l_role;?></label><br/>
<input type="text" id="role" name="role" autocorrect="off" autocapitalize="off" 
<?php echo 'value="'.(isset($_POST['role']) ? $_POST['role']: $_SESSION['_loginUser']->title).'"'?>/></div>
</div>
<input type="hidden" id="sub" name="sub" value="1"/>
<input type="submit" value="<?php echo $l_submit?>"/>
</form>
</div>
<?php include_once '../m.common/footer1.inc.php'; ?>
</div>
</body>
</html>