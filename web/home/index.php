<?php
include_once ("../_obj/User.php");
include_once ("../_obj/Conference.php");
include_once ("../utils/CommonUtil.php");
include_once ("../utils/SecurityUtil.php");

session_start();
SecurityUtil::checkLogin($_SERVER['REQUEST_URI']);

//============================================= Language ==================================================

if($_SESSION['_language'] == 'en') {
	$l_title = "ConfOne";
	$l_clist = "Events Attending";;
	$l_no_join = 'You are not attending any events. <a class="l_sugg" href="../conference/happening.php">Look around</a>';
	$l_nlist = "Events Near Me";
	$l_time = "Time: ";
	$l_loca = "Location: ";
	$l_join = "Join Now";
	$l_leave = "Disjoin";
	$l_search = "Search ConfOne";
	$l_suggest = "Suggest Events";
	$l_history = "History Events";
	$l_fjoin = "Quick Join an Event";
	$l_fjoinhint = "Event Link Code";
	$l_no_finished = "There is no finished events";
	$l_more = "More ...";
	$l_mess = '<b style="color:#000"></b><br />Page: "http://www.confome.con/demo2011",<br />Then: "demo2011"';

	$l_mess = '<label style="line-height:2.5em;"><b style="color:#000">Link Code</b> is used for an Event Home Page</label><br />
				<label style="line-height:1.5em;">If the Event Page:<br />
				"<span style="text-decoration:underline;">http://www.confome.con/<span style="color:orange">demo2011</span></span>"<br />
				Then: "<span style="color:orange">demo2011</span>" is the Link Code.<br />
				<span style="line-height:2.5em;">Please ask the event organizer for the Link Code.</span></label>';
}
else if($_SESSION['_language'] == 'zh') {
	$l_title = "会云网";
	$l_clist = "我参加的会议活动";
	$l_no_join = '您没有参加任何会议活动，<a class="l_sugg" href="../conference/happening.php">随便看看</a>';
	$l_nlist = "我附近的会议活动";
	$l_time = "时间：";
	$l_loca = "地点：";
	$l_join = "参与活动";
	$l_leave = "退出活动";
	$l_search = "搜索会云网";
	$l_suggest = "会云推荐";
	$l_history = "我过去参加的会议活动";
	$l_fjoin = "快速参加会议活动";
	$l_fjoinhint = "活动链接码";
	$l_no_finished = "没有已经结束的会议活动";
	$l_more = "更多 ...";
	$l_mess = '<label style="line-height:2.5em;"><b style="color:#000">链接码</b>用于访问会议活动的网络主页</label><br />
				<label style="line-height:1.5em;">若活动连接为：<br />
				"<span style="text-decoration:underline;">http://www.confome.con/<span style="color:orange">demo2011</span></span>"<br />
				则："<span style="color:orange">demo2011</span>"为链接码<br />
				<span style="line-height:2.5em;">请向会议活动主办方获得链接码。</span></label>';
}

//=========================================================================================================

$ids='';
$confIds = Conference::getUserJoinedConfs($_SESSION['_userId']);

while($row = mysql_fetch_array($confIds, MYSQL_ASSOC))
{
	$ids = $ids.$row['cid'].', ';
}
$ids = $ids.'0';

