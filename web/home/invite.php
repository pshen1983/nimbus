<?php
include_once ("../_obj/User.php");
include_once ("../_obj/Message.php");
include_once ("../utils/CommonUtil.php");
include_once ("../utils/TestCodeUtil.php");
include_once ("../utils/MessageUtil.php");
include_once ("../utils/SecurityUtil.php");

session_start();
SecurityUtil::checkLogin($_SERVER['REQUEST_URI']);

if( isset($_POST['forg']) && !empty($_POST['forg']) )
{
	if( isset($_POST['forg_name']) && !empty($_POST['forg_name']) )
	{
		if( CommonUtil::validateEmailFormat($_POST['forg_name']) )
		{
			$user = User::getUserByEmail($_POST['forg_name']);

			if($user && isset($user))
			{
				$result = 4;
			}
			else {
				if( isset($_POST['check_against']) && !empty($_POST['check_against']) && 
					$_POST['forg_code'] == $_SESSION['_register'][$_POST['check_against']] )
				{
					$code = TestCodeUtil::createTestCode();
					if($_SESSION['_userId'] == 1)
					{
						MessageUtil::sendTestCode($_POST['forg_name'], $code);
					}
					else {
						MessageUtil::sendTestInvi($_POST['forg_name'], $code);
					}
					$result = 0;
				}
				else $result = 3;
			}
		}
		else $result = 2;
	}
	else $result = 1;
}

$imageId = CommonUtil::genRandomString(8);

//============================================= Language ==================================================

if($_SESSION['_language'] == 'en') {
	$l_title="Aquire Test Code | ConfOne";
	$l_contact = "Aquire Test Code";
	$l_email = "Email:";
	$l_desc = "Please tell us why you want to join ConfOne internal test:";
	$l_bu_submit = "Submit";
	$l_err_0 = "Thank you for applying ConfOne internal test, we will contact you soon.";
	$l_err_1 = "Please enter your Email information";
	$l_err_2 = "Invalid Email formate";
	$l_err_3 = "Please tell us why you want to join ConfOne internal test";
	$l_err_4 = "System is temporarily busy, please try again later";
}
else if($_SESSION['_language'] == 'zh') {
	$l_title="邀请内测 | 会云网";
	$l_contact = "邀请朋友参加内测";
	$l_name = "朋友邮箱：";
	$l_pass = "验证码：";
	$l_reg_diff_check = '看不清，换一个验证码';
	$l_bu_submit = "确定";
	$l_err_0 = "感谢您邀请您的朋友参加会云内测。";
	$l_err_1 = "请填写朋友邮箱信息";
	$l_err_2 = "朋友邮箱格式有误";
	$l_err_3 = "验证码有误，请重试";
	$l_err_4 = "您的朋友已经注册，给他发<a href='../message/index.php'>站内信</a>";
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
<script type="text/JavaScript" src="../js/common.js"></script>
<style type="text/css">
html,body{margin:0;padding:0;}
</style>
</head>
<body>
<div style="width:100%;">
<?php include_once isset($_SESSION['_userId']) ? '../common/header.inc.php' : '../common/header1.inc.php';?>
<div class="stand_width">
<div id="hmain">
<div style="padding:50px;">
<div style="padding:15px;font-weight:bold;"><label><?php echo $l_contact;?></label></div>
<div style="width:98%;margin:auto;border-bottom:1px solid #AAA;"></div>
<div id="log_div">
<form id="log_form" method="post" enctype="multipart/form-data" action="invite.php" name="login_form" accept-charset="UTF-8">
<table>
<tr>
<td colspan="2">
<div class="log_in_div"><label class="log_label"><?php echo $l_name?></label><br />
<input type="text" name="forg_name" class="log_in round_border_4" tabindex='1' value="<?php if( isset($_POST['forg_name']) && !empty($_POST['forg_name']) && $result!=0) echo $_POST['forg_name']?>" /></div></td>
<td rowspan="4" style="vertical-align:top;">
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
		case 0:
			echo $l_err_0;
			break;
		case 1:
			echo $l_err_1;
			break;
		case 2:
			echo $l_err_2;
			break;
		case 3:
			echo $l_err_3;
			break;
		case 4:
			echo $l_err_4;
			break;
	}
	echo '</div>';

}?></td>
</tr>
<tr>
<td style="text-align:right;">
<input type="hidden" name="forg" value="forg" />
<?php if (isset($_GET['page'])) echo "<input type=\"hidden\" value=\"". str_replace("%%", "&", $_GET['page']) ."\" name=\"url\" />"?>
<div class="offset"><button type="submit" class="log round_border_4" tabindex='4' ><span class="input"><?php echo $l_bu_submit?></span></button></div></td>
</tr>
</table></form>
</div>
</div>
</div>
</div>
</div>
<?php include_once '../common/footer.inc.php';?>
</body>
</html>