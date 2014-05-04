<?php
include_once ("../utils/configuration.inc.php");
include_once ("../_obj/User.php");
include_once ("../utils/CommonUtil.php");
include_once ("../utils/SecurityUtil.php");

session_start();
if(!isset($_SESSION['_language'])) CommonUtil::setSessionLanguage();
if (isset($_SESSION['_userId']) || SecurityUtil::cookieLogin())
{
	header( 'Location: ../home/index.php' );
	exit;
}

if( isset($_POST['log']) && !empty($_POST['log']) )
{
	$result = User::loginUser($_POST['log_name'], $_POST['log_pass']);

	if($result==0)
	{
		if( isset($_POST['remember']) && $_POST['remember'] )
			SecurityUtil::setCookie($_POST['log_name'], $_POST['log_pass']);

		if ( isset($_POST['url']) )
			header( 'Location: '. $HTTP_BASE . $_POST['url'] );
		else if( $_SESSION['_loginUser']->reg_type == 'C' )
			header( 'Location: ../home/index.php' );
		else
			header( 'Location: ../conference/index.php' );

		exit;
	}
}

//============================================= Language ==================================================

if($_SESSION['_language'] == 'en') {
	$l_title = "Login | ConfOne";
	$l_log_mess0 = "Sign in to ";
	$l_log_mess1 = "ConfOne";
	$l_log_mess1_1 = "Back to ConfOne Home Page";
	$l_name = "Account / Email";
	$l_pass = "Password";
	$l_auto = "Remember me";
	$l_bu_login = "Sign in";
	$l_new_to = "New to ConfOne?";
	$l_reg_now = "Join in Now!";
	$l_find = "Find your ";
	$l_or = "or ";
	$l_err_12 = 'Accont does not exist, <a style="color:#3333FF;text-decoration:none;" href="../default/register.php">Register Now!</a>';
	$l_err_3 = 'Invalid email and password combination, please try again';
	$l_err_4 = 'Login email and password cannot be empty';
	$l_err_5 = 'Invalid email format, please try again';
}
else if($_SESSION['_language'] == 'zh') {
	$l_title = "登录 | 会云网";
	$l_log_mess0 = "登录";
	$l_log_mess1 = "会云网";
	$l_log_mess1_1 = "返回会云网首页";
	$l_name = "帐号/邮件";
	$l_pass = "密码";
	$l_auto = "自动登录";
	$l_bu_login = "登录";
	$l_new_to = "刚到会云网？";
	$l_reg_now = "立刻注册！";
	$l_find = "找回您的";
	$l_or = "或";
	$l_err_12 = '用户不存在，<a style="color:#3333FF;text-decoration:none;" href="../default/register.php">立刻注册！</a>';
	$l_err_3 = '登录帐号或密码错误，请重试';
	$l_err_4 = '登录帐号和密码都不能为空白';
	$l_err_5 = '登录帐号格式有误，请重试';
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
<label class="head"><?php echo $l_log_mess0?> <a class="home" href="../default/index.php" title="<?php echo $l_log_mess1_1?>"><?php echo $l_log_mess1?></a></label>
<form id="log_form" method="post" enctype="multipart/form-data" action="../default/login.php" name="login_form" accept-charset="UTF-8">
<table>
<tr>
<td colspan="2"><div class="log_in_div"><label class="log_label"><?php echo $l_name?></label><br /><input type="text" name="log_name" class="log_in round_border_4" tabindex='1' value="<?php if(isset($_POST['log_name'])) echo $_POST['log_name']?>"/></div></td>
<td rowspan="4" style="vertical-align:top;">
<table>
<tr>
<td><div id="quest" class="offset"></div></td>
<td><div id="qbody" class="offset"><label><?php echo $l_find?> <a class="forget" href="../default/forget.php"><?php echo $l_pass?></a></label></div></td>
</tr>
</table>
</td>
</tr>
<tr>
<td colspan="2"><div class="log_in_div"><label class="log_label"><?php echo $l_pass?></label><br /><input type="password" name="log_pass" class="log_in round_border_4" tabindex='2' /></div>
<?php if(isset($result) && $result!=0) { ?>
<div class="round_border_6" style="margin-top:10px;text-align:center;padding:15px;font-size:.8em;font-weight:bold;background-color:#FFDDDD;color:#FF3333;">
<?php 
	switch ($result)
	{
		case 1:
		case 2:
			echo $l_err_12;
			break;
		case 3:
			echo $l_err_3;
			break;
		case 4:
			echo $l_err_4;
			break;
		case 5:
			echo $l_err_5;
			break;
	}
?>
</div>
<?php } ?></td>
</tr>
<tr>
<td>
<div class="offset"><input type="checkbox" name="remember" id="remember" tabindex='3' /><label for="remember" id="check"><?php echo $l_auto?></label></div></td>
<td style="text-align:right;">
<input type="hidden" name="log" value="log" />
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