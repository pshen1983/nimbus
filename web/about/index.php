<?php
include_once ("../utils/CommonUtil.php");
include_once ("../utils/SecurityUtil.php");

session_start();
if(!isset($_SESSION['_language'])) CommonUtil::setSessionLanguage();
if(!isset($_SESSION['_userId'])) SecurityUtil::cookieLogin();

//============================================= Language ==================================================

if($_SESSION['_language'] == 'en') {
	$l_title="Follow us | ConfOne";
	$l_search="You Search For";
	$l_contact = "About us";
	$l_web = "Web: ";
	$l_email = "Email: ";
	$l_qq = "QQ: ";
	$l_about_1 = "ConfOne Product: <span class='info'>ConfOne is your personal event manager in the mobile century.</span>";
	$l_about_2 = "ConfOne Team: <span class='info'>The fonder team has technical background in e-business event web and mobile development as well as consulting. Our goal is to development smart phone market for business use and a platform for professional to connect.</span>";
	$l_about_3 = "ConfOne Partners: <span class='info'>We are looking for event organization team to partner up build up web2.0 and smart phone platform for conferences, seminars, sharing events and so on. To know more about us, please do not be hasitate to <a href='contact.php'>Contact us</a></span>";
	$l_a_1_0 = "Troditional Web: www.confone.com";
	$l_a_1_1 = "Mobile Web: m.confone.com";
	$l_a_1_2 = "Apps：<span class='info'>iPhone/iPad/Andriod  ( Coming soon ... )</span>";
}
else if($_SESSION['_language'] == 'zh') {
	$l_title="关注我们 | 会云网";
	$l_search="您搜索的词条";
	$l_contact = "关于我们";
	$l_web = "网址：";
	$l_email = "邮箱：";
	$l_qq = "Q Q ：";
	$l_about_1 = "会云产品：<span class='info'>会云是移动时代您的个人会议管理平台。 </span>";
	$l_about_2 = "会云团队：<span class='info'>主创人员具备国外展会电子化和移动化的技术背景。专业提供商业会议电子化的解决方案的咨询和开发。目标是推广智能手机的商业应用和专业人士的商务社交平台。 </span>";
	$l_about_3 = "合作伙伴：<span class='info'>会云团队愿意精心打造展会电子化体验。我们期待和专业展会策划和承办方的合作，共同发展和经营展会、学术研讨、领域交流的移动社交平台，带领中国的展会商业进入web2.0时代。如果您想更多的了解我们，别犹豫马上<a href='contact.php'>联系我们</a>。 </span>";
	$l_a_1_0 = "传统网页：www.confone.com";
	$l_a_1_1 = "手机网页：m.confone.com";
	$l_a_1_2 = "原生App：<span class='info'>iPhone/iPad/Andriod （即将推出）</span>";
}

//=========================================================================================================
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo $l_title?></title><link rel="shortcut icon" href="../image/favicon.ico" />
<link type="text/css" rel="stylesheet" href="../css/generic.css" />
<link type="text/css" rel="stylesheet" href="../css/search.css" />
<script type="text/JavaScript" src="../js/common.js"></script>
<style type="text/css">
html,body{margin:0;padding:0;}
div#contactus ul li{padding-top:20px;font-size:.9em;}
.info{font-size:.9em;line-height:2em;}
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
<ul>
<li><label><?php echo $l_about_1?></label>
<ul>
<li><label><?php echo $l_a_1_0?></label></li>
<li><label><?php echo $l_a_1_1?></label></li>
<li><label><?php echo $l_a_1_2?></label></li>
</ul>
</li>
<li><label><?php echo $l_about_2?></label></li>
<li><label><?php echo $l_about_3?></label></li>
</ul>
</div>
</div>
</div>
</div>
</div>
<?php include_once '../common/footer.inc.php';?>
</body>
</html>