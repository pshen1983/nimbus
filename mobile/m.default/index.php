<?php
include_once ("../_obj/User.php");
include_once ("../utils/CommonUtil.php");
include_once ("../utils/SecurityUtil.php");

session_start();
if( !isset($_SESSION['_language']) ) CommonUtil::setSessionLanguage();
if( !isset($_SESSION['_userId']) ) SecurityUtil::cookieLogin();
if( isset($_SESSION['_userId']) )
{
	header( 'Location: ../m.home/index.php' );
}

if( isset($_POST['log']) && !empty($_POST['log']) )
{
	$result = User::loginUser($_POST['log_name'], $_POST['log_pass']);

	if($result==0) {
		SecurityUtil::setCookie($_POST['log_name'], $_POST['log_pass']);
		header( 'Location: ../m.home/index.php' );
		exit;
	}
}

//============================================= Language ==================================================

if($_SESSION['_language'] == 'en') {
	$l_title = "ConfOne";
	$l_email = "Please enter your email:";
	$l_bu_login = "Continue";
	$l_new_come = "New to ConfOne? ";
	$l_find_pw = "Forget Password?";
	$l_reg = "Register Now!";
	$l_company = "ConfOne";
	$l_res_1 = 'Cannot find user, <a href="register.php">register now</a>';
	$l_res_2 = 'Invalid format, please try again.';
	$l_res_3 = 'Email cannot be empty, please try again';
}
else if($_SESSION['_language'] == 'zh') {
	$l_title = "会云网";
	$l_email = "请输入您的帐号/邮件:";
	$l_bu_login = "继续";
	$l_new_come = "刚到会云网？";
	$l_find_pw = "忘记密码？";
	$l_reg = "手机注册！";
	$l_company = "会云网";
	$l_res_1 = '无法找到用户，<a href="register.php">立刻注册</a>';
	$l_res_2 = '邮件格式有误，请重试';
	$l_res_3 = '邮件不能为空，请重试';
}

//=========================================================================================================
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
<div data-role="header" data-theme="b">
<div style="padding:5px 0 3px 5px;font-size:1.4em;font-weight:bold;color:#fff;font-family: Helvetica, Arial, sans-serif;"><label><?php echo $l_title?></label></div>
</div>
<div data-role="content" data-theme="c">
<?php if (isset($_SESSION['_mCellErr']) && !empty($_SESSION['_mCellErr'])) {
	
	if($_SESSION['_mCellErr'] == 3)
	{
		echo '<div style="font-size:.8em;font-weight:bold;padding:10px 10px 10px 10px;background-color:#DDFFDD;color:#666;"><center>';
		echo $l_res_3;
		echo '</center></div>';
	}
	else {
		echo '<div style="font-size:.8em;font-weight:bold;padding:10px 10px 10px 10px;background-color:#FFDDDD;color:#666;"><center>';
	
		switch ($_SESSION['_mCellErr']) {
	    case 1:
	        echo $l_res_1;
	        break;
	    case 2:
	        echo $l_res_2;
	        break;
		}
	
		echo '</center></div>';
	}
	unset($_SESSION['_mCellErr']);
}
?>
<form data-transition="slideup" id="m_login_form" method="post" enctype="multipart/form-data" action="passwd.php" name="login_form" id="login_form" accept-charset="UTF-8">
<div class="m_in_div"><br /><label><?php echo $l_email?></label><br />
<input id="log_name" name="log_name" type="email" autocorrect="off" autocapitalize="off" <?php 
if(isset($_SESSION['_mobileReg'])) {
	echo $_SESSION['_mobileReg'];
	unset($_SESSION['_mobileReg']);
}
else if(isset($_POST['log_name']))
	echo $_POST['log_name'];?> /><br />
</div>
<input id="name" name="name" type="hidden" value="1" />
<button type="submit" ><?php echo $l_bu_login?></button>
</form>
<br />
<div style="margin-top:50px;"><label><?php echo $l_new_come?></label><a href="../m.default/register.php" data-transition="slidedown"><?php echo $l_reg?></a></div>
<div style="margin:27px 0 20px 0;"><a href="../m.default/forget.php" data-transition="pop"><?php echo $l_find_pw?></a></div>
</div>
<?php include_once '../m.common/footer.inc.php';?>
</div>
</body>
</html>