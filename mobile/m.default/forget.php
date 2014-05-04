<?php
include_once ("../_obj/User.php");
include_once ("../utils/CommonUtil.php");
include_once ("../utils/SecurityUtil.php");
include_once ("../utils/MessageUtil.php");

session_start();
if( !isset($_SESSION['_language']) ) CommonUtil::setSessionLanguage();
if( !isset($_SESSION['_userId']) ) SecurityUtil::cookieLogin();
if( isset($_SESSION['_userId']) )
{
	header( 'Location: ../m.home/index.php' );
}

if( isset($_POST['forg']) && !empty($_POST['forg']) )
{
	if( isset($_POST['forg_name']) && !empty($_POST['forg_name']) )
	{
		if( CommonUtil::validateEmailFormat($_POST['forg_name']) )
		{
			if( isset($_POST['check_against']) && !empty($_POST['check_against']) && 
				$_POST['m_check'] == $_SESSION['_register'][$_POST['check_against']] )
			{
				$user = User::getUserByEmail($_POST['forg_name']);

				if($user && isset($user))
				{
					$newPass = CommonUtil::genRandomString(8);
					$result = User::updateUserPasswd($_POST['forg_name'], $newPass);

					if( $result == 0 )
					{
						MessageUtil::resetPasswdEmail($_POST['forg_name'], $newPass, $_SESSION['_language']);
					}
				}
				else {
					$result = -2;
				}
			}
			else {
				$result = -1;
			}
		}
		else {
			$result = -4;
		}
	}
	else {
		$result = -3;
	}
}

//============================================= Language ==================================================

if($_SESSION['_language'] == 'en') {
	$l_title = "ConfOne";
	$l_home = "Home";
	$l_email = "Please enter your email:";
	$l_bu_login = "Continue";
	$l_new_come = "New to ConfOne? ";
	$l_login = "Forget Password?";
	$l_reg = "Register Now!";
	$l_company = "ConfOne";
	$l_res_1 = 'Cannot find user, <a href="register.php">register now</a>';
	$l_res_2 = 'Invalid format, please try again.';
	$l_res_3 = 'Email cannot be empty, please try again';
	$l_ccode = "Check:";
	$l_reg_diff_check = "New Code";
	$l_err_f3 = "Email cannot be empty, please try again.";
	$l_err_f2 = 'The email you enterred is not registerred, <a href="../m.default/register.php>register now</a>."';
	$l_err_f1 = "Invalid check code, please try again.";
	$l_err_f4 = "Invalid email format, please try again.";
	$l_err_3 = "System busy, please try again.";
	$l_succ = "Your password has been reset, please check your email.";
}
else if($_SESSION['_language'] == 'zh') {
	$l_title = "找回密码";
	$l_home = "首页";
	$l_email = "请输入您的帐号/邮件:";
	$l_bu_login = "找回密码";
	$l_new_come = "刚到会云网？";
	$l_registed = "已经注册？";
	$l_login = "马上登陆";
	$l_reg = "手机注册！";
	$l_company = "会云网";
	$l_res_1 = '无法找到用户，<a href="register.php">立刻注册</a>';
	$l_res_2 = '邮件格式有误，请重试';
	$l_res_3 = '邮件不能为空，请重试';
	$l_ccode = "验证码：";
	$l_reg_diff_check = "新验证码";
	$l_err_f3 = "邮箱不能为空，请重试。";
	$l_err_f2 = '您输入的邮箱尚未注册，<a href="../m.default/register.php">马上注册</a>';
	$l_err_f1 = "验证码有误，请重试。";
	$l_err_f4 = "邮箱格式有误，请重试。";
	$l_err_3 = "系统忙，请稍后再试。";
	$l_succ = "您的密码已重置，请从邮箱查收后登陆。";
}

//=========================================================================================================

$imageId = CommonUtil::genRandomString(8);
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
<a href="index.php" data-transition="pop" data-icon="home"><?php echo $l_home?></a>
<h1><?php echo $l_title?></h1>
</div>
<div data-role="content" data-theme="c">
<?php if (isset($result)) 
{
	echo '<div style="margin:auto;margin-bottom:10px;font-size:.8em;font-weight:bold;padding:10px 10px 10px 10px;background-color:'.($result==0 ? '#DDFFDD' : '#FFDDDD').';color:#666;"><center>';

	switch ($result)
	{
		case -1:
			echo $l_err_f1;
			break;
		case -2:
			echo $l_err_f2;
			break;
		case -3:
			echo $l_err_f3;
			break;
		case -4:
			echo $l_err_f4;
			break;
		case 3:
			echo $l_err_3;
			break;
		case 0:
			echo $l_succ;
			break;
	}

	echo '</center></div>';
}
?>
<form data-transition="fade" id="m_login_form" method="post" enctype="multipart/form-data" action="forget.php" name="login_form" id="login_form" accept-charset="UTF-8">
<div class="m_in_div"><br /><label><?php echo $l_email?></label><br />
<input id="forg_name" name="forg_name" type="email" autocorrect="off" autocapitalize="off" value="<?php if(isset($result) && $result!=0) echo $_POST['forg_name'];?>"/>
</div>
<div style="margin:6px 0 20px 0;"><label><?php echo $l_ccode?></label>
<input id="m_check" name="m_check" type="tel" style="width:38px;display:inline;" />
<input type="hidden" value="<?php echo $imageId?>" name="check_against" />
<img style="vertical-align:middle;height:30px;" id="verifyPic" src="../utils/getCode.php?r=<?php echo $imageId?>" />
<a href="javascript:changeCodeImage('verifyPic','<?php echo $imageId?>')"><?php echo $l_reg_diff_check?></a>
</div>
<input id="forg" name="forg" type="hidden" value="1" />
<button type="submit" ><?php echo $l_bu_login?></button>
</form>
<div style="margin-top:50px;"><label><?php echo $l_new_come?></label><a href="../m.default/register.php" data-transition="slidedown"><?php echo $l_reg?></a></div>
<div style="margin:27px 0 20px 0;"><?php echo $l_registed?><a href="../m.default/index.php" data-transition="pop"><?php echo $l_login?></a></div>
</div>
<?php include_once '../m.common/footer.inc.php';?>
</div>
</body>
</html>