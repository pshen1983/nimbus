<?php
include_once ("../utils/configuration.inc.php");
include_once ("../_obj/User.php");
include_once ("../utils/CommonUtil.php");
include_once ("../utils/SecurityUtil.php");

session_start();

if( !isset($_POST['log_name']) || empty($_POST['log_name']) )
{
	$_SESSION['_mCellErr'] = 3;
	header( 'Location: '.$M_HTTP_BASE.'/m.default/index.php' );
	exit;
}

$hasPwd = true;
$result = 0;
$user = null;

// check cellphone format and if the user is registered
if ( CommonUtil::validateEmailFormat($_POST['log_name']) )
{
	$user = User::registeredEmail($_POST['log_name']);
	if($user)
	{
		$hasPwd = (isset($user['passwd']) && !empty($user['passwd'])) ? true : false;
	}
	else {
		header( 'Location: ../m.default/register.php?e='.$_POST['log_name'] );
		exit;
	}
}
else {
	$_SESSION['_mCellErr'] = 2;
	header( 'Location: ../m.default/index.php' );
	exit;
}

// login to the system
if( isset($_POST['log_pass']) && !empty($_POST['log_pass']) )
{
	if( isset($_POST['log_pass1']) && !empty($_POST['log_pass1']) )
	{
		if($_POST['log_pass'] != $_POST['log_pass1'])
		{
			$result = 1;
		}
		else if( !User::insertPassword($_POST['log_name'], $_POST['log_pass']) )
		{
			$result = 2;
		}
	}

	if($result == 0)
	{
		$result = User::loginUser($_POST['log_name'], $_POST['log_pass']);
	
		if($result==0) {
			SecurityUtil::setCookie($_POST['log_name'], $_POST['log_pass']);
			header( 'Location: ../m.home/index.php' );
			exit;
		}
		else $result = 3;
	}
}

if( !isset($_SESSION['_language']) ) CommonUtil::setSessionLanguage();
if( isset($_SESSION['_userId']) )
{
	header( 'Location: ../m.home/index.php' );
}

//============================================= Language ==================================================

if($_SESSION['_language'] == 'en') {
	$l_title = "Login ConfOne";
	$l_back = "Back";
	$l_passwd = "Please enter your password:";
	$l_repasswd = "Please enter password again:";
	$l_bu_login = "Sign in";
	$l_forget = "Forget Password?";
	$l_find_pw = " Retrieve Now!";
	$l_res_1 = 'Password pair are not the same, please try again';
	$l_res_2 = 'System busy please try agian later';
	$l_res_3 = 'Invalid password, please try again';
}
else if($_SESSION['_language'] == 'zh') {
	$l_title = "登录会云网";
	$l_back = "返回";
	$l_passwd = "请输入您的密码:";
	$l_repasswd = "请再次输入密码:";
	$l_bu_login = "进入会云";
	$l_forget = "忘记密码？";
	$l_find_pw = "立刻找回！";
	$l_res_1 = '两次输入的密码不一致，请重试';
	$l_res_2 = '系统忙，请稍候再试';
	$l_res_3 = '帐号邮件/密码有错误，请重试';
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
<a href="../m.default/index.php" data-transition="slidedown" data-icon="arrow-l"><?php echo $l_back?></a>
<h1><?php echo $l_title?></h1>
</div>
<div data-role="content" data-theme="c">
<?php if ( isset($_POST['log_pass']) && !empty($_POST['log_pass']) && $result != 0 )
{
	echo '<div style="font-size:.8em;font-weight:bold;padding:10px 10px 10px 10px;background-color:#FFDDDD;color:#666;"><center>';

	switch ($result) {
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
<form data-transition="fade" id="m_login_form" method="post" enctype="multipart/form-data" action="<?php echo $M_HTTP_BASE?>/m.default/passwd.php" name="login_form" id="login_form" accept-charset="UTF-8">
<input type="hidden" id="log_name" name="log_name" value="<?php echo $_POST['log_name'];?>">
<div class="m_in_div"><br />
<label><?php echo $l_passwd?></label><br />
<input id="log_pass" name="log_pass" type="password" />
</div>
<?php if(!$hasPwd) {?>
<div class="m_in_div">
<label><?php echo $l_repasswd?></label><br />
<input id="log_pass1" name="log_pass1" type="password" />
</div>
<?php }?>
<br /><button type="submit" ><?php echo $l_bu_login?></button>
</form>
<br />
<div style="margin:27px 0 20px 0;"><?php echo $l_forget?><a href="../m.default/forget.php"><?php echo $l_find_pw?></a></div>
</div>
<?php include_once '../m.common/footer.inc.php';?>
</div>
</body>
</html>