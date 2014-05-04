<?php
include_once ("../utils/CommonUtil.php");

session_start();
if(!isset($_SESSION['_language'])) CommonUtil::setSessionLanguage();

//============================================= Language ==================================================

if($_SESSION['_language'] == 'en') {
	$l_title = "ConfOne";
	$l_pinfo = "Profile";
}
else if($_SESSION['_language'] == 'zh') {
	$l_title = "会云网";
	$l_pinfo = "个人介绍";
}

//=========================================================================================================

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; minimum-scale=1.0; user-scalable=0;" />
<meta name="format-detection" content="telephone=no" />
<title><?php echo $l_title?></title>
<link rel="apple-touch-icon" href="../image/cbutton.png" />
<link rel="apple-touch-icon-precomposed" href="../image/cbutton.png" />
<link type="text/css" rel="stylesheet" href="../css/generic.css" />
<link type="text/css" rel="stylesheet" href="../css/conference.css" />
<script type="text/JavaScript" src="../js/common.js"></script>
<script type="text/JavaScript" src="../js/conference.js"></script>
<script type="text/javascript">function linkTo(link){window.location = link}</script>
<style>html{background-color:#589ED0;}</style>
</head>
<body><?php 
$_GET['m_b']='planners.php'; $_GET['m_h']="../home/index.php"; 
include_once '../common/header.inc.php';
unset($_GET['m_b']); unset($_GET['m_h']);?>
<div id="m_main_div">
<div style="padding: 20px 0 0 20px;height:80px;">
<div style="float:left;border:0 none;background: url('../image/home.jpg');background-position:-800px 0px;width:80px;height:80px;"></div>
<div style="padding-left:130px;padding-top:10px;">
<label style="font-weight:bold;">庞小伟（阿迪）</label>
<div style="padding-top:10px;font-size:.9em;"><label>天使湾创投</label><br />
<label>CEO</label>
</div></div>
</div>
<div style="padding: 30px 10px 20px 10px;font-size:.8em;">
<label>1998年至2000年在浙江省兴合集团投资发展部任职。</label><br />
<label>2000年兴合集团给予投资得以创办联商网并担任CEO至2004年。</label><br />
<label>2004年创建E都市并担任CEO至2009年。</label><br />
<label>2010年创办天使湾创投并担任CEO。在可预见的未来，将全身心专注于互联网领域的风险投资事业，致力于用资本的力量来推动中国互联网的应用和发展。</label><br /><br />
<img src="../image/phone_icon.png" align="top" />: 13805735249<br />
<div style="height:5px;"></div>
<img src="../image/email-icon.gif" align="top" />: pxw@tisiwi.com
<div style="height:5px;"></div>
<img src="../image/weibo-icon.png" align="top" />: t.sina.com.cn/pxwpxw
</div>
</div>
<?php include_once '../common/footer.inc.php';?>
</body>
</html>