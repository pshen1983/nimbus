<?php
include_once ("../_obj/User.php");
include_once ("../utils/MessageUtil.php");
include_once ("../utils/CommonUtil.php");
include_once ("../utils/SecurityUtil.php");

session_start();
if(!isset($_SESSION['_language'])) CommonUtil::setSessionLanguage();
if (isset($_SESSION['_userId']) || SecurityUtil::cookieLogin())
{
	header( 'Location: ../home/index.php' );
	exit;
}

$imageId = CommonUtil::genRandomString(8);

if( isset($_POST['forg']) && !empty($_POST['forg']) )
{
	if( isset($_POST['forg_name']) && !empty($_POST['forg_name']) )
	{
		if( CommonUtil::validateEmailFormat($_POST['forg_name']) )
		{
			if( isset($_POST['check_against']) && !empty($_POST['check_against']) && 
				$_POST['forg_code'] == $_SESSION['_register'][$_POST['check_against']] )
			{
				$user = User::getUserByEmail($_POST['forg_name']);
		
				if($user && isset($user))
				{
					$newPass = CommonUtil::genRandomString(8);
		
					$result = User::updateUserPasswd($_POST['forg_name'], $newPass);
		
					if( $result == 0 )
					{
						if( isset($user->email) && !empty($user->email) )
						{
							MessageUtil::resetPasswdEmail($user->email, $newPass, $_SESSION['_language']);
						}

						//code for send cell phone message.
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
	$l_title = "Find Password | ConfOne";
	$l_log_mess0 = "Find Password ";
	$l_log_mess1 = "ConfOne";
	$l_log_mess1_1 = "Back to ConfOne Home Page";
	$l_name = "Account(Email)";
	$l_pass = "Check Code";
	$l_auto = "Remember me";
	$l_bu_login = "Find";
	$l_new_to = "New to ConfOne?";
	$l_reg_now = "Join in Now!";
	$l_reg_diff_check = 'Try a different check code';
	$l_regged = 'Already have an account? ';
	$l_loggin = 'Login Now';
	$l_err_f3 = "Email cannot be empty, please try again.";
	$l_err_f2 = 'The email you enterred is not registerred, <a href="../default/register.php>register now</a>."';
	$l_err_f1 = "Invalid check code, please try again.";
	$l_err_f4 = "Invalid email format, please try again.";
	$l_err_3 = "System busy, please try again.";
	$l_succ = "Your password has been reset, please check your email.";
	$l_info = "ConfOne will send reset password to the Email in your profile, SMS message is coming soon.<br />
			   If you did not provide an Email in your profile, please send text msg --<b>Find ConfOne Password</b>-- to <b>15221696220</b>";
}
else if($_SESSION['_language'] == 'zh') {
	$l_title = "找回密码 | 会云网";
	$l_log_mess0 = "找回密码";
	$l_log_mess1 = "会云网";
	$l_log_mess1_1 = "返回会云网首页";
	$l_name = "注册邮箱";
	$l_pass = "验证码";
	$l_auto = "自动登录";
	$l_bu_login = "找回密码";
	$l_new_to = "刚到会云网？";
	$l_reg_now = "立刻注册！";
	$l_reg_diff_check = '看不清，换一个验证码';
	$l_regged = '已经注册？';
	$l_loggin = '马上登陆';
	$l_err_f3 = "邮箱不能为空，请重试。";
	$l_err_f2 = '您输入的邮箱尚未注册，<a href="../default/register.php">马上注册</a>';
	$l_err_f1 = "验证码有误，请重试。";
	$l_err_f4 = "邮箱格式有误，请重试。";
	$l_err_3 = "系统忙，请稍后再试。";
	$l_succ = "您的密码已重置，请从邮箱查收后登陆。";
	$l_info = '会云目前将重置的密码发送到您个人信息的邮箱中，短信发送即将推出。<br />
			        没有填写邮箱的用户请用注册手机号码发送短信 --<b>找回会云密码</b>-- 至 <b>15221696220</b>。';
}

//=========================================================================================================
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo $l_title?></title><link rel="shortcut icon" href="../image/favicon.ico" />
<link type="text/css" rel="stylesheet" href="../css/generic.css" />
<link type="text/css" rel="stylesheet" href="../css/default.css" />
<script type="text/JavaScript" src="../js/default.js"></script>
<style type="text/css">html,body{margin:0;padding:0;}</style>
</head>
<body>
<div style="width:100%;">
<?php include_once '../common/header1.inc.php';?>
<div class="stand_width">
<div id="log_div">
<label class="head"><?php echo $l_log_mess0?> <a class="home" href="index.php" title="<?php echo $l_log_mess1_1?>"><?php echo $l_log_mess1?></a></label>
<!-- div class="round_border_6" style="margin-top:10px;margin-bottom:10px;text-align:left;line-height:1.5em;padding:15px;font-size:.8em;background-color:#FFFFDD;color:#FF3333;">
<?php echo $l_info?></div -->
<form id="log_form" method="post" enctype="multipart/form-data" action="../default/forget.php" name="login_form" accept-charset="UTF-8">
<table>
<tr>
<td colspan="2">
<div class="log_in_div"><label class="log_label"><?php echo $l_name?></label><br />
<input type="text" name="forg_name" class="log_in round_border_4" tabindex='1' value="<?php if( isset($_POST['forg_name']) && !empty($_POST['forg_name']) && $result!=0) echo $_POST['forg_name']?>" /></div></td>
<td rowspan="4" style="vertical-align:top;">
<table>
<tr>
<td><div id="quest" class="offset"></div></td>
<td><div id="qbody" class="offset"><label><?php echo $l_regged?> <a class="forget" href="../default/login.php"><?php echo $l_loggin?></a></label></div></td>
</tr>
</table>
</td>
</tr>
<tr>
<td colspan="2"><div class="log_in_div"><label class="log_label"><?php echo $l_pass?></label><br />
<input type="text" name="forg_code" class="log_in round_border_4" style="width:70px;" tabindex='2' />
<input type="hidden" value="<?php echo $imageId?>" name="check_against" />
<img style="border:1px solid #DDD;vertical-align:middle;height:32px;margin-left:5px;" id="verifyPic" src="../utils/getCode.php?r=<?php echo $imageId?>" />
<a id="reg_form_diffrent_code" href="javascript:changeCodeImage('verifyPic','<?php echo $imageId?>')"><?php echo $l_reg_diff_check?></a></div>
<?php if( isset($result) ) {
	echo '<div class="round_border_6" style="margin-top:10px;text-align:center;padding:15px;font-size:.8em;background-color:'.(($result==0) ? '#DDFFDD;color:green;' : '#FFDDDD;color:red;').'">';

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
	echo '</div>';

}?></td>
</tr>
<tr>
<td style="text-align:right;">
<input type="hidden" name="forg" value="forg" />
<?php if (isset($_GET['page'])) echo "<input type=\"hidden\" value=\"". str_replace("%%", "&", $_GET['page']) ."\" name=\"url\" />"?>
<div class="offset"><button type="submit" class="log round_border_4" tabindex='4' ><span class="input"><?php echo $l_bu_login?></span></button></div></td>
</tr>
<tr>
<td colspan="2"><div id="go_reg"><a class="go_reg" href="../default/register.php"><?php echo $l_new_to?> <span style="font-weight:bold;"><?php echo $l_reg_now?></span></a></div></td>
</tr>
</table>
</form>
</div>
<div style="width:100%;border-bottom:1px solid #DDD;margin-bottom:10px;"></div>
</div>
<?php include_once '../common/footer.inc.php';?>
</div>
</body>
</html>