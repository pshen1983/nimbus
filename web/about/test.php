<?php
include_once ("../_obj/Message.php");
include_once ("../utils/CommonUtil.php");
include_once ("../utils/SecurityUtil.php");

session_start();
if(!isset($_SESSION['_language'])) CommonUtil::setSessionLanguage();
if(!isset($_SESSION['_userId'])) SecurityUtil::cookieLogin();

if( isset($_POST['sub']) ) {
	if( isset($_POST['te_em']) && !empty($_POST['te_em']) )
	{
		if( isset($_POST['te_re']) && !empty($_POST['te_re']) )
		{
			if( CommonUtil::validateEmailFormat($_POST['te_em']) )
			{
				if( Message::create('Apply Test', 1, 1, $_POST['te_em'].'<br /><br />'.$_POST['te_re']) != -1 )
				{
					$result = 0;
				}
				else $result = 4;
			}
			else $result = 2;
		}
		else $result = 3;
	}
	else $result = 1;
}

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
	$l_title="获取内测码 | 会云网";
	$l_contact = "获取内测码";
	$l_email = "邮箱：";
	$l_desc = "告诉我们您参加会云内测的原因吧：";
	$l_bu_submit = "确定";
	$l_err_0 = "感谢您有意参加会云网内测，我们会在24内和您联系，谢谢。";
	$l_err_1 = "请填写邮箱信息";
	$l_err_2 = "电子邮箱格式有误";
	$l_err_3 = "请告诉我们您参加会云内测的原因吧";
	$l_err_4 = "系统暂时忙碌，请稍候再试";
}

//=========================================================================================================
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo $l_title?></title><link rel="shortcut icon" href="../image/favicon.ico" />
<link type="text/css" rel="stylesheet" href="../css/generic.css" />
<script type="text/JavaScript" src="../js/common.js"></script>
<style type="text/css">
html,body{margin:0;padding:0;}
div#contactus{padding:30px 0 0 50%;margin-left:-250px;}
input.email{border:1px solid #ccc;height:25px;padding-left:4px;}
label.title{font-size:.9em;color:#AAA;line-height:1.5em;}
.email
{
	margin-top:2px;
	border: 1px solid #AAA;
	font-weight:bold;
	padding-left:4px;
	-webkit-padding-start:4px;
	-moz-padding-start:4px;
	outline:none;
}
.email:hover
{
	-webkit-box-shadow:0 0,inset 0 0 1px #000;
	-moz-box-shadow:0 0,inset 0 0 1px #000;
	box-shadow:0 0,inset 0 0 1px #000;
}
.email:focus
{
	-webkit-box-shadow:0 0 10px #589ED0,inset 0 0 1px #000;
	-moz-box-shadow:0 0 10px #589ED0,inset 0 0 1px #000;
	box-shadow:0 0 10px #589ED0,inset 0 0 1px #000;
}
button.log
{
	font-size:.8em;
	font-weight:bold;
	background: #ffffff; /* Old browsers */
	background: -moz-linear-gradient(top, #ffffff 0%, #e5e5e5 99%); /* FF3.6+ */
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ffffff), color-stop(99%,#e5e5e5)); /* Chrome,Safari4+ */
	background: -webkit-linear-gradient(top, #ffffff 0%,#e5e5e5 99%); /* Chrome10+,Safari5.1+ */
	background: -o-linear-gradient(top, #ffffff 0%,#e5e5e5 99%); /* Opera11.10+ */
	background: -ms-linear-gradient(top, #ffffff 0%,#e5e5e5 99%); /* IE10+ */
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#e5e5e5',GradientType=0 ); /* IE6-9 */
	background: linear-gradient(top, #ffffff 0%,#e5e5e5 99%); /* W3C */
	border:none!important;
	-webkit-box-shadow:0 0 2px #333,inset -1px -1px 0 #777;
	-moz-box-shadow:0 0 2px #333,inset -1px -1px 0 #777;
	box-shadow:0 0 2px #333,inset -1px -1px 0 #777;
	height:34px;
	width:100px;
}
button.log:hover
{
	background: #ffffff; /* Old browsers */
	background: -moz-linear-gradient(top, #ffffff 0%, #e5e5e5 31%); /* FF3.6+ */
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ffffff), color-stop(31%,#e5e5e5)); /* Chrome,Safari4+ */
	background: -webkit-linear-gradient(top, #ffffff 0%,#e5e5e5 31%); /* Chrome10+,Safari5.1+ */
	background: -o-linear-gradient(top, #ffffff 0%,#e5e5e5 31%); /* Opera11.10+ */
	background: -ms-linear-gradient(top, #ffffff 0%,#e5e5e5 31%); /* IE10+ */
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#e5e5e5',GradientType=0 ); /* IE6-9 */
	background: linear-gradient(top, #ffffff 0%,#e5e5e5 31%); /* W3C */
	cursor:pointer;
}
button.log:active
{
	background: #ffffff; /* Old browsers */
	background: -moz-linear-gradient(top, #ffffff 0%, #e5e5e5 0%); /* FF3.6+ */
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ffffff), color-stop(0%,#e5e5e5)); /* Chrome,Safari4+ */
	background: -webkit-linear-gradient(top, #ffffff 0%,#e5e5e5 0%); /* Chrome10+,Safari5.1+ */
	background: -o-linear-gradient(top, #ffffff 0%,#e5e5e5 0%); /* Opera11.10+ */
	background: -ms-linear-gradient(top, #ffffff 0%,#e5e5e5 0%); /* IE10+ */
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#e5e5e5',GradientType=0 ); /* IE6-9 */
	background: linear-gradient(top, #ffffff 0%,#e5e5e5 0%); /* W3C */
	-webkit-box-shadow:0 0 2px #333;
	-moz-box-shadow:0 0 2px #333;
	box-shadow:0 0 2px #333;
}
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
<div id="contactus">
<form id="test_form" method="post" enctype="multipart/form-data" action="test.php" name="test_form" accept-charset="UTF-8">
<label class="title"><?php echo $l_email?></label><br />
<input style="height:36px;width:300px;" class="email round_border_4" type="text" name="te_em" id="te_em" value="<?php if ( isset($result) && $result!=0 ) echo $_POST['te_em']?>" /><br />
<div style="margin-top:15px;"></div>
<label class="title"><?php echo $l_desc?></label><br />
<input type="hidden" name="sub" id="sub" value="1" />
<textarea style="width:500px;height:200px;padding-top:4px;" class="email round_border_4" name="te_re" id="te_re"><?php if ( isset($result) && $result!=0 ) echo $_POST['te_re']?></textarea>
<?php if(isset($result)) { ?>
<div class="round_border_6" style="margin-top:10px;text-align:center;width:480px;padding:15px;font-size:.8em;font-weight:bold;background-color:<?php echo ($result==0 ? '#DDFFDD;color:green;' : '#FFDDDD;color:red;')?>">
<?php 
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
?>
</div>
<?php } ?>
<div style="margin-top:20px;"><button type="submit" class="log round_border_4" tabindex='4' ><span class="input"><?php echo $l_bu_submit?></span></button></div>
</form>
</div>
</div>
</div>
</div>
</div>
<?php include_once '../common/footer.inc.php';?>
</body>
</html>