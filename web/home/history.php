<?php
include_once ("../_obj/Conference.php");
include_once ("../utils/CommonUtil.php");
include_once ("../utils/SecurityUtil.php");

session_start();
SecurityUtil::checkLogin($_SERVER['REQUEST_URI']);

//============================================= Language ==================================================

if($_SESSION['_language'] == 'en') {
	$l_title="Closed Events | ConfOne";
	$l_history = "Events closed by Me";
}
else if($_SESSION['_language'] == 'zh') {
	$l_title="参加过的活动 | 会云网";
	$l_history = "我参加过的会议活动";
}

//=========================================================================================================

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo $l_title?></title><link rel="shortcut icon" href="../image/favicon.ico" />
<link type="text/css" rel="stylesheet" href="../css/generic.css" />
<link type="text/css" rel="stylesheet" href="../css/conference.css" />
<script type="text/JavaScript" src="../js/common.js"></script>
<style type="text/css">
html,body{margin:0;padding:0;}
a.link{font-size:.9em;text-decoration:none;line-height:1.5em;color:#000080;}
a.link:hover{text-decoration:underline;}
a.link:active{color:#9CC7E5}
li{color:#000080}
</style>
</head>
<body>
<div style="width:100%;">
<?php include_once '../common/header.inc.php';?>
<div class="stand_width">
<div id="hmain">
<div id="hright">
</div>
<div id="hleft">
<div id="in_con">
<label style="font-weight:bold;"><?php echo $l_history?></label>
<div style="margin-top:20px;">
<ul>
<?php 
$counter = 0;
$cConfs = Conference::getUserFinishedConfList($_SESSION['_userId']);
while($row = mysql_fetch_array($cConfs, MYSQL_ASSOC))
{
$counter++;
?>
<li><a class="link" target="_blank" title="<?php echo $row['name']?>" href="../conference/info_home.php?url=<?php echo $row['url']?>"><?php echo CommonUtil::truncate($row['name'], 30);?></a></li>
<?php } ?>
</ul>
</div>
</div>
</div>
</div>
<div style="width:100%;border-bottom:1px solid #DDD;margin-bottom:10px;"></div>
</div>
</div>
<?php include_once '../common/footer.inc.php';?>
</body>
</html>