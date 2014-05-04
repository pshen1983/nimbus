<?php
include_once ("../utils/configuration.inc.php");
include_once ("../_obj/User.php");
include_once ("../utils/CommonUtil.php");
include_once ("../utils/SecurityUtil.php");

session_start();
if(!isset($_SESSION['_language'])) CommonUtil::setSessionLanguage();

if( isset($_POST['reg_hide']) && $_POST['reg_hide'] == 'buttonclick' )
{
	if( isset($_POST['m_check']) && !empty($_POST['m_check']) && 
		isset($_POST['check_against']) && !empty($_POST['check_against']) && 
		$_POST['m_check'] == $_SESSION['_register'][$_POST['check_against']] )
	{
		$result = User::registerUser( $_POST['m_email'], $_POST['m_passwd'], $_POST['m_fname'] );

		if($result==0)
		{
			if( User::loginUser($_POST['m_email'], $_POST['m_passwd'])==0 )
			{
				SecurityUtil::setCookie($_POST['m_email'], $_POST['m_passwd']);
				header( 'Location: '.$HTTP_BASE.'/m.home/index.php' );
			}
			else {
				header( 'Location: index.php' );
			}

			exit;
		}
	}
	else {
		$result = -1;
	}
}

//============================================= Language ==================================================

if($_SESSION['_language'] == 'en') {
	$l_title = "ConfOne Registration";
	$l_home = "Home";
	$l_email = "Account(Email):";
	$l_passwd = "Password:";
	$l_fname = "Full Name:";
	$l_ccode = "Check:";
	$l_bu_login = "Register";
	$l_new_come = "Already have an account?";
	$l_reg = "Login Now!";
	$l_company = "ConfOne";
	$l_reg_diff_check = "New Code";
	$l_reg_1n = 'Invalid Check Code, please try again.';
	$l_res_1 = 'System busy. Please try again later.';
	$l_res_2 = 'Please fill all fields and try again.';
	$l_res_3 = 'Invalid email format';
	$l_res_4 = 'User already exist, please <a href="../m.default/index.php">Login</a>';
}
else if($_SESSION['_language'] == 'zh') {
	$l_title = "注册会云网";
	$l_home = "首页";
	$l_email = "帐号 / 邮件：";
	$l_passwd = "密码：";
	$l_fname = "真实姓名：";
	$l_ccode = "验证码：";
	$l_bu_login = "注册";
	$l_new_come = "已经注册？";
	$l_reg = "马上登陆！";
	$l_company = "会云网";
	$l_reg_diff_check = "新验证码";
	$l_reg_1n = '验证码有误，请重试';
	$l_res_1 = '系统忙碌，请稍候再试';
	$l_res_2 = '请填写所有信息后重试';
	$l_res_3 = '邮箱格式有误，请重新填写后重试';
	$l_res_4 = '用户已存在，<a href="../m.default/index.php">马上登录</a>';
}

//=========================================================================================================

$imageId = CommonUtil::genRandomString(8);
?>
<!DOCTYPE html>
<html manifest="../m_common/m_confone.manifest">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; minimum-scale=1.0; user-scalable=0;" />
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
<div data-role="header" data-theme="b">
<a href="../m.default/index.php" data-transition="slideup" data-icon="home"><?php echo $l_home?></a>
<h1><?php echo $l_title?></h1>
</div>
<div data-role="content" data-theme="c">
<?php if (isset($_POST['reg_hide']) && !empty($_POST['reg_hide']) && isset($result) && $result!=0) {
	echo '<div style="margin:auto;margin-bottom:10px;font-size:.8em;font-weight:bold;padding:10px 10px 10px 10px;background-color:#FFDDDD;color:red;"><center>';

	switch ($result) {
    case -1:
        echo $l_reg_1n;
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
    case 4:
    	$_SESSION['_mobileReg'] = $_POST['m_email'];
        echo $l_res_4;
        break;
	}

	echo '</center></div>';
}
?>
<form data-transition="pop" id="m_reg_form" method="post" enctype="multipart/form-data" action="<?php echo $M_HTTP_BASE?>/m.default/register.php" name="reg_form" id="reg_form" accept-charset="UTF-8">
<div><label><?php echo $l_fname?></label><br /><input id="m_fname" name="m_fname" type="text"  value="<?php if(isset($_POST['m_fname'])) echo $_POST['m_fname'];?>"/></div>
<div><label><?php echo $l_email?></label><br /><input id="m_email" name="m_email" type="email"  value="
<?php if(isset($_POST['m_email'])) echo $_POST['m_email']; elseif(isset($_GET['e'])) echo $_GET['e'];?>"/></div>
<div><label><?php echo $l_passwd?></label><br /><input id="m_passwd" name="m_passwd" type="password" /></div>
<div style="margin-top:6px;"><label><?php echo $l_ccode?></label>
<input id="m_check" name="m_check" type="tel" style="width:38px;display:inline;" />
<input type="hidden" value="<?php echo $imageId?>" name="check_against" />
<img style="vertical-align:middle;height:30px;" id="verifyPic" src="../utils/getCode.php?r=<?php echo $imageId?>" />
<a href="javascript:changeCodeImage('verifyPic','<?php echo $imageId?>')"><?php echo $l_reg_diff_check?></a>
<input type="hidden" value="buttonclick" name="reg_hide" id="reg_hide" />
</div>
<button type="submit"><?php echo $l_bu_login?></button>
</form>
<div style="margin:27px 0 20px 0"><label><?php echo $l_new_come?></label><a href="../m.default/index.php" data-transition="slideup"><?php echo $l_reg?></a></div>
</div>
<?php include_once '../m.common/footer.inc.php';?>
</body>
</html>