$confs = Conference::getRangeBasicConfInfo($ids, 'P');
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo $l_title?></title><link rel="shortcut icon" href="../image/favicon.ico" />
<link type="text/css" rel="stylesheet" href="../css/generic.css" />
<link type="text/css" rel="stylesheet" href="../css/home.css" />
<script type="text/JavaScript" src="../js/common.js"></script>
<script type="text/javascript">
function showLinkInfo()
{
	document.getElementById('linfo').style.visibility='visible';
}
function hideLinkInfo()
{
	document.getElementById('linfo').style.visibility='hidden';
}
</script>
<style type="text/css">
html,body{margin:0;padding:0;}
input#fjoin
{
	border:none!important;
	-webkit-box-shadow:0 0,inset 0 1px 3px #666;
	-moz-box-shadow:0 0,inset 0 1px 3px #666;
	box-shadow:0 0,inset 0 1px 3px #666;
	background-color:white;
	height:30px;
	width:232px;
	padding-left:15px;
	-webkit-padding-start:15px;
	-moz-padding-start:15px;
	outline:none;
}
input#fjoin:focus
{
	-webkit-box-shadow:0 0 20px white,inset 0 1px 2px #666;
	-moz-box-shadow:0 0 20px white,inset 0 1px 2px #666;
	box-shadow:0 0 20px white,inset 0 1px 2px #666;
}
input#fjoin_but
{
	vertical-align: middle;
	background:url(../image/sprite-icons.png);
	background-position:-63px -16px;
	width:15px;
	height:14px;
	border:none!important;
	cursor:pointer;
}
label#fjhint
{
	position:relative;
	top:-23px;
	left:16px;
	color:#aaa;
	height:30px;
	cursor:text;
}
div.ccolor
{
	float:right;
	position:relative;
	top:20px;
	left:-20px;
	width:12px;
	height:12px;
}
div#linfo
{
	visibility:hidden;
	position:absolute;
	top:140px;
	width:240px;
	background-color:#FFFFDD;
	border:1px solid #AAA;
	margin:0 0 10px 0;
	padding:5px 10px 5px 10px;
	word-wrap:break-word
}
a.ctitle
{
	color:blue;
	font-weight:bold;
	font-size:.9em;
}
</style>
</head>
<body>
<div style="width:100%;">
<?php $_GET['f']='h'; include_once '../common/header.inc.php'; unset($_GET['f'])?>
<div class="stand_width">
<div id="hmain">
<div id="hright">
<div style="padding: 20px 0 0 10px;font-size:.8em;">
<label style="font-weight:bold;"><?php echo $l_fjoin?></label><br />
<form style="padding-top:10px;width:100%;" method="post" enctype="multipart/form-data" action="../conference/joinConf.inc.php" name="ceform" id="cdfrom" accept-charset="UTF-8" >
<input id="fjoin" name="fjoin" type="text" class="round_border_20" onfocus="inputOnFocus('fjhint');showLinkInfo();" onblur="inputOffFocus('fjhint', this);hideLinkInfo();"/>
<div>
<label id="fjhint" class="hi" onclick="hideOnFocus(this, 'fjoin');"><?php echo $l_fjoinhint?></label>
<input id="fjoin_but" type="submit" value="" style="position:relative;left:<?php 
if($_SESSION['_language'] == 'zh') { echo 153; }
else if($_SESSION['_language'] == 'en') { echo 125; }?>px;top:-25px;"/>
</div>
<div id="linfo">
<label style="color:#666;line-height:1.3em;"><?php echo $l_mess?></label>
</div>
</form>
</div>
<div style="margin:auto;border-bottom:1px solid #AAA;"></div>
<!-- div style="padding: 20px 0 20px 10px;font-size:.8em;">
<label style="font-weight:bold;"><?php echo $l_suggest?></label><br />
<div id="slink">
<a href="">杭州天使湾创业分享</a><br />
<a href="">杭州东部软件园2011年年会</a><br />
<a href="">上海PGL游戏总决赛</a><br />
<div style="margin-top:10px;"><a href="">更多 ...</a></div>
</div>
</div>
<div style="width:90%;margin:auto;border-bottom:1px solid #AAA;"></div -->
<div style="padding: 20px 0 20px 10px;font-size:.8em;">
<label style="font-weight:bold;"><?php echo $l_history?></label><br />
<div id="slink">
<?php 
$counter = 0;
$cConfs = Conference::getUserFinishedConfList($_SESSION['_userId'], 0, 3);
while($row = mysql_fetch_array($cConfs, MYSQL_ASSOC))
{
$counter++;
?>
<a target="_blank" title="<?php echo $row['name']?>" href="../conference/info_home.php?url=<?php echo $row['url']?>"><?php echo CommonUtil::truncate($row['name'], 15);?></a><br />
<?php }
if($counter > 2) {?>
<div style="margin-top:10px;"><a style="text-decoration:underline" href="../home/history.php"><?php echo $l_more?></a></div>
<?php } else if($counter == 0){
echo '<label style="color:#666">'.$l_no_finished.'</label>';
}?>
</div>
</div>
<div></div>
<!-- div style="width:90%;margin:auto;border-bottom:1px solid #AAA;"></div>
<div style="padding: 20px 0 20px 10px;font-size:.8em;">
<label style="font-weight:bold;">更多内容</label><br />
</div -->
</div>
<div id="hleft">
<div id="in_con">
<label style="font-size:.8em;font-weight:bold;"><?php echo $l_clist?></label>
<div style="border-bottom:1px solid #DDD;width:40%;margin: 5px 0 15px 0;"></div>
<ul class="clist">
<!--  ================================================================================================================ --> 
<?php 
$ind = 0;
foreach ($confs as $conf) 
{
	$sftime = strtotime($conf->stime);
	$sdate = date('Y-m-d', $sftime);
	$stime = date('H:i', $sftime);
	$tprint = $sdate.(($stime!='00:00') ? ' '.$stime : '');

	if( isset($conf->etime) && !empty($conf->etime) )
	{
		$eftime = strtotime($conf->etime);
		$edate = date('Y-m-d', $eftime);
		$etime = date('H:i', $eftime);
		$tprint = $tprint.' - '.$edate.(($etime!='00:00') ? ' '.$etime : '');
	}
?>
<li style="background-color:<?php echo ($ind%2==0) ? "#f1f1f1" : "#ffffff";?>;">
<div class="conf_link">
<!--div class="ccolor" style="background:<?php echo $_SESSION['_color'][$ind];?>;"></div -->
<div class="bdiv"><form method="post" action="../conference/leaveConf.inc.php" enctype="multipart/form-data" name="leform" id="leform" accept-charset="UTF-8" >
<input type="hidden" id="leave" name="leave" value="<?php echo $conf->getUrl()?>" />
<input type="hidden" id="curl" name="curl" value="<?php echo $_SERVER['REQUEST_URI']?>" />
<button type="submit" class="join" style="padding-top:7px;padding-bottom:7px;"><span><?php echo $l_leave?></span></button><br />
</form></div>
<table>
<tr><td><div class="img_f" style="background:url('<?php echo $conf->img?>') no-repeat center center;"></div></td>
<td style="padding-left:20px;">
<a style="color:<?php echo $_SESSION['_color'][$ind];?>" class="ctitle" href="../conference/info_home.php?url=<?php echo $conf->getUrl()?>" target="_blank"><?php echo $conf->name;?></a>
<div style="margin-top:10px;font-size:.8em;color:#333;">
<label><span style="font-weight:bold"><?php echo $l_time;?></span><?php echo $tprint;?></label><br />
<label><span style="font-weight:bold"><?php echo $l_loca;?></span><?php echo $conf->getFaddr();?></label>
</div></td></tr></table></div></li>
<?php 
$ind++;
}
if($ind==0) {
	echo '<div style="margin:10px 0 10px 20px;color:#666"><label>'.$l_no_join.'</label></div>';
}?>
<!--  ================================================================================================================ --> 
</ul>
</div>
<!-- div style="border-top:1px solid #EEE;"></div -->
<div id="near_by">

</div>
</div>
</div>
<div style="width:100%;border-bottom:1px solid #DDD;margin-bottom:10px;"></div>
</div>
</div>
<?php include_once '../common/footer.inc.php';?>
</body>
</html